<?php
global $wp_theme;
global $seatosun_release_meta;

$args = array(
    'post_type' => 'seatosun_release',
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
<?php if (!empty($posts)) : ?>
    <div class="featured-releases">
        <?php foreach ($posts as $post) : ?>
            <?php
            setup_postdata($post);
            $seatosun_release_meta->the_meta($post->ID);
            ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class('featured-item'); ?>>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="featured-release-image">
                        <?php the_post_thumbnail('releases-archive-featured-thumbnail'); ?>
                        <p class="tagline"><?php $seatosun_release_meta->the_value('tagline'); ?></p>
                    </div>
                <?php endif; ?>
                <div class="featured-release-info">
                    <p class="title"><?php $seatosun_release_meta->the_value('title'); ?></p>
                    <p class="artist"><?php $seatosun_release_meta->the_value('artist'); ?></p>
                    <p class="release-date">
                        <?php
                        $year = $seatosun_release_meta->get_the_value('year');
                        $month = $seatosun_release_meta->get_the_value('month');
                        $day = $seatosun_release_meta->get_the_value('day');

                         if ($month && $day) {
                            echo "$month-$day-";
                        }
                        echo $year;
                        ?>
                    </p>
                </div>
            </div><!-- #post-<?php the_ID(); ?> -->
        <?php endforeach; ?>
    </div>
<?php endif; ?>