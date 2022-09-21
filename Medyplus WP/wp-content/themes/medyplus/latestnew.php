<div class="latest-news container-fluid">
	<div class="container">
		<div class="title">
			<div class="text-bg">
				<span><?php echo get_post_meta( $post->ID, 'big_title_hot_news', true ); ?></span>
				<h2 class="black"><?php echo get_post_meta( $post->ID, 'small_title_hot_news', true ); ?></h2>
			</div>
		</div>

		<div class="item-groups">
			<div class="item">
				<?php
				$args 	= array(
				    'post_type' 		=> 'post',
				    'posts_per_page'	=> 3,
				    'order' 			=> 'asc'
				);

				$the_query 				= new WP_Query( $args );

				if ( $the_query->have_posts() ):
					$countImg 			= 0;
				    while ( $the_query->have_posts() ):
				    	$the_query->the_post();
				    	$countImg+=1;
				?>
				<div class="item-<?php echo $countImg; ?> item-show">
					<?php 
						the_post_thumbnail(); 
					?>
					<div class="description">
						<a href="#">
							<span><?php echo $post->post_title; ?></span>
						</a>
					</div>

					<div class="date">
						<span>14 June 2018 by <span class="author"><?php echo the_author_meta('user_nicename', $post->post_author);; ?></span></span>
					</div>
				</div>
				<?php
				endwhile;
				endif;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
</div>