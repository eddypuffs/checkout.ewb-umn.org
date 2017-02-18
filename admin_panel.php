<?php
require_once('checkout_fns.php');
require_once('includes/admin_fns.php');
session_start();
do_html_header('Admin Panel');


try {
    check_valid_admin();
    display_user_menu();
    
    display_takeover_user();
	display_authorize_user();
	display_deauthorize_user();
    display_newitem_form();
    display_item_delete();
	display_item_edit();
}

catch (Exception $e) {
    echo $e->getMessage();
  }

do_html_footer();
?>