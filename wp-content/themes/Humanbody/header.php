<?php
/* the prime hook, add comments sign out the user and uther nifty functions */
/**
 * DONT REMOVE THIS HOOK
 */

do_action('spinal-primal');

/* homepage options are retrieved with get_option('homepage') */
if(function_exists('spinal_get_theme_option')) :
	$homeOptions = spinal_get_theme_option();
	/* get uploaded logo */
	$homeOptionsLogo = isset($homeOptions['logo']) ? $homeOptions['logo'] : false;
endif;
/* if no logo is set in the options, get the fallback */
$logoUrl = $homeOptionsLogo ? $homeOptionsLogo : _IMG_.'logo.png';
/* menu */
$menu = new spinal_menu();
$menu->before = '<i class="dot"></i>';
$menu->first = '';
$menu = $menu->get('main-nav');
$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'facebook-img', true );

/* get current user */
$current_user = new user_info();
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- Site Seo -->
		<title><?php wp_title(''); ?></title>
		<meta name="description" content="page description"/>
		<meta name="keywords" content="words, words"/>
		<!-- / Site Seo	-->

		<!-- Social Network Support	-->
		<meta property="og:title" content="<?php wp_title(''); ?>" />
		<meta property="og:url" content="<?php the_permalink(); ?>" />
		<meta property="og:image" content="<?php echo $large_image_url[0] ?>" />
		<meta property="og:image:type" content="image/jpg" />  
		<meta property="og:image:width" content="831" /> 
		<meta property="og:image:height" content="490" /> 


		<!-- / Social Network Support -->

		<!-- Mobile Meta -->
		<meta name="HandheldFriendly" content="True" />
		<meta name="MobileOptimized" content="320" />
		<!-- mobile scale -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!-- disable Telephone number styling in safari -->
		<meta name="format-detection" content="telephone=no">
		<!-- / Mobile Meta -->

		<!-- favicon support for safari ios/chrome/ff/win 8+ ie -->
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<meta name="msapplication-TileColor" content="#bbcfe3">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/win8-tile-icon.png">
        <meta name="theme-color" content="#003e72">
		<!--  / favicon support	-->

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<?php wp_head(); ?>		<style media="screen">
		.membership .top-banner-wrapper {
			background-position: top center !important;
			background-size: cover !important;
		}
		</style>
	</head>
	
	<?php
	$user_ID = get_current_user_id();
	if(!empty($user_ID)){
		$prem_check = get_userdata( $user_ID );		
	}
	?>
	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage" data-membership="<?php echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?1:0; ?>">
		<?php
		/* CONTACT STICKY - Popup form
		---------------------------------------------------------------------------------*/
		 ?>
		 <div class="contact-popup-sticky">
 			<a href="#contact-popup"><i class="fa fa-envelope"></i></a>
 		</div>
 		<div class="hidden">
 			<div id="contact-popup" class="avatar-popup">
 				<form action="index.html" method="post" class="sing-up-general" style="margin-bottom:0px;">
 					<div class="row">
 						<?php if (logged_in()): ?>
							<input type="hidden" name="fullname" value="<?php echo $current_user->name; ?>">
							<input type="hidden" name="email" value="<?php echo $current_user->email; ?>">
 						<?php else: ?>
							<div class="col-md-6">
	 							<label>Full name</label>
	 							<input type="text" name="fullname">
	 						</div>
	 						<div class="col-md-6">
	 							<label for="title">Email</label>
	 							<input type="text" name="email">
	 						</div>
 						<?php endif; ?>
 						<div class="col-md-12">
 							<label for="title">Message</label>
 							<textarea name="message"></textarea>
 							<input type="submit" value="Send Message" name="send">
 						</div>
 					</div>
 				</form>
 			</div>
 		</div>
		<?php
		/* END CONTACT STICKY - Popup form
		---------------------------------------------------------------------------------*/
		 ?>
		<!-- Start wrapper -->
			<div id="wrapper">

				<header id="header">

					<!-- start top-wrapper -->
					<div class="top-wrapper">
						<div class="container">
							<?php

							/* User not logged in
							--------------------------------------------------------------------------------- */
							if(!logged_in()) :
							?>
								<div class="sign-up-wrapper clearfix">
									<a href="<?php echo page('sign-up') ?>" class="pull-right sign-up">Sign Up</a>
									<a href="<?php echo page('sign-in') ?>" class="pull-right sign-in">Sign In</a>
								</div>
							<?php
							/* User logged in
							--------------------------------------------------------------------------------- */
							else:

                                /* this is the main notification class object */
                                $uno = new notifications();
								/* number of notifications */
								$notifications_count = $uno->total_unread();
                                /* feature available only to premium members */

								    /* add active if user notifications is greater than 0 */
                                    if($notifications_count > 0) :
    									$active = 'active';
    								else :
    									$active = '';
    								endif;

								/* get notifications */
								$messages_not = $uno->get_all_messages(15, $page);

							 ?>

							<div class="user-wrapper clearfix">
                                <?php
                                /* NOTIFICATION COUNT (bell)
                                ------------------------------------------------------------------------- */
                                 ?>
								<div class="bell <?php echo $active ?> pull-right">
									<div class="inner-bell">
										<span class="bell-number"><?php echo $notifications_count ?></span>
									</div>
								</div>
                                <?php
                                /* END NOTIFICATION COUNT (bell)
                                ------------------------------------------------------------------------- */
                                ?>
							    <div class="user-profil pull-right">
                                    <?php
                                    /* CURRENT USER AVATAR NAME AND ACTIONS
                                    ------------------------------------------------------------------------- */
                                     ?>
									<div class="user-img pull-left">
											<img src="<?php echo $current_user->avatar ?>" alt="user-avatar">
									</div>
									<div class="user-name pull-right">
										<b>Welcome,</b> <?php echo $current_user->name ?> <i class="fa fa-angle-down"></i>
										<div class="my-account-list">
											<a href="<?php echo page('my-account') ?>"><i class="fa fa-user"></i> My account</a>
											<a href="<?php echo spinal_sign_out_url() ?>"><i class="fa fa-unlock-alt"></i> Log Out</a>
										</div>
									</div>
                                    <?php
                                    /* END CURRENT USER AVATAR NAME AND ACTIONS
                                    ------------------------------------------------------------------------- */
                                     ?>
                                    <?php
                                    /* NOTIFICATION LISTING
                                    ------------------------------------------------------------------------- */
                                     ?>
                                    <?php if($notifications_count >= 0) : ?>
									<div class="notifications-list">
										<ul>
										<?php
										//messages
										if(count($messages_not) > 0) :
											
											$users_arr = array();
											if(!empty($messages_not)):

												foreach ($messages_not as $message) {
													if($message->status == 0 ){
														$users_arr[$message->from_id][] = $message;
													}
												}
												if(count($users_arr) > 0) : ?>
														<?php
															foreach ($users_arr as $n) :
																$date_time = $n[0]->date;
																$name = $n[0]->name;
																$first = explode(' ', $name);
																$first = $first[0];
																$count = 0;
																foreach ($n as $messaege) {
																	if($messaege->status == 0 ){
																		$count += 1;
																	}
																}
																?>
																
																<li class="clearfix <?php echo $count > 0?'unread':''; ?> <?php echo $n[0]->class ?>" title="<?php echo $n[0]->title ?>">
				    												<a href="<?php echo $n[0]->url ?>">
				    													<div class="user-content-details">
				    														<img src="<?php echo $n[0]->avatar ?>" alt="avatar">
				    														<p style="position: relative;"><?php echo $name ?>
				    															<?php if($count > 0): ?>
																					<span style="top: 5px; right: 0px;" class="bell-number"><?php echo $count; ?></span>
																				<?php endif; ?>
				    														</p>
				    														<span><?php echo $n[0]->action ?> <i>"<?php echo $n[0]->text ?>..."</i></span>
				    													</div>
				    													<div class="notification-date">
				    														<p><?php echo ucfirst($date_time[0]) ?><br><?php echo $date_time[1] ?></p>
				    													</div>
				    												</a>
				    											</li>
																<?php
															endforeach;
														?>
													<?php endif; 
												endif;//check if arr not empty 
											endif;
											//end user messages
											?>
											
                                            
                                            <?php
                                            //Number of nothifications to display is 5. If $messages_not is lower than 5, display the other notifications on available positions
                                            if (count($users_arr)<5) {
                                            	$notifications_nr_to_display = 5 - count($users_arr);
                                            } else {
                                            	$notifications_nr_to_display = 0;
                                            }
                                            
                                            $notifications =$uno->get_no_messages($notifications_nr_to_display, 1);

                                            if(count($notifications) > 0):
	                                            foreach ($notifications as $n) :
	                                                /* user avatar */
	                                                $avatar = $n->avatar;
	                                                /* user name */
	                                                $name = $n->name;
	                                                /* notification action, a string in the form of a verb */
	                                                $action = $n->action;
	                                                /* cut string from the place the notification began */
	                                                $text = substr($n->text, 0, 17);
	                                                /* notification date and time (array) */
	                                                $date_time = $n->date;
	                                                /* boolean 0/1 */
	                                                $status = $n->status;
	                                                /* url to make the user go to */
	                                                $permalink = $n->url;
	                                                /* class added to the notification */
	                                                $class = $n->class;
	                                                /* notification title in case the area is locked */
	                                                $title = $n->title;
	                                            ?>
	    											<li class="clearfix <?php echo $status == 1 ? '' : 'unread' ?> <?php echo $class ?>" title="<?php echo $title ?>">
	    												<a href="<?php echo $permalink ?>">
	    													<div class="user-content-details">
	    														<img src="<?php echo $avatar ?>" alt="avatar">
	    														<p><?php echo $name ?></p>
	    														<span><?php echo $action ?> 

	    														<?php if ($n->type!='friend request') : ?>	
	    															<i>"<?php echo $text ?>..."</i>
																<?php endif; ?>

	    														</span>
	    													</div>
	    													<div class="notification-date">
	    														<p><?php echo ucfirst($date_time[0]) ?><br><?php echo $date_time[1] ?></p>
	    													</div>
	    												</a>
	    											</li>
	                                            <?php
	                                            endforeach;
											endif;
                                            ?>
										</ul>
										<?php
											$userinfo = new user_info();
											$info = $userinfo->get();
											$premium = $userinfo->premium;
											if($premium) :
										?>
										<?php endif; ?>
										<a href="<?php echo page('notifications'); ?>">see all notifications</a>
									</div>
                                    <?php
                                    endif;

                                    /* END NOTIFICATION LISTING
                                    ------------------------------------------------------------------------- */
                                     ?>
								</div>
							</div>
							<?php
							endif;
							/* END user state logged in
							--------------------------------------------------------------------------------- */
							 ?>
						</div>
					</div>
					<!-- end top-wrapper -->

					<!-- start menu-wrapper -->
					<div class="menu-wrapper">

						<!-- start container -->
						<div class="container">

							<!-- start row -->
							<div class="row">

								<div id="menu-button" class="clearfix">
									<div id="nav-icon" class="pull-right">
										<span></span>
										<span></span>
										<span></span>
									</div>
								</div>

								<!-- Start logo -->
