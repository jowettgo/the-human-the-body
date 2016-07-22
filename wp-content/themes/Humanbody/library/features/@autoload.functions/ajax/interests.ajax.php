<?php
/* ajax load cities based on id */
add_action( 'wp_ajax_main_interests', 'ajax_main_interests' );
/* ajax load cities based on id */
add_action( 'wp_ajax_nopriv_main_interests', 'ajax_main_interests' );

/* ajax load cities based on id */
add_action( 'wp_ajax_category_types', 'ajax_category_types' );
/* ajax load cities based on id */
add_action( 'wp_ajax_nopriv_category_types', 'ajax_category_types' );


function ajax_category_types()
{
    $_POST = filter::post();
    $categories = explode(',', $_POST['categories']);

    //$categories = explode(',', $_GET['categories']);



    foreach ($categories as $key => $category_id) {

        $id = explode('-',$category_id);



        //if we have parent attribute, we need to retreieve values for all subcategories of main interests
        if (isset($id[2]) and $id[2]=='parent'){


            //get all categories for main interest (parent)
            $subcategories = csv_import_interests::get_categories($id[0]);

            $nr = 1;
            foreach ($subcategories as $subcat) {

                $types = csv_import_interests::get_types($subcat->ID);


                if(is_array($types) && count($types) > 0) :
                    $interests[$subcat->ID.'-'.$subcat->category] = '<span class="separator-interests">'.$subcat->category.'<span>';
                    foreach ($types as $type) {
                        $value = $type->interest_type;
                        $ID = $type->ID;
                        $interests[$ID.'-'.$value] = $value;
                    }
                endif;          

                $nr++;
            } 
            

        } else {
            $types = csv_import_interests::get_types($id[0]);

            if(is_array($types) && count($types) > 0) :
                $interests[$category_id] = '<span class="separator-interests">'.$id[1].'<span>';
                foreach ($types as $type) {
                    $value = $type->interest_type;
                    $ID = $type->ID;
                    $interests[$ID.'-'.$value] = $value;
                }
            endif;            
        }


    }


    echo json_encode( $interests );
    exit;
}

function ajax_main_interests()
{
    $_POST = filter::post();
    $main = explode(',', $_POST['main_interests']);
    foreach ($main as $key => $category_id) {
        $categories = csv_import_interests::get_categories($category_id);
        $interest = csv_import_interests::get_interest_by_id($category_id);

        if($category_id) {
            $interests[$category_id.'-'.$interest.'-parent'] = '<span class="separator-interests">'.$interest.'<span>';
        }

        //if we have just one middle column category (interest_category) with the same name of the main category, that means that the 2 column was blank in the csv
        if (count($categories)==1 and $categories[0]->category==$interest) {
            continue;
        }
       
        foreach ($categories as $category) {
            $value = $category->category;
            $ID = $category->ID;
            $interests[$ID.'-'.$value] = $value;
        }


    }

    echo json_encode( $interests );
    exit;

    
}

 ?>
