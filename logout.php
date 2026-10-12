<?php


session_start();



$userFile="users.json";



$userId=$_SESSION["user_id"] ?? null;





if(

$userId !== null

&&

file_exists($userFile)

){



$users=json_decode(

file_get_contents($userFile),

true

);






if(is_array($users)){



foreach($users as $index=>$user){





if(

$user["id"]

==

$userId

){



unset(

$users[$index]["token"]

);



break;



}



}






file_put_contents(

$userFile,

json_encode(

array_values($users),

JSON_PRETTY_PRINT |

JSON_UNESCAPED_UNICODE

)

);



}



}








// حذف remember cookie



setcookie(

"remember_token",

"",

time()-3600,

"/"

);







// حذف session



$_SESSION=[];





if(ini_get("session.use_cookies")){



$params=session_get_cookie_params();





setcookie(

session_name(),

"",

time()-42000,

$params["path"],

$params["domain"],

$params["secure"],

$params["httponly"]

);



}







session_destroy();








header(

"Location: login.php"

);


exit;



?>