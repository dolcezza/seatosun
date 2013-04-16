<?php 
    $struct_tag = '<div class="a-tag">
                <div class="tag-title">Tag <span class="delete-tag">delete this tag</span></div>
                <div class="setting">
                <span class="label">Start Time</span><input type="text" value="5" class="textinput short" name="starttime" placeholder=""/>
                </div>
                <div class="setting">
                <span class="label">End Time</span><input type="text" value="10" class="textinput short" name="endtime" placeholder=""/>
                </div>
                <div class="setting">
                <span class="label">Position Left</span><input value="5" type="text" class="textinput short" name="posleft" placeholder=""/>
                </div>
                <div class="setting">
                <span class="label">Position Top</span><input value="5" type="text" class="textinput short" name="postop" placeholder=""/>
                </div>
                <div class="setting">
                <span class="label">Width</span><input value="50" type="text" class="textinput short" name="width" placeholder=""/>
                </div>
                <div class="setting">
                <span class="label">Height</span><input value="50" type="text" class="textinput short" name="height" placeholder=""/>
                </div>
                <div class="setting">
                <span class="label">Link</span><input value="" type="text" class="textinput" name="link" placeholder=""/>
                </div>
                <div class="setting">
                <span class="label">Text</span><input value="something" type="text" class="textinput" name="text" placeholder=""/>
                </div>
            </div>';
    
    
        $aux = str_replace(array("\r", "\r\n", "\n"), '', $struct_tag);
        
        $initer = '';
        if(isset($_GET['initer'])){
            $initer = $_GET['initer'];
        }
        
?>
<!doctype html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="popup.css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="popup.js"></script>
    <script>
        <?php echo "var struct_tag = '" . $aux . "';"; ?>
        <?php echo "var initer = '" . $initer . "';"; ?>
    </script>
    </head>
    <body>
        <div class="add-tag">add a tag</div>
        <div class="con-tags">
        </div>
        <hr>
        <div class="btn-submit">submit</div>
    </body>
</html>