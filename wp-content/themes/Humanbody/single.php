<?php
global $wp_query;
$category = get_the_category();

$meta = get_term_meta($category[0]->cat_ID);
$type = $post->post_type;
?>
<?php get_header(); ?>
<?php
if($meta['post-ideas'][0] == 'on') :
    include('single-ideas.php');
else :
    switch ($type) :
        case 'idea':
            include('single-user-idea.php');
            break;
        case 'research':
            include('single-user-research.php');
            break;
        case 'galleries':
            include('single-gallery.php');
            break;
        case 'affection':
            include('single-body.php');
            break;
        default:
            include('single-default.php');
            break;
    endswitch;
endif;
?>
<?php
get_footer();
