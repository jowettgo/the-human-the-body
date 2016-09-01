<?php
$_GET   = filter::get();
$_POST  = filter::post();
/*
 Template Name: Sign-up
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

$signup = new spinal_register_user();
//$signup->register();
$userID = $signup->register();

?>
<?php get_header() ?>


<div id="content" class="secondary-subpage sing-up">

	<!-- Start top-banner-wrapper -->
	<div class="top-banner-wrapper">

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
					<h1 style="margin-bottom: 20px"><?php the_title() ?></h1>

					<div class="social-sign-up">
						<p>Create a profile or sign up with your preferred social service.</p>
						<div style="display: inline-block;">
							<?php
								echo do_shortcode('[TheChamp-Login]');
							?>
						</div>
						<hr />
					</div>

					<?php
					$options = get_option('theme-options');
				    $trial_days = (isset($options['trial_member']) and !empty($options['trial_member']) )? intval($options['trial_member']) : 30;
					?>
					
				</div>
				<!-- End description wrapper -->

				<div class="col-md-10 col-md-offset-1">

					<div class="form-wrapper">
						<?php
						if((int)$userID > 0) {
							?>
							<br><br>
							<p style="color:#fff; text-align:center">Your account has been successfully created.  
								<br>An email has been sent to you with detailed instructions on how to activate it.</p>
							<?php
						} else {
						?>

						<!-- start form -->
						<form method="post" action="<?php the_permalink() ?>" id="sign-up" class="sing-up-general" autocomplete="off">

							<div class="row">
								<div class="col-sm-6 col-md-6">
									<label for="fullname">Username</label>
									<input type="text" id="fullname" name="fullname" value="<?php echo $_POST['fullname'] ?>"/>
								</div>

								<div class="col-sm-6 col-md-6">
									<label for="birth-date">Date of birth:</label>
									<div class="dropdown datepicker clearfix">
										<input type="text" name="birth-date" id="birth-date" class="date-picker" value="<?php echo $_POST['birth-date'] ?>"/>
									</div>
								</div>
							</div>

							<div class="row">

								<div class="col-md-6 col-sm-12">
									<label for="email">Email</label>
									<input type="email" name="email" id="email" data-error="Email already registered!" value="<?php echo $_POST['email'] ?>"/>
								</div>

								<div class="col-md-6 col-sm-12">
									<div class="row clear">

										<?php
										/* AJAX LOAD CITIES BASED ON COUNTRY
										------------------------------------------------------*/ ?>
										<div class="col-md-6 col-sm-6">
											<label for="country">Country or land</label>
											<div class="ui selection dropdown" id="country">
												<input type="hidden" name="country" class="change-country">
												<i class="dropdown icon"></i>
												<div class="default text">Select Country</div>
												<div class="menu">
													<?php
													$co  = new csv_import_cities;
													$countries = $co->get_countries();
													if(is_array($countries) && count($countries) > 0) :
														foreach ($countries as $country) :
														 ?>
														<div class="item" data-value="<?php echo $country->ID.' '.$country->country ?>" data-text="<?php echo $country->country ?>">
															<?php echo $country->country ?>
														</div>
													<?php
														endforeach;
													endif;
													 ?>
												</div>
											</div>
										</div>

										<div class="col-md-6 col-sm-6">
											<label for="city">City or closest city</label>
											<div class="ui selection dropdown change-cities" id="city">
												<input type="hidden" name="city">
												<i class="dropdown icon"></i>
												<div class="default text">Select City</div>
												<div class="menu">

												</div>
											</div>
										</div>
										<?php
										/* END AJAX LOAD CITIES BASED ON COUNTRY
										------------------------------------------------------*/ ?>
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-12 col-sm-6">
											<label for="password">Password</label>
											<input type="password" name="password" id="password" />
										</div>

										<div class="col-md-12 col-sm-6">
											<label for="confirm-password">Confirm Password</label>
											<input type="password" name="confirm-password" id="confirm-password" data-error='Confirmation password doesn`t match'/>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<label for="description">Describe yourself</label>
									<textarea name="bio" id="description" ><?php echo $_POST['bio'] ?></textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 clearfix">
									<?php
										if($_POST['gender']) :
											switch ($_POST['gender']) :
												case 'male':
														$checkedmale = 'checked';
													break;
												case 'female':
														$checkedfemale = 'checked';
													break;
												case 'other':
														$checkedother = 'checked';
													break;

											endswitch;
										endif;
									 ?>
									<label>Gender:</label>
									<div class="pull-left clearfix gender">
										<div class="radio-circle">
											<input type="radio" name="gender" value="male" id="male" <?php echo $checkedmale ?>>
										</div>

										<label for="male">Male</label>
									</div>
									<div class="pull-left clearfirx gender">
										<div class="radio-circle">
											<input type="radio" name="gender" value="female" id="female" <?php echo $checkedfemale ?>>
										</div>
										<label for="female">Female</label>
									</div>

									<div class="pull-left clearfirx gender">
										<div class="radio-circle">
											<input type="radio" name="gender" value="other" id="other" <?php echo $checkedother ?>>
										</div>
										<label for="other">Other</label>
									</div>
								</div>

								<div class="col-md-6">
									<div class="chose-wrapper">
										<a href="#fancybox-avatar2">Choose your profile picture <i class="fa fa-plus"></i></a>
									</div>
									<input type="hidden" name="avatar" value="<?php echo $_POST['avatar'] ?>" id="profile-pic">
								</div>
							</div>
							<div class="row search-profile">
								<div class="col-sm-4"></div>
								<div class="col-xs-12 col-sm-4">
									<div class="dropdown-outer-wrapper">
										<div class="dropdown-title">
											Conditions
										</div>
										<div class="ui fluid multiple search selection dropdown">

											<input type="hidden" name="affections" id="main-affections"  value="">

											<div class="default text">Search for conditions</div>
											<div class="menu transition visible general-list">
												<?php

												$affections = new csv_import_affections();
												$affections = $affections->get_affections();
											   //$ilist = csv_import_interests::get_interests();

											  foreach ($affections as $affection) :

												  $value = $affection->affection;
												  $id = $affection->ID;
												   ?>
												   <div class="item" data-value="<?php echo $value ?>"><?php echo $value ?></div>
												   <?php
											   endforeach;
											   ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="term-outer-wrapper">
										<div class="terms-wrapper clearfix">
											<input id="terms" type="checkbox" name="terms" value="1">
											<label for="terms"><span></span>I accept the <a href="<?php echo page('terms') ?>">Terms and Conditions</a></label>
										</div>
									</div>
								</div>



								<div class="col-md-12">
									<input type="submit" value="Sign up" />
								</div>

							</div>

						</form>

						<!-- end form -->
						<?php
						/* AVATARS
					 	----------------------------------------------------------------------------------------- */
						?>
						<!-- start avatar popup -->
						<div id="fancybox-avatar2" class="avatar-popup">
							<h3>Choose your profile picture</h3>
							<div class="avatar-inner">
								<ul class="avatar-list clearfix">
									<?php
									/* get avatr list from theme options */
									$avatar_list = get_option('avatars');
									/* loop through all the avatars stored in the database */
									foreach ($avatar_list['avatars_list'] as $avatar) :
										/* get 200px image size */
										$src = get_image_size($avatar, 'spinal-thumb-200');
									 ?>
										<li>
											<div class="avatar-outer-wrap">
												<img src="<?php echo $src; ?>" alt="<?php echo $src; ?>" class="avatar-img">
											</div>
										</li>
									<?php
									/* end loop through the avatars */
									endforeach; ?>
								</ul>
								<div class="avatar-action-wrapper clearfix">
									<a href="javascript:void(0)" class="cancel-popup">cancel</a>
									<a href="javascript:void(0)" class="select-avatar">select</a>
								</div>
							</div>
						</div>
						<!-- end avatar popup -->
						<?php
						/* END AVATARS
					 	----------------------------------------------------------------------------------------- */
						?>


					<?php } //end if user ?>
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



