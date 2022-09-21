<?php
if (($_SERVER['REQUEST_METHOD'] == 'POST') && (empty($_POST['data']) === false)):
    $data       = json_decode($_POST['data']);
?>
<button class="category-option" id="select-All" value="0">
    <div class="title">All</div>
    <span class="quantity" id="quantity-All"></span>
</button>
<?php
foreach ($data as $category):
    if(isset($category->category_name)):
?>

<button class="category-option" id="select-<?php echo str_replace(" ", "", $category->category_name); ?>" value="<?php echo $category->category_id; ?>">
    <div class="title"><?php echo $category->category_name; ?></div>
    <span class="quantity" id="quantity-<?php echo str_replace(" ", "", $category->category_name); ?>"></span>
</button>
<?php endif; ?>
<!-- foreach data -->
<?php endforeach; ?>
<!-- if method post -->
<?php endif; ?>