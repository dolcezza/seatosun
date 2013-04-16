
var sliderIndex = 0;
var itemIndex = [0];
var currSlider_nr=-1;
var currSlider;
var targetInput;
var global_items = 0;

function sliders_ready(){
    zsvg_settings.currSlider = parseInt(zsvg_settings.currSlider, 10);
    //console.log(zsvg_settings.currSlider)

        jQuery('.saveconfirmer').fadeOut('slow');
	jQuery('.add-slider').bind('click', sliders_click_addslider);
	
        jQuery(document).on("click", ".item-preview", item_open);
	//currSlider = jQuery('.slider-con').eq(currSlider_nr);
	jQuery('.master-save').bind('click', sliders_saveall);
    jQuery('.slider-save').bind('click', sliders_saveslider);
	jQuery('.save-mainoptions').bind('click', mo_saveall);
        jQuery(document).on("change", ".main-id", sliders_change_mainid);
        jQuery(document).on("change", ".main-thumb", sliders_change_mainthumb);
        jQuery(document).on("click", ".slider-edit", sliders_click_slideredit);
        jQuery(document).on("click", ".slider-duplicate", sliders_click_sliderduplicate);
        jQuery(document).on("click", ".slider-delete", sliders_click_sliderdelete);
        jQuery(document).on("click", ".slider-sliderexport", sliders_click_sliderexport);
        jQuery(document).on("click", ".slider-embed", sliders_click_sliderembed);
        
        jQuery(document).on("click", ".item-delete", sliders_click_itemdelete);
        jQuery(document).on("click", ".item-duplicate", sliders_click_itemduplicate);
        
        jQuery(document).on("click", ".upload_file", sliders_wpupload);
        jQuery(document).on("change", ".item-type", sliders_itemchangetype);
	jQuery('.item-type').trigger('change');
	
	jQuery('#importytplaylist').bind('click', extra_importytplaylist)
	jQuery('#importytuser').bind('click', extra_importytuser)
	jQuery('#importvimeouser').bind('click', extra_importvimeouser)
	
        jQuery(document).on("click", ".picker-con .the-icon", function(){
		var _t = jQuery(this);
		var _c = _t.parent().children('.picker');
		if(_c.css('display')=='none'){
			_c.fadeIn('fast');
		}else{
			_c.fadeOut('fast');
		};
	});
	
	jQuery('.import-export-db-con .the-toggle').click(function(){
		var _t = jQuery(this);
		var _cont = _t.parent().children('.the-content-mask');
		/*
		if(_cont.css('display')=='none')
		_cont.slideDown('slow');
		else
		_cont.slideUp('slow');
		*/
		var cont_h = _cont.children('.the-content').height() + 50 + 19;
		if(_cont.css('height')=='0px')
		_cont.stop().animate({
                    'height' : cont_h
		}, 200);
		else
		_cont.stop().animate({
                    'height' : 0
		}, 200);
		
	});
        dzsvg_setupDbSelect();
        setTimeout(sliders_addlisteners, 1000);
	jQuery('.import-export-db-con .the-content-mask').css({
        'height':0
    })
	
}
function dzsvg_setupDbSelect(){
    var _c = jQuery('.db-select.dzsvg');
    //console.log(_c);
        //console.log('muie crys');
    _c.append('<div class="db-select-nicecon"><div id="db-select-scroller-con" class="scroller-con easing" style="width: 180px; height: 80px;"><div class="inner"></div></div></div>');
    _c.find('.main-select').children().each(function(){
        var _t = jQuery(this);
        _c.find('.inner').append('<div class="a-db-option">select database <span class="strong">'+_t.text()+'</span><a href="'+_t.attr('data-newurl')+'" class="todb">&raquo;</a></div>');
    })
    _c.find('.inner').append('<div class="a-db-option">create database <input class="newdb"/><a href=" " class="todb createdb">&raquo;</a></div>');
    
    if(jQuery.fn.scroller){
        //console.log(jQuery('#db-select-scroller-con'));
	jQuery('#db-select-scroller-con').scroller({
		settings_skin:'skin_slider'
	});
    }
    _c.find('.todb.createdb').eq(0).bind('click', function(){
        var _t = jQuery(this);
        //console.log(_t);
        if(_t.prev().val()==''){
            _t.prev().addClass('attention');
            setTimeout(function(){
            _t.prev().removeClass('attention');

            },1000)
            return false;

        }else{
            var aux = _c.find('.replaceurlhelper').eq(0).text();
            aux = aux.replace('replaceurlhere', _t.prev().val());
            _t.attr('href', aux);
        }
    })
    jQuery('.dzsvg .btn-show-dbs').bind('click',function(){
        //console.log(jQuery('.db-select-nicecon').eq(0));
        var _t = jQuery(this).parent();
        if(_t.find('.db-select-nicecon').eq(0).hasClass('active')){
            _t.find('.db-select-nicecon').eq(0).removeClass('active')
        }else{
            _t.find('.db-select-nicecon').eq(0).addClass('active')
        }
    })
}




