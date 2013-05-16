<?php
/*
Class: TooltipAtomicPressHelper
Tooltip helper class
*/
class TooltipAtomicPressHelper extends AtomicPressHelper {
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
            'activation' => 'hover',
            'position' => 'top',
            'gutter' => 5,
            'maxwidth' => 220,
            'delay' => 0,
            'fadeIn' => 300,
            'fadeOut' => 300,
            'sticky' => 0
        ) as $option => $value) {
            $val              = $this->options->get('tooltip_' . $option, $value);
            $options[$option] = is_numeric($val) ? (float) $val : $val;
        }
        // is enabled ?
        if ($this->options->get('tooltip_enable', 1)) {
            $pluginPath = $this["path"]->url('tooltip:');
            $selector   = $this->options->get('tooltip_selector', '[data-tooltip]');
            $selector   = $this->options->get('tooltip_selector', '[data-tooltip]');
            if ($selector != '[data-tooltip]')
                $selector .= ',[data-tooltip]';
            $options = json_encode($options);
            // add stylesheets/javascripts
            $this['asset']->addFile('css', 'tooltip:css/tooltip.css');
            $this['asset']->addFile('js', 'tooltip:js/tooltip.js');
            $this['asset']->addString('js', "jQuery(function($){ $('{$selector}').tooltip({$options}) });");
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
        $xml = simplexml_load_file($this['path']->path('tooltip:tooltip.xml'));
        // add js
        $this['asset']->addFile('js', 'tooltip:js/dashboard.js');
        // render dashboard
        echo $this['template']->render('tooltip:layouts/dashboard', compact('xml'));
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
            if (preg_match('/^tooltip_/', $option)) {
                $this['system']->options->set($option, $value);
            }
        }
        $this['system']->saveOptions();
    }
}
// bind events
$atomicpress = AtomicPress::getInstance();
$atomicpress['event']->bind('site', array(
    $atomicpress['tooltip'],
    'site'
));
$atomicpress['event']->bind('dashboard', array(
    $atomicpress['tooltip'],
    'dashboard'
));
$atomicpress['event']->bind('task:config_tooltip', array(
    $atomicpress['tooltip'],
    'config'
)); 