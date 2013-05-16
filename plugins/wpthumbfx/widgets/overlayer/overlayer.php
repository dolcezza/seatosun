<?php
/*
Class: OverlayerAtomicPressHelper
Overlayer helper class
*/
class OverlayerAtomicPressHelper extends AtomicPressHelper {
    /* type */
    public $type;
    /* options */
    public $options;
    /*
    Function: Constructor
    Class Constructor.
    */
    public function __construct($atomicpress) {
        parent::__construct($atomicpress);
        // init vars
        $this->type    = strtolower(str_replace('AtomicPressHelper', '', get_class($this)));
        $this->options = $this['system']->options;
        // register path
        $this['path']->register(dirname(__FILE__), $this->type);
    }
    /*
    Function: site
    Site init actions
    
    Returns:
    Void
    */
    public function site() {
        $options = array();
        // get options
        foreach (array(
            'duration' => 300
        ) as $option => $value) {
            $val              = $this->options->get('overlayer_' . $option, $value);
            $options[$option] = is_numeric($val) ? (float) $val : $val;
        }
        // is enabled ?
        if ($this->options->get('overlayer_enable', 1)) {
            $pluginPath = $this["path"]->url('overlayer:');
            $selector   = $this->options->get('overlayer_selector', '[data-overlayer]');
            if ($selector != '[data-overlayer]')
                $selector .= ',[data-overlayer]';
            $options = json_encode($options);
            // add stylesheets/javascripts
            $this['asset']->addFile('css', 'overlayer:css/overlayer.css');
            $this['asset']->addFile('js', 'overlayer:js/overlayer.js');
            $this['asset']->addString('js', "jQuery(function($){ $('{$selector}').overlayer({$options}) });");
        }
    }
    /*
    Function: dashboard
    Render dashboard layout
    
    Returns:
    Void
    */
    public function dashboard() {
        // get xml
        $xml = simplexml_load_file($this['path']->path('overlayer:overlayer.xml'));
        // add js
        $this['asset']->addFile('js', 'overlayer:js/dashboard.js');
        // render dashboard
        echo $this['template']->render('overlayer:layouts/dashboard', compact('xml'));
    }
    /*
    Function: config
    Save configuration
    
    Returns:
    Void
    */
    public function config() {
        // save configuration
        foreach ($this['request']->get('post:', 'array') as $option => $value) {
            if (preg_match('/^overlayer_/', $option)) {
                $this['system']->options->set($option, $value);
            }
        }
        $this['system']->saveOptions();
    }
}
// bind events
$atomicpress = AtomicPress::getInstance();
$atomicpress['event']->bind('site', array(
    $atomicpress['overlayer'],
    'site'
));
$atomicpress['event']->bind('dashboard', array(
    $atomicpress['overlayer'],
    'dashboard'
));
$atomicpress['event']->bind('task:config_overlayer', array(
    $atomicpress['overlayer'],
    'config'
)); 