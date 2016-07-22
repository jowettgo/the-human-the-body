<?php
$_GET   = filter::get();
$_POST  = filter::post();
/*
 Template Name: Activate account
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

/* ----------------------------------------------------------------------------------- */
/* redirect to community if user is logged in */
login_redirect(page('community'));
/* ----------------------------------------------------------------------------------- */


?>
<?php get_header() ?>
<div id="content" class="secondary-subpage sing-in">

	<!-- Start top-banner-wrapper -->
	<div class="top-banner-wrapper" style="height: 518px;">

		<div class="container">
			<!-- Start Bredcrumb -->
			<div class="breadcrumb-wrapper clearfix">

				<ol vocab="http://schema.org/" typeof="BreadcrumbList">
					<?php spinal_breadcrumb() ?>
				</ol>

			</div>
			<!-- End Bredcrumb -->

			<div class="row">
				<!-- Start description wrapper -->
				<div class="description-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h1><?php the_title() ?></h1>

						<?php
							$user_id = intval($_GET['u']);
							if ( $user_id ) {
								// get user meta activation hash field
            					$code_from_db = get_user_meta( $user_id, 'has_to_be_activated', true );

            					if($code_from_db == $_GET['key']) {
            						//activate account
            						delete_user_meta( $user_id, 'has_to_be_activated' );
            						the_content();
            						
            					} else {
            						wp_redirect( home_url() ); 
									exit;
            					} 
							} else {
								wp_redirect( home_url() ); 
								exit;
							}	
						?>
						<?php $login_page = page('sign-in'); ?>
						<br>
						<div style="margin:0 auto; width: 170px;">
							<a class="view-area" style="text-align:center; padding:0 20px; max-width: 100%" href="<?php echo $login_page; ?>">Go to login page</a>
						</div>	
						

				</div>
				<!-- End description wrapper -->


			</div>
			<!-- End row -->
		</div>
		<!-- End container -->
	</div>
	<!-- End top-banner-wrapper -->

</div>
<?php get_footer() ?>
