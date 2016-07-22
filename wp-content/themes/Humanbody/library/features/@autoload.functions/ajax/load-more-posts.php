<?php
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_loadmoreposts', 'ajax_load_more_posts' );
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_nopriv_loadmoreposts', 'ajax_load_more_posts' );


function ajax_load_more_posts($catID = false) {
    $ajax = isset($ajax) ? $ajax : true;
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* get the page */
    $page = $_POST['p'] ? $_POST['p'] : 0;
    $catID = $_POST['c'] ? $_POST['c'] : $catID;
    global $wp_query, $post;

    /* category specific */

    $meta = get_term_meta($catID);
    // category image
    $image = $meta['image'][0];
    // category colors
    $color1 = $meta['color1'][0];
    $color2 = $meta['color2'][0];

    $colortext = $meta['color-article-text'][0];
    /* Articles loop
    --------------------------------------------------------------------------------- */
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 12,
        'order_by' => 'date',
        'order' => 'DESC',
        'paged'=> $page,
        'category__in' =>$catID

    );
    $articles = new WP_Query($args);

    if ($articles->have_posts()) :
        while ($articles->have_posts()) :
            $articles->the_post();
            ?>
            <?php
            /* Base article info template, its colored according to the category it belongs to
            -------------------------------------------------------------------------------- */
             ?>
            <!-- start aticle 1 -->
            <div class="article-small  col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="inner-wrap">
                    <img src="<?php echo featured_image($post, 'spinal-thumb-blog') ?>" alt="img">
                    <h4><a href="<?php the_permalink() ?>" style="color:<?php echo $colortext ?>"><?php the_title() ?></a></h4>
                    <p>
                        <?php echo excerpt_size(150) ?>
                    </p>
                    <a href="<?php the_permalink() ?>" class="read-more" style="color:<?php echo $colortext ?>"><i class="fa fa-angle-right" style="background:<?php echo $colortext ?>"></i>Read more</a>
                </div>
            </div>
            <!-- end aticle 1 -->
             <?php
             endwhile;
         endif;

         /* END Articles loop
        --------------------------------------------------------------------------------- */
        if(isset($_POST['c'])) {
            die();
        }
}

 ?>
