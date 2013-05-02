<?php
/**
 * The template is used for displaying the Event List widget if the placeholder option isn't used.
 *
 * You can use this to edit how the output of the event list widget. For the shortcode [eo_events] see shortcode-event-list.php
 *
 * For a list of available functions (outputting dates, venue details etc) see http://wp-event-organiser.com/documentation/function-reference/
 *
 ***************** NOTICE: *****************
 *  Do not make changes to this file. Any changes made to this file
 * will be overwritten if the plug-in is updated.
 *
 * To overwrite this template with your own, make a copy of it (with the same name)
 * in your theme directory. See http://wp-event-organiser.com/documentation/editing-the-templates/ for more information
 *
 * WordPress will automatically prioritise the template in your theme directory.
 ***************** NOTICE: *****************
 *
 * @package Event Organiser (plug-in)
 * @since 1.7
 */
global $eo_event_loop,$eo_event_loop_args;

//Date % Time format for events
$date_format = get_option('date_format');
$time_format = get_option('time_format');

//The list ID / classes
$id = $eo_event_loop_args['id'];
$classes = $eo_event_loop_args['class'];

// Past events
$past_events = array();
?>

<?php if( $eo_event_loop->have_posts() ): ?>

	<ul id="<?php echo esc_attr($id);?>" class="<?php echo esc_attr($classes);?>" > 

		<?php while( $eo_event_loop->have_posts() ): $eo_event_loop->the_post(); ?>

			<?php 
				//Generate HTML classes for this event
				$eo_event_classes = eo_get_event_classes(); 

				//For non-all-day events, include time format
				$format = ( eo_is_all_day() ? $date_format : $date_format.' '.$time_format );
			?>
			
			<?php
			$html  = '<li class="' . esc_attr(implode(' ', $eo_event_classes)) . '">';
            $html .= '<a href="' . get_permalink(get_the_ID()) . '" title="' . the_title_attribute(array('echo' => false)) . '">' . get_the_title() .'</a> ' . __('on','eventorganiser') . ' ' . eo_get_the_start($format);
            $html .= '</li>';
			?>
			
			<?php if (in_array('eo-event-past', $eo_event_classes)) : ?>
			    <?php
			    $past_events[] = $html;
			    ?>
			<?php else : ?>
                <?php
                echo $html;
                ?>
            <?php endif; ?>

		<?php endwhile; ?>
		
		<?php if (!empty($past_events)) : ?>
    	    <ul class="past-events">
    	        <?php foreach ($past_events as $event_html) : ?>
    	            <?php echo $event_html; ?>
    	        <?php endforeach; ?>
            </ul>
    	<?php endif; ?>

	</ul>

<?php elseif( ! empty($eo_event_loop_args['no_events']) ): ?>

	<ul id="<?php echo esc_attr($id);?>" class="<?php echo esc_attr($classes);?>" > 
		<li class="eo-no-events" > <?php echo $eo_event_loop_args['no_events']; ?> </li>
	</ul>

<?php endif; ?>

