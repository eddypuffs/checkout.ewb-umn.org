<?php
require_once('checkout_fns.php');
require_once('includes/admin_fns.php');
#require_once('query/admin_fns.php');
session_start();
do_html_header('Admin Panel');


try {
    check_valid_admin();
    display_user_menu();
    
    display_edititem_form();

}

catch (Exception $e) {
    echo $e->getMessage();
  }

do_html_footer();
?>