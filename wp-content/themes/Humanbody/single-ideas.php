<?php
global $post;
$featuredImage = featured_image($post, 'spinal-full-featured');

$postMeta = get_post_meta($post->ID);
$featuredAlign = $postMeta['align-featured'][0];
$pageTemplates = get_all_page_templates();
$url = $_GET['s'] ? get_site_url().'?s='.$_GET['s'] : $pageTemplates['page-blog.php']['url'];


$category = get_the_category();
$meta = get_term_meta($category[0]->cat_ID);
$color1 = $meta['color1'][0];
$color2 = $meta['color2'][0];
$cat_image = $meta['image'][0];

$featured_image = featured_image($post, 'large');

$link = get_the_permalink($post->ID);
$title = get_the_title().' | '.get_bloginfo( 'name');
$image = $featured_image;

?>

<div id="content" class="ideas secondary-subpage">

	<!-- Start top-banner-wrapper -->
	<div class="top-banner-wrapper" style='background-image:url(<?php echo $featured_image ?>)'>
		<div class="what-can-top-inner">
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
						<h1><?php the_title() ?></h1>

						<p>
							<?php short_description() ?>
						</p>

					</div>
					<!-- End description wrapper -->
					<!-- start social wrapper -->
						<div class="social-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<ul class=" clearfix">
								<li><a href="<?php echo "https://www.facebook.com/sharer/sharer.php?u=$link" ?>" class="fb social-share js-share"><i class="fa fa-facebook"></i>Share</a></li>
								<li><a href="<?php echo "https://twitter.com/home?status=".get_the_title() ?> <?php echo $link ?>" class="tw social-share js-share"><i class="fa fa-twitter"></i>Tweet</a></li>
								<li><a href="<?php echo "https://pinterest.com/pin/create/button/?url=$link&media=$image&description=$title" ?>" class="pt social-share js-share"><i class="fa fa-pinterest-p"></i>Pin it</a></li>
								<li><a href="<?php echo "https://plus.google.com/share?url=$link" ?>" class="gl social-share js-share"><i class="fa fa-google-plus"></i>Google <span>+</span></a></li>
								<li><a href="<?php echo "http://tumblr.com/widgets/share/tool?canonicalUrl=$link" ?>" class="tb social-share js-share"><i class="fa fa-tumblr"></i>Tumblr</a></li>
							</ul>
						</div>
						<!-- end social wrapper -->

				</div>
				<!-- End row -->
			</div>
			<!-- End container -->
		</div>
	</div>
	<!-- End top-banner-wrapper -->

	<!-- start main content -->
	<div class="container main-content">

		<?php echo do_shortcode('[banner]') ?>

		<div class="row">
			<div class="col-lg-12  col-md-12  col-sm-12 col-xs-12">
				<!-- start body wrap -->
				<div class="body-wrap text-center cf">
					<?php the_content() ?>
				</div>
				<!-- end body wrap -->
			</div>
			<!-- start article nav wrap -->
			<div class="article-nav-wrapper clearfix">

				<!-- Start the Loop. -->
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 pull-left">
					<div class="article-nav-holder prev-article-holder">
						<?php previous_post_link( '%link', '<i class="fa fa-angle-left"></i>previous article', TRUE ); ?>
					</div>	

				</div>
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 pull-right">
					
					<div class="article-nav-holder next-article-holder">
					<?php next_post_link( '%link', 'next article <i class="fa fa-angle-right"></i>', TRUE ); ?>
					</div>	
				</div>
				<?php endwhile; else : ?>
				<?php endif; ?>



			</div>
			<!-- end article nav wrap -->
		</div>
		<?php include_once('comments.php') ?>
	</div>
	<!-- end main content -->
</div>
