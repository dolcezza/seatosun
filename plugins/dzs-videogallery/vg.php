<?php
/*
  Plugin Name: DZS Video Gallery
  Plugin URI: http://digitalzoomstudio.net/
  Description: Creates and manages cool videogallery galleries.
  Version: 6.20
  Author: Digital Zoom Studio
  Author URI: http://digitalzoomstudio.net/ 
 */





$zsvg = new DZSVideoGallery();

class DZSVideoGallery {

    public $thepath;
    public $sliders_index = 0;
    public $cats_index = 0;
    public $the_shortcode = 'videogallery';
    public $admin_capability = 'manage_options';
    public $dbitemsname = 'zsvg_items';
    public $dboptionsname = 'zsvg_options';
    public $dbs = array();
    public $dbdbsname = 'zsvg_dbs';
    public $currDb = '';
    public $currSlider = '';
    public $mainitems;
    public $mainoptions;
    public $pluginmode = "plugin";
    public $alwaysembed = "on";
    public $httpprotocol = 'https';
    public $adminpagename = 'zsvg_menu';
    private $usecaching = true;

    function __construct() {
        if ($this->pluginmode == 'theme') {
            $this->thepath = THEME_URL . 'plugins/dzs-videogallery/';
        } else {
            $this->thepath = plugins_url('', __FILE__) . '/';
        }
        
        //clear database
        //update_option($this->dbdbsname, '');

        
        $currDb = '';
        if (isset($_GET['dbname'])) {
            $this->currDb = $_GET['dbname'];
            $currDb = $_GET['dbname'];
        }
        
        
        if (isset($_GET['currslider'])) {
            $this->currSlider = $_GET['currslider'];
        }else{
            $this->currSlider = 0;
        }
        
        $this->dbs = get_option($this->dbdbsname);
        //$this->dbs = '';
        if ($this->dbs == '') {
            $this->dbs = array('main');
            update_option($this->dbdbsname, $this->dbs);
        }
        if(is_array($this->dbs) && !in_array($currDb, $this->dbs) && $currDb!='main' && $currDb!=''){
            array_push($this->dbs, $currDb);
            update_option($this->dbdbsname, $this->dbs);
        }
        //echo 'ceva'; print_r($this->dbs);
        if($currDb!='main' && $currDb!=''){
            $this->dbitemsname.='-'.$currDb;
        }

        $this->mainitems = get_option($this->dbitemsname);
        if ($this->mainitems == '') {
            $aux = 'a:2:{i:0;a:4:{s:8:"settings";a:49:{s:8:"feedfrom";s:6:"normal";s:2:"id";s:4:"gal1";s:5:"width";s:3:"900";s:6:"height";s:3:"300";s:11:"displaymode";s:6:"normal";s:12:"skin_html5vg";s:12:"skin_default";s:12:"skin_html5vp";s:11:"skin_aurora";s:10:"skin_flash";s:6:"normal";s:8:"nav_type";s:6:"thumbs";s:12:"menuposition";s:5:"right";s:8:"autoplay";s:2:"on";s:12:"autoplaynext";s:2:"on";s:13:"cueFirstVideo";s:2:"on";s:10:"coverImage";s:0:"";s:9:"defaultvg";s:5:"html5";s:17:"disableNonDefault";s:3:"off";s:12:"enableswitch";s:3:"off";s:6:"jqcall";s:11:"window.load";s:7:"bgcolor";s:7:"#111111";s:6:"shadow";s:3:"off";s:4:"logo";s:0:"";s:8:"logoLink";s:0:"";s:11:"sharebutton";s:3:"off";s:12:"facebooklink";s:0:"";s:11:"twitterlink";s:0:"";s:14:"googlepluslink";s:0:"";s:11:"embedbutton";s:3:"off";s:9:"sdquality";s:5:"small";s:9:"hdquality";s:5:"hd720";s:14:"defaultquality";s:2:"HD";s:15:"html5transition";s:7:"slideup";s:15:"html5responsive";s:3:"off";s:14:"html5designmiw";s:3:"275";s:14:"html5designmih";s:2:"76";s:14:"html5designmis";s:1:"0";s:13:"defaultvolume";s:0:"";s:9:"thumbnail";s:0:"";s:10:"windowmode";s:6:"opaque";s:18:"disabledescription";s:3:"off";s:17:"enabledeeplinking";s:3:"off";s:8:"hdButton";s:3:"off";s:20:"disablebigplaybutton";s:3:"off";s:23:"playerdesignonlybigplay";s:3:"off";s:15:"directurlaccess";s:3:"off";s:9:"scrollbar";s:3:"off";s:16:"youtubefeed_user";s:0:"";s:20:"youtubefeed_playlist";s:0:"";s:21:"youtubefeed_maxvideos";s:2:"50";s:14:"vimeofeed_user";s:0:"";}i:0;a:12:{s:4:"type";s:7:"youtube";s:6:"source";s:11:"Hv7Jxi_wMq4";s:14:"html5sourceogg";s:0:"";s:8:"thethumb";s:43:"http://img.youtube.com/vi/Hv7Jxi_wMq4/0.jpg";s:5:"title";s:0:"";s:11:"description";s:0:"";s:15:"menuDescription";s:0:"";s:10:"audioimage";s:0:"";s:4:"tags";s:0:"";s:8:"adsource";s:0:"";s:6:"adtype";s:5:"video";s:6:"adlink";s:0:"";}i:1;a:12:{s:4:"type";s:7:"youtube";s:6:"source";s:11:"Hv7Jxi_wMq4";s:14:"html5sourceogg";s:0:"";s:8:"thethumb";s:43:"http://img.youtube.com/vi/Hv7Jxi_wMq4/0.jpg";s:5:"title";s:0:"";s:11:"description";s:0:"";s:15:"menuDescription";s:0:"";s:10:"audioimage";s:0:"";s:4:"tags";s:0:"";s:8:"adsource";s:0:"";s:6:"adtype";s:5:"video";s:6:"adlink";s:0:"";}i:2;a:12:{s:4:"type";s:7:"youtube";s:6:"source";s:11:"Hv7Jxi_wMq4";s:14:"html5sourceogg";s:0:"";s:8:"thethumb";s:43:"http://img.youtube.com/vi/Hv7Jxi_wMq4/0.jpg";s:5:"title";s:0:"";s:11:"description";s:0:"";s:15:"menuDescription";s:0:"";s:10:"audioimage";s:0:"";s:4:"tags";s:0:"";s:8:"adsource";s:0:"";s:6:"adtype";s:5:"video";s:6:"adlink";s:0:"";}}i:1;a:3:{s:8:"settings";a:49:{s:8:"feedfrom";s:6:"normal";s:2:"id";s:4:"gal2";s:5:"width";s:3:"900";s:6:"height";s:3:"300";s:11:"displaymode";s:6:"normal";s:12:"skin_html5vg";s:12:"skin_default";s:12:"skin_html5vp";s:11:"skin_aurora";s:10:"skin_flash";s:6:"normal";s:8:"nav_type";s:6:"thumbs";s:12:"menuposition";s:5:"right";s:8:"autoplay";s:2:"on";s:12:"autoplaynext";s:2:"on";s:13:"cueFirstVideo";s:2:"on";s:10:"coverImage";s:0:"";s:9:"defaultvg";s:5:"html5";s:17:"disableNonDefault";s:3:"off";s:12:"enableswitch";s:3:"off";s:6:"jqcall";s:11:"window.load";s:7:"bgcolor";s:7:"#111111";s:6:"shadow";s:3:"off";s:4:"logo";s:0:"";s:8:"logoLink";s:0:"";s:11:"sharebutton";s:3:"off";s:12:"facebooklink";s:0:"";s:11:"twitterlink";s:0:"";s:14:"googlepluslink";s:0:"";s:11:"embedbutton";s:3:"off";s:9:"sdquality";s:5:"small";s:9:"hdquality";s:5:"hd720";s:14:"defaultquality";s:2:"HD";s:15:"html5transition";s:7:"slideup";s:15:"html5responsive";s:3:"off";s:14:"html5designmiw";s:3:"275";s:14:"html5designmih";s:2:"76";s:14:"html5designmis";s:1:"0";s:13:"defaultvolume";s:0:"";s:9:"thumbnail";s:0:"";s:10:"windowmode";s:6:"opaque";s:18:"disabledescription";s:3:"off";s:17:"enabledeeplinking";s:3:"off";s:8:"hdButton";s:3:"off";s:20:"disablebigplaybutton";s:3:"off";s:23:"playerdesignonlybigplay";s:3:"off";s:15:"directurlaccess";s:3:"off";s:9:"scrollbar";s:3:"off";s:16:"youtubefeed_user";s:0:"";s:20:"youtubefeed_playlist";s:0:"";s:21:"youtubefeed_maxvideos";s:2:"50";s:14:"vimeofeed_user";s:0:"";}i:0;a:12:{s:4:"type";s:7:"youtube";s:6:"source";s:11:"Hv7Jxi_wMq4";s:14:"html5sourceogg";s:0:"";s:8:"thethumb";s:43:"http://img.youtube.com/vi/Hv7Jxi_wMq4/0.jpg";s:5:"title";s:0:"";s:11:"description";s:0:"";s:15:"menuDescription";s:0:"";s:10:"audioimage";s:0:"";s:4:"tags";s:0:"";s:8:"adsource";s:0:"";s:6:"adtype";s:5:"video";s:6:"adlink";s:0:"";}i:1;a:12:{s:4:"type";s:7:"youtube";s:6:"source";s:11:"Hv7Jxi_wMq4";s:14:"html5sourceogg";s:0:"";s:8:"thethumb";s:43:"http://img.youtube.com/vi/Hv7Jxi_wMq4/0.jpg";s:5:"title";s:0:"";s:11:"description";s:0:"";s:15:"menuDescription";s:0:"";s:10:"audioimage";s:0:"";s:4:"tags";s:0:"";s:8:"adsource";s:0:"";s:6:"adtype";s:5:"video";s:6:"adlink";s:0:"";}}}';
            $this->mainitems = unserialize($aux);
            //$this->mainitems = array();
            update_option($this->dbitemsname, $this->mainitems);
        }
        
        $defaultOpts = array(
                'usewordpressuploader' => 'on',
                'embed_prettyphoto' => 'on',
                'embed_masonry' => 'on',
                'is_safebinding' => 'on',
                'vimeo_byline' => '0',
                'vimeo_portrait' => '0',
            );
        $this->mainoptions = get_option($this->dboptionsname);
        if ($this->mainoptions == '') {
            $this->mainoptions = array(
                'usewordpressuploader' => 'on',
                'embed_prettyphoto' => 'on',
                'embed_masonry' => 'on',
                'is_safebinding' => 'on',
            );
            update_option($this->dboptionsname, $this->mainoptions);
        }
        
        $this->mainoptions = array_merge($defaultOpts, $this->mainoptions);
        //print_r($this->mainoptions);
        load_plugin_textdomain('dzsvg', false, basename(dirname(__FILE__)) . '/languages');

        $this->post_options();

        //echo get_admin_url('', 'options-general.php?page=' . $this->adminpagename) . dzs_curr_url();
        //echo $newurl;

        $uploadbtnstring = '<button class="button-secondary action upload_file zs2-main-upload">Upload</button>';

        if ($this->mainoptions['usewordpressuploader'] != 'on') {
            $uploadbtnstring = '<div class="dzs-upload">
<form name="upload" action="#" method="POST" enctype="multipart/form-data">
    	<input type="button" value="Upload" class="btn_upl"/>
        <input type="file" name="file_field" class="file_field"/>
        <input type="submit" class="btn_submit"/>
</form>
</div>
<div class="feedback"></div>';
        }


        $this->sliderstructure = '<div class="slider-con" style="display:none;">
        <div class="setting type_all">
            <div class="setting-label">'.__('Select Feed Mode', 'dzsvg').'</div>
                <div class="main-feed-chooser select-hidden-metastyle">
                <select class="textinput mainsetting" name="0-settings-feedfrom">
                    <option value="normal">'.__('Normal', 'dzsvg').'</option>
                    <option value="youtube user channel">'.__('Youtube User Channel', 'dzsvg').'</option>
                    <option value="youtube playlist">'.__('YouTube Playlist', 'dzsvg').'</option>
                    <option value="vimeo user channel">'.__('Vimeo User Channel', 'dzsvg').'</option>
                </select>
                <div class="option-con clearfix">
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Normal', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed from custom items you set below.', 'dzsvg').'
                    
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Youtube User Channel', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__(' Feed videos from your YouTube User channel.', 'dzsvg').'
                   
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('YouTube Playlist', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed videos from the YouTube Playlist you create on their site. Just input the Playlist ID below.', 'dzsvg').'
                    
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Vimeo User Channel', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Feed videos from your Vimeo User channel.', 'dzsvg').'
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="settings-con">
        <h4>'.__('General Options', 'dzsvg') . '</h4>
        <div class="setting type_all">
            <div class="setting-label">' . __('ID', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting main-id" name="0-settings-id" value="default"/>
            <div class="sidenote">'.__('Choose an unique id.', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Width', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-width" value="900"/>
            <div class="sidenote">'.__('Width in pixels.', 'dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Height', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-height" value="300"/>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Responsive', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-html5responsive">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Display Mode', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-displaymode">
                <option>normal</option>
                <option>wall</option>
                <option>rotator</option>
                <option>rotator3d</option>
                <option>alternatemenu</option>
                <option>alternatewall</option>
            </select>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Video Gallery Skin', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skin_html5vg">
                <option>skin_default</option>
                <option>skin_navtranparent</option>
                <option>skin_pro</option>
                <option>skin_custom</option>
            </select>
            <div class="sidenote">'.__('Skin Custom can be modified via Designer Center.', 'dzsvg') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Video Player Skin', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skin_html5vp">
                <option>skin_aurora</option>
                <option>skin_default</option>
                <option>skin_white</option>
                <option>skin_pro</option>
                <option>skin_bigplay</option>
                <option>skin_custom</option>
            </select>
            <div class="sidenote">'.__('Skin Custom can be modified via Designer Center.', 'dzsvg') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Flash Gallery Skin', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skin_flash">
                <option>normal</option>
                <option>light</option>
                <option>custom</option>
            </select>
            <div class="sidenote">'.__('Skin Custom can be modified via Designer Center.', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Navigation Style', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-nav_type">
                <option>thumbs</option>
                <option>thumbsandarrows</option>
            </select>
            <div class="sidenote">'.__('Choose a navigation style for the normal display mode.', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Menu Position', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-menuposition">
                <option>right</option>
                <option>down</option>
                <option>left</option>
                <option>up</option>
                <option>none</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Autoplay', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplay">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Autoplay Next', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplaynext">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Cue First Video', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-cueFirstVideo">
                <option value="on">'.__('on', 'dzsvg').'</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
            </select>
            <div class="sidenote">'.__('Choose if the video should load at start or it should activate on click ( if a <strong>Cover Image</strong> is set ).', 'dzsvg') . '</div>
            
