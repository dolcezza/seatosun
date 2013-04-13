<?php get_header(); ?>
    
    <?php get_template_part('masthead', 'front-page'); ?>
    
    <?php get_sidebar('left'); ?>
    
    <?php get_template_part('loop', 'front-page'); ?>
    
    <?php get_sidebar('right'); ?>
    
<?php get_footer(); ?>