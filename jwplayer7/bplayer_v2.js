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

var pluginName = 'bPlayer';

var defaults = {
    sources:   [],
    sourcesBk: [],
	sourcesIP4:   [],
    sourcesIP4Bk: [],
	linkV6Check: '',
    tracks:    [],
	pauseAds: '',
    adsArray:  [],
    midArray:  [],
    skipArr:   [],
    isMobile: 0,
    poster: "",
    title: "",
    downloadTitle: "",
    aspectRatio: "16:9",
    numberPreroll: 1,
    skipTime: 20,
    linkBackup: "",
    modelId: 0
};
var playAds = 0, reloadTimes = 0, timeToSeek = 0, currentVolume;
var m3u8 = -1, playTimeout, loadingTimeout, seekTimeOut, skipTimeOut, manualSeek = false, error3bp = 0;
var displaySkip = false, skipTime = 20;
function BPlayer(element, options) {
    this.element = jQuery("#"+element);
    this.options = $.extend({}, defaults, options);
    this._defaults = defaults;
    this._name = pluginName;
    this.playerInstance = jwplayer(element);

    if(typeof(this.options['sources']) === 'object' && this.options['sources'].length > 0) {
        for(var i=0;i<this.options['sources'].length;i++) {
            this.options['sources'][i]['file'] = decodeLink(this.options['sources'][i]['file'], this.options['modelId']);
        }
    }else {
		this.options['sources'] = {file: this.options['sources']};
	}
    if(typeof(this.options['sourcesBk']) === 'object' && this.options['sourcesBk'].length > 0) {
        for(var i=0;i<this.options['sourcesBk'].length;i++) {
            this.options['sourcesBk'][i]['file'] = decodeLink(this.options['sourcesBk'][i]['file'], this.options['modelId']);
        }
    }else {
		this.options['sourcesBk'] = {file: this.options['sourcesBk']};
	}
	if(typeof(this.options['sourcesIP4']) === 'object' && this.options['sourcesIP4'].length > 0) {
        for(var i=0;i<this.options['sourcesIP4'].length;i++) {
            this.options['sourcesIP4'][i]['file'] = decodeLink(this.options['sourcesIP4'][i]['file'], this.options['modelId']);
        }
    }else {
		this.options['sourcesIP4'] = {file: this.options['sourcesIP4']};
	}
	if(typeof(this.options['sourcesIP4Bk']) === 'object' && this.options['sourcesIP4Bk'].length > 0) {
        for(var i=0;i<this.options['sourcesIP4Bk'].length;i++) {
            this.options['sourcesIP4Bk'][i]['file'] = decodeLink(this.options['sourcesIP4Bk'][i]['file'], this.options['modelId']);
        }
    }else {
		this.options['sourcesIP4Bk'] = {file: this.options['sourcesIP4Bk']};
	}
	if(this.options['linkBackup'] != "") {
		this.options['linkBackup']  = decodeLink(this.options['linkBackup'], this.options['modelId']);
	}
    this.sources = this.options['sources'];
    this.sourcesBk = this.options['sourcesBk'];

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
    this.init = function () {
        var self = this;
        if(Math.random() > 0.5) {
            self.adsArray = self.adsArray.reverse();
            self.midArray = self.midArray.reverse();
        }
        //if(self.adsArray.length == 0) playAds = 2;
        if(self.sources.length == 0) {
            reloadTimes = 13;
        }
		
        self.setUpVideo();
    };
	this.checkClientIp = function() {
		var self = this;
		var clientip = getCookie("c_info");
		if(clientip == 6) {
			self.sources = self.options['sourcesIP4'];
			self.sourcesBk = self.options['sourcesIP4Bk'];
		}
	};
    this.showLoadingPlayer = function() {
        var self = this;
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
        var self = this;
		console.log("playAds - "+playAds);
		
        if(playAds < 2) {
            var firstSource = [{file: 'http://bilutv.com/uploads/1.mp4',type:'mp4',label: '360p',default: true}];
            self.playerInstance.setup({
                sources: firstSource,
                tracks: self.tracks,
                image: self.options['poster'],
                title: self.options['title'],
                primary: self.isMobile == 0 ?  "flash" : "html5",
                aspectratio: self.options['aspectRatio'],
                //advertising: self.advertising
            });
			if(self.isMobile == 1) {
				playAds += 1;
			}
			playAds += 1;
            
            self.setUpVideoEvent();
        } else {
			playAds += 1;
			if(self.isMobile == 1) {
				self.hideLoadingPlayer();
				jQuery("#player").html('<iframe width="100%" height="100%" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" src="'+self.sources[0].file+'" frameborder="0" allowfullscreen=""></iframe>');
			} else {
				if(reloadTimes <= 5) {
					if(reloadTimes >= 3) self.sources = self.sourcesBk;
					self.playerInstance.setup({
						sources: self.sources,
						tracks: self.tracks,
						image: self.options['poster'],
						title: self.options['title'],
						primary: "html5",
						aspectratio: self.options['aspectRatio'],
						related: {
							file: "/ajax/filmRelated/"+self.options['modelId'],
							onclick: "link"
						}
					});
					self.setUpVideoEvent();
				} else {
					self.hideLoadingPlayer();
					setTimeout(function() {
						self.playerInstance.remove();
						jQuery("#player").html('<img class="error_loading" src="/images/error_loading.jpg" />');
					}, 100);
				}
			}
        } 
    };
    this.setUpVideoEvent = function() {
        var self = this;
        self.playerInstance.on('ready', function () {
            if(seekTimeOut != null) {clearTimeout(seekTimeOut);}
            if(timeToSeek > 8) seekTimeOut = setTimeout(function() { self.playerInstance.seek(timeToSeek); manualSeek = false;}, 500);
            if(playTimeout != null) {clearTimeout(playTimeout); playTimeout = null;}
            playTimeout = setTimeout(function(){ self.playerInstance.play(true); manualSeek = false;}, 1000);
        }).on('beforePlay', function () {
            var volume = Cookies.get('volume');
            if(volume == undefined)  {self.playerInstance.setVolume(80)}
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
            if(playAds < 3 && self.isMobile == 0) {
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
            timeToSeek = event.offset;
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
            if(time > 8 && (manualSeek == false)) timeToSeek = time;
            var itemPlay = self.playerInstance.getPlaylistItem(self.playerInstance.getPlaylistIndex());
            m3u8 = itemPlay.file.lastIndexOf('.m3u8');
            if(m3u8 == -1 && (reloadTimes < 12)) {
                self.showLoadingPlayer();
				console.log("reloadTimes - "+reloadTimes);
                reloadTimes = reloadTimes + 1;
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

        if(self.sources instanceof Array && self.sources[0]['file'].indexOf("google") !== false) {
            self.playerInstance.addButton(
                    //This portion is what designates the graphic used for the button
                    "/css/image/icon-download.png",
                    'Nhấn vào đây để tải video',
                    function() {
                        var kI = self.playerInstance.getPlaylistItem(),
                                kcQ = self.playerInstance.getCurrentQuality();
                        if(kcQ < 0) { kcQ =0;}
                        var kF = kI.sources[kcQ].file+"?itag="+kcQ+"&type=video/mp4&title="+self.options['downloadTitle'];
                        if(kI.sources[kcQ].file.lastIndexOf('redirector.googlevideo.com') > 0) {
                            var kF = kI.sources[kcQ].file+"&title="+self.options['downloadTitle'];
                        }
                        window.open(kF,'_blank');
                    },
                    "download"
            );
        }
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

    this.downloadVideo = function() {
        var self = this;
        if(self.sources instanceof Array && self.sources[0]['file'].indexOf("google") !== false) {
            var kI = self.playerInstance.getPlaylistItem(),
                    kcQ = self.playerInstance.getCurrentQuality();
            if(kcQ < 0) { kcQ =0;}
            var kF = kI.sources[kcQ].file+"?itag="+kcQ+"&type=video/mp4&title="+self.options['downloadTitle'];
            if(kI.sources[kcQ].file.lastIndexOf('redirector.googlevideo.com') > 0) {
                var kF = kI.sources[kcQ].file+"&title="+self.options['downloadTitle'];
            }
            window.open(kF,'_blank');
        }
    };
    this.setFullscreen = function() {
        this.playerInstance.setFullscreen();
    };
	this.reloadSource = function(link) {
        var self = this;
		var trueLink = decodeLink(link, self.options['modelId']);
        $.getJSON(trueLink, function( data ) {
			if(data.length > 0 && data[0].file!="" && data[0].file != null) {
				self.sources = data;
				timeToSeek = 0;
				self.playerInstance.setup({
					sources: self.sources,
					tracks: self.tracks,
					image: self.options['poster'],
					title: self.options['title'],
					primary: "html5",
					aspectratio: self.options['aspectRatio'],
					//advertising: self.advertising
				});
				self.setUpVideoEvent();
			} else {
				self.showLoadingPlayer();
				self.playerInstance.remove();
				reloadTimes = reloadTimes + 1;
				setTimeout(function(){
					self.setUpVideo();
				}, 1000);
			}
		}).fail(function() {
			self.showLoadingPlayer();
			self.playerInstance.remove();
			reloadTimes = reloadTimes + 1;
			setTimeout(function(){
				self.setUpVideo();
			}, 2000);
		});
    }
}
