<?php
/*
 Template Name: Body Landing
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
$featuredImage = featured_image($post, 'spinal-full-featured');
$postMeta = get_post_meta($post->ID);
$featuredAlign = $postMeta['align-featured'][0];
$pageTemplates = get_all_page_templates();

$forumID = get_page_by_path('forum','OBJECT', 'forum')->ID;
$supportID = get_page_by_path('support-group', 'OBJECT', 'forum')->ID;
$meetingsID = get_page_by_path('meetings', 'OBJECT', 'forum')->ID;
$catdone = get_category_by_slug( 'what-had-been-done' );
$catdo = get_category_by_slug( 'what-you-can-do' );
$catdone_url = get_term_link($catdone );
$catdo_url = get_term_link($catdo );


$args = array(
    'post_type' => 'affection',
    'posts_per_page' => -1,
    'order_by' => 'date',
    'order' => 'ASC'
);
$affections = new WP_Query($args);
 ?>

<?php get_header(); ?>

<!-- start content -->
<div id="content" class="community-subpage afectiuni">

	<!-- start gallery section  -->
	<div class="gallery-section afectiuni-section">

		<!-- start gallery slideshow -->
		<div class="afectiuni-slideshow">
			<?php

                if($affections->have_posts()) :
                    $i = 0;
                    while ($affections->have_posts()) :
                        $affections->the_post();


                        $excerpt = substr(strip_tags($meta['description'][0]), 0, 120 );
                        $title = get_the_title($post->ID);
						$meta = get_post_meta($post->ID);
                        $image = featured_image($post, 'spinal-affections');
                         ?>
            			<div>
            				<div class="slide-inner" data-index="<?php echo $i ?>">
            					<div class="slide-inner-wrap">
            						<img src="<?php echo $image ?>" alt="<?php echo $title ?>">
            						<div class="slide-link">
            							<div class="link-inner">
            								<a href="<?php the_permalink() ?>" class="view-details">View Details <i class="fa fa-angle-right"></i></a>
            								<div class="slide-title-wrapper">
            									<h3 <?php echo isset($meta['text-color'][0])?'style="color: '.$meta['text-color'][0].';text-shadow: 1px 2px #444;"':''; ?>><?php the_title() ?></h3>
            									<?php if(1 == 60): ?>
            									<div <?php echo isset($meta['text-color'][0])?'style="color: '.$meta['text-color'][0].'"':''; deg($meta);?> class="text-body">

            										<?php echo $excerpt ?>...
            									</div>
            									<?php endif; ?>
            								</div>
            							</div>
            						</div>
            					</div>
            				</div>
            			</div>
                        <?php
                        $i++;
                        endwhile;
                    endif;
                ?>
        </div>
            		<!-- end gallery slideshow -->

		<div class="drag-to-navigate">
			<div class="container">
				<div class="row">
					<!-- Start search wrapper -->
					<div class="search-wrapper col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
						<form class="searchbox" id="search-affection-form">
							<input type="text" id="search-affection" placeholder="Search for medical conditions...">
							<input type="submit">
						</form>
					</div>
					<div id="number-of-items" style="margin: 0 auto; width: 300px; text-align: center;">
					</div>
					<!-- End search wrapper -->

				</div>

			</div>
		</div>

	</div>
	<!-- end gallery section  -->

</div>
<!-- end content -->
<?php get_footer() ?>
