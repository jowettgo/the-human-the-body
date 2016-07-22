<?php
/*
 Template Name: Ideas Landing
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

/* category specific */


$forumID = get_page_by_path('forum','OBJECT', 'forum')->ID;
$supportID = get_page_by_path('support-group', 'OBJECT', 'forum')->ID;
$meetingsID = get_page_by_path('meetings', 'OBJECT', 'forum')->ID;
$catdone = get_category_by_slug( 'what-had-been-done' );
$catdo = get_category_by_slug( 'what-you-can-do' );
$catdone_url = get_term_link($catdone );
$catdo_url = get_term_link($catdo );
$category = get_category_by_slug($slug);
 ?>

 <?php get_header(); ?>
 <div id="content" class="landing ideas">

	<!-- start left-section -->
	<div class="left-section">

		<div class="text-inner">
			<div class="text-wrapper">
				<h2><a href="<?php echo $catdone_url ?>">What has been done</a></h2>
				<p><?php echo $catdone->category_description ?></p>
			</div>
		</div>

	</div>
	<!-- end left-section -->

	<!-- start right-section -->
	<div class="right-section">

		<div class="text-inner">
			<div class="text-wrapper">
				<h2><a href="<?php echo page('what-you-can-do') ?>">What would you do?</a></h2>
				<p>Anyone can come up with a bright idea. More often<br /> than not such bright ideas get discussed with friends<br /> over a pint of beer and are later forgotten. In this section you are free to post any idea that you propose and think is worthy and capable of making the world a better place. Here you can check up on other people’s ideas as well.</p>
			</div>
		</div>

	</div>
	<!-- end right-section -->
</div>

    <?php wp_footer(); ?>
        </div>
    </body>
</html> <!-- end of site. what a ride! -->