function sliders_click_sliderexport(){
    var _t = jQuery(this);
    var par = _t.parent().parent().parent();
    var ind = par.parent().children().index(par);
    var sname = par.children('td').eq(0).html()
    //console.log(_t, ind);
    tb_show('Slide Editor', zsvg_settings.thepath + 'admin/sliderexport.php?KeepThis=true&width=400&height=200&slidernr=' + ind + '&slidername=' + sname + '&TB_iframe=true');
    return false;
}
function sliders_click_sliderembed(){
    var _t = jQuery(this);
    var par = _t.parent().parent().parent();
    var ind = par.parent().children().index(par);
    var sname = par.children('td').eq(0).html()
    //console.log(_t, ind);
    //jQuery('#preparedforsliderembed').html('use this shortcode for embedding: [slider id="' + sname + '"]');
    //jQuery('#preparedforsliderembed').delay(4000).fadeOut('slow');
    
    
    jQuery('.saveconfirmer').html('use this shortcode for embedding: [videogallery id="' + sname + '"]');
    jQuery('.saveconfirmer').stop().fadeIn('fast').delay(4000).fadeOut('fast');
    //tb_show('Slide Editor', themesettings.thepath + 'admin/slidersadmin/sliderembed.php?KeepThis=true&width=400&height=200&slidernr=' + ind + '&slidername=' + sname + '&TB_iframe=true');
    return false;
}

