<?php
$_GET   = filter::get();
$_POST  = filter::post();
/*
 Template Name: Membership
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
$user = new user_info();
$color2 = '#5969B0';
$color1 =  '#C39360';

$custom_fields = get_post_custom();
//deg ($user->roles);


$user_data = get_userdata( $user->ID );

if (isset($user_data->caps['premium']) && !empty($user_data->caps['premium']) or isset($user_data->caps['premium_member']) && !empty($user_data->caps['premium_member'])) {
	$is_premium = true;
} else {
	$is_premium = false;
}


?>
<?php get_header() ?>
<div id="content" class="membership">
	<!-- Start top-banner-wrapper -->
	<div class="top-banner-wrapper">
		<div class="category-color" style="background: <?php echo $color1 ?>;
			background: -moz-linear-gradient(-45deg, <?php echo $color1 ?> 0%, <?php echo $color2 ?> 100%);
			background: -webkit-linear-gradient(-45deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
			background: linear-gradient(135deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color1 ?>', endColorstr='<?php echo $color2 ?>',GradientType=1 );">
		</div>
		<div class="container">
			<!-- Start Bredcrumb -->
			<div class="breadcrumb-wrapper">

				<ol vocab="http://schema.org/" typeof="BreadcrumbList">
					<li property="itemListElement" typeof="ListItem">
						<a property="item" typeof="WebPage" href="indexx.html">
						<span property="name">Home</span><i class="fa fa-angle-right"></i></a>
						<meta property="position" content="1">
					</li>
					<li property="itemListElement" typeof="ListItem" class="active">
						<a property="item" typeof="WebPage" href="#">
						<span property="name">Membership</span></a>
						<meta property="position" content="2">
					</li>
				</ol>

			</div>
			<!-- End Bredcrumb -->

			<div class="row">
				<div class="description-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">

					<h1><?php the_title() ?></h1>

					<p>
						<?php the_content() ?>
					</p>
					<div class="donation-method month_price col-xs-12 col-sm-6 col-md-6 clearfix">
						
						<?php echo apply_filters('the_content', $custom_fields['ba_month_price'][0]) ?>

						<div class="col-md-8">
							<p style="margin:0; text-transform: none; text-align: right; padding-top: 7px; padding-right: 0" >Make a donation</p>
						</div>
						
						<div class="col-md-4">
							<?php /* paypal donation live */ ?>
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="JVH7AHP5BGJDW">
							 	<input type="hidden" name="custom" value="ksx-<?php echo $user->ID; ?>">
								<input type="image" src="<?php bloginfo('stylesheet_directory'); ?>/library/img/suport.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
								<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
							</form>
				
							<?php /* paypal donation sandbox */
							/*			
							<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="EWUC4LNPP42UE">
								<input type="hidden" name="custom" value="ksx-<?php echo $user->ID; ?>">
								<input type="image"  src="https://www.sandbox.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
								<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
							</form>
							*/ ?>
						</div>	
					</div>	
					<div class="get-membership col-xs-12 col-sm-6 col-md-6">
						<?php if(!is_premium()) : ?>
							<a href="<?php echo get_page_link('156'); ?>" class="scroll-down">Get free membership</a>
						<?php else : ?>
							<a href="#pay" class="scroll-down">Get free membership</a>
	                    <?php endif; ?>
					</div>
				</div>

			</div>
			<!-- End row -->
		</div>
		<!-- End container -->
	</div>
	<!-- End top-banner-wrapper -->
	<div class="membership-content">
		<div class="container">
			<div class="row">
				<!-- start membership-content -->
				<div class="col-sm-12 col-md-12">

					<?php 
					$userinfo = new user_info();
					$paypal_pending = get_user_meta($user->ID, 'paypal_pending', true);

					$premium = $userinfo->premium;
					?>

					<?php if(!empty($paypal_pending) and !$premium) : ?>
					<div class="alert alert-danger">
					    It may take a few moments before your account is being checked and updated. Please refresh the page to view upgrade.
				    </div>
					<?php endif; ?>

					<div class="membership-baners clearfix">
						<ul>
							<?php
								global $wpdb;
								$items = $wpdb->get_results("SELECT * FROM $wpdb->membership ORDER BY `order` ASC");
								$width = floor(100 / count($items));
								foreach ($items as $item) {
									echo "<li style='width:$width%;'>
											<div><i class='$item->icon'></i></div>
											<div><p>$item->title</p></div>
										</li>
										";
								}
							?>
