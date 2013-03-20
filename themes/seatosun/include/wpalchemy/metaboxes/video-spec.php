<?php
global $wpa_metabox_dir;
global $seatosun_video_meta;

$seatosun_video_meta = new WPAlchemy_MetaBox(array(
    'id' => '_video_meta',
    'title' => 'Video Information',
    'types' => array('seatosun_video'),
    'template' => $wpa_metabox_dir . 'video-meta.php',
));