function extra_importytplaylist(){
    //console.log('ceva');
    var aux = jQuery('#import_inputtext').val();
    var i2=0;
    var data = {
            action: 'zsvg_import_ytplaylist',
            postdata: aux
    };
    
	jQuery.post(ajaxurl, data, function(response) {
            //console.log(response);
            if(window.console !=undefined ){
		//console.log(response);
            }
            if(response.substr(0,6)=='error:'){
                //console.log('ceva');
                jQuery('.import-error').html(response.substr(7));
                jQuery('.import-error').fadeIn('fast').delay(5000).fadeOut('slow');
                return false;
            }
            var obj = jQuery.parseJSON(response);
            if(obj.length>0){
                var aux = itemIndex[currSlider_nr];
                for(i2=0;i2<aux;i2++){
                    sliders_delete_item(0);
                }
                for(i2=0;i2<obj.length; i2++){
                    //console.log(itemIndex[currSlider_nr]);
                    var arg3 = {
                        title : obj[i2].title
                        , type : obj[i2].type
                        , thumb : response[i2].thethumb
                    }
                    sliders_additem(currSlider_nr, obj[i2].source, arg3);
                }
            }
	});
    return false;
}
function extra_importytuser(){
    //console.log('ceva');
    var aux = jQuery('#import_inputtext').val();
    var i2=0;
    var data = {
            action: 'zsvg_import_ytuser',
            postdata: aux
    };
    
	jQuery.post(ajaxurl, data, function(response) {
            //console.log(response);
            if(window.console !=undefined ){
		console.log(response);
            }
            if(typeof(response)=='string' && response.substr(0,6)=='error:'){
                //console.log('ceva');
                jQuery('.import-error').html(response.substr(7));
                jQuery('.import-error').fadeIn('fast').delay(5000).fadeOut('slow');
                return false;
            }
            var obj;
            if(typeof(response)=='string'){
                obj = jQuery.parseJSON(response);
            }else{
                obj = response;
            }
            
            if(obj!=null && obj.length>0){
                var aux = itemIndex[currSlider_nr];
                for(i2=0;i2<aux;i2++){
                    sliders_delete_item(0);
                }
                for(i2=0;i2<obj.length; i2++){
                    //console.log(itemIndex[currSlider_nr]);
                    var arg3 = {
                        title : obj[i2].title
                        , type : obj[i2].type
                        , thumb : obj[i2].thethumb
                    }
                    sliders_additem(currSlider_nr, obj[i2].source, arg3);
                }
            }else{
                if(window.console !=undefined ){
                    console.log('admin.js - obj is null :(');
                }
                
            }
	}, "json");
    return false;
}
function extra_skin_hiddenselect(){
    for(i=0;i<jQuery('.select-hidden-metastyle').length;i++){
        var _t = jQuery('.select-hidden-metastyle').eq(i);
        if(_t.hasClass('inited')){
            continue;
        }
        //console.log(_t);
        _t.addClass('inited');
        _t.children('select').eq(0).bind('change', change_selecthidden);
        change_selecthidden(null, _t.children('select').eq(0));
        _t.find('.an-option').bind('click', click_anoption);
    }
    function change_selecthidden(e, arg){
        var _c = jQuery(this);
        if(arg!=undefined){
            _c = arg;
        }
        var _con = _c.parent();
        var selind = _c.children().index(_c.children(':selected'));
        var _slidercon = _con.parent().parent();
        //console.log(selind);
        _con.find('.an-option').removeClass('active');
        _con.find('.an-option').eq(selind).addClass('active');
        //console.log(_con);
        do_changemainsliderclass(_slidercon, selind);
    }
    function click_anoption(e){
        var _c = jQuery(this);
        var ind = _c.parent().children().index(_c);
        var _con = _c.parent().parent();
        var _slidercon = _con.parent().parent();
        _c.parent().children().removeClass('active');
        _c.addClass('active');
        _con.children('select').eq(0).children().removeAttr('selected');
        _con.children('select').eq(0).children().eq(ind).attr('selected', 'selected');
        do_changemainsliderclass(_slidercon, ind);
        //console.log(_c, ind, _con, _slidercon);
    }
    function do_changemainsliderclass(arg, argval){
        //extra function - handmade
        //console.log(arg, arg)
        if(arg.hasClass('slider-con')){
            arg.removeClass('mode_normal'); arg.removeClass('mode_ytuser'); arg.removeClass('mode_ytplaylist'); arg.removeClass('mode_vimeouser');
            if(argval==0){
                arg.addClass('mode_normal')
            }
            if(argval==1){
                arg.addClass('mode_ytuser')
            }
            if(argval==2){
                arg.addClass('mode_ytplaylist')
            }
            if(argval==3){
                arg.addClass('mode_vimeouser')
            }
        
        }
        if(arg.hasClass('item-settings-con')){
            arg.removeClass('type_youtube'); arg.removeClass('type_normal'); arg.removeClass('type_vimeo'); arg.removeClass('type_audio'); arg.removeClass('type_image'); arg.removeClass('type_link');
            
            if(argval==0){
                arg.addClass('mode_youtube')
            }
            if(argval==1){
                arg.addClass('mode_normal')
            }
            if(argval==2){
                arg.addClass('mode_vimeo')
            }
            if(argval==3){
                arg.addClass('mode_audio')
            }
            if(argval==4){
                arg.addClass('mode_image')
            }
            if(argval==5){
                arg.addClass('mode_link')
            }
        }
    }
    
}

