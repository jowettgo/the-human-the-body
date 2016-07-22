<?php
$contactOptions = function_exists('spinal_get_contact_options') ? spinal_get_contact_options() : false;
$theme_options  = function_exists('spinal_get_contact_options') ? spinal_get_theme_option() : false;
$menu = new spinal_menu();
$menu->ul_class = 'col-lg-4 col-md-4 col-sm-4';
$menu = $menu->get('footer-nav');

$options = get_option('theme-options');
$facebooklink = $options['facebook'];
$twitterlink = $options['twitter'];
?>

<?php if(!is_home()) :

    ?>
            <footer id="footer">

                <div class="footer-inner">

                    <div class="container">
                        <div class="row">

                            <!-- Start logo -->
                            <div class="col-md-2 col-lg-2 col-sm-2 col-xs-12">
                                <div class="footer-logo-wrapper">
                                    <a href="<?php echo get_site_url() ?>" class="logo"></a>
                                    <img src="<?php echo _IMG_ ?>footer-logo.png" alt="logo"/>
                                </div>
                            </div>
                            <!-- End logo -->

                            <!-- start copyright -->
                            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 copyright">
                                <strong>© 2015 Human Body. All rights reserved.</strong>
                                <div class="clearfix">
                                    <span>Designed &amp; developed by:</span>
                                    <a href="http://www.design19.org" class="design19"></a>
                                </div>
                            </div>
                            <!-- end copyright -->

                            <!-- start footer menu -->
                            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-12 hidden-xs">
                                <ul class="footer-menu row">
                                	<?php echo $menu ?>
                                    <?php if(logged_in()) :
											$menu_object = new spinal_menu();

											if($menu_object->curPageURL() === page('my-account')) :
												$active = 'active';
											else :
												$active = false;
											endif;
											?>

											<li class="col-lg-4 col-md-4 col-sm-4  default"><a href="<?php echo page('my-account') ?>" class="<?php echo $active ?>"><?php echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?'Profile & Chat':'Profile'; ?></a></li>

										<?php endif; ?>
                                </ul>
                            </div>
                            <!-- start footer menu -->
                            <div class="col-lg-6 col-md-6 col-sm-5 col-xs-12 hidden-xs">
                                <ul class="footer-menu row">
                                    <li class="col-lg-4 pull-right social-icons-footer">
                                        <a href="<?php echo $facebooklink ?>"><i class="fa fa-facebook"></i></a>
                                        <a href="<?php echo $twitterlink ?>"><i class="fa fa-twitter"></i></a>
                                    </li>
                                </ul>
                            </div>

                            <!-- end footer menu -->

                        </div>
                    </div>
                </div>
            </footer>


        <?php endif; ?>
        <!-- end wrapper -->
        </div>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>
        <script src="http://cdn.amcharts.com/lib/3/ammap.js" type="text/javascript"></script>
        <script src="http://cdn.amcharts.com/lib/3/maps/js/worldHigh.js" type="text/javascript"></script>
        <script src="http://cdn.amcharts.com/lib/3/themes/dark.js" type="text/javascript"></script>
        <?php wp_footer(); ?>
    </body>
</html> <!-- end of site. what a ride! -->
