<?php


require_once "auth.php";






function getCurrentUserIndex(){



global $users;



if(

!isset($_SESSION["user_id"])

){

return -1;

}







foreach($users as $index=>$user){



if(

$user["id"]

==

$_SESSION["user_id"]

){



return $index;



}



}





return -1;



}






?>