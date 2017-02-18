<?php
require_once('checkout_fns.php');
session_start();
do_html_header('Checkout Details');

try {

check_valid_user();
display_user_menu();
display_past_details();

}

catch (Exception $e) {
    echo $e->getMessage();
  }

do_html_footer();
?>