<?php
/*
 Template Name: Community
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

 ?>

 <?php get_header(); ?>

 <div id="content" class="community landing">

 	<!-- start top wrapper -->
 	<div class="community-top-wrapper community-wrapper">

 		<!-- start left-section -->
 		<div class="left-section-community forum-wrap comm-general-wrap">


 			<div class="text-inner">
 				<h2>
                    <?php if(!is_premium()) : ?>
						<a href="<?php echo get_page_link('156'); ?>">Forum</a>
					<?php else : ?>
						<a href="<?php echo get_the_permalink($forumID); ?>">Forum</a>
                    <?php endif; ?>
                </h2>
 			</div>

 		</div>
 		<!-- end left-section -->

 		<!-- start right-section -->
 		<div class="right-section-community support-wrap comm-general-wrap">

 			<div class="text-inner">

				<h2>
                    <?php if(!is_premium()) : ?>
						<a href="<?php echo get_page_link('156'); ?>">Support Groups</a>
					<?php else : ?>
						<a href="<?php echo get_the_permalink($supportID); ?>">Support Groups</a>
                    <?php endif; ?>
                </h2>

 			</div>

 		</div>
 		<!-- end right-section -->

 	</div>
 	<!-- end top wrapper -->

 	<!-- start bot wrapper -->
 	<div class="community-bottom-wrapper community-wrapper">

 		<!-- start left-section -->
 		<div class="left-section-community photo-wrap comm-general-wrap">


 			<div class="text-inner">
 			<h2>
                    <?php if(!is_premium()) : ?>
						<a href="<?php echo get_page_link('156'); ?>">Pictures from Friends</a>
					<?php else : ?>
						<a href="<?php echo page('gallery') ?>">Pictures from Friends</a>
                    <?php endif; ?>
                </h2>
 			</div>

 		</div>
 		<!-- end left-section -->


 		<!-- start right-section -->
 		<div class="right-section-community meetings-wrap comm-general-wrap">

 			<div class="text-inner">

 				<h2>
                    <?php if(!is_premium()) : ?>
						<a href="<?php echo get_page_link('156'); ?>">Get-togethers</a>
					<?php else : ?>
						<a href="<?php echo get_the_permalink($meetingsID); ?>">Get-togethers</a>
                    <?php endif; ?>
                </h2>

 			</div>

 		</div>
 		<!-- end right-section -->

 	</div>
 	<!-- start bot wrapper -->

 </div>
</div>
<?php wp_footer(); ?>
</body>
</html> <!-- end of site. what a ride! -->
