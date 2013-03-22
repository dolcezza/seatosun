<?php
global $wp_theme;
?>
<?php wp_footer();?>
<script src="//connect.soundcloud.com/sdk.js"></script>
<script>
    SC.initialize({
        client_id: "<?php echo $wp_theme->config->soundcloud_client_id; ?>",
    });
</script>
</body>
</html>
