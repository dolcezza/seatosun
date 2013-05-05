<?php
global $wp_theme;
global $seatosun_release_meta;
?>
<div class="content-container ten columns">
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $seatosun_release_meta->the_meta();
        $release_url = $seatosun_release_meta->get_the_value('track_or_playlist_url');
        $release_data = $wp_theme->get_soundcloud_data_from_url($release_url);
        ?>
        <?php if ($release_data) : ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-release-id="<?php echo $release_data['id']; ?>">
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
                    <p class="tagline"><?php $seatosun_release_meta->the_value('tagline'); ?></p>
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
                    <p class="duration">06:38</p>
                </div>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
</div><!-- .content-container -->