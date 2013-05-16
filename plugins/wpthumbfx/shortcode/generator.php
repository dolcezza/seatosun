<?php
// Start WordPress
require('../../../../wp-load.php');
// Capability check
if (!current_user_can('publish_posts'))
    die('Access denied');
// Param check
if (empty($_GET['shortcode']))
    die('Shortcode not specified');
$shortcode = sc_list($_GET['shortcode']);
// Shortcode has atts
if (count($shortcode['atts']) && $shortcode['atts']) {
    foreach ($shortcode['atts'] as $attr_name => $attr_info) {
        $return .= '<p>';
        $return .= '<label for="sc-generator-attr-' . $attr_name . '">' . $attr_info['desc'] . '</label>';
        switch ($attr_info['type']) {
            case 'select':
                $return .= '<select name="' . $attr_name . '" id="sc-generator-attr-' . $attr_name . '" class="sc-generator-attr widefat">';
                foreach ($attr_info['values'] as $key => $attr_value) {
                    $return .= '<option value="' . $key . '"' . selected($attr_info['default'], $key, false) . '>' . $attr_value . '</option>';
                }
                $return .= '</select>';
                break;
            case 'text':
                $return .= '<input type="text" name="' . $attr_name . '" value="' . $attr_info['default'] . '" id="sc-generator-attr-' . $attr_name . '" class="sc-generator-attr widefat" />';
                break;
            case 'color':
                $return .= '<input type="text" name="' . $attr_name . '" value="' . $attr_info['default'] . '" id="sc-generator-attr-' . $attr_name . '" class="sc-generator-attr color widefat" />';
                break;
            case 'taxonomy':
                $categories = get_terms($attr_info['taxonomy'], $attr_info['options']);
                $return .= '<select name="' . $attr_name . '" id="sc-generator-attr-' . $attr_name . '" class="sc-generator-attr">';
                $return .= '<option value="">All</option>';
                foreach ($categories as $category) {
                    $return .= '<option value="' . $category->slug . '">' . $category->name . '</option>';
                }
                $return .= '</select>';
                break;
        }
        $return .= '</p>';
    }
}
// Single shortcode (not closed)
if ($shortcode['type'] == 'single') {
    $return .= '<input type="hidden" name="sc-generator-content" id="sc-generator-content" value="false" />';
}
// Wrapping shortcode
else {
    $return .= '<p><label>' . __('Content', 'atomicpress') . '</label><input type="text" name="sc-generator-content" class="widefat" id="sc-generator-content" value="' . $shortcode['content'] . '" /></p>';
}
$return .= '<input type="hidden" name="sc-generator-result" id="sc-generator-result" value="" />';
echo $return;
?> 