<!--								<div class="col-md-2 col-lg-2 col-sm-3 col-xs-12 logo-wrapper">-->
								<div class="col-md-3 col-lg-3 col-sm-3 col-xs-12 logo-wrapper">
									<div itemscope itemtype="http://schema.org/Organization" id="logo" >
										<a itemprop="url" href="<?php echo get_site_url() ?>"></a>
										<img itemprop="logo" src="<?php echo _IMG_ ?>logo.png" alt="logo"/>
									</div>
								</div>
								<!-- End logo -->

								<!-- Start nav -->
								<nav id="nav" class="col-md-9 col-lg-9 col-sm-9 col-xs-12">
									<ul class="main-menu">
										<?php if(logged_in()) :
											$menu_object = new spinal_menu();

											if($menu_object->curPageURL() === page('my-account')) :
												$active = 'active';
											else :
												$active = false;
											endif;
											?>

											<li><i class="dot"></i><a href="<?php echo page('my-account') ?>" class="<?php echo $active ?>"><?php echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?'Profile & Chat':'Profile'; ?></a></li>

										<?php endif; ?>
										<?php echo $menu ?>
										<li><a href="<?php echo get_site_url()  ?>" class="<?php if(is_home()) echo 'active' ?>"><i class="fa fa-home"></i></a></li>

									</ul>
								</nav>
								<!-- End nav -->
							</div>
							<!-- end row -->
						</div>
						<!-- end container -->
					</div>
					<!-- end menu-wrapper -->
				</header>
