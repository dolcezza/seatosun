<?php
require_once('get_wp.php');
//<script src="<?php echo site_url(); "></script>
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>The title</title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo $zsvg->thepath; ?>tinymce/popup.css"/>
        <script src="<?php echo $zsvg->thepath; ?>tinymce/popup.js"></script>
        <script>window.theme_url = "<?php echo THEME_URL; ?>"</script>
        <?php //wp_head(); ?>
    </head>
    <body>
        <div class="sc-con">
        <div class="sc-menu">
            <h3>Select a Gallery to Insert</h3>
            <select class="styleme" name="zsvg_selectid">
            <?php foreach($zsvg->mainitems as $mainitem){
                echo '<option>' . ($mainitem['settings']['id']) . '</option>';
            } ?>
                </select>
            <div class="clear"></div>
            <br/>
            <br/>
                <button id="insert_tests" class="ui-button">Insert Gallery</button>
        </div>

            </div>
    </body>
</html> 