<?php
$post = $_GET;
require_once('get_wp.php');
//print_r();
//echo $post['slidernr'].$post['slidername'];
?>
Please note that this feature uses the last saved data. Unsaved changes will not be exported.
<form action="<?php echo site_url() . '/wp-admin/options-general.php?page=zsvg_menu'; ?>" method="POST">
    <input type="hidden" class="hidden" name="slidernr" value="<?php echo $post['slidernr']; ?>"/> 
    <input type="hidden" class="hidden" name="slidername" value="<?php echo $post['slidername']; ?>"/> 
    <input class="button-secondary" type="submit" name="zsvg_exportslider" value="Export"/></form>
