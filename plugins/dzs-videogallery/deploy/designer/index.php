<?php
require_once('dc_config.php');
$dc_mainoptions = $dc_config;
        //print_r($dc_mainoptions);
$dc = new DesignerCenter();
class DesignerCenter{
    private $targetuser;
    private $targetpass;
    private $theuser;
    function __construct(){
        global $dc_config;
        //print_r($dc_config);
        $this->targetuser = $dc_config['user'];
        $this->targetpass = $dc_config['password'];
        
        
        if (isset($_COOKIE['cookie_user'])) {
            $cookieuser = $_COOKIE['cookie_user'];
        }
        if ($cookieuser != '') {
            $this->theuser = $cookieuser;
        }
        if (isset($_POST['user'])) {
            if($_POST['user']==$this->targetuser){
                if (isset($_POST['password'])) {
                    if($_POST['password']==$this->targetpass){
                        $this->theuser = $_POST['user'];
                        setcookie("cookie_user", $this->theuser, time()+12000);
                    }
                    
                }
            }
        }
            
        
        
        $args = array();
        $this->check_postdata();
        //echo $this->theuser;
        if($this->theuser==false){
            $this->admin_login_screen($args);
            die();
        }
        
    }
    function check_postdata(){
        

if (isset($_REQUEST['thedata'])) {
    global $dc_config;
    $thedata = $_REQUEST['thedata'];
    //$dat = unserialize($thedata);
    $params = array();
    parse_str($_POST['thedata'], $params);
    //print_r($params);
    //echo $thedata;
    $myFile = "db/db.txt";
    $fh = fopen($myFile, 'w') or die("can't open file db.txt");
    $stringData = serialize($params);
    fwrite($fh, $stringData);
    fclose($fh);

    $xmlFile = $dc_config['xmllocation'];
    $xmlData = '<?xml version="1.0"?>';
    $xmlData.='<content>';
    foreach ($params as $key => $param) {
        //$xmlData.='<>'x
        $xmlData .= '<' . $key . '>' . $param . '</' . $key . '>';
    }
    $xmlData.='</content>';
    //die('ceva' . $xmlFile);
    $xh = fopen($xmlFile, 'w') or die("can't open file xml");
    
            if(!is_writable($xmlFile)){
                die($xmlFile . ' not writable :(');
            }
    fwrite($xh, $xmlData);
    fclose($xh);
    
    $cssData = '';
    foreach ($params as $key => $param) {
        
        if($key=='html5_color_thumbs_bg'){
            $cssData .= '.videogallery.skin_pro.skin_custom .navCon .navigationThumb { background: ' . $param . '; } ';
        }
        if($key=='html5_color_thumbs_bg_hover'){
            $cssData .= '.videogallery.skin_pro.skin_custom .navCon .navigationThumb.active { background: ' . $param . '; } ';
        }
        if($key=='html5_color_thumbs_borderc'){
            $cssData .= '.videogallery.skin_pro.skin_custom .navCon .navigationThumb { border-color: ' . $param . '; } ';
        }
        if($key=='html5_color_thumbs_titlec'){
            $cssData .= '.videogallery.skin_pro.skin_custom .the-tite { color: ' . $param . '; } ';
        }
        if($key=='html5_color_thumbs_w'){
            $cssData .= '.videogallery.skin_custom .imgblock { width: ' . $param . 'px; } ';
        }
        if($key=='html5_color_thumbs_h'){
            $cssData .= '.videogallery.skin_custom .imgblock { height: ' . $param . 'px; } ';
        }
        if($key=='html5_color_player_main'){
            $cssData .= '.videogallery.skin_pro.skin_custom .playSimple  { border-left-color: ' . $param . '; }
.videogallery.skin_pro.skin_custom .hdbutton-con .hdbutton-normal  { color: ' . $param . '; }
.videogallery.skin_pro.skin_custom .volumeicon:before { border-right-color: ' . $param . '; }
.videogallery.skin_pro.skin_custom .volume_static { background: ' . $param . '; }
.videogallery.skin_pro.skin_custom .stopSimple .pause-part-1, .videogallery.skin_pro.skin_custom .stopSimple .pause-part-2, .videogallery.skin_pro.skin_custom .volumeicon { background: ' . $param . '; } 
';
        }
        if($key=='html5_color_player_main_hover'){
            $cssData .= '.videogallery.skin_pro.skin_custom .playcontrols:hover .playSimple  { border-left-color: ' . $param . '; } .videogallery.skin_pro.skin_custom .volumeicon:hover:before { border-right-color: ' . $param . '; } 
                .videogallery.skin_pro.skin_custom .playcontrols:hover { border-color: ' . $param . '; }
.videogallery.skin_pro.skin_custom .playcontrols:hover .stopSimple .pause-part-1, .videogallery.skin_pro.skin_custom .playcontrols:hover .stopSimple .pause-part-2, .videogallery.skin_pro.skin_custom .volumeicon:hover { background: ' . $param . '; } 
                    ';
        }
        if($key=='html5_color_player_highlight'){
            $cssData .= '.videogallery.skin_pro.skin_custom .scrub, .videogallery.skin_pro.skin_custom .volume_active { background: ' . $param . '; } 
.videogallery.skin_pro.skin_custom .hdbutton-con .hdbutton-hover  { color: ' . $param . '; }
';
        }
        if($key=='html5_logo_posx'){
            if($param < 0){
                $cssData .= '.videogallery.skin_pro.skin_custom .the-logo { right: ' . $param . 'px; }';
            }else{
                $cssData .= '.videogallery.skin_pro.skin_custom .the-logo { left: ' . $param . 'px; }';
            }
        }
        if($key=='html5_logo_posy'){
            if($param < 0){
                $cssData .= '.videogallery.skin_pro.skin_custom .the-logo { bottom: ' . $param . 'px; }';
            }else{
                $cssData .= '.videogallery.skin_pro.skin_custom .the-logo { top: ' . $param . 'px; }';
            }
        }
    }
    $cssFile = $dc_config['csslocation'];
    $xh = fopen($cssFile, 'w') or die("can't open file css");
    
            if(!is_writable($cssFile)){
                die($cssFile . ' not writable :(');
            }
    fwrite($xh, $cssData);
    fclose($xh);
    
    
    //print_r($xmlData);

    die();
}


if (isset($_REQUEST['previewdata'])) {
    $thedata = $_REQUEST['previewdata'];
    //$dat = unserialize($thedata);
    $params = array();
    parse_str($_POST['previewdata'], $params);
    print_r($params);
    //echo $thedata;

    $xmlFile = "xml/design" . $params['rand'] . ".xml";
    $xmlData = '<?xml version="1.0"?>';
    $xmlData.='<content>';
    foreach ($params as $key => $param) {
        //$xmlData.='<>'
        $xmlData .= '<' . $key . '>' . $param . '</' . $key . '>';
    }
    $xmlData.='</content>';
    $xh = fopen($xmlFile, 'w') or die("can't open file");
    fwrite($xh, $xmlData);
    fclose($xh);
    //print_r($xmlData);

    die();
}
    }
    function admin_login_screen($args) {
        ?>
        <!DOCTYPE HTML>
        <html>
            <head>
                <title>Admin Login | DZS Video Gallery</title>
                <meta charset="UTF-8"/>
                <link rel="stylesheet" type="text/css" href="./style/reset.css">
                <link rel="stylesheet" type="text/css" href="./style/login.css">
            </head>

            <body class='loginpage'>
                <div class="userwarning">Default Login: admin, thepassword<br/>
                Please change these on purchase from the config file</div>
                <div class="login-form">
                    <form action="index.php" method="POST" class="login">
                        <fieldset class="loginBody">
                            <label>Username</label>
                            <input type="text" tabindex="1" placeholder="user" name="user" required>
                            <label>Password</label>
                            <input type="password" tabindex="2" required name="password">
                        </fieldset>
                        <footer>
                            <label><input name="remember_me" type="checkbox" tabindex="3"><p>Keep me logged in</p></label>
                            <input type="submit" class="btnLogin" value="Login" tabindex="4" name="action_login">
                        </footer>
                    </form>
                </div>
                <div class="warning"><?php if(isset ($args['warning'])) { echo $args['warning']; } ?></div>
            </body>
        </html>

        <?php
    }
    
}