        </div>
        <div class="setting">
            <div class="setting_label">' . __('Cover Image', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-coverImage" value=""/>' . $uploadbtnstring . '
                <div class="sidenote">A image that appears while the video is cued / not played</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Default Gallery', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-defaultvg">
                <option>html5</option>
                <option>flash</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Non-Default', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disableNonDefault">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Randomize / Shuffle Elements', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-randomize">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Switch Button', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enableswitch">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('HTML5 Gallery Inits on', 'dzsvg') . '
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">' . __('Select window.load if you want the slider to init when all the images are loaded, or the other when the document is ready', 'dzsvg') . '</div>
            </div></div>
            <select class="textinput mainsetting styleme" name="0-settings-jqcall">
            <option>window.load</option>
            <option>document.ready</option>
            </select>
        </div>
        <div class="setting">
            <div class="setting-label">' . __('Background', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting with_colorpicker" name="0-settings-bgcolor" value="#111111"/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Shadow', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-shadow">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting">
            <div class="setting_label">' . __('Logo', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-logo" value=""/>' . $uploadbtnstring . '
        </div>
        <div class="setting">
            <div class="setting_label">' . __('Logo Link', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-logoLink" value=""/>
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Share Button', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-sharebutton">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting_label">' . __('Facebook Link', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-facebooklink" value=""/>
        </div>
        <div class="setting type_all">
            <div class="setting_label">' . __('Twitter Link', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-twitterlink" value=""/>
        </div>
        <div class="setting type_all">
            <div class="setting_label">' . __('Google Plus Link', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-googlepluslink" value=""/>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Embed Button', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-embedbutton">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <hr/>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('YouTube Options', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="setting type_all">
            <div class="setting-label">' . __('SD Quality', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-sdquality">
                <option>small</option>
                <option>medium</option>
                <option>default</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('HD Quality', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-hdquality">
                <option>hd720</option>
                <option>highres</option>
                <option>medium</option>
                <option>large</option>
                <option>default</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Default Quality', 'dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-defaultquality">
                <option value="HD">'.__('HD', 'dzsvg').'</option>
                <option value="SD">'.__('SD', 'dzsvg').'</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">'.__('Enable Custom Skin', 'dzsvg').'</div>
            <select class="textinput mainsetting styleme" name="0-settings-yt_customskin">
                <option value="on">'.__('on', 'dzsvg').'</option>
                <option value="off">'.__('off', 'dzsvg').'</option>
            </select>
            <div class="sidenote">'.__('Choose if the custom skin you set in the Video Player Skin is how YouTube videos should show ( on )
                 or if the default YouTube skin should show ( off )', 'dzsvg') . '</div>
        </div>
</div>
</div>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Design Options', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="sidenote" style="font-size:14px;">'.__('Only for the html5 gallery', 'dzsvg') . '</div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Design Menu Item Width', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmiw" value="275"/>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Design Menu Item Height', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmih" value="76"/>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Design Menu Item Space', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmis" value="0"/>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Menu Title', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_title">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Menu Description', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_menu_description">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Transition', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-html5transition">
                <option>slideup</option>
                <option>fade</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Normal Controls Opacity', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5design_controlsopacityon" value="1"/>
            <div class="sidenote">'.__('Choose an opacity from 0 to 1', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Roll Out Controls Opacity', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5design_controlsopacityout" value="0.3"/>
            <div class="sidenote">'.__('Choose an opacity from 0 to 1 for when the mouse is not on the video player', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Container Width', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-outerwidth" value="0"/>
            <div class="sidenote">'.__('Width in pixels. Leave 0 for auto', 'dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Container Height', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-outerheight" value="0"/>
            <div class="sidenote">'.__('Height in pixels. Leave 0 for auto', 'dzsvg').'</div>
        </div>
</div>
</div>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Flash Gallery Options', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="setting type_all">
            <div class="setting-label">' . __('Default Volume', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-defaultvolume" value=""/>
        </div>
        <div class="setting">
            <div class="setting_label">' . __('Thumbnail', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-thumbnail" value=""/>' . $uploadbtnstring . '
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Window Mode', 'dzsvg') . '</div>
            <div class="sidenote">Only for the flash version.</div>
            <select class="textinput mainsetting styleme" name="0-settings-windowmode">
                <option>opaque</option>
                <option>transparent</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Description', 'dzsvg') . '</div>
            <div class="sidenote">' . __('Only for the flash version', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disabledescription">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Deeplinking', 'dzsvg') . '</div>
            <div class="sidenote">' . __('Only for the flash version.', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enabledeeplinking">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('HD Button', 'dzsvg') . '</div>
            <div class="sidenote">' . __('Only for the flash version.', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-hdButton">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Big Play Button', 'dzsvg') . '</div>
            <div class="sidenote">' . __('Only for the flash version.', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disablebigplaybutton">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Leave Only Big Play Button', 'dzsvg') . '</div>
            <div class="sidenote">' . __('Only for the flash version.', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-playerdesignonlybigplay">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Let Flash Handle Feeds', 'dzsvg') . '</div>
            <div class="sidenote">' . __('Deprecated.', 'dzsvg') . ' ' . __('Only for the flash version.', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-directurlaccess">
                <option value="off">off</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Scrollbar', 'dzsvg') . '</div>
            <div class="sidenote">' . __('Only for the flash version.', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-scrollbar">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
</div>
</div>
        
        
        </div><!--end settings con-->
        <div class="modes-con">
        
        <div class="setting mode_ytuser">
            <div class="setting_label">' . __('YouTube User', 'dzsvg') . '</div>
            <input type="text" class="short textinput mainsetting" name="0-settings-youtubefeed_user" value=""/>
        </div>
	<div class="setting mode_ytplaylist">
            <div class="setting_label">' . __('YouTube Playlist', 'dzsvg') . '
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">' . __('You need to set the playlist ID there not the playlist Name. For example for this playlist http:' . '/' . '' . '/' . 'www.youtube.com/my_playlists?p=PL08BACDB761A0C52A the id is 08BACDB761A0C52A. Remember that if you have the characters PL at the beggining of the ID they should not be included here.</div>', 'dzsvg') . '
        </div>
        </div>
            <input type="text" class="short textinput mainsetting" name="0-settings-youtubefeed_playlist" value=""/>
            </div>
        <div class="setting type_all mode_ytuser mode_ytplaylist">
            <div class="setting-label">' . __('YouTube Max Videos', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-youtubefeed_maxvideos" value="50"/>
        </div>
        <div class="setting type_all mode_vimeouser">
            <div class="setting_label">' . __('Vimeo User ID', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeofeed_user" value=""/>
        </div>
        
</div>
        <div class="master-items-con mode_normal">
        <div class="items-con "></div>
        <a href="#" class="add-item"></a>
        </div><!--end master-items-con-->
        <div class="clear"></div>
        </div>';
        $this->itemstructure = '<div class="item-con">
            <div class="item-delete">x</div>
            <div class="item-duplicate"></div>
        <div class="item-preview" style="">
        </div>
        <div class="item-settings-con">
        <div class="setting type_all">
            <h4 class="non-underline"><span class="underline">'.__('Type', 'dzsvg').'*</span>&nbsp;&nbsp;&nbsp;<span class="sidenote">select one from below</span></h4> 
            
            <div class="main-feed-chooser select-hidden-metastyle select-hidden-foritemtype">
                <select class="textinput item-type" data-label="type" name="0-0-type">
            <option>youtube</option>
            <option>video</option>
            <option>vimeo</option>
            <option>audio</option>
            <option>image</option>
            <option>link</option>
            <option>inline</option>
                </select>
                <div class="option-con clearfix">
                    <div class="an-option">
                    <div class="an-title">
                    '.__('YouTube', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Input in the <strong>Source</strong> field below the youtube video ID. You can find the id contained in the link to 
                    the video - http://www.youtube.com/watch?v=<strong>ZdETx2j6bdQ</strong> ( for example )', 'dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Self-hosted Video', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Stream videos your own hosted videos. You just have to include two formats of the video you are streaming. In the <strong>Source</strong>
                    field you need to include the path to your mp4 formatted video. And in the OGG field there should be the ogg / ogv path, this is not mandatory, 
                    but recommended.', 'dzsvg').' <a href="'.$this->thepath.'readme/index.html#handbrake" target="_blank" class="">Documentation here</a>.
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Vimeo Video', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Insert in the <strong>Source</strong> field the ID of the Vimeo video you want to stream. You can identify the ID easy from the link of the video,
                     for example, here see the bolded part', 'dzsvg').' - http://vimeo.com/<strong>55698309</strong>
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Self-hosted Audio File', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('You need a MP3 format of your audio file and an OGG format. You put their paths in the Source and Html5 Ogg Format fields', 'dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Self-hosted Image File', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Just put in the <strong>Source</strong> field the path to your image.', 'dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('A link', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Link where the visitor should go when clicking the menu item.', 'dzsvg').'
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    '.__('Inline Content', 'dzsvg').'
                    </div>
                    <div class="an-desc">
                    '.__('Insert in the <strong>Source</strong> field custom content ( ie. embed from a custom site ).', 'dzsvg').'
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Source', 'dzsvg') . '*
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">'.__('Below you will enter your video address. If it is a video from YouTube or Vimeo you just need to enter 
                the id of the video in the "Video:" field. The ID is the bolded part http://www.youtube.com/watch?v=<strong>j_w4Bi0sq_w</strong>. 
                If it is a local video you just need to write its location there or upload it through the Upload button ( .mp4 / .flv format ).', 'dzsvg').'
                    </div>
                </div>
            </div>
<textarea class="textinput main-source type_all" data-label="source" name="0-0-source" style="width:160px; height:23px;">Hv7Jxi_wMq4</textarea>' . $uploadbtnstring . '
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">HTML5 OGG ' . __('Format', 'dzsvg') . '</div>
            <div class="sidenote">' . __('Optional ogg / ogv file', 'dzsvg') . ' / ' . __('Only for the Video or Audio type', 'dzsvg') . '</div>
            <input class="textinput upload-prev" name="0-0-html5sourceogg" value=""/>' . $uploadbtnstring . '
        </div>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Appearance Settings', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="setting type_all floatleft220 ">
            <div class="setting-label">' . __('Thumbnail', 'dzsvg') . '</div>
            <input type="text" class="textinput main-thumb" name="0-0-thethumb"/>' . $uploadbtnstring . '
        </div>
        <div class="setting type_all floatleft220 br1">
            <div class="setting-label">' . __('Menu Title', 'dzsvg') . '</div>
            <input type="text" class="textinput" name="0-0-title"/>
        </div>
        <div class="setting type_all floatleft220">
            <div class="setting-label">' . __('Video Description', 'dzsvg') . ':</div>
            <textarea class="textinput" name="0-0-description"></textarea>
        </div>
        <div class="setting type_all floatleft220 br1">
            <div class="setting-label">' . __('Menu Description', 'dzsvg') . '</div>
            <textarea class="textinput" name="0-0-menuDescription"></textarea>
        </div>
        <div class="clear"></div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Preview Image', 'dzsvg') . '</div>
            <input class="textinput upload-prev" name="0-0-audioimage" value=""/>' . $uploadbtnstring . '
            <div class="sidenote">' . __('will be used as the background image for audio type too', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Tags', 'dzsvg') . '</div>
            <input class="textinput tageditor-prev" name="0-0-tags" value=""/><button class="button-secondary btn-tageditor">Tag Editor</button>
            <div class="sidenote">' . __('use the tag editor to generate tags at given times of the video', 'dzsvg') . '</div>
        </div>
</div>
</div>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Advertising Settings', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="setting type_all">
            <div class="setting-label">' . __('Ad  Source', 'dzsvg') . '</div>
            <div class="sidenote">' . __('if you host the video yourself, input here the mp4 / m4v source and have a file named the same but with .ogv extension
            format in the same folder', 'dzsvg') . '</div>
            <input class="textinput upload-prev" name="0-0-adsource" value=""/>' . $uploadbtnstring . '
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Ad  Type', 'dzsvg') . '</div>
            <select class="textinput item-type styleme type_all" name="0-0-adtype">
            <option>video</option>
            <option>youtube</option>
            <option>image</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Ad  Link', 'dzsvg') . '</div>
            <input class="textinput" name="0-0-adlink" value=""/>
        </div>
        <div class="clear"></div>
