<?php
/*
 Template Name: Blog
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
global $post;


$featuredImage = featured_image($post, 'spinal-full-900');
/* make the post loop as long as we have posts */

$catID = get_category_by_slug('news')->cat_ID;
$args = array(
	'post_type' => 'post',
	'tag' => $tags ? $tags : get_query_var('tag'),
	'paged' => get_query_var('paged'),
	'category__not_in' => $catID
	//'posts_per_page' => $wp_query->max_num_pages
);
$posts = new WP_Query($args);

?>

<?php get_header(); ?>
				<div id="main">
					<!--Static Headline -->
					<section class="headline">
						 <div class="static-header" style="background-image: url(<?php echo $featuredImage ?>);">
							 <div class="container">
								 <div class="row">
									 <h2><?php the_title() ?></h2>
								 </div>
							 </div>
						 </div>
					</section>
					<!-- End Static headline -->

					<!-- Breadcrumbs -->
					<div class="container">
						 <div class="row">
							 <div class="breadcrumbs">
								 <ul>
									 <?php echo spinal_breadcrumb(); ?>
								 </ul>
							 </div>
						 </div>
					 </div>
					 <!-- End breadcrumbs -->


					 <div class="container">
						<div class="row">
							<div class="page-content cf">
								<div class="page-content-wrapper">
									<!-- heading-section	 -->
									<div class="heading-section">
										<!-- <h3>Archive 2-Anywhere</h3> -->
										<?php
										loop_posts($posts)
										?>
									</div>
									<!-- end heading-section -->


								</div>
								<!-- end page-content-wrapper -->
							</div>
							<!-- end page-content -->
						</div> <!-- end row -->
					 </div> <!-- end container -->
				</div>






	<!-- / content -->
<?php get_footer(); ?>
