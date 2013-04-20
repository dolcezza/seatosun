<?php
global $wp_theme;
global $seatosun_video_meta;
?>
<div class="content-container ten columns">
    <?php
    $args = array(
        'post_type' => 'seatosun_video',
        'posts_per_page' => 4,
        'meta_query' => array(
            array(
                'key' => '_default_meta_is_featured',
                'value' => 'true'
            ),
        ),
    );
    $posts = get_posts($args);
    ?>
    <?php
    $post = $posts[0];
    setup_postdata($post);
    $seatosun_video_meta->the_meta($post->ID);
    $url = $seatosun_video_meta->get_the_value('video_or_playlist_url');
    ?>
    <?php if ($url) : ?>
        <div class="video-player">
            <?php
            $wp_theme->youtube_embed_code($url);
            ?>
        </div>
    <?php endif; ?>
    <div class="featured-videos">
        <h3>Featured Videos</h3>
        <?php if (!empty($posts)) : ?>
            <div class="featured-videos">
                <?php foreach ($posts as $post) : ?>
                    <?php
                    setup_postdata($post);
                    $seatosun_video_meta->the_meta($post->ID);
                    ?>
                    <div id="featured-video-<?php the_ID(); ?>" class="featured-video">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-video-image">
                                <?php the_post_thumbnail('videos-archive-thumbnail'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="featured-video-info">

                        </div>
                    </div><!-- #post-<?php the_ID(); ?> -->
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="playlists">
        <h3>Playlists</h3>
        <?php
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        query_posts(array(
            'post_type' => 'seatosun_video',
            'paged' => $paged,
            'post_per_page' => 5
        ));
        ?>
        <?php while (have_posts()) : the_post(); ?>
        	
        	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	    <?php if (has_post_thumbnail()) : ?>
    				<div class="video-image">
    					<?php the_post_thumbnail('videos-archive-thumbnail'); ?>
    				</div>
    			<?php endif; ?>
    			<div class="video-info">
    			    <p class="num-videos"></p>
    				<p class="title"><?php the_title(); ?></p>
    				<p class="description"><?php the_content(); ?></p>
    			</div>
        	</div>
        	
        <?php endwhile; ?>
    </div>
</div><!-- .content-container -->