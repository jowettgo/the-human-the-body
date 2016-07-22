<?php
/*
 Template Name: Celebrities Landing
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
$cat1 = get_category_by_slug( 'past' );
$cat2 = get_category_by_slug( 'present' );
if($cat1) :
    $cat1_url = get_term_link($cat1 );
endif;
if($cat2) :
    $cat2_url = get_term_link($cat2 );
endif;
 ?>

 <?php get_header(); ?>


     <div id="content" class="celebrities landing">

     	<!-- start left-section -->
     	<div class="left-section">

     		<div class="text-inner">
     			<div class="text-wrapper">
     				<h2><a href="<?php echo $cat1_url ? $cat1_url : '' ?>"><?php echo str_replace('-', '-<br/>',$cat1->name) ?></a></h2>
     			</div>
     		</div>

     	</div>
     	<!-- end left-section -->

     	<!-- start right-section -->
     	<div class="right-section">

     		<div class="text-inner">
     			<div class="text-wrapper">
     				<h2><a href="<?php echo $cat2_url ? $cat2_url : '' ?>"><?php echo str_replace('-', '-<br/>',$cat2->name) ?></a></h2>
     			</div>
     		</div>

     	</div>
     	<!-- end right-section -->
     </div>

<?php wp_footer(); ?>
</body>
</html> <!-- end of site. what a ride! -->
