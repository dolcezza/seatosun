<?php
/*
 * List of available shortcodes
 */
 
function sc_list($shortcode = false) {
    $shortcodes = array(
        # overlayer
        'overlayer' => array(
            'name' => 'Overlayer',
            'type' => 'wrap',
            'atts' => array(
                'effect' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'fade' => __('Fade', 'atomicpress'),
                        'top' => __('Top', 'atomicpress'),
                        'bottom' => __('Bottom', 'atomicpress'),
                        'left' => __('Left', 'atomicpress'),
                        'right' => __('Right', 'atomicpress')
                    ),
                    'desc' => __('Overlay Animation Effect', 'atomicpress')
                ),
                'image' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Image URI', 'atomicpress')
                ),
                'overlayDefault' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'overlay-default zoom' => __('Zoom', 'atomicpress'),
                        'overlay-default play' => __('Play', 'atomicpress'),
                        'overlay-default link' => __('link', 'atomicpress')
                    ),
                    'desc' => __('Default Icon', 'atomicpress')
                ),
                'invert' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'true' => __('Yes', 'atomicpress'),
                    ),
                    'desc' => __('Invert Behavior', 'atomicpress')
                ),
                'duration' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Effect Duration (in ms)', 'atomicpress')
                ),
                'easing' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'linear' => __('linear', 'atomicpress'),
                        'swing' => __('swing', 'atomicpress'),
                        'easeInQuad' => __('easeInQuad', 'atomicpress'),
                        'easeOutQuad' => __('easeOutQuad', 'atomicpress'),
                        'easeInOutQuad' => __('easeInOutQuad', 'atomicpress'),
                        'easeInCubic' => __('easeInCubic', 'atomicpress'),
                        'easeOutCubic' => __('easeOutCubic', 'atomicpress'),
                        'easeInOutCubic' => __('easeInOutCubic', 'atomicpress'),
                        'easeInQuart' => __('easeInQuart', 'atomicpress'),
                        'easeOutQuart' => __('easeOutQuart', 'atomicpress'),
                        'easeInOutQuart' => __('easeInOutQuart', 'atomicpress'),
                        'easeInQuint' => __('easeInQuint', 'atomicpress'),
                        'easeOutQuint' => __('easeOutQuint', 'atomicpress'),
                        'easeInOutQuint' => __('easeInOutQuint', 'atomicpress'),
                        'easeInExpo' => __('easeOutExpo', 'atomicpress'),
                        'easeInOutExpo' => __('easeInOutExpo', 'atomicpress'),
                        'easeInSine' => __('easeInSine', 'atomicpress'),
                        'easeOutSine' => __('easeOutSine', 'atomicpress'),
                        'easeInOutSine' => __('easeInOutSine', 'atomicpress'),
                        'easeInCirc' => __('easeInCirc', 'atomicpress'),
                        'easeOutCirc' => __('easeOutCirc', 'atomicpress'),
                        'easeInOutCirc' => __('easeInOutCirc', 'atomicpress'),
                        'easeInElastic' => __('easeInElastic', 'atomicpress'),
                        'easeOutElastic' => __('easeOutElastic', 'atomicpress'),
                        'easeInOutElastic' => __('easeInOutElastic', 'atomicpress'),
                        'easeInBack' => __('easeInBack', 'atomicpress'),
                        'easeOutBack' => __('easeOutBack', 'atomicpress'),
                        'easeInOutBack' => __('easeInOutBack', 'atomicpress'),
                        'easeInBounce' => __('easeInBounce', 'atomicpress'),
                        'easeOutBounce' => __('easeOutBounce', 'atomicpress'),
                        'easeInOutBounce' => __('easeInOutBounce', 'atomicpress')
                    ),
                    'desc' => __('Animation Easing', 'atomicpress')
                )
            ),
            'content' => __('', 'atomicpress'),
            'desc' => __('', 'atomicpress')
        ),
        # Lightbox
        'lightbox' => array(
            'name' => 'Lightbox',
            'type' => 'wrap',
            'atts' => array(
                'link' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Link to open in lightbox', 'atomicpress')
                ),
                'title' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Title for Lighbox', 'atomicpress')
                ),
                'group' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Group name', 'atomicpress')
                ),
                'width' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Lightbox Width', 'atomicpress')
                ),
                'height' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Lightbox Height', 'atomicpress')
                ),
                'padding' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Lightbox Padding', 'atomicpress')
                ),
                'overlayColor' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Overlay Color', 'atomicpress')
                ),
                'titlePosition' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'float' => __('Float', 'atomicpress'),
                        'outside' => __('Outside', 'atomicpress'),
                        'inside' => __('Inside', 'atomicpress'),
                        'over' => __('Over', 'atomicpress')
                    ),
                    'desc' => __('Title Position', 'atomicpress')
                ),
                'transitionIn' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'fade' => __('fade', 'atomicpress'),
                        'elastic' => __('elastic', 'atomicpress'),
                        'none' => __('none', 'atomicpress')
                    ),
                    'desc' => __('Set a opening transition', 'atomicpress')
                ),
                'transitionOut' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'fade' => __('fade', 'atomicpress'),
                        'elastic' => __('elastic', 'atomicpress'),
                        'none' => __('none', 'atomicpress')
                    ),
                    'desc' => __('Set a closing transition', 'atomicpress')
                )
            ),
            'content' => __('', 'atomicpress'),
            'desc' => __('', 'atomicpress')
        ),
        # tooltip
        'tooltip' => array(
            'name' => 'Tooltip',
            'type' => 'wrap',
            'atts' => array(
                'activation' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'hover' => __('Hover', 'atomicpress'),
                        'click' => __('Click', 'atomicpress')
                    ),
                    'desc' => __('Activate Tooltip On', 'atomicpress')
                ),
                'maxwidth' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Maximum Width of Tooltip', 'atomicpress')
                ),
                'gutter' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Space between tooltip and content', 'atomicpress')
                ),
                'sticky' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'true' => __('Yes', 'atomicpress'),
                        'false' => __('No', 'atomicpress')
                    ),
                    'desc' => __('Activate Sticky Tooltip', 'atomicpress')
                ),
                'position' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'top' => __('Top', 'atomicpress'),
                        'bottom' => __('Bottom', 'atomicpress'),
                        'right' => __('Right', 'atomicpress'),
                        'left' => __('Left', 'atomicpress')
                    ),
                    'desc' => __('Default position', 'atomicpress')
                ),
                'delay' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Delay before removing tooltip (in ms)', 'atomicpress')
                ),
                'fadeIn' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Fade In Speed (in ms)', 'atomicpress')
                ),
                'fadeOut' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Fade Out Speed (in ms)', 'atomicpress')
                ),
                'tooltipContent' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Tooltip Content', 'atomicpress')
                )
            ),
            'content' => __('', 'atomicpress'),
            'desc' => __('', 'atomicpress')
        ),
        # slides
        'slides' => array(
            'name' => 'Slides',
            'type' => 'wrap',
            'atts' => array(
                'fx' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'fade' => __('Fade', 'atomicpress'),
                        'slide' => __('Slide', 'atomicpress')
                    ),
                    'desc' => __('Overlay Animation Effect', 'atomicpress')
                ),
                'speed' => array(
                    'type' => 'text',
                    'default' => __('', 'atomicpress'),
                    'desc' => __('Transition Speed (in ms)', 'atomicpress')
                ),
                'pager' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'on' => __('Yes', 'atomicpress'),
                        'off' => __('No', 'atomicpress')
                    ),
                    'desc' => __('Pagination', 'atomicpress')
                ),
                'nav' => array(
                    'type' => 'select',
                    'values' => array(
                        '' => __('', 'atomicpress'),
                        'on' => __('Yes', 'atomicpress'),
                        'off' => __('No', 'atomicpress')
                    ),
                    'desc' => __('Navigation', 'atomicpress')
                )
            ),
            'content' => __('', 'atomicpress'),
            'desc' => __('', 'atomicpress')
        )
    );
    if ($shortcode)
        return $shortcodes[$shortcode];
    else
        return $shortcodes;
}
?>