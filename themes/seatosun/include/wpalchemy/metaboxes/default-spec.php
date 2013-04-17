<?php
global $wpa_metabox_dir;
global $seatosun_default_meta;

$seatosun_default_meta = new WPAlchemy_MetaBox(array(
    'id' => '_default_meta',
    'title' => 'Settings',
    'template' => $wpa_metabox_dir . 'default-meta.php',
    'types' => array('post', 'seatosun_artist', 'seatosun_release', 'seatosun_video'),
    'mode' => WPALCHEMY_MODE_EXTRACT,
    'prefix' => '_default_meta_',
));