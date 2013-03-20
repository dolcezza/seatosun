<?php
//
//  SETTINGS CONFIGURATION CLASS
//
//  By Olly Benson / v 1.2 / 13 July 2011 / http://code.olib.co.uk
//  Modified / Bugfix by Karl Cohrs / 17 July 2011 / http://karlcohrs.com
//
//  HOW TO USE
//  * add a include() to this file in your plugin.
//  * amend the config class below to add your own settings requirements.
//  * to avoid potential conflicts recommended you do a global search/replace on this page to replace 'custom_theme_settings' with something unique
//  * Full details of how to use Settings see here: http://codex.wordpress.org/Settings_API
 

class custom_theme_settings_config {
 
// MAIN CONFIGURATION SETTINGS
 
var $group = "custom_theme_settings"; // defines setting groups (should be bespoke to your settings)
var $page_name = "custom_theme_settings"; // defines which pages settings will appear on. Either bespoke or media/discussion/reading etc
 
//  DISPLAY SETTINGS
//  (only used if bespoke page_name)
 
var $title = "Theme Settings";  // page title that is displayed
var $intro_text = ""; // text below title
var $nav_title = "Theme Settings"; // how page is listed on left-hand Settings panel
 
//  SECTIONS
//  Each section should be own array within $sections.
//  Should contatin title, description and fields, which should be array of all fields.
//  Fields array should contain:
//  * label: the displayed label of the field. Required.
//  * description: the field description, displayed under the field. Optional
//  * suffix: displays right of the field entry. Optional
//  * default_value: default value if field is empty. Optional
//  * dropdown: allows you to offer dropdown functionality on field. Value is array listed below. Optional
//  * function: will call function other than default text field to display options. Option
//  * callback: will run callback function to validate field. Optional
//  * All variables are sent to display function as array, therefore other variables can be added if needed for display purposes
 
var $sections = array(
    'appearance_settings' => array(
        'title' => 'Appearance Settings',
        'fields' => array(
            'foreground_color' => array(
                'label' => 'Text Color',
                'class' => 'colorpicker',
                'default_value' => '464646',
                'description' => 'The text color for page headings and the top navigation menu'
            ),
            'background_color' => array(
                'label' => 'Background Color',
                'class' => 'colorpicker',
                'default_value' => 'ffffff',
                'description' => 'The main background color'
            ),
            'logo_image' => array(
                'label' => 'Logo Image',
                'upload' => true,
                'description' => 'The logo that is displayed in the site header (max height 70px)'
            ),
            'logo_text' => array(
                'label' => 'Logo Text',
                'description' => 'This text will be used if no image is specified above. If nothing is set here the site title is used.'
            )
        )
    ),
    'social_links' => array(
        'title' => 'Social Network Links',
        'fields' => array(
            'facebook_url' => array(
                'label' => 'Facebook URL'
            ),
            'twitter_url' => array(
                'label' => 'Twitter URL'
            ),
            'linkedin_url' => array(
                'label' => 'LinkedIn URL'
            )
        )
    ),
    'contact_info' => array(
		'title' => 'Contact Information',
		'fields' => array(
		    'contact_name' => array(
				'label' => 'Name'
			),
			'contact_phone_number' => array(
			    'label' => 'Phone Number'
			),
			'contact_fax_number' => array(
			    'label' => 'Fax Number'
			),
			'contact_email' => array(
			    'label' => 'Email Address'
			),
			'contact_website' => array(
			    'label' => 'Website URL'
			),
			'contact_address' => array(
				'label' => 'Address',
				'description' => 'Also used to generate the Google Map',
				'textarea' => true
			)
		)
	)
);
 
 // DROPDOWN OPTIONS
 // For drop down choices.  Each set of choices should be unique array
 // Use key => value to indicate name => display name
 // For default_value in options field use key, not value
 // You can have multiple instances of the same dropdown options
 
public static $dropdown_options = array();
 
//  end class
};

 
class custom_theme_settings {
    function custom_theme_settings($settings_class) {
        global $custom_theme_settings;
        global $wp_theme;
        
        $custom_theme_settings = get_class_vars($settings_class);
        $wp_theme->update_google_maps_thumbnail();
        
        if (function_exists('add_action')) {
            add_action('admin_init', array( &$this, 'plugin_admin_init'));
            add_action('admin_menu', array( &$this, 'plugin_admin_add_page'));
        }
    }
     
    function plugin_admin_add_page() {
        global $custom_theme_settings;
        add_options_page($custom_theme_settings['title'], $custom_theme_settings['nav_title'], 'manage_options', $custom_theme_settings['page_name'], array( &$this,'plugin_options_page'));
    }
     