</div>
</div>
</div><!--end item-settings-con-->
</div>';
        
        if(isset($_GET['deleteslider'])){
            //print_r($this->mainitems);
            unset($this->mainitems[$_GET['deleteslider']]);
            $this->mainitems = array_values($this->mainitems);
            $this->currSlider = 0;
            //print_r($this->mainitems);
            update_option($this->dbitemsname, $this->mainitems);
            
        }
        //print_r($this->mainitems);
        


        add_shortcode($this->the_shortcode, array($this, 'show_shortcode'));
        add_shortcode('dzs_' . $this->the_shortcode, array($this, 'show_shortcode'));
        add_shortcode('videogallerycategories', array($this, 'show_shortcode_cats'));
        add_shortcode('videogallerylightbox', array($this, 'show_shortcode_lightbox'));


        add_shortcode('vimeo', array($this, 'vimeo_func'));
        add_shortcode('youtube', array($this, 'youtube_func'));
        add_shortcode('video', array($this, 'video_func'));

        add_action('init', array($this, 'handle_init'));
        add_action('wp_ajax_zsvg_ajax', array($this, 'post_save'));
        add_action('wp_ajax_zsvg_import_ytplaylist', array($this, 'post_importytplaylist'));
        add_action('wp_ajax_zsvg_import_ytuser', array($this, 'post_importytuser'));
        add_action('wp_ajax_zsvg_import_vimeouser', array($this, 'post_importvimeouser'));
        
        
        add_action('wp_ajax_zsvg_ajax_mo', array($this, 'post_save_mo'));

        add_action('admin_menu', array($this, 'handle_admin_menu'));
        add_action('admin_head', array($this, 'handle_admin_head'));
        
        
        add_action('wp_footer', array($this, 'handle_footer'));

        if ($this->pluginmode == 'theme') {
            $this->mainoptions['embed_prettyphoto'] = 'off';
        }
        if ($this->pluginmode != 'theme') {
            add_action('admin_init', array($this, 'admin_init'));
            add_action('save_post', array($this, 'admin_meta_save'));
        }
    }
    function handle_admin_head() {
        //echo 'ceva23';
        //siteurl : "'.site_url().'", 
        
        //$params = array( 'addslider' => '_currslider_' );
        $aux = remove_query_arg( 'addslider', dzs_curr_url() );
        $aux = remove_query_arg( 'deleteslider', $aux );
        $params = array( 'currslider' => '_currslider_' );
        $newurl = add_query_arg( $params, $aux );
        $params = array( 'deleteslider' => '_currslider_' );
        $delurl = add_query_arg( $params, $aux );
        echo '<script>var zsvg_settings = { thepath: "' . $this->thepath . '", is_safebinding: "'.$this->mainoptions['is_safebinding'].'"';
        if(isset($_GET['addslider']) && $_GET['addslider']=='on'){
            echo ', addslider:"on"';
        }
        echo ', urldelslider:"'.$delurl.'", urlcurrslider:"'.$newurl.'", currSlider:"'.$this->currSlider.'", currdb:"'.$this->currDb.'", zsvg_dc_poster_url_path:"'.$this->thepath.'deploy/designer/index.php"}; </script>';
    }
    function handle_footer(){
        
        global $post;
        if(!$post){
            return;
        }
        //echo 'ceva';
        $wallid = get_post_meta($post->ID, 'zsvg_fullscreen',true);
        if($wallid!='' && $wallid!='none'){
        echo '<div class="wall-close">'.__('CLOSE GALLERY', 'dzsvg').'</div>';
        echo do_shortcode('[videogallery id="'.$wallid.'" fullscreen="on"]');
        ?>
<script>
    var zsvg_videofs = true;
jQuery(document).ready(function($){
    //$('body').css('overflow', 'hidden');
    jQuery(".wall-close").click(handle_wall_close);
    function handle_wall_close(){
        var $t = jQuery(this);
        if(zsvg_videofs==true){
            $t.html('OPEN GALLERY');
            jQuery(".gallery-is-fullscreen").fadeOut("slow");
            zsvg_videofs = false;
        }else{
            $t.html('CLOSE GALLERY');
            jQuery(".gallery-is-fullscreen").fadeIn("slow");
            zsvg_videofs = true;
        }
    }
})
</script>
                <?php
        }
    }

    function vimeo_func($atts) {
        //[vimeo id="youtubeid"]
        $fout = '';
        $args = array(
            'id'=>'2',
            'vimeo_title'=>'0',
            'vimeo_byline'=>'0',
            'vimeo_portrait'=>'0',
            'vimeo_color'=>'',
            );
        
        if($atts==false){
            $atts = array();
        }
        
        $args = array_merge($args, $atts);
        
        $w = 400;
        if (isset($atts['width'])){
            $w = $atts['width'];
        }
        $h = 300;
        if (isset($atts['height'])){
            $h = $atts['height'];
        }
        
        if(isset($this->mainoptions['vimeo_title'])){
            $args['vimeo_title'] = $this->mainoptions['vimeo_title'];
        }
        if(isset($this->mainoptions['vimeo_byline'])){
            $args['vimeo_byline'] = $this->mainoptions['vimeo_byline'];
        }
        if(isset($this->mainoptions['vimeo_portrait'])){
            $args['vimeo_portrait'] = $this->mainoptions['vimeo_portrait'];
        }
        if(isset($this->mainoptions['vimeo_color'])){
            $args['vimeo_color'] = $this->mainoptions['vimeo_color'];
        }
        
        $str_title = 'title=' . $args['vimeo_title'];
        $str_byline = '&amp;byline=' . $args['vimeo_byline'];
        $str_portrait = '&amp;portrait=' . $args['vimeo_portrait'];
        $str_color = '';
        if($args['vimeo_color']!=''){
            $str_color = '&amp;color='.$args['vimeo_color'];
        }
        
        
        
        $fout.='<iframe src="http://player.vimeo.com/video/' . $atts['id'] . '?'.$str_title.$str_byline.$str_portrait.$str_color.'" width="' . $w . '" height="' . $h . '" frameborder="0"></iframe>';
        return $fout;
    }

    function youtube_func($atts) {
        //[youtube id="youtubeid"]
        $fout = '';
        $w = 400;
        if (isset($atts['width']))
            $w = $atts['width'];
        $h = 300;
        if (isset($atts['height']))
            $h = $atts['height'];
        $fout.='<object width="' . $w . '" height="' . $h . '"><param name="movie" value="http://www.youtube.com/v/' . $atts['id'] . '?version=3&amp;hl=en_US"></param><param name="wmode" value="transparent"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $atts['id'] . '?version=3&amp;hl=en_US" type="application/x-shockwave-flash" width="' . $w . '" height="' . $h . '" allowscriptaccess="always" wmode="transparent" allowfullscreen="true"></embed></object>';
        return $fout;
    }

    function video_func($atts) {
        //[video source="pathto.mp4"]
        $this->slider_index++;
        
        $fout = '';
        
        $this->front_scripts();
        
        $args = array(
            'width' => 400,
            'height' => 300,
            'source' => '',
        );
        
        $args=array_merge($args,$atts);
        
        
        
        $fout.='<div id="vp'.$this->slider_index.'" class="vplayer-tobe" style="" data-sourcemp4="'.$args['source'].'">
</div>
<script>jQuery(document).ready(function($){ var videoplayersettings = {
	autoplay : "off",
	videoWidth : '.$args['width'].',
	videoHeight : '.$args['height'].',
	constrols_out_opacity : 1,
	constrols_normal_opacity : 1
	,settings_hideControls : "off"
	,settings_swfPath : "'.$this->thepath.'deploy/preview.swf"
  ,design_skin: "skin_pro"
}; jQuery("#vp'.$this->slider_index.'").vPlayer(videoplayersettings); });</script>';

        return $fout;
    }

    function log_event($arg) {
        $fil = dirname(__FILE__) . "/log.txt";
        $fh = fopen($fil, 'a') or die("cannot open file");
        fwrite($fh, ($arg . "\n"));
        fclose($fh);
    }
    function show_shortcode_cats($atts, $content=null){
        //echo 'ceva';
        $args = array(
            'width' => 300
            ,'height' => 400
        );
        
        $args = array_merge($args, $atts);
        
        $fout.='<div class="categories-videogallery" id="cats'.(++$this->cats_index).'" style="width: '.$args['width'].'px;height: '.$args['height'].'px;">';
        $fout.='<div class="the-categories-con"><span class="label-categories">'.__('categories', 'dzsvg').'</span></div>';
        $fout.=do_shortcode($content);
        $fout.='</div>';
        $fout.='<script>jQuery(document).ready(function($){ vgcategories("#cats'.$this->cats_index.'"); });</script>';
        
        return $fout;
    }

    function show_shortcode_lightbox($atts, $content=null){
        
        $fout = '';
        //$this->sliders_index++;
        
        $this->front_scripts();
        
            wp_enqueue_style('dzs.zoombox', $this->thepath . 'zoombox/zoombox.css');
            wp_enqueue_script('dzs.zoombox', $this->thepath . 'zoombox/zoombox.js');
        
        $args = array(
            'id' => 'default'
            ,'db' => ''
            ,'category' => ''
            ,'width' => ''
            ,'height' => ''
        );
        $args = array_merge($args, $atts);
        $fout.='<div class="zoombox"';
        
        if($args['width']!=''){
            $fout.=' data-width="'.$args['width'].'"';
        }
        if($args['height']!=''){
            $fout.=' data-height="'.$args['height'].'"';
        }
        
        $fout.='data-src="'.$this->thepath.'retriever.php?id='.$args['id'].'" data-type="ajax">'.$content.'</div>';
        $fout.='<script>
jQuery(document).ready(function($){
jQuery(".zoombox").zoomBox();
});
</script>';
        
        return $fout;
    }
    function show_shortcode($atts){
        global $post;
        $fout = '';

        $args = array(
            'id' => 'default'
            ,'db' => ''
            ,'category' => ''
            ,'fullscreen' => 'off'
        );
        
        if($atts==false){
            $atts=array();
        }
        $args = array_merge($args, $atts);
        
        
        //===setting up the db
        $currDb = '';
        if (isset($args['db']) && $args['db']!='') {
            $this->currDb = $args['db'];
            $currDb = $this->currDb;
        }
        $this->dbs = get_option($this->dbdbsname);
        
        //echo 'ceva'; print_r($this->dbs);
        if($currDb!='main' && $currDb!=''){
            $this->dbitemsname.='-'.$currDb;
            $this->mainitems = get_option($this->dbitemsname);
        }
        //===setting up the db END
        
        
        
        
        if ($this->mainitems == '')
            return;


        $this->front_scripts();

        $this->sliders_index++;


        $i = 0;
        $k = 0;
        $id = 'default';
        if (isset($args['id'])) {
            $id = $args['id'];
        }

        //echo 'ceva' . $id;
        for ($i = 0; $i < count($this->mainitems); $i++) {
            if ((isset($id)) && ($id == $this->mainitems[$i]['settings']['id']))
                $k = $i;
        }

        $its = $this->mainitems[$k];
        //print_r($this->mainitems);
        
        

        if ($post && $this->sliders_index == 1) {
            if (get_post_meta($post->ID, 'dzsvg_preview', true) == 'on') {
                wp_enqueue_script('preseter', $this->thepath . 'preseter/preseter.js');
                wp_enqueue_style('preseter', $this->thepath . 'preseter/preseter.css');
                echo '<div class="preseter"><div class="the-icon"></div>
<div class="the-content"><h3>Quick Config</h3>
<form method="GET">
<div class="setting">
<div class="alabel">Width:</div>
<input type="text" name="opt1" value="900"/>
</div>
<div class="setting">
<div class="alabel">Height:</div>
<input type="text" name="opt2" value="300"/>
</div>
<div class="setting">
<div class="alabel">Menu Position:</div>
<div class="select-wrapper"><span>right</span><select name="opt3" class="textinput short"><option>right</option><option>down</option><option>up</option><option>left</option><option>none</option></select></div>
</div>
<div class="setting">
<div class="alabel">Autoplay:</div>
<div class="select-wrapper"><span>on</span><select name="opt4" class="textinput short"><option value="on">' . __('on', 'dzsvg') . '</option><option value="off">' . __('off', 'dzsvg') . '</option></select></div>
</div>
<div class="setting type_all">
    <div class="setting-label">' . __('Feed From', 'dzsvg') . '</div>
    <div class="select-wrapper"><span>normal</span><select class="textinput styleme" name="feedfrom">
        <option>normal</option>
        <option>youtube user channel</option>
        <option>youtube playlist</option>
        <option>vimeo user channel</option>
    </select></div>
</div>
<div class="setting">
    <div class="alabel">Target Feed User</div>
    <div class="sidenote">Or playlist ID if you have selected playlist in the dropdown</div>
    <input type="text" name="opt6" value="digitalzoomstudio"/>
</div>
<div class="setting">
    <input type="submit" class="button-primary" name="submiter" value="Submit"/>
</div>
</form>
</div><!--end the-content-->
</div>';
                if (isset($_GET['opt1'])) {
                    $its['settings']['width'] = $_GET['opt1'];
                    $its['settings']['height'] = $_GET['opt2'];
                    $its['settings']['menuposition'] = $_GET['opt3'];
                    $its['settings']['autoplay'] = $_GET['opt4'];
                    $its['settings']['feedfrom'] = $_GET['feedfrom'];
                    $its['settings']['youtubefeed_user'] = $_GET['opt6'];
                    $its['settings']['youtubefeed_playlist'] = $_GET['opt6'];
                    $its['settings']['vimeofeed_user'] = $_GET['opt6'];
                }
            }
        }//----dzsvg preview END



        $w = $its['settings']['width'] . 'px';
        $h = $its['settings']['height'] . 'px';
        $fullscreenclass = '';
        $theclass = 'videogallery';
        //echo $id;
        //$fout.='<div class="videogallery-con" style="width:'.$w.'; height:'.$h.'; opacity:0;">';
            if($args['category']!=''){
                $its['settings']['autoplay'] = 'off';
            }
        

        $user_feed = '';
        $yt_playlist_feed = '';


        
        $skin_html5vg = $its['settings']['skin_html5vg'];
        $skin_html5vp = $its['settings']['skin_html5vp'];
        $skin_flash = 'deploy/preview_allchars.swf';
        if ($its['settings']['skin_flash'] == 'normal' || $its['settings']['skin_flash'] == 'custom') {
            $skin = 'deploy/preview_allchars.swf';
        }
        if ($its['settings']['skin_flash'] == 'light') {
            $skin = 'deploy/preview_skin_overlay.swf';
        }
        $swfloc = $this->thepath . $skin_flash;
        $wmode = 'opaque';
        $wmode = $its['settings']['windowmode'];

        $targetfeed = '';
        $target_file = '';
        if (($its['settings']['feedfrom'] == 'youtube user channel') && $its['settings']['youtubefeed_user'] != '') {
            $user_feed = $its['settings']['youtubefeed_user'];
            $targetfeed = $its['settings']['youtubefeed_user'];
            if ($its['settings']['youtubefeed_playlist'] == '')
                $its['settings']['youtubefeed'] = 'off';
        }
        if (($its['settings']['feedfrom'] == 'youtube playlist') && $its['settings']['youtubefeed_playlist'] != '') {
            $yt_playlist_feed = $its['settings']['youtubefeed_playlist'];
            $targetfeed = $its['settings']['youtubefeed_playlist'];

            if (substr($yt_playlist_feed, 0, 2) == "PL") {
                $yt_playlist_feed = substr($yt_playlist_feed, 2);
            }
            $its['settings']['youtubefeed'] = 'on';
            $user_feed = '';
        }

        //..youtube user feed..
        if (($its['settings']['feedfrom'] == 'youtube user channel') && $its['settings']['youtubefeed_user'] != '') {
            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }
            //echo $target_file;
            $cacher = get_option('cache_dzsvg_ytuser');
            $target_file = $this->httpprotocol . "://gdata.youtube.com/feeds/api/users/" . $targetfeed . "/uploads?v=2&alt=jsonc";
            if ($cacher == '' || $this->usecaching == false) {
                $ida = dzs_get_contents($target_file);

                $cache_mainaux = array();
                $cache_aux = array(
                    'output' => $ida
                    , 'username' => $targetfeed
                    , 'time' => $_SERVER['REQUEST_TIME']
                );
                array_push($cache_mainaux, $cache_aux);
                update_option('cache_dzsvg_ytuser', $cache_mainaux);
            } else {
                if (is_array($cacher)) {
                    $ik = -1;
                    for ($i = 0; $i < count($cacher); $i++) {
                        if ($cacher[$i]['username'] == $targetfeed) {
                            if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                                $ik = $i;
                                break;
                            }
                        }
                    }
                    if ($ik > -1) {
                        $ida = $cacher[$ik]['output'];
                    } else {
                        $ida = dzs_get_contents($target_file);
                        $ik = -1;
                        for ($i = 0; $i < count($cacher); $i++) {
                            if ($cacher[$i]['username'] == $user_feed) {
                                $ik = $i;
                                break;
                            }
                        }
                        $cache_aux = array(
                            'output' => $ida
                            , 'username' => $targetfeed
                            , 'time' => $_SERVER['REQUEST_TIME']
                        );
                        if ($ik > -1) {
                            $cacher[$ik] = $cache_aux;
                        } else {
                            array_push($cacher, $cache_aux);
                        }
                        update_option('cache_dzsvg_ytuser', $cacher);
                    }
                }
            }
            $idar = json_decode($ida);
            $sw = false;

            if (isset($idar->error) || !is_array($idar->data->items)) {
                echo '<div class="error">Eror no. ' . $idar->error->code . ' please check username / playlist - <a href="' . $target_file . '">this</a> did not return anything to use...</div>';
                return;
            }
            $i = 0;
            if ($its['settings']['youtubefeed_maxvideos'] == '')
                $its['settings']['youtubefeed_maxvideos'] = 100;
            $yf_maxi = $its['settings']['youtubefeed_maxvideos'];

            if ($ida == 'yt:quotatoo_many_recent_calls') {
                echo 'too many recent calls - YouTube rejected the call';
                $this->log_event('too many recent calls - YouTube rejected the call');
                $sw = true;
            }
            if ($sw == false) {

                if (!is_array($idar->data->items)) {
                    $this->log_event('check youtube user channel name if correct, this is the target file - ' . $target_file);
                }
                foreach ($idar->data->items as $ytitem) {
                    //print_r($ytitem);
                    $its[$i]['source'] = $ytitem->id;
                    $its[$i]['thethumb'] = "";
                    $its[$i]['type'] = "youtube";

                    $aux = $ytitem->title;
                    $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                    $aux = str_replace($lb, ' ', $aux);
                    $its[$i]['title'] = $aux;

                    $aux = $ytitem->description;
                    $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                    $aux = str_replace($lb, ' ', $aux);
                    $its[$i]['description'] = $aux;

                    $i++;
                    if ($i > $yf_maxi + 1)
                        break;
                }

                $its[$i]['source'] = " ";
                $its[$i]['thethumb'] = " ";
                //$its[$i]['type'] = " ";
            }
        }



        //------start vimeo user channel //http://vimeo.com/api/v2/blakewhitman/videos.json
        if (($its['settings']['feedfrom'] == 'vimeo user channel') && $its['settings']['vimeofeed_user'] != '') {
            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }
            $target_file = "http://vimeo.com/api/v2/" . $its['settings']['vimeofeed_user'] . "/videos.json";

            $cacher = get_option('cache_dzsvg_vmuser');
            if ($cacher == '' || $this->usecaching == false) {
                $ida = dzs_get_contents($target_file);

                $cache_mainaux = array();
                $cache_aux = array(
                    'output' => $ida
                    , 'username' => $its['settings']['vimeofeed_user']
                    , 'time' => $_SERVER['REQUEST_TIME']
                );
                array_push($cache_mainaux, $cache_aux);
                update_option('cache_dzsvg_vmuser', $cache_mainaux);
            } else {
                if (is_array($cacher)) {
                    $ik = -1;
                    for ($i = 0; $i < count($cacher); $i++) {
                        if ($cacher[$i]['username'] == $its['settings']['vimeofeed_user']) {
                            if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                                $ik = $i;
                                break;
                            }
                        }
                    }
                    if ($ik > -1) {
                        $ida = $cacher[$ik]['output'];
                    } else {
                        $ida = dzs_get_contents($target_file);
                        $ik = -1;
                        for ($i = 0; $i < count($cacher); $i++) {
                            if ($cacher[$i]['username'] == $its['settings']['vimeofeed_user']) {
                                $ik = $i;
                                break;
                            }
                        }
                        $cache_aux = array(
                            'output' => $ida
                            , 'username' => $its['settings']['vimeofeed_user']
                            , 'time' => $_SERVER['REQUEST_TIME']
                        );
                        if ($ik > -1) {
                            $cacher[$ik] = $cache_aux;
                        } else {
                            array_push($cacher, $cache_aux);
                        }
                        update_option('cache_dzsvg_vmuser', $cacher);
                    }
                }
            }
            $idar = json_decode($ida);
            $i = 0;
            if(is_array($idar)){
            foreach ($idar as $item) {
                $its[$i]['source'] = $item->id;
                $its[$i]['thethumb'] = $item->thumbnail_small;
                if ($its['settings']['directurlaccess'] == 'on') {
                    $its[$i]['thethumb'] = '';
                }
                $its[$i]['type'] = "vimeo";

                $aux = $item->title;
                $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);
                $its[$i]['title'] = $aux;

                $aux = $item->description;
                $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);
                $its[$i]['description'] = $aux;
                $i++;
            }
            }else{
                $this->log_event('vimeo foreach error');
                echo '<div class="error">error: <a href="' . $target_file . '">this</a> returned nothing useful</div>';
            }
        }

        //------start youtube playlist
        if (($its['settings']['feedfrom'] == 'youtube playlist') && $its['settings']['youtubefeed_playlist'] != '') {
            $len = count($its) - 1;
            for ($i = 0; $i < $len; $i++) {
                unset($its[$i]);
            }
            $target_file = "https://gdata.youtube.com/feeds/api/playlists/" . $yt_playlist_feed . "?alt=json&start-index=1&max-results=40";

            $cacher = get_option('cache_dzsvg_ytpls');
            if ($cacher == '' || $this->usecaching == false) {
                $ida = dzs_get_contents($target_file);

                $cache_mainaux = array();
                $cache_aux = array(
                    'output' => $ida
                    , 'username' => $yt_playlist_feed
                    , 'time' => $_SERVER['REQUEST_TIME']
                );
                array_push($cache_mainaux, $cache_aux);
                
                if (strpos($ida, 'many_recent_calls')==false){
                update_option('cache_dzsvg_ytpls', $cache_mainaux);
                }
            } else {
                if (is_array($cacher)) {
                    $ik = -1;
                    for ($i = 0; $i < count($cacher); $i++) {
                        if ($cacher[$i]['username'] == $yt_playlist_feed) {
                            if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
                                $ik = $i;
                                break;
                            }
                        }
                    }
                    if ($ik > -1 && !strpos($cacher[$ik]['output'], 'too_many_recent_calls')) {
                        //print_r($cacher); echo 'ceva2121'; echo $cacher[$i]['username'] . $yt_playlist_feed; echo $cacher[$ik]['output'];
                        $ida = $cacher[$ik]['output'];
                    } else {
                        $ida = dzs_get_contents($target_file);
                        $ik = -1;
                        for ($i = 0; $i < count($cacher); $i++) {
                            if ($cacher[$i]['username'] == $yt_playlist_feed) {
                                $ik = $i;
                                break;
                            }
                        }
                        $cache_aux = array(
                            'output' => $ida
                            , 'username' => $yt_playlist_feed
                            , 'time' => $_SERVER['REQUEST_TIME']
                        );
                        if ($ik > -1) {
                            $cacher[$ik] = $cache_aux;
                        } else {
                            array_push($cacher, $cache_aux);
                        }
                        update_option('cache_dzsvg_ytpls', $cacher);
                    }
                }
            }
            $ida = dzs_get_contents($target_file);
            //echo 'ceva';
            $idar = json_decode($ida);
            $i = 0;
            if ($its['settings']['youtubefeed_maxvideos'] == '') {
                $its['settings']['youtubefeed_maxvideos'] = 100;
            }
            $yf_maxi = $its['settings']['youtubefeed_maxvideos'];
            
            
            
            if (strpos($ida, 'too_many_recent_calls')){
                return '<div class="error">youtube rejected this call because too many calls where maken</div>';
                
            }
            if ($idar == '' || !is_array($idar->feed->entry)) {
                echo '<div class="error">please check playlist id - <a href="' . $target_file . '">this</a> did not return anything to use or could not be retrieved by the server ( if you can see the link target in a json string then your server does not have curl / furl enabled...</div>';
                return;
            }
            if (is_array($idar->feed->entry)) {
                foreach ($idar->feed->entry as $ytitem) {
                    //print_r($ytitem);
                    $cache = $ytitem;
                    $aux = array();
                    $auxtitle = '';
                    $auxcontent = '';
                    //print_r($cache);
                    if ($cache->title) {
                        foreach ($cache->title as $hmm) {
                            $auxtitle = $hmm;
                            break;
                        }
                    }
                    if ($cache->content) {
                        foreach ($cache->content as $hmm) {
                            $auxcontent = $hmm;
                            break;
                        }
                    }
                    //echo 'muiecrys'.is_array($cache->content);
                    parse_str($ytitem->link[0]->href, $aux);

                    //print_r($aux);
                    $theid = $aux[$this->httpprotocol . '://www_youtube_com/watch?v'];
                    $its[$i]['source'] = $theid;
                    $its[$i]['thethumb'] = "";
                    $its[$i]['audioimage'] = "http://img.youtube.com/vi/" . $theid . "/0.jpg";

                    $its[$i]['type'] = "youtube";
                    $its[$i]['title'] = $auxtitle;
                    $its[$i]['menuDescription'] = $auxcontent;
                    $its[$i]['description'] = $auxcontent;

                    $aux2 = get_object_vars($ytitem->title);
                    $aux = ($aux2['$t']);
                    $lb = array("\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                    $aux = str_replace($lb, ' ', $aux);

                    /*
                      $aux = $ytitem->description;
                      $lb   = array("\r\n", "\n", "\r", "&" ,"-", "`", '???', "'", '-');
                      $aux = str_replace($lb, ' ', $aux);
                      $its['settings']['description'] = $aux;
                     */
                    $i++;
                    if ($i > $yf_maxi)
                        break;
                }
            }
        }
        //------end youtube playlist

