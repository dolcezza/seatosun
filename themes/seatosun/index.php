<?php get_header(); ?>
    
    <?php get_template_part('masthead', 'index'); ?>
    
    <?php get_sidebar('left'); ?>
    
    <?php get_template_part('loop', 'index'); ?>
    
    <?php get_sidebar('right'); ?>
    
<?php get_footer(); ?>