<?php
/**
 * load_features takes a buch or arrays and includes them inside functions.php
 * @method load_features
 * @return null
 */
function load_features($features) {
    /* add all features to admin */
    foreach ($features as $feature => $settings) {
        if($settings['feature-enabled'] === true) {
            /* define our feature constant */
            if(isset($settings["variables"])) {
                define($settings["variables"]['constant'], $settings["variables"]['value']);
            }
            if($settings['file']) {
                /* reuire the file */
                require_once($settings['file']);
            }

            /* switch */
            switch ($feature) {
                /* dashboard switch case */
                case 'dashboard':
                    /* disable dashboard */
                    if($settings['disable']) {
                        add_action( 'admin_menu', 'remove_dashboard',0);
                    }
                    break;

                default:

                    break;
            }
        }
    }
}



/* auto load all settings regarding this theme in @autoload.options directory */
function _require_all($dir, $depth=0) {
        if ($depth > 3) {
            return;
        }
        // require all php files
        $scan = glob("$dir/*");
        foreach ($scan as $path) {
            if (preg_match('/\.php$/', $path)) {
                require_once $path;
            }
            elseif (is_dir($path)) {
                _require_all($path, $depth+1);
            }
        }
}
/* add all classes */
$dir = get_template_directory().'/library/features/@autoload';
_require_all($dir);
/* add all classes */
$dir = get_template_directory().'/library/features/@autoload.custom.posts';
_require_all($dir);

/* add all functions */
$dir = get_template_directory().'/library/features/@autoload.functions';
_require_all($dir);
/* only include the options if its admin */
if(is_admin()) :
    $dir = get_template_directory().'/library/features/@autoload.options';
    _require_all($dir);
endif;



?>
