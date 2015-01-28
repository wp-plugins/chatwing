<?php

use Chatwing\IntegrationPlugins\WordPress\Asset;

$count = 0;
// var_dump($boxes);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Asset::link('forms-min.css'); ?>
">
<link rel="stylesheet" type="text/css" href="<?php echo Asset::link('buttons-min.css'); ?>
">
<h2><?php _e('Chatboxes', CHATWING_TEXTDOMAIN); ?></h2>
<div class="wrap">
    <table class="widefat">
        <thead>
        <tr>
            <th>#</th>
            <th><?php _e('Name', CHATWING_TEXTDOMAIN) ?></th>
            <th><?php _e('Alias', CHATWING_TEXTDOMAIN); ?></th>
            <th><?php _e('Key', CHATWING_TEXTDOMAIN); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($boxes)): ?>
            <?php foreach ($boxes as $box): ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $box['name']; ?></td>
                    <td><?php echo $box['alias']; ?></td>
                    <td><?php echo $box['key']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"><?php _e('No box', CHATWING_TEXTDOMAIN); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <h2><?php _e("Settings", CHATWING_TEXTDOMAIN) ?></h2>

    <div id="poststuff" style="max-width: 800px;">
        <form class="pure-form pure-form-aligned pure-g" method="post"
              action="<?php echo admin_url('admin.php') ?>">
            <fieldset>
                <div class="pure-control-group">
                    <label
                        for="token"><?php _e('Access token', CHATWING_TEXTDOMAIN) ?></label>
                    <input id="token" type="text" name="token">
                    <label for="">
                        <input type="checkbox" name="remove_token" id="remove_token" value="1">
                    Delete current token ?
                    </label>
                </div>

                <div class="pure-control-group">
                    <label
                        for="width"><?php _e('Default chatbox width', CHATWING_TEXTDOMAIN) ?></label>
                    <input type="text" name="width" id="width" value="<?php echo get_option('chatwing_default_width'); ?>">
                    <?php _e('pixel') ?>
                </div>

                <div class="pure-control-group">
                    <label
                        for="height"><?php _e('Default chatbox height', CHATWING_TEXTDOMAIN) ?></label>
                    <input type="text" name="height" id="height" value="<?php echo get_option('chatwing_default_height'); ?>">
                    <?php _e('pixel') ?>
                </div>

                <div class="pure-controls">
                    <input type="submit" class="pure-button pure-button-primary"
                           value="<?php _e('Save', CHATWING_TEXTDOMAIN) ?>">
                </div>
            </fieldset>
            <div style="display: none">
                <input type="hidden" name="action" value="chatwing_save_settings">
                <?php wp_nonce_field('settings_save', 'nonce' ); ?>
            </div>
        </form>
    </div>
</div>