if($its['settings']['randomize'] == 'on' && is_array($its)){

    $backup_its = $its;
//print_r($its); $rand_keys = array_rand($its, count($its)); print_r($rand_keys);
shuffle($its);
//print_r($its);print_r($backup_its);

for($i=0; $i<count($its); $i++){
    if(isset($its[$i]['feedfrom'])){
        //print_r($it);
        
        unset($its[$i]);
    }
}
$its['settings'] = $backup_its['settings'];
$its = array_reverse($its);
//print_r($its);

    
}


        $tw = $its['settings']['width'];
        $th = $its['settings']['height'];
        $etw = $tw;
        $eth = $th;
        if ($its['settings']['scrollbar'] == 'on') {
            if ($its['settings']['menuposition'] == 'right') {
                $etw+=15;
            }
        }
        
        if(strpos($tw, "%")===false){
            $tw = $tw.'px';
        }
        if(strpos($th, "%")===false){
            $th = $th.'px';
        }
        if($args['fullscreen']=='on'){
            $tw = '100%';
            $th = '100%';
            $its['settings']['disableNonDefault'] = 'on';
            $its['settings']['defaultvg'] = 'html5';
            $its['settings']['html5responsive'] = 'on';
        }

        $fout.='<div class="gallery-precon gp' . $this->sliders_index . '';
        if($args['fullscreen']=='on'){
            $fout.=' gallery-is-fullscreen';
        }
        
        $fout.='" style="width:' . $tw . ';';
        
        if($args['fullscreen']=='on'){
            $fout.=' height:' . $th . ';';
            $fout.=' position:' . 'fixed' . '; z-index:50005; top:0; left:0;';
        }
        if($args['category']!=''){
            $fout.=' display:none;"';
            $fout.=' data-category="'.$args['category'].'';
        }
        $fout.='"';
        $fout.='>';
        if (!($its['settings']['disableNonDefault'] == 'on' && $its['settings']['defaultvg'] == 'html5')) {
            $fout.='<div class="flashgallery-con" style="height:' . $h . ';">';




            $fout.='<object type="application/x-shockwave-flash" data="' . $swfloc . '" width="' . $etw . '" height="' . $eth . '" id="flashcontent' . $this->sliders_index . '" wmode="' . $wmode . '" style="visibility: visible;">
<param name="movie" value="' . $swfloc . '"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="bgcolor" value="' . $its['settings']['bgcolor'] . '">
<param name="scale" value="noscale"><param name="allowFullScreen" value="true"><param name="wmode" value="' . $wmode . '">
<param name="flashvars" value="';
            //print_r($its[$k]);








            $videos = '';
            $thumbs = '';
            $titles = '';
            $descriptions = '';
            $menuDescriptions = '';
            $types = '';
            for ($i = 0; $i < count($its) - 1; $i++) {
                $videos .= $its[$i]['source'];
                if ($i != count($its) - 2) {
                    $videos.=';';
                }

                if ($its[$i]['thethumb'] == '' && $its[$i]['type'] == 'vimeo') {
                    $imgid = $its[$i]['source'];
                    $url = "http://vimeo.com/api/v2/video/$imgid.php";
                    $cache = dzs_get_contents($url);
                    $imga = unserialize($cache);
                    $img = ($imga[0]['thumbnail_small']);
                    $its[$i]['thethumb'] = $img;
                }
                $thumbs .= $its[$i]['thethumb'];

                if ($i != count($its) - 2) {
                    $thumbs.=';';
                }


                if (isset($its[$i]['type'])) {
                    $types .= $its[$i]['type'];
                }

                if ($i != count($its) - 2) {
                    $types.=';';
                }
                if (isset($its[$i]['title'])) {
                    $titles .= $its[$i]['title'];
                }
                if ($i != count($its) - 2) {
                    $titles.=';';
                }
                if (isset($its[$i]['description'])) {
                    $descriptions .= $its[$i]['description'];
                }
                if ($i != count($its) - 2) {
                    $descriptions.=';';
                }
                if (isset($its[$i]['menuDescription'])) {
                    $menuDescriptions .= $its[$i]['menuDescription'];
                }
                if ($i != count($its) - 2) {
                    $menuDescriptions.=';';
                }
            }
            $shareIcons = '';
            $shareLinks = '';
            $shareTooltips = '';
            if ($its['settings']['sharebutton'] == 'on') {
                if ($its['settings']['facebooklink'] != '') {
                    $shareIcons.=$this->thepath . 'icons/1.png';
                    $shareLinks.=$its['settings']['facebooklink'];
                    $shareTooltips.=__('Share on Facebook');
                }
                if ($its['settings']['twitterlink'] != '') {
                    $shareIcons.=';';
                    $shareLinks.=';';
                    $shareTooltips.=';';
                    $shareIcons.=$this->thepath . 'icons/3.png';
                    $shareLinks.=$its['settings']['twitterlink'];
                    $shareTooltips.=__('Share on Twitter');
                }
                if ($its['settings']['googlepluslink'] != '') {
                    $shareIcons.=';';
                    $shareLinks.=';';
                    $shareTooltips.=';';
                    $shareIcons.=$this->thepath . 'icons/2.png';
                    $shareLinks.=$its['settings']['googlepluslink'];
                    $shareTooltips.=__('Share on Google Plus');
                }
            }
            //echo $videos;
            //print_r($its);
            //echo $this->abspath;
            $fout.=dzs_addSwfAttr('video', $videos, true);
            $fout.=dzs_addSwfAttr('totalWidth', $its['settings']['width']);
            $fout.=dzs_addSwfAttr('totalHeight', $its['settings']['height']);
            $fout.=dzs_addSwfAttr('thumbs', $thumbs);
            $fout.=dzs_addSwfAttr('types', $types);
            $fout.=dzs_addSwfAttr('titles', $titles);
            $fout.=dzs_addSwfAttr('descriptions', $descriptions);
            $fout.=dzs_addSwfAttr('menuDescriptions', $menuDescriptions);
            $fout.=dzs_addSwfAttr('menuPosition', $its['settings']['menuposition']);

            
            if ($its['settings']['defaultvg'] == 'html5' && $its['settings']['autoplay'] == 'on') {
                $fout.=dzs_addSwfAttr('autoplay', "off");
            } else {
                $fout.=dzs_addSwfAttr('autoplay', $its['settings']['autoplay']);
            }
            


            if ($its['settings']['directurlaccess'] == 'on') {
                if (($its['settings']['feedfrom'] == 'youtube user channel') && $its['settings']['youtubefeed_user'] != '') {
                    $fout.=dzs_addSwfAttr('youtubeFeed', 'on');
                    $fout.=dzs_addSwfAttr('youtubeFeed_user', $its['settings']['youtubefeed_user']);
                }
                if (($its['settings']['feedfrom'] == 'youtube playlist') && $its['settings']['youtubefeed_playlist'] != '') {
                    $fout.=dzs_addSwfAttr('youtubeFeed', 'on');
                    $fout.=dzs_addSwfAttr('youtubeFeed_playlistId', $its['settings']['youtubefeed_playlist']);
                }
            }

            //$fout.=dzs_addSwfAttr('shareIcons', $this->abspath . 'img/twitter.png;' . $this->abspath . 'img/facebook.png');
            //$fout.=dzs_addSwfAttr('shareTooltips', "Tweet It;Share on Facebook");
            //$fout.=dzs_addSwfAttr('shareLinks', "http://twitter.com/sharecr63urlcr61".dzs_curr_url()."cr38textcr61Awesome%20VideoGallery;http://www.facebook.com/sharer.phpcr63ucr61".dzs_curr_url()."cr38tcr61Awesome%20VideoGallery");
            if ($its['settings']['embedbutton'] == 'on') {
                $fout.=dzs_addSwfAttr('embedButton', "on");
                $fout.=dzs_addSwfAttr('htmlEmbedCode', "cr60iframe src=cr34" . $this->thepath . 'bridge.phpcr63actioncr61viewcr38idcr61' . $its['settings']['id'] . "cr34 width=cr34" . $its['settings']['width'] . "cr34 height=cr34" . $its['settings']['height'] . "cr34 style=cr34overflow:hidden;cr34 cr62cr60/iframecr62");
            }
            if (isset($its['settings']['coverImage']) && $its['settings']['coverImage'] != '') {
                $fout.=dzs_addSwfAttr('cueFirstVideo', "off");
                $fout.=dzs_addSwfAttr('thumb', $its['settings']['coverImage']);
            }
            if (isset($its['settings']['logo']) && $its['settings']['logo'] != '') {
                $fout.=dzs_addSwfAttr('logo', $its['settings']['logo']);
            }
            if (isset($its['settings']['logoLink']) && $its['settings']['logoLink'] != '') {
                $fout.=dzs_addSwfAttr('logoLink', $its['settings']['logoLink']);
            }
            if ($its['settings']['skin_flash'] == 'custom') {
                $fout.=dzs_addSwfAttr('designXML', $this->thepath . 'deploy/xml/design.xml');
            }
            //print_r($its);
            if ($its['settings']['sharebutton'] == 'on') {
                $fout.=dzs_addSwfAttr('shareButton', 'on');
                $fout.=dzs_addSwfAttr('shareIcons', $shareIcons);
                $fout.=dzs_addSwfAttr('shareLinks', $shareLinks);
                $fout.=dzs_addSwfAttr('shareTooltips', $shareTooltips);
            }
            if ($its['settings']['hdButton'] == 'on') {
                $fout.=dzs_addSwfAttr('hdButton', 'on');
            }
            if ($its['settings']['scrollbar'] == 'on') {
                $fout.=dzs_addSwfAttr('scrollbar', 'on');
            }
            if ($its['settings']['disablebigplaybutton'] == 'on') {
                $fout.=dzs_addSwfAttr('player_design_disable_bigplay', 'on');
            }
            if ($its['settings']['playerdesignonlybigplay'] == 'on') {
                $fout.=dzs_addSwfAttr('player_design_only_bigplay', 'on');
            }
            if ($its['settings']['defaultquality'] == 'HD') {
                $fout.=dzs_addSwfAttr('defaultQuality', 'hd');
            }
            if (isset($its['settings']['autoplaynext']) && $its['settings']['coverImage']) {
                $fout.=dzs_addSwfAttr('autoplayNextVideo', $its['settings']['autoplaynext']);
            }



            $fout.='">';
            $fout.='</object>';


            $fout.='</div>'; //end flashgallery-con
        }


        $menuitem_w = $its['settings']['html5designmiw'];
        $menuitem_h = $its['settings']['html5designmih'];
        $menuposition = ($its['settings']['menuposition']);
        $html5mp = $menuposition;
        
        $jreadycall = 'jQuery(document).ready(function($)';
        if ($menuposition == 'right' || $menuposition == 'left') {
            //$tw -= $menuitem_w;
        }
        if ($menuposition == 'down' || $menuposition == 'up') {
            //$th -= $menuitem_h;
        }
        if ($menuposition == 'down') {
            $html5mp = 'bottom';
        }
        if ($menuposition == 'up') {
            $html5mp = 'top';
        }

        if($its['settings']['skin_html5vp']=='skin_custom'){
            $its['settings']['skin_html5vp'] = 'skin_pro skin_custom';
        }
        if($its['settings']['skin_html5vg']=='skin_custom'){
            $its['settings']['skin_html5vg'] = 'skin_pro skin_custom';
        }
        //echo $its['settings']['skin_html5vg'];
        
        if($args['fullscreen']!='on'){
        $fout.='<div class="videogallery-con" style="width:' . $tw . '; height:' . $th . '; display:none;"><div class="preloader"></div>';
        }
        $fout.='<div id="vg' . $this->sliders_index . '" class="videogallery id_'.$its['settings']['id'].' '.$its['settings']['skin_html5vg'].'" style="width:' . $tw . '; height:' . $th . '; background-color:' . $its['settings']['bgcolor'] . '">';
