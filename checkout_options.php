<?php
require_once('checkout_fns.php');
session_start();
do_html_header('Inventory');

try {

check_valid_user();
display_user_menu();
display_checkout_searchcriteria();
display_checkout_options();

}

catch (Exception $e) {
    echo $e->getMessage();
  }

do_html_footer();
?>