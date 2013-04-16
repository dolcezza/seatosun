
function flashhtml5(argsettings){
	var tg = jQuery('.' + argsettings.target);
	var currPlayer = 'flash';
	if(argsettings.defaultis=='flash'){
		switch_flash();
	}else{
		switch_html5();
	}
	if(is_ios()){
		switch_html5();
		tg.find('.html5-button').hide();
	}
	tg.find('.html5-button').bind('click', function(){
		if(currPlayer == 'flash'){
			switch_html5()
			return;
		}
		if(currPlayer == 'html5'){
			switch_flash();
			return;
		}
	})
	function switch_flash(){
		tg.children('.flashgallery-con').css({
			display : 'block'
		});
		tg.children('.videogallery-con').css({
			display : 'none'
		});
		currPlayer = 'flash';
		tg.find('.html5-button').html('Switch to HTML5');
		
		
		jQuery('.header-aux').css({
			'background' : 'url("style/img/info.png") no-repeat scroll center 50px transparent'
		});
	}
	function switch_html5(){
		tg.children('.flashgallery-con').css({
			display : 'none'
		})
		tg.find('.preloader').css({
			//display : 'none'
		})
		tg.children('.videogallery-con').css({
			display : 'block'
		})
		currPlayer = 'html5';
		tg.find('.html5-button').html('Switch to Flash');
		
		jQuery('.header-aux').css({
			'background' : 'none'
		})
		return;
	}
}

function vgcategories(arg){
    var carg = jQuery(arg);
    var currCatNr = -1;
    //console.log(carg);
    carg.find('.gallery-precon').each(function(){
        var _t = jQuery(this);
        //console.log(_t);
        _t.css({'display' : 'none'});
        carg.find('.the-categories-con').append('<span class="a-category">'+_t.attr('data-category')+'</span>')
    });
    
    carg.find('.the-categories-con').find('.a-category').eq(0).addClass('active');
    carg.find('.the-categories-con').find('.a-category').bind('click', click_category);
    function click_category(){
        var _t = jQuery(this);
        var ind = _t.parent().children('.a-category').index(_t);
        gotoCategory(ind);
    }
    gotoCategory(0);
    function gotoCategory(arg){
        if(currCatNr>-1 && carg.find('.gallery-precon').eq(currCatNr).find('.videogallery').eq(0).get(0)!=undefined && carg.find('.gallery-precon').eq(currCatNr).find('.videogallery').eq(0).get(0).external_handle_stopCurrVideo!=undefined ){
            carg.find('.gallery-precon').eq(currCatNr).find('.videogallery').eq(0).get(0).external_handle_stopCurrVideo();
        }
        carg.find('.the-categories-con').find('.a-category').removeClass('active');
        carg.find('.the-categories-con').find('.a-category').eq(arg).addClass('active');
        carg.find('.gallery-precon').css('display', 'none');
        carg.find('.gallery-precon').eq(arg).css('display', 'block');
        currCatNr = arg;
    }
}