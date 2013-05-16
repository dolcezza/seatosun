<?php
foreach ($node->children() as $option) {
    // set attributes
    $attributes = array(
        'type' => 'radio',
        'name' => $name,
        'value' => $option->attributes()->value
    );
    // is checked ?
    if ($option->attributes()->value == $value) {
        $attributes = array_merge($attributes, array(
            'checked' => 'checked'
        ));
    }
    printf('<input %s /> %s ', $this['field']->attributes($attributes), (string) $option);
} 