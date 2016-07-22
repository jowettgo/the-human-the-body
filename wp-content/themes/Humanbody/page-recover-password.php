<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/*
 Template Name: recover-password
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


if($_POST['email']) :
	$reset  = new spinal_recover_password();
	$messages = $reset->send_mail($_POST['email']);
endif;

/* ----------------------------------------------------------------------------------- */
/* redirect to community if user is logged in, no recovery is needed if the user is logged in */
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
					<h1 style="font-size: 56px;"><?php the_title() ?></h1>
				</div>
				<!-- End description wrapper -->

				<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">

					<div class="form-wrapper">
						<!-- start form -->
						<form method="post" class="sing-up-general">

							<div class="row">
								<div class="cold-md-12">
									<p>
										
									</p>
								</div>
								<div class="col-md-6 col-sm-6 col-sm-offset-3 col-md-offset-3">
									<label for="email">Email</label>
									<?php do_action( 'login_form', 'resetpass' ); ?>
									<input type="text" name="email" value="" size="40" id="user_login" tabindex="<?php bbp_tab_index(); ?>" />
								</div>


							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="term-outer-wrapper">
										<div class="terms-wrapper clearfix">
											<a href="<?php echo page('sign-in') ?>" class="forgot-password"><i class="fa fa-sign-in"></i>Sign In</a>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<input type="submit" value="<?php _e( 'Reset My Password', '' ); ?>" name="user-submit">
									<?php bbp_user_lost_pass_fields(); ?>
								</div>

							</div>

						</form>
						<!-- end form -->
					</div>
				</div>
			</div>
			<!-- End row -->
		</div>
		<!-- End container -->
	</div>
	<!-- End top-banner-wrapper -->

</div>
<?php get_footer() ?>
