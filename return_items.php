<?php

// include function files for this application
require_once('checkout_fns.php');
session_start();

do_html_header('Item Returns');

$checkoutID = $_POST['return_id'];
$returnNotes = $_POST['return_notes'];
$timestamp = date('Y-m-d H:i:s');

try {

check_valid_user();
if (count($checkoutID)>0) {
    foreach( $checkoutID as $key => $ID) {

      //Makes the item available in the inventory, assigns date and places return notes
      $conn = db_connect();

     $result = $conn->query("UPDATE checkouts
                             SET returnDate = '".$timestamp."',
                             returnNotes = '".$returnNotes[$key]."'
                             WHERE checkoutID = '".$ID."' ");

      if (!$result) {
        throw new Exception('Can not return items at this time. Please try again later.');
      }

     $itemID = checkoutID2itemID($ID);
	 
	$result3 = $conn->query("select available 
							 FROM inventory
							 WHERE itemID = '".$itemID."' ");
	$stocked;							
	while ($row = $result3->fetch_array(MYSQLI_BOTH)) {
    $stocked=$row[0];
	} 
	$stocked++;//todo return multiple at once
	
     $result2 = $conn->query("UPDATE inventory
                             SET available = '".$stocked."'
                             WHERE itemID = '".$itemID."' ");

      if (!$result2) {
        throw new Exception('Can not return items at this time.. Please try again later.');
      }


      print "You have returned CheckoutID# " .$ID.
            " with Notes: " .$returnNotes[$key]. " and
            return date ".$timestamp." ...  Thank you <br /><br />";

    }
    }
else {echo "<strong> You did not select any items to return. <strong><br/><br/>";}

    display_user_menu();

}

catch (Exception $e) {
    echo $e->getMessage();
  }


do_html_footer();

?>