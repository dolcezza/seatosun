<?php

// set attributes
$attributes = array();
$attributes['type']  = 'text';
$attributes['name']  = $name;
$attributes['value'] = $value;
$attributes['class'] = 'title widefat '.(isset($class) ? $class : '');

printf('<input %s />', $this['field']->attributes($attributes, array('label', 'description', 'default')));

?>

<script>
	jQuery(function($){
		$('input.title').die('keyup.title').live('keyup.title', function() {
			$(this).trigger('update');
		});
	});
</script>