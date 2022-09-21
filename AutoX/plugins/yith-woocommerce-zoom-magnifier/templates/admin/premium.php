<style>
	.landing{
		margin-right: 15px;
		border: 1px solid #d8d8d8;
		border-top: 0;
	}
	.section{
		font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
		background: #fafafa;
	}
	.section h1{
		text-align: center;
		text-transform: uppercase;
		color: #445674;
		font-size: 35px;
		font-weight: 700;
		line-height: normal;
		display: inline-block;
		width: 100%;
		margin: 50px 0 0;
	}
	.section .section-title h2{
		vertical-align: middle;
		padding: 0;
		line-height: normal;
		font-size: 24px;
		font-weight: 700;
		color: #445674;
		text-transform: uppercase;
		background: none;
		border: none;
		text-align: center;
	}
	.section p{
		margin: 15px 0;
		font-size: 19px;
		line-height: 32px;
		font-weight: 300;
		text-align: center;
	}
	.section ul li{
		margin-bottom: 4px;
	}
	.section.section-cta{
		background: #fff;
	}
	.cta-container,
	.landing-container{
		display: flex;
		max-width: 1200px;
		margin-left: auto;
		margin-right: auto;
		padding: 30px 0;
		align-items: center;
	}
	.landing-container-wide{
		flex-direction: column;
	}
	.cta-container{
		display: block;
		max-width: 860px;
	}
	.landing-container:after{
		display: block;
		clear: both;
		content: '';
	}
	.landing-container .col-1,
	.landing-container .col-2{
		float: left;
		box-sizing: border-box;
		padding: 0 15px;
	}
	.landing-container .col-1{
		width: 58.33333333%;
	}
	.landing-container .col-2{
		width: 41.66666667%;
	}
	.landing-container .col-1 img,
	.landing-container .col-2 img,
	.landing-container .col-wide img{
		max-width: 100%;
	}
	.wishlist-cta{
		color: #4b4b4b;
		border-radius: 10px;
		padding: 30px 25px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		width: 100%;
		box-sizing: border-box;
	}
	.wishlist-cta:after{
		content: '';
		display: block;
		clear: both;
	}
	.wishlist-cta p{
		margin: 10px 0;
		line-height: 1.5em;
		display: inline-block;
		text-align: left;
	}
	.wishlist-cta a.button{
		border-radius: 25px;
		float: right;
		background: #e09004;
		box-shadow: none;
		outline: none;
		color: #fff;
		position: relative;
		padding: 10px 50px 8px;
		text-align: center;
		text-transform: uppercase;
		font-weight: 600;
		font-size: 20px;
		line-height: normal;
		border: none;
	}
	.wishlist-cta a.button:hover,
	.wishlist-cta a.button:active,
	.wp-core-ui .yith-plugin-ui .wishlist-cta a.button:focus{
		color: #fff;
		background: #d28704;
		box-shadow: none;
		outline: none;
	}
	.wishlist-cta .highlight{
		text-transform: uppercase;
		background: none;
		font-weight: 500;
	}

	@media (max-width: 991px){
		.landing-container{
			display: block;
			padding: 50px 0 30px;
		}

		.landing-container .col-1,
		.landing-container .col-2{
			float: none;
			width: 100%;
		}

		.wishlist-cta{
			display: block;
			text-align: center;
		}

		.wishlist-cta p{
			text-align: center;
			display: block;
			margin-bottom: 30px;
		}
		.wishlist-cta a.button{
			float: none;
			display: inline-block;
		}
	}
	.mejs-controls {
		display: none !important;
		visibility: hidden !important;
	}
</style>
<div class="landing">
	<div class="section section-cta section-odd">
		<div class="cta-container">
			<div class="wishlist-cta">
				<p><?php echo sprintf (esc_html__('Upgrade to the %1$spremium version%2$s%3$sof %1$sYITH WooCommerce Product Gallery & Image Zoom%2$s to benefit from all features!','yith-woocommerce-zoom-magnifier'),'<span class="highlight">','</span>','<br/>');?></p>
				<a href="<?php echo YITH_YWZM_Plugin_FW_Loader::get_instance()->get_premium_landing_uri(); ?>" target="_blank" class="wishlist-cta-button button btn">
					<?php _e('Upgrade','yith-woocommerce-zoom-magnifier');?>
				</a>
			</div>
		</div>
	</div>

	<div class="section section-even clear" style="background-color: white">
		<h1><?php _e('Premium Features', 'yith-woocommerce-zoom-magnifier');?></h1>

		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php _e('THE BIGGER THE IMAGE, THE MORE PERSUADED THE CLIENT', 'yith-woocommerce-zoom-magnifier');?></h2>
				</div>
				<p><?php _e( 'It is hard to get a sense of the real quality and the details of an online product when you look at it. Because of this, YITH WooCommerce Product Gallery & Image Zoom offers a great tool to perceive the quality of the products with great care.' ) ?></p>
			</div>
			<div class="col-1">
				<img src="https://yithemes.com/wp-content/uploads/2021/09/00.YITH-WooCommerce-Product-Gallery-Image-Zoom-001.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="https://yithemes.com/wp-content/uploads/2021/09/02.YITH-WooCommerce-Product-Image-Zoom-Disable-Zoom.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php _e('Use the exclusion list to disable the zoom option on specific products or categories', 'yith-woocommerce-zoom-magnifier');?></h2>
				</div>
				<p><?php _e( 'Do you want to disable the zoom option on some products or specific categories of your shop and keep it active only on selected products? The Exclusion List bundled in the plugin will grant you the highest flexibility.', 'yith-woocommerce-zoom-magnifier' ) ?></p>
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php _e('Change the main image on click or hover for gallery thumbnails', 'yith-woocommerce-zoom-magnifier');?></h2>
				</div>
				<p><?php _e( 'Since version 2.0, you will find an option to edit the main product image whenever you hover over one of the gallery thumbnails. This solution draws inspiration from Amazon and many other big e-commerce stores for better usability and to spare your customers any extra clicks.', 'yith-woocommerce-zoom-magnifier' ) ?></p>
			</div>
			<div class="col-1">
				<img src="https://yithemes.com/wp-content/uploads/2021/09/05.YITH-WooCommerce-Product-Gallery-Change-Main-Image-At-Hover.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="https://yithemes.com/wp-content/uploads/2021/09/zoom-icon-option.jpg"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php _e('Show a zoom icon on main product image', 'yith-woocommerce-zoom-magnifier');?></h2>
				</div>
				<p><?php _e( 'Enable a glass icon to visually identify the zoom feature available for your images. You can customize colors, size and position of this icon.', 'yith-woocommerce-zoom-magnifier' ) ?></p>
			</div>
		</div>
	</div>


	<div class="section section-cta section-odd">
		<div class="cta-container">
			<div class="wishlist-cta">
				<p><?php echo sprintf (esc_html__('Upgrade to the %1$spremium version%2$s%3$sof %1$sYITH WooCommerce Product Gallery & Image Zoom%2$s to benefit from all features!','yith-woocommerce-zoom-magnifier'),'<span class="highlight">','</span>','<br/>');?></p>
				<a href="<?php echo YITH_YWZM_Plugin_FW_Loader::get_instance()->get_premium_landing_uri()?>" target="_blank" class="wishlist-cta-button button btn">
					<?php _e( 'Upgrade', 'yith-woocommerce-zoom-magnifier' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
