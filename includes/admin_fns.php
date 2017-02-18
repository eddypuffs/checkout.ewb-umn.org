<?php

    function display_takeover_user()
    {
        echo "<form method='post' action=''>
        <strong> * Login as any registered user:</strong>
        <input typetype='text' name='xUser' size='10' maxlength='20' value=''>
        <input type='submit' value='Submit'>
        </form>";
        
        $xUser = $_POST['xUser'];
        
        if (isset($xUser))
        {
            try
            {
                $auth=check_user_exists($xUser);
                $_SESSION['valid_user'] = $xUser;
				if ($auth==2) {
					$_SESSION['checkoutAuth'] = 1;
				}
				else {
					unset($_SESSION['checkoutAuth']);
				}
                echo "<font color='red'> Override login user ".$xUser." performed succesfully. </font>";
                return;
            }
            catch (Exception $e)
            {
            echo "<font color='red'>".$e->getMessage()."</font>";
            }
        }
        
        echo "<br><br>";
    }

	function display_authorize_user()
    {
        echo "<form method='post' action=''>
        <strong> * Authorize any user to checkout:</strong>
        <input typetype='text' name='aUser' size='10' maxlength='20' value=''>
        <input type='submit' value='Submit'>
        </form>";
        
        $aUser = $_POST['aUser'];
        
        if (isset($aUser))
        {
            try
            {
				authorize_user($aUser);
                echo "<font color='red'> Authorize user ".$aUser." performed succesfully. </font>";
                return;
            }
            catch (Exception $e)
            {
            echo "<font color='red'>".$e->getMessage()."</font>";
            }
        }
        
        echo "<br><br>";
    }
	
	function authorize_user($aUser)
    {
        $conn = db_connect();
		$result = $conn->query("UPDATE users 
							SET CheckoutAuth = 1
								
								WHERE username = '$aUser'");
        if (!$result) {
        throw new Exception('Could not execute query');
        }
        return true;
    }
	
	function display_deauthorize_user()
    {
        echo "<form method='post' action=''>
        <strong> * Deauthorize any user to checkout:</strong>
        <input typetype='text' name='dUser' size='10' maxlength='20' value=''>
        <input type='submit' value='Submit'>
        </form>";
        
        $dUser = $_POST['dUser'];
        
        if (isset($dUser))
        {
            try
            {
				deauthorize_user($dUser);
                echo "<font color='red'> Deauthorize user ".$dUser." performed succesfully. </font>";
                return;
            }
            catch (Exception $e)
            {
            echo "<font color='red'>".$e->getMessage()."</font>";
            }
        }
        
        echo "<br><br>";
    }
	
	function deauthorize_user($dUser)
    {
        $conn = db_connect();
		$result = $conn->query("UPDATE users 
							SET CheckoutAuth = 0
								
								WHERE username = '$dUser'");
        if (!$result) {
        throw new Exception('Could not execute query');
        }
        return true;
    }
	
    function check_user_exists($xUser)
    {
        $conn = db_connect();
        $result = $conn->query("select * from users where username='".$xUser."'");
        $row = mysqli_fetch_row($result);
        if (!$result) {
        throw new Exception('Could not execute query');
        }

        if ($result->num_rows>0) {
            return $row[6]+1;
        }
        else
        {
            throw new Exception('That is not a registered username.');
        }
    }

    function display_newitem_form()
    {
        echo "<form method='post' action=''>";
        echo "<strong>* Add a new item to the inventory: </strong><br><br>";
        echo "<table>";
        echo "<tr><td> Category: </td>
        <td> <select name='itemGroup'> 
				<option value='Cement Tools'>Cement Tools</option>
				<option value='Hand Tools'>Hand Tools</option>
				<option value='Power Tools'>Power Tools</option>
				<option value='Protective Equipment'>Protective Equipment</option>
				<option value='In Country'>In Country</option>
				<option value='Misc.'>Misc.</option>
				</select></td></tr>";
        echo "<tr><td> Name: </td> <td> <input typetype='text' name='name' size='10' maxlength='50' value=''> <td></tr>";
        echo "<tr><td> Quantity: </td>
        <td> <input type='text' name='quantity' size='3' maxlength='3' rows='3' value=''></textarea></td></tr>";
        echo "<tr><td> Description: </td>
        <td> <textarea type='text' name='description' size='30' maxlength='100' rows='3' value=''></textarea><td></tr>";    
        echo "</table>";
        echo "<input name='submit' type='submit' value='Submit'>";
        echo "</form><br>";
        
        $itemGroup = $_POST['itemGroup'];
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $description = $_POST['description'];
        

        if ($itemGroup!='' & $name!='')
        {
            try
            {
                insert_item($itemGroup, $name, $quantity, $description);
                echo "<font color='red'> Item was inserted succesfully. </font>";
                return;
            }
            catch (Exception $e)
            {
            echo "<font color='red'>".$e->getMessage()."</font>";
            }
        }
        
        echo "<br>";
    }

    function display_item_delete()
    {
        echo "<form method='post' action=''>
        <strong> * Delete item #:</strong>
        <input typetype='text' name='itemID' size='4' maxlength='4' value=''>
        <input type='submit' value='Submit'>
        </form>";
        
        $itemID = $_POST['itemID'];
        if (isset($itemID))
        {
            try
            {
                delete_item($itemID);
                echo "<font color='red'> Item #".$itemID." was succesfully deleted. </font>";
                return;
            }
            catch (Exception $e)
            {
            echo "<font color='red'>".$e->getMessage()."</font>";
            }
        }
        
        echo "<br><br>";
    }
	
	function display_item_edit()
    {
        echo "<form method='post' action='edit_items.php'>
        <strong> * Edit item #:</strong>
        <input typetype='text' name='itemID' size='4' maxlength='4' value=''>
        <input type='submit' value='Submit'>
        </form>";
        
        $itemID = $_POST['itemID'];
                
        echo "<br><br>";
    }
	
	function display_edititem_form()
    {
		$itemID = $_POST['itemID'];
		#echo $itemID
		$row=get_inventory_row($itemID);
		#print_r($row);
		$Qitemgroup = $row[1];
		$Qname = $row[2];
		$Qquantity = $row[3];
		$Qdescription = $row[4];
		$Qcheckedout = $Qquantity-$row[5]; 	
		echo $row;
		echo "<form method='post' action=''>";
        echo "<strong>* Edit a item in the inventory: </strong><br><br>";
        echo "<table>";
        echo "<tr><td> Category: </td>
        <td> <select name='itemGroup'> 
				<option value='Cement Tools' <?php if($Qitemgroup == 'Cement Tools'){echo('selected');}?>Cement Tools</option>
				<option value='Hand Tools' <?php if($Qitemgroup == 'Hand Tools'){echo('selected');}?>Hand Tools</option>
				<option value='Power Tools' <?php if($Qitemgroup == 'Power Tools'){echo('selected');}?>Power Tools</option>
				<option value='Protective Equipment' <?php if($Qitemgroup == 'Cement Tools'){echo('selected');}?>Protective Equipment</option>
				<option value='In Country' <?php if($Qitemgroup == 'In Country'){echo('selected');}?>In Country</option>
				<option value='Misc.' <?php if($Qitemgroup == 'Misc.'){echo('selected');}?>Misc.</option>
				</select></td></tr>";
        echo "<tr><td> Name: </td> <td> <input typetype='text' name='name' size='10' maxlength='50' value='$Qname'> <td></tr>";
        echo "<tr><td> Quantity: </td>
        <td> <input type='text' name='quantity' size='3' maxlength='3' rows='3' value='$Qquantity'></textarea></td></tr>";
        echo "<tr><td> Description: </td>
        <td> <textarea type='text' name='description' size='30' maxlength='100' rows='3'>$Qdescription</textarea><td></tr>";   
		echo "<input type='hidden' name='itemID' value='$itemID'> ";
		echo "<input type='hidden' name='checkedout' value='$Qcheckedout'> ";
        echo "</table>";
        echo "<input name='submit' type='submit' value='Submit'>";
        echo "</form><br>";
        
        $itemGroup = $_POST['itemGroup'];
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $description = $_POST['description'];
        $itemID = $_POST['itemID'];
		$checkedout= $_POST['checkedout'];

        if ($itemGroup!='' & $name!='')
        {
            try
            {
                update_item($itemID, $itemGroup, $name, $quantity, $description, $checkedout);
                echo "<font color='red'> Item was changed succesfully. </font>";
                return;
            }
            catch (Exception $e)
            {
            echo "<font color='red'>".$e->getMessage()."</font>";
            }
        }
        
        echo "<br>";
    }
?>