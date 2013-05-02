<?php
global $wp_theme;
global $seatosun_video_meta;
?>
<div class="content-container ten columns">
    <?php
    // Get featured videos / playlists
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
    // Embed video player for latest featured video / playlist
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
        <h3 class="section-title">Featured Videos</h3>
        <?php if (!empty($posts)) : ?>
            <div class="featured-videos">
                <?php foreach ($posts as $post) : ?>
                    <?php
                    setup_postdata($post);
                    $seatosun_video_meta->the_meta($post->ID);
                    $url = $seatosun_video_meta->get_the_value('video_or_playlist_url');
                    ?>
                    <?php if ($url) : ?>
                        <?php
                        $youtube_data = $wp_theme->get_youtube_data($url);
                        extract($youtube_data);
                        ?>
                        <div id="featured-video-<?php the_ID(); ?>" <?php post_class('featured-video'); ?> data-embed-url="<?php echo $embed_url; ?>">
                            <?php if ($thumbnail) : ?>
                                <div class="featured-video-image">
                                    <?php echo $thumbnail; ?>
                                    <?php if ($duration) : ?>
                                        <span class="duration"><?php echo $duration; ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="featured-video-info">
                                <p class="video-title"><?php echo $title; ?></p>
                            </div>
                        </div><!-- #post-<?php the_ID(); ?> -->
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="playlists">
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
            ?>
            <?php if ($url) : ?>
                <?php
                $youtube_data = $wp_theme->get_youtube_data($url);
                extract($youtube_data);
                $container_class = $playlist_items ? 'playlist-container' : 'video-container';
                ?>
                <?php if ($playlist_items) : ?>
                    <h3 class="section-title playlist-title"><?php echo $title; ?> (<?php echo count($playlist_items); ?> Videos)</h3>
                <?php endif; ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class($container_class); ?> data-embed-url="<?php echo $embed_url; ?>">
                    <?php if ($playlist_items) : ?>
                        <?php foreach ($playlist_items as $video) : ?>
                            <?php
                            $video_data = $video->video;
                            $title = $video_data->title;
                            $player_url = $video_data->player->default;
                            $embed_url = $wp_theme->get_youtube_embed_url($player_url);
                            $thumbnail = $video_data->thumbnail->hqDefault;
                            if (!$thumbnail) {
                                $thumbnail = $video_data->thumbnail->sqDefault;
                            }
                            if ($thumbnail) {
                                $thumbnail = '<img class="video-thumbnail wp-post-image" src="' . $thumbnail . '" alt="" />';
                            }
                            ?>
                            <div class="playlist-video" data-embed-url="<?php echo $embed_url; ?>">
                                <?php if ($thumbnail) : ?>
                                    <div class="playlist-video-thumbnail-container">
                                        <?php echo $thumbnail; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($title) : ?>
                                    <p class="video-title"><?php echo $title; ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <?php if ($thumbnail) : ?>
                            <div class="video-image">
                                <?php echo $thumbnail; ?>
                                <?php if ($duration) : ?>
                                    <span class="duration"><?php echo $duration; ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <aside class="video-info">
                            <p class="title"><?php echo $title; ?></p>
                            <p class="description"><?php echo wp_trim_words($description, 100); ?></p>
                        </aside>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        <?php endwhile; ?>
    </div>
</div><!-- .content-container -->