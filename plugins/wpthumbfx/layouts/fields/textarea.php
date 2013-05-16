<?php

// set attributes
$attributes = array();
$attributes['name']  = $name;
$attributes['class'] = isset($node->attributes()->class) ? (string) $node->attributes()->class : '';

printf('<textarea %s>%s</textarea>', $this['field']->attributes($attributes, array('label', 'description', 'default')), htmlspecialchars($value, ENT_COMPAT, 'UTF-8'));