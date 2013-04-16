	jQuery(document).ready(function($){
		//console.log('ceva');
		//console.log($('#colorpickerHolder'));
		var lastindex = 3;

        jQuery('.with_colorpicker').each(function(){
            var _t = jQuery(this);
            if(_t.hasClass('treated')){
                return;
            }
            if(jQuery.fn.farbtastic){
                _t.next().find('.picker').farbtastic(_t);

            }
            _t.addClass('treated');

            _t.bind('change', function(){
                //console.log(_t);
                jQuery('#customstyle_body').html('body{ background-color:' + $('input[name=color_bg]').val() + '} .dzsportfolio, .dzsportfolio a{ color:' + $('input[name=color_main]').val() + '} .dzsportfolio .portitem:hover .the-title, .dzsportfolio .selector-con .categories .a-category.active { color:' + $('input[name=color_high]').val() + ' }');
            });
            _t.trigger('change');
            _t.bind('click', function(){
                //console.log(_t);
                if(_t.next().hasClass('picker-con')){
                    _t.next().find('.the-icon').eq(0).trigger('click');
                }
            })
        });

        jQuery(document).delegate(".picker-con .the-icon", "click", function(){
            var _t = jQuery(this);
            var _c = _t.parent().children('.picker');
            //console.log(_c);
            if(_c.css('display')=='none'){
                _c.fadeIn('fast');
            }else{
                _c.fadeOut('fast');
            };
        });
        jQuery('.type-selector .big-button').bind('click', function(){
        	var _t = jQuery(this);
        	var ind = _t.parent().children().index(_t);
        	//console.log(ind);
        	jQuery('.settings-flash, .settings-html5vg').hide();
        	_t.parent().children().removeClass('active');
        	_t.addClass('active');
        	//console.log(_t);
        	if(ind==1){
        		jQuery('.settings-flash').show();
        	}
        	if(ind==0){
        		jQuery('.settings-html5vg').show();
        	}
        })

		$('.textinput').change(function(){
			var _t = $(this);


            if(_t.attr('name') == 'html5_color_thumbs_bg'){
                jQuery('.preview-html5player .preview-thumb .preview-thumb-bg').css('background-color', _t.val());
            }
            if(_t.attr('name') == 'html5_color_thumbs_bg_hover'){
                jQuery('.preview-html5player .preview-thumb.active .preview-thumb-bg').css('background-color', _t.val());
            }
            if(_t.attr('name') == 'html5_color_thumbs_borderc'){
                jQuery('.preview-html5player .preview-thumb').css('border-color', _t.val());
            }
            if(_t.attr('name') == 'html5_color_thumbs_titlec'){
                jQuery('.preview-html5player .preview-thumb .the-title').css('color', _t.val());
            }
            if(_t.attr('name') == 'html5_color_thumbs_w'){
                //console.log(_t, _t.val(), jQuery('.preview-html5player .preview-thumb .preview-thumb-pic'));
                jQuery('.preview-html5player .preview-thumb .preview-thumb-pic').css({'width' : _t.val()});
            }
            if(_t.attr('name') == 'html5_color_thumbs_h'){
                jQuery('.preview-html5player .preview-thumb .preview-thumb-pic').css('height', _t.val());
            }
            if(_t.attr('name') == 'html5_color_player_main'){
                jQuery('.preview-html5player .pp').css('border-color', _t.val());
                jQuery('.preview-html5player .vol, .preview-html5player .full').css('background-color', _t.val());
            }
            if(_t.attr('name') == 'html5_color_player_main_hover'){
                //jQuery('.preview-html5player .pp').css('background-color', _t.val());
            }
            if(_t.attr('name') == 'html5_color_player_highlight'){
                //console.log(_t, _t.val(), jQuery('.preview-html5player .scrp_bg'));
                jQuery('.preview-html5player .scrp_bg').css('background-color', _t.val());
            }

			if(_t.attr('name') == 'thumbs_width'){
				jQuery('.preview-flash .preview-thumb-bg').css('width', _t.val());
			}
			if(_t.attr('name') == 'thumbs_height'){
				jQuery('.preview-flash .preview-thumb-bg').css('height', _t.val());
			}
			if(_t.attr('name') == 'thumbs_space'){
				jQuery('.preview-flash .preview-thumb-bg').css('margin-bottom', _t.val() + "px");
			}
			if(_t.attr('name') == 'thumbs_bg'){
				jQuery('.preview-flash .preview-thumb-bg').css('background-color', _t.val());
			}
			if(_t.attr('name') == 'thumbs_pic_w'){
				jQuery('.preview-flash .preview-thumb-pic').css('width', _t.val());
			}
			if(_t.attr('name') == 'thumbs_pic_h'){
				jQuery('.preview-flash .preview-thumb-pic').css('height', _t.val());
			}
            //=== pp = playpause
			if(_t.attr('name') == 'pp_x'){
				var val = parseFloat(_t.val());
				if(val<0){
				jQuery('.preview-flash .pp').css('left', 'auto');
				jQuery('.preview-flash .pp').css('right', (-val + 16));
				}else{
				jQuery('.preview-flash .pp').css('left', (val + 16));
				jQuery('.preview-flash .pp').css('right', 'auto');
				}
			}
			if(_t.attr('name') == 'pp_y'){
				var val = parseFloat(_t.val());
				if(val<0){
				jQuery('.preview-flash .pp').css('top', 'auto');
				jQuery('.preview-flash .pp').css('bottom', (-val + 8));
				}else{
				jQuery('.preview-flash .pp').css('top', (val + 8));
				jQuery('.preview-flash .pp').css('bottom', 'auto');
				}
			}
			if(_t.attr('name') == 'pp_bg'){
				var val = (_t.val());
				jQuery('.preview-flash .pp').css('border-color', 'transparent ' + val);
			}
			if(_t.attr('name') == 'scr_x'){
				var val = parseFloat(_t.val());
				var offset = 0;
				var iden = '.preview-flash .scr_bg, .preview-flash .scrl_bg, .preview-flash .scrp_bg';
				if(val<0){
				jQuery(iden).css('left', 'auto');
				jQuery(iden).css('right', (-val + offset));
				}else{
				jQuery(iden).css('left', (val + offset));
				jQuery(iden).css('right', 'auto');
				}
			}
			if(_t.attr('name') == 'scr_y'){
				var val = parseFloat(_t.val());
				var offset = 11;
				var iden = '.preview-flash .scr_bg, .preview-flash .scrl_bg, .preview-flash .scrp_bg';
				if(val<0){
				jQuery(iden).css('top', 'auto');
				jQuery(iden).css('bottom', (-val + offset));
				}else{
				jQuery(iden).css('top', (val + offset));
				jQuery(iden).css('bottom', 'auto');
				}
			}
			if(_t.attr('name') == 'scr_w'){
				var val = parseFloat(_t.val());
				var offset = 0;
				var iden = '.preview-flash .scr_bg';
				if(val<0){
				jQuery(iden).css('width', (400 + val));
				}else{
				jQuery(iden).css('width', (val));
				}
			}
			if(_t.attr('name') == 'scr_h'){
				var val = parseFloat(_t.val());
				var offset = 0;
				var iden = '.preview-flash .scr_bg';
				if(val<0){
				jQuery(iden).css('height', (500 + val));
				}else{
				jQuery(iden).css('height', (val));
				}
			}
			if(_t.attr('name') == 'scr_bg'){
				var val = (_t.val());
				jQuery('.preview-flash .scr_bg').css('background-color', val);
			}
			if(_t.attr('name') == 'scrl_bg'){
				var val = (_t.val());
				jQuery('.preview-flash .scrl_bg').css('background-color', val);
			}
			if(_t.attr('name') == 'scrp_bg'){
				var val = (_t.val());
				jQuery('.preview-flash .scrp_bg').css('background-color', val);
			}
			if(_t.attr('name') == 'vol_x'){
				var val = parseFloat(_t.val());
				var offset = 11;
				var iden = '.preview-flash .vol';
				if(val<0){
				jQuery(iden).css('left', 'auto');
				jQuery(iden).css('right', (-val + offset));
				}else{
				jQuery(iden).css('left', (val + offset));
				jQuery(iden).css('right', 'auto');
				}
			}
			if(_t.attr('name') == 'vol_y'){
				var val = parseFloat(_t.val());
				var offset = 11;
				var iden = '.preview-flash .vol';
				if(val<0){
				jQuery(iden).css('top', 'auto');
				jQuery(iden).css('bottom', (-val + offset));
				}else{
				jQuery(iden).css('top', (val + offset));
				jQuery(iden).css('bottom', 'auto');
				}
			}
			
			if(_t.attr('name') == 'vol_bg'){
				var val = (_t.val());
				jQuery('.preview-flash .vol').css('background-color', val);
			}
			if(_t.attr('name') == 'full_x'){
				var val = parseFloat(_t.val());
				var offset = 11;
				var iden = '.preview-flash .full';
				if(val<0){
				jQuery(iden).css('left', 'auto');
				jQuery(iden).css('right', (-val + offset));
				}else{
				jQuery(iden).css('left', (val + offset));
				jQuery(iden).css('right', 'auto');
				}
			}
			if(_t.attr('name') == 'full_y'){
				var val = parseFloat(_t.val());
				var offset = 11;
				var iden = '.preview-flash .full';
				if(val<0){
				jQuery(iden).css('top', 'auto');
				jQuery(iden).css('bottom', (-val + offset));
				}else{
				jQuery(iden).css('top', (val + offset));
				jQuery(iden).css('bottom', 'auto');
				}
			}
			
			if(_t.attr('name') == 'full_bg'){
				var val = (_t.val());
				jQuery('.preview-flash .full').css('background-color', val);
			}
			if(_t.attr('name') == 'settings_bg'){
				var val = (_t.val());
				jQuery('.preview-flash .player_bg').css('background-color', val);
			}
			if(_t.attr('name') == 'settings_controls_bg'){
				var val = (_t.val());
				jQuery('.preview-flash .controls_bg').css('background-color', val);
			}
			if(_t.attr('name') == 'settings_controls_bg_h'){
				jQuery('.preview-flash .controls_bg').css('height', _t.val());
			}
			
		})
			
		$('.toggle-title').bind('click', function(){
			var $t = $(this);
			if($t.hasClass('opened')){
				($t.parent().find('.toggle-content').slideUp('fast'));
				$t.removeClass('opened');
			}else{
				($t.parent().find('.toggle-content').slideDown('fast'));
				$t.addClass('opened');
			}
		})
			
		$('.save-button').bind('click', function(){
			//console.log(jQuery(this).parent().children('.preloader'));
			jQuery(this).parent().children('.preloader').animate({'opacity': 1}, {queue:false, duration:1});
			var aux = ($('.textinput').serialize());

			var poster_url_path = 'index.php';
			if(zsvg_settings!=undefined && zsvg_settings.zsvg_dc_poster_url_path!=undefined){
				poster_url_path = zsvg_settings.zsvg_dc_poster_url_path;
			}
			//console.log(poster_url_path);
			$.post(poster_url_path, { thedata: aux },
   function(data) {
			jQuery('.preloader').animate({'opacity': 0}, {queue:false, duration:1});
			if(console)
    	 	console.log("Data Loaded: " + data);
   			});
			
			return false;
		})
			
		$('.preview-button').bind('click', function(){
			//console.log(jQuery(this).parent().children('.preloader'));
			jQuery(this).parent().children('.preloader').animate({'opacity': 1}, {queue:false, duration:1});
			var aux = ($('.textinput').serialize())
			var randomnumber=Math.floor(Math.random()*1000);
			aux += '&rand=' + randomnumber;
			$.post("index.php", { previewdata: aux },
   function(data) {
			jQuery('.preloader').animate({'opacity': 0}, {queue:false, duration:1});
			if(console)
    	 	console.log("Data Loaded: " + data);
   			});
			
			window.open('preview.php?designrand=' + randomnumber + '&swfloc=' + window.swfloc,'mywindow','width=700,height=330');
			
			
			return false;
		})
		
		jQuery('.textinput').change();
			
		$('#example1').bind('click', function(){
			for(i=0;i<jQuery('.textinput').length;i++){
				var $cache = jQuery('.textinput').eq(i);
				var nm = $cache.attr('name');
				if($cache.attr('name')=='thumbs_bg' || nm=='settings_controls_bg')
				$cache.val('#f5f5f5');
				if($cache.attr('name')=='thumbs_text_title_c' || nm == 'pp_bg' || nm == 'scrp_bg' || nm == 'vol_bg' || nm == 'full_bg')
				$cache.val('#99f099');
				if(nm == 'scr_bg')
				$cache.val('#e6e6e6');
				if(nm == 'scl_bg')
				$cache.val('#858585');
				$cache.change();
				
			}
		})
	})