<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
global $WOOCS;
$search_id = uniqid('woof_txt_search');
?>
<div data-css-class="woof_text_search_container" class="woof_text_search_container woof_container woof_container_woof_text">
    <div class="woof_container_overlay_item"></div>
    <div class="woof_container_inner">
        <a href="javascript:void(0);"  class="woof_text_search_go"></a>
        <label class="woof_wcga_label_hide"  for="<?php echo $search_id ?>"><?php _e('Text search', 'woocommerce-products-filter') ?></label>
        <?php
        global $WOOF;
        $woof_text = '';
        $request = $WOOF->get_request_data();

        if (isset($request['woof_text'])) {
            $woof_text = stripslashes($request['woof_text']);
        }

        $input = '<input  type="search" class="woof_husky_txt-input" id="' . $search_id . '" ';

        foreach ($data as $key => $value) {
            if ($key === 'placeholder') {
                $input .= "{$key}=\"{$value}\" ";
            } elseif (in_array($key, array('notes_for_customer'))) {
                
            } else {
                $input .= "data-{$key}=\"{$value}\" ";
            }
        }

        $input .= 'value="' . $woof_text . '" autocomplete="off" />';

        echo $input;
        ?>
        <?php if (isset($data['notes_for_customer']) AND!empty($data['notes_for_customer'])): ?>
            <span class="woof_text_notes_for_customer"><?php echo stripcslashes($data['notes_for_customer']); ?></span>
<?php endif; ?>   		
    </div>
</div>