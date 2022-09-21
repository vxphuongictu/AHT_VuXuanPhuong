<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOF;
if (isset($WOOF->settings['by_text_2']) AND $WOOF->settings['by_text_2']['show'])
{
    if (isset($WOOF->settings['by_text_2']['title']) AND ! empty($WOOF->settings['by_text_2']['title']))
    {
        ?>
        <!-- <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo $WOOF->settings['by_text_2']['title']; ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>> -->
        <?php
    }
    echo do_shortcode('[woof_text_filter]');
}


