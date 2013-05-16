<?php

// set attributes
$attributes = array();
$attributes['name']  = $name;
$attributes['value'] = $value;
$attributes['class'] = isset($class) ? $class : '';
$attributes['id'] = $id = isset($attributes['id']) ? $attributes['id'] : 'image-'.uniqid();
echo '<input id="'.$id.'_text" type="text" name="'.$attributes['name'].'" value="'.$value.'" />';
echo '<input id="'.$id.'_button" type="button" value="Browse" />';
?>
<script type="text/javascript">
jQuery(function($){
	$('#<?php echo $id.'_button';?>').click(function() {
		window.send_to_editor = function(html) 
		{
		 imgurl = $('img',html).attr('src');
		 $('#<?php echo $id.'_text';?>').val(imgurl);
		 tb_remove();
		}
		tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
		return false;
	});
});
</script>

