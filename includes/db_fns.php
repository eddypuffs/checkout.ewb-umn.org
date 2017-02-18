<?php

function db_connect() {
   $result = new mysqli('CHANGED_FOR_GITHUB', 'CHANGED_FOR_GITHUB', 'CHANGED_FOR_GITHUB', 'CHANGED_FOR_GITHUB');
   if (!$result) {
     throw new Exception('Could not connect to database server');
   } else {
     return $result;
   }
}

?>
