<?php $wp_dummy_content_generatorPosTypes = wp_dummy_content_generatorGetPostTypes(); ?>
<div class="wp_dummy_content_generator-success-msg" style="display: none;"></div>
<div class="wp_dummy_content_generator-error-msg" style="display: none;"></div>
<form method="post" id="wp_dummy_content_generatorGenPostForm" class="wp_dummy_content_generatorCol-9">
	<input type="hidden" name="action" value="wp_dummy_content_generatorAjaxGenPosts" />
	<input type="hidden" name="remaining_posts" class="remaining_posts" value="" />
    <table class="form-table">
        <tr valign="top">
	        <th scope="row">Choose Post type</th>
	        <td>
	        	<select name="wp_dummy_content_generator-posttype">
	        		<?php foreach ($wp_dummy_content_generatorPosTypes as $key => $value): ?>
	        			<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
	        		<?php endforeach; ?>
	        	</select>
	        	<p class="description">Choose the post type for which you want to generate the dummy data.</p>
	        </td>
        </tr>
        <tr valign="top">
	        <th scope="row">Number of posts</th>
	        <td>
	        	<input type="number" name="wp_dummy_content_generator-post_count" class="wp_dummy_content_generator-post_count"  placeholder="Number of posts" value="10" max="500" min="1" />
	        	<p class="description">Enter the number of posts you want to generate. Please use at a max of 500</p>
	        </td>
        </tr>

        <tr valign="top">
	        <th scope="row">Posts date range</th>
	        <td>
	        	<label>From</label>
	        	<input type="date" name="wp_dummy_content_generator-post_from" class="wp_dummy_content_generator-post_from"  placeholder="Date Range From" value="<?=date("Y/m/d")?>"/>

	        	<label>To</label>
	        	<input type="date" name="wp_dummy_content_generator-post_to" class="wp_dummy_content_generator-post_to"  placeholder="Date Range To" value="<?=date("Y/m/d")?>" />

	        	<p class="description">Choose the from and to date. The Plugin will pick any random date from this range to use as a post publish date. Useful if you are testing date filters etc.</p>
	        </td>
        </tr>
        <tr valign="top">
	        <th scope="row">Featured Image/Thumbnail</th>
	        <td>
	        	<input type="checkbox" name="wp_dummy_content_generator-thumbnail" />
	        	<p class="description">Check this checkbox if you want to generate the featured image for these dummy posts.</p>
	        </td>
        </tr>
    </table>
    <input class="wp_dummy_content_generator-btn btnFade wp_dummy_content_generator-btnBlueGreen wp_dummy_content_generatorGeneratePosts" type="submit" name="wp_dummy_content_generatorGeneratePosts" value="Generate posts">
</form>
<div class="wrapper dcsLoader wp_dummy_content_generatorCol-3" style="display: none;">
	<div class="wp_dummy_content_generatorLoaderWrpper c100 p0 blue">
		<span class="wp_dummy_content_generatorLoaderPer">0%</span>
		<div class="slice">
			<div class="bar"></div>
			<div class="fill"></div>
		</div>
	</div>
</div>