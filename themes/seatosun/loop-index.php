<div class="content-container ten columns">
    <?php
    $num_featured_items = 4;
    $news_posts = get_posts(array(
        'numberposts' => $num_featured_items,
    ));
    $artists = get_posts(array(
        'post_type' => 'seatosun_artist',
        'numberposts' => $num_featured_items,
    ));
    $videos = get_posts(array(
        'post_type' => 'seatosun_video',
        'numberposts' => $num_featured_items,
    ));
    $gallery_photos = get_posts(array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'numberposts' => $num_featured_items,
    ));
    $press_posts = get_posts(array(
        'category' => 'press',
        'numberposts' => $num_featured_items,
    ));
    ?>
    <?php if (!empty($news_posts)) : ?>
        <div class="featured-items news">
            
        </div>
    <?php endif; ?>
    <?php if (!empty($artists)) : ?>
        <div class="featured-items artists">
            
        </div>
    <?php endif; ?>
    <?php if (!empty($videos)) : ?>
        <div class="featured-items videos">
            
        </div>
    <?php endif; ?>
    <?php if (!empty($gallery_photos)) : ?>
        <div class="featured-items gallery">
            
        </div>
    <?php endif; ?>
    <?php if (!empty($press_posts)) : ?>
        <div class="featured-items press">
            
        </div>
    <?php endif; ?>
</div><!-- .content-container -->