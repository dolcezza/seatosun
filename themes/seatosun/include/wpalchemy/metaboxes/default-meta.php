<table class="custom-metabox">
    <tr>
        <?php $metabox->the_field('is_featured'); ?>
        <td>
            <label for="<?php $metabox->the_name(); ?>">Featured?</label>
        </td>
        <td>
            <input type="checkbox" name="<?php $mb->the_name(); ?>" id="<?php $mb->the_name(); ?>" value="true" <?php $mb->the_checkbox_state('true'); ?> />
        </td>
    </tr>
</table>