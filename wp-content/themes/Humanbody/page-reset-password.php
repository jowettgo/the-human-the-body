<?php
//$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/*
 Template Name: Reset Password
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

$reset = new spinal_reset_password();
$message = $reset->reset();
$cookie = $reset->get_cookie();
/* set up the login and key reset data as vars */
list($rp_login, $rp_key) = explode( ':', $cookie, 2 );
// error_reporting(E_ALL); ini_set("display_errors", 1);



/* ----------------------------------------------------------------------------------- */
/* redirect to community if user is logged in, no recovery is needed if the user is logged in */
login_redirect(page('my-account'));
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
					<p>
						<?php

						if(count($message) > 0) :
							foreach ($message as $mes) :
									echo $mes.'<br/>';
							endforeach;
						endif;
						 ?>
					</p>
				</div>
				<!-- End description wrapper -->

				<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">

					<div class="form-wrapper">
						<!-- start form -->


						<form name="resetpassform" id="resetpassform" action="?action=resetpass" method="post" autocomplete="off" class="sing-up-general">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-sm-offset-3 col-md-offset-3">
									<input type="hidden" id="user_login" value="<?php echo esc_attr( $rp_login ); ?>" autocomplete="off" />

									<label for="pass1"><?php _e('New password') ?></label><br />
									<input type="password" data-reveal="1" data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" name="pass1" id="pass1" size="20" value="" autocomplete="off"/>

									<label for="pass2"><?php _e('Confirm new password') ?></label><br />
									<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />

									<?php
									/**
									 * Fires following the 'Strength indicator' meter in the user password reset form.
									 *
									 * @param WP_User $user User object of the user whose password is being reset.
									 */
									///do_action( 'resetpass_form', $user );
									?>
									<input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>" />
									<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Reset Password'); ?>" /></p>
								</div>


							</div>


						</form>

						<!-- end form -->
					</div>
				</div>
				<div class="col-md-12">
					<div class="term-outer-wrapper">
						<div class="terms-wrapper clearfix">
							<a href="<?php echo page('recover-password') ?>" class="forgot-password"><i class="fa fa-question-circle"></i>Forgot your password?</a>
							<a href="<?php echo page('sign-in') ?>" class="forgot-password"><i class="fa fa-question-circle"></i>Sign in</a>
						</div>
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
