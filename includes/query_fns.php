<?php
require_once('db_fns.php');

//Selects and outputs values from DB

function get_user_checkouts($username) {
  //extract from the database all the checkouts that the user has not returned

  $conn = db_connect();
  $result = $conn->query("select checkoutID
                          from checkouts
                          where username = '".$username."'
                          and returnDate = '0000-00-00 00:00:00'");
  if (!$result) {
    return false;
  }

  //create an array of the checkouts
  $usercheckout_array = array();
  for ($count = 1; $row = $result->fetch_row(); ++$count) {
    $usercheckout_array[$count] = $row[0];
  }
  return $usercheckout_array;
}

function get_checkout_options($itemID,$itemGroup,$name,$available)
{
  $conn = db_connect();
  $itemID_q; $itemGroup_q; $name_q; $available_q;
        
  //Sets query search parameters
    
  if(empty($itemID))
  {
      $itemID_q = "1=1";
  }
  else
  {
      $itemID_q = "itemID = '".$itemID."' ";
  }
    
  if($itemGroup == "")
  {
      $itemGroup_q = "1=1";
  }
  else
  {
      $itemGroup_q = "itemGroup LIKE '%".$itemGroup."%' ";
  }
    
  if($name == "")
  {
      $name_q = "1=1";
  }
  else
  {
      $name_q = "name LIKE '%" . $name . "%' ";
  }
    
  if($available == "2" | $available == "")
  {
      $available_q = "1=1";
  }
  else
  {
      $available_q = "available = '" . $available . "'";
  }

    
  $result = $conn->query("select itemID
                          from inventory
                          where " . $itemID_q .
                          " and " . $itemGroup_q .
                          " and " . $name_q .
                          " and " . $available_q
                        );
 //create an array of the items
  $checkoutoptions_array = array();
  for ($count = 1; $row = $result->fetch_row(); ++$count) {
    $checkoutoptions_array[$count] = $row[0];
  }

  return $checkoutoptions_array;
}

function get_past_options($itemID,$checkoutID,$firstName,
                          $lastName,$itemGroup,$name,$available)
{
  $username = firstandlast2username($firstName,$lastName);

  $conn = db_connect();
    
  $checkoutID_q;$itemID_q;$itemGroup_q;$name_q;$username_q;$available_q;
        
  //Sets query search parameters
    
  if(empty($checkoutID))
  {
      $checkoutID_q = "1=1";
  }
  else
  {
      $checkoutID_q = "checkouts.checkoutID = '".$checkoutID."' ";
  }
    
  if(empty($itemID))
  {
      $itemID_q = "1=1";
  }
  else
  {
      $itemID_q = "checkouts.itemID = '".$itemID."' ";
  }
            
  if($itemGroup == "")
  {
      $itemGroup_q = "1=1";
  }
  else
  {
      $itemGroup_q = "inventory.itemGroup LIKE '%" . $itemGroup . "%' ";
  }
    
  if($name == "")
  {
      $name_q = "1=1";
  }
  else
  {
      $name_q = "inventory.name LIKE '%" . $name . "%' ";
  }
    
  if($available == "2" | $available == "")
  {
      $available_q = "1=1";
  }
  else
  {
      $available_q = "inventory.available = '".$available."' ";
  }
    
  if(!empty($username))
  {
      $username_q = "checkouts.username = ". $username;
  }
  else
  {
      $username_q = "1=1";
  }

    
  $result = $conn->query("SELECT checkoutID
                          FROM checkouts
                          LEFT JOIN inventory ON checkouts.itemID = inventory.itemID
                          WHERE " . $checkoutID_q .
                          " and " . $itemID_q .
                          " and " . $name_q .
                          " and " . $username_q .
                         " and " . $available_q
                        );
    
 //create an array of the items
  $pastoptions_array = array();
  for ($count = 1; $row = $result->fetch_row(); ++$count) {
    $pastoptions_array[$count] = $row[0];
  }

  return $pastoptions_array;
}


function checkoutID2name($checkoutID)
{
    $item_id = checkoutID2itemID($checkoutID);
    
    $conn = db_connect();
    $name_result =
    $conn->query("SELECT name from inventory where itemID = '".$item_id."'");

    $name = $name_result->fetch_row()[0];
    return $name;

}

function checkoutID2itemID($checkoutID)
{
    $conn = db_connect();
        
    $item_id_result =
    $conn->query("SELECT itemID from checkouts where checkoutID = '".$checkoutID."'");

    $item_id = $item_id_result->fetch_row()[0];
    
    return $item_id;

}

function checkoutID2username($checkoutID)
{
    $conn = db_connect();
        
    $username_result = 
    $conn->query("SELECT username from checkouts where checkoutID = '".$checkoutID."'");

    $username = $username_result->fetch_row()[0];
    
    return $username;
}

function checkoutID2fullname($checkoutID)
{
    $username = checkoutID2username($checkoutID);
    
    $conn = db_connect();
    
    $fullname_result =
    $conn->query("SELECT firstname, lastname from users where username = '".$username."'");
    $row = mysqli_fetch_row($fullname_result);
    $firstName = $row[0];
    $lastName = $row[1];
    
    
    $fullName = $firstName . " " . $lastName;
    
    return $fullName;
}

function firstandlast2username($firstName,$lastName)
{
    $conn = db_connect();
    
    $username_result =
    $conn->query("SELECT username from users
                where firstName LIKE '%" . $firstName . "%'
                and lastName LIKE '%" . lastName . "%'");
    $username = $username_result->fetch_row()[0];
    return $username;
}

function get_inventory_row($itemID)
{    
    $conn = db_connect();
    
    $query_result =
    $conn->query("SELECT * from inventory where itemID = '".$itemID."'");
    $row = mysqli_fetch_row($query_result);
        
    return $row;
}

function get_checkout_row($checkoutID)
{    
    $conn = db_connect();
    
    $query_result =
    $conn->query("SELECT * from checkouts where checkoutID = '".$checkoutID."'");
    $row = mysqli_fetch_row($query_result);
        
    return $row;
}

function get_user_row($username)
{    
    $conn = db_connect();
    
    $query_result =
    $conn->query("SELECT * from users where username = '".$username."'");
    $row = mysqli_fetch_row($query_result);
        
    return $row;
}

function itemID2available($itemID)
{
    $row = get_inventory_row($itemID);    
    return $row[5];
}

function get_last_item_checkoutID($itemID)
{        
    $conn = db_connect();
    
    $query_result = 
    $conn->query("SELECT MAX(checkoutID) FROM checkouts WHERE itemID = '".$itemID."'");

    $maxID = $query_result->fetch_row()[0];
    
    return $maxID;
}

//Inserts values into DB

function insert_checkout($username, $itemID, $creationDate, $checkoutDate, $intendedReturnDate, $checkoutNotes)
{
  $conn = db_connect();
  $null_returnDate = "0000-00-00 00:00:00";
  $null_returnNotes = "ITEM HAS NOT BEEN RETURNED";

  // Inserts values into checkout table
  $result = $conn->query("INSERT INTO `checkouts` (`checkoutID`, `username`, `itemID`, `creationDate`, `checkoutDate`, `intendedReturnDate`, `returnDate`, `checkoutNotes`, `returnNotes`) VALUES ('NULL', '".$username."', '".$itemID."', '".$creationDate."', '".$checkoutDate."', '".$intendedReturnDate."', '".$null_returnDate."', '".$checkoutNotes."', '".$null_returnNotes."')");
     
  if (!$result) {
    throw new Exception('Can not checkout item at this time. Please try again later.');
  }

  // Reduces available count
	$result3 = $conn->query("select available 
							 FROM inventory
							 WHERE itemID = '".$itemID."' ");
	$stocked;							
	while ($row = $result3->fetch_array(MYSQLI_BOTH)) {
    $stocked=$row[0];
	} 
	$stocked--;//todo return multiple at once
	 
  $result = $conn->query("update inventory
                          set available = '".$stocked."'
                          where itemID = '".$itemID."' ");
     
  if (!$result) {
    throw new Exception('Can not checkout item at this time.. Please try again later.');
  }
    
  return true;
}

function insert_item($itemGroup, $name, $quantity, $description)
{
  $conn = db_connect();
  $default_available = "1";

  // Inserts values into checkout table
  $result = $conn->query("INSERT INTO inventory (itemID ,itemGroup ,name ,quantity ,description ,available)
  
                         VALUES (NULL , '".$itemGroup."',  '".$name."',  '".$quantity."',  '".$description."',  '".$quantity."')");

     
  if (!$result) {
    throw new Exception('Can not insert item at this time. Please try again later.');
  }
    
  return true;
}

function update_item($itemID, $itemGroup, $name, $quantity, $description, $checkedout)
{
  $conn = db_connect();
  $default_available = "1";
  $available=$quantity-$checkedout;
  // Inserts values into checkout table
  $result = $conn->query("UPDATE inventory 
							SET itemGroup = '$itemGroup',
								name = '$name',
								quantity = '$quantity',
								description = '$description',
								available = '$available'
								
								WHERE itemID = '$itemID'");


     
  if (!$result) {
    throw new Exception('Can not update item at this time. Please try again later.');
  }
    
  return true;
}


//Inserts values from DB

function delete_item($itemID)
{
  $conn = db_connect();
  $default_available = "1";

  // Deletes item from inventory
  $result = $conn->query("DELETE FROM inventory
  WHERE itemID='".$itemID."' ");
     
  if (!$result) {
    throw new Exception('Can not delete item at this time.');
  }
    
  return true;
}

?>
