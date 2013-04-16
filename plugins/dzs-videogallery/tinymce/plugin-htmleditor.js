//console.log('ceva');
jQuery(document).ready(function($){
    $('#wp-content-media-buttons').append('<a class="thickbox shortcode_opener" id="zsvg_shortcode" style="cursor:pointer;"><img title="add video gallery" alt="add video gallery" src="'+zsvg_settings.thepath+'tinymce/img/shortcodes-small.png"/></a>');
    $('#wp-content-media-buttons').append('<a class="shortcode_opener" id="zsvg_shortcode_addvideoplayer" style="cursor:pointer;"><img title="add video player" alt="add video player" src="'+zsvg_settings.thepath+'tinymce/img/shortcodes-small-addvideoplayer.png"/></a>');
    $('#zsvg_shortcode').bind('click', function(){
        tb_show('ZSVG Shortcodes', zsvg_settings.thepath + 'tinymce/popupiframe.php?width=630&height=800');
    })
    $('#zsvg_shortcode_addvideoplayer').bind('click', function(){
        
            frame = wp.media.frames.dzsvg_addplayer = wp.media({
                // Set the title of the modal.
                title: "Insert Video Player",

                // Tell the modal to show only images.
                library: {
                    type: 'video'
                },

                // Customize the submit button.
                button: {
                    // Set the text of the button.
                    text: "Insert Video",
                    // Tell the button not to close the modal, since we're
                    // going to refresh the page when the image is selected.
                    close: false
                }
            });

            // When an image is selected, run a callback.
            frame.on( 'select', function() {
                // Grab the selected attachment.
                var attachment = frame.state().get('selection').first();

                //console.log(attachment.attributes.url);
                var arg = '[video source="'+attachment.attributes.url+'"]';
                    if(typeof(top.zsvg_receiver)=='function'){
                        top.zsvg_receiver(arg);
                    }
                    frame.close();
            });

            // Finally, open the modal.
            frame.open();
    })
})