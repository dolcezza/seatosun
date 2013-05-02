<table class="custom-metabox">
    <tr>
        <?php
        $metabox->the_field('track_or_playlist_url');
        ?>
        <td>
            <label for="<?php $metabox->the_name(); ?>">Track / Playlist URL</label>
        </td>
        <td>
            <input type="text" name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>" />
        </td>
    </tr>
    <tr>
        <?php
        $metabox->the_field('title');
        ?>
        <td>
            <label for="<?php $metabox->the_name(); ?>">Title</label>
        </td>
        <td>
            <input type="text" name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>" />
        </td>
    </tr>
    <tr>
        <?php
        $metabox->the_field('tagline');
        ?>
        <td>
            <label for="<?php $metabox->the_name(); ?>">Tagline</label>
        </td>
        <td>
            <textarea name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>"><?php $metabox->the_value(); ?></textarea>
        </td>
    </tr>
    <tr>
        <?php
        $metabox->the_field('artist');
        ?>
        <td>
            <label for="<?php $metabox->the_name(); ?>">Artist</label>
        </td>
        <td>
            <input type="text" name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>" />
        </td>
    </tr>
    <tr>
        <?php
        $metabox->the_field('year');
        ?>
        <td>
            <label for="<?php $metabox->the_name(); ?>">Release Date</label>
        </td>
        <td>
            <input type="text" name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>" placeholder="Year" />
            <?php
            $metabox->the_field('month');
            ?>
            <input type="text" name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>" placeholder="Month" />
            <?php
            $metabox->the_field('day');
            ?>
            <input type="text" name="<?php $metabox->the_name(); ?>" id="<?php $metabox->the_name(); ?>" value="<?php $metabox->the_value(); ?>" placeholder="Day" />
        </td>
    </tr>
</table>