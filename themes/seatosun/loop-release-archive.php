<?php
global $wp_theme;
global $seatosun_release_meta;

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
query_posts(array(
    'post_type' => 'seatosun_release',
    'paged' => $paged,
    'post_per_page' => 5
));
?>
<div class="content-container ten columns">
    <div id="releases-soundcloud-player-container">
    	<div class="track-info clearfix">
            <span class="track-title"></span>
            <span class="track-duration"></span>
        </div>
        <div class="player-controls clearfix">
            <a class="track-list ir" href="#" title="Track List">Track List</a>
            <a class="previous ir" href="#" title="Previous Track">Previous Track</a>
            <a class="play-pause paused ir" href="#" title="Play">Play</a>
            <a class="next ir" href="#" title="Next Track">Next Track</a>
            <a class="volume ir" href="#" title="Volume">Volume</a>
        </div>
    </div>
    
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $release_meta = $seatosun_release_meta->the_meta();
        $release_url = $seatosun_release_meta->get_the_value('track_or_playlist_url');
        $release_data = $wp_theme->get_soundcloud_data_from_url($release_url);
        $tracks = $release_data['tracks'];
        $track_id_list = array();
        if (!empty($tracks)) {
            foreach ($tracks as $track) {
                $track_id_list[] = $track['id'];
            }
        }
        ?>
        <?php if ($release_data) : ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-release-id="<?php echo $release_data['id']; ?>" data-track-id-list="<?php echo json_encode($track_id_list); ?>" data-overlayer="on" data-tooltip="on" >
                <div class="release-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('releases-archive-widget'); ?>
                    <?php else : ?>
                        <?php if (!empty($release_data['artwork_url'])) : ?>
                            <img class="wp-post-image" src="<?php echo $release_data['artwork_url']; ?>" alt="" />
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="release-info">
                    <p class="title"><?php $seatosun_release_meta->the_value('title'); ?></p>
                    <p class="artist"><?php $seatosun_release_meta->the_value('artist'); ?></p>
                </div>
                <div class="release-hover-info tip-content">
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
                    <?php
                    $meta_download_links = $release_meta['download_links'];
                    $soundcloud_download_link = $release_data['purchase_url'];
                    ?>
                    <?php if (!empty($meta_download_links) || $soundcloud_download_link) : ?>
                        <div class="download-links-section">
                            <span class="section-title">Download</span>
                            <?php if (!empty($meta_download_links)) : ?>
                                <?php while ($seatosun_release_meta->have_fields('download_links')) : ?>
                                    <?php
                                    $service_name = $seatosun_release_meta->get_the_value('service_name');
                                    $link_url = $seatosun_release_meta->get_the_value('link_url');
                                    ?>
                                    <a class="download-link <?php echo $service_name; ?> ir" href="<?php echo $link_url; ?>"><?php echo $service_name; ?></a>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <a class="download-link soundcloud ir" href="<?php echo $soundcloud_download_link; ?>">SoundCloud</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
</div><!-- .content-container -->