<?php
/* Category logic
----------------------------------------------------------------------------- */
global $wp_query;
$slug = $wp_query->query_vars['category_name'];
/* category specific */
$category = get_category_by_slug($slug);
if($slug == 'famous-people') {
	//wp_redirect(page('humans'));
}
/* category meta options custom implemented */
$meta = get_term_meta($category->cat_ID);
// category image
$image = $meta['image'][0];
// category colors
$color1 = $meta['color1'][0];
$color2 = $meta['color2'][0];

$colortext = $meta['color-article-text'][0];
$banner1_img = get_term_meta($category->cat_ID,'ba_banner1_image');
$banner1_link = get_term_meta($category->cat_ID,'ba_banner1_link');
$banner2_img = get_term_meta($category->cat_ID,'ba_banner2_image');
$banner2_link = get_term_meta($category->cat_ID,'ba_banner2_link');

/* category meta options */

/* End Category logic
----------------------------------------------------------------------------- */
?>

<?php
get_header();
?>
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
					<?php spinal_breadcrumb() ?>
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
			<?php
			$args = array(
				'post_type' => 'post',
				'posts_per_page' => 3,
				'order_by' => 'date',
				'order' => 'DESC',
				'meta_query' => array(
					array(
						'key'     => 'post-featured',
						'value'   => 'on',
					),
				),
				'category__in' => $category->cat_ID

			);
			$articles = new WP_Query($args);

			if ($articles->have_posts()) :
				$n=1;
				while ($articles->have_posts()) :
					$articles->the_post();
			 ?>
			<!-- start aticle 1 -->
			<div class="article  article-<?php echo $n ?> col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="inner-wrap" style="background-image:url('<?php echo featured_image($post, 'spinal-thumb-blog-featured') ?>')">
						<div class="filter-blog-featured">
							<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
							<span class="separator"></span>
							<p>
								<?php echo excerpt_size(200) ?>
							</p>
							<a href="<?php the_permalink() ?>"  class="read-more"><i class="fa fa-angle-right"></i>Read more</a>
						</div>
					</div>
			</div>
			<!-- end aticle 1 -->
			<?php
				$n++;
				endwhile;
			endif;
			 ?>


		</div>

		<div class="article-banner ">
			<?php 
				if(!empty($banner1_img[0]['url'])){ ?>
					<a target="_blank" style="background: url(<?php echo $banner1_img[0]['url'] ?>);" href="<?php echo $banner1_link[0] ?>"></a>
				<?php }
			?>
			<?php 
				if(!empty($banner2_img[0]['url'])){ ?>
					<a target="_blank" style="background: url(<?php echo $banner2_img[0]['url'] ?>);" href="<?php echo $banner2_link[0] ?>"></a>
				<?php }
			?>
		</div>

		<div class="article-separator">
			<h5 class="divider">more articles</h5>
		</div>

		<div class="row first-article-row load-articles-ajax">
			<?php ajax_load_more_posts($category->cat_ID) ?>
		</div>

		<div class="clearfix category-load-more">
			<button class="load-more" data-c='<?php echo $category->cat_ID ?>'><i class="fa fa-spinner"></i>Load More</button>
		</div>

	</div>
	<!-- end main content -->
</div>
<?php
get_footer();
 ?>
