function cloneObject(obj) {
    if (obj === null || typeof obj !== 'object') {
        return obj;
    }
 
    var temp = obj.constructor(); // give temp the original obj's constructor
    for (var key in obj) {
        temp[key] = cloneObject(obj[key]);
    }
 
    return temp;
}

var defaults = {
	sourceLinks:   [],
    sources:   [],
	sourcesBk2: [],
	sourcesBk3: [],
    tracks:    [],
	pauseAds: '',
    adsArray:  [],
    midArray:  [],
    skipArr:   [],
    isMobile: 0,
    poster: "",
    title: "",
    downloadTitle: "",
    numberPreroll: 1,
    skipTime: 20,
    modelId: 0
};
var currentVolume;
var m3u8 = -1, playTimeout, loadingTimeout, seekTimeOut, skipTimeOut, manualSeek = false;
var displaySkip = false, skipTime = 20;
function BPlayer(element) {
	var self = this;
    this.element = jQuery("#"+element);
    this.playerInstance = jwplayer(element);
    this.init = function (options) {
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;
		this.playAds = 0;
		this.reloadTimes = 0;
		this.timeToSeek = 0;
		this.maxAds = 0;
		this.sourceLinks = [];
		this.sourceLinksBk = [];
		this.sourceLinks = this.getOriginalSources(this.options['sourceLinks']);
		this.sourceLinksBk = this.getOriginalSources(this.options['sourceLinksBk']);
		this.tracks = this.options['tracks'];
		this.midArray = this.options['midArray'];
		this.adsArray = this.options['adsArray'];
		this.isMobile = this.options['isMobile'];
		this.advertising = {
			client: '/jwplayer7/vast.js',
			skipoffset: this.options['skipTime'],
			admessage: 'Quảng cáo sẽ kết thúc sau xx giây.',
			schedule: {
				adbreak1: {
					offset: "pre",
					tag: this.adsArray
				}
			}
		}
		switch(this.options['numberPreroll']) {
			case '2': this.advertising['schedule']['adbreak2'] = {offset: 0,tag: this.midArray.reverse()};break;
			case '3': this.advertising['schedule']['adbreak2'] = {offset: "post",tag: this.midArray.reverse()};break;
			case '4': this.advertising['schedule']['adbreak2'] = {offset: 0,tag: this.midArray.reverse()}; this.advertising['schedule']['adbreak3'] = {offset: "post",tag: this.midArray};break;
			case '5': this.advertising['schedule']['adbreak2'] = {offset: "50%",tag: this.midArray.reverse()};break;
		}
        
		if(self.isMobile == 1) {
			self.maxAds = 0;
		}	
		self.getPlayerLink();
        self.setUpVideo();
    };
	this.getOriginalSources = function(sources) {
		if(typeof(sources) === 'object') {
			for (var index in sources) {
				if(sources[index]["links"].length > 0) {
					for (var itemIndex in sources[index]["links"]) {
						sources[index]["links"][itemIndex]['file'] = decodeLink(sources[index]["links"][itemIndex]['file'], this.options['modelId']);
					}
				}
			} 
			return sources;
		} 
		return null;
	};
	this.getPlayerLink = function() {
		this.sources = [];
		this.sourcesBk2 = [];
		this.sourcesBk3 = [];	
		//console.log(this.sourceLinks);
		var score = {'zi': 1,'vm': 2,'gp': 3,'ga': 4, 'st': 5,'gs': 6, 'op': 7,'sm': 8,'gd': 9,'yt': 10};
		this.sourceLinks.sort(function(a,b) {return (score[a.server] - score[b.server])});
		console.log(this.sourceLinks);
		
		if(this.sourceLinks[0]) this.sources = this.sourceLinks[0]["links"];
		if(this.sourceLinksBk[0]) {
			this.sourcesBk2 = this.sourceLinksBk[0]["links"];
			if(this.sourceLinks[1]) this.sourcesBk3 = this.sourceLinks[1]["links"];
		} else {
			if(this.sourceLinks[1]) this.sourcesBk2 = this.sourceLinks[1]["links"];
			if(this.sourceLinks[2]) this.sourcesBk3 = this.sourceLinks[2]["links"];
		}
	};
	this.changePlayer = function(data) {
		this.sourceLinks = this.getOriginalSources(data.sourceLinks);
		this.sourceLinksBk = this.getOriginalSources(data.sourceLinksBk);
		this.playAds = 0;
		this.reloadTimes = 0;
		this.timeToSeek = 0;
		self.advertising['schedule']['adbreak1'] = {offset: "pre",tag: self.adsArray};
		this.getPlayerLink();
        this.setUpVideo();
	};
    this.showLoadingPlayer = function() {
        $("#box-player").addClass('loading');
        $("#box-player .jw-title-primary").hide();
        $("#box-player .jw-title-secondary").hide();
        if(loadingTimeout != null) {clearTimeout(loadingTimeout);}
        loadingTimeout = setTimeout(function(){
            jQuery("#box-player").removeClass('loading');
        }, 15000);
    };
    this.hideLoadingPlayer = function() {
        jQuery("#box-player").removeClass('loading');
    };
    this.setUpVideo = function() {
		self.playerInstance.remove();
		if(self.sources.length == 0) {
            self.reloadTimes = 13;
        }
		if(Math.random() > 0.5) {
            self.adsArray = self.adsArray.reverse();
            self.midArray = self.midArray.reverse();
        }
        
		if(self.adsArray.length == 0) {
            self.playAds = 3;
        }
		console.log("playAds - "+self.playAds);
        if(self.playAds < self.maxAds) {
			self.playAds += 1;
			if(self.playAds == 2) self.advertising['schedule']['adbreak1'] = {offset: "pre",tag: self.midArray};
			$("#adsmessage").html("Quảng cáo " + self.playAds + "/"+self.maxAds).show();
            var firstSource = [{file: '/uploads/1.mp4',type:'mp4',label: '360p',default: true}];
            self.playerInstance.setup({
                sources: firstSource,
                tracks: self.tracks,
                image: self.options['poster'],
                title: self.options['title'],
				aspectratio: "16:9",
                primary: self.isMobile == 0 ?  "flash" : "html5",
                // advertising: self.advertising
            });
            self.setUpVideoEvent();
        } else {
			self.playAds += 1;
			self.reloadTimes += 1;
			$("#adsmessage").hide();
			if(self.reloadTimes <= 6) {
				if(self.reloadTimes >= 3 && self.sourcesBk2){
                    self.sources = self.sourcesBk2;
                }
				if(self.reloadTimes >= 5 && self.sourcesBk3){
                    var url = window.location.href;    
                    if (url.indexOf('?refresh_cache') == -1){
                        url += '?refresh_cache=1';
                        window.location.href = url; 
                    }
                    self.sources = self.sourcesBk3;
                }
				if(self.sources[0].type == "embed") {
					self.hideLoadingPlayer();
					jQuery("#player").html('<iframe width="100%" height="100%" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" src="'+self.sources[0].file+'" frameborder="0" allowfullscreen=""></iframe>');
				} else {
					self.playerInstance.setup({
						sources: self.sources,
						tracks: self.tracks,
						image: self.options['poster'],
						title: self.options['title'],
						aspectratio: "16:9",
						primary: "html5",
						related: {
							file: "/ajax/filmRelated/"+self.options['modelId'],
							onclick: "link"
						}
					});
					self.setUpVideoEvent();
				}
			} else {
				self.hideLoadingPlayer();
				setTimeout(function() {
					self.playerInstance.remove();
					jQuery("#player").html('<img class="error_loading" src="/images/error_loading.jpg" />');
				}, 100);
			}
        } 
    };
    this.setUpVideoEvent = function() {
        self.playerInstance.on('ready', function () {
            if(seekTimeOut != null) {clearTimeout(seekTimeOut);}
            if(self.timeToSeek > 8) seekTimeOut = setTimeout(function() { self.playerInstance.seek(self.timeToSeek); manualSeek = false;}, 500);
            if(playTimeout != null) {clearTimeout(playTimeout); playTimeout = null;}
            playTimeout = setTimeout(function(){ self.playerInstance.play(true); manualSeek = false;}, 1000);
        }).on('beforePlay', function () {
            var volume = Cookies.get('volume');
            if(volume == undefined || volume == 20)  {self.playerInstance.setVolume(80)}
            else if(volume > 0)  {self.playerInstance.setVolume(volume)}
            var qualityLevels = self.playerInstance.getQualityLevels();
            $("#player .jw-icon-hd").removeClass('quality_360p quality_480p quality_720p quality_1080p').addClass('quality_'+qualityLevels[self.playerInstance.getCurrentQuality()]['label']);
        }).on('play', function () {
			self.playerInstance.setCurrentCaptions(1);
            $("#skipad").hide();
			if($("#player .player_pause_ads").size() == 0 && self.isMobile == 0) {
				$("#player").append('<div class="player_pause_ads">'+self.options['pauseAds']+'</div>');
				$("#player").append('<div class="player_pause_btn"></div>');
				$(".player_pause_btn").click(function() {self.playerInstance.play()});
			}
			clearTimeout(skipTimeOut);
            if(self.playAds < self.maxAds+1 && self.isMobile != 1) {
                self.showLoadingPlayer();
                self.playerInstance.remove();
                self.setUpVideo();
            } else {
                self.hideLoadingPlayer();
                if(currentVolume > 0) {self.playerInstance.setVolume(currentVolume);currentVolume = 0}
            }
			
        }).on('seek', function (event) {
            manualSeek = true;
			var itemPlay = self.playerInstance.getPlaylistItem(self.playerInstance.getPlaylistIndex());
			var m3u8 = itemPlay.file.lastIndexOf('.m3u8');
			if(m3u8 == '-1') {self.showLoadingPlayer();}
            self.timeToSeek = event.offset;
        }).on('seeked', function (event) {
            self.hideLoadingPlayer();
            manualSeek = false;
        }).on('volume', function(event) {
            Cookies.set('volume', event.volume, { expires: 7});
        }).on('complete', function() {
			if(self.isMobile == 0) {
				if($("#btn_autonext").hasClass('active')) {
					self.playNextLink();
				}
			} else {
				self.showLoadingPlayer();
                self.playerInstance.remove();
                self.setUpVideo();
			}
            
        }).on('error', function(message) {
            var time = self.playerInstance.getPosition();
            if(time > 8 && (manualSeek == false)) self.timeToSeek = time;
            var itemPlay = self.playerInstance.getPlaylistItem(self.playerInstance.getPlaylistIndex());
            m3u8 = itemPlay.file.lastIndexOf('.m3u8');
            if(m3u8 == -1 && (self.reloadTimes < 12)) {
                self.showLoadingPlayer();
				console.log("reloadTimes - "+self.reloadTimes);
                setTimeout(function(){
                    self.playerInstance.remove();
                    self.setUpVideo();
                }, 2000);
            } else {
                self.hideLoadingPlayer();
                self.playerInstance.remove();
                self.element.html('<img class="error_loading" src="/images/error_loading.jpg" />');
            }
        }).on('adPlay' , function() {
            self.hideLoadingPlayer();
            currentVolume = self.playerInstance.getVolume();
            setTimeout(function(){ $("#admute").hide();}, 5000);
			clearTimeout(skipTimeOut);
            skipTimeOut  = setTimeout(function() {
                if(displaySkip == false) {
                    $("#skipad").show();
                    $("#skipad").click(function() {
                        $("#skipad").hide();
                        self.playerInstance.remove();
                        self.setUpVideo();
                    });
                    displaySkip = true;
                }
            }, 1000 + skipTime *1000);
            if(Cookies.get('admute')) {
                $("#admute").removeClass('active');
                $("#admute").html('Bật âm quảng cáo');
                self.playerInstance.setMute(true);
            } else {
                self.playerInstance.setVolume(20);
            }
        }).on('adRequest', function(event) {
            skipTime = self.options['skipArr'][event.tag];
            displaySkip = false;
            if(event.adposition == 'mid') {
                skipTime = 15;
            }
        }).on('adTime', function(event) {
            if(event.position > skipTime && (displaySkip == false)) {
                $("#skipad").show();
                setTimeout(function(){ $("#skipad").hide();}, 10000);
                $("#skipad").click(function() {
                    $("#skipad").hide();
                    self.playerInstance.remove();
                    self.setUpVideo();
                });
                displaySkip = true;
            }
        }).on('adComplete', function() {
            $("#skipad").hide();
            displaySkip = true;
            self.playerInstance.setMute(false);
            $("#admute").hide();
        }).on('adSkipped', function() {
            $("#skipad").hide();
            displaySkip = true;
            self.playerInstance.setMute(false);
            $("#admute").hide();
        }).on('levelsChanged', function(event) {
            var qualityLevels = self.playerInstance.getQualityLevels();
            jQuery("#player .jw-icon-hd").removeClass('quality_360p quality_480p quality_720p quality_1080p').addClass('quality_'+qualityLevels[event.currentQuality]['label']);
        });
    };
    this.playNextLink = function () {
        var $elm = $("#list_episodes a.current");
        if ($elm.length) {
            var next_link = $elm.next().attr('href');
            if (typeof(next_link) != 'undefined') {
                // Uncomment to auto next link
                window.location.href = next_link;
            }

        }
    };
    this.setFullscreen = function() {
        this.playerInstance.setFullscreen();
    };
}