//<div class="vplayer-tobe" data-videoTitle="Pages"  data-description="<img src=thumbs/pages1.jpg class='imgblock'/><div class='the-title'>Pages</div>AE Project by Generator" data-sourcemp4="video/pages.mp4" data-sourceogg="video/pages.ogv" ><div class="videoDescription">You can have a description here if you choose to.</div></div>

        for ($i = 0; $i < count($its) - 1; $i++) {
            $che = $its[$i];
            if ($che['source'] == '' || $che['source'] == ' ') {
                continue;
            }
            $fout.='<div class="vplayer-tobe"';
            if ($i == 0) {
                if (isset($its['settings']['coverImage']) && $its['settings']['coverImage']) {
                    $fout.=' data-img="' . $its['settings']['coverImage'] . '"';
                }
            }
            if (isset($che['title']) && $che['title']) {
                $fout.=' data-videoTitle="' . $che['title'] . '"';
            }
            if (isset($che['type']) && $che['type'] == 'video') {
                $fout.=' data-sourcemp4="' . $che['source'] . '"';
                if (isset($che['html5sourceogg']) & $che['html5sourceogg'] != '') {
                    $fout.=' data-sourceogg="' . $che['html5sourceogg'] . '"';
                }
            }
            if (isset($che['audioimage']) && $che['audioimage'] != '') {
                $fout.=' data-previewimg="' . $che['audioimage'] . '"';
            }else{
                
                if ($its['settings']['displaymode'] == 'wall' && isset($che['thethumb']) && $che['thethumb'] != '') {
                $fout.=' data-previewimg="' . $che['thethumb'] . '"';
                }
            }
            if (isset($che['type']) && $che['type'] == 'audio') {
                $fout.=' data-sourcemp3="' . $che['source'] . '"';
                if (isset($che['html5sourceogg']) && $che['html5sourceogg'] != '') {
                    $fout.=' data-sourceogg="' . $che['html5sourceogg'] . '"';
                }
                if (isset($che['audioimage']) && $che['audioimage'] != '') {
                    $fout.=' data-audioimg="' . $che['audioimage'] . '"';
                }
                $fout.=' data-type="audio"';
            }
            if (isset($che['type']) && $che['type'] == 'youtube') {
                $fout.=' data-type="youtube"';
                $fout.=' data-src="' . $che['source'] . '"';
            }
            if (isset($che['type']) && $che['type'] == 'vimeo') {
                $fout.=' data-type="vimeo"';
                $fout.=' data-src="'.$che['source'].'"';
            }
            if (isset($che['type']) && $che['type'] == 'image') {
                $fout.=' data-type="image"';
                $fout.=' data-img="'.$che['source'].'"';
            }
            if (isset($che['type']) && $che['type'] == 'inline') {
                $fout.=' data-type="inline"';
            }
            $aux = 'adsource';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout.=' data-' . $aux . '="' . $che[$aux] . '"';
            }
            $aux = 'adtype';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout.=' data-' . $aux . '="' . $che[$aux] . '"';
            }
            $aux = 'adlink';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout.=' data-' . $aux . '="' . $che[$aux] . '"';
            }
            $fout.='>';
            if (isset($che['description']) && $che['description']) {
                $fout.='<div class="videoDescription">' . $che['description'] . '</div>';
            }

            $fout.='<div class="menuDescription">';
            if (isset($che['thethumb']) && $che['thethumb'] != '') {
                $fout.='<img src="' . $che['thethumb'] . '" class="imgblock"/>';
            } else {
                if ($che['type'] == 'youtube') {
                    $fout.='{ytthumb}';
                }
            }
            if (((!isset($its['settings']['disable_title']) || isset($its['settings']['disable_title'])) && $its['settings']['disable_title']!='on') && isset($che['title']) && $che['title']) {
                $fout.='<div class="the-title">' . $che['title'] . '</div>';
            }
            if (((!isset($its['settings']['disable_menu_description']) || isset($its['settings']['disable_menu_description'])) && $its['settings']['disable_menu_description']!='on') && isset($che['menuDescription']) && $che['menuDescription']) {
                $fout.=$che['menuDescription'];
            }
            $fout.='</div>';//---menuDescription END
            if (isset($che['tags']) && $che['tags']) {
                $arr_septag = explode('$$;', $che['tags']);
                foreach($arr_septag as $septag){
                    //print_r($septag);
                    if($septag!=''){
                    $arr_septagprop = explode('$$', $septag);
                    //print_r($arr_septagprop);
                    $fout.='<div class="dzstag-tobe" data-starttime="'.$arr_septagprop[0].'" data-endtime="'.$arr_septagprop[1].'" data-left="'.$arr_septagprop[2].'" data-top="'.$arr_septagprop[3].'" data-width="'.$arr_septagprop[4].'" data-height="'.$arr_septagprop[5].'" data-link="'.$arr_septagprop[6].'">'.$arr_septagprop[7].'</div>';
                
                    }
                }
                //print_r($arr_septag);
            }
            
            if (isset($che['type']) && $che['type'] == 'inline') {
                $fout.=$che['source'];
            }
            $fout.='</div>';
        }
        $html5vgautoplay = 'off';
        if ($its['settings']['autoplay'] == 'on' && $its['settings']['defaultvg'] == 'html5') {
            $html5vgautoplay = 'on';
        }
        
        if ($its['settings']['fullscreen'] != 'on') {
            $html5vgautoplay = '</div>';
        }
        $fout.='</div></div>
<script>
var flashhtml5main' . $this->sliders_index . ' = {
    defaultis : "' . $its['settings']['defaultvg'] . '", //flash or html5
    target : "gp' . $this->sliders_index . '"
}
var videoplayersettings = {
autoplay : "off",
videoWidth : 500,
videoHeight : 300,
constrols_out_opacity : 0.9,
constrols_normal_opacity : 0.9
,settings_swfPath : "'.$this->thepath.'deploy/preview.swf"
}
';
        if ($its['settings']['displaymode'] == 'wall') {
            $fout.='window.zoombox_videoplayersettings = videoplayersettings;';
        }
        
                $fout.=$jreadycall . '{
