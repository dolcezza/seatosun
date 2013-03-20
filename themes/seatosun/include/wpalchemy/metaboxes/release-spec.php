<?php
global $wpa_metabox_dir;
global $seatosun_release_meta;

$seatosun_release_meta = new WPAlchemy_MetaBox(array(
    'id' => '_release_meta',
    'title' => 'Release Information',
    'types' => array('seatosun_release'),
    'template' => $wpa_metabox_dir . 'release-meta.php',
));