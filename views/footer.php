<script type="text/javascript">
    $(document).ready(function () {
        const DOMAIN = "https://vipmanga.com";
        $(".navbar-brand").attr('href', DOMAIN);
        $('.navbar-brand img').css({
            'width': '50px',
            'border-radius': '15px'
        });
        $("a[title='Home']").attr('href', DOMAIN);
        $("a[title='All List']").attr('href', DOMAIN + "/manga-list");
        $("a[title='Latest ']").attr('href', DOMAIN + "/latest-release");
        $("a[title='Contact us']").remove();
        $("a[title='RSS feed']").remove();
        // console.log($("link[rel='canonical']"));
        $("link[rel='canonical']").attr("href", DOMAIN);
        $("a[href='https://readmanhua.net']").attr("href", DOMAIN);


        $('.search').prev().remove();
        $('nav.navbar').css({
            'background-color': '#003399',
            'background-image': 'linear-gradient(#1f78de, #2265a7 60%, #0e5ba9)',
            // 'background-image': '-webkit-linear-gradient(#1f78de, #2265a7 60%, #0e5ba9)',
            // 'background-image': '-o-linear-gradient(#1f78de, #2265a7 60%, #0e5ba9)',
            // 'background-image': 'background-image: -webkit-gradient(linear, left top, left bottom, from(#1f78de), color-stop(60%, #2265a7), to(#0e5ba9))'
        });
        
        $('ul.nav li a').css({
            'color': '#CCFFCC',
            'border-radius': '15px'
        });
        $("li a[title='Support Us']").remove();
        $('.layout-boxed .wrapper').css({
            'color': '#FFF',
            'background-color': '#4ABDAC'
        });
        
        // console.log($('.tag-links'));
        if($('.tag-links')) {
            $(".tag-links").eq(0).prev("dt").eq(0).remove();
            $(".tag-links").eq(0).remove();
        }
        // console.log($("#disqus_thread"));
        $("#disqus_thread").remove();
        if($('.hrule')) {
            $('.hrule').next(".row").remove();
            $('.hrule').remove();
        }

        if($("a[aria-controls='disqus']").text() == "Disqus"){
            $("a[aria-controls='disqus']").parents(".row").eq(0).remove();
        }

        if($(".navbar-custom-menu")) {
            $(".navbar-custom-menu").remove();
        }

        
        var url = window.location.href;
        if(url.indexOf("manga-list") > -1){

        }else if(url.indexOf("latest-release") > -1) {
            //Latest
            $("ul.pagination").parents(".col-xs-12").eq(0).addClass("text-center");
        }else if(url.indexOf("manga/") > -1) {
            $(".col-sm-2").addClass("col-sm-3");
            $(".col-sm-2").removeClass("col-sm-2");
        } else {
            //Index
            // $('.mangalist').parents(".col-sm-12").eq(0).addClass("col-sm-4").removeClass("col-sm-12");
            // $('.hotmanga-header').parents(".col-sm-12").eq(0).addClass('col-sm-8').removeClass('col-sm-12');
            // $('.listmanga-header').parents(".col-sm-12").eq(0).addClass('col-sm-4').removeClass('col-sm-12');
            // $('.panel-default').remove();
            $('.listmanga-header').prev('row').remove();
            $("[style='clear:both']").remove();
            $('.manganews').prev('hr').remove();
            $('.manganews').remove();
            $('.listmanga-header').remove();
            $(".alert-success").remove();
            
            // Hidden right
            $(".panel-default").eq(0).find(".panel-body").html(`<div style="width:300px; height:250px"></div>`);
            $(".panel-default").eq(1).find(".panel-body").html(`<div style="width:300px; height:250px"></div>`);
            $(".panel-default").eq(2).find(".panel-body").html(`<div style="width:300px; height:250px"></div>`);
            $(".panel-default").eq(3).remove();
            $(".panel-default").eq(3).remove();
            
            
            // $('.col-sm-4').eq(0).remove();
            // $('.col-sm-8').removeClass('col-sm-pull-4');
            // $('.col-sm-8').eq(0).addClass('col-sm-12');
            // $('.col-sm-8').eq(0).removeClass('col-sm-8');
            
        }
    });
    
</script>