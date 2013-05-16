<?php
/*
Class: SlidesAtomicPressHelper
Slides helper class
*/
class SlidesAtomicPressHelper extends AtomicPressHelper {
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
            'fx' => 'fade',
            'speed' => 700,
            'pager' => 0,
            'nav' => 1
        ) as $option => $value) {
            $val              = $this->options->get('slides_' . $option, $value);
            $options[$option] = is_numeric($val) ? (float) $val : $val;
        }
        // is enabled ?
        if ($this->options->get('slides_enable', 1)) {
            $slidesjs = $this['path']->url("slides:js/slides.js");
            $selector = $this->options->get('slides_selector', '[data-slides]');
            if ($selector != '[data-slides]')
                $selector .= ',[data-slides]';
            $params = count($options) ? json_encode($options) : '{}';
            // add stylesheets/javascripts
            $this['asset']->addFile('css', 'slides:css/slides.css');
            $this['asset']->addFile('js', 'slides:js/slides.js');
            $this['asset']->addString('js', "jQuery(function($){ $('{$selector}').slides({$params}) });");
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
        $xml = simplexml_load_file($this['path']->path('slides:slides.xml'));
        // add js
        $this['asset']->addFile('js', 'slides:js/dashboard.js');
        // render dashboard
        echo $this['template']->render('slides:layouts/dashboard', compact('xml'));
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
            if (preg_match('/^slides_/', $option)) {
                $this['system']->options->set($option, $value);
            }
        }
        $this['system']->saveOptions();
    }
}
// bind events
$atomicpress = AtomicPress::getInstance();
$atomicpress['event']->bind('site', array(
    $atomicpress['slides'],
    'site'
));
$atomicpress['event']->bind('dashboard', array(
    $atomicpress['slides'],
    'dashboard'
));
$atomicpress['event']->bind('task:config_slides', array(
    $atomicpress['slides'],
    'config'
)); 