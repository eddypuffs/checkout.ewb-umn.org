<?php
require_once('checkout_fns.php');
session_start();

$itemID = $_GET['itemID'];
$username = $_SESSION['valid_user'];
$creationDate = date('Y-m-d H:i:s');

$checkoutDay = $_POST['checkoutDay'];
$checkoutTime = $_POST['checkoutTime'];
$checkoutDate = $checkoutDay." ".$checkoutTime.":00";

$returnDay = $_POST['returnDay'];
$returnTime = $_POST['returnTime'];
$returnDate = $returnDay." ".$returnTime.":00";

$purpose = $_POST['purpose'];


  try   {
    check_valid_user();
      
    // check forms filled in
    if (!filled_out($_POST)) {
      throw new Exception('You have not filled the form out correctly - please go back and try again.');
    }

    // check if itemID has already been registered
    if (itemID2available($itemID)==0)
    {
      throw new Exception('The item has already been checked out - please try again when it is available.');
    }
      
    // checks if user refreshed the page
    if (strlen($checkoutDate)!=19)
    {
		print $checkoutDate;
		print strlen($checkoutDate);
        throw new Exception('Checkout has already been processed. Please go back to the home page.');
    }  

    // Proceed with the querry
      
    insert_checkout($username, $itemID, $creationDate, $checkoutDate, $returnDate, $purpose);
    
    do_html_header('Success!');
    
    display_user_menu();
    
    echo '<br>';
    echo "* Checkout has been processed for item # " . $itemID .
     " under username " . $username . "<br><br>" .
     " * Created : " . $creationDate . "<br><br>" .
     " * With notes : " . $purpose . "<br><br>" .
     " * To be picked up : " . $checkoutDate . "<br><br>" .
     " * To be picked returned : " . $returnDate;

   // end page
   do_html_footer();
  }

  catch (Exception $e) {
     do_html_header('Problem:');
     display_user_menu();
     echo "<br>".$e->getMessage();
     do_html_footer();
     exit;
  }

?>