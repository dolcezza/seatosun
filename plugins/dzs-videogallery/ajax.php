<?php
//
//
//
//
//// parse current file path

$the_path = 'http';
if ($_SERVER["HTTPS"] == "on") {$the_path .= "s";}
$the_path .= "://";

$the_path.=$_SERVER['SERVER_NAME'];

$info = pathinfo($_SERVER['PHP_SELF']);
$the_path.=$info['dirname'] . '/';
//$the_path.=$info['basename'];




// print info

//print_r($info);
$w =  $_GET['width'];
$h =  $_GET['height'] - 10;

$backup = '<video width="' .$w . '" height="' .$h . '" src="' . $_GET['source'] . '"></video>';

//if($_GET['type']=='normal'){
    echo '<div>
<object type="application/x-shockwave-flash" data="' . $the_path . 'deploy/preview.swf" width="' .$w . '" height="' .$h . '" id="flashcontent" style="visibility: visible;">
<param name="movie" value="' . $the_path . 'deploy/preview.swf"><param name="menu" value="false"><param name="allowScriptAccess" value="always">
<param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="opaque">
<param name="flashvars" value="video=' . $_GET['source'] . '&types=' . $_GET['type'] . '&defaultQuality=hd" width="' . $w . '" height="' . $h . '">'.$backup.'
</object>
</div>';