function reskin_select(){
	for(i=0;i<jQuery('select').length;i++){
		var _cache = jQuery('select').eq(i);
		//console.log(_cache.parent().attr('class'));
		
		if(_cache.hasClass('styleme')==false || _cache.parent().hasClass('select_wrapper') || _cache.parent().hasClass('select-wrapper')){
		continue;
		}
		var sel = (_cache.find(':selected'));
		_cache.wrap('<div class="select-wrapper"></div>')
		_cache.parent().prepend('<span>' + sel.text() + '</span>')
	}
    jQuery(document).undelegate(".select-wrapper select", "change");
    jQuery(document).delegate(".select-wrapper select", "change",  change_select);
        

    function change_select(){
            var selval = (jQuery(this).find(':selected').text());
            jQuery(this).parent().children('span').text(selval);
    }

}
function extra_importvimeouser(){
    var aux = jQuery('#import_inputtext').val();
    var i2=0;
    var data = {
            action: 'zsvg_import_vimeouser',
            postdata: aux
    };
    
	jQuery.post(ajaxurl, data, function(response) {
            if(window.console !=undefined ){
		//console.log(response);
            }
            if(response.substr(0,6)=='error:'){
                //console.log('ceva');
                jQuery('.import-error').html(response.substr(7));
                jQuery('.import-error').fadeIn('fast').delay(5000).fadeOut('slow');
                return false;
            }
            var obj = jQuery.parseJSON(response);
            if(obj.length>0){
                var aux = itemIndex[currSlider_nr];
                for(i2=0;i2<aux;i2++){
                    sliders_delete_item(0);
                }
                for(i2=0;i2<obj.length; i2++){
                    //console.log(itemIndex[currSlider_nr]);
                    var arg3 = {
                        title : obj[i2].title
                        , type : obj[i2].type
                        , thumb : obj[i2].thethumb
                    }
                    sliders_additem(currSlider_nr, obj[i2].source, arg3);
                }
            }
	});
    return false;
}
function sliders_reinit(){
    jQuery('.with_colorpicker').each(function(){
    	var _t = jQuery(this);
    	if(_t.hasClass('treated')){
    		return;
    	}
        if(jQuery.fn.farbtastic){
    	_t.next().find('.picker').farbtastic(_t);
            
        }
    	_t.addClass('treated');
    });
}
function sliders_itemchangetype(){
	var _t = jQuery(this);
	var selval = _t.find(':selected').val();
	//var 
	var target = _t.parent().parent().parent().find('.main-source');
	//console.log(_t);
	if(selval=='inline'){
		target.css({
			'height' : 80,
			'resize' : 'vertical'
		});
	}else{
		target.css({
			'height' : 23,
			'resize' : 'none'
		});
	}
	
}
function sliders_wpupload(){
    targetInput = jQuery(this).prev();
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true&amp;post_id=1&amp;width=640&amp;height=105');
        var backup_send_to_editor      = window.send_to_editor;
        var intval      = window.setInterval(function() {
                            if ( jQuery('#TB_iframeContent').attr('src').indexOf( "&field_id=" ) !== -1 ) {
                              jQuery('#TB_iframeContent').contents().find('#tab-type_url').hide();
                            }
                            jQuery('#TB_iframeContent').contents().find('.savesend .button').val("Upload to Video Gallery"); 
                          }, 50);
	window.send_to_editor = function (arg) {
	    var fullpath = arg
	    ,fullpathArray = fullpath.split('>');
	    //fullpath = fullpathArray[1] + '>';


	    var aux3 = jQuery(fullpath).attr('href');


	    targetInput.val(aux3);
            targetInput.trigger('change');
	    tb_remove();
            window.clearInterval(intval);
          window.send_to_editor = backup_send_to_editor;
	}



return false;
}
function sliders_click_slideredit(){

    if(zsvg_settings.is_safebinding == 'on' ){

    }else{
	var index = jQuery('.slider-edit').index(jQuery(this));
	sliders_showslider(index);
	return false;
    }
}
function sliders_click_sliderduplicate(){
	var index = jQuery('.slider-duplicate').index(jQuery(this));
	//sliders_showslider(index);
	
	jQuery('.main_sliders').children('tbody').append('<tr class="slider-in-table"><td>'+jQuery('.slider-con').eq(index).find('.main-id').eq(0).val()+'</td><td class="button_view"><strong><a href="#" class="slider-action slider-edit">Edit</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-embed">Embed</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-sliderexport">Export</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-duplicate">Duplicate</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-delete">Delete</a></strong></td></tr>')
	jQuery('.master-settings').append(jQuery('.slider-con').eq(index).clone());
	for(i=0; i<jQuery('.slider-con').eq(sliderIndex).find('.textinput').length;i++){
		var _cache = jQuery('.slider-con').eq(sliderIndex).find('.textinput').eq(i);
		sliders_rename(_cache, sliderIndex, 'same')
	}
	
	
	for(i=0;i<jQuery('.slider-con').eq(index).find('textarea').length;i++){
		var _c = jQuery('.slider-con').last().find('textarea').eq(i);
		//console.log(_c);
		_c.val(jQuery('.slider-con').eq(index).find('textarea').eq(i).val());
	}
	
	sliders_addlisteners();
	itemIndex[sliderIndex] = 0;
	++sliderIndex;
	
	
	
	return false;
}
function sliders_click_itemdelete(){
	var index = currSlider.find('.item-delete').index(jQuery(this));
	//console.log(index, itemIndex[currSlider_nr])
        
	var arg=index;
        sliders_delete_item(arg);
	return false;
}
function sliders_delete_item(arg){
	currSlider.find('.item-con').eq(arg).remove();
	if(arg<itemIndex[currSlider_nr]-1){
		for(i=arg;i<itemIndex[currSlider_nr]-1;i++){
			var _c = currSlider.find('.item-con').eq(i);
			for(j=0; j<_c.find('.textinput').length;j++){
			sliders_rename(_c.find('.textinput').eq(j), currSlider_nr, i);
			}
		}
	}
	itemIndex[currSlider_nr]--;
	return false;
}
function sliders_click_itemduplicate(){
var $ = jQuery.noConflict();
	var index = currSlider.find('.item-duplicate').index(jQuery(this));
	var _cache = currSlider.find('.items-con').eq(0);
	_cache.append(jQuery(this).parent().clone());
	console.log(_cache.children().last());
	for(i=0;i<_cache.children().last().find('.textinput').length;i++){
		sliders_rename(_cache.children().last().find('.textinput').eq(i), currSlider_nr, itemIndex[currSlider_nr]);
	}
	for(i=0;i<_cache.children().last().find('textarea').length;i++){
		var _c = _cache.children().last().find('textarea').eq(i);
		_c.val(_cache.children().eq(index).find('textarea').eq(i).val());
	}
	setTimeout(reskin_select, 10);
		itemIndex[currSlider_nr]++;
		
	return false;
	//sliders_showslider(index);
	
}
function sliders_click_sliderdelete(){

    var r=confirm("are you sure you want to delete ?");
    if (r==true){
    }
    else{
        return false;
    }

    if(zsvg_settings.is_safebinding == 'on' ){

    }else{
        var index = jQuery('.slider-delete').index(jQuery(this));
        sliders_deleteslider(index);
        return false;
    }

}
function sliders_deleteslider(arg){
var $ = jQuery.noConflict();
	//console.log(arg, sliderIndex);
	jQuery('.main_sliders').children('tbody').children().eq(arg).remove();
    jQuery('.slider-con').eq(arg).remove();
	if(arg<sliderIndex-1){
		for(i=arg;i<sliderIndex-1;i++){
			_cache = jQuery('.slider-con').eq(i);
			for(j=0; j<_cache.find('.textinput').length;j++){
				var _c2 = _cache.find('.textinput').eq(j);
				sliders_rename(_c2, i, 'same')
			}
		}
	}
	
	sliderIndex--;
	if(arg==currSlider_nr){
	currSlider_nr=-1;
	sliders_showslider(arg);
	}
}
var extra_targettagseditor;
function sliders_addlisteners(){
	jQuery('.add-item').unbind();
	jQuery('.add-item').bind('click', click_additem);
	jQuery('.items-con').sortable({
		placeholder: "ui-state-highlight",
		update: item_onsorted
	});
        if(jQuery.fn.singleUploader){
        jQuery('.dzs-upload').singleUploader();
        }
        if(window.dzstoggle_initalltoggles!=undefined){
            dzstoggle_initalltoggles();
        }else{
            if(window.console){ console.info('toggles not defined'); };
        }
	setTimeout(reskin_select, 100);
        
        jQuery('.btn-tageditor').each(function(){
            var _t = jQuery(this);
            if(_t.hasClass('inited')){
                
            }else{
                _t.addClass('inited');
                _t.bind('click', function(e){
                    var _tt = jQuery(this);
                    
                    extra_targettagseditor = _tt.prev();
                    //console.log(_tt.prev(), extra_targettagseditor.val());
                    e.preventDefault();
                    jQuery.fn.zoomBox.open(zsvg_settings.thepath + 'admin/tagseditor/popup.php?initer=' + extra_targettagseditor.val(), 'iframe', {width: 400, height: 800});
                    return false;
                });
            }
        });
        //console.log('cva');
        extra_skin_hiddenselect();
}
function extra_receiver_tagsreceived(arg){
    extra_targettagseditor.val(arg);
    jQuery.fn.zoomBox.close();
}
function sliders_click_addslider(){

    if(zsvg_settings.is_safebinding == 'on' ){

    }else{
        sliders_addslider();
        return false;
    }
}
function sliders_addslider(args){
    //console.log(jQuery('.main_sliders').children('tbody').children().length);
    //console.log(zsvg_settings)
    var sliderslen = jQuery('.main_sliders').children('tbody').children().length;
    var auxurl = (zsvg_settings.urlcurrslider).replace('_currslider_', sliderslen);
    var auxdelurl = (zsvg_settings.urldelslider).replace('_currslider_', sliderslen);
    var auxname = 'default';




        if(args!=undefined && args.name!=undefined){
            auxname = args.name;
        }

        jQuery('.main_sliders').children('tbody').append('<tr class="slider-in-table"><td>'+auxname+'</td><td class="button_view"><strong><a href="'+auxurl+'" class="slider-action slider-edit">Edit</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-embed">Embed</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-sliderexport">Export</a></strong></td><td class="button_view"><strong><a href="#" class="slider-action slider-duplicate">Duplicate</a></strong></td><td class="button_view"><strong><a href="'+auxdelurl+'" class="slider-action slider-delete">Delete</a></strong></td></tr>');
        if(zsvg_settings.is_safebinding == 'on' ){
            if(zsvg_settings.currSlider == sliderslen){
                jQuery('.master-settings').append(sliderstructure);
            }
        }else{
            jQuery('.master-settings').append(sliderstructure);
        }
        for(i=0; i<jQuery('.slider-con').eq(sliderIndex).find('.textinput').length;i++){
            var _cache = jQuery('.slider-con').eq(sliderIndex).find('.textinput').eq(i);
            sliders_rename(_cache, sliderIndex, 'settings')
        }
        sliders_addlisteners();
        itemIndex[sliderIndex] = 0;
        ++sliderIndex;
        sliders_reinit();
        return false;

}
function sliders_additem(arg1, arg2, arg3){
var j =0;
    //====arg1 the slider index
	var _cache = jQuery('.items-con').eq(arg1);
	_cache.append(itemstructure);
	for(i=0;i<_cache.children().last().find('.textinput').length;i++){
		sliders_rename(_cache.children().last().find('.textinput').eq(i), arg1, itemIndex[arg1]);
	}
	if(arg2!=undefined){
		_cache.children().last().find('.textinput').eq(0).val(arg2)
		_cache.children().last().find('.textinput').eq(0).trigger('change');
	}
	if(arg3!=undefined){
            if(arg3.title!=undefined){
		_cache.children().last().find('.textinput').eq(3).val(arg3.title)
		_cache.children().last().find('.textinput').eq(3).trigger('change');
            }
            if(arg3.thumb!=undefined){
		_cache.children().last().find('.textinput').eq(1).val(arg3.thumb)
		_cache.children().last().find('.textinput').eq(1).trigger('change');
            }
            if(arg3.type!=undefined){
                var _c = _cache.children().last().find('.textinput').eq(2);
		_c.find(':selected').attr('selected', '');
                
                for(j=0;j<_c.children().length;j++){
                        if(_c.children().eq(j).text() == arg3.type)
                        _c.children().eq(j).attr('selected', 'selected');
                }
               // console.log(_c);
		_c.trigger('change');
            }
        }
	setTimeout(reskin_select, 10)
    itemIndex[arg1]++;
    global_items++;
		
	return false;
}
function check_global_items(){
    var limit = 15;

    if(zsvg_settings.is_safebinding == 'on' ){
        limit = 75;
    }
    //console.log(global_items)
    if(global_items>limit){
        jQuery('.notes').append('<div class="warning"><strong>Warning</strong> - you have many items in this database. max_input_vars is defaulted to 1000. What this means is if you have more then '+limit+' items across all the galleries in this database, saving via the <strong>Save All Sliders</strong> option might not work. and there are three possible solutions to this:</p>        <ol>  <li>( recommended ) distribute your galleries accross multiple databases - <a href="http://digitalzoomstudio.net/docs/wpvideogallery/#explaination_dbs">see how</a>           <li>OR increase max_input_vars via php.ini or .htaccess file       <li>OR use the <strong>save slider</strong> ( single ) option - you can only save the current slider you are editing with this                </ol>        <p>Also remember to backup regularly via the Export option from the Gear menu</p></div>')
    }
}
function sliders_showslider(arg1){
    console.log(arg1, currSlider_nr);
	if(arg1==currSlider_nr)
	return;
	jQuery('.slider-con').eq(currSlider_nr).fadeOut('fast');
	jQuery('.slider-con').eq(arg1).fadeIn('fast');
	currSlider_nr = arg1;
	currSlider = jQuery('.slider-con').eq(currSlider_nr);
        jQuery('.slider-con').removeClass('currSlider');
        currSlider.addClass('currSlider');
}
function click_additem(){



	sliders_additem(currSlider_nr)
	sliders_addlisteners();

	return false;
}
function sliders_change_mainid(){
	var _t=jQuery(this);
	var index=jQuery('.main-id').index(_t);
    if(zsvg_settings.is_safebinding!='on'){
	jQuery('.main_sliders tbody').children().eq(index).children().eq(0).text(_t.val());
    }
}
function sliders_change_mainthumb(){
	var _t=jQuery(this);
        var _con = _t.parent().parent().parent().parent();
        //console.log(_con.find('textarea[data-label=source]').eq(0).val());
        //console.log(_t, _con);
        if(_t.val()=='' && _con.find('select[data-label=type]').eq(0).val()=='youtube'){
            aux = 'http://img.youtube.com/vi/'+_con.find('textarea[data-label=source]').eq(0).val()+'/0.jpg';
            _t.val(aux);
            //
        }
	_con.parent().find('.item-preview').css('background-image', "url(" + _t.val() + ")");
        //console.log(_t);
}
function sliders_change(arg1,arg2,arg3,arg4){
    //select the main slider
	var _cache = jQuery('.slider-con').eq(arg1);
	if(arg2=="settings"){
		for(i=0;i<_cache.find('.mainsetting').length;i++){
			
			var _c2 = _cache.find('.mainsetting').eq(i);
			var aux = arg1 + "-" + arg2 + "-" + arg3;
			if(_c2.attr('name') == aux){
                            _c2.val(arg4);
                            if(_c2[0].nodeName=='SELECT'){
                                    for(j=0;j<_c2.children().length;j++){
                                        var auxval = _c2.children().eq(j).text();
                                        if(_c2.children().eq(j).attr('value')!='' && _c2.children().eq(j).attr('value')!=undefined){
                                            auxval = _c2.children().eq(j).attr('value');
                                        }
                                            if(auxval == arg4)
                                            _c2.children().eq(j).attr('selected', 'selected');
                                    }
                            }
                            if(_c2[0].nodeName=='INPUT' && _c2.attr('type')=='checkbox'){
                                    if(arg4=='on'){
                                            _c2.attr('checked', 'checked');
                                    }
                            }
                            _c2.change();
			}
		}
	}else{
		var _c2 = _cache.find('.item-con').eq(arg2);
		for(i=0;i<_c2.find('.textinput').length;i++){
			var _c3 = _c2.find('.textinput').eq(i);
			var aux = arg1 + "-" + arg2 + "-" + arg3;
			if(_c3.attr('name') == aux){
			_c3.val(arg4);
			if(_c3[0].nodeName=='SELECT'){
				for(j=0;j<_c3.children().length;j++){
					if(_c3.children().eq(j).text() == arg4)
					_c3.children().eq(j).attr('selected', 'selected');
				}
			}
				_c3.change();
			
			}
		}
		
	}
}
function sliders_rename(arg1, arg2, arg3, arg4){
		var name = arg1.attr('name');
		var aname = name.split('-');
		
		if(arg2!='same'){
		if(arg2==undefined){
		aname[0] = currSlider_nr;
		}else{
		aname[0]= arg2;
		}
		}
		if(arg3!='same'){
		if(arg3==undefined){
		aname[1] = itemIndex[currSlider_nr];
		}else{
		aname[1]= arg3;
		}
		}
		var str = aname[0] + '-' + aname[1] + '-' + aname[2];
		arg1.attr('name', str);
	
}
function item_onsorted(){
	//console.log(currSlider.find('.item-con'))
	for(i=0;i<currSlider.find('.item-con').length;i++){
		var _cache = currSlider.find('.item-con').eq(i);
		for(j=0;j<_cache.find('.textinput').length;j++){
			var _cache2 = _cache.find('.textinput').eq(j);
			sliders_rename(_cache2, undefined, i);
		}
	}
}
function item_open(){
	var _t = jQuery(this);
	if(_t.parent().children('.item-settings-con').css('display')=='none'){
		_t.parent().children('.item-settings-con').fadeIn('fast')
	}else{
		_t.parent().children('.item-settings-con').fadeOut('fast')
	}
}

