<?php $pageTemplates = get_all_page_templates(); ?>
<?php //$theme_options = spinal_get_theme_option(); ?>

<?php get_header(); ?>


<!-- start left-section -->
<div class="left-section">

	<!-- start video -->
	<video autoplay muted loop  id="bgvid"><source src="<?php echo get_stylesheet_directory_uri() ?>/library/video/tekapo_starlight.mp4" type="video/mp4"></video>
	<!-- end video -->
    <img src="<?php echo get_stylesheet_directory_uri() ?>/library/video/mobile.jpg" alt="Human body" class="hidden" id="mobile-video-placeholder"/>
	<div class="text-inner">
		<div class="text-wrapper">
			<h2><a href="<?php echo bloginfo('url').'/articles/consciousness/' ?>">The Human</a></h2>
			<p>
				Each of us is a ray of light striving for itself, but failing to remember we are all parts of the same source. Our mind and life circumstances are only external layers enclosing the unaffected essence, which is what we truly are. Above Good or Bad, life itself, that exists, and always will.
			</p>

		</div>

	</div>
    <p class="notice-funds text-wrapper">
        This site is (co-)financed from the European Social Fund through the Sectorial Operational Programme for Human Resources Development 2007-2013 – Project ID 150300 (NewBiz)
    </p>
	<img src="<?php echo _IMG_ ?>bgi/human_left.png" alt="body">

</div>
<!-- start sign-up box -->
<!-- <div class="sign-up-section">
	<div class="sign-up-box">
		<div class="inner-box">
			<a href="<?php echo page('sign-up') ?>" class="sign-up"><?php if(!logged_in()) : ?>Sign up <?php endif; ?></a>
		</div>
	</div>
</div> -->
<!-- end sign-up box -->

<!-- start right-section -->
<div class="right-section">

	<div class="text-inner">
		<div class="text-wrapper">
			<h2><a href="<?php echo bloginfo('url').'/articles/science/' ?>">The Body</a></h2>
			<p>
				The temporary shelter of the consciousness that somehow manages to trick many of us into thinking it is our actual self. Source of much pleasure and pain, it works as a device that helps us explore the world. We can only own one at a time, it often breaks and in some cases permanently crashes without warning.
			</p>
		</div>
	</div>

	<img src="<?php echo _IMG_ ?>bgi/body.png" alt="body">

</div>
<!-- end right-section -->
<?php get_footer(); ?>
