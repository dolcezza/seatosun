<table class="custom-metabox">
    <tr>
        <?php
        $metabox->the_field('video_or_playlist_url');
        ?>
        <td>
            <label for="<?php $metabox->the_name(); ?>">Video / Playlist URL</label>
        </td>
        <td>
            <input type="text" name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>" />
        </td>
    </tr>
</table>