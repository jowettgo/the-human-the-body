<?php
/*
 Template Name: Humans Landing
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
$catPeople = get_category_by_slug( 'people' );
$catFamousPeople = get_category_by_slug( 'famous-people' );
$catpeople_url = get_term_link($catPeople );
$catfamouspeople_url = get_term_link($catFamousPeople );
 ?>

 <?php get_header(); ?>
 <div id="content" class="humans landing">

	<!-- start left-section -->
	<div class="left-section">

		<div class="text-inner">
			<div class="text-wrapper">
				<h2><a href="<?php echo $catpeople_url ?>">People</a></h2>
				<p>
					<!-- <?php echo $catPeople->description ?> -->
					Every person has dealt with at least one physically or psychologically difficult moment at one point in their life. Many have come out victorious. Yet, victory does not necessarily mean getting healed, but also learning to live with an affection. In this section, we would like to share your personal stories.
				</p>
			</div>
		</div>

	</div>
	<!-- end left-section -->

	<!-- start right-section -->
	<div class="right-section">

		<div class="text-inner">
			<div class="text-wrapper">
				<h2><a href="<?php echo page('famous-people') ?>">Famous People</a></h2>
				<p>
					We all inhabit the same world and deal with issues similar<br /> in nature. There are famous people who have had<br /> the courage to take the world’s stage and voice their<br /> struggles about their challenging medical conditions.<br /> These are their stories.
				</p>
			</div>
		</div>

	</div>
	<!-- end right-section -->
</div>

<?php wp_footer(); ?>
</body>
</html> <!-- end of site. what a ride! -->
