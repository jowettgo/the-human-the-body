<?php
function redirect_js($url) {
    return "<script type='text/javascript'>
    window.location.href = '{$url}';
    </script>";
}
 ?>