<!--							<li>-->
<!--								<div><i class="fa fa-users"></i></div>-->
<!--								<div><p>Friends list</p></div>-->
<!--							</li>-->
<!--							<li>-->
<!--								<div><i class="fa fa-envelope-o"></i></div>-->
<!--								<div><p>Messages</p></div>-->
<!--							</li>-->
<!--							<li>-->
<!--								<div><i class="fa fa-weixin"></i></div>-->
<!--								<div><p>Chat</p></div>-->
<!--							</li>-->
<!--							<li>-->
<!--								<div><i class="fa fa-map-marker"></i></div>-->
<!--								<div><p>Map</p></div>-->
<!--							</li>-->
<!--							<li>-->
<!--								<div><i class="fa"><img src="--><?php //bloginfo('template_url'); ?><!--/library/img/crowd.svg" alt="get-togheter" style="width: 100%; vertical-align: middle; max-height: 60px;"></i></div>-->
<!--								<div><p>Get-togethers</p></div>-->
<!--							</li>-->
<!--							<li>-->
<!--								<div><img src="--><?php //echo _IMG_ ?><!--lifesaver-security-sportive-tool2.png" alt="support"  style="    width: 56px; display: inline-block; margin-bottom: 43px;"/></div>-->
<!--								<div><p>Support Groups</p></div>-->
<!--							</li>-->
						</ul>
					</div>
				</div>

				<?php
				foreach ($items as $item) {
					echo "<div class=\"col-xs-12 col-sm-6 col-md-6\">
							<div class=\"banner-box $item->css\" style=\"height: 556px;\">
								<h3>$item->title <i class=\"$item->icon\"></i></h3>
								$item->content
							</div>
						</div>";
				}
				?>


