<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/*
 Template Name: Gallery
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
    'post_type' => 'galleries',
    'posts_per_page' => -1,
    'order_by' => 'date',
    'order' => 'DESC'
);
$galleries = new WP_Query($args);
//$last = $galleries->posts[count($galleries->posts)-1];
$first = $galleries->posts[0];


//$lastMeta = get_post_meta($last->ID);
//$firstMeta = get_post_meta($first->post_title);

function getDateHuman($timestamp)
{
    $day = (int)date('j', $timestamp);
    switch ($day) {
        case 1:
            $termination = 'st';
            break;
        case 2:
            $termination = 'nd';
            break;
        case 3:
            $termination = 'rd';
            break;
        default:
            $termination = 'th';
            break;
    }
    return 'opened on '.date('j', $timestamp) . "<sup>$termination</sup> of " .date("F Y", $timestamp);
}

?>

 <?php get_header(); ?>

 <!-- start content -->
 <div id="content" class="community-subpage gallery">

 	<!-- start gallery section  -->
 	<div class="gallery-section">
 		<!-- start gallery top inner -->
 		<div class="gallery-top-inner">
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

 						<div class="title-divider">
 							<h5 class="divider"><?php echo getDateHuman(strtotime($first->post_date)) ?></h5>
 						</div>

 						<h1>
                            This week’s theme is: <?php echo get_the_title($first) ?>
 						</h1>
 						<p>We will occasionally announce a theme on the basis of which a picture gallery will be put together with the help of photos collected from you. Let's focus on primarily creating a positive, supportive, and overall friendly space for everybody (fun and good-vibes included).</p>

 						<a href="<?php echo get_the_permalink($first->ID) ?>" class="view-gallery">Current gallery</a>

 					</div>
 					<!-- End description wrapper -->
                    <div class="col-lg-12">
                        <form class="searchbox" action="#">
								<input type="text" id="search-gallery" placeholder="Search for gallery...">
								<input type="submit">
							</form>
                    </div>
 				</div>
 			</div>
 		</div>
 		<!-- end gallery top inner -->

 		<!-- start gallery top inner -->
 		<div class="gallery-bottom-inner">

 			<!-- start gallery slideshow -->
 			<div class="galley-slideshow">
 				<?php

                if($galleries->have_posts()) :

                    while($galleries->have_posts()) :
                        $galleries->the_post();
                        $meta = get_post_meta($post->ID);
                        $photos = unserialize($meta['photos'][0]);
                        $i = 0;
                        if(isset($photos[0])) {
                            foreach ($photos as $photo) {
                                if($photo['approve'] == 'on') :
                                    $i++;
                                endif;
                            }
                        }
                        $count = $i;
                        $userinfo = new user_info();
                        $created = $userinfo->get($post->post_author);
                        $name = $created['name'];
                        $timestamp = strtotime($post->post_date);
                 ?>
 				<div>
 					<div class="slide-inner">
 						<div class="slide-inner-wrap">
 							<img src="<?php echo featured_image($post) ?>" alt="slide">
 							<div class="slide-link">
 								<div class="link-inner">
 									<span class="post-count"><?php echo $count ?></span>
 									<a href="<?php the_permalink() ?>" class="view-gallery"><i class="fa fa-eye"></i>View Gallery</a>
 									<div class="slide-title-wrapper">
 										<h3><?php the_title() ?></h3>
 										<div class="uploaded-by">
 											<?php echo getDateHuman($timestamp); ?>
 										</div>
 									</div>
 								</div>
 							</div>
 						</div>
 					</div>
 				</div>
                <?php
            endwhile;
        endif;
                    ?>
 			</div>
 			<!-- end gallery slideshow -->

 			<div class="drag-to-navigate"></div>
 		</div>
 		<!-- end gallery bottom inner -->
 	</div>
 	<!-- end gallery section  -->

 </div>
 <!-- end content -->



<?php get_footer(); ?>
