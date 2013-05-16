<?php
/*
 * Shortcode: overlayer
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Output html
 */
function sc_overlayer_shortcode($atts, $content = null) {
    $customOverlay = '';
    if ($atts):
        foreach ($atts as $key => $values) {
            if ($key != 'image') {
                if ($key == 'overlaydefault')
                    $key = 'overlayDefault';
                $param .= $key . ':' . $values . ';';
            }
        }
    else:
        $param = 'on';
    endif;
    if ($content)
        $customOverlay = '<div class="overlay">' . do_shortcode($content) . '</div>';
    $output = '<div data-overlayer="' . $param . '"><img src="' . $atts['image'] . '"/>' . $customOverlay . '</div>';
    return $output;
}

/*
 * Shortcode: slides
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Output html
 */
function sc_slides_shortcode($atts, $content = null) {
    if ($atts):
        foreach ($atts as $key => $values)
            $param .= $key . ':' . $values . ';';
    else:
        $param = 'on';
    endif;
    $output = '<div data-slides="' . $param . '">' . do_shortcode($content) . '</div>';
    return $output;
}

/*
 * Shortcode: lightbox
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Output html
 */
function sc_lightbox_shortcode($atts, $content = null) {
    $title = '';
    if ($atts):
        foreach ($atts as $key => $values) {
            if ($key != 'link') {
                switch ($key) {
                    case 'overlaycolor':
                        $key = 'overlayColor';
                        break;
                    case 'titleposition':
                        $key = 'titlePosition';
                        break;
                    case 'transitionin':
                        $key = 'transitionIn';
                        break;
                    case 'transitionout':
                        $key = 'transitionOut';
                        break;
                }
                $param .= $key . ':' . $values . ';';
            }
        }
    else:
        $output = do_shortcode($content);
        return $output;
    endif;
    if ($atts['title'])
        $title = 'title="' . $atts['title'] . '"';
    $output = '<a ' . $title . ' href="' . $atts['link'] . '" data-lightbox="' . $param . '">' . do_shortcode($content) . '</a>';
    return $output;
}

/*
 * Shortcode: tooltip
 * @param array $atts Shortcode attributes
 * @param string $content
 * @return string Output html
 */
function sc_tooltip_shortcode($atts, $content = null) {
    if ($atts):
        foreach ($atts as $key => $values) {
            if ($key != 'tooltipcontent') {
                $param .= $key . ':' . $values . ';';
            }
        }
    else:
        $param = 'on';
    endif;

    $output = '<span data-tooltip="' . $param . '">' . do_shortcode($content) . '<span class="tip-content">'.do_shortcode(html_entity_decode($atts['tooltipcontent'])).'</span></span>';
    return $output;
}

?>