<!--				<div class="col-xs-12 col-sm-6 col-md-6 highlight">-->
<!--					<div class="banner-box support" style="height: 556px;">-->
<!--						<h3>Support Groups <img src="--><?php //echo _IMG_ ?><!--lifesaver-security-sportive-tool.png" alt="support"  style="width: 32px; position: absolute; right: 0px;"/></h3>-->
<!--						<p>Members will have access to the support groups page where they will be able to get in touch with other members and freely engage in organized discussions concerning any type of physical or psychological condition they might be facing. A qualified psychotherapist will try to mediate the discussions.</p>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="col-xs-12 col-sm-6 col-md-6">-->
<!--					<div class="banner-box friends" style="height: 556px;">-->
<!--						<h3>FRIENDS LISTS <i class="fa fa-users"></i></h3>-->
<!--						<p>Members of the community will have the chance to create their own friends lists with other platform members.</p>-->
<!--						<p>If failing to pay for membership subscription within the stated time frame, friends lists will not be removed, but become inactive until payment is finalized.</p>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="col-xs-12 col-sm-6 col-md-6">-->
<!--					<div class="banner-box message" style="height: 556px;">-->
<!--						<h3>MESSAGE <i class="fa fa-envelope-o"></i></h3>-->
<!--						<p>Members of the community will have the chance to communicate among themselves via private messaging.</p>-->
<!--						<p>If failing to pay for membership subscription within the stated time frame, messages will not be erased, but become inactive until payment is finalized.</p>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="col-xs-12 col-sm-6 col-md-6">-->
<!--					<div class="banner-box chat" style="height: 556px;">-->
<!--						<h3>CHAT <i class="fa fa-weixin"></i></h3>-->
<!--						--><?php
//						$page_slug ='my-account/chat';
//						$page_data = get_page_by_path($page_slug);
//						$page_id = $page_data->ID;
//						?>
<!--						--><?php //echo apply_filters('the_content', $page_data->post_content); ?>
<!--					</div>-->
<!--				</div>-->
<!--				<div class="col-xs-12 col-sm-6 col-md-6">-->
<!--					<div class="banner-box map" style="height: 556px;">-->
<!--						<h3>MAP <i class="fa fa-map-marker"></i></h3>-->
<!--						--><?php
//						$page_slug ='map';
//						$page_data = get_page_by_path($page_slug);
//						$page_id = $page_data->ID;
//						?>
<!--						--><?php //echo apply_filters('the_content', $page_data->post_content); ?>
<!--					</div>-->
<!--				</div>-->
<!--				<div class="col-xs-12 col-sm-6 col-md-6">-->
<!--					<div class="banner-box get-togethers" style="height: 556px;">-->
<!--						<h3>Get-Togethers <i class="fa"><img src="--><?php //echo _IMG_ ?><!--crowd.svg" alt="get-togheter"  width="30"></i></h3>-->
<!--						<p>This section allows members to suggest real life get-togethers. Members can post a description of the meeting including organizational details, in addition to inviting others to join. Those who are interested may discuss in the comments section of each get-together.”-->
<!--Friends lists: “Members of the community will have the chance to create their own friends lists with other platform members. If failing to pay for membership subscription within the stated time frame, friends lists will not be removed, but become inactive until payment is finalized.”-->
<!--Messages: “Members of the community will have the chance to communicate among themselves via private messaging. If failing to pay for membership subscription within the stated time frame, messages will not be erased, but become inactive until payment is finalized.</p>-->
<!--					</div>-->
<!--				</div>-->
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div id="pay" class="payment-method clearfix">
						
						
						<?php if(!$is_premium) : ?>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<p>Payment method</p>
							<img src="<?php echo _IMG_ ?>paypal-icon.png" alt="">
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<?php
							if(logged_in()) :
								$fullname = explode(' ', $user->name);
								$name = $fullname[0];
								$surname = $fullname[1];
							 ?>
							<form class="paypal" action="<?php echo page('payment') ?>" method="post" id="paypal-form" >
								<input type="hidden" name="cmd" value="_xclick" />
								<input type="hidden" name="no_note" value="1" />
								<input type="hidden" name="lc" value="US" />
								<input type="hidden" name="currency_code" value="USD" />
								<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
								<input type="hidden" name="first_name" value="<?php echo $name ?>"  />
								<input type="hidden" name="last_name" value="<?php echo $surname ?>"  />
								<input type="hidden" name="payer_email" value="<?php echo $user->email ?>"  />
								<input type="hidden" name="item_number" value="638322" / >
								<input type="hidden" name="custom" value="pHBua-<?php echo $user->ID ?>">
								<input type="hidden" name="no_shipping" value="1">
								<button class="after-submit" name="submit" type="submit" value="submit">continue to payment<i class="fa fa-angle-right"></i></button>
								<span class="notice-payment">You don’t need to be a PayPal customer</span>
							</form>
							<?php else : ?>
								<a href="#payment-login-register" class="fancybox-popup">continue to payment<i class="fa fa-angle-right"></i></a>
								<span class="notice-payment">You don’t need to be a PayPal customer</span>
								<div class="hidden">
									<div id="payment-login-register"  class="avatar-popup" style="width:100%;position:relative;float:left;">
										<!-- start form -->
										<form method="post" class="sign-in-ajax sing-up-general container" style="width:100%;">
											<div class="row">

												<div class="col-md-6 col-sm-6">
													<label for="email">Email or Username</label>
													<input type="text" name="logID" value="<?php bbp_sanitize_val( 'user_login', 'text' ); ?>" size="20" id="email" tabindex="<?php bbp_tab_index(); ?>" />
												</div>
												<div class="col-md-6 col-sm-6">
													<label for="password">Password</label>
													<input type="password" name="password" value="<?php bbp_sanitize_val( 'user_pass', 'password' ); ?>" size="20" id="password" tabindex="<?php bbp_tab_index(); ?>" />
												</div>

											</div>

											<div class="row">
												<div class="col-md-12">
													<div class="term-outer-wrapper">
														<div class="terms-wrapper clearfix">
															<a href="<?php echo page('recover-password') ?>" class="forgot-password" target="_blank"><i class="fa fa-question-circle"></i>Forgot your password?</a>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<?php do_action( 'login_form' ); ?>
													<input type="submit" value="Sign in">
												</div>

											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="term-outer-wrapper">
														<div class="terms-wrapper clearfix text-center" style="color:#fff;">
															or create a new account
														</div>
													</div>

														<a href="<?php echo page('sign-up'); ?>" target="_blank" class="button-register">register</a>

												</div>
											</div>
										</form>
										<!-- end form -->
									</div>
								</div>
							<?php endif; ?>
						</div>
						<?php else: ?>
							<div class="col-xs-12 col-sm-12 col-md-12">
								<?php 
									//check if trial
									$trial = get_user_meta($user->ID, 'trial_premium', true); 
									
									if ($trial) {
										//days of the free trial is set in the admin panel
										$options = get_option('theme-options');
									    $trial_days = (isset($options['trial_member']) and !empty($options['trial_member']) )? intval($options['trial_member']) : 30;
										$date_premium = $trial + ($trial_days*24*60*60);
									} else {
										$date_premium = get_user_meta($user->ID, 'date_when_became_premium', true); 
										$date_premium = $date_premium + (30*24*60*60);
									}
									
								?>
								<p style="margin:0; text-transform: none">Your premium <?php echo ($trial) ? 'trial' : ''; ?> membership expires on the <strong><?php echo date('dS', $date_premium); ?> of <?php echo date('F Y', $date_premium); ?></strong> at <strong><?php echo date('h:m A', $date_premium); ?></strong></p>
							</div>						
						<?php endif; ?>
						
					</div>
				</div>

			</div>
			<!-- end membership-content -->
		</div>
	</div>
</div>
<?php get_footer() ?>