<?php
global $wp_theme;
?>
</div><div class="footer">copyright 2013 Sea To Sun Recordings</div>
<?php wp_footer();?>
<script src="//connect.soundcloud.com/sdk.js"></script>
<script>
    SC.initialize({
        client_id: "<?php echo $wp_theme->config->soundcloud_client_id; ?>",
    });
</script>
</body>
</html>
