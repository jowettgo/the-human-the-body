<?php
/* purge everything from magic quotes to xss atacks */

$_GET = filter::get();
$_POST = filter::post();
/*
 Template Name: Sign-in
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


/* check if we have the user password posted from the sign in form */
/* ----------------------------------------------------------------------------------- */
/* redirect to community if user is logged in */
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
					<h1><?php the_title() ?></h1>
					<span style="color:#fff;width:100%;display:block;margin-bottom:10px;font-size:12px;" class="text-center"><?php echo $message ?></span>
				</div>
				<!-- End description wrapper -->

				<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">

					<div class="form-wrapper">
						<!-- start form -->
						<form method="post" class="sing-up-general">
							<div class="row">

								<div class="col-md-6 col-sm-6">
									<label for="email">Email or Username</label>
									<?php $fnc = check_user_login($_POST['logID']); ?>
									<input <?php echo !empty($_POST) && empty($fnc)?'class="invalid-input"':''; ?> type="text" name="logID" value="<?php echo check_user_login($_POST['logID']) ?>" size="20" id="email" tabindex="<?php bbp_tab_index(); ?>" />
								</div>
								<div class="col-md-6 col-sm-6">
									<label for="password">Password</label>
									<?php $fnc2 = bbp_sanitize_val( 'user_pass', 'password' ); ?>
									<input <?php echo !empty($_POST) && empty($fnc2)?'class="invalid-input"':''; ?> type="password" name="password" value="<?php bbp_sanitize_val( 'user_pass', 'password' ); ?>" size="20" id="password" tabindex="<?php bbp_tab_index(); ?>" />
								</div>

							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="term-outer-wrapper">
										<div class="terms-wrapper clearfix">
											<a href="<?php echo page('recover-password') ?>" class="forgot-password"><i class="fa fa-question-circle"></i>Forgot your password?</a>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<?php do_action( 'login_form' ); ?>
									<input type="submit" value="Sign in">
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
