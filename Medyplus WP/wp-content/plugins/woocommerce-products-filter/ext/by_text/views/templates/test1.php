<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<img src="<?php echo $thumbnail ?>" class="woof_husky_txt-option-thumbnail" alt="" />
<div>
    test 1<br>

    <?php
    $labels_string = '<div class="woof_husky_txt-labels">';
    if (!empty($labels)) {
        foreach ($labels as $label) {
            $labels_string .= "<div>{$label}</div>";
        }
    }
    $labels_string .= '</div>';
    ?>
    <?php echo $labels_string ?>
    <div class="woof_husky_txt-option-title"><a href="<?php echo $permalink ?>" target="<?php echo $options['click_target'] ?>"><?php echo $title ?></a></div>
    <div class="woof_husky_txt-option-text"><?php echo $excerpt ?></div>        
</div>

