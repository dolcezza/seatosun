var coll_buffer=0;
var func_output='';
jQuery(document).ready(function($){
       
	setTimeout(reskin_select, 10);
      jQuery('#insert_tests').bind('click', click_insert_tests);
       
});

function tinymce_add_content(arg){
	//console.log(arg);
    if(typeof(top.zsvg_receiver)=='function'){
        top.zsvg_receiver(arg);
    }
}

      function click_insert_tests(){
      //console.log(jQuery('#mainsettings').serialize()); 
        prepare_fout();
        //console.log(fout);
          tinymce_add_content(fout);
          return false;
      }

      function prepare_fout(){
          var $ = jQuery.noConflict();
          fout='';
        fout+='[videogallery';
        var _c,
        _c2
        ;
        /*
        _c = $('input[name=settings_width]');
        if(_c.val()!=''){
            fout+=' width=' + _c.val() + '';
        }
        _c = $('input[name=settings_height]');
        if(_c.val()!=''){
            fout+=' height=' + _c.val() + '';
        }
        */
        _c = $('select[name=zsvg_selectid]');
        if(_c.val()!=''){
            fout+=' id=' + _c.val() + '';
        }
        
        fout+=']';
      }

function sc_toggle_change(){
          var $ = jQuery.noConflict();
       	//var $t = $(this);

       		var type = 'toggle';
       		var params = '?type=' + type;
       	for(i=0;i<$('.sc-toggle').length;i++){
       		var $cach = $('.sc-toggle').eq(i);
       		var val = $cach.val();
       		if($cach.hasClass('color'))
       		val = val.substr(1);
       		params+='&opt' + (i+1) + '=' + val;
       	}
       // console.log(params);
       		$('.sc-toggle-frame').attr('src' , window.theme_url + 'tinymce/preview.php' + params);

       }
      function sc_boxes_change(){
       	//var $t = $(this);

       		var type = 'box';
       		var params = '?type=' + type;
       	for(i=0;i<$('.sc-box').length;i++){
       		var $cach = $('.sc-box').eq(i);
       		var val = $cach.val();
       		params+='&opt' + (i+1) + '=' + val;
       	}
        //console.log(params);
       		$('.sc-box-frame').attr('src' , window.theme_url + 'tinymce/preview.php' + params);

       }
       
       
       
function reskin_select(){
var $ = jQuery.noConflict();
	for(i=0;i<jQuery('select').length;i++){
		var $cache = jQuery('select').eq(i);
		//console.log($cache.parent().attr('class'));
		
		if($cache.hasClass('styleme')==false || $cache.parent().hasClass('select_wrapper') || $cache.parent().hasClass('select-wrapper')){
		continue;
		}
		var sel = ($cache.find(':selected'));
		$cache.wrap('<div class="select-wrapper"></div>')
		$cache.parent().prepend('<span>' + sel.text() + '</span>')
	}
	jQuery('.select-wrapper select').unbind();
	jQuery('.select-wrapper select').live('change',change_select);	
}

function change_select(){
var $ = jQuery.noConflict();
	var selval = (jQuery(this).find(':selected').text());
	jQuery(this).parent().children('span').text(selval);
}