$dbget = file_get_contents("db/db.txt");
$data = unserialize($dbget);

//print_r($data);

function add_simple_field($pname, $otherargs = array()) {
    global $data;
    $fout = '';
    $val = '';
    
    $args = array(
        'val' => ''
    );
    $args = array_merge($args, $otherargs);
    
    $val = $args['val'];
    
    //====check if the data from database txt corresponds
    if (isset($data[$pname]))
        $val = $data[$pname];
    
    $fout.='<div class="setting"><input type="text" class="textinput short" name="' . $pname . '" value="' . $val . '"></div>';
    echo $fout;
}

function add_cb_field($pname) {
    global $data;
    $fout = '';
    $val = '';
    if (isset($data[$pname]))
        $val = $data[$pname];
    $checked = '';
    if($val=='on')
        $checked=' checked';
    
    $fout.='<div class="setting"><input type="checkbox" class="textinput" name="' . $pname . '" value="on" '.$checked.'/> on</div>';
    echo $fout;
}

function add_cp_field($pname, $otherargs = array()) {
    global $data;
    $fout = '';
    $val = '';
    
    
    $args = array(
        'val' => ''
    );
    
    $args = array_merge($args, $otherargs);
    
    
    
    //print_r($args);
    $val = $args['val'];
    
    //====check if the data from database txt corresponds
    if (isset($data[$pname]))
        $val = $data[$pname];
    
    $fout.='
<div class="setting"><input type="text" class="textinput short with_colorpicker" name="' . $pname . '" value="' . $val . '">
<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
</div>';
    echo $fout;
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="style/admin.css"/>
        <link rel="stylesheet" type="text/css" href="style/style.css"/>
        <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz&v2' rel='stylesheet' type='text/css'> 
        <title>DZS Video YouTube Vimeo Gallery Designer Center</title>
        <script src="js/jquery.js" type="text/javascript"></script>
        <script type="text/javascript" src="colorpicker/farbtastic.js"></script>
        <script src="js/admin.js" type="text/javascript"></script>
        <link rel="stylesheet" href="colorpicker/farbtastic.css" type="text/css" /> 
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
        <script>
            window.swfloc = "<?php echo $dc_config['swflocation']; ?>";
        </script>
    </head>
    <body>
        <div class="content-wrapper">
            <div class="content">
                <h1>Video Gallery Designer Center</h1>
                <?php if($dc_config['ispreview']=='on'){ ?>
                <div class="comment">Hello and welcome to DZS Video / YouTube / Vimeo Gallery Designer Center. As this is only a preview, it will not save the changes in the primary database, but it will create temp files so you can preview the full power of this 
                    tool ( click <strong>Preview</strong> from the right ). You may notice that you won't find here all the options that you may need for fully customising the gallery. That's because here are only the options that are stricly related to the controls
                 of the gallery. The others like menu position, video list etc. are found in the main xml file ( gallery.xml ) you can find a full list of those options at the bottom.
                </div>
                <?php } ?>
                <hr>
                <div class="type-selector">
                    <div class="big-button active">HTML5 Gallery Design</div>
                    <div class="big-button">Flash Gallery Design</div>
                    <div class="clear"></div>
                </div>
                <hr>
                <div class="settings-html5vg">
                    <div class="settings_block">
                        <h2>HTML5 Gallery Settings</h2>
                        
                        <div class="toggle">
                            <div class="toggle-title"><h3>Gallery Thumbs Design</h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">
                                
                                <h5>Background</h5>
                                <?php add_cp_field('html5_color_thumbs_bg', array('val'=>'#111111')) ?>
                                <h5>Active Background</h5>
                                <?php add_cp_field('html5_color_thumbs_bg_hover', array('val'=>'#111111')) ?>
                                <h5>Border Color</h5>
                                <?php add_cp_field('html5_color_thumbs_borderc', array('val'=>'#111111')) ?>
                                <h5>Thumb Text Title Color</h5>
                                <?php add_cp_field('html5_color_thumbs_titlec', array('val'=>'#ffffff')) ?>
                                <h5>Thumb Pic Width</h5>
                                <?php add_simple_field('html5_color_thumbs_w', array('val'=>'50')) ?>
                                <h5>Thumb Pic Height</h5>
                                <?php add_simple_field('html5_color_thumbs_h', array('val'=>'50')) ?>


                            </div>
                        </div>



                        <div class="toggle">
                            <div class="toggle-title"><h3>General Player Colors</h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5>Main Color</h5>
                                <?php add_cp_field('html5_color_player_main', array('val'=>'#7a7a7a')) ?>
                                <h5>Hover Color</h5>
                                <?php add_cp_field('html5_color_player_main_hover', array('val'=>'#111111')) ?>
                                <h5>Highlight Color</h5>
                                <?php add_cp_field('html5_color_player_highlight', array('val'=>'#ff0000')) ?>

                            </div>
                        </div>







                    </div>
                    <div class="preview_block">
                        <div>
                            <h2>Preview</h2>
                            <div class="preview-all-con">
                                <div class="preview-all preview-html5player">
                                        <div class="preview-player">
                                            <div class="player_bg" style="background-color: #555;"></div>
                                            <div class="controls_bg" style="background-color: #000; height:28px;"></div>
                                            <div class="pp" style="border-color: transparent #fff; bottom:8px; left:16px;"></div>
                                            <div class="scr_bg" style="background-color: #fff;  width:100%; height:6px;  bottom:25px; left:0;"></div>
                                            <div class="scrl_bg" style="background-color: #eee; width:50%; height:6px;  bottom:25px; left:0; "></div>
                                            <div class="scrp_bg" style="background-color: #0099ff; width:25%; height:6px;  bottom:25px; left:0; "></div>
                                            <div class="vol" style="background-color: #fff; bottom:10px; right:38px;"></div>
                                            <div class="full" style="background-color: #fff; bottom:10px; right:10px;"></div>
                                        </div>
                                        <div class="preview-thumbs">
                                            <div class="preview-thumb active" style="position:relative;">
                                                <div class="preview-thumb-bg" style="background-color:#333; width:275px; height:70px; margin-bottom:0px;">
                                                </div>
                                                <div class="preview-thumb-text" style="position:absolute; top:5px; left:70px; width:200px; color:#fff; font-family: Arial, Helvetica, sans-serif;">
                                                    <div class="the-title">Lorem ipsum dolor</div> sit amet, consectetur adipisicing elit ud.
                                                </div>
                                                <div class="preview-thumb-pic" style="background:#fff; width:50px; height:50px; position:absolute; top:10px; left:10px;">
                                                </div>
                                            </div>
                                            <div class="preview-thumb" style="position:relative;">
                                                <div class="preview-thumb-bg" style="background:#333; width:275px; height:70px; margin-bottom:0px;">
                                                </div>
                                                <div class="preview-thumb-text" style="position:absolute; top:5px; left:70px; width:200px; color:#fff; font-family: Arial, Helvetica, sans-serif;">
                                                    <div class="the-title">Lorem ipsum dolor</div>  dolor sit amet, consectetur adipisicing elit ud.
                                                </div>
                                                <div class="preview-thumb-pic" style="background:#fff; width:50px; height:50px; position:absolute; top:10px; left:10px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
            </div>
                <div class="settings-flash" style="display:none;">
                    <div class="settings_block">
                        <h2>Flash Gallery Settings</h2>
                        <div class="toggle">
                            <div class="toggle-title"><h3>Thumb Settings</h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5>Width</h5>
                                <div class="setting"><input type="text" class="textinput short" name="thumbs_width" value="<?php echo $data['thumbs_width'] ?>"/></div>
                                <h5>Height</h5>
                                <div class="setting"><input type="text" class="textinput short" name="thumbs_height" value="<?php echo $data['thumbs_height'] ?>"/></div>
                                <h5>Spacing</h5>
                                <div class="setting"><input type="text" class="textinput short" name="thumbs_space" value="<?php echo $data['thumbs_space'] ?>"/></div>
                                <h5>Background</h5>
                                <div class="setting"><input type="text" class="textinput short" name="thumbs_bg" value="<?php echo $data['thumbs_bg'] ?>"/>
                                    </div>
                                <h5>Border Width</h5>
                                <?php add_simple_field('thumbs_borderw') ?>
                                <h5>Border Color</h5>
                                <?php add_cp_field('thumbs_borderc') ?>
                                <h5>Thumb Pic Width</h5>
                                <?php add_simple_field('thumbs_pic_w') ?>
                                <h5>Thumb Pic Height</h5>
                                <?php add_simple_field('thumbs_pic_h') ?>
                                <h5>Thumb Pic X</h5>
                                <?php add_simple_field('thumbs_pic_x') ?>
                                <h5>Thumb Pic Y</h5>
                                <?php add_simple_field('thumbs_pic_y') ?>
                                <h5>Thumb Text Width</h5>
                                <?php add_simple_field('thumbs_text_w') ?>
                                <h5>Thumb Text Height</h5>
                                <?php add_simple_field('thumbs_text_h') ?>
                                <h5>Thumb Text Title Color</h5>
                                <?php add_cp_field('thumbs_text_title_c') ?>
                                <h5>Thumb Text X</h5>
                                <?php add_simple_field('thumbs_text_x') ?>
                                <h5>Thumb Text Y</h5>
                                <?php add_simple_field('thumbs_text_y') ?>

                                <div class="preview-component-con">
                                    <div class="preview-component">
                                        <div class="preview-thumb" style="position:relative;">
                                            <div class="preview-thumb-bg" style="background-color:#333; width:275px; height:70px; margin-bottom:0px;">
                                            </div>
                                            <div class="preview-thumb-text" style="position:absolute; top:5px; left:70px; width:200px; color:#fff; font-family: Arial, Helvetica, sans-serif;">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.
                                            </div>
                                            <div class="preview-thumb-pic" style="background:#fff; width:50px; height:50px; position:absolute; top:10px; left:10px;">
                                            </div>
                                        </div>
                                        <div class="preview-thumb" style="position:relative;">
                                            <div class="preview-thumb-bg" style="background:#333; width:275px; height:70px; margin-bottom:0px;">
                                            </div>
                                            <div class="preview-thumb-text" style="position:absolute; top:5px; left:70px; width:200px; color:#fff; font-family: Arial, Helvetica, sans-serif;">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.
                                            </div>
                                            <div class="preview-thumb-pic" style="background:#fff; width:50px; height:50px; position:absolute; top:10px; left:10px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>



                        <div class="toggle">
                            <div class="toggle-title"><h3>Play / Stop Buttons</h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5>Position X</h5>
                                <?php add_simple_field('pp_x') ?>
                                <h5>Position Y</h5>
                                <?php add_simple_field('pp_y') ?>
                                <h5>Background</h5>
                                <?php add_cp_field('pp_bg') ?>

                            </div>
                        </div>



                        <div class="toggle">
                            <div class="toggle-title"><h3>Scrub Bar</h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5>Position X</h5>
                                <?php add_simple_field('scr_x') ?>
                                <h5>Position Y</h5>
                                <?php add_simple_field('scr_y') ?>
                                <h5>Width</h5>
                                <div class="sidenote">Best to use a negative value. For example -200 means video width - 200.</div>
                                <?php add_simple_field('scr_w') ?>
                                <h5>Height</h5>
                                <?php add_simple_field('scr_h') ?>
                                <h5>Background Static</h5>
                                <?php add_cp_field('scr_bg') ?>
                                <h5>Background Loaded</h5>
                                <?php add_cp_field('scrl_bg') ?>
                                <h5>Background Progress</h5>
                                <?php add_cp_field('scrp_bg') ?>

                            </div>
                        </div>


                        <div class="toggle">
                            <div class="toggle-title"><h3>Volume Button</h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5>Position X</h5>
                                <?php add_simple_field('vol_x') ?>
                                <h5>Position Y</h5>
                                <?php add_simple_field('vol_y') ?>
                                <h5>Background</h5>
                                <?php add_cp_field('vol_bg') ?>

                            </div>
                        </div>


                        <div class="toggle">
                            <div class="toggle-title"><h3>Fullscreen Button</h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5>Position X</h5>
                                <?php add_simple_field('full_x') ?>
                                <h5>Position Y</h5>
                                <?php add_simple_field('full_y') ?>
                                <h5>Background</h5>
                                <?php add_cp_field('full_bg') ?>

                            </div>
                        </div>


                        <div class="toggle">
                            <div class="toggle-title"><h3>General Settings</h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">
                                <h5>Player Background</h5>
                                <?php add_cp_field('settings_bg') ?>
                                <h5>Controls Background</h5>
                                <?php add_cp_field('settings_controls_bg') ?>

                                <h5>Controls Background Height</h5>
                                <?php add_simple_field('settings_controls_bg_h') ?>
                                <h5>Video Does Not Overlay Controls?</h5>
                                <?php add_cb_field('settings_does_not_overlay_controls') ?>
                                <h5>Disable Video Description?</h5>
                                <?php add_cb_field('settings_disable_description') ?>
                                <h5>Fade Controls On Mouse Leave ?</h5>
                                <?php add_cb_field('settings_fade_on_leave') ?>

                            </div>
                        </div>







                    </div>
                    <div class="preview_block">
                        <div>
                            <h2>Preview</h2>
                            <div class="preview-all-con">
                                <div class="preview-all preview-flash">
                                    <div>
                                        <div class="preview-player">
                                            <div class="player_bg" style="background-color: #555;"></div>
                                            <div class="controls_bg" style="background-color: #000; height:28px;"></div>
                                            <div class="pp" style="border-color: transparent #fff; bottom:8px; left:16px;"></div>
                                            <div class="scr_bg" style="background-color: #fff; width:226px; height:6px;  bottom:11px; left:55px;"></div>
                                            <div class="scrl_bg" style="background-color: #eee; width:126px; height:6px;  bottom:11px; left:55px; "></div>
                                            <div class="scrp_bg" style="background-color: #0099ff; width:63px; height:6px;  bottom:11px; left:55px; "></div>
                                            <div class="vol" style="background-color: #fff; bottom:10px; right:38px;"></div>
                                            <div class="full" style="background-color: #fff; bottom:10px; right:10px;"></div>
                                        </div>
                                        <div class="preview-thumbs">
                                            <div class="preview-thumb" style="position:relative;">
                                                <div class="preview-thumb-bg" style="background-color:#333; width:275px; height:70px; margin-bottom:0px;">
                                                </div>
                                                <div class="preview-thumb-text" style="position:absolute; top:5px; left:70px; width:200px; color:#fff; font-family: Arial, Helvetica, sans-serif;">
                                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.
                                                </div>
                                                <div class="preview-thumb-pic" style="background:#fff; width:50px; height:50px; position:absolute; top:10px; left:10px;">
                                                </div>
                                            </div>
                                            <div class="preview-thumb" style="position:relative;">
                                                <div class="preview-thumb-bg" style="background:#333; width:275px; height:70px; margin-bottom:0px;">
                                                </div>
                                                <div class="preview-thumb-text" style="position:absolute; top:5px; left:70px; width:200px; color:#fff; font-family: Arial, Helvetica, sans-serif;">
                                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.
                                                </div>
                                                <div class="preview-thumb-pic" style="background:#fff; width:50px; height:50px; position:absolute; top:10px; left:10px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <a class="preview-button primary-button" href="#">Preview</a>
                            <h2>Premade Examples</h2>
                            <img src="img/example1.png" id="example1" class="example"/>
                    </div>
            </div>
                <div class="clear"></div>
                
                <br/>
                <?php
                if ($dc_config['ispreview'] == 'on') {
                    echo '<div>Because preview mode is enabled, saving is disabled. You can still preview your configuration from the Preview button in the right half.</div>';
                }
                ?>
                <a class="<?php
                if ($dc_config['ispreview'] != 'on') {
                    echo 'save-button ';
                }
                ?> primary-button" href="#">Save</a><div class="preloader"></div>
                <div class="clear"></div><br/>
                
            </div>
        </div>
    </body>
</html>