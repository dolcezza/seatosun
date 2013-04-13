<?php
global $wp_theme;
?>
<div class="content-container ten columns">
    <?php while (have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
		
	<?php endwhile; ?>
</div><!-- .content-container -->