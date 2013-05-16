<?php

// set attributes
$attributes = array();
$attributes['type']  = 'text';
$attributes['name']  = $name;
$attributes['value'] = $value;
$attributes['class'] = isset($class) ? $class : '';

printf('<input %s />', $this['field']->attributes($attributes, array('label', 'description', 'default')));