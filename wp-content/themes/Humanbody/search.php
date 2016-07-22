<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/* Category logic
----------------------------------------------------------------------------- */
global $wp_query;

/* category specific */
$category = get_category_by_slug($wp_query->query['category_name']);

/* category meta options custom implemented */
$meta = get_term_meta($category->cat_ID);
// category image
$image = $meta['image'][0];
// category colors
$color1 = $meta['color1'][0];
$color2 = $meta['color2'][0];

$colortext = $meta['color-article-text'][0];
/* category meta options */

/* End Category logic
----------------------------------------------------------------------------- */
?>
<?php get_header() ?>

<div id="content" class="science">

	<!-- Start top-banner-wrapper -->
	<div class="top-banner-wrapper" style="background-image: url('<?php echo $image; ?>')">
		<div class="category-color" style="background: <?php echo $color1 ?>;
			background: -moz-linear-gradient(-45deg, <?php echo $color1 ?> 0%, <?php echo $color2 ?> 100%);
			background: -webkit-linear-gradient(-45deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
			background: linear-gradient(135deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color1 ?>', endColorstr='<?php echo $color2 ?>',GradientType=1 );">
		</div>
		<div class="container">
			<!-- Start Bredcrumb -->
			<div class="breadcrumb-wrapper">

				<ol vocab="http://schema.org/" typeof="BreadcrumbList">
					<?php //spinal_breadcrumb() ?>
				</ol>

			</div>
			<!-- End Bredcrumb -->

			<div class="row">
				<!-- Start description wrapper -->
				<div class="description-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h1><?php echo $category->name; ?></h1>
					<p>
						<?php echo $category->description ?>
					</p>
				</div>
				<!-- End description wrapper -->

				<!-- Start search wrapper -->
				<div class="search-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<form class="searchbox" action="<?php echo get_term_link($category) ?>" method="GET">
						<input type="text" name="s" placeholder="Search for articles...">
						<input type="submit">
					</form>
				</div>
				<!-- End search wrapper -->
			</div>
			<!-- End row -->
		</div>
		<!-- End container -->
	</div>
	<!-- End top-banner-wrapper -->

	<!-- start main content -->
	<div class="main-content-wrapper container">
		<div class="row">
			Search resulsts for <?php the_search_query() ?>
		</div>


		<div class="row first-article-row">
			<?php
			/* Articles loop
			--------------------------------------------------------------------------------- */


			/* make the post loop as long as we have posts */
			$args = $wp_query->query_vars;
			$blog = new WP_Query($args);

			if ($blog->have_posts()) : while ($blog->have_posts()) : $blog->the_post();
				/* post title */
				$title = get_the_title();
				/* post url */
				$url = get_the_permalink();
				/* search capability return to search list */
				if($_GET['s']) :
					$url = $url.'?s='.$_GET['s'];
				endif;
				/* post image */
				$image =  featured_image($post, 'spinal-thumb-blog');
				//wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

				$image = $image ? $image : false;
				/* post excerpt */
				$excerpt = substr(get_the_excerpt(), 0, 84);
				/* post date */
				$date = get_the_time('d/m/y');

				/* setup our link,, option in admin for custom linked article support */
				$postMeta = get_post_meta($post->ID);
				$customMetaLink = $postMeta['linked-post'][0];
				$link = $customMetaLink ? $customMetaLink : $url;
					/* Base article info template, its colored according to the category it belongs to
					-------------------------------------------------------------------------------- */
					 ?>
					<!-- start aticle 1 -->
					<div class="article-small  col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<div class="inner-wrap">
							<img src="<?php echo featured_image($post, 'spinal-thumb-blog') ?>" alt="img">
							<h4><a href="<?php the_permalink() ?>" style="color:<?php echo $colortext ?>"><?php the_title() ?></a></h4>
							<p>
								<?php echo excerpt_size(150) ?>
							</p>
							<a href="<?php the_permalink() ?>" class="read-more" style="color:<?php echo $colortext ?>"><i class="fa fa-angle-right" style="background:<?php echo $colortext ?>"></i>Read more</a>
						</div>
					</div>
					<!-- end aticle 1 -->
					<?php
					/* END Base article info template
					-------------------------------------------------------------------------------- */
					 ?>
			 <?php

					 endwhile;
				 endif;
				 /* END Articles loop
	 			--------------------------------------------------------------------------------- */
			  ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
