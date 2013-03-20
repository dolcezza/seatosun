<?php
global $wpa_metabox_dir;
global $seatosun_artist_meta;

$seatosun_artist_meta = new WPAlchemy_MetaBox(array(
    'id' => '_artist_meta',
    'title' => 'Artist Information',
    'types' => array('seatosun_artist'),
    'template' => $wpa_metabox_dir . 'artist-meta.php',
));