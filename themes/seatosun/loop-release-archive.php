<?php
global $wp_theme;
global $seatosun_release_meta;
?>
<div class="content-container ten columns">
    <?php while (have_posts()) : the_post(); ?>
	
		<?php
		$seatosun_release_meta->the_meta();
		?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if (has_post_thumbnail()) : ?>
				<div class="release-image">
					<?php the_post_thumbnail('releases-archive-widget'); ?>
				</div>
			<?php endif; ?>
			<div class="release-info">
				<p class="title"><?php $seatosun_release_meta->the_value('title'); ?></p>
				<p class="artist"><?php $seatosun_release_meta->the_value('artist'); ?></p>
			</div>
			<div class="release-hover-info">
				
			</div>
		</div>
		
	<?php endwhile; ?>
</div><!-- .content-container -->