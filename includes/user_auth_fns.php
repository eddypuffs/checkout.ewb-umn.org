<?php

require_once('db_fns.php');

function register($username, $email, $password, $firstName, $lastName, $userDescription) {
// register new person with db
// return true or error message

  // connect to db
  $conn = db_connect();

  // check if username is unique
  $result = $conn->query("select * from users where username='".$username."'");
  if (!$result) {
    throw new Exception('Could not execute query');
  }

  if ($result->num_rows>0) {
    throw new Exception('That username is taken - go back and choose another one.');
  }

  // if ok, put in db
  $result = $conn->query("insert into users values
                         ('".$username."', sha1('".$password."'), '".$firstName."',                               '".$lastName."', '".$email."', '".$userDescription."',0)");
    
  if (!$result) {
    throw new Exception('Could not register you in database - please try again later.');
  }

  return true;
}

function login($username, $password) {
// check username and password with db
// if yes, return true
// else throw exception

  // connect to db
  $conn = db_connect();

  // check if username is unique
  $result = $conn->query("select * from users
                         where username='".$username."'
                         and passwd = sha1('".$password."')");
  
  $row = mysqli_fetch_row($result);
  if (!$result) {
     throw new Exception('Could not log you in.');
  }

  if ($result->num_rows>0) {
     return $row[6]+1;
  } else {
     throw new Exception('Could not log you in.');
  }
}

function check_valid_user() {
// see if somebody is logged in and notify them if not
  if (isset($_SESSION['valid_user']))  {
      echo "<font color='green'> Logged in as ".$_SESSION['valid_user']."</font>.<br />";
  } else {
     // they are not logged in
     do_html_heading('Problem:');
     echo 'You are not logged in.<br />';
     do_html_url('login.php', 'Login');
     do_html_footer();
     exit;
  }
}

function check_valid_admin() {
// see if somebody is logged in and notify them if not
  if ($_SESSION['valid_user'] == "admin")
  {
      echo "YOU ARE ADMIN :)";
  }
  else if (isset($_SESSION['valid_user']))  {
      // they are not admin
     do_html_heading('Problem:');
     echo 'You are not admin.<br />';
     do_html_url('member.php', 'Home');
     do_html_footer();
     exit;
  } else {
     // they are not logged in
     do_html_heading('Problem:');
     echo 'You are not logged in.<br />';
     do_html_url('login.php', 'Login');
     do_html_footer();
     exit;
  }
}


function change_password($username, $old_password, $new_password) {
// change password for username/old_password to new_password
// return true or false

  // if the old password is right
  // change their password to new_password and return true
  // else throw an exception
  login($username, $old_password);
  $conn = db_connect();
  $result = $conn->query("update users
                          set passwd = sha1('".$new_password."')
                          where username = '".$username."'");
  if (!$result) {
    throw new Exception('Password could not be changed.');
  } else {
    return true;  // changed successfully
  }
}

function get_random_word($min, $max){
 # get random character length between minimum and maximum length
 $length = rand($min, $max);
 $string = '';
 # character index [0-9a-zA-Z]
 $index = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 # loop random times defined by $length
 for ($i=0; $i < $length; $i++) {
  # get random character index
  $string .= $index[rand(0, strlen($index) -1)];
 }
 return $string;
}

function reset_password($username) {
// set password for username to a random value
// return the new password or false on failure
  // get a random dictionary word b/w 6 and 13 chars in length
  $new_password = get_random_word(6, 13);

  echo "new pass = ".$new_password."<br>";

  if($new_password == false) {
    throw new Exception('Could not generate new password.');
  }
    
//
//  // add a number  between 0 and 999 to it
//  // to make it a slightly better password
//  $rand_number = rand(0, 999);
//  $new_password .= $rand_number;

  // set user's password to this in database or return false
  $conn = db_connect();
  $result = $conn->query("update users
                          set passwd = sha1('".$new_password."')
                          where username = '".$username."'");
  if (!$result) {
    throw new Exception('Could not change password.');  // not changed
  } else {
    return $new_password;  // changed successfully
  }
}

function notify_password($username, $password) {
// notify the user that their password has been changed

    
    $conn = db_connect();
    $result = $conn->query("select email from users
                            where username='".$username."'");    
    if (!$result) {
      throw new Exception('Could not find email address.');
    } else if ($result->num_rows == 0) {
      throw new Exception('Could not find email address.');
      // username not in db
    } else {
      $row = $result->fetch_object();
      $email = $row->email;
      $mesg = "Your EWB Checkout password has been changed to ".$password."\r\n"
              ."Please change it next time you log in.\r\n";

      if (mail($email, 'EWB Checkout login information', $mesg)) {
        return true;
      } else {
        throw new Exception('Could not send email.');
      }
    }
}

?>
