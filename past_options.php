<?php
require_once('checkout_fns.php');
session_start();
do_html_header('Checkout History');

try {

check_valid_user();
display_user_menu();
display_past_searchcriteria();
display_past_options();


}

catch (Exception $e) {
    echo $e->getMessage();
  }

do_html_footer();
?>