videoplayersettings.design_skin = "'.$its['settings']['skin_html5vp'].'";
videoplayersettings.settings_youtube_usecustomskin = "'.$its['settings']['yt_customskin'].'";
videoplayersettings.constrols_normal_opacity = "'.$its['settings']['html5design_controlsopacityon'].'";
videoplayersettings.constrols_out_opacity = "'.$its['settings']['html5design_controlsopacityout'].'";
zsvg_init("#vg' . $this->sliders_index . '",{
menuSpace:0
,randomise:"off"
,totalWidth : "' . $tw . '"
,totalHeight : "' . $th . '"
,autoplay :"' . $html5vgautoplay . '"
,autoplayNext : "' . $its['settings']['autoplaynext'] . '"
,nav_type : "' . $its['settings']['nav_type'] . '"
,menuitem_width:"' . $menuitem_w . '"
,menuitem_space:"' . $its['settings']['html5designmis'] . '"
,menuitem_height:"' . $menuitem_h .'"
';
        if (isset($its['settings']['cueFirstVideo'])) {
            $fout.=',cueFirstVideo:"' . $its['settings']['cueFirstVideo'] . '"';
        }
        if (isset($its['settings']['displaymode']) && ($its['settings']['displaymode'] == 'wall' || $its['settings']['displaymode'] == 'normal') || $its['settings']['displaymode'] == 'rotator' || $its['settings']['displaymode'] == 'rotator3d') {
            $fout.=',settings_mode:"' . $its['settings']['displaymode'] . '"';
        }

        if (isset($its['settings']['logoLink']) && $its['settings']['logoLink'] != '') {
            $fout.=',logoLink:"' . $its['settings']['logoLink'] . '"';
        }
        $fout.=',menu_position:"' . $html5mp . '"
,transition_type:"' . $its['settings']['html5transition'] . '"
,design_skin: "' . $skin_html5vg . '"';

        if (isset($its['settings']['logo']) && $its['settings']['logo'] != '') {
            $fout.=',logo : "' . $its['settings']['logo'] . '" ';
        }

        if (isset($its['settings']['html5responsive']) && $its['settings']['html5responsive'] == 'on') {
            $fout.=',responsive : "on" ';
        }else{
            $fout.=',responsive : "off" ';
        }
        if ($its['settings']['sharebutton'] == 'on') {
            $auxout = '';
            if ($its['settings']['facebooklink'] != 'on') {
                $auxout .= '<a class="icon" href="' . $its['settings']['facebooklink'] . '"><img src="' . $this->thepath . 'img/facebook.png"/></a>';
            }
            if ($its['settings']['twitterlink'] != 'on') {
                $auxout .= '<a class="icon" href="' . $its['settings']['twitterlink'] . '"><img src="' . $this->thepath . 'img/twitter.png"/></a>';
            }
            if ($its['settings']['googlepluslink'] != 'on') {
                $auxout .= '<a class="icon" href="' . $its['settings']['googlepluslink'] . '"><img src="' . $this->thepath . 'img/google.png"/></a>';
            }
            $fout.=',shareCode : ' . "'" . $auxout . "'" . ' ';
        }

        if ($its['settings']['embedbutton'] == 'on') {
            $auxout = '<iframe src="' . $this->thepath . 'bridge.php?action=view&id=' . $its['settings']['id'] . '&db=' . $this->currDb . '" width="' . $its['settings']['width'] . '" height="' . $its['settings']['height'] . '" style="overflow:hidden;"></iframe>';
            $fout.=',embedCode : \'' . $auxout . '\' ';
        }

        $fout.=',videoplayersettings : videoplayersettings
})	
flashhtml5(flashhtml5main' . $this->sliders_index . ');
})
</script>';
        if ($its['settings']['shadow'] == 'on') {
            $fout.='<div class="all-shadow" style="width:' . $w . ';"></div>';
        }

        //print_r($its);
        if ($its['settings']['enableswitch'] == 'on' && $its['settings']['disableNonDefault'] == 'off') {
            $fout.='<div class="html5-button-con alignright"><div class="html5-button">' . __('Switch to', 'dzsvg') . ' HTML5</div></div>';
        }
        $fout.='<div class="clear"></div>';
        $fout.='</div>'; //END gallery-precon
        if ($its['settings']['displaymode'] == 'wall') {
            wp_enqueue_script('jquery.masonry', $this->thepath . "masonry/jquery.masonry.min.js");
            
            wp_enqueue_style('dzs.zoombox', $this->thepath . 'zoombox/zoombox.css');
            wp_enqueue_script('dzs.zoombox', $this->thepath . 'zoombox/zoombox.js');
        }


        
        //----mode alternatewall
        if ($its['settings']['displaymode'] == 'alternatewall') {
            $fout = '';
            $fout.='<style>
            .dzs-gallery-container .item{ width:23%; margin-right:1%; float:left; position:relative; display:block; margin-bottom:10px; }
            .dzs-gallery-container .item-image{ width:100%; }
            .dzs-gallery-container h4{  color:#D26; }
            .dzs-gallery-container h4:hover{ background: #D26; color:#fff; }
            .last { margin-right:0!important; }
            .clear { clear:both; }
            </style>';
            $fout.='<div class="dzs-gallery-container">';

            for ($i = 0; $i < count($its) - 1; $i++) {
                if (!isset($its[$i]['type'])) {
                    continue;
                }
                $islastonrow = false;
                if ($i % 4 == 3) {
                    $islastonrow = true;
                }
                $itemclass = 'item';
                if ($islastonrow == true) {
                    $itemclass.=' last';
                }
                $fout.='<div class="' . $itemclass . '">';
                $fout.='<a href="' . $this->thepath . 'ajax.php?ajax=true&height=' . $its['settings']['height'] . '&width=' . $its['settings']['width'] . '&type=' . $its[$i]['type'] . '&source=' . $its[$i]['source'] . '" title="' . $its[$i]['type'] . '" rel="prettyPhoto"><img class="item-image" src="';
                if ($its[$i]['thethumb'] != '')
                    $fout.=$its[$i]['thethumb'];
                else {
                    if ($its[$i]['type'] == "youtube") {
                        $fout.='https://img.youtube.com/vi/' . $its[$i]['source'] . '/0.jpg';
                        $its[$i]['thethumb'] = 'https://img.youtube.com/vi/' . $its[$i]['source'] . '/0.jpg';
                    }
                }
                $fout.='"/></a>';
                $fout.='<h4>' . $its[$i]['title'] . '</h4>';
                $fout.='</div>';
                if ($islastonrow) {
                    $fout.='<div class="clear"></div>';
                }
            }
            $fout.='<div class="clear"></div>';
            $fout.='</div>';
            $fout.='<div class="clear"></div>';
            $fout.='<script>jQuery(document).ready(function($){ jQuery("a[rel^=prettyPhoto]").prettyPhoto(); });</script>';
            return $fout;
        }



        /////---mode alternatemenu
        if ($its['settings']['displaymode'] == 'alternatemenu') {
            $i = 0;
            $k = 0;


            $current_urla = explode("?", dzs_curr_url());
            $current_url = $current_urla[0];

            $fout = '';
            $fout .= '
<style type="text/css">
.submenu{
margin:0;
padding:0;
list-style-type:none;
list-style-position:outside;
position:relative;
z-index:32;
}

.submenu a{
display:block;
padding:5px 15px;
background-color: #28211b;
color:#fff;
text-decoration:none;
}

.submenu li ul a{
display:block;
width:200px;
height:auto;
}

.submenu li{
float:left;
position:static;
width: auto;
position:relative;
}

.submenu ul, .submenu ul ul{
position:absolute;
width:200px;
top:auto;
display:none;
list-style-type:none;
list-style-position:outside;
}
.submenu > li > ul{
position:absolute;
top:auto;
left:0;
margin:0;
}

.submenu a:hover{
background-color:#555;
color:#eee;
}

.submenu li:hover ul, .submenu li li:hover ul{
display:block;
}
</style>';

            $fout .= '<ul class="submenu">';
            if (isset($this->mainitems)) {
                for ($k = 0; $k < count($this->mainitems); $k++) {
                    if (count($this->mainitems[$k]) < 2) {
                        continue;
                    }
                    $fout.='<li><a href="#">' . $this->mainitems[$k]["settings"]["id"] . '</a>';

                    if (isset($this->mainitems[$k]) && count($this->mainitems[$k]) > 1) {

                        $fout.='<ul>';
                        for ($i = 0; $i < count($this->mainitems[$k]); $i++) {
                            if (isset($this->mainitems[$k][$i]["thethumb"]))
                                $fout.='<li><a href="' . $current_url . '?the_source=' . $this->mainitems[$k][$i]["source"] . '&the_thumb=' . $this->mainitems[$k][$i]["thethumb"] . '&the_type=' . $this->mainitems[$k][$i]["type"] . '&the_title=' . $this->mainitems[$k][$i]["title"] . '">' . $this->mainitems[$k][$i]["title"] . '</a>';
                        }
                        $fout.='</ul>';
                    }
                    $fout.='</li>';
                }
            }

            $k = 0;
            $i = 0;
            $fout .= '</ul>
<div class="clearfix"></div>
<br>';

            if (isset($_REQUEST['the_source'])) {
                $fout.='<div id="hiddenModalContent" style="display:none; width:' . $its['settings']["width"] . 'px; height:' . ($its['settings']["height"] + 20) . 'px;">
<p><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' . $its['settings']["width"] . '" height="' . $its['settings']["height"] . '">
        <param name="movie" value="' . $this->thepath . 'deploy/preview.swf?video=' . $_REQUEST['the_source'] . '&types=' . $_REQUEST['the_type'] . '" />
        <param name="allowFullScreen" value="true"/>
        <param name="allowScriptAccess" value="always"/>
        <param name="wmode" value="opaque"/>
        <object type="application/x-shockwave-flash" data="' . $this->thepath . 'deploy/preview.swf?video=' . $_REQUEST['the_source'] . '&types=' . $_REQUEST['the_type'] . '" width="' . $its['settings']["width"] . '" height="' . $its['settings']["height"] . '" allowFullScreen="true" allowScriptAccess="always" wmode="opaque">
        <video width="300" height="200" src="' . $this->mainitems[$k][$i]["source"] . '"></video></object>
</object></p>
</div>';
                $inline_thumb = '<img width="320" height="240" src="' . $_REQUEST['the_thumb'] . '" alt="You can set a image here via the Thumb field."/>';
                if ($_REQUEST['the_type'] == "youtube")
                    $inline_thumb = '<img width="320" height="240" src="https://img.youtube.com/vi/' . $_REQUEST['the_source'] . '/0.jpg" alt="You can set a image here via the Thumb field."/>';

                $fout.='<a href="#hiddenModalContent" title="' . $_REQUEST['the_title'] . '" rel="prettyPhoto">' . $inline_thumb . '</a>
';
            }



            return $fout;
        }

        return $fout;



        //echo $k;
    }

    function admin_init() {
        add_meta_box('zsvg_meta_options', __('DZS Video Gallery Settings'), array($this,'admin_meta_options'), 'post', 'normal', 'high');
        add_meta_box('zsvg_meta_options', __('DZS Video Gallery Settings'), array($this,'admin_meta_options'), 'page', 'normal', 'high');
    }
    function admin_meta_options(){
          global $post;
                ?>
                <input type="hidden" name="dzs_nonce" value="<?php echo wp_create_nonce('dzs_nonce'); ?>" />
              <h4><?php _e("Fullscreen Gallery", 'dzsvg'); ?></h4>
              <select class="textinput styleme" name="zsvg_fullscreen">
                  <option>none</option>
                  <?php
                  foreach($this->mainitems as $it){
                      echo '<option ';
                      dzs_checked(get_post_meta($post->ID,  'zsvg_fullscreen', true), $it['settings']['id'], 'selected');
                      echo '>'.$it['settings']['id'].'</option>';
                  }
                  ?>
              </select>
              <div class="clear"></div>
            
                        <div class="sidenote">
                            <?php echo __('Get a fullscreen gallery in your post / page with a close button.', 'dzsvg'); ?><br/>
                        </div>
          <?php
    }
    function admin_meta_save($post_id){
        global $post;
        if(!$post){
            return;
        }
        if(isset($post->post_type) && !($post->post_type=='post' || $post->post_type=='page')){
                return $post_id;
        }
	/* Check autosave */
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
        if(isset($_REQUEST['dzs_nonce'])){
        $nonce=$_REQUEST['dzs_nonce'];
        if (! wp_verify_nonce($nonce, 'dzs_nonce') ) wp_die('Security check'); 
        }
        dzs_savemeta($post->ID, 'zsvg_fullscreen', $_POST['zsvg_fullscreen']);
    }

    function handle_init() {
        //wp_deregister_script('jquery');        wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"), false, '1.9.0');
        wp_enqueue_script('jquery');
        if (is_admin()) {
        wp_enqueue_style('zsvg_admin_global', $this->thepath . 'admin/admin_global.css');
        wp_enqueue_script('zsvg_admin_global', $this->thepath . 'admin/admin_global.js');
            if (isset($_GET['page']) && $_GET['page'] == 'zsvg_menu') {
                $this->admin_scripts();
            }
            if (isset($_GET['page']) && $_GET['page'] == 'zsvg-dc') {
                wp_enqueue_style('zsvg-dc.style', $this->thepath . 'deploy/designer/style/style.css');
        wp_enqueue_script('dzs.farbtastic', $this->thepath . "admin/colorpicker/farbtastic.js");
        wp_enqueue_style('dzs.farbtastic', $this->thepath . 'admin/colorpicker/farbtastic.css');
                wp_enqueue_script('zsvg-dc.admin', $this->thepath . 'deploy/designer/js/admin.js');
            }
            if (isset($_GET['page']) && $_GET['page'] == 'zsvg-mo') {
        wp_enqueue_style('zsvg_admin', $this->thepath . 'admin/admin.css');
        wp_enqueue_script('zsvg_admin', $this->thepath . "admin/admin.js");
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_style('iphone.checkbox', $this->thepath . 'admin/checkbox/checkbox.css');
        wp_enqueue_script('iphone.checkbox', $this->thepath . "admin/checkbox/checkbox.dev.js");
            }

            if (current_user_can('edit_posts') || current_user_can('edit_pages')) {
                wp_enqueue_script('thickbox');
                wp_enqueue_style('thickbox');
                wp_enqueue_script('zsvg_htmleditor', $this->thepath . 'tinymce/plugin-htmleditor.js');
                wp_enqueue_script('configreceiver', $this->thepath . 'tinymce/receiver.js');
            }
        } else {
            if($this->mainoptions['always_embed']=='on'){
                $this->front_scripts();
            }
        }
    }

    function handle_admin_menu() {

        if ($this->pluginmode == 'theme') {
            $zsvg_page = add_theme_page(__('DZS Video Gallery', 'dzsvg'), __('DZS Video Gallery', 'dzsvg'), $this->admin_capability, $this->adminpagename, array($this, 'admin_page'));
        } else {
            //$zsvg_page = add_options_page(__('DZS Video Gallery', 'dzsvg'), __('DZS Video Gallery', 'dzsvg'), $this->admin_capability, $this->adminpagename, array($this, 'admin_page'));
        
            $zsvg_page = add_menu_page(__('Video Gallery', 'dzsvg'), __('Video Gallery', 'dzsvg'), $this->admin_capability, $this->adminpagename, array($this, 'admin_page'), 'div');
            $zsvg_subpage = add_submenu_page($this->adminpagename, __('Designer Center', 'dzsvg'), __('Designer Center', 'dzsvg'), $this->admin_capability, 'zsvg-dc', array($this, 'admin_page_dc'));
            $zsvg_subpage = add_submenu_page($this->adminpagename, __('Video Gallery Settings', 'dzsvg'), __('Settings', 'dzsvg'), $this->admin_capability, 'zsvg-mo', array($this, 'admin_page_mainoptions'));
        
        }
        //echo $zsvg_page;
    }

    function admin_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('tiny_mce');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('zsvg_admin', $this->thepath . "admin/admin.js");
        wp_enqueue_style('zsvg_admin', $this->thepath . 'admin/admin.css');
        wp_enqueue_script('dzs.farbtastic', $this->thepath . "admin/colorpicker/farbtastic.js");
        wp_enqueue_style('dzs.farbtastic', $this->thepath . 'admin/colorpicker/farbtastic.css');
        wp_enqueue_style('zsvgdzsuploader', $this->thepath . 'admin/dzsuploader/upload.css');
        wp_enqueue_script('zsvgdzsuploader', $this->thepath . 'admin/dzsuploader/upload.js');
        wp_enqueue_style('dzs.scroller', $this->thepath . 'dzsscroller/scroller.css');
        wp_enqueue_script('dzs.scroller', $this->thepath . 'dzsscroller/scroller.js');
        wp_enqueue_style('dzs.dzstoggle', $this->thepath . 'dzstoggle/dzstoggle.css');
        wp_enqueue_script('dzs.dzstoggle', $this->thepath . 'dzstoggle/dzstoggle.js');
        wp_enqueue_style('dzs.zoombox', $this->thepath . 'zoombox/zoombox.css');
        wp_enqueue_script('dzs.zoombox', $this->thepath . 'zoombox/zoombox.js');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
    }

    function front_scripts() {
        //print_r($this->mainoptions);
        $videogalleryscripts = array('jquery');
        wp_enqueue_script('dzs.vplayer', $this->thepath . "videogallery/vplayer.js");
        wp_enqueue_script('dzs.flashhtml5main', $this->thepath . "videogallery/flashhtml5main.js");
        wp_enqueue_style('dzs.vgallery.skin2', $this->thepath . 'videogallery/skin_white.css');
        wp_enqueue_style('dzs.vgallery.skin.custom', $this->thepath . 'videogallery/skin_custom.css');
        wp_enqueue_style('dzs.vplayer', $this->thepath . 'videogallery/vplayer.css');


        if ($this->mainoptions['embed_prettyphoto'] == 'on') {
            wp_enqueue_script('jquery.prettyphoto', $this->thepath . "prettyphoto/jquery.prettyPhoto.js");
            wp_enqueue_style('jquery.prettyphoto', $this->thepath . 'prettyphoto/prettyPhoto.css');
        }
        //if($this->mainoptions['embed_masonry']=='on'){
        //wp_enqueue_script('jquery.masonry', $this->thepath . "masonry/jquery.masonry.min.js");
        //}
    }


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
    if (isset($data[$pname])){
        $val = $data[$pname];
    }
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
    if (isset($data[$pname])){
        $val = $data[$pname];
    }
    
    $fout.='