    function plugin_options_page() {
        global $custom_theme_settings;
        printf(
            '</pre>
            <div class="custom-theme-settings">
            <div class="icon32" id="icon-tools"></div>
            <h2 class="theme-settings-title">%s</h2>
            %s
            <form class="theme-settings-form" action="options.php" method="post" enctype="multipart/form-data">',$custom_theme_settings['title'],$custom_theme_settings['intro_text']);
            settings_fields($custom_theme_settings['group']);
            do_settings_sections($custom_theme_settings['page_name']);
            printf('<input type="submit" name="Submit" class="button-primary" value="%s" /></form>
            </div>
            <pre>',
            __('Save Changes')
        );
    }
    
    function plugin_admin_init(){
        global $custom_theme_settings;
        foreach ($custom_theme_settings["sections"] as $section_key => $section_value) :
            add_settings_section($section_key, $section_value['title'], array( &$this, 'plugin_section_text'), $custom_theme_settings['page_name'], $section_value);
            foreach ($section_value['fields'] as $field_key => $field_value) :
                if (!empty($field_value['function'])) {
                    $function = $field_value['function'];
                } else if (!empty($field_value['dropdown'])) {
                    $function = array(&$this, 'plugin_setting_dropdown');
                } else if (!empty($field_value['checkbox'])) {
                    $function = array(&$this, 'plugin_setting_checkbox');
                } else if (!empty($field_value['textarea'])) {
                    $function = array(&$this, 'plugin_setting_textarea');
                } else if (!empty($field_value['upload'])) {
                    $function = array(&$this, 'plugin_setting_file_upload');
                } else {
                    $function = array(&$this, 'plugin_setting_string');
                }
                $callback = (!empty($field_value['callback'])) ? $field_value['callback'] : null;
                $field_id = $custom_theme_settings['group'] . '_' . $field_key;
                $field_name = $field_id;
                $field_title = $field_value['label'];
                register_setting($custom_theme_settings['group'], $field_id, $callback);
                add_settings_field($field_id, $field_title, $function, $custom_theme_settings['page_name'], $section_key, array_merge($field_value, array('name' => $field_name, 'label_for' => $field_name)));
            endforeach;
        endforeach;
            
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery');
    }
    
    function plugin_section_text($value = null) {
        global $custom_theme_settings;
        printf("%s", $custom_theme_settings['sections'][$value['id']]['description']);
    }
     
    function plugin_setting_string($value = null) {
        $options = get_option($value['name']);
        $default_value = (!empty ($value['default_value'])) ? $value['default_value'] : null;
        printf(
            '<input id="%s" class="%5$s" type="text" name="%1$s[text_string]" value="%2$s" size="40" /> %3$s%4$s',
            $value['name'],
            (!empty($options['text_string'])) ? $options['text_string'] : $default_value,
            (!empty($value['suffix'])) ? $value['suffix'] : null,
            (!empty($value['description'])) ? sprintf("<br /><em>%s</em>", $value['description']) : null,
            (!empty($value['class'])) ? $value['class'] : null
        );
    }
    
    function plugin_setting_textarea($value = null) {
        $options = get_option($value['name']);
        $default_value = (!empty ($value['default_value'])) ? $value['default_value'] : null;
        printf(
            '<textarea id="%s" type="text" name="%1$s[text_string]">%2$s</textarea> %3$s%4$s',
            $value['name'],
            (!empty($options['text_string'])) ? $options['text_string'] : $default_value,
            (!empty($value['suffix'])) ? $value['suffix'] : null,
            (!empty($value['description'])) ? sprintf("<br /><em>%s</em>",$value['description']) : null
        );
    }
     
    function plugin_setting_dropdown($value = null) {
        global $custom_theme_settings;
        $options = get_option($value['name']);
        $default_value = (!empty ($value['default_value'])) ? $value['default_value'] : null;
        $current_value = ($options['text_string']) ? $options['text_string'] : $default_value;
        $chooseFrom = "";
        $choices = $custom_theme_settings['dropdown_options'][$value['dropdown']];
        foreach($choices AS $key=>$option) :
            $chooseFrom .= sprintf(
                '<option value="%s" %s>%s</option>',
                $key,
                ($current_value == $key) ? ' selected="selected"' : null,
                $option
            );
        endforeach;
        printf(
            '<select id="%s" name="%1$s[text_string]">%2$s</select> %3$s',
            $value['name'],
            $chooseFrom,
            (!empty ($value['description'])) ? sprintf("<br /><em>%s</em>",
            $value['description']) : null
        );
    }
    
    function plugin_setting_checkbox($value = null) {
        global $custom_theme_settings;
        $options = get_option($value['name']);
        $checked = (!empty($value['checked']));
        printf(
            '<input type="checkbox" id="%1$s" class="%2$s" name="%1$s[text_string]" %3$s />%4$s%5$s',
            $value['name'],
            (!empty($value['class'])) ? $value['class'] : null,
            (!empty($options['text_string'])) ? 'checked="checked"' : null,
            (!empty($value['suffix'])) ? $value['suffix'] : null,
            (!empty($value['description'])) ? sprintf("<br /><em>%s</em>", $value['description']) : null
        );
    }
    
    function plugin_setting_file_upload($value = null) {
        global $custom_theme_settings;
        $options = get_option($value['name']);
        $default_value = (!empty ($value['default_value'])) ? $value['default_value'] : null;
        printf(
            '<input id="%1$s" class="%5$s media-upload-input" type="text" name="%1$s[text_string]" value="%2$s" size="40" />
            <input id="%1$s_button" data-target="%1$s" class="button button-primary media-upload-button" type="button" value="Select Image" />
            <input class="button button-primary media-upload-clear-value" type="button" value="Remove Image" /> %3$s%4$s',
            $value['name'],
            (!empty($options['text_string'])) ? $options['text_string'] : $default_value,
            (!empty($value['suffix'])) ? $value['suffix'] : null,
            (!empty($value['description'])) ? sprintf("<br /><em>%s</em>",$value['description']) : null,
            (!empty($value['class'])) ? $value['class'] : null
        );
    }
}
 
$custom_theme_settings_init = new custom_theme_settings('custom_theme_settings_config');
?>