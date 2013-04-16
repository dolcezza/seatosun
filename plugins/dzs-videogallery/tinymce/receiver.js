function zsvg_receiver(arg){
    
		
            if(window.console) { console.info(arg); }
		if(window.tinyMCE.activeEditor!=null){
                        if(window.mceeditor_sel==''){
                            window.tinyMCE.activeEditor.selection.moveToBookmark(window.tinymce_cursor);
                            window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, arg);

                        }else{
                            
                            window.tinyMCE.execCommand('mceReplaceContent',false, arg);
                        }
		}else{
			var aux = jQuery("#content").val();
                        var bigaux = aux+arg;
                        if(window.htmleditor_sel!=''){
                            bigaux = aux.replace(window.htmleditor_sel,arg);
                        }
			jQuery("#content").val( bigaux );
		}
		tb_remove();
	
}