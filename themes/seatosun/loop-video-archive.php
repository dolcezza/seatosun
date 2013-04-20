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
        <div id="video-player-container">
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
                    $url = $seatosun_video_meta->get_the_value('video_or_playlist_url');
                    $embed_url = $wp_theme->get_youtube_embed_url($url);
                    $video_data = $wp_theme->get_youtube_video_data($url);

                    $title = $seatosun_video_meta->get_the_value('title');
                    if (!$title) {
                        $title = $video_data ? $video_data->title : get_the_title();
                    }
                    
                    if (has_post_thumbnail()) {
                        $thumbnail = get_the_post_thumbnail($post->ID, 'videos-archive-thumbnail');
                    } else {
                        $thumbnail = $video_data->thumbnail->hqDefault;
                        if (!$thumbnail) {
                            $thumbnail = $video_data->thumbnail->sqDefault;
                        }
                        if ($thumbnail) {
                            $thumbnail = '<img class="attachment-videos-archive-thumbnail wp-post-image" src="' . $thumbnail . '" alt="" />';
                        }
                    }
                    ?>
                    <div id="featured-video-<?php the_ID(); ?>" <?php post_class('featured-video'); ?> data-embed-url="<?php echo $embed_url; ?>">
                        <?php if ($thumbnail) : ?>
                            <div class="featured-video-image">
                                <?php echo $thumbnail; ?>
                            </div>
                        <?php endif; ?>
                        <div class="featured-video-info">
                            <p class="title"><?php echo $title; ?></p>
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
        	<?php
        	$seatosun_video_meta->the_meta();
        	$url = $seatosun_video_meta->get_the_value('video_or_playlist_url');
            $embed_url = $wp_theme->get_youtube_embed_url($url);
            $video_data = $wp_theme->get_youtube_video_data($url);
            
            $title = $seatosun_video_meta->get_the_value('title');
            if (!$title) {
                $title = $video_data ? $video_data->title : get_the_title();
            }
            
            $description = $seatosun_video_meta->get_the_value('description');
            if (!$description) {
                $description = $video_data ? $video_data->description : get_the_content();
            }
            
            if (has_post_thumbnail()) {
                $thumbnail = get_the_post_thumbnail($post->ID, 'videos-archive-thumbnail');
            } else {
                $thumbnail = $video_data->thumbnail->hqDefault;
                if (!$thumbnail) {
                    $thumbnail = $video_data->thumbnail->sqDefault;
                }
                if ($thumbnail) {
                    $thumbnail = '<img class="attachment-videos-archive-thumbnail wp-post-image" src="' . $thumbnail . '" alt="" />';
                }
            }
        	?>
        	<div id="post-<?php the_ID(); ?>" <?php post_class('playlist-item'); ?> data-embed-url="<?php echo $embed_url; ?>">
        	    <?php if ($thumbnail) : ?>
    				<div class="video-image">
    					<?php echo $thumbnail; ?>
    				</div>
    			<?php endif; ?>
    			<div class="video-info">
    			    <p class="num-videos"></p>
    				<p class="title"><?php echo $title; ?></p>
    				<p class="description"><?php echo wp_trim_words($description, 100); ?></p>
    			</div>
        	</div>
        	
        <?php endwhile; ?>
    </div>
</div><!-- .content-container -->