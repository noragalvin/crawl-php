<style type="text/css">
	#balloon_left_1, .send-message{display: none !important;}
    #header{background: #00BCD4!important;}
    .form-search button{background: #ff3378!important;}
    #main-menu>.container{background: #FF9800!important;}
    #main-menu .sub-menu{background-color: rgb(249, 173, 63)!important;}
    #main-menu.desktop ul>li>a:hover{color: #000000!important; background: #ffffffa8!important;}
    .block-film .caption, #film-trailer .caption{background: #4CAF50!important;}
    #footer{background: #000000!important;}
    #footer .views-row .copy-right, #footer .views-row a{color: #fff!important;}
    .right-content .block .caption{background: #4CAF50!important;}
    .tags a{background: #00BCD4!important;}
    .tags a:before{border-color: transparent #00BCD4 transparent transparent;}
    #main-menu.fix-nav{background: #00BCD4!important;}
    .meta-data li a {color: #34c5ff!important;}
    .broadcast{padding: 10px; background: #282828;}
    .pagination{text-align: center!important;}
    .form-filter .btn{background: #27a761!important;}
    .form-filter .btn:hover{background: #279aa7!important;}
    .pagination ul li a.disabled{opacity: 1!important;}
    .pagination { background: rgba(0,0,0,.5); padding: 5px; border: 2px dotted #fff601; text-align: center!important; }
    .form-filter{background: rgba(0,0,0,.5);}
    .list-film .film-item .current-status{height: 20px!important;background: #8BC34A!important;overflow: hidden!important;}
	.tags a{background:#00BCD4!important}
	.tags a:before{border-color: transparent #00BCD4 transparent transparent;}
	.tags a:hover:before{transparent #00BCD4 transparent transparent;}
	.list-film .film-item .title .real-name, .list-film .film-item .title .name{color: #00ff0a!important;}
	.list-film .film-item-ver .name{color: #00ff0a!important;}
</style>
<script type='text/javascript'>
//<![CDATA[
    jQuery.cookie = function (a, b, c) {
        if (arguments.length > 1 && "[object Object]" !== String(b)) {
            if (c = jQuery.extend({}, c), null !== b && void 0 !== b || (c.expires = -1), "number" == typeof c.expires) {
                var d = c.expires, e = c.expires = new Date;
                e.setDate(e.getDate() + d)
            }
            return b = String(b), document.cookie = [encodeURIComponent(a), "=", c.raw ? b : encodeURIComponent(b), c.expires ? "; expires=" + c.expires.toUTCString() : "", c.path ? "; path=" + c.path : "", c.domain ? "; domain=" + c.domain : "", c.secure ? "; secure" : ""].join("")
        }
        c = b || {};
        var f, g = c.raw ? function (a) {
            return a
        } : decodeURIComponent;
        return(f = new RegExp("(?:^|; )" + encodeURIComponent(a) + "=([^;]*)").exec(document.cookie)) ? g(f[1]) : null
    };
//]]>
</script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.refresh_cache').click(function(event) {
            $.ajax({
                type: "POST",
                url: "http://videohot.co/ajax.php",
                data: "url="+window.location.href+"&refresh_cache=true",
                success: function(data){
                    location.reload();
                }
            });
            return false;
        });
        
    });
</script>