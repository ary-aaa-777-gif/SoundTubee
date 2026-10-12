<?php


session_start();



$userFile="users.json";


$message="";





if($_SERVER["REQUEST_METHOD"]=="POST"){



$username=trim($_POST["username"] ?? "");


$password=trim($_POST["password"] ?? "");


$remember=isset($_POST["remember"]);






if($username=="" || $password==""){



$message="❌ Please fill all fields";



}

else{





if(file_exists($userFile)){



$users=json_decode(

file_get_contents($userFile),

true

);



}

else{



$users=[];



}








if(!is_array($users)){



$users=[];



}








$found=false;






foreach($users as $index=>$user){





if(

strtolower($user["username"])

===

strtolower($username)

){



$found=true;







if(

password_verify(

$password,

$user["password"]

)

){






session_regenerate_id(true);







$_SESSION["user_id"]=$user["id"];


$_SESSION["username"]=$user["username"];


$_SESSION["role"]=$user["role"];









// ساخت توکن جدید



$token=bin2hex(

random_bytes(32)

);





$users[$index]["token"]=$token;










if($remember){





setcookie(

"remember_token",

$token,

time()+60*60*24*30,

"/"

);



}

else{



setcookie(

"remember_token",

"",

time()-3600,

"/"

);



}









file_put_contents(

$userFile,

json_encode(

$users,

JSON_PRETTY_PRINT |

JSON_UNESCAPED_UNICODE

)

);









header(

"Location: index.php"

);


exit;







}

else{



$message="❌ Incorrect password";



}





break;



}





}








if(!$found){



$message="❌ Username not found";



}





}



}



?>





<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Login</title>


<link rel="stylesheet" href="style.css">


</head>





<body>


<div class="container">



<div class="upload-box">





<h1>

👤 Login

</h1>






<p>

<?php echo $message; ?>

</p>







<form method="post">





<input

type="text"

name="username"

placeholder="Username"

required

>






<input

type="password"

name="password"

placeholder="Password"

required

>








<label style="display:block;margin:15px 0;color:white;">



<input

type="checkbox"

name="remember"

checked

>


Keep me logged in



</label>








<button type="submit">

Login

</button>






</form>








<a href="register.php">

Create new account

</a>







</div>


</div>

<script>

/* =========================
   MOBILE CLICK FIX
========================= */

document.addEventListener("touchend", function(event){

    let element = event.target.closest("a, button, [role='button']");

    if(element){

        setTimeout(function(){

            if(document.activeElement === element){

                element.blur();

            }

        }, 0);

    }

}, {passive:true});


document.addEventListener("click", function(event){

    let element = event.target.closest("a, button, [role='button']");

    if(element){

        setTimeout(function(){

            if(document.activeElement === element){

                element.blur();

            }

        }, 0);

    }

});

</script>
</body>


</html>