<div class="setting"><input type="text" class="textinput short with_colorpicker" name="' . $pname . '" value="' . $val . '">
<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
</div>';
    echo $fout;
}
    function admin_page_dc() {
        $dc_config = array(
            'ispreview' => 'off'
        );
        
        echo 'ceva';
$dbget = file_get_contents(dirname(__FILE__) . "/deploy/designer/db/db.txt");
$dbvals = unserialize($dbget);
        ?>
<div class="wrap">
    <h1><?php echo __('Video Gallery Designer Center', 'dzsvg'); ?></h1>
                <?php if($dc_config['ispreview']=='on'){ ?>
                <div class="comment"><?php echo __('Hello and welcome to DZS Video / YouTube / Vimeo Gallery Designer Center. As this is only a preview, it will not save the changes in the primary database, but it will create temp files so you can preview the full power of this 
                    tool ( click <strong>Preview</strong> from the right ). You may notice that you would not find here all the options that you may need for fully customising the gallery. That is because here are only the options that are stricly related to the controls
                 of the gallery. The others like menu position, video list etc. are found in the main xml file ( gallery.xml ) you can find a full list of those options at the bottom.', 'dzsvg'); ?>
                </div>
                <?php } ?>
                <hr>
                <div class="type-selector">
                    <div class="big-button active"><?php echo __('HTML5 Gallery Design', 'dzsvg'); ?></div>
                    <div class="big-button"><?php echo __('Flash Gallery Design', 'dzsvg'); ?></div>
                    <div class="clear"></div>
                </div>
                <hr>
                <div class="settings-html5vg">
                    <div class="settings_block">
                        <h2><?php echo __('HTML5 Gallery Settings', 'dzsvg'); ?></h2>
                        
                        <div class="toggle">
                            <div class="toggle-title"><h3><?php echo __('Gallery Thumbs Design', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">
                                
                                <h5><?php echo __('Background', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_color_thumbs_bg';
                                $val = '#111111';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_cp_field($sname, array('val'=>$val)) 
                                        ?>
                                <h5><?php echo __('Active Background', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_color_thumbs_bg_hover';
                                $val = '#111111';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_cp_field($sname, array('val'=>$val)) 
                                        ?>
                                <h5><?php echo __('Border Color', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_color_thumbs_borderc';
                                $val = '#111111';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_cp_field($sname, array('val'=>$val)) 
                                        ?>
                                <h5><?php echo __('Thumb Text Title Color', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_color_thumbs_titlec';
                                $val = '#ffffff';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_cp_field($sname, array('val'=>$val)) 
                                        ?>
                                <h5><?php echo __('Thumb Pic Width', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_color_thumbs_w';
                                $val = '50';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_simple_field($sname, array('val'=>$val)) 
                                        ?>
                                <h5><?php echo __('Thumb Pic Height', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_color_thumbs_h';
                                $val = '50';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_simple_field($sname, array('val'=>$val)) 
                                        ?>


                            </div>
                        </div>



                        <div class="toggle">
                            <div class="toggle-title"><h3><?php echo __('General Player Colors', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5><?php echo __('Main Color', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('html5_color_player_main', array('val'=>'#7a7a7a')) ?>
                                <?php $sname = 'html5_color_player_main_hover';
                                $val = '#111111';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_cp_field($sname, array('val'=>$val)) 
                                        ?>
                                <h5><?php echo __('Hover Color', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_color_player_main_hover';
                                $val = '#111111';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_cp_field($sname, array('val'=>$val)) 
                                        ?>
                                <h5><?php echo __('Highlight Color', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_color_player_highlight';
                                $val = '#ff0000';
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_cp_field($sname, array('val'=>$val)) 
                                        ?>

                            </div>
                        </div>


                        <div class="toggle">
                            <div class="toggle-title"><h3><?php echo __('Other Options', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">
                                <div class="settings">
                                <h5><?php echo __('Logo Position Left', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_logo_posx';
                                $val = -50;
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_simple_field($sname, array('val'=>$val)) 
                                        ?>
                                <div class="sidenote"><?php echo __('Use a negative value for right position - for example <strong>-60</strong> means 60 pixels from the right', 'dzsvg'); ?></div>
                                </div>
                                <div class="settings">
                                <h5><?php echo __('Logo Position Top', 'dzsvg'); ?></h5>
                                <?php $sname = 'html5_logo_posy';
                                $val = 50;
                                if(isset($dbvals[$sname])){
                                    $val = $dbvals[$sname];
                                }
                                $this->add_simple_field($sname, array('val'=>$val)) 
                                        ?>
                                <div class="sidenote"><?php echo __('Use a negative value for right position - for example <strong>-60</strong> means 60 pixels from the right', 'dzsvg'); ?></div>
                                </div>


                            </div>
                        </div>



                    </div>
                    <div class="preview_block">
                        <div>
                            <h2><?php echo __('Preview', 'dzsvg'); ?></h2>
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
                    <div class="clear"></div>
                        <div class="sidenote">
                            <?php echo __('Other design options can be found in the main admin under Html5 Gallery Options', 'dzsvg'); ?><br/>
                            <img src="<?php echo $this->thepath; ?>admin/img/design_main.png"/>
                        </div>
            </div>
                <div class="settings-flash" style="display:none;">
                    <div class="settings_block">
                        <h2><?php echo __('Flash Gallery Settings', 'dzsvg'); ?></h2>
                        <div class="toggle">
                            <div class="toggle-title"><h3><?php echo __('Thumb Settings', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5><?php echo __('Width', 'dzsvg'); ?></h5>
                                <div class="setting"><input type="text" class="textinput short" name="thumbs_width" value="<?php echo $data['thumbs_width'] ?>"/></div>
                                <h5><?php echo __('Height', 'dzsvg'); ?></h5>
                                <div class="setting"><input type="text" class="textinput short" name="thumbs_height" value="<?php echo $data['thumbs_height'] ?>"/></div>
                                <h5><?php echo __('Spacing', 'dzsvg'); ?></h5>
                                <div class="setting"><input type="text" class="textinput short" name="thumbs_space" value="<?php echo $data['thumbs_space'] ?>"/></div>
                                <h5><?php echo __('Background', 'dzsvg'); ?></h5>
                                <div class="setting"><input type="text" class="textinput short" name="thumbs_bg" value="<?php echo $data['thumbs_bg'] ?>"/>
                                    </div>
                                <h5><?php echo __('Border Width', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_borderw') ?>
                                <h5><?php echo __('Border Color', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('thumbs_borderc') ?>
                                <h5><?php echo __('Thumb Pic Width', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_pic_w') ?>
                                <h5><?php echo __('Thumb Pic Height', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_pic_h') ?>
                                <h5><?php echo __('Thumb Pic X', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_pic_x') ?>
                                <h5><?php echo __('Thumb Pic Y', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_pic_y') ?>
                                <h5><?php echo __('Thumb Text Width', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_text_w') ?>
                                <h5><?php echo __('Thumb Text Height', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_text_h') ?>
                                <h5><?php echo __('Thumb Text Title Color', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('thumbs_text_title_c') ?>
                                <h5><?php echo __('Thumb Text X', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_text_x') ?>
                                <h5><?php echo __('Thumb Text Y', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('thumbs_text_y') ?>

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
                            <div class="toggle-title"><h3><?php echo __('Play / Stop Buttons', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5><?php echo __('Position X', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('pp_x') ?>
                                <h5><?php echo __('Position Y', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('pp_y') ?>
                                <h5><?php echo __('Background', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('pp_bg') ?>

                            </div>
                        </div>



                        <div class="toggle">
                            <div class="toggle-title"><h3><?php echo __('Scrub Bar', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5><?php echo __('Position', 'dzsvg'); ?> X</h5>
                                <?php $this->add_simple_field('scr_x') ?>
                                <h5><?php echo __('Position', 'dzsvg'); ?> Y</h5>
                                <?php $this->add_simple_field('scr_y') ?>
                                <h5><?php echo __('Width', 'dzsvg'); ?></h5>
                                <div class="sidenote"><?php echo __('Best to use a negative value. For example -200 means video width - 200.', 'dzsvg'); ?></div>
                                <?php $this->add_simple_field('scr_w') ?>
                                <h5><?php echo __('Height', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('scr_h') ?>
                                <h5><?php echo __('Background Static', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('scr_bg') ?>
                                <h5><?php echo __('Background Loaded', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('scrl_bg') ?>
                                <h5><?php echo __('Background Progress', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('scrp_bg') ?>

                            </div>
                        </div>


                        <div class="toggle">
                            <div class="toggle-title"><h3><?php echo __('Volume Button', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5><?php echo __('Position X', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('vol_x') ?>
                                <h5><?php echo __('Position Y', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('vol_y') ?>
                                <h5><?php echo __('Background', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('vol_bg') ?>

                            </div>
                        </div>


                        <div class="toggle">
                            <div class="toggle-title"><h3><?php echo __('Fullscreen Button', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">

                                <h5><?php echo __('Position X', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('full_x') ?>
                                <h5><?php echo __('Position Y', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('full_y') ?>
                                <h5><?php echo __('Background', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('full_bg') ?>

                            </div>
                        </div>


                        <div class="toggle">
                            <div class="toggle-title"><h3><?php echo __('General Settings', 'dzsvg'); ?></h3><div class="arrow-down"></div></div>

                            <div class="toggle-content" style="display:none">
                                <h5><?php echo __('Player Background', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('settings_bg') ?>
                                <h5><?php echo __('Controls Background', 'dzsvg'); ?></h5>
                                <?php $this->add_cp_field('settings_controls_bg') ?>

                                <h5><?php echo __('Controls Background Height', 'dzsvg'); ?></h5>
                                <?php $this->add_simple_field('settings_controls_bg_h') ?>
                                <h5><?php echo __('Video Does Not Overlay Controls?', 'dzsvg'); ?></h5>
                                <?php $this->add_cb_field('settings_does_not_overlay_controls') ?>
                                <h5><?php echo __('Disable Video Description?', 'dzsvg'); ?></h5>
                                <?php $this->add_cb_field('settings_disable_description') ?>
                                <h5><?php echo __('Fade Controls On Mouse Leave ?', 'dzsvg'); ?></h5>
                                <?php $this->add_cb_field('settings_fade_on_leave') ?>

                            </div>
                        </div>







                    </div>
                    <div class="preview_block">
                        <div>
                            <h2><?php echo __('Preview', 'dzsvg'); ?></h2>
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
                        <a class="preview-button primary-button" href="#"><?php echo __('Preview', 'dzsvg'); ?></a>
                            <h2><?php echo __('Premade Examples', 'dzsvg'); ?></h2>
                            <img src="<?php echo $this->thepath; ?>deploy/designer/img/example1.png" id="example1" class="example"/>
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
                ?> primary-button" href="#"><?php echo __('Save', 'dzsvg'); ?></a><div class="preloader"></div>
                <div class="clear"></div><br/>
    <?php    
    }
    function misc_input_text($argname, $argopts){
        $fout = '';
        $fout.='<input type="text"';
        $fout.=' name="'.$argname.'"';
        if(isset($argopts['seekval'])){
                $fout.=' value="'.$argopts['seekval'].'"';
            }
        
        $fout.='/>';
        return $fout;
    }
    function misc_input_checkbox($argname, $argopts){
        $fout = '';
        $auxtype = 'checkbox';
        
        if(isset($argopts['type'])){
            if($argopts['type']=='radio'){
                $auxtype = 'radio';
            }
        }
        $fout.='<input type="'.$auxtype.'"';
        $fout.=' name="'.$argname.'"';
        if(isset($argopts['class'])){
            $fout.=' class="'.$argopts['class'].'"';
        }
        $theval = 'on';
        if(isset($argopts['val'])){
            $fout.=' value="'.$argopts['val'].'"';
            $theval = $argopts['val'];
        }else{
            $fout.=' value="on"';
        }
        //print_r($this->mainoptions); print_r($argopts['seekval']);
        if(isset($argopts['seekval'])){
            $auxsw=false;
            if(is_array($argopts['seekval'])){
                //echo 'ceva'; print_r($argopts['seekval']);
                foreach($argopts['seekval'] as $opt){
                    //echo 'ceva'; echo $opt; echo 
                    if($opt == $argopts['val'] ){
                        $auxsw=true;
                    }
                }
            }else{
                if($argopts['seekval']==$theval){
                    //echo $argval;
                    $auxsw=true;
                }
            }
            if($auxsw==true){
                $fout.=' checked="checked"';
            }
        }
        $fout.='/>';
        return $fout;
    }
    function admin_page_mainoptions(){
        //print_r($this->mainoptions);
        ?>
        
        <div class="wrap">
                <h2><?php echo __('Video Gallery Main Settings','dzsvg'); ?></h2>
                <br/>
            <form class="mainsettings">
                
                <h3>Admin Options</h3>
                <div class="setting">
                    <div class="label"><?php echo __('use wordpres uploader','dzsvg'); ?></div>
                    <?php echo $this->misc_input_checkbox('usewordpressuploader', array('val'=>'on', 'seekval' => $this->mainoptions['usewordpressuploader'])); ?>
                </div>
                
                <div class="setting">
                    <div class="label"><?php echo __('Use External wp-content Upload Directory ?','dzsvg'); ?></div>
                    <?php echo $this->misc_input_checkbox('use_external_uploaddir', array('val'=>'on', 'seekval' => $this->mainoptions['use_external_uploaddir'])); ?>
                <div class="sidenote"><?php echo __('use an outside directory for uploading files','dzsvg'); ?></div>
               </div>
                
                <div class="setting">
                    <div class="label"><?php echo __('Always Embed Scripts?','dzsvg'); ?></div>
                    <?php echo $this->misc_input_checkbox('always_embed', array('val'=>'on', 'seekval' => $this->mainoptions['always_embed'])); ?>
                    <div class="sidenote"><?php echo __('by default scripts and styles from this gallery are included only when needed for optimizations reasons, but you can choose to always use them ( useful for when you are using a ajax theme that does not reload the whole page on url change )','dzsvg'); ?></div>
                </div>
                
                <div class="setting">
                    <div class="label"><?php echo __('Fast binding?','dzsvg'); ?></div>
                    <?php echo $this->misc_input_checkbox('is_safebinding', array('val'=>'off', 'seekval' => $this->mainoptions['is_safebinding'])); ?>
                    <div class="sidenote"><?php echo __('the galleries admin can use a complex ajax backend to ensure fast editing, but this can cause limitation issues on php servers. Turn this to on if you want a faster editing experience ( and if you have less then 20 videos accross galleries ) ','dzsvg'); ?></div>
                </div>
                <h3>Vimeo Options</h3>
                <div class="setting">
                    <div class="label"><?php echo __('Vimeo Player Byline','dzsvg'); ?></div>
                    <?php echo $this->misc_input_text('vimeo_byline', array('val'=>'0', 'seekval' => $this->mainoptions['vimeo_byline'])); ?>
                    <div class="sidenote"><?php echo __('','dzsvg'); ?></div>
                </div>
                <div class="setting">
                    <div class="label"><?php echo __('Vimeo Player Portrait','dzsvg'); ?></div>
                    <?php echo $this->misc_input_text('vimeo_portrait', array('val'=>'0', 'seekval' => $this->mainoptions['vimeo_portrait'])); ?>
                    <div class="sidenote"><?php echo __('','dzsvg'); ?></div>
                </div>
                <div class="setting">
                    <div class="label"><?php echo __('Vimeo Player Color','dzsvg'); ?></div>
                    <?php echo $this->misc_input_text('vimeo_color', array('val'=>'', 'seekval' => $this->mainoptions['vimeo_color'])); ?>
                    <div class="sidenote"><?php echo __('','dzsvg'); ?></div>
                </div>
                <br/>
                <a href='#' class="button-primary save-btn save-mainoptions"><?php echo __('Save Options','dzsvg'); ?></a>
            </form>
        <div class="saveconfirmer" style=""><img alt="" style="" id="save-ajax-loading2" src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif"/></div>
        <script>
            jQuery(document).ready(function($){
                sliders_ready();
				$('input:checkbox').checkbox();
            })
            </script>
        </div>
              <div class="clear"></div><br/>
    <?php
    }
    function admin_page() {
        ?>
        <div class="wrap">
            <div class="import-export-db-con">
                <div class="the-toggle"></div>
                <div class="the-content-mask" style="">

                    <div class="the-content">
                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="one_half">
                                <h3>Import Database</h3>
                                <input name="zsvg_importdbupload" type="file" size="10"/><br />
                            </div>
                            <div class="one_half last alignright">
                                <input class="button-secondary" type="submit" name="zsvg_importdb" value="Import" />
                            </div>
                            <div class="clear"></div>
                        </form>


                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="one_half">
                                <h3>Import Slider</h3>
                                <input name="importsliderupload" type="file" size="10"/><br />
                            </div>
                            <div class="one_half last alignright">
                                <input class="button-secondary" type="submit" name="zsvg_importslider" value="Import" />
                            </div>
                            <div class="clear"></div>
                        </form>

                        <div class="one_half">
                            <h3>Export Database</h3>
                        </div>
                        <div class="one_half last alignright">
                            <form action="" method="POST"><input class="button-secondary" type="submit" name="zsvg_exportdb" value="Export"/></form>
                        </div>
                        <div class="clear"></div>

                    </div>
                </div>
            </div>
            <h2>DZS <?php _e('Video Gallery Admin', 'dzsvg'); ?> <img alt="" style="visibility: visible;" id="main-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/></h2>
            <noscript><?php _e('You need javascript for this.', 'dzsvg'); ?></noscript>
            <div class="top-buttons">
                <a href="<?php echo $this->thepath; ?>readme/index.html" class="button-secondary action"><?php _e('Documentation', 'dzsvg'); ?></a>
                <a href="<?php echo $this->thepath; ?>deploy/designer/index.php" target="_blank" class="button-secondary action"><?php _e('Go to Designer Center', 'dzsvg'); ?></a>
                <div class="super-select db-select dzsvg"><button class="button-secondary btn-show-dbs">Current Database - <span class="strong currdb"><?php
if($this->currDb==''){
    echo 'main';
}else{
    echo $this->currDb;
}
                ?></span></button>
                    <select class="main-select hidden"><?php
                    //print_r($this->dbs);
                    
                    if(is_array($this->dbs)){
                        foreach($this->dbs as $adb){
                        $params = array( 'dbname' => $adb );
                        $newurl = add_query_arg( $params, dzs_curr_url() );
                            echo '<option' . ' data-newurl="' . $newurl . '"' . '>' . $adb . '</option>';
                        }
                    }else{
                        $params = array( 'dbname' => 'main' );
                        $newurl = add_query_arg( $params, dzs_curr_url() );
                        echo '<option' . ' data-newurl="' . $newurl . '"' . ' selected="selected"' . '>' . $adb . '</option>';
                    }
                    ?></select><div class="hidden replaceurlhelper"><?php
                        $params = array( 'dbname' => 'replaceurlhere' );
                        $newurl = add_query_arg( $params, dzs_curr_url() );
                        echo $newurl;
                    ?></div>
                </div>
            </div>
            <table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
                <thead> 
                    <tr> 
                        <th style="" class="manage-column column-name" id="name" scope="col"><?php _e('ID', 'dzsvg'); ?></th>
                        <th class="column-edit">Edit</th>
                        <th class="column-edit">Embed</th>
                        <th class="column-edit">Export</th>
                        <?php 
                if($this->mainoptions['is_safebinding']!='on'){
                    ?>
                        <th class="column-edit">Duplicate</th> 
                        <?php 
                }
                    ?>
                        <th class="column-edit">Delete</th> 
                    </tr> 
                </thead> 
                <tbody>
                </tbody>
            </table>
            <?php
            $url_add = '';
            $url_add = '';
        $items = $this->mainitems;
            //echo count($items);
        
        $aux = remove_query_arg( 'deleteslider', dzs_curr_url() );
        $params = array( 'currslider' => count($items), 'addslider'=>'on' );
        $url_add = add_query_arg( $params, $aux );
            
            ?>
            <a class="button-secondary add-slider" href="<?php echo $url_add; ?>"><?php _e('Add Slider', 'dzsvg'); ?></a>
            <form class="master-settings">
            </form>
            <div class="block">
                <div class="extra-options">
                    <h3><?php _e('Import', 'dzsvg'); ?></h3>
                    <!-- demo/ playlist: ADC18FE37410D250, user: digitalzoomstudio, vimeo: 5137664 -->
                    <input type="text" name="import_inputtext" id="import_inputtext" value="digitalzoomstudio"/>
                    <div class="sidenote"><?php _e('Import here feed from a YT Playlist, YT User Channel or Vimeo User Channel - you just have to enter the 
                        id of the playlist / user id in the box below and select the correct type from below', 'dzsvg') . '. Remember to set the <strong>Feed From</strong> field to <strong>Normal</strong> after your videos have been imported.'; ?></div>
                    <a href="#" id="importytplaylist" class="button-secondary">YouTube Playlist</a>
                    <a href="#" id="importytuser" class="button-secondary">YouTube User Channel</a>
                    <a href="#" id="importvimeouser" class="button-secondary">Vimeo User Channel</a>
                    <br/>
                    <span class="import-error" style="display:none;"></span>
                </div>
            </div>
<div class="dzs-multi-upload">
<h3>Choose file(s)</h3>
<div>
	<input id="files-upload" class="multi-uploader" name="file_field" type="file" multiple>
</div>
<div class="droparea">
	<div class="instructions">drag & drop files here</div>
</div>
<div class="upload-list-title">The Preupload List</div>
<ul class="upload-list">
	<li class="dummy">add files here from the button or drag them above</li>
</ul>
<button class="primary-button upload-button">Upload All</button>
</div>
            <div class="notes">
                <div class="curl">Curl: <?php echo function_exists('curl_version') ? 'Enabled' : 'Disabled' . '<br />'; ?>
                </div>
                <div class="fgc">File Get Contents: <?php echo ini_get('allow_url_fopen') ? "Enabled" : "Disabled"; ?>
                </div>
                <div class="sidenote"><?php _e('If neither of these are enabled, only normal feed will work. 
                    Contact your host provider on how to enable these services to use the YouTube User Channel 
                    or YouTube Playlist feed.', 'dzsvg'); ?>
                </div>
            </div>
            <div class="saveconfirmer"><?php _e('Loading...', 'dzsvg'); ?></div>
            <a href="#" class="button-primary master-save"></a> <img alt="" style="position:fixed; bottom:18px; right:125px; visibility: hidden;" id="save-ajax-loading" src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

    <a href="#" class="button-primary master-save"><?php _e('Save All Galleries', 'dzsvg'); ?></a>
    <a href="#" class="button-secondary slider-save"><?php _e('Save Gallery', 'dzsvg'); ?></a>
        </div>
        <script>
        <?php
//$jsnewline = '\\' + "\n";
        if(isset($this->mainoptions['use_external_uploaddir']) && $this->mainoptions['use_external_uploaddir']=='on'){
        echo "window.dzs_upload_path = '" . site_url('wp-content') . "/upload/';
";
        echo "window.dzs_phpfile_path = '" . site_url('wp-content') . "/upload.php';
";
            
        }else{
        echo "window.dzs_upload_path = '" . $this->thepath . "admin/upload/';
";
        echo "window.dzs_phpfile_path = '" . $this->thepath . "admin/upload.php';
";
        }
        $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->sliderstructure);
        echo "var sliderstructure = '" . $aux . "';
";
        $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->itemstructure);
        echo "var itemstructure = '" . $aux . "';
";
        ?>
            jQuery(document).ready(function($){
                sliders_ready();
                if(jQuery.fn.multiUploader){
                jQuery('.dzs-multi-upload').multiUploader();
                }
        <?php
        $items = $this->mainitems;
        for ($i = 0; $i < count($items); $i++) {
            //print_r($items[$i]);
            $aux = '';
            if(isset($items[$i]) && isset($items[$i]['settings']) && isset($items[$i]['settings']['id'])){
                //echo $items[$i]['settings']['id'];
                $aux = '{ name: "'.$items[$i]['settings']['id'].'"}';
            }
            echo "sliders_addslider(".$aux.");";
        }
        if (count($items) > 0)
            echo 'sliders_showslider(0);';
        for ($i = 0; $i < count($items); $i++) {
            //echo $i . $this->currSlider . 'cevava';
            if(($this->mainoptions['is_safebinding']!='on' || $i==$this->currSlider) && is_array($items[$i])){
                
                //==== jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery ) 
                $jsi = $i;
                if($this->mainoptions['is_safebinding']=='on'){
                    $jsi = 0;
                }
                
                for ($j = 0; $j < count($items[$i]) - 1; $j++) {
                    echo "sliders_additem(" . $jsi . ");";
                }
                
                foreach ($items[$i] as $label => $value) {
                    if ($label === 'settings') {
                        if(is_array($items[$i][$label])){
                            foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                $subvalue = (string)$subvalue;
                                $subvalue = stripslashes($subvalue);
                                $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
                                $subvalue = str_replace(array("'"), '"', $subvalue);
                                echo 'sliders_change(' . $jsi . ', "settings", "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                            }
                        }
                    } else {

                        if(is_array($items[$i][$label])){
                        foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                $subvalue = (string)$subvalue;
                            $subvalue = stripslashes($subvalue);
                            $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
                            $subvalue = str_replace(array("'"), '"', $subvalue);
                            if ($label == '') {
                                $label = '0';
                            }
                            echo 'sliders_change(' . $jsi . ', ' . $label . ', "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                        }
                        }
                    }
                }
                if($this->mainoptions['is_safebinding']=='on'){
                    break;
                }
            }
        }
        ?>
                jQuery('#main-ajax-loading').css('visibility', 'hidden');
                if(zsvg_settings.is_safebinding=="on"){
                    jQuery('.master-save').remove();
                    if(zsvg_settings.addslider=="on"){
                    //console.log(zsvg_settings.addslider)
                        sliders_addslider();
                        window.currSlider_nr=-1
                        sliders_showslider(0);
                    }
                    jQuery('.slider-in-table').each(function(){
                        jQuery(this).children('.button_view').eq(3).remove();
                    });
                }
            check_global_items();
            });     
        </script>
        <?php
    }

    function post_options() {
        //// POST OPTIONS ///

        if (isset($_POST['zsvg_exportdb'])) {
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . "zsvg_backup.txt" . '"');
            echo serialize($this->mainitems);
            die();
        }

        if (isset($_POST['zsvg_exportslider'])) {
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . "zsvg-slider-" . $_POST['slidername'] . ".txt" . '"');
            //print_r($_POST);
            echo serialize($this->mainitems[$_POST['slidernr']]);
            die();
        }


        if (isset($_POST['zsvg_importdb'])) {
            //print_r( $_FILES);
            $file_data = file_get_contents($_FILES['zsvg_importdbupload']['tmp_name']);
            $this->mainitems = unserialize($file_data);
            update_option($this->dbitemsname, $this->mainitems);
        }

        if (isset($_POST['zsvg_importslider'])) {
            //print_r( $_FILES);
            $file_data = file_get_contents($_FILES['importsliderupload']['tmp_name']);
            $auxslider = unserialize($file_data);
            //replace_in_matrix('http://localhost/wpmu/eos/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //replace_in_matrix('http://eos.digitalzoomstudio.net/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //echo 'ceva';
            //print_r($auxslider);
            $this->mainitems = get_option($this->dbitemsname);
            //print_r($this->mainitems);
            $this->mainitems[] = $auxslider;

            update_option($this->dbitemsname, $this->mainitems);
        }

        if (isset($_POST['zsvg_saveoptions'])) {
            $this->mainoptions['usewordpressuploader'] = $_POST['usewordpressuploader'];
            $this->mainoptions['embed_prettyphoto'] = $_POST['embed_prettyphoto'];
            $this->mainoptions['use_external_uploaddir'] = $_POST['use_external_uploaddir'];
            
            if($_POST['use_external_uploaddir']=='on'){
                copy(dirname(__FILE__).'/admin/upload.php', dirname(dirname(dirname(__FILE__))).'/upload.php');
                $mypath = dirname(dirname(dirname(__FILE__))) . '/upload';
                if(is_dir($mypath) === false && file_exists($mypath) === false){
                mkdir($mypath,0777);
                }
            }
            
            
            //$this->mainoptions['embed_masonry'] = $_POST['embed_masonry'];
            update_option($this->dboptionsname, $this->mainoptions);
        }
    }

    function post_save_mo() {
        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);
        print_r($auxarray);
        
            if($auxarray['use_external_uploaddir']=='on'){
                
                $path_uploadfile = dirname(dirname(dirname(__FILE__))).'/upload.php';
                if(file_exists($path_uploadfile) === false){
                copy(dirname(__FILE__).'/admin/upload.php', $path_uploadfile);
                }
                $path_uploaddir = dirname(dirname(dirname(__FILE__))) . '/upload'; 
                if(is_dir($path_uploaddir) === false){
                    mkdir($path_uploaddir,0777);
                }
            }
        
            update_option($this->dboptionsname, $auxarray);
        die();
    }
    function post_save() {
        //---this is the main save function which saves item
        $auxarray = array();
        $mainarray = array();
        
        //print_r($this->mainitems);
        
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);
        
        
        if (isset($_POST['currdb'])) {
            $this->currDb = $_POST['currdb'];
        }
        //echo 'ceva'; print_r($this->dbs);
        if($this->currDb!='main' && $this->currDb!=''){
            $this->dbitemsname.='-'.$this->currDb;
        }
        //echo $this->dbitemsname;
        if(isset($_POST['sliderid'])){
            //print_r($auxarray);
            $mainarray= get_option($this->dbitemsname);
            foreach($auxarray as $label => $value){
                $aux = explode('-', $label);
                $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
            }
            $mainarray[$_POST['sliderid']] = $tempmainarray;
        }else{
            foreach ($auxarray as $label => $value) {
                //echo $auxarray[$label];
                $aux = explode('-', $label);
                $mainarray[$aux[0]][$aux[1]][$aux[2]] = $auxarray[$label];
            }
        }
        //echo $this->dbitemsname; print_r($_POST); print_r($this->currDb); echo isset($_POST['currdb']);
        update_option($this->dbitemsname, $mainarray);
        echo 'success';
        die();
    }

    function post_importytplaylist() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        //echo $aux;
        $yf_maxi = 100;
        $i = 0;
        $its = array();

        $target_file = $this->httpprotocol . "://gdata.youtube.com/feeds/api/playlists/" . $pd . "?alt=json&start-index=1&max-results=40";
        $ida = dzs_get_contents($target_file);
        $idar = json_decode($ida);
        //print_r($idar);
        if ($idar == false) {
            echo 'error: ' . 'check the id';
        } else {
            foreach ($idar->feed->entry as $ytitem) {
                $cache = $ytitem;
                $aux = array();
                $auxtitle;
                $auxcontent;
                //print_r($cache);
                //print_r(get_object_vars($cache->title));
                foreach ($cache->title as $hmm) {
                    $auxtitle = $hmm;
                    break;
                }
                foreach ($cache->content as $hmm) {
                    $auxcontent = $hmm;
                    break;
                }
                //print_r($aux2);
                //print_r(parse_str($cache->title));
                parse_str($ytitem->link[0]->href, $aux);
                //print_r($aux);

                $its[$i]['source'] = $aux[$this->httpprotocol . '://www_youtube_com/watch?v'];
                $its[$i]['thethumb'] = "";
                $its[$i]['type'] = "youtube";
                $its[$i]['title'] = $auxtitle;
                $its[$i]['menuDescription'] = $auxcontent;
                $its[$i]['description'] = $auxcontent;

                //print_r($ytitem);
                $aux2 = get_object_vars($ytitem->title);
                $aux = ($aux2['$t']);
                $lb = array("\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);

                /*
                  $aux = $ytitem->description;
                  $lb   = array("\r\n", "\n", "\r", "&" ,"-", "`", '???', "'", '-');
                  $aux = str_replace($lb, ' ', $aux);
                  $its['settings']['description'] = $aux;
                 */
                $i++;
                if ($i > $yf_maxi)
                    break;
            }
        }

        if (count($its) == 0) {
            echo 'error: ' . '<a href="'.$target_file.'">this</a> is what the feed returned ' . $ida;
            die();
        }
        for ($i = 0; $i < count($its); $i++) {
            
        }
        $sits = json_encode($its);
        echo $sits;



        die();
    }

    function post_importytuser() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        $yf_maxi = 100;
        $i = 0;
        $its = array();
        //echo $aux;
        //echo 'ceva';


        $sw = false;
        //print_r($idar);
        //print_r($idar);
        //print_r(count($idar->data->items));
        $i = 0;
        $yf_maxi = 100;

        //echo $ida;



        $target_file = $this->httpprotocol . "://gdata.youtube.com/feeds/api/users/" . $pd . "/uploads?v=2&alt=jsonc";
        $ida = dzs_get_contents($target_file);
        $idar = json_decode($ida);

        if ($ida == 'yt:quotatoo_many_recent_calls') {
            echo 'error: too many recent calls - YouTube rejected the call';
            $sw = true;
        }
        //print_r($idar);

        if ($idar == false) {
            echo 'error: ' . 'check the id ';
            print_r($ida);
            die();
        } else {

            foreach ($idar->data->items as $ytitem) {
                //print_r($ytitem);
                $its[$i]['source'] = $ytitem->id;
                $its[$i]['thethumb'] = "";
                $its[$i]['type'] = "youtube";

                $aux = $ytitem->title;
                $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);
                $its[$i]['title'] = $aux;

                $aux = $ytitem->description;
                $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);
                $its[$i]['description'] = $aux;

                $i++;
                if ($i > $yf_maxi + 1)
                    break;
            }
        }
        if (count($its) == 0) {
            echo 'error: ' . 'this is what the feed returned ' . $ida;
            die();
        }
        $sits = json_encode($its);
        echo $sits;



        die();
    }

    function post_importvimeouser() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        $yf_maxi = 100;
        $i = 0;
        $its = array();
        //echo $aux;
        $target_file = "http://vimeo.com/api/v2/" . $pd . "/videos.json";
        $ida = dzs_get_contents($target_file);
        $idar = json_decode($ida);
        $i = 0;
        if ($idar == false) {
            echo 'error: ' . 'check the id ';
            print_r($ida);
            die();
        } else {
            foreach ($idar as $item) {
                $its[$i]['source'] = $item->id;
                $its[$i]['thethumb'] = $item->thumbnail_small;
                //if ($its['settings']['directurlaccess'] == 'on') {
                //    $its[$i]['thethumb'] = '';
                //}
                $its[$i]['type'] = "vimeo";

                $aux = $item->title;
                $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);
                $its[$i]['title'] = $aux;

                $aux = $item->description;
                $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);
                $its[$i]['description'] = $aux;
                $i++;
            }
        }
        if (count($its) == 0) {
            echo 'error: ' . 'this is what the feed returned ' . $ida;
            die();
        }

        $sits = json_encode($its);
        echo $sits;


        die();
    }

}


require_once('widget.php');
if ($zsvg->pluginmode != 'theme') {
    require_once('dzs_functions.php');
}