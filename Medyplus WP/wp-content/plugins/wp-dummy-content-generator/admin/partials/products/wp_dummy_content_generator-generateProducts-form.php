<div class="wp_dummy_content_generator-success-msg" style="display: none;"></div>
<div class="wp_dummy_content_generator-error-msg" style="display: none;"></div>
<form method="post" id="wp_dummy_content_generatorGenProductForm" class="wp_dummy_content_generatorCol-9">
	<input type="hidden" name="action" value="wp_dummy_content_generatorAjaxGenProducts" />
	<input type="hidden" name="remaining_products" class="remaining_products" value="" />
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Number of products</th>
			<td>
				<input type="number" name="wp_dummy_content_generator-product_count" class="wp_dummy_content_generator-product_count"  placeholder="Number of products" value="10" max="500" min="1" />
				<p class="description">Enter the number of products you want to generate. Please use at a max of 500</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Products have Sales price</th>
			<td>
				<input type="checkbox" name="wp_dummy_content_generator-haveSalesPrice" checked />
			<p class="description">Check this checkbox if you want to give a sale price to these dummy products. This is also known as discounted price.</p>
			</td>


		</tr>
		<tr valign="top">
			<th scope="row">All products have same price</th>
			<td>
				<input type="checkbox" name="wp_dummy_content_generator-haveSamePrice" />
			<p class="description">Check this checkbox if you want to keep same price for all the dummy products generated.</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Featured Image/Thumbnail</th>
			<td>
				<input type="checkbox" name="wp_dummy_content_generator-thumbnail" />
				<p class="description">Check this checkbox if you want to generate the featured image for these dummy products.</p>
			</td>
		</tr>
	</table>
	<input class="wp_dummy_content_generator-btn btnFade wp_dummy_content_generator-btnBlueGreen wp_dummy_content_generatorGenerateProducts" type="submit" name="wp_dummy_content_generatorGenerateProducts" value="Generate products">
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