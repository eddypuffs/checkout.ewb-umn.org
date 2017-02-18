<?php

//Headers, footers and other stuff

function do_html_header($title) {
  // print an HTML header
?>
  <html>
  <head>
    <title><?php echo $title;?></title>
    <style>
      body { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      li, td { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      hr { color: #3333cc; width=300; text-align=left}
      a { color: #000000 }
    </style>
  </head>
  <body>
  <img src="http://ewb-umn.org/nimgs/logos/chapterlogo.png" border="0"/> <br />
  <h1>EWB-UMN Checkout</h1>
  <hr />
<?php
  if($title) {
    do_html_heading($title);
  }
}

function do_html_footer() {
  // print an HTML footer
?>
  </body>
  </html>
<?php
}

function do_html_heading($heading) {
  // print heading
?>
  <h2><?php echo $heading;?></h2>
<?php
}

function do_html_URL($url, $name) {
  // output URL as link and br
?>
  <br /><a href="<?php echo $url;?>"><?php echo $name;?></a><br />
<?php
}

function display_site_info() {
  // display some marketing info
?>
  <ul>
  <li>Welcome to the EWB Checkout Site!</li>
    <li>NOTE: Actual equipment has not been catalogued into the site yet! Equipment you can see here is only meant for testing purposes.</li>
  </ul>
<?php
}

//Login and registration

function display_login_form() {
?>
  <p><a href="register_form.php">Not a member?</a></p>
  <form method="post" action="member.php">
  <table bgcolor="#CCE6FF">
   <tr>
     <td colspan="2">Members log in here:</td>
   <tr>
     <td>Username:</td>
     <td><input type="text" name="username"/></td></tr>
   <tr>
     <td>Password:</td>
     <td><input type="password" name="passwd"/></td></tr>
   <tr>
     <td colspan="2" align="center">
     <input type="submit" value="Log in"/></td></tr>
   <tr>
     <td colspan="2"><a href="forgot_form.php">Forgot your password?</a></td>
   </tr>
 </table></form>
<?php
}

function display_registration_form() {
?>
 <form method="post" action="register_new.php">
 <table bgcolor="#CCE6FF">
   <tr>
     <td><strong>First Name:</strong>:</td>
     <td><input type="text" name="firstname" size="15" maxlength="25"/></td></tr>
   <tr>
   
   <tr>
     <td><strong>Last Name:</strong>:</td>
     <td><input type="text" name="lastname" size="15" maxlength="25"/></td></tr>
   <tr>
       
  <tr>
    <td><strong>Email:</strong></td>
    <td><input type="text" name="email" size="15" maxlength="100"/></td></tr>

   
     <td><strong>Preffered Username:</strong> <br />(max 16 chars):</td>
     <td valign="top"><input type="text" name="username"
         size="15" maxlength="16"/></td></tr>
   <tr>
     <td><strong>Password:</strong> <br />(between 6 and 16 chars):</td>
     <td valign="top"><input type="password" name="passwd"
         size="15" maxlength="16"/></td></tr>
   <tr>
     <td><strong>Confirm password:</strong></td>
     <td><input type="password" name="passwd2" size="15" maxlength="16"/></td></tr>
   
   <tr>
     <td><strong>Description:</strong> <br />
		 Please describe your <br />
		 involvement with EWB,<br />
		 i.e. what project/s you <br />
         are part of and roles<br />
         you have.</td>
       
     <td><textarea type="text" name="description" size="30" maxlength="500" rows="6"/></textarea></td></tr>
   
   <tr>
     <td colspan=2 align="center">
     <input type="submit" value="Register"></td></tr>
	 
	 
 </table></form>
<?php

}


// Checkout functions

function display_user_checkouts($usercheckout_array) {
  // display the table of user's checkouts
  // usercheckout_array contains the ids of the items the user has checked out
  // set global variable, so we can test later if this is on the page
  global $return_table;
  $return_table = true;
?>
  <br />


<strong>
Below, you will find the items that are currently checked out under
your name. Once you have returned an item, please select it on the table below.
Write any details regarding the item condition before and
after it was used as well as any other concerns. </br>
</strong>


 <br />  <br />
  <form name="return_table" method="post" action="return_items.php" onsubmit="return_items.php">
  <table width="500" cellpadding="6" cellspacing="1" border="1">
  <?php
  $color = "#7aacdb";
  echo "<tr bgcolor=\"".$color."\"><td><strong>Chk ID</strong></td>";
  echo "<td><strong>Item Name</strong></td>";
  echo "<td><strong>Returned?</strong></td>";    
  echo "<td><strong>Notes</strong></td></tr>";
  if ((is_array($usercheckout_array)) && (count($usercheckout_array) > 0)) {
    foreach ($usercheckout_array as $chk)  {
      if ($color == "#CCE6FF") {
        $color = "#ffffff";
      } else {
        $color = "#CCE6FF";
      }
	  //Need to change how the items are displayed. Instead of displaying ID, we want to display the names
        
        $name = checkoutID2name($chk);
        
      echo "<tr bgcolor=\"".$color."\">
      
			<td><a href=past_details.php?checkoutID=".$chk.">".$chk."</a></td>
            <td height='1'>".$name."</td>
            <td><input type=\"checkbox\" name=\"return_id[]\" value=\"".$chk."\"/></td>
			<td><input type=\"text\" name=\"return_notes[]\" size=\"25\" maxlength=\"100\" value=\"\"/></td>
            </tr>";
    }
  } else {
    echo "<tr><td>There are no checked out items under this account.</td></tr>";
  }
?>
  </table>
  </form>
<?php
}


function display_user_menu() {
  // Display the menu options on this page (USER PAGE BLOCK)
?>
<hr />
<a href="member.php">Home</a> &nbsp;|&nbsp;
<a href="checkout_options.php">Checkout Item</a> &nbsp;|&nbsp;
<?php
  // only offer the return option if return table is on this page
  global $return_table;
  if ($return_table == true) {
    echo "<a href=\"#\" onClick=\"return_table.submit();\"> Return Selected </a> &nbsp;|&nbsp;";
  } else {
    echo "<span style=\"color: #888\">Return Selected</span> &nbsp;|&nbsp;";
  }
?>
<a href="past_options.php">View History of Checkouts</a> &nbsp;|&nbsp;


<a href="change_passwd_form.php">Change password</a> &nbsp;|&nbsp;
<a href="admin_panel.php">Admin Panel</a> &nbsp;|&nbsp;
<a href="logout.php">Logout</a>
<hr />

<?php
}


//Inventory and Checkout

function display_checkout_searchcriteria(){
?>

<br />

<strong>
Search criteria: </br>
</strong><br />

  <form name="checkout_search" method="post" action="checkout_options.php" onsubmit="checkout_options">
  <table width="500" cellpadding="6" cellspacing="1" border="1">
  
  <?php
  $color = "#7aacdb";
  echo "<tr bgcolor=\"".$color."\"><td><strong>Item ID</strong></td>";
  echo "<td><strong>Item Category</strong></td>";
  echo "<td><strong>Name</strong></td>";
  echo "<td><strong>Available?</strong></td>
  </tr>";
  ?>
  
  <tr>
  <td> <input type="text" name="itemID" size="4" maxlength="4" value=""/></td>
  <td> <input type="text" name="itemGroup" maxlength="50" value=""/></td>
  <td> <input type="text" name="name" maxlength="50" value=""/></td>
  <td> <select name="available" value="2">
  <option value="2">All</option>
  <option value="1">Yes</option>
  <option value="0">No</option>
  </td>
  </tr>
  </table>

  <br>
  <input type="submit" value="Search">
  </form>

<?php
}

function display_checkout_options(){

//This displays a table of all items that fit search criteria of the form
$itemID = $_POST['itemID'];
$itemGroup = $_POST['itemGroup'];
$name = $_POST['name'];
$available = $_POST['available'];
        
//We obtain an array with all the itemIDs based on the search criteria
$inventory_array = get_checkout_options($itemID,$itemGroup,$name,$available);

if ((is_array($inventory_array)) && (count($inventory_array) > 0)) {
    
?>
 <br />
  <form name="return_table" method="post">
  <table width="1000" cellpadding="6" cellspacing="1" border="1">
  
  <?php

  $color = "#7aacdb";
  echo "<tr bgcolor=\"".$color."\">";
  echo "<td><strong>Item ID</strong></td>";
  echo "<td><strong>Name</strong></td>";
  echo "<td><strong>Item Category</strong></td>";
  echo "<td><strong>Quantity</strong></td>";
  echo "<td><strong>Desription</strong></td>";
  echo "<td><strong>Last Checkout</strong></td>";
  echo "<td><strong></strong></td></tr>";

    foreach ($inventory_array as $QitemID)  {
        
        $row = get_inventory_row($QitemID);
        
        $QitemGroup = $row[1];
        $Qname = $row[2];
        $Qquantity = $row[3];
        $Qdescription = $row[4];
        $Qavailable = $row[5];
        $QlastCheckoutID = get_last_item_checkoutID($QitemID);        
        $QlastCheckoutFullName = checkoutID2fullname($QlastCheckoutID);
                
        $checkoutlink;
        
		if ($_SESSION['checkoutAuth'] != 1) {
			$color = "#fcb3b3";
			$checkoutlink = "Not authorized";
		}
        else if ($Qavailable != 0) {
        $color = "#a7fab7";
        $checkoutlink = "<a href=checkout_form.php?itemID=" . $QitemID .">Checkout!</a>";
		} 
		else {
        $color = "#fcb3b3";
        $checkoutlink = "Not available";
		}
        
//        $checkoutlink;
        
      echo "<tr bgcolor=\"".$color."\">
			<td>".$QitemID."</td>      
			<td>".$Qname."</td>
            <td>".$QitemGroup."</td>
            <td>".$Qquantity."</td>
            <td>".$Qdescription."</td>
            <td> #".$QlastCheckoutID." - ".$QlastCheckoutFullName." </td>
            <td> ".$checkoutlink."</td>
            <form>
            </tr>";
    }
    ?>
  </table>
  </form>

<?php
  } else {
    
    echo "There are no available items to display. <br>";
  }
}

function display_checkout_details($itemID) {
// Displays info for a particular item
    $row = get_inventory_row($itemID);
    
    $Qname = $row[2];
    $Qquantity = 1;#$row[3];
    $Qdescription = $row[4];    

    echo "* You are checking out a: <strong>".$Qname."</strong><br><br>";
    echo "* Quantity: <strong>".$Qquantity."</strong><br><br>";
    echo "* Description: <strong>".$Qdescription."</strong><br><hr /><br>";
    echo "* Please fill out everything in the form below: <br>";
    
}

function display_checkout_form($itemID){
// Displays form with info for checkout date, intended return date, and purpose of checkout
   ?>
 <form method="post" action=<?php echo "checkout_script.php?itemID=". $itemID ."" ?>>
 <table bgcolor="#CCE6FF">
   <tr>
     <td><strong>Pickup Date:</strong>:</td>
     <td><input type="date" name="checkoutDay" />
     <input type="time" name="checkoutTime" value="12:00"/></tr>
   <tr>
       
    <tr>
     <td><strong>Return Date:</strong>:</td>
     <td><input type="date" name="returnDay" />
     <input type="time" name="returnTime" value="12:00"/></tr>
   <tr>
   
   <tr>
     <td><strong>Purpose:</strong> <br />
		 Please describe the reason<br />
         this item is being checked<br />
         out, and how you intend to <br />
         use it. <br /></td>
       
     <td><textarea type="text" name="purpose" size="30" maxlength="500" rows="6"/></textarea></td></tr>
   
   <tr>
     <td colspan=2 align="center">
     <input type="submit" value="Submit"></td></tr>
	 
	 
 </table></form>
<?php
}

//History Options

function display_past_searchcriteria() {
//Displays all past/current checkouts that fit search criteria

    ?>

<br />

<strong>
Search criteria: </br>
</strong><br />

  <form name="past_search" method="post" action="past_options.php" onsubmit="past_options.php">
  <table width="500" cellpadding="6" cellspacing="1" border="1">
  
  <?php
  $color = "#7aacdb";
  echo "<tr bgcolor=\"".$color."\">";
  echo "<td><strong>Item ID</strong></td>";
  echo "<td><strong>Chk ID</strong></td>";
  echo "<td><strong>First Name</strong></td>";
  echo "<td><strong>Last Name</strong></td>";
  echo "<td><strong>Item Category</strong></td>";
  echo "<td><strong>Item Name</strong></td>";
  echo "<td><strong>Returned?</strong></td></tr>";
  ?>
  
  <tr>
  <td> <input type="text" name="itemID" size="4" maxlength="4" value=""/></td>
  <td> <input type="text" name="checkoutID" size="4" maxlength="4" value=""/></td>
  <td> <input type="text" name="firstName" size="12" maxlength="25" value=""/></td>
  <td> <input type="text" name="lastName" size="12" maxlength="25" value=""/></td>
  <td> <input type="text" name="itemGroup" size="12" maxlength="50" value=""/></td>
  <td> <input type="text" name="name" size="12" maxlength="50" value=""/></td>
  <td> <select name="available" value="2">
  <option value="2">All</option>
  <option value="1">Yes</option>
  <option value="0">No</option>
  </td>
  </tr>
  </table>

  <br>
  <input type="submit" value="Search">
  </form>

<?php
    
}

function display_past_options() {
//Displays all past/current details that fit search criteria

    //This displays a table of all items that fit search criteria of the form
    $itemID = $_POST['itemID'];
    $checkoutID = $_POST['checkoutID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $itemGroup = $_POST['itemGroup'];
    $name = $_POST['name'];
    $available = $_POST['available'];

    //We obtain an array with all the itemIDs based on the search criteria
    $history_array = get_past_options($itemID,$checkoutID,$firstName,
                                      $lastName,$itemGroup,$name,$available);
    
        
    if ((is_array($history_array)) && (count($history_array) > 0)) {
    ?>
     <br />
      <form name="return_table" method="post">
      <table width="1000" cellpadding="6" cellspacing="1" border="1">

      <?php

      $color = "#7aacdb";
      echo "<tr bgcolor=\"".$color."\">";
      echo "<td><strong>Checkout ID</strong></td>";
      echo "<td><strong>Item ID</strong></td>";
      echo "<td><strong>Name</strong></td>";
      echo "<td><strong>Item Name</strong></td>";
      echo "<td><strong>Item Category</strong></td>";
      echo "<td><strong>Pick Up Date</strong></td>";
      echo "<td><strong>Return Date</strong></td>";
      echo "<td><strong>Actual Return Date</strong></td>";
      echo "<td><strong></strong></td></tr>";

        foreach ($history_array as $QcheckoutID)  {
            //
            $checkoutRow = get_checkout_row($QcheckoutID);
            //
            
            $QitemID = $checkoutRow[2];
            
            //
            $inventoryRow = get_inventory_row($QitemID);
            //
            
            $Qfullname = checkoutID2fullname($QcheckoutID);
            $Qname = $inventoryRow[2];
            $QitemGroup = $inventoryRow[1];
            $QcheckoutDate = $checkoutRow[4];
            $QintendedReturnDate = $checkoutRow[5];        
            $QreturnDate = $checkoutRow[6];
            
            $Qreturned = ($QreturnDate != "0000-00-00 00:00:00");
            $returnDateSub;
            
            

            if ($Qreturned) {
            $color = "#a7fab7";
            $returnDateSub = $QreturnDate;
          } else {
            $color = "#fcb3b3";
            $returnDateSub = "NOT RETURNED";
          }
            
            $detailslink = "<a href=past_details.php?checkoutID=" . $QcheckoutID .">Details</a>";

          echo "<tr bgcolor=\"".$color."\">
                <td>".$QcheckoutID."</td> 
                <td>".$QitemID."</td>
                <td>".$Qfullname."</td>
                <td>".$Qname."</td>
                <td>".$QitemGroup."</td>
                <td>".$QcheckoutDate."</td>
                <td>".$QintendedReturnDate."</td>
                <td>".$returnDateSub."</td>
                <td> ".$detailslink."</td>
                <form>
                </tr>";
        }
        ?>
      </table>
      </form>

    <?php
      } else {

        echo "There are no checkouts to display. <br>";
      }
    
}

function display_past_details() {

    $checkoutID = $_GET['checkoutID'];
    
    //
    $checkoutRow = get_checkout_row($checkoutID);
    //
    
    $itemID=$checkoutRow[2];
    $username=$checkoutRow[1];
    
    //
    $inventoryRow = get_inventory_row($itemID);
    //
    
    //
    $userRow = get_user_row($username);
    //
    
    $fullName = checkoutID2fullname($checkoutID);
    $username;
    $email = $userRow[4];
    $userDescription = $userRow[5];
    
    $itemID;
    $name = $inventoryRow[2];
    $itemGroup = $inventoryRow[1];
    $description = $inventoryRow[4];
    $quantity = $inventoryRow[3];
    
    $purpose = $checkoutRow[7];
    $returnNotes = $checkoutRow[8];
    
    $creationDate = $checkoutRow[3];
    $pickupDate = $checkoutRow[4];
    $returnDate = $checkoutRow[5];
    $actualReturnDate = $checkoutRow[6];

    
    echo "<br>
    <big>Details for Checkout # ".$checkoutID.": </big><br><br>
    
    <strong> * Name : </strong>".$fullName."<br><br>
    <strong> * Username : </strong>".$username."<br><br>
    <strong> * Email : </strong>".$email."<br><br>
    <strong> * User Descrption : </strong>".$userDescription."<br><hr /><br>
    
    <strong> * Item ID : </strong>".$itemID."<br><br>
    <strong> * Item Name : </strong>".$name."<br><br>
    <strong> * Item Category : </strong>".$itemGroup."<br><br>
    <strong> * Item Description : </strong>".$description."<br><br>
    <strong> * Item Quantity : </strong>".$quantity."<br><hr /><br>
    
    <strong> * Checkout Purpose: </strong>".$purpose."<br><br>
    <strong> * Checkout Return Notes: </strong>".$returnNotes."<br><hr /><br>
    
    <strong> * Checkout Creation Date: </strong>".$creationDate."<br><br>
    <strong> * Pickup Date : </strong>".$pickupDate."<br><br>
    <strong> * Return Date : </strong>".$returnDate."<br><br>
    <strong> * Actual Return Date : </strong>".$actualReturnDate."<br><br>";
}



// Password stuff

function display_password_form() {
  // display html change password form
?>
   <br />
   <form action="change_passwd.php" method="post">
   <table width="250" cellpadding="2" cellspacing="0" bgcolor="#CCE6FF">
   <tr><td>Old password:</td>
       <td><input type="password" name="old_passwd"
            size="16" maxlength="16"/></td>
   </tr>
   <tr><td>New password:</td>
       <td><input type="password" name="new_passwd"
            size="16" maxlength="16"/></td>
   </tr>
   <tr><td>Repeat new password:</td>
       <td><input type="password" name="new_passwd2"
            size="16" maxlength="16"/></td>
   </tr>
   <tr><td colspan="2" align="center">
       <input type="submit" value="Change password"/>
   </td></tr>
   </table>
   <br />
<?php
}

function display_forgot_form() {
  // display HTML form to reset and email password
?>
   <br />
   <form action="forgot_passwd.php" method="post">
   <table width="250" cellpadding="2" cellspacing="0" bgcolor="#cccccc">
   <tr><td>Enter your username</td>
       <td><input type="text" name="username" size="16" maxlength="16"/></td>
   </tr>
   <tr><td colspan=2 align="center">
       <input type="submit" value="Change password"/>
   </td></tr>
   </table>
   <br />
<?php
}

?>
