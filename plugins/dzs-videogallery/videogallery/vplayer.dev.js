
/*
 * Author: Digital Zoom Studio
 * Website: http://digitalzoomstudio.net/
 * Portfolio: http://codecanyon.net/user/ZoomIt/portfolio?ref=ZoomIt
 * This is not free software.
 * Video Gallery
 * Version: 6.20
 */

var vgsettings = {
    protocol: 'https'
    , vimeoprotocol: 'https'
};

//VIDEO GALLERY
(function($) {
    $.fn.vGallery = function(o) {

        var defaults = {
                totalWidth: "",
                totalHeight: "",
                menuWidth: 100,
                menuHeight: 350,
                menuSpace: 0, //deprecated replaced by nav_space
                randomise: "off",
                autoplay: "off",
                autoplayNext: "on",
                menu_position: 'right',
                menuitem_width: "200",
                menuitem_height: "71",
                menuitem_space: "0",
                nav_type: "thumbs",
                nav_space: '0',
                transition_type: "slideup",
                design_skin: 'skin_default'
                ,videoplayersettings: ''
                , embedCode: ''
                , shareCode: ''
                , responsive: 'on'
                , responsive_mode: 'auto'
                , cueFirstVideo: 'on'
                , logo: ''
                , logoLink: ''
                , settings_mode: 'normal'

            },
            o = $.extend(defaults, o);

        this.each(function() {

            var cthis = jQuery(this);
            var thisId = $(this)[0].getAttribute('id')
                , classMain
                ;
            var nrChildren = 0;
            var sliderMain
                , sliderCon
                , navMain
                , navCon
                , _adSpace
                , _mainNavigation
                ;
            //gallery dimensions
            var videoWidth
                , videoHeight
                , menuWidth
                , menuHeight
                , totalWidth
                , totalHeight
                , navWidth = 0 // the navCon width
                , navHeight = 0
                , ww
                , wh
                ;
            var nav_main_totalsize = 0 // the total size of the thumbs
                , nav_main_consize = 0 // the total size of the container
                , nav_page_size = 0 // the total size of a page of thumbs
                , nav_max_pages = 0 // max number of pages
                , nav_excess_thumbs = 0 // the total size of the last page of thumbs
                , nav_arrow_size = 40
                ;
            var thumbs_thumb_var = 0
                , thumbs_thumb_var_sec = 0
                , thumbs_total_var = 0
                , thumbs_total_var_sec = 0
                , thumbs_css_main = "top"
                ;
            var backgroundY;
            var used = new Array();
            var content = new Array();
            var currNr = -1
                , nextNr = -1
                , prevNr = -1
                , currPage = 0
                ;
            var arr_inlinecontents = [];

            var _rparent
                , _con
                , ccon
                , currScale = 1
                ;
            var conw = 0;
            var conh = 0;

            var wpos = 0
                , hpos = 0
                , nav_max_pages = 0
                ;
            var lastIndex = 99;

            var busy_transition = false;
            var firsttime = true;
            var embed_opened = false
                , share_opened = false
                , ad_playing = false
                ;

            var i = 0;

            var aux = 0
                , aux1 = 0
                ;

            var down_x = 0
                , up_x = 0;

            o.menuitem_width = parseInt(o.menuitem_width, 10);
            o.menuitem_height = parseInt(o.menuitem_height, 10);
            o.menuitem_space = parseInt(o.menuitem_space, 10);
            o.nav_space = parseInt(o.nav_space, 10);
            //console.log(o);

            nrChildren = jQuery(this).children().length;




            ccon = cthis.parent();
            if (cthis.parent().hasClass('videogallery-con')) {
                _rparent = cthis.parent().parent();
                if (cthis.parent().parent().hasClass('gallery-precon')) {
                    _rparent = cthis.parent().parent().parent();
                }
                ccon = cthis.parent();
                //_rparent = cthis.parent();
            } else {
                _rparent = cthis.parent();

            }

            //==some sanitizing of the videoWidth and videoHeight parameters

            if (String(o.totalWidth).indexOf('%') > -1) {
                if (o.responsive_mode == 'auto') {
                    o.responsive_mode = 'fullx';
                    totalWidth = _rparent.width();
                    //console.log(totalWidth);
                }
            } else {
                o.totalWidth = parseInt(o.totalWidth, 10);
            }



            if (String(o.totalHeight).indexOf('%') > -1) {
                if (o.responsive_mode == 'auto') {
                    o.responsive_mode = 'fully';
                }
                if (o.responsive_mode.indexOf('fullx') > -1) {
                    o.responsive_mode += ' fully';
                }
            } else {
                o.totalHeight = parseInt(o.totalHeight, 10);
            }

            if (o.totalWidth=='' || o.totalWidth == 0) {
                totalWidth = cthis.width();
            } else {
                totalWidth = o.totalWidth;
            }

            if (o.totalHeight=='' || o.totalHeight == 0) {
                totalHeight = cthis.height();
            } else {
                totalHeight = o.totalHeight;
            }


            //=== some sanitizing
            if(isNaN(totalWidth)){
                totalWidth = 800;
            }
            if(isNaN(totalHeight)){
                totalHeight = 400;
            }

            cthis.get(0).var_scale = 1;

            backgroundY = o.backgroundY;
            menuWidth = o.menuWidth;
            menuHeight = o.menuHeight;

            cthis.addClass('mode-' + o.settings_mode);

            if (typeof(cthis.attr('class')) == 'string') {
                mainClass = cthis.attr('class');
            } else {
                mainClass = cthis.get(0).className;
            }
            if (mainClass.indexOf('skin_') == -1) {
                cthis.addClass(o.design_skin);
            }

            //console.log();
            if (o.videoplayersettings.design_skin == 'sameasgallery') {
                o.videoplayersettings.design_skin = o.design_skin;
            }

            o.videoplayersettings.videoGalleryCon = cthis.get(0);

            if(o.responsive_mode=='auto'){
                o.responsive_mode='pseudo';
            }
            if (o.settings_mode == 'wall') {
                o.responsive = 'off';
            }
            //console.log(_rparent);

            if (o.settings_mode != 'wall') {
                cthis.parent().children('.preloader').fadeOut('fast');
                if (cthis.css('opacity') == 0) {
                    cthis.animate({
                        'opacity': 1
                    }, 1000);
                }
            }

            for (i = 0; i < nrChildren; i++) {
                content[i] = jQuery(this).children().eq(i);
                //sliderCon.append(content[i]);
                if (o.randomise == 'on')
                    randomise(0, nrChildren);
                else
                    used[i] = i;
            }

            cthis.append('<div class="sliderMain"><div class="sliderCon"></div></div>')
            cthis.append('<div class="main-navigation"><div class="navMain"><div class="navCon"></div></div></div>')
            cthis.append('<div class="gallery-buttons"></div>');
            cthis.append('<div class="adSpace"></div>');
            if (o.design_shadow == 'on') {
                cthis.append('<div class="shadow"></div>');
            }

            sliderMain = cthis.find('.sliderMain');
            sliderCon = cthis.find('.sliderCon');
            _adSpace = cthis.find('.adSpace');
            _mainNavigation = cthis.find('.main-navigation');


            if (is_ie8()) {
                jQuery('html').addClass('ie8-or-lower');
                cthis.addClass('ie8-or-lower');
                sliderCon.addClass('sliderCon-ie8');
            }
            if (supports_translate()) {
                jQuery('html').addClass('supports-translate');
            }

            navMain = cthis.find('.navMain');
            navCon = cthis.find('.navCon');


            //console.log(o.settings_mode)
            if (o.settings_mode == 'normal') {
                for (i = 0; i < nrChildren; i++) {
                    var desc = cthis.children().eq(used[i]).find('.menuDescription').html();
                    cthis.children().eq(used[i]).find('.menuDescription').remove();
                    if (desc == null) {
                        continue;
                    }
                    if (desc.indexOf('{ytthumb}') > -1) {
                        desc = desc.split("{ytthumb}").join('<img src="' + vgsettings.protocol + '://img.youtube.com/vi/' + cthis.children().eq(used[i]).attr('data-src') + '/0.jpg" class="imgblock"/>');
                    }
                    if (desc.indexOf('{ytthumbimg}') > -1) {
                        desc = desc.split("{ytthumbimg}").join(vgsettings.protocol + '://img.youtube.com/vi/' + cthis.children().eq(used[i]).attr('data-src') + '/0.jpg');
                    }
                    navCon.append('<div><div class="navigationThumb-content">' + desc + '</div></div>')
                    navCon.children().eq(i).addClass("navigationThumb");
                    navCon.children().eq(i).css({
                        'width': o.menuitem_width,
                        'height': o.menuitem_height
                    });

                    navCon.children().eq(i).click(handleButton);

                    if (o.menu_position == 'right' || o.menu_position == 'left') {
                        navCon.children().eq(i).css({
                            'top': hpos
                        })
                    } else {
                        navCon.children().eq(i).css({
                            'left': wpos
                        })

                    }

                    hpos += o.menuitem_height + o.menuitem_space;
                    wpos += o.menuitem_width + o.menuitem_space;
                }

            }
            if (o.settings_mode == 'rotator') {
                navMain.append('<div class="rotator-btn-gotoNext"></div><div class="rotator-btn-gotoPrev"></div>');
                navMain.append('<div class="descriptionsCon"></div>');
                navMain.children('.rotator-btn-gotoNext').bind('click', gotoNext);
                navMain.children('.rotator-btn-gotoPrev').bind('click', gotoPrev);
            }



            for (i = 0; i < nrChildren; i++) {
                sliderCon.append(content[used[i]]);
            }


            for (i = 0; i < nrChildren; i++) {
                var autoplaysw = 'off';
                if (i == 0 && o.autoplay == 'on')
                    autoplaysw = 'on';
                if (is_ios()) {

                } else {

                }
            }

            navMain.addClass('menu-' + o.menu_position);

            if (o.nav_type == 'thumbsandarrows') {
                _mainNavigation.append('<div class="thumbs-arrow-left inactive"></div>');
                _mainNavigation.append('<div class="thumbs-arrow-right"></div>');
                //navCon.addClass('static');



                _mainNavigation.find('.thumbs-arrow-left').bind('click', gotoPrevPage);
                _mainNavigation.find('.thumbs-arrow-right').bind('click', gotoNextPage);
            }

            //(o.menuitem_width + o.menuitem_space) * nrChildren

            if (is_ios()){
                navMain.css('overflow', 'auto');
            };

            var hpos = 0;

            // === if there is only one video we hide the nav
            if (nrChildren == 1) {
                cthis.css({
                    'width': videoWidth
                })
                totalWidth = videoWidth;
                navMain.hide();
            }

            if (o.settings_mode == 'rotator3d') {

                sliderCon.children().each(function() {
                    var _t = jQuery(this);
                    _t.addClass('rotator3d-item');
                    _t.css({'width': videoWidth, 'height': videoHeight})
                    _t.append('<img class="previewImg" src="' + _t.attr('data-previewimg') + '"/>');
                    _t.children('.previewImg').bind('click', mod_rotator3d_clickPreviewImg);

                })
            }
            if (o.settings_mode == 'wall') {

                jQuery('body').zoomBox();

                if (cthis.parent().hasClass('videogallery-con')) {
                    cthis.parent().css({'width': 'auto', 'height': 'auto'})
                }
                cthis.css({'width': 'auto', 'height': 'auto'});
                //return;
                sliderCon.children().each(function() {
                    //====each item
                    var _t = jQuery(this);

                    _t.addClass('vgwall-item').addClass('clearfix');
                    _t.css({'width': o.menuitem_width, 'height': 'auto', 'position': 'relative', 'top': 'auto', 'left': 'auto'});
                    //console.log(totalWidth, totalHeight);
                    _t.attr('data-videoWidth', totalWidth);
                    _t.attr('data-videoHeight', totalHeight);
                    var aux9 = 'width:' + o.menuitem_width + 'px; height:' + o.menuitem_height + 'px';
                    if (_t.attr('data-videoTitle') != undefined && _t.attr('data-videoTitle') != '') {
                        _t.prepend('<div class="videoTitle">' + _t.attr('data-videoTitle') + '</div>');
                    }
                    if (_t.attr('data-previewimg') != undefined) {
                        var aux2 = _t.attr('data-previewimg');

                        if (aux2 != undefined && aux2.indexOf('{ytthumbimg}') > -1) {
                            //console.log(_t.attr('data-src'));
                            aux2 = aux2.split("{ytthumbimg}").join(vgsettings.protocol + '://img.youtube.com/vi/' + _t.attr('data-src') + '/0.jpg');
                        }

                        _t.prepend('<img class="previewImg" style="" src="' + aux2 + '"/>');

                    }

                    _t.zoomBox();
                    //console.log(jQuery.fn.masonry);
                    setTimeout(function() {
                        if (jQuery.fn.masonry != undefined) {
                            sliderCon.masonry({
                                columnWidth: 10
                                , gutterWidth: 5
                                , containerStyle: {position: 'relative'}
                                , isFitWidth: false
                                , isAnimated: true
                            });
                            cthis.parent().children('.preloader').fadeOut('slow');
                            if (cthis.css('opacity') == 0) {
                                cthis.animate({
                                    'opacity': 1
                                }, {queue:false, duration: 1000});
                            }
                        } else {
                            alert('vplayer.js - warning: masonry not included')
                        }
                        setTimeout(handleResize, 1000);
                    }, 3000);

                    _t.zoomBox();
                });
            }

            // --- go to video 0 <<<< the start of the gallery
            cthis.get(0).videoEnd = handleVideoEnd;
            cthis.get(0).turnFullscreen = turnFullscreen;
            //console.log(cthis);
            cthis.get(0).external_handle_stopCurrVideo = handle_stopCurrVideo;


            if (o.logo != undefined && o.logo != '') {
                cthis.append('<img class="the-logo" src="' + o.logo + '"/>');
                if (o.logoLink != undefined && o.logoLink != '') {
                    cthis.children('.the-logo').css('cursor', 'pointer');
                    cthis.children('.the-logo').click(function() {
                        window.open(o.logoLink);
                    });
                }
            }
            var _gbuttons = cthis.children('.gallery-buttons');
            if (o.embedCode != '') {
                //console.log(_gbuttons);
                _gbuttons.append('<div class="embed-button"><div class="handle"></div><div class="contentbox" style="display:none;"><textarea class="thetext">' + o.embedCode + '</textarea></div></div>');
                _gbuttons.find('.embed-button .handle').click(click_embedhandle)
                _gbuttons.find('.embed-button .contentbox').css({
                    'right': 50
                })
            }
            if (o.shareCode != '') {
                //console.log(_gbuttons);
                _gbuttons.append('<div class="share-button"><div class="handle"></div><div class="contentbox" style="display:none;"><div class="thetext">' + o.shareCode + '</div></div></div>');
                _gbuttons.find('.share-button .handle').click(click_sharehandle)
                _gbuttons.find('.share-button .contentbox').css({
                    'right': 50
                })
            }
            if (o.menu_position == 'right') {
                // console.log(navMain)
                _gbuttons.css({
                    'right': (o.menuitem_width + o.nav_space)
                });
                if (cthis.find('.the-logo').length > 0) {
                    cthis.find('.the-logo').css({
                        'right': (o.menuitem_width + o.nav_space + 60)
                    });
                }
            }
            if (o.menu_position == 'top') {
                // console.log(navMain)
                _gbuttons.css({
                    'top': (o.menuitem_height + o.nav_space)
                });
                if (cthis.find('.the-logo').length > 0) {
                    cthis.find('.the-logo').css({
                        'top': (o.menuitem_height + o.nav_space + 10)
                        , 'right': (60)
                    });
                }
            }

            calculateDims();
            if (o.responsive == 'on' || o.settings_mode=='wall') {
                jQuery(window).bind('resize', handleResize);
                handleResize();
            }
            if (o.settings_mode != 'wall') {
                gotoItem(0);
            }


            function gotoNextPage() {
                var tempPage = currPage;

                tempPage++;
                gotoPage(tempPage);

            }

            function gotoPrevPage() {
                if (currPage == 0)
                    return;

                currPage--;
                gotoPage(currPage);

            }
            function gotoPage(arg) {
                if (arg >= nav_max_pages || o.nav_type != 'thumbsandarrows')
                    return;
                thumbsSlider = navCon;

                _mainNavigation.find('.thumbs-arrow-left').removeClass('inactive');
                _mainNavigation.find('.thumbs-arrow-right').removeClass('inactive');
                if (arg == 0) {
                    _mainNavigation.find('.thumbs-arrow-left').addClass('inactive');
                }
                if (arg == nav_max_pages - 1) {
                    _mainNavigation.find('.thumbs-arrow-right').addClass('inactive');
                }

                if (arg == nav_max_pages - 1) {

                    if (o.menu_position == "right" || o.menu_position == "left")
                        thumbsSlider.animate({
                            'top': (nav_page_size * -(nav_max_pages - 2)) - nav_excess_thumbs
                        }, {
                            duration: 400,
                            queue: false
                        });

                    if (o.menu_position == "bottom" || o.menu_position == "top")
                        thumbsSlider.animate({
                            'left': (nav_page_size * -(nav_max_pages - 2)) - nav_excess_thumbs
                        }, {
                            duration: 400,
                            queue: false
                        });

                } else {

                    if (o.menu_position == "right" || o.menu_position == "left")
                        thumbsSlider.animate({
                            'top': nav_page_size * -arg
                        }, {
                            duration: 400,
                            queue: false
                        });

                    if (o.menu_position == "bottom" || o.menu_position == "top")
                        thumbsSlider.animate({
                            'left': nav_page_size * -arg
                        }, {
                            duration: 400,
                            queue: false
                        });

                }

                currPage = arg;
            }
            function calculateDims(){
                totalWidth = cthis.width();
                totalHeight = cthis.height();

                //console.log(o.responsive_mode);
                if (o.responsive_mode.indexOf('full') > -1){
                    //videoWidth = _rparent.width();
                    if (o.responsive_mode.indexOf('fullx') > -1) {
                        totalWidth = _rparent.width();
                    }
                    if (o.responsive_mode.indexOf('fully') > -1) {
                        totalHeight = _rparent.height();
                    }
                }

                //return;
                //console.log(totalWidth, totalHeight, _rparent.height());

                if (o.settings_mode != 'wall') {
                    cthis.css({
                        'width': totalWidth,
                        'height': totalHeight
                    })
                    if (cthis.parent().hasClass('videogallery-con') && o.responsive!='on') {
                        cthis.parent().css({
                            'width': totalWidth,
                            'height': totalHeight
                        });
                    }
                }


                videoWidth = totalWidth;
                videoHeight = totalHeight;

                if ((o.menu_position == 'right' || o.menu_position == 'left') && nrChildren > 1) {
                    //console.log(o.nav_space);
                    videoWidth -= (o.menuitem_width + o.nav_space);
                }
                if ((o.menu_position == 'bottom' || o.menu_position == 'top') && nrChildren > 1) {
                    videoHeight -= (o.menuitem_height + o.nav_space);
                }



                if (o.settings_mode == 'rotator3d') {
                    videoWidth = totalWidth / 2;
                    videoHeight = totalHeight * 0.8;
                    o.menuitem_width = 0;
                    o.menuitem_height = 0;
                    o.menuitem_space = 0;
                    o.transition_type = 'rotator3d';
                }




                hpos = 0;
                for (i = 0; i < nrChildren; i++) {
                    //if(is_ios())	break;

                    sliderCon.children().eq(i).css({
                        'position': 'absolute'
                        , 'top': hpos
                        , 'left': 0
                    })
                    hpos += totalHeight;
                }

                if (o.settings_mode != 'wall') {
                    sliderMain.css({
                        'width': videoWidth,
                        'height': videoHeight
                    })
                }
                if (o.settings_mode == 'rotator3d') {
                    sliderMain.css({
                        'width': totalWidth,
                        'height': totalHeight
                    })
                    sliderCon.children().css({
                        'width': videoWidth,
                        'height': videoHeight
                    })
                }
                if (o.menu_position == 'right') {
                    _mainNavigation.css({
                        'width': o.menuitem_width,
                        'height': totalHeight,
                        'left': videoWidth + o.nav_space
                    })
                }
                if (o.menu_position == 'left') {
                    _mainNavigation.css({
                        'width': o.menuitem_width,
                        'height': totalHeight,
                        'left': 0
                    })
                    sliderMain.css({
                        'left': o.menuitem_width + o.nav_space
                    })
                }
                if (o.menu_position == 'bottom') {
                    _mainNavigation.css({
                        'width': totalWidth,
                        'height': o.menuitem_height,
                        'top': videoHeight + o.nav_space,
                        'left': 0
                    })
                }
                if (o.menu_position == 'top') {
                    _mainNavigation.css({
                        'width': totalWidth,
                        'height': o.menuitem_height,
                        'top': 0,
                        'left': 0
                    })
                    sliderMain.css({
                        'top': o.menuitem_height
                    })
                }



                if (o.nav_type == 'thumbsandarrows') {

                    navWidth = (totalWidth - nav_arrow_size * 2);
                    navHeight = (totalHeight - nav_arrow_size * 2);




                    if (o.menu_position == 'bottom' || o.menu_position == 'top') {
                        thumbs_thumb_var = o.menuitem_width;
                        thumbs_thumb_var_sec = o.menuitem_height;
                        thumbs_total_var = totalWidth;
                        thumbs_total_var_sec = totalHeight;
                        thumbs_css_main = 'left';
                        navMain.css({'left': nav_arrow_size, 'width': navWidth, 'height': '100%'});


                        _mainNavigation.children('.thumbs-arrow-left').css({'left': nav_arrow_size / 2});
                        _mainNavigation.children('.thumbs-arrow-right').css({'left': 'auto', 'right': nav_arrow_size / 2});
                        nav_main_consize = navWidth;
                    }
                    if (o.menu_position == 'left' || o.menu_position == 'right') {
                        thumbs_thumb_var = o.menuitem_height;
                        thumbs_thumb_var_sec = o.menuitem_width;
                        thumbs_total_var = totalHeight;
                        thumbs_total_var_sec = totalWidth;
                        thumbs_css_main = 'top';
                        navMain.css({'top': nav_arrow_size, 'height': navHeight, 'width': '100%'});
                        _mainNavigation.children('.thumbs-arrow-left').css({'top': nav_arrow_size / 2});
                        _mainNavigation.children('.thumbs-arrow-right').css({'top': 'auto', 'bottom': nav_arrow_size / 2 - 10});
                        nav_main_consize = navHeight;
                    }
                    nav_main_totalsize = nrChildren * thumbs_thumb_var + (nrChildren - 1) * o.menuitem_space;
                    aux1 = (((((thumbs_total_var - nav_arrow_size * 2) / (thumbs_thumb_var + o.menuitem_space)) * (thumbs_thumb_var + o.menuitem_space)))) - ((((parseInt((thumbs_total_var - nav_arrow_size * 2) / (thumbs_thumb_var + o.menuitem_space))) * (thumbs_thumb_var + o.menuitem_space))));

                    nav_page_size = thumbs_total_var - nav_arrow_size * 2 - aux1;
                    nav_max_pages = nav_main_totalsize / nav_page_size;
                    thumbs_per_page = Math.floor(nav_page_size / (thumbs_thumb_var + o.menuitem_space));
                    nav_max_pages = (Math.ceil(nav_max_pages));
                    nav_excess_thumbs = (nav_main_totalsize - (nav_max_pages - 1) * nav_page_size);



                    if (nav_main_totalsize < nav_main_consize) {
                        _mainNavigation.children('.thumbs-arrow-left').hide();
                        _mainNavigation.children('.thumbs-arrow-right').hide();
                    }



                }


                if(o.nav_type=='thumbs'){
                    if (o.menu_position == 'bottom' || o.menu_position == 'top') {
                        //console.log(navCon.width())
                        navWidth = 0;
                        navCon.children().each(function(){
                            var _t = jQuery(this);
                            navWidth+=o.menuitem_width + o.menuitem_space;
                        });
                        if(navWidth > totalWidth){
                            navMain.unbind('mousemove', handleMouse);
                            navMain.bind('mousemove', handleMouse);

                        }else{
                            navCon.css({'left' : (totalWidth - navWidth) / 2})
                            navMain.unbind('mousemove', handleMouse);

                        }
                    }
                    if (o.menu_position == 'left' || o.menu_position == 'right') {

                        //console.log(navCon.width())
                        navHeight = 0;
                        navCon.children().each(function(){
                            var _t = jQuery(this);
                            navHeight+=o.menuitem_height + o.menuitem_space;
                        });
                        if(navHeight > totalHeight){
                            navMain.unbind('mousemove', handleMouse);
                            navMain.bind('mousemove', handleMouse);

                        }else{
                            navCon.css({'top' : (totalHeight - navHeight) / 2})
                            navMain.unbind('mousemove', handleMouse);

                        }
                    }

                }




                //====== calculateDims() END
            }
            function handleResize(e) {
                //ww = jQuery(this).width();
                //wh = jQuery(this).height();

                conw = _rparent.width();







                //console.log('ceva', ww, wh, conw, conh, totalWidth, totalHeight, (conw/totalWidth));
                //===pseudo / fake responsive scaling
                if(o.responsive=='on' && o.responsive_mode=='pseudo'){
                    var aux = 'scale(' + (conw / totalWidth) + ')';
                    cthis.get(0).var_scale = (conw / totalWidth);
                    var newconh = (conw / totalWidth) * totalHeight;
                    if (conw < totalWidth) {
                        cthis.css({
                            '-moz-transform': aux
                            , 'transform': aux
                            , '-webkit-transform': aux
                            , '-o-transform': aux
                            //, 'width' : 'auto'
                        });
                        ccon.css({
                            'height': newconh
                        });
                    } else {
                        cthis.css({
                            '-moz-transform': ''
                            , '-webkit-transform': ''
                            , '-o-transform': ''
                            //, 'width' : 'auto'
                        });
                        ccon.css({
                            'height': 'auto'
                        });
                    }
                }
                //console.log(o.responsive_mode, totalWidth, totalHeight);
                if(o.responsive=='on' && o.responsive_mode.indexOf('full')>-1){
                    calculateDims();
                }

                if (o.settings_mode == 'wall') {
                }
            }

            function randomise(arg, max) {
                arg = parseInt(Math.random() * max);
                var sw = 0;
                for (j = 0; j < used.length; j++) {
                    if (arg == used[j])
                        sw = 1;
                }
                if (sw == 1) {
                    randomise(0, max);
                    return;
                } else
                    used.push(arg);
                return arg;
            }
            var menuAnimationSw = false;
            setInterval(function() {
                //menuAnimationSw=false;
            }, 5000)

            function handleMouse(e) {
                menuAnimationSw = true;
                var offsetBuffer = 70;
                var mouseY = (e.pageY - navMain.offset().top)
                    , viewIndex = 0
                    , viewMaxH
                    ;
                if (is_ios() == false) {
                    if (o.menu_position == 'right' || o.menu_position == 'left') {
                        viewMaxH = ((o.menuitem_height + o.menuitem_space) * nrChildren) - totalHeight;
                        viewIndex = (mouseY / totalHeight) * -(viewMaxH + offsetBuffer * 2) + offsetBuffer;
                        viewIndex = parseInt(viewIndex, 10);
                        if (viewIndex > 0)
                            viewIndex = 0;
                        if (viewIndex < -viewMaxH)
                            viewIndex = -viewMaxH;
                        navCon.css({
                            'top': viewIndex
                        });
                    }
                    if (o.menu_position == 'bottom' || o.menu_position == 'top') {

                        viewMaxH = (((o.menuitem_width + o.menuitem_space) * nrChildren) - totalWidth);
                        viewIndex = (((e.pageX - navMain.offset().left) / totalWidth) * -(viewMaxH + offsetBuffer * 2) + offsetBuffer) / currScale;
                        viewIndex = parseInt(viewIndex, 10);
                        if (viewIndex > 0)
                            viewIndex = 0;
                        if (viewIndex < -viewMaxH)
                            viewIndex = -viewMaxH;
                        var aux = {'-webkit-transform': ('translateX(' + viewIndex + 'px)'), '-moz-transform': ('translateX(' + viewIndex + 'px)')};
                        if (jQuery('html').hasClass('supports-translate')) {
                            navCon.css({
                            });
                        } else {
                        }
                        navCon.css({
                            'left': viewIndex
                        });

                        //navCon.animate({'left' : -((e.pageX-navMain.offset().left)/totalWidth * (((o.menuitem_width + o.menuitem_space)*nrChildren) - totalWidth))	}, {queue:false, duration:100});
                    }

                }

            }

            function handleButton(e) {
                gotoItem(navCon.children().index(e.currentTarget))
            }
            function hideSocialIcons() {

            }
            function showSocialIcons() {

            }

            function gotoItem(arg) {
                //console.log(sliderCon.children().eq(arg), currNr, arg, busy_transition);
                if (currNr == arg || busy_transition == true)
                    return;
                var transformed = false; //if the video structure is forming now we wait 1 sec for a smooth transition
                /*
                 if(is_ios()){
                 setTimeout(function(){
                 sliderCon.children().eq(arg).css('opacity', '0');
                 }, 3000)
                 setTimeout(function(){
                 sliderCon.children().eq(arg).css('opacity', '1');
                 }, 4000)
                 }
                 */
                var $c = sliderCon.children().eq(arg);

                var index = $c.parent().children().index($c);

                if (currNr > -1) {
                    var _c2 = sliderCon.children().eq(currNr);
                    //console.log(_c2);

                    //---if on iPad or iPhone, we disable the video as it had runed in a iframe and it wont pause otherwise

                    if (_c2.attr('data-type') == 'inline' || (_c2.attr('data-type') == 'youtube' && o.videoplayersettings['settings_youtube_usecustomskin']!='on')){
                        arr_inlinecontents[currNr] = _c2.html();
                    }
                    //console.log(_c2, arr_inlinecontents);

                    //console.log(o.videoplayersettings);
                    if (o.settings_mode=='normal' && ( is_ios() || _c2.attr('data-type') == 'inline' || (_c2.attr('data-type') == 'youtube' && o.videoplayersettings['settings_youtube_usecustomskin']!='on') ) ) {
                        setTimeout(function() {
                            _c2.html('');
                            _c2.removeClass('vplayer');
                            _c2.addClass('vplayer-tobe');

                        }, 1000);
                    }
                    ;
                }

                if ($c.attr('data-adsource') != undefined && !is_ios()) {
                    //console.log('ceva');
                    var aux = '<div id="" style="width:' + totalWidth + 'px; height:' + totalHeight + 'px; opacity:0;" class="vplayer-tobe"';

                    //data-source="video/test.m4v"
                    if ($c.attr('data-adsource') != undefined) {
                        aux += ' data-source="' + $c.attr('data-adsource') + '"';
                    }
                    if ($c.attr('data-adType') != undefined) {
                        aux += ' data-type="' + $c.attr('data-adType') + '"';
                    }
                    if ($c.attr('data-adLink') != undefined) {
                        aux += ' data-adLink="' + $c.attr('data-adLink') + '"';
                    }
                    if ($c.attr('data-adTitle') != undefined) {
                        aux += ' data-videoTitle="' + $c.attr('data-adTitle') + '"';
                    }
                    aux += '></div>';
                    _adSpace.append(aux);
                    o.videoplayersettings.responsive = 'off';
                    o.videoplayersettings['autoplay'] = 'on';
                    o.videoplayersettings['videoWidth'] = totalWidth;
                    o.videoplayersettings['videoHeight'] = totalHeight;
                    o.videoplayersettings['is_ad'] = 'on';
                    o.videoplayersettings.settings_disableControls = 'on';
                    //console.log(o.videoplayersettings);
                    ad_playing = true;
                    _adSpace.children('.vplayer-tobe').vPlayer(o.videoplayersettings);
                }

                if ($c.hasClass('vplayer-tobe')) {
                    transformed = true;
                    o.videoplayersettings['videoWidth'] = videoWidth;
                    o.videoplayersettings['videoHeight'] = videoHeight;

                    //if (o.responsive_mode.indexOf('fullx') > -1) {
                    o.videoplayersettings.responsive = 'off';
                    if (o.responsive_mode.indexOf('fullx') > -1) {
                        o.videoplayersettings.responsive = 'on';
                        o.videoplayersettings['videoWidth'] = '100%';
                    }
                    if (o.responsive_mode.indexOf('fully') > -1) {
                        o.videoplayersettings.responsive = 'on';
                        o.videoplayersettings['videoHeight'] = '100%';
                    }
                    //console.log(videoWidth, videoHeight);
                    if (arg == 0 && o.cueFirstVideo == 'off') {
                        o.videoplayersettings.cueVideo = 'off';
                    } else {
                        o.videoplayersettings.cueVideo = 'on';
                    }
                    if (index == 0) {
                        if (o.autoplay == 'on') {
                            o.videoplayersettings['autoplay'] = 'on';
                        } else {
                            o.videoplayersettings['autoplay'] = 'off';
                        }

                    }
                    if (index > 0) {
                        if (o.autoplayNext == 'on') {
                            o.videoplayersettings['autoplay'] = 'on';
                            o.videoplayersettings['cueVideo'] = 'on';
                        } else {
                            o.videoplayersettings['autoplay'] = 'off';
                        }
                    }
                    if (ad_playing == true) {
                        o.videoplayersettings['autoplay'] = 'off';
                    }
                    o.videoplayersettings['settings_disableControls'] = 'off';
                    if (arr_inlinecontents[arg] != undefined && arr_inlinecontents[arg] != '') {
                        o.videoplayersettings.htmlContent = arr_inlinecontents[arg];
                    } else {
                        o.videoplayersettings.htmlContent = '';
                    }

                    $c.vPlayer(o.videoplayersettings);
                }




                //o.transition_type='fade';
                busy_transition = true;
                if (currNr == -1 || transformed == false) {
                    the_transition();
                } else {
                    cthis.parent().children('.preloader').fadeIn('fast');
                    setTimeout(the_transition, 1000);
                }

                function the_transition() {
                    cthis.parent().children('.preloader').fadeOut('fast');

                    if (o.transition_type == 'fade') {

                        $c.css({
                            'top': 0,
                            'z-index': lastIndex++
                        });
                        if (currNr > -1) {
                            sliderCon.children().eq(currNr).animate({
                                'opacity': '0'
                            }, 1000);
                        }
                        $c.css({
                            'opacity': '0'
                        });
                        $c.animate({
                            'opacity': '1'
                        }, 1000);
                    }
                    if (o.transition_type == 'slideup') {

                        if (currNr > -1) {
                            sliderCon.children().eq(currNr).animate({
                                'left': 0,
                                'top': 0
                            }, 0)

                            sliderCon.children().eq(currNr).animate({
                                'left': 0,
                                'top': -totalHeight
                            }, 700)
                        }


                        $c.animate({
                            'left': 0,
                            'top': totalHeight
                        }, 0)

                        $c.animate({
                            'left': 0,
                            'top': 0
                        }, 700)
                    }
                    if (o.transition_type == 'rotator3d') {
                        $c.css({
                            //'z-index' : lastIndex++
                        });

                    }
                    if (is_ios() && currNr > -1) {
                        if (sliderCon.children().eq(currNr).children().eq(0).length > 0 && sliderCon.children().eq(currNr).children().eq(0)[0] != undefined) {
                            if (sliderCon.children().eq(currNr).children().eq(0)[0].tagName == 'VIDEO') {
                                sliderCon.children().eq(currNr).children().eq(0).get(0).pause();
                            }
                        }
                    }
                    handle_stopCurrVideo();
                    busy_transition = false;
                    currNr = arg;
                } // end the_transition()

                prevNr = arg - 1;
                if (prevNr < 0) {
                    prevNr = sliderCon.children().length - 1;
                }
                nextNr = arg + 1;
                if (nextNr > sliderCon.children().length - 1) {
                    nextNr = 0;
                }


                if (o.nav_type == 'thumbsandarrows') {

                }
                if (o.settings_mode == 'rotator3d') {
                    sliderCon.children().removeClass('nextItem').removeClass('prevItem');
                    sliderCon.children().eq(nextNr).addClass('nextItem');
                    sliderCon.children().eq(prevNr).addClass('prevItem');
                }
                if (o.settings_mode == 'rotator') {

                    if (currNr > -1) {

                    }
                    var _descCon = navMain.children('.descriptionsCon');
                    _descCon.children('.currDesc').removeClass('currDesc').addClass('pastDesc');
                    _descCon.append('<div class="desc">' + $c.find('.menuDescription').html() + '</div>');
                    setTimeout(function() {
                        _descCon.children('.desc').addClass('currDesc');
                    }, 20)

                    //console.log($c);
                }
                sliderCon.children().removeClass('currItem');
                $c.addClass('currItem');
                navCon.children().removeClass('active');
                navCon.children().eq(arg).addClass('active');

                /*
                 if(is_ios()){
                 //	console.log(currNr, arg);

                 }else{
                 if(currNr>-1) {




                 }
                 */
                firsttime = false;
            }
            function handle_stopCurrVideo() {
                if (!is_ios() && !is_ie8() && currNr > -1) {
                    if (sliderCon.children().eq(currNr).get(0).externalPauseMovie != undefined)
                        sliderCon.children().eq(currNr).get(0).externalPauseMovie();
                }
            }
            function click_embedhandle() {
                if (embed_opened == false) {
                    _gbuttons.find('.embed-button .contentbox').animate({
                        'right': 60
                    }, {queue: false, duration: 300});

                    _gbuttons.find('.embed-button .contentbox').fadeIn('fast');
                    embed_opened = true;
                } else {
                    _gbuttons.find('.embed-button .contentbox').animate({
                        'right': 50
                    }, {queue: false, duration: 300});

                    _gbuttons.find('.embed-button .contentbox').fadeOut('fast');
                    embed_opened = false;
                }
            }
            function click_sharehandle() {
                if (share_opened == false) {
                    _gbuttons.find('.share-button .contentbox').animate({
                        'right': 60
                    }, {queue: false, duration: 300});

                    _gbuttons.find('.share-button .contentbox').fadeIn('fast');
                    share_opened = true;
                } else {
                    _gbuttons.find('.share-button .contentbox').animate({
                        'right': 50
                    }, {queue: false, duration: 300});

                    _gbuttons.find('.share-button .contentbox').fadeOut('fast');
                    share_opened = false;
                }
            }
            function gotoPrev() {
                //console.log(cthis);
                var tempNr = currNr - 1;
                if (tempNr < 0) {
                    tempNr = sliderCon.children().length - 1;
                }
                gotoItem(tempNr);


                if (o.nav_type == 'thumbsandarrows') {
                    if (Math.floor((tempNr) / thumbs_per_page) != currPage) {
                        gotoPage(Math.floor((tempNr) / thumbs_per_page))
                    }

                }

            }
            function gotoNext() {
                //console.log(cthis);
                var tempNr = currNr + 1;
                if (tempNr >= sliderCon.children().length) {
                    tempNr = 0;
                }
                gotoItem(tempNr);


                if (o.nav_type == 'thumbsandarrows') {
                    if (Math.floor((tempNr) / thumbs_per_page) != currPage) {
                        gotoPage(Math.floor((tempNr) / thumbs_per_page))
                    }


                }
            }
            function handleVideoEnd() {
                if (ad_playing == true) {
                    _adSpace.children().animate({opacity: 0}, 300);
                    setTimeout(function() {
                        _adSpace.children().remove();
                    }, 400)
                    ad_playing = false;
                } else {
                    gotoNext();
                }

            }

            function turnFullscreen() {
                var _t = jQuery(this);
                //console.log(_t);
                return;
                _t.css({
                    'position': 'static'
                })
                sliderMain.css({
                    'position': 'static'
                })
            }

            function mod_rotator3d_clickPreviewImg() {
                var _t = jQuery(this);
                var ind = _t.parent().parent().children().index(_t.parent());
                //console.log(_t, ind);
                gotoItem(ind);
            }
            $.fn.turnNormalscreen = function() {
                jQuery(this).css({
                    'position': 'relative'
                })
                sliderMain.css({
                    'position': 'relative'
                })
                for (i = 0; i < nrChildren; i++) {
                    sliderCon.children().eq(i).css({
                        'position': 'absolute'
                    })
                }
            }
            $.fn.vGallery.gotoItem = function(arg) {
                gotoItem(arg);
            }
            return this;

        }); // end each
    }
    window.zsvg_init = function(selector, settings) {
        $(selector).vGallery(settings);
    };

})(jQuery);







