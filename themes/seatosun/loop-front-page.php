<?php
global $wp_theme;
?>
<div class="content-container ten columns">
    <?php
    $posts_per_page = $wp_theme->get_option_value('posts_per_page');
    if (!$posts_per_page) {
        $posts_per_page = 10;
    }
    $wp_theme->cache_post_object();
    
    $args = array(
        'post_type' => array('post', 'seatosun_release', 'seatosun_video'),
        'posts_per_page' => $posts_per_page,
        'meta_query' => array(
            array(
                'key' => '_default_meta_is_featured',
                'value' => 'true'
            ),
        ),
    );
    
    $posts = get_posts($args);
    ?>
    <?php if (!empty($posts)) : ?>
        <div class="featured-items">
            <?php foreach ($posts as $post) : ?>
                <?php
                setup_postdata($post);
                $has_post_thumbnail = has_post_thumbnail($post->ID);
                $additional_classes = 'featured-item';
                if ($has_post_thumbnail) {
                    $additional_classes .= ' has-post-thumbnail';
                }
                ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class($additional_classes); ?>>
                    <?php if ($has_post_thumbnail) : ?>
                        <div class="entry-image-container">
                            <?php echo get_the_post_thumbnail($post->ID); ?>
                        </div>
                    <?php endif; ?>
                    <header class="entry-header">
                        <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    </header>
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </div><!-- #post-<?php the_ID(); ?> -->
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php
    $wp_theme->restore_post_object();
    ?>
</div><!-- .content-container -->