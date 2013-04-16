/*
 * Author: Digital Zoom Studio
 * Website: http://digitalzoomstudio.net/
 * Portfolio: http://bit.ly/nM4R6u
 * This is not free software.
 * ZoomBox v0.7
 */


//ZoomBox
(function ($) {
    var maincon
        , _conHolder
        , _holder
        , _theItem
        , toload
        ;
    var maincon_orightml = '';
    //window params
    var _w;
    var ww
        , wh
        , cw
        , ch
        , iw
        , ih
        ;
    var busy = false;


    $.fn.zoomBox = function (o) {
        var defaults = {
                settings_paddingHorizontal: '100', settings_paddingVertical: '100'
            },

            o = $.extend(defaults, o);


        //console.log(maincon, busy);
        if (maincon == undefined && busy == false) {
            init();
        }


        function init() {
            $('body').append('<div class="zoombox-maincon disabled"><div class="zoombox-bg"></div></div>');
            maincon = $('body').children('.zoombox-maincon');
            maincon_orightml = maincon.html();
            _w = jQuery(window);
            busy = true;
            setTimeout(function () {
                busy = false;
            }, 500)

            _w.bind('resize', handleResize);
            jQuery(document).on("click", ".zoombox-bg", click_close);
            handleResize();
        }

        function handleResize() {
            ww = _w.width();
            wh = _w.height();
            maincon.css({ 'width': ww, 'height': wh });
        }

        function click_close() {
            var _t = jQuery(this);
            //console.log(_t);
            _conHolder.fadeOut('slow');
            maincon.children('.zoombox-bg').fadeOut('slow');
            setTimeout(function () {
                maincon.html(maincon_orightml);
                maincon.addClass('disabled');
                busy = false;
            }, 1000);
        }

        this.each(function () {
            var cthis = jQuery(this);
            var type = 'image';

            o.settings_paddingHorizontal = parseInt(o.settings_paddingHorizontal, 10);
            o.settings_paddingVertical = parseInt(o.settings_paddingVertical, 10);

            //console.log(cthis, type);
            //if(cthi)

            readyInit();
            function readyInit() {
                if (cthis.get(0) == jQuery('body').get(0)) {
                    return;
                }
                cthis.unbind('click');
                cthis.bind('click', click_item);
            }

            function click_item(e) {
                var _t = jQuery(this);
                var args = {};
                var arg = '';
                var auxtype = 'detect';
                if (_t[0].nodeName == "A") {

                    _t.attr('data-src', _t.attr('href'));
                }


                if (_t.attr('data-src') == undefined) {
                    if (_t.attr('data-sourcemp4') != undefined) {
                        arg = _t.attr('data-sourcemp4');
                    }
                } else {
                    //console.log(arg, args);
                    arg = _t.attr('data-src');
                }

                if (_t.attr('data-type') == undefined) {
                    //args.type = 'image';
                    auxtype = 'detect';
                } else {
                    //args.type = _t.attr('data-type');
                    auxtype = _t.attr('data-type');
                }

                if (arg.indexOf('.jpg') > -1 || arg.indexOf('.gif') > -1 || arg.indexOf('.jpeg') > -1 || arg.indexOf('.png') > -1) {
                    auxtype = 'image';
                }
                if (arg.indexOf('youtube.com/watch?') > -1) {
                    auxtype = 'youtube';
                }
                if (arg.indexOf('vimeo.com/') > -1) {
                    auxtype = 'vimeo';
                }
                if (arg.indexOf('.mp4') > -1 || arg.indexOf('.m4v') > -1) {
                    auxtype = 'video';
                }

                //https://www?v=ylcPqmZ4ESw

                //console.log(auxtype, arg);

                if (_t.attr('data-videotype') == undefined) {
                    args.video_type = 'normal';
                    //auxtype='detect';
                } else {
                    args.video_type = _t.attr('data-type');
                }

                if (_t.attr('data-type') == 'youtube') {
                    auxtype = 'video';
                    args.video_type = 'youtube';
                }
                if (auxtype == 'detect') {
                    auxtype = 'image';
                }

                if (_t.attr('data-sourceogg') != undefined) {
                    args.video_sourceogg = _t.attr('data-sourceogg');
                }
                if (_t.attr('data-sourcewebm') != undefined) {
                    args.video_sourcewebm = _t.attr('data-sourcewebm');
                }
                if (_t.attr('data-width') != undefined) {
                    args.width = _t.attr('data-width');
                }
                if (_t.attr('data-height') != undefined) {
                    args.height = _t.attr('data-height');
                }
                if (_t.attr('data-videowidth') != undefined) {
                    args.video_width = _t.attr('data-videowidth');
                }
                if (_t.attr('data-videoheight') != undefined) {
                    args.video_height = _t.attr('data-videoheight');
                }

                //console.log(arg, auxtype, args); e.preventDefault();
                args.e = e;
                gotoItem(arg, auxtype, args);

            }

            //=======start gotoItem function
            function gotoItem(arg, argtype, otherargs) {
                if (argtype == undefined) {
                    argtype = 'image';
                }

                //===define the event
                var e = otherargs.e;
                if (e != undefined && e.preventDefault != undefined) {
                    e.preventDefault();
                }
                //console.log(arg, argtype, otherargs);

                if (argtype == 'detect') {
                    //to be completed
                }

                var defaults = {
                    type: 'image', video_sourceogg: '', video_title: '', video_type: 'normal', video_previewimg: '', video_width: '598', video_height: '300', video_description: ''
                };
                otherargs = $.extend(defaults, otherargs);
                otherargs.video_width = parseInt(otherargs.video_width, 10);
                otherargs.video_height = parseInt(otherargs.video_height, 10);

                maincon.removeClass('disabled');
                maincon.append('<div class="holder-con"><div class="holder"><div class="preloader"></div></div><div class="close-btn"></div></div>');

                _conHolder = maincon.children('.holder-con');
                _holder = maincon.find('.holder');
                _conHolder.children('.close-btn').bind('click', click_close);
                //console.log(argtype, otherargs, _conHolder);


                _holder.addClass('type-' + argtype);


                _conHolder.css({
                    'top': '0%'
                });

                if (argtype == 'image') {
                    _holder.append('<img class="the-item" src="' + arg + '"/>');
                    _theItem = _holder.children().eq(1);
                    toload = _theItem.get(0);
                    //console.log(_theItem, _theItem.width(), _theItem.attr('style'), _holder);

                    if (toload.complete == true && toload.naturalWidth != 0) {
                        itemLoaded();
                        //setTimeout(itemLoaded, 10000);
                    } else {
                        jQuery(toload).bind('load', itemLoaded);
                    }

                }
                if (argtype == 'ajax') {
                    $.ajax({
                        url: arg,
                        success: function (result) {
                            //if(window.console){ console.log(result) };
                            _holder.append('<div class="the-item ajax-content">' + result + '</div>');
                            _theItem = _holder.children().eq(1);

                            //console.log(_theItem.children().eq(0).width())
                            if (otherargs.width == undefined || otherargs.width == 0) {
                                otherargs.width = _theItem.outerWidth(false);
                                if (_theItem.children().length == 1) {
                                    otherargs.width = _theItem.children().eq(0).outerWidth(false);
                                }
                            }
                            if (otherargs.height == undefined || otherargs.height == 0) {
                                otherargs.height = _theItem.outerHeight(false);
                                if (_theItem.children().length == 1) {
                                    otherargs.height = _theItem.children().eq(0).outerHeight(false);
                                }
                            }
                            //console.log(otherargs.width, otherargs.height)
                            if (otherargs.width != undefined && otherargs.width != 0) {
                                _theItem.css({
                                    'width': otherargs.width, 'height': otherargs.height
                                })
                            }
                            setTimeout(itemLoaded, 1000);
                        }
                    })

                }
                //console.log(arg,argtype);
                if (argtype == 'youtube') {

                    //console.log(arg, argtype, otherargs);
                    //console.log()
                    arg = jQuery.fn.urlParam(arg, "v");
                    var style_w = '';
                    var style_h = '';

                    //console.log(otherargs);
                    if (otherargs.width == undefined || otherargs.width == 0) {
                        otherargs.width = 1024;
                    }
                    if (otherargs.height == undefined || otherargs.height == 0) {
                        otherargs.height = 768;
                    }


                    if (otherargs.width > 0) {
                        style_w = 'width:' + otherargs.width + 'px;';
                    }
                    if (otherargs.height > 0) {
                        style_h = 'height:' + otherargs.height + 'px;';
                    }
                    _holder.append('<iframe class="the-item" src="https://www.youtube.com/embed/' + arg + '?autoplay=1" style="border:0;' + style_w + style_h + '" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
                    _theItem = _holder.children().eq(1);
                    setTimeout(itemLoaded, 1000);

                }
                if (argtype == 'vimeo') {

                    //console.log(arg, argtype, otherargs);
                    //console.log()
                    var aux = arg.split('vimeo.com/');
                    arg = aux[1];
                    var style_w = '';
                    var style_h = '';

                    //console.log(otherargs);
                    if (otherargs.width == undefined || otherargs.width == 0) {
                        otherargs.width = 1024;
                    }
                    if (otherargs.height == undefined || otherargs.height == 0) {
                        otherargs.height = 768;
                    }


                    if (otherargs.width > 0) {
                        style_w = 'width:' + otherargs.width + 'px;';
                    }
                    if (otherargs.height > 0) {
                        style_h = 'height:' + otherargs.height + 'px;';
                    }
                    _holder.append('<iframe class="the-item" src="https://player.vimeo.com/video/' + arg + '?autoplay=1" style="border:0;' + style_w + style_h + '"  webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
                    _theItem = _holder.children().eq(1);
                    setTimeout(itemLoaded, 1000);

                }

                if (argtype == 'iframe') {
                    var style_w = '';
                    var style_h = '';
                    if (otherargs.width > 0) {
                        style_w = 'width:' + otherargs.width + 'px;';
                    }
                    if (otherargs.height > 0) {
                        style_h = 'height:' + otherargs.height + 'px;';
                    }
                    _holder.append('<iframe class="the-item" src="' + arg + '" style="border:0;' + style_w + style_h + '"/>');
                    _theItem = _holder.children().eq(1);
                    setTimeout(itemLoaded, 1000);

                }
                if (argtype == 'video') {
                    if ($.fn.vPlayer != undefined) {
                        var aux = '<div class="the-item"><div class="vplayer-tobe"';
                        if (otherargs.video_title != '') {
                            aux += ' data-videoTitle="' + otherargs.video_title + '"';
                        }
                        aux += ' data-sourcemp4="' + arg + '"';
                        if (otherargs.video_sourceogg != '') {
                            aux += ' data-sourceogg="' + otherargs.video_sourceogg + '"';
                        }
                        if (otherargs.video_previewimg != '') {
                            aux += ' data-img="' + otherargs.video_previewimg + '"';
                        }
                        aux += ' data-type="' + otherargs.video_type + '"';
                        aux += ' style="width:' + otherargs.video_width + 'px; height:' + otherargs.video_height + 'px; opacity:0;"';
                        aux += '>';
                        if (otherargs.video_description != '') {
                            aux += '<div class="videoDescription">' + otherargs.video_description + '</div>';
                        }
                        aux += '</div>';
                        /*
                        aux += '<script>jQuery(document).ready(function($){';
                        aux += 'var videoplayersettingsholder={ responsive:"on", autoplay: "on", design_skin: "' + o.design_skin + '"}; ';
                        aux += '$(".the-item .vplayer-tobe").vPlayer(videoplayersettingsholder);';
                        aux += '});</script>';
                         */
                        var videoplayersettingsholder={ responsive:"on", autoplay: "on" };

                        if(window.zoombox_videoplayersettings!=undefined){
                            if(window.zoombox_videoplayersettings.design_skin!=undefined){
                                videoplayersettingsholder.design_skin = window.zoombox_videoplayersettings.design_skin;
                            }
                            if(window.zoombox_videoplayersettings.settings_swfPath!=undefined){
                                videoplayersettingsholder.settings_swfPath = window.zoombox_videoplayersettings.settings_swfPath;
                            }


                        }

                        _holder.append(aux);
                        _holder.find('.vplayer-tobe').eq(0).vPlayer(videoplayersettingsholder);
                        _theItem = _holder.children().eq(1).children().eq(0);
                        toload = _theItem.get(0);

                        setTimeout(itemLoaded, 1000);
                    } else {
                        console.info('vplayer.js not included');
                        click_close();
                    }
                }

                _conHolder.animate({
                    'top': '50%'
                }, { queue: false, easing: 'swing', duration: 1000});

                function itemLoaded() {
                    iw = _theItem.width();
                    ih = _theItem.height();

                    if (_theItem.get(0) != undefined) {
                        if (_theItem.get(0).naturalWidth != undefined) {
                            iw = _theItem.get(0).naturalWidth;
                        }
                        if (_theItem.get(0).naturalHeight != undefined) {
                            ih = _theItem.get(0).naturalHeight;
                        }
                    }

                    //console.log(_theItem, iw,ih);
                    var orig_iw = iw;
                    var orig_ih = ih;
                    tw = ww - o.settings_paddingHorizontal;
                    th = wh - o.settings_paddingVertical;
                    if (iw > ww - o.settings_paddingHorizontal) {
                        iw = ww - o.settings_paddingHorizontal;
                        ih = (iw / orig_iw) * orig_ih;

                    }
                    if (ih > wh - o.settings_paddingVertical) {
                        ih = wh - o.settings_paddingVertical;
                        iw = (ih / orig_ih) * iw;
                    }
                    //console.log(ww - o.settings_paddingHorizontal, iw, tw);
                    _conHolder.css({
                        'margin-left': -iw / 2, 'margin-top': -ih / 2, 'top': '50%', 'left': '50%'
                    })
                    _holder.css({
                        'width': iw,
                        'height': ih
                    });
                    _theItem.css({
                        'width': iw,
                        'height': ih
                    });
                    if (argtype == 'image') {
                        _holder.addClass('loaded');

                    }
                    setTimeout(function () {
                        jQuery(window).trigger('resize');
                        _holder.addClass('loaded');
                    }, 700);

                };


                if (e != undefined) {
                    e.preventDefault();
                }


            }//end gotoItem

            $.fn.zoomBox.open = function (arg, argtype, otherargs) {
                if (maincon == undefined) {
                    alert('please init zoomBox first')
                    return;
                }
                gotoItem(arg, argtype, otherargs);
            };

            $.fn.zoomBox.close = function () {
                if (maincon == undefined) {
                    alert('please init zoomBox first')
                    return;
                }
                click_close();
            };
            return this;
        }); // end each

    }
})(jQuery);
jQuery(document).ready(function ($) {
    jQuery('body').zoomBox();

    //$('body').zoomBox.open('video/test.m4v', 'video', {video_sourceogg: 'video/test.ogv'});
});

jQuery.fn.urlParam = function (arg, name) {
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(arg);
    return (results !== null) ? results[1] : 0;
}

function is_ios() {
    return ((navigator.platform.indexOf("iPhone") != -1) || (navigator.platform.indexOf("iPod") != -1) || (navigator.platform.indexOf("iPad") != -1)
        );
}

function is_android() {
    //return true;
    var ua = navigator.userAgent.toLowerCase();
    return (ua.indexOf("android") > -1);
}

function is_ie() {
    if (navigator.appVersion.indexOf("MSIE") != -1) {
        return true;
    }
    ;
    return false;
};
function is_firefox() {
    if (navigator.userAgent.indexOf("Firefox") != -1) {
        return true;
    }
    ;
    return false;
};
function is_opera() {
    if (navigator.userAgent.indexOf("Opera") != -1) {
        return true;
    }
    ;
    return false;
};
function is_chrome() {
    return navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
};
function is_safari() {
    return navigator.userAgent.toLowerCase().indexOf('safari') > -1;
};
function version_ie() {
    return parseFloat(navigator.appVersion.split("MSIE")[1]);
};
function version_firefox() {
    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1);
        return(aversion);
    }
    ;
};
function version_opera() {
    if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1);
        return(aversion);
    }
    ;
};
function is_ie8() {
    if (is_ie() && version_ie() < 9) {
        return true;
    }
    return false;
}
function is_ie9() {
    if (is_ie() && version_ie() == 9) {
        return true;
    }
    return false;
}