function sliders_saveslider(){
    jQuery('#save-ajax-loading').css('visibility', 'visible');
    var mainarray = currSlider.serializeAnything();

    //console.log(currSlider, currSlider.serializeAnything(), currSlider_nr);

    var auxslidernr = currSlider_nr;

    if(zsvg_settings.is_safebinding=='on'){
        auxslidernr = zsvg_settings.currSlider;
    }

    var data = {
        action: 'zsvg_ajax'
        ,postdata: mainarray
        ,sliderid : auxslidernr
        , currdb: zsvg_settings.currdb
    };
    jQuery.post(ajaxurl, data, function(response) {
        if(window.console != undefined){
            console.log('Got this from the server: ' + response);
        }
        jQuery('#save-ajax-loading').css('visibility', 'hidden');
        if(response.indexOf('success')>-1){
            jQuery('.saveconfirmer').html('Options saved.');
        }else{
            jQuery('.saveconfirmer').html('There seemed to be a problem ? Please check if options were actually saved.');
        }
        jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
    });
    return false;
}

function sliders_saveall(){
	jQuery('#save-ajax-loading').css('visibility', 'visible');
	var mainarray = jQuery('.master-settings').serialize();
	var data = {
		action: 'zsvg_ajax'
        ,postdata: mainarray
        ,currdb: zsvg_settings.currdb
	};
        jQuery('.saveconfirmer').html('Options saved.');
        jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
	jQuery.post(ajaxurl, data, function(response) {
            if(window.console !=undefined ){
		console.log('Got this from the server: ' + response);
            }
            jQuery('#save-ajax-loading').css('visibility', 'hidden');
	});
        
	return false;
}
function mo_saveall(){
	jQuery('#save-ajax-loading').css('visibility', 'visible');
	var mainarray = jQuery('.mainsettings').serialize();
	var data = {
		action: 'zsvg_ajax_mo',
		postdata: mainarray
	};
        jQuery('.saveconfirmer').html('Options saved.');
        jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
	jQuery.post(ajaxurl, data, function(response) {
            if(window.console !=undefined ){
		console.log('Got this from the server: ' + response);
            }
            jQuery('#save-ajax-loading').css('visibility', 'hidden');
	});
        
	return false;
}
function global_dzsmultiupload(arg){
	//console.log(arg);
	sliders_additem(currSlider_nr, window.dzs_upload_path + arg);
}
function sliders_resize(){
	jQuery('.master-settings').height(currSlider.height() + 250)
}



/* @projectDescription jQuery Serialize Anything - Serialize anything (and not just forms!)
 * @author Bramus! (Bram Van Damme)
 * @version 1.0
 * @website: http://www.bram.us/
 * @license : BSD
 */

(function($) {

    $.fn.serializeAnything = function() {

        var toReturn    = [];
        var els         = $(this).find(':input').get();

        $.each(els, function() {
            if (this.name && !this.disabled && (this.checked || /select|textarea/i.test(this.nodeName) || /text|hidden|password/i.test(this.type))) {
                var val = $(this).val();
                toReturn.push( encodeURIComponent(this.name) + "=" + encodeURIComponent( val ) );
            }
        });

        return toReturn.join("&").replace(/%20/g, "+");

    }

})(jQuery);