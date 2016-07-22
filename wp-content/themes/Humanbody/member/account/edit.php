<?php
/* purge everything on get and post */
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$user = wp_get_current_user();
$userinfo = new user_info();
?>
<div id="content" class="secondary-subpage editare-cont">

	<!-- cont-utilizator-section -->
	<div class="editare-cont-section">
		<!-- Start top-banner-wrapper -->
		<div class="top-banner-wrapper">

			<div class="container">
				<!-- Start Bredcrumb -->
				<div class="breadcrumb-wrapper clearfix">

					<ol vocab="http://schema.org/" typeof="BreadcrumbList">
						<li property="itemListElement" typeof="ListItem">
							<a property="item" typeof="WebPage" href="<?php echo get_site_url() ?>"> <span property="name">Home</span><i class="fa fa-angle-right"></i></a>
							<meta property="position" content="1">
						</li>
						<li property="itemListElement" typeof="ListItem" class="active">
							<a property="item" typeof="WebPage" href="#"> <span property="name">Edit profile</span></a>
							<meta property="position" content="2">
						</li>
					</ol>

				</div>
				<!-- End Bredcrumb -->

				<!-- Start cont-wrapper -->
				<div class="cont-wrapper">
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<?php include_once('account-topbar.php') ?>
						</div>
						<div class="col-md-10 col-md-offset-1">
							<div class="edit-profile-section">
								<h1>Edit profile</h1>
								<p>
									<?php //the_content() ?>
								</p>
								<div class="form-wrapper clearfix">
									<!-- start form -->
									<form method="POST" action="<?php get_permalink() ?>" id="profile-edit" class="sing-up-general edit clearfix">
										<div class="col-sm-6 col-md-6">
											<div class="edit-profile-img">
												<div>
													<img class="profile-pic-upload"  src="<?php echo $userinfo->avatar  ?>" alt="">
												</div>
											</div>
										</div>
										<div class="col-sm-6 col-md-6">

											<div class="chose-wrapper upload-button">
												<a href="#fancybox-avatar2">edit your profile picture <i class="fa fa-plus"></i></a>
											</div>
											<input type="hidden" name="avatar" value="<?php echo $userinfo->avatar_id  ?>" id="profile-pic">

										</div>
										<hr>
										<div class="col-md-12">
											<div class="row">
												<div class="col-sm-6 col-md-6">
													<label for="name">Name</label>
													<input type="text" id="name" name="fullname" value="<?php echo $userinfo->name ?>">
												</div>

												<div class="col-sm-6 col-md-6">
													<label for="birth-date">Date of birth:</label>
													<div class="dropdown datepicker clearfix">
														<input type="text" name="birthdate" id="birth-date" value="<?php echo $userinfo->birthdate ?>">
													</div>
												</div>
											</div>

											<div class="row">

												<div class="col-md-6 col-sm-6">
													<label for="email">Email</label>
													<input type="email" name="email" id="edit-email" value="<?php echo $userinfo->email ?>" disabled>
												</div>

												<div class="col-md-6 col-sm-6">
													<div class="row clear">
														<?php
														/* AJAX LOAD CITIES BASED ON COUNTRY
														------------------------------------------------------*/ ?>
														<div class="col-md-6 col-sm-6">
															<label for="country">Country or land</label>
															<div class="ui selection dropdown" id="country">
																<input type="hidden" name="country" class="change-country" value="<?php echo $userinfo->country ?>">
																<i class="dropdown icon"></i>
																<div class="default text"><?php echo $userinfo->country ? $userinfo->country : 'Select Country' ?></div>
																<div class="menu">
																	<?php
																	$co  = new csv_import_cities;
																	$countries = $co->get_countries();
																	if(is_array($countries) && count($countries) > 0) :
																		foreach ($countries as $country) :
																			?>
																		<div class="item" data-value="<?php echo $country->ID.' '.$country->country  ?>" data-text="<?php echo $country->country ?>">
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
																	<input type="hidden" name="city" value="<?php echo $userinfo->city ?>">
																	<i class="dropdown icon"></i>
																	<div class="default text"><?php echo $userinfo->city ? $userinfo->city : 'Select City' ?></div>
																	<div class="menu">
																		<?php
																		$co  = new csv_import_cities;
																		$cities = $co->get_cities_by_country_name($userinfo->country);
																		if(is_array($cities) && count($cities) > 0) :
																			foreach ($cities as $city) :
																				?>
																			<div class="item" data-value="<?php echo $city->city  ?>" data-text="<?php echo $city->city ?>">
																				<?php echo $city->city ?>
																			</div>
																			<?php
																			endforeach;
																			endif;
																			?>
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
															<input type="password" name="old-password" id="old-password">
														</div>
														<div class="col-md-12 col-sm-6">
															<label for="new-password">New Password</label>
															<input type="password" name="new-password" id="new-password">
														</div>
														<div class="col-md-12 col-sm-6">
															<label for="confirm-password">Confirm new Password</label>
															<input type="password" name="confirm-password" id="confirm-password">
														</div>
													</div>
												</div>

												<div class="col-md-6">
													<label for="description">Describe yourself</label>
													<textarea maxlength="2000" name="description" id="description"><?php echo $userinfo->description ?></textarea>

												</div>
											</div>

											<?php
											$gender = $userinfo->gender;
											switch ($gender) {
												case 'male':
												$checkedmale = 'checked';
												break;
												case 'female':
												$checkedfemale = 'checked';
												break;
												case 'other':
												$checkedother = 'checked';
												break;
												default:
													# code...
												break;
											}
											?>
											<div class="row">
												<div class="col-md-6 clearfix">
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

												<div class="col-md-6"></div>
											</div>
											<div class="row search-profile">
												<div class="col-md-12">
													<div class="dropdown-title">
														Conditions
														<p style="text-align: left;">Please select any conditions that apply to you. They will not appear on your profile. They will be useful for helping you get in touch with others sharing similar conditions, as well as recommend you useful articles.</p>
													</div>
												</div>
												<div class="col-sm-4"></div>
												<div class="col-sm-4">
													<div class="dropdown-outer-wrapper">
														<div class="ui fluid multiple search selection dropdown">

															<input type="hidden" name="affections" id="main-affections"  value="<?php echo $userinfo->affections ?>">
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
												<div class="col-sm-4"></div>
											</div>
											<?php
											/* PREMIUM INTERESTS
											---------------------------------------------------------------- */
											if($userinfo->premium) :
												$int = get_option('interests-options');
											$co = new csv_import_cities();
											$int_selected_1 = $userinfo->get_interests(1);
											$int_selected_2 = $userinfo->get_interests(2);
											$int_selected_3 = $userinfo->get_interests(3);

											?>

											<div class="row search-profile">
												<div class="col-md-12">
													<div class="dropdown-title">
														INTERESTS
														<p style="text-align: left;">Please select up to 40 interests that you have. This will help you get in touch with others sharing similar interests. Please select a category from the first box for each interest. Then, please choose a corresponding subcategory from the second box and a subsequent particular interest from the third box. Alternatively, you can search for a particular interest directly from the third box using the search bar.</p>
													</div>
												</div>

												<div class="col-md-4 col-sm-4 col-xs-12">
													<div class="dropdown-title">
														Categories
														<!-- <?php echo $int['interest_1_title'] ?> -->
													</div>
													<div class="dropdown-outer-wrapper">
														<div class="ui fluid multiple search selection dropdown">
															<div class="selected-title">Main interests</div>
															<input type="hidden" name="interests-1" id="main-interest"  value="<?php echo $userinfo->interests_1 ?>">

															<div class="default text">Search for an interests</div>
															<div class="menu transition visible general-list">
																<?php


																$ilist = csv_import_interests::get_interests();

																foreach ($ilist as $interestobject) :

																	$value = $interestobject->interest;
																$id = $interestobject->ID;
																?>
																<div class="item" data-value="<?php echo $id ?>"><?php echo $value ?></div>
																<?php
																endforeach;
																?>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													<div class="dropdown-title">
														SUBCATEGORIES
													</div>
													<div class="dropdown-outer-wrapper check">
														<div class="ui fluid multiple search selection dropdown">
															<div class="selected-title">Selected categories</div>
															<input type="hidden" name="interests-2" id="interest-categories" value="<?php echo $userinfo->interests_2 ?>">
															<?php echo $int_selected_2 ?>
															<div class="default text">Search for interests</div>
															<div class="menu transition visible general-list">


															</div>
														</div>
													</div>
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													<div class="dropdown-title">
														PARTICULAR
													</div>
													<div class="dropdown-outer-wrapper check">
														<div class="ui fluid multiple search selection dropdown">
															<div class="selected-title">Selected types</div>
															<input type="hidden" name="interests-3" id="interest-types"  value="<?php echo $userinfo->interests_3 ?>">
															<?php echo $int_selected_3 ?>
															<div class="default text">Search for interests</div>
															<div class="menu transition visible general-list">


															</div>
														</div>
													</div>
												</div>
											</div>

											<?php
											endif;
												 /* End user info premium
												 --------------------------------------------- */
												 ?>


												 <div class="row">
												 	<div class="col-md-12">
												 		<input type="submit" name="edit-profile" value="Update profile">
												 	</div>

												 </div>
												</div>


											</form>
											<!-- end form -->
										</div>
									</div>
								</div>
							</div>
							<!-- End row -->
						</div>
						<!-- End cont-wrapper -->




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
							/* get avatar list from theme options */
							$avatar_list = get_option('avatars');


							/* premium users */
							if($userinfo->premium) :
								$premium_avatars_list = $avatar_list['premium_avatars_list'];
							endif;
							if(is_array($premium_avatars_list) && count($premium_avatars_list) > 0) :

								/* loop through all the avatars stored in the database */
							foreach ($premium_avatars_list as $avatar) :
								/* get 200px image size */
							$src = get_image_size($avatar, 'spinal-thumb-200');

							?>
							<li>
								<div class="avatar-outer-wrap">
									<img src="<?php echo $src; ?>" alt="<?php echo get_image_id($avatar); ?>" class="avatar-img">
								</div>
							</li>
							<?php
							/* end loop through the avatars */
							endforeach;
							endif;

							/* normal users */
							$avatars = $avatar_list['avatars_list'];

							if(is_array($avatars) && count($avatars) > 0) :
								/* loop through all the avatars stored in the database */
							foreach ($avatars as $avatar) :
								/* get 200px image size */
							$src = get_image_size($avatar, 'spinal-thumb-200');
							?>
							<li>
								<div class="avatar-outer-wrap">
									<img src="<?php echo $src; ?>" alt="<?php echo get_image_id($avatar);; ?>" class="avatar-img">
								</div>
							</li>
							<?php
							/* end loop through the avatars */
							endforeach;
							endif;


							?>
						</ul>
						<div class="avatar-action-wrapper clearfix">
							<a href="#" class="cancel-popup">cancel</a>
							<a href="#" class="select-avatar">select</a>
						</div>
					</div>
				</div>
				<!-- end avatar popup -->
				<?php
				/* END AVATARS
				----------------------------------------------------------------------------------------- */
				?>
			</div>
			<!-- End container -->
		</div>
		<!-- End top-banner-wrapper -->
	</div>
	<!-- cont-utilizator-section -->
</div>
<a class="max-allow" href="#max-allow"></a>
<div class="hidden">
    <div id="max-allow" class="text-center">
          <p>Maximum of 40 interests are allowed.</p>
    </div>
</div>