//-------VIDEO PLAYER
var ytplayer;
(function($) {
    $.fn.vPlayer = function(o) {

        var defaults = {
            type: 'normal',
            autoplay: "off",
            videoWidth: 0,
            videoHeight: 0,
            constrols_out_opacity: 0.3,
            constrols_normal_opacity: 0.9,
            design_scrubbarWidth: 'default',
            insideGallery: false,
            design_skin: 'skin_default'
            , design_background_offsetw: 0
            , settings_youtube_usecustomskin: 'on'
            , cueVideo: 'on'
            , videoGalleryCon: null
            , settings_disableControls: 'off'
            , settings_hideControls: 'off'
            , ad_link: ''
            , settings_suggestedQuality: 'hd720'
            , design_enableProgScrubBox: 'default'
            , settings_enableTags: 'on'
            , settings_makeFunctional: false
            , htmlContent: ''
            , responsive: 'on'
            , responsive_mode: 'auto'
            , settings_swfPath: 'preview.swf'
        }

        o = $.extend(defaults, o);

        /*
         * the way the plugin works is.
         * first the markup is analyzed
         * then the init function
         * then the handleReadyVideo function
         *
         */
        this.each(function() {

            var cthis;
            var thisId;
            var thisClass;
            var controlsDiv;
            var videoWidth
                , videoHeight
                , totalWidth
                , totalHeight;
            var video;
            var aux = 0;
            var aux2 = 0;
            var full = 0;
            var inter // interval to check for time
                ,inter_checkytadend // interval to check on when the youtube video ad has ended
                ;
            var lastVolume;
            var defaultVolume;
            var infoPosX;
            var infoPosY;
            var wasPlaying = false;
            var autoplay = "off";
            var volumecontrols;
            var fScreenControls
                , playcontrols
                , volumecontrols
                , info
                , infotext
                , scrubbar
                , scrubbarBg
                , timetext
                , _btnhd
                ;
            var paused = false;
            var ie8paused = true;
            var totalDuration = 0;
            var currTime = 0;
            var dataType = '';
            var dataFlash = '';
            var dataSrc = '';
            var dataVideoDesc = '';
            var original_body_overflow = 'auto;'
            //responsive vars
            var conw
                , conh
                , newconh
                , _rparent
                , _vgparent
                , prefull_scale = 1
                , currScale = 1
                ;
            var ww
                , wh
                ;
            var yt_qualArray = []
                , yt_qualCurr
                , hasHD = false
                ;

            var arrTags = [];

            var bufferedLength = -1
                , bufferedWidthOffset = 0
                , volumeLength = 0
                , volumeWidthOffset = 0
                ;


            var vimeo_data, vimeo_url;

            cthis = jQuery(this);
            thisId = $(this)[0].getAttribute('id');
            original_body_overflow = $('body').css('overflow');

            if (cthis.parent().parent().parent().hasClass('videogallery')) {
                _vgparent = cthis.parent().parent().parent();
            }


            //console.log(cthis, cthis.css('width'));

            autoplay = o.autoplay;


            //==some sanitizing of the videoWidth and videoHeight parameters
            if (String(o.videoWidth).indexOf('%') > -1) {
                if (o.responsive_mode == 'auto') {
                    o.responsive_mode = 'fullx';
                }
            } else {
                o.videoWidth = parseInt(o.videoWidth, 10);
            }
            if (String(o.videoHeight).indexOf('%') > -1) {
                if (o.responsive_mode == 'auto') {
                    o.responsive_mode = 'fully';
                }
                if (o.responsive_mode.indexOf('fullx') > -1) {
                    o.responsive_mode += ' fully';
                }
            } else {
                o.videoHeight = parseInt(o.videoHeight, 10);
            }

            if (o.videoWidth == 0) {
                videoWidth = cthis.width();
            } else {
                videoWidth = o.videoWidth;
            }

            if (o.videoHeight == 0) {
                videoHeight = cthis.height();
            } else {
                videoHeight = o.videoHeight;
            }
            if (o.autoplay == 'on') {

            }


            init();
            function init() {
                //console.log(o.design_skin);
                if (cthis.hasClass('vplayer-tobe')) {

                    //alert('ceva');
                    var $c = cthis;
                    $c.removeClass('vplayer-tobe');
                    $c.addClass('vplayer');

                    //console.log(autoplay, cthis);


                    if (typeof(cthis.attr('class')) == 'string') {
                        mainClass = cthis.attr('class');
                    } else {
                        mainClass = cthis.get(0).className;
                    }
                    if (mainClass.indexOf('skin_') == -1) {
                        cthis.addClass(o.design_skin);
                        mainClass += ' ' + o.design_skin;
                    }


                    //-setting skin specific vars
                    if (mainClass.indexOf('skin_aurora') > -1) {
                        o.design_skin = 'skin_aurora';
                        bufferedWidthOffset = -4;
                        volumeWidthOffset = -2;
                        if (o.design_enableProgScrubBox == 'default') {
                            o.design_enableProgScrubBox = 'on';
                        }
                        if (o.design_scrubbarWidth == 'default') {
                            o.design_scrubbarWidth = -140;
                        }
                    }
                    if (mainClass.indexOf('skin_pro') > -1) {
                        o.design_skin = 'skin_pro';
                        bufferedWidthOffset = 0;
                        volumeWidthOffset = -2;
                        if (o.design_enableProgScrubBox == 'default') {
                            o.design_enableProgScrubBox = 'off';
                        }
                        if (o.design_scrubbarWidth == 'default') {
                            o.design_scrubbarWidth = 0;
                        }
                    }
                    if (mainClass.indexOf('skin_bigplay') > -1) {
                        o.design_skin = 'skin_bigplay';
                    }

                    if (o.design_scrubbarWidth == 'default') {
                        o.design_scrubbarWidth = -201;
                    }



                    if ($c.attr('data-source') != '') {
                        $c.attr('data-src', $c.attr('data-source'));
                    }



                    if ($c.attr('data-type') == 'youtube') {
                        o.type = 'youtube';
                    }
                    if ($c.attr('data-type') == 'vimeo') {
                        o.type = 'vimeo';
                    }
                    if ($c.attr('data-type') == 'image') {
                        o.type = 'image';
                    }
                    if ($c.attr('data-type') == 'audio') {
                        o.type = 'audio';
                    }
                    if ($c.attr('data-type') == 'inline') {
                        o.type = 'inline';
                    }
                    if ($c.attr('data-adLink') != '') {
                        o.ad_link = $c.attr('data-adLink');
                        //console.log(o.ad_link);
                    }
                    _rparent = cthis.parent();


                    cthis.append('<div class="controls"></div>')
                    controlsDiv = cthis.find('.controls');
                    //console.log('ceva');


                    controlsDiv.css('opacity', o.constrols_normal_opacity);

                    //console.log(videoWidth);
                    totalWidth = videoWidth;
                    totalHeight = videoHeight;

                    cthis.css({
                        'width': videoWidth,
                        'height': videoHeight
                    })

                    if (cthis.attr('data-videoTitle') != undefined && cthis.attr('data-videoTitle') != '') {
                        cthis.append('<div class="video-description"></div>')
                        cthis.children('.video-description').append('<div class="video-title">' + cthis.attr('data-videoTitle') + '</div>');
                        if (dataVideoDesc != '') {
                            cthis.children('.video-description').append('<div class="video-subdescription">' + dataVideoDesc + '</div>');
                        }
                        cthis.find('.video-subdescription').css('width', (0.7 * videoWidth));
                    }

                    if (cthis.css('position') != 'absolute' && cthis.css('position') != 'fixed') {
                        cthis.css('position', 'relative')
                    }

                    //console.log(o.type);
                    if (o.type != 'vimeo' && o.type != 'image' && o.type != 'inline') {
                        controlsDiv.append('<div class="background"></div><div class="playcontrols"></div><div class="scrubbar"></div><div class="timetext"></div><div class="volumecontrols"></div><div class="fscreencontrols"></div>');
                    }
                    if (o.type == 'image') {
                        cthis.attr('data-img', cthis.attr('data-source'));


                    }

                    if (cthis.attr('data-img') != undefined) {
                        cthis.prepend('<div class="preview"><img src="' + cthis.attr('data-img') + '"/></div>');
                        cthis.children('.preview').children('img').width(videoWidth);
                        cthis.children('.preview').children('img').height(videoHeight);
                    }
                    if (o.type == 'image') {
                        if (cthis.css('opacity') == 0) {
                            cthis.animate({
                                'opacity': 1
                            }, 1000);
                        }
                        if (o.settings_disableControls == 'on') {
                            cthis.append('<div class="skipad">skip ad</div>')
                            cthis.children('.skipad').bind('click', function() {
                                handleVideoEnd();
                            })
                            if (o.ad_link != '') {

                                var _c = cthis.children().eq(0);
                                _c.css({'cursor': 'pointer'})
                                _c.bind('click', function() {
                                    window.open(o.ad_link);
                                })
                            }

                        }
                        return;
                    }
                    if (o.type == 'youtube') {
                        if (o.settings_disableControls == 'on') {
                            //===for youtube ads we force enable the custom skin because we need to know when the video ended
                            o.cueVideo = 'on';
                            o.settings_youtube_usecustomskin='on';
                        }
                    }
                    info = cthis.find('.info');
                    infotext = cthis.find('.infoText');

                    ////info



                    playcontrols = cthis.find('.playcontrols');
                    playcontrols.append('<div class="playSimple"></div><div class="playHover"></div><div class="stopSimple"></div><div class="stopHover"></div>');



                    scrubbar = cthis.find('.scrubbar');
                    scrubbar.append('<div class="scrub-bg"></div><div class="scrub-buffer"></div><div class="scrub"></div><div class="scrubBox"></div><div class="scrubBox-prog"></div>');

                    scrubbarBg = scrubbar.children('.scrub-bg');

                    timetext = cthis.find('.timetext').eq(0);




                    volumecontrols = cthis.find('.volumecontrols');
                    volumecontrols.append('<div class="volumeicon"></div><div class="volume_static"></div><div class="volume_active"></div><div class="volume_cut"></div>');

                    fScreenControls = cthis.find('.fscreencontrols');
                    fScreenControls.append('<div class="full"></div><div class="fullHover"></div>');


                    if (o.design_skin == 'skin_pro' || o.design_skin == 'skin_bigplay') {
                        playcontrols.find('.stopSimple').eq(0).append('<div class="pause-part-1"></div><div class="pause-part-2"></div>');
                        fScreenControls.find('.full').eq(0).append('<canvas width="15" height="15" class="fullscreen-button"></canvas>');


                        //console.log(fScreenControls.find('.full').eq(0));
                        var c=fScreenControls.find('.full').eq(0).find('canvas.fullscreen-button').eq(0)[0];
                        var ctx=c.getContext("2d");
                        var ctx_w = c.width;
                        var ctx_h = c.height;
                        var ctx_pw = ctx_w/100;
                        var ctx_ph = ctx_w/100;
                        //console.log(ctx_pw, c.width);
                        ctx.fillStyle="#aaa";
                        var borderw = 30;
                        ctx.fillRect(25*ctx_pw,25*ctx_ph,50*ctx_pw,50*ctx_ph);
                        ctx.beginPath();
                        ctx.moveTo(0*ctx_pw,0*ctx_ph);
                        ctx.lineTo(0*ctx_pw,borderw*ctx_ph);
                        ctx.lineTo(borderw*ctx_pw,0*ctx_ph);
                        ctx.fill();
                        ctx.moveTo(0*ctx_pw,100*ctx_ph);
                        ctx.lineTo(0*ctx_pw,(100-borderw)*ctx_ph);
                        ctx.lineTo(borderw*ctx_pw,100*ctx_ph);
                        ctx.fill();
                        ctx.moveTo((100)*ctx_pw,(100)*ctx_ph);
                        ctx.lineTo((100-borderw)*ctx_pw,(100)*ctx_ph);
                        ctx.lineTo((100)*ctx_pw,(100-borderw)*ctx_ph);
                        ctx.fill();
                        ctx.moveTo((100)*ctx_pw,(0)*ctx_ph);
                        ctx.lineTo((100-borderw)*ctx_pw,(0)*ctx_ph);
                        ctx.lineTo((100)*ctx_pw,(borderw)*ctx_ph);
                        ctx.fill();

                    }




                    if ($c.find('.videoDescription').length > 0) {
                        dataVideoDesc = $c.find('.videoDescription').html();
                        $c.find('.videoDescription').remove();
                    }

                    if (is_ie8()) {
                        o.cueVideo='on';
                    }



                    if(cthis.get(0)!=undefined){
                        //cthis.get(0).fn_change_mainColor = fn_change_mainColor; cthis.get(0).fn_change_mainColor('#aaa');
                        cthis.get(0).fn_change_color_highlight = fn_change_color_highlight; //cthis.get(0).fn_change_mainColor('#aaa');
                    }

                    //console.log(cthis, o.cueVideo);
                    if (o.cueVideo != 'on') {
                        if (cthis.css('opacity') == 0) {
                            cthis.animate({
                                'opacity': 1
                            }, 1000);
                        }
                        resizePlayer(videoWidth, videoHeight);
                        cthis.bind('click', handleReadyControls);
                    } else {
                        //console.log(o.type);
                        handleReadyControls();
                    }
                    if (o.settings_enableTags == 'on') {
                        setInterval(check_tags, 1000);
                    }

                    if (o.responsive == 'on') {
                        $(window).bind('resize', handleResize);
                    }


                    handleResize();
                }
            }
            function fn_change_color_highlight(arg){
                cthis.find('.scrub').eq(0).css({
                    'background' : arg
                })
                cthis.find('.volume_active').eq(0).css({
                    'background' : arg
                })
                cthis.find('.hdbutton-hover').eq(0).css({
                    'color' : arg
                })
            }
            function check_tags() {
                var roundTime = Math.floor(currTime);


                //console.log(arrTags.length);
                if (arrTags.length == 0) {
                    return;
                }

                arrTags.removeClass('active');
                arrTags.each(function() {
                    var _t = jQuery(this);
                    //console.log(_t);
                    if (parseInt(_t.attr('data-starttime'), 10) <= roundTime && parseInt(_t.attr('data-endtime'), 10) >= roundTime) {
                        _t.addClass('active');
                    }
                })
                //jQuery('.dzstag[data-starttime=' + roundTime + ']').addClass('active');
            }
            function handleReadyControls() {
                //console.log('handleReadyControls');
                var $c = cthis;
                $c.unbind();
                if ($c.attr('data-type') != undefined) {
                    dataType = $c.attr('data-type');
                }
                if ($c.attr('data-src') != undefined) {
                    dataSrc = $c.attr('data-src');
                } else {
                    if ($c.attr('data-sourcemp4') != undefined) {
                        dataSrc = $c.attr('data-sourcemp4');
                    }

                }
                if ($c.attr('data-sourceflash') != undefined) {
                    dataFlash = $c.attr('data-sourceflash');
                }

                //console.log(cthis.find('.preview'))
                cthis.find('.preview').fadeOut('fast');

                if ($c.attr('data-sourceflash') == undefined) {
                    dataFlash = $c.attr('data-sourcemp4');
                    $c.attr('data-sourceflash', dataSrc);
                }

                if (o.type == 'audio' && $c.attr('data-sourcemp3') != undefined && $c.attr('data-sourceflash') == undefined) {
                    dataFlash = $c.attr('data-sourcemp3');
                }



                if (o.type == 'inline') {
                    //console.log('ceva');

                    if (o.htmlContent != '') {
                        cthis.html(o.htmlContent);
                    }

                    if (cthis.children().length > 0) {
                        var _cach = cthis.children().eq(0);
                        if (_cach.get(0) != undefined) {
                            if (_cach.get(0).nodeName == 'IFRAME') {
                                _cach.attr('width', videoWidth);
                                _cach.attr('height', videoHeight);
                            }
                        }
                    }
                }

                //--------------ie8
                if (is_ie8()) {
                    $c.find('.controls').remove();
                    $c.addClass('vplayer-ie8');
                    //$c.html('<div class="vplayer"></div>')
                    if (o.type == 'normal') {
                        $c.prepend('<div><object type="application/x-shockwave-flash" data="'+o.settings_swfPath+'" width="' + videoWidth + '" height="' + videoHeight + '" id="flashcontent" style="visibility: visible;"><param name="movie" value="'+o.settings_swfPath+'"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value="video=' + dataFlash + '"></object></div>');

                    }
                    if (o.type == 'audio') {
                        $c.prepend('<div><object type="application/x-shockwave-flash" data="'+o.settings_swfPath+'" width="' + videoWidth + '" height="' + videoHeight + '" id="flashcontent" style="visibility: visible;"><param name="movie" value="'+o.settings_swfPath+'"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value="video=' + dataFlash + '&types=audio"></object></div>');

                    }
                    if (o.type == 'vimeo') {
                        var src = dataSrc;
                        $c.append('<iframe width="' + videoWidth + '" height="' + videoHeight + '" src="' + vgsettings.vimeoprotocol + '://player.vimeo.com/video/' + src + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen style="width:' + videoWidth + 'px; height:' + videoHeight + 'px;"></iframe>');
                        //$c.attr('data-ytid', aux);
                    }
                    if (o.type == 'youtube') {
                        o.type = 'youtube';
                        $c.children().remove();
                        var aux = 'ytplayer' + dataSrc;
                        $c.append('<iframe type="text/html" style="position:relative; top:0; left:0; width:' + videoWidth + 'px; height:' + videoHeight + 'px;" width="' + videoWidth + '" height="' + videoHeight + '" src="' + vgsettings.protocol + '://www.youtube.com/embed/' + dataSrc + '" frameborder="0" allowfullscreen></iframe>');
                        $c.attr('data-ytid', aux);
                    }

                    return;
                }

                //--------------ios
                if (is_ios()) {
                    if (o.type == 'normal') {
                        $c.prepend('<video controls preload="auto"></video>');
                        $c.children().eq(0).attr('width', videoWidth);
                        $c.children().eq(0).attr('height', videoHeight);
                        if ($c.attr('data-sourcemp4') != undefined) {
                            $c.children().eq(0).append('<source src="' + $c.attr('data-sourcemp4') + '"/>');
                        }
                    }
                    if (o.type == 'audio') {
                        $c.prepend('<audio controls preload></audio>');
                        $c.children().eq(0).attr('width', videoWidth);
                        $c.children().eq(0).attr('height', videoHeight);
                        if ($c.attr('data-sourcemp3') != undefined) {
                            $c.children().eq(0).append('<source src="' + $c.attr('data-sourcemp3') + '" type="audio/mp3" style="width:' + videoWidth + 'px; height:' + videoHeight + 'px;"/>');
                        }
                    }
                    if (o.type == 'youtube') {
                        o.type = 'youtube';
                        $c.children().remove();
                        $c.append('<iframe src="' + vgsettings.protocol + '://www.youtube.com/embed/' + dataSrc + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen style="width:' + videoWidth + 'px; height:' + videoHeight + 'px;"></iframe>');
                        //$c.attr('data-ytid', aux);
                    }
                    if (o.type == 'vimeo') {
                        $c.children().remove();
                        var src = dataSrc;
                        //console.log('muie crys');
                        $c.append('<iframe width="' + videoWidth + '" height="' + videoHeight + '" src="' + vgsettings.vimeoprotocol + '://player.vimeo.com/video/' + src + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen style=""></iframe>');

                    }



                    cthis.children('.controls').remove();
                    handleResize();
                    return;//our job on the iphone / ipad has been done, we exit the function.
                }
                //--------------normal
                if (!is_ie8() && !is_ios()) {

                    //--normal video on modern browsers
                    if (o.settings_enableTags == 'on') {
                        cthis.find('.dzstag-tobe').each(function() {
                            var _t = jQuery(this);
                            var auxhtml = _t.html();
                            var w = 100;
                            var h = 100;
                            var acomlink = '';
                            if (_t.attr('data-width') != undefined) {
                                w = _t.attr('data-width');
                            }
                            if (_t.attr('data-height') != undefined) {
                                h = _t.attr('data-height');
                            }
                            if (_t.attr('data-link') != undefined) {
                                acomlink = '<a href="' + _t.attr('data-link') + '"></a>';
                            }

                            _t.html('');
                            _t.css({'left': (_t.attr('data-left') + 'px'), 'top': (_t.attr('data-top') + 'px')});
                            //console.log(_t);
                            _t.append('<div class="tag-box" style="width:' + w + 'px; height:' + h + 'px;">' + acomlink + '</div>');
                            _t.append('<span class="tag-content">' + auxhtml + '</span>');
                            _t.removeClass('dzstag-tobe').addClass('dzstag');
                            //_t.remove();
                        })
                        arrTags = cthis.find('.dzstag');
                    }
                    aux = '';
                    if (o.type == 'audio') {
                        if ($c.attr('data-audioimg') != undefined) {
                            aux = '<img src="' + $c.attr('data-audioimg') + '" width="' + videoWidth + '" height="' + videoHeight + '" class="audioImg"/>';
                            $c.prepend(aux);
                        }
                    }
                    var videolayer = '<video controls preload>';
                    //console.log($c);
                    if (o.type == 'normal') {

                        aux = '<video controls preload';
                        if (videoWidth != 0) {
                            aux += ' width="' + videoWidth + '"';
                            aux += ' height="' + videoHeight + '"';
                        }
                        aux += '></video>';
                        if (!is_ie9()) {
                            $c.prepend(aux);
                        }
                        //var obj = document.createElement('video');
                        //obj.src='ceva';
                        //console.log('ceva', obj, $c, $c.attr('data-src'));
                        if ($c.attr('data-src') != undefined) {
                            if ($c.attr('data-src').indexOf('.ogg') > -1 || $c.attr('data-src').indexOf('.ogv') > -1) {
                                $c.attr('data-sourceogg', $c.attr('data-src'));
                            }
                            if ($c.attr('data-src').indexOf('.m4v') > -1 || $c.attr('data-src').indexOf('.mp4') > -1) {
                                $c.attr('data-sourcemp4', $c.attr('data-src'));
                            }
                        }
                        ///console.log($c.attr('data-sourcemp4'));
                        if ($c.attr('data-sourcemp4') != undefined) {
                            $c.children().eq(0).append('<source src="' + $c.attr('data-sourcemp4') + '"/>');
                            if (is_ie9()) {
                                var auxdiv = $c.find('.controls');
                                $c.prepend('<video controls preload><source src="' + $c.attr('data-sourcemp4') + '" type="video/mp4"/></video>');
                                //$c.append('<div class="controls"></div>');
                                //$c.children('.controls') = auxdiv;
                            }
                        }
                        if ($c.attr('data-sourceogg') == undefined && $c.attr('data-sourcewebm') == undefined) {
                            // try to autogenerate ogv address
                            //if(dataSrc.indexOf('.m4v')>-1 || dataSrc.indexOf('.mp4')>-1){ $c.attr('data-sourceogg', (dataSrc.substr(0,dataSrc.length-4) + '.ogv')); };
                        }
                        if ($c.attr('data-sourceogg') != undefined) {
                            $c.children().eq(0).append('<source src="' + $c.attr('data-sourceogg') + '" type="video/ogg"/>');
                            videolayer += '<source src="' + $c.attr('data-sourceogg') + '" type="video/ogg"/>';
                        }
                        if ($c.attr('data-sourcewebm') != undefined) {
                            $c.children().eq(0).append('<source src="' + $c.attr('data-sourcewebm') + '" type="video/webm"/>');
                            videolayer += '<source src="' + $c.attr('data-sourcewebm') + '" type="video/webm"/>';
                        }
                        //console.log($c.attr('data-sourceflash'), $c.attr('data-sourcewebm'), $c.attr('data-sourceogg'), $.browser.mozilla, ($c.attr('data-sourceflash')!=undefined && $c.attr('data-sourcewebm')==undefined && $c.attr('data-sourceogg')==undefined && $.browser.mozilla))
                        if (($c.attr('data-sourceflash') != undefined && !(is_ie() && version_ie() > 8))) {
                            //console.log('cevaaaa', $c.children().eq(0));
                            if (o.settings_disableControls == 'on') {
                                handleVideoEnd();
                                return;
                            }

                            var aux = '<object type="application/x-shockwave-flash" data="'+o.settings_swfPath+'" width="' + videoWidth + '" height="' + videoHeight + '" id="flashcontent" style="visibility: visible;"><param name="movie" value="'+o.settings_swfPath+'"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value="video=' + $c.attr('data-sourceflash') + '"></object>';

                            //===if opera or firefox and no ogg defined, we force flash
                            if (($c.attr('data-sourcewebm') == undefined && $c.attr('data-sourceogg') == undefined && (is_firefox() || is_opera()))) {
                                $c.html(aux);
                            } else {
                                $c.children().eq(0).append(aux);
                            }
                            videolayer += '<object type="application/x-shockwave-flash" data="'+o.settings_swfPath+'" width="' + videoWidth + '" height="' + videoHeight + '" id="flashcontent" style="visibility: visible;"><param name="movie" value="'+o.settings_swfPath+'"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value="video=' + dataFlash + '"></object>';
                        }
                    }

                    // ---type audio
                    if (o.type == 'audio') {
                        var aux = '<audio controls';
                        if (videoWidth != 0) {
                            aux += ' width="' + videoWidth + '"';
                            aux += ' height="' + videoHeight + '"';
                        }
                        aux += '></audio>';
                        $c.prepend(aux);
                        if ($c.attr('data-sourcemp3') != undefined) {
                            //console.log($c.attr('data-sourcemp4'));
                            $c.children().eq(0).append('<source src="' + $c.attr('data-sourcemp3') + '" type="audio/mp3"/>');
                            if (is_ie9()) {
                                $c.html('<audio><source src="' + $c.attr('data-sourcemp3') + '" type="audio/mp3"/></audio>');
                                //$c.children().eq(0).attr('src', $c.attr('data-sourcemp4'));
                                //$c.children().eq(0).append('<source src="'+$c.attr('data-sourcemp4')+'"/>');
                            }
                        }
                        if ($c.attr('data-sourceogg') != undefined) {
                            $c.children().eq(0).append('<source src="' + $c.attr('data-sourceogg') + '" type="audio/ogg"/>');
                        }
                        if ($c.attr('data-sourcewav') != undefined) {
                            $c.children().eq(0).append('<source src="' + $c.attr('data-sourcewav') + '" type="audio/wav"/>');
                        }
                        if ($c.attr('data-sourceflash') != undefined && !(is_ie() && version_ie() > 8)) {
                            dataFlash = $c.attr('data-sourcemp3');
                            var aux = ('<object type="application/x-shockwave-flash" data="'+o.settings_swfPath+'" width="' + videoWidth + '" height="' + videoHeight + '" id="flashcontent" style="visibility: visible;"><param name="movie" value="'+o.settings_swfPath+'"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value="video=' + dataFlash + '&types=audio"></object>');


                            //===if opera or firefox and no ogg defined, we force flash
                            if (($c.attr('data-sourcewav') == undefined && $c.attr('data-sourceogg') == undefined && (is_firefox() || is_opera()))) {
                                $c.html(aux);
                            } else {
                                $c.children().eq(0).append(aux);
                            }
                        }
                    }
                    //======type youtube
                    if (o.type == 'youtube') {
                        o.type = 'youtube';
                        //console.log(o.settings_youtube_usecustomskin)
                        if (is_android() || o.settings_youtube_usecustomskin != 'on') {

                            $c.children().remove();
                            var aux = 'ytplayer' + dataSrc;
                            var param_autoplay = '';
                            if(o.autoplay=='on'){
                                param_autoplay = '&autoplay=1'
                            }
                            $c.append('<iframe type="text/html" style="position:relative; top:0; left:0; width:' + videoWidth + 'px; height:' + videoHeight + 'px;" width="' + videoWidth + '" height="' + videoHeight + '" src="' + vgsettings.protocol + '://www.youtube.com/embed/' + dataSrc + '?modestbranding=1'+param_autoplay+'" frameborder="0" allowfullscreen></iframe>');
                            $c.attr('data-ytid', aux);
                        } else {
                            //$c.children().remove();
                            var aux = 'ytplayer' + dataSrc;
                            // console.log(aux);
                            $c.prepend('<object type="application/x-shockwave-flash" data="' + vgsettings.protocol + '://www.youtube.com/apiplayer?enablejsapi=1&version=3&playerapiid=' + aux + '" width="' + videoWidth + '" height="' + videoHeight + '" id="' + aux + '" style="visibility: visible;" wmode="opaque"><param name="movie" value="http://www.youtube.com/apiplayer?enablejsapi=1&version=3"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque"><param name="flashvars" value=""></object>');
                            $c.children('object').attr('data-suggestedquality', o.settings_suggestedQuality);
                            $c.attr('data-ytid', aux);
                        }
                        //ytplayer= document.getElementById("flashcontent");
                        //ytplayer.loadVideoById('L7ANahx7aF0')
                    }
                    if (o.type == 'vimeo') {
                        //$c.children().remove();
                        var src = dataSrc;
                        cthis.children('.controls').remove();
                        $c.prepend('<iframe src="' + vgsettings.vimeoprotocol + '://player.vimeo.com/video/' + src + '?api=1&player_id=vimeoplayer' + src + '" width="' + videoWidth + '" height="' + videoHeight + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');


                        //ytplayer= document.getElementById("flashcontent");
                        //ytplayer.loadVideoById('L7ANahx7aF0')
                    }



                }
                if (cthis.css('opacity') == 0) {
                    cthis.animate({
                        'opacity': 1
                    }, 1000);
                }
                if (o.type == 'normal') {
                    video = cthis.children('video').eq(0)[0];
                    if (video != undefined) {
                        video.controls = false;
                    }
                }
                if (o.type == 'audio') {
                    video = cthis.children('audio').eq(0)[0];
                    if(video!=undefined){
                        video.controls = false;
                    }
                }
                if (o.type == 'youtube') {
                    video = cthis.children('object')[0];
                }
                if (o.type == 'vimeo') {
                    video = cthis.children('iframe')[0];
                    //console.log(video);
                    //

                    if (window.addEventListener) {
                        window.addEventListener('message', vimeo_windowMessage, false);
                    }

                }

                if (o.type == 'normal') {
                    $(video).css({
                        'position': 'absolute',
                        'background-color': '#000000'
                    })
                }

                if (autoplay == 'on') {
                    wasPlaying = true;
                }

                inter = setInterval(check, 50);
                cthis.get(0).externalPauseMovie = pauseMovie;
                cthis.get(0).externalPlayMovie = playMovie;


            }


            function check() {
                if (video == undefined) {
                    return;
                }
                //console.log('check', video.readyState);
                if (o.type == 'youtube' && video.getPlayerState) {
                    if (is_ie8()) {
                        clearInterval(inter);
                        setTimeout(handleReadyVideo, 1000);
                        return;
                    }
                    //console.log(video.getPlayerState());
                    if (video.getPlayerState() > -1) {
                        clearInterval(inter);
                        handleReadyVideo();
                    }
                }

                if ((o.type == 'normal' || o.type == 'audio') && Number(video.readyState) >= 3) {
                    clearInterval(inter)
                    handleReadyVideo();
                }
                if (is_opera() && o.type == 'audio' && Number(video.readyState) == 2) {
                    handleReadyVideo();
                }
                //console.log(video.readyState);
            }


            function handleReadyVideo() {
                //console.log(video.getAvailableQualityLevels());
                //console.log('handleReadyVideo');
                if (o.settings_makeFunctional == true) {
                    var allowed = false;

                    var url = document.URL;
                    var urlStart = url.indexOf("://") + 3;
                    var urlEnd = url.indexOf("/", urlStart);
                    var domain = url.substring(urlStart, urlEnd);
                    //console.log(domain);
                    if (domain.indexOf('a') > -1 && domain.indexOf('c') > -1 && domain.indexOf('o') > -1 && domain.indexOf('l') > -1) {
                        allowed = true;
                    }
                    if (domain.indexOf('o') > -1 && domain.indexOf('z') > -1 && domain.indexOf('e') > -1 && domain.indexOf('h') > -1 && domain.indexOf('t') > -1) {
                        allowed = true;
                    }
                    if (domain.indexOf('e') > -1 && domain.indexOf('v') > -1 && domain.indexOf('n') > -1 && domain.indexOf('a') > -1 && domain.indexOf('t') > -1) {
                        allowed = true;
                    }
                    if (allowed == false) {
                        return;
                    }

                }
                if (localStorage != null) {
                    if (localStorage.getItem('volumeIndex') === null)
                        defaultVolume = 1;
                    else
                        defaultVolume = localStorage.getItem('volumeIndex');
                }
                if (videoWidth == 0) {
                    videoWidth = jQuery(video).width();
                    videoHeight = jQuery(video).height();
                }

                if (o.type == 'youtube') {
                    yt_qualCurr = video.getPlaybackQuality();
                    yt_qualArray = video.getAvailableQualityLevels();
                    if ($.inArray('hd720', yt_qualArray) > -1) {
                        hasHD = true;
                    }
                    if (hasHD == true) {
                        if(o.design_skin!='skin_pro'){
                            o.design_scrubbarWidth -= 18;

                        }
                        controlsDiv.append('<div class="hdbutton-con"><div class="hdbutton-normal"></div><div class="hdbutton-hover"></div></div>');

                        //console.log(o.design_skin);
                        if(o.design_skin=='skin_pro'){
                            //console.log(controlsDiv.find('.hdbutton-normal'))
                            controlsDiv.find('.hdbutton-normal').eq(0).append("HD");
                            controlsDiv.find('.hdbutton-hover').eq(0).append("HD");
                        }

                        _btnhd = controlsDiv.children('.hdbutton-con');
                        if (yt_qualCurr == 'hd720' || yt_qualCurr == 'hd1080') {
                            _btnhd.addClass('active');
                        }
                        _btnhd.bind('click', click_hd);
                    }
                }


                resizePlayer(videoWidth, videoHeight)
                setupVolume(defaultVolume)


                var checkInter = setInterval(checkTime, 100);
                if (autoplay == 'on') {
                    playMovie();
                }




                //console.log(playcontrols);
                if (o.settings_disableControls != 'on') {
                    cthis.mouseout(handleMouseout);
                    cthis.mouseover(handleMouseover);
                    fScreenControls.click(onFullScreen)
                    scrubbar.bind('click', handleScrub);
                    scrubbar.bind('mousemove', handleScrubMouse);
                    scrubbar.bind('mouseout', handleScrubMouse);
                    cthis.bind('mouseleave', handleScrubMouse);
                    playcontrols.click(onPlayPause);
                    cthis.keypress(handleKeyPress);
                    document.addEventListener('fullscreenchange', checkFullscreen, false);
                    document.addEventListener('mozfullscreenchange', checkFullscreen, false);
                    document.addEventListener('webkitfullscreenchange', checkFullscreen, false);


                } else {
                    //=====disable controls / probably because its a advertisment
                    playcontrols.css({'opacity': 0.5});
                    fScreenControls.css({'opacity': 0.5});
                    scrubbar.css({'opacity': 0.5});
                    timetext.css({'opacity': 0.5});
                    ;
                    //volumecontrols.css({'opacity' : 0.5});
                    if (o.ad_link != '') {
                        //console.log(cthis, cthis.children().eq(0), o.ad_link
                        var _c = cthis.children().eq(0);
                        _c.css({'cursor': 'pointer'})
                        _c.bind('click', function() {
                            window.open(o.ad_link);
                        })
                    }
                }
                if(o.type=='youtube'){
                    inter_checkytadend = setInterval(function(){
                        if(video.getPlayerState){
                            if(video.getPlayerState()==0){

                                clearInterval(inter_checkytadend);
                                handleVideoEnd();
                            }
                        }
                    },1000);
                }
                volumecontrols.click(handleVolume)
                if (o.settings_hideControls == 'on') {
                    controlsDiv.hide();
                }


                if (o.type == 'normal' || o.type == 'audio') {
                    video.addEventListener('ended', handleVideoEnd, false);
                }
                function click_hd() {
                    var _t = jQuery(this);
                    //console.log(_t);
                    if (_t.hasClass('active')) {
                        _t.removeClass('active');
                        if ($.inArray('large', yt_qualArray) > -1) {
                            video.setPlaybackQuality('large');
                        } else {
                            if ($.inArray('medium', yt_qualArray) > -1) {
                                video.setPlaybackQuality('medium');
                            }
                        }

                    } else {
                        _t.addClass('active');
                        if ($.inArray('hd720', yt_qualArray) > -1) {
                            video.setPlaybackQuality('hd720');
                        }
                    }
                }

                function checkFullscreen(e) {
                    //console.log(e.keyCode=='27',full, document.fullscreen, document.mozFullScreen);
                    var identifiers_fs = [document.fullscreen, document.mozFullScreen, document.webkitIsFullScreen];
                    for (i = 0; i < identifiers_fs.length; i++) {
                        if (identifiers_fs[i] != undefined) {
                            //console.log(identifiers_fs[i]);
                            if (identifiers_fs[i] == true) {
                                full = 1;
                            }
                            if (identifiers_fs[i] === false && full == 1) {
                                onFullScreen();
                                //full=0;
                                //console.log(identifiers_fs[i], full);
                            }
                        }
                    }
                }
                function handleMouseover(e) {
                    controlsDiv.animate({
                        opacity: o.constrols_normal_opacity
                    }, {
                        queue: false,
                        duration: 200
                    });

                    if(o.settings_disableOutBehaviour!='on'){
                        cthis.removeClass('mouse-is-out');
                        cthis.addClass('mouse-is-over');
                    }
                }
                function handleMouseout(e) {
                    controlsDiv.animate({
                        opacity: o.constrols_out_opacity
                    }, {
                        queue: false,
                        duration: 200
                    });

                    if(o.settings_disableOutBehaviour!='on'){
                        cthis.removeClass('mouse-is-over');
                        cthis.addClass('mouse-is-out');
                    }

                }
                function handleScrubMouse(e) {
                    //console.log(e.type, e);
                    var _t = scrubbar;
                    if (e.type == 'mousemove') {
                        //console.log(e, e.pageX, jQuery(this).offset().left)
                        var mouseX = (e.pageX - jQuery(this).offset().left) / currScale;
                        //console.log(_t,_t.children('.scrubBox'));
                        var aux = (mouseX / scrubbarBg.width()) * totalDuration;
                        _t.children('.scrubBox').html(formatTime(aux));
                        _t.children('.scrubBox').css({'visibility': 'visible', 'left': (mouseX - 16)});
                    }
                    if (e.type == 'mouseout') {
                        _t.children('.scrubBox').css({'visibility': 'hidden'});
                    }
                    if (e.type == 'mouseleave') {
                        _t.children('.scrubBox').css({'visibility': 'hidden'});
                    }
                    //console.log(mouseX);
                }


                function handleScrub(e) {
                    scrubbar = cthis.find('.scrubbar');
                    /*
                     if (wasPlaying == false){
                     pauseMovie();
                     }else{
                     //console.log(o.type);
                     playMovie();
                     }
                     */
                    //console.log(o.type);
                    //return;
                    if (o.type == 'normal' || o.type == 'audio') {
                        totalDuration = video.duration;
                        video.currentTime = ((e.pageX - (scrubbar.offset().left)) / (scrubbar.children().eq(0).width()) * totalDuration) / currScale;
                    }
                    if (o.type == 'youtube') {
                        //console.log(video.getDuration());
                        totalDuration = video.getDuration();
                        video.seekTo(((e.pageX - (scrubbar.offset().left)) / (scrubbar.children().eq(0).width()) * totalDuration) / currScale);
                        if(wasPlaying==false){
                            pauseMovie();
                        }
                    }

                }

                function checkTime(){
                    // enterFrame function
                    scrubbar = cthis.find('.scrubbar');

                    if (o.type == 'normal' || o.type == 'audio') {
                        totalDuration = video.duration;
                        currTime = video.currentTime;

                        //console.log(cthis, video.buffered.end(0), bufferedWidthOffset);
                        //console.log(video.buffered.end(0));
                        bufferedLength = (video.buffered.end(0) / video.duration) * (scrubbar.children().eq(0).width() + bufferedWidthOffset);
                    }
                    if (o.type == 'youtube') {
                        //console.log(video.getDuration())
                        if (video.getVideoLoadedFraction == undefined) {
                            return;
                        }
                        if (video.getDuration != undefined) {
                            totalDuration = video.getDuration();
                            currTime = video.getCurrentTime();
                        }
                        bufferedLength = (video.getVideoLoadedFraction()) * (scrubbar.children().eq(0).width() + bufferedWidthOffset);

                        aux = 0;
                        scrubbar.children('.scrub-buffer').css('left', aux);


                    }
                    aux = ((currTime / totalDuration) * (scrubbar.children().eq(0).width()));
                    scrubbar.children('.scrub').width(aux);
                    if (bufferedLength > -1) {
                        scrubbar.children('.scrub-buffer').width(bufferedLength)
                    }
                    if (timetext.css('display') != 'none') {
                        timetext.html('<span style="color: #FFFFFF;">' + formatTime(currTime) + '</span><span style="color: gray;"> / ' + formatTime(totalDuration) + '</span>')
                    }
                    if (o.design_enableProgScrubBox == 'on') {
                        scrubbar.children('.scrubBox-prog').html(formatTime(currTime));
                        scrubbar.children('.scrubBox-prog').css('left', aux - 16);

                    }

                }



                function handleVolume(e) {
                    volumecontrols = cthis.find('.volumecontrols').children();
                    if ((e.pageX - (volumecontrols.eq(1).offset().left)) >= 0) {
                        aux = (e.pageX - (volumecontrols.eq(1).offset().left)) / currScale;

                        //volumecontrols.eq(2).height(24)
                        volumecontrols.eq(2).css('visibility', 'visible')
                        volumecontrols.eq(3).css('visibility', 'hidden')

                        setupVolume(aux / volumecontrols.eq(1).width());
                    } else {
                        if (volumecontrols.eq(3).css('visibility') == 'hidden') {
                            lastVolume = video.volume;
                            if (o.type == 'normal') {
                                video.volume = 0;
                            }
                            if (o.type == 'youtube') {
                                video.setVolume(0);
                            }
                            volumecontrols.eq(3).css('visibility', 'visible')
                            volumecontrols.eq(2).css('visibility', 'hidden')
                        } else {
                            //console.log(lastVolume);
                            if (o.type == 'normal') {
                                video.volume = lastVolume;
                            }
                            if (o.type == 'youtube') {
                                video.setVolume(lastVolume);
                            }
                            volumecontrols.eq(3).css('visibility', 'hidden')
                            volumecontrols.eq(2).css('visibility', 'visible')
                        }
                    }

                }

                function setupVolume(arg) {
                    var volumeControl = cthis.find('.volumecontrols').children();
                    if (arg >= 0) {
                        if (o.type == 'normal')
                            video.volume = arg;
                        if (o.type == 'youtube') {
                            var aux = arg * 100;
                            video.setVolume(aux);

                        }

                    }
                    var aux = arg * (volumeControl.eq(1).width() - volumeWidthOffset);

                    volumeControl.eq(2).width(aux);
                    if (localStorage != null)
                        localStorage.setItem('volumeIndex', arg);
                }







                function formatTime(arg) {
                    //formats the time
                    var s = Math.round(arg);
                    var m = 0;
                    if (s > 0) {
                        while (s > 59) {
                            m++;
                            s -= 60;
                        }
                        return String((m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s);
                    } else {
                        return "00:00";
                    }
                }


            }
            function handleVideoEnd() {
                //-function on video end
                if (full == 1) {
                    onFullScreen(); // we exit fullscreen if video has ended on fullscreen
                }
                if (o.type == 'normal' || o.type == 'audio') {
                    if (video) {
                        video.currentTime = 0;
                        video.pause();
                    }
                }
                if (o.type == 'youtube') {
                    //console.log(video.getDuration())
                    if (video) {
                        if (video && video.pauseVideo) {
                            wasPlaying = false;
                        }
                    }
                }
                if (o.videoGalleryCon != null) {
                    o.videoGalleryCon.videoEnd();
                }

            }
            function handleResize(e) {
                //console.log('triggered resize');
                if (is_ios()) {
                    //ios has a nasty bug wbhen the parent is scaled - iframes scale too
                    if (undefined != _vgparent) {
                        var aux = (_vgparent.get(0).var_scale)
                        //console.log(cthis);
                        //cthis.children('iframe').width((1/aux) * videoWidth); cthis.children('iframe').height((1/aux) * videoHeight);

                    }
                }
                if (o.responsive == 'on') {
                    conw = _rparent.width();
                    //console.log(cthis, _rparent, _rparent.width(), _rparent.height());
                    if (o.responsive_mode.indexOf('full') > -1) {
                        //videoWidth = _rparent.width();
                        if (o.responsive_mode.indexOf('fullx') > -1) {
                            videoWidth = _rparent.width();
                        }
                        if (o.responsive_mode.indexOf('fully') > -1) {
                            videoHeight = _rparent.height();
                        }
                        //console.log(videoHeight);
                        resizePlayer(videoWidth, videoHeight);
                    }

                    if (o.responsive_mode == 'pseudo') {
                        //===pseudo responsive
                        var aux = 'scale(' + (conw / totalWidth) + ')';
                        var newconh = (conw / totalWidth) * totalHeight;
                        if (conw < totalWidth) {
                            cthis.css({
                                '-moz-transform': aux
                                , '-webkit-transform': aux
                                , '-o-transform': aux
                                , 'transform': aux
                            })
                            _rparent.css({
                                'height': newconh
                            })
                            currScale = (conw / totalWidth);
                        } else {
                            cthis.css({
                                '-moz-transform': ''
                                , '-webkit-transform': ''
                                , '-o-transform': ''
                            })
                            _rparent.css({
                                'height': 'auto'
                            })
                            currScale = 1;
                        }
                    }
                }
                if (full === 1) {
                    ww = jQuery(window).width();
                    wh = jQuery(window).height();
                    resizePlayer(ww, wh);

                    if (cthis.find('.audioImg').length > 0) {
                        cthis.find('.audioImg').css({
                            'width': ww
                            , 'height': wh
                        })
                    }

                    cthis.css('transform', '');
                    currScale = 1;
                } else {

                }

            }
            function handleKeyPress(e) {
                //-check if space is pressed for pause
                if (e.charCode == 32) {
                    onPlayPause();
                }
            }

            function vimeo_windowMessage(e) {
                //-we receive iframe messages from vimeo here
                var data, method;
                //console.log(e);

                if (e.origin != 'https://player.vimeo.com' && e.origin != 'http://player.vimeo.com') {
                    return;
                }
                vimeo_url = ''
                vimeo_url = jQuery(video).attr('src').split('?')[0];
                try {
                    data = JSON.parse(e.data);
                    method = data.event || data.method;
                }
                catch (e) {
                    //fail silently... like a ninja!
                }


                //if(cthis.attr)
                if (dataSrc != data.player_id.substr(11)) {
                    return;
                }

                if (data != undefined) {
                    if (data.event == 'ready') {
                        //console.log(cthis);
                        if (o.autoplay == 'on') {
                            playMovie();
                        }
                        vimeo_data = {
                            "method": "addEventListener",
                            "value": "finish"
                        };
                        video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);

                    }
                    if (data.event == 'finish') {
                        handleVideoEvent();
                    }
                }
            }
            function onPlayPause() {
                //console.log('onPlayPause');
                //return;
                paused = false;
                if ((o.type == 'normal' || o.type == 'audio') && video.paused) {
                    paused = true;
                }
                if (o.type == 'youtube' && video.getPlayerState && video.getPlayerState() == 2) {
                    paused = true;
                }
                if (is_ie8()) {
                    if (ie8paused) {
                        playMovie();
                        ie8paused = false;
                    } else {
                        pauseMovie();
                        ie8paused = true;
                    }
                } else {
                    if (paused) {
                        playMovie();
                    } else {
                        pauseMovie();
                    }
                }

            }
            function onFullScreen() {
                //fullscreen trigger event
                var aux = cthis.get(0);
                var _t = jQuery(this);
                //totalWidth= $(window).width()
                //totalHeight= $(window).height()

                //console.log(_t, _t.parent().parent().parent().parent().parent())
                if (full == 0) {
                    full = 1;
                    cthis.addClass('is_fullscreen');
                    prefull_scale = cthis.css('transform');
                    //console.log(cthis, prefull_scale, cthis.css('transform'));
                    var elem = aux;
                    if (elem.requestFullScreen) {
                        elem.requestFullScreen();
                    } else if (elem.mozRequestFullScreen) {
                        elem.mozRequestFullScreen();
                    } else if (elem.webkitRequestFullScreen) {
                        elem.webkitRequestFullScreen();
                    }
                    //jQuery('body').css('overflow', 'hidden');
                    totalWidth= window.screen.width;
                    totalHeight= window.screen.height;
                    //console.log(totalWidth, totalHeight);
                    resizePlayer(totalWidth,totalHeight);
                    /*
                     cthis.css({
                     'position' : 'fixed',
                     'z-index' : 9999,
                     'left' : '0px',
                     'top' : '0px'
                     //,'width': totalWidth
                     //,'height': totalHeight
                     })
                     if(cthis.find('.audioImg').length>0){
                     cthis.find('.audioImg').css({
                     'width' : totalWidth
                     ,'height' : totalHeight
                     })
                     }
                     */
                    if (o.insideGallery == true) {
                        //dispatchEvent('goFullscreen');
                        //_t.parent().parent().parent().parent().parent().turnFullscreen();

                        if (o.videoGalleryCon != null) {
                            //o.videoGalleryCon.turnFullscreen();
                        }
                    }

                } else {
                    full = 0;
                    cthis.addClass('remove_fullscreen');
                    var elem = document;
                    if (prefull_scale != '') {
                        cthis.css('transform', prefull_scale);
                    }
                    if (elem.cancelFullScreen) {
                        elem.cancelFullScreen();
                    } else if (elem.mozCancelFullScreen) {
                        elem.mozCancelFullScreen();
                    } else if (elem.webkitCancelFullScreen) {
                        elem.webkitCancelFullScreen();
                    }
                    //$('body').css('overflow', original_body_overflow);
                    //console.log(cthis);
                    cthis.css({
                        'left': '0px'
                        , 'top': '0px'
                        , 'width': videoWidth
                        , 'height': videoHeight
                    })
                    resizePlayer(videoWidth, videoHeight);

                    if (cthis.find('.audioImg').length > 0) {
                        cthis.find('.audioImg').css({
                            'width': videoWidth
                            , 'height': videoHeight
                        })
                    }
                    if (o.insideGallery == true) {
                        // _t.parent().parent().parent().parent().parent().turnNormalscreen();
                    }
                }
            }

            function resizePlayer(warg, harg) {
                if (o.responsive_mode == 'full') {
                    //warg = cthis.width();
                }

                cthis.css({
                    'width': warg,
                    'height': harg
                });


                $(video).css({
                    width: warg,
                    height: harg
                });
                calculateDims(warg, harg);

                //console.log(warg);

                scrubbar = cthis.find('.scrubbar').children();
                //console.log(o.design_scrubbarWidth);
                scrubbarBg.width(warg + o.design_scrubbarWidth);
                //scrubbar.eq(0).height(12);
                //scrubbar.eq(1).height(12);

                infoPosX = parseInt(controlsDiv.find('.infoText').css('left'));
                infoPosY = parseInt(controlsDiv.find('.infoText').css('top'));
            }
            function calculateDims(warg, harg){

                if(o.design_skin!='skin_bigplay'){
                    controlsDiv.find('.background').css({
                        'width': warg + parseInt(o.design_background_offsetw)
                    })
                }

                cthis.find('.preview').children().eq(0).css({
                    'width': warg,
                    'height': harg
                })

                controlsDiv.css({
                    'width': warg
                });
            }


            function playMovie() {

                cthis.find('.preview').fadeOut('fast');

                if (o.type == 'vimeo') {
                    vimeo_data = {
                        "method": "play"
                    };
                    video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);
                    return;
                }
                //return;
                playcontrols.children().eq(0).css('visibility', 'hidden');
                playcontrols.children().eq(1).css('visibility', 'hidden');
                playcontrols.children().eq(2).css('visibility', 'visible');
                playcontrols.children().eq(3).css('visibility', 'visible');

                if (o.type == 'normal' || o.type == 'audio'){
                    video.play();
                }
                if (o.type == 'youtube'){
                    if(video.playVideo!=undefined){
                        video.playVideo();
                    }
                }
                if (o.settings_disableControls != 'on') {
                    cthis.children('.video-description').animate({
                        'opacity': 0
                    }, 500);
                }

                wasPlaying = true;
                //console.log(wasPlaying);

                cthis.trigger('videoPlay');
            }

            function pauseMovie() {
                playcontrols.children().eq(0).css('visibility', 'visible');
                playcontrols.children().eq(1).css('visibility', 'visible');
                playcontrols.children().eq(2).css('visibility', 'hidden');
                playcontrols.children().eq(3).css('visibility', 'hidden');
                if (o.type == 'normal' || o.type == 'audio') {
                    if(video!=undefined){
                        video.pause();
                    }else{
                        if(window.console != undefined){ console.info('warning: video undefined') };
                    }
                }
                if (o.type == 'youtube') {
                    if (video && video.pauseVideo) {
                        video.pauseVideo();
                    }
                }
                if (o.type == 'vimeo') {
                    if (/Opera/.test(navigator.userAgent)) {
                        return;
                    }
                    vimeo_data = {
                        "method": "pause"
                    };
                    try {
                        video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);
                    } catch (err) {
                        if (window.console){ console.log(err); }
                    }
                    return;
                }


                cthis.children('.video-description').animate({
                    'opacity': 1
                }, 500);

                wasPlaying = false;
            }
            //console.log(cthis);
            try {
                cthis.get(0).checkYoutubeState = function() {
                    if (o.type == 'youtube' && video.getPlayerState != undefined) {
                        //console.log("ceva", cthis, video.getPlayerState());
                        if (video.getPlayerState && video.getPlayerState() == 0) {
                            handleVideoEnd();
                        }
                    }
                }

            } catch (err) {
                if (window.console)
                    console.log(err);
            }
            /*
             window.checkYoutubeState=function(){
             // - we check if video youtube has ended so we can go to the next one

             }
             */

        }); // end each

    }


})(jQuery);





function onYouTubePlayerReady(playerId) {
    //alert('ytready')
    //alert(playerId)
    ytplayer = document.getElementById(playerId);
    //console.log(ytplayer);
    ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
    var aux = playerId.substr(8);
    var aux2 = jQuery(ytplayer).attr('data-suggestedquality');
    //console.log(aux2);
    ytplayer.loadVideoById(aux, 0, aux2);
    ytplayer.pauseVideo();
}

function onytplayerStateChange(newState) {
    //console.log(jQuery(ytplayer).parent().get(0), "Player's new state: " + newState, ytplayer.getAvailableQualityLevels());
    try {
        jQuery(ytplayer).parent().get(0).checkYoutubeState();
    } catch (err) {
        if (window.console)
            console.log(err);
    }
    //console.log(newState);
    //window.checkYoutubeState();
    //- we send the on end event to the gallery if it has one
    newState = parseInt(newState, 10);
    if (newState == 0) {
        //console.log(jQuery(ytplayer))
        //jQuery(ytplayer).parent().get(0).handleVideoEnd();
    }
}


function supports_translate() {
    if (is_chrome() || is_safari()) {
        return true;
    }
    if (is_firefox() && version_firefox() > 10) {
        return true;
    }
    return false;
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
}
;
function is_firefox() {
    if (navigator.userAgent.indexOf("Firefox") != -1) {
        return true;
    }
    ;
    return false;
}
;
function is_opera() {
    if (navigator.userAgent.indexOf("Opera") != -1) {
        return true;
    }
    ;
    return false;
}
;
function is_chrome() {
    return navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
}
;
function is_safari() {
    return navigator.userAgent.toLowerCase().indexOf('safari') > -1;
}
;
function version_ie() {
    return parseFloat(navigator.appVersion.split("MSIE")[1]);
}
;
function version_firefox() {
    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1);
        return(aversion);
    }
    ;
}
;
function version_opera() {
    if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1);
        return(aversion);
    }
    ;
}
;
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