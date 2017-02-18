<?php
require_once('checkout_fns.php');
session_start();
do_html_header('Checkout Form');

try {

display_user_menu();
$itemID = $_GET["itemID"];
display_checkout_details($itemID);
echo "<br>";
display_checkout_form($itemID);

}

catch (Exception $e) {
    echo $e->getMessage();
  }

do_html_footer();

?>