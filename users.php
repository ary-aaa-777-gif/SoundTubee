<?php

require "auth.php";


if(!$isLoggedIn){

    header("Location: login.php");
    exit;

}



if($currentUser["role"] !== "admin"){

    echo "Access Denied";
    exit;

}





$usersFile="users.json";


$users=[];



if(file_exists($usersFile)){


    $users=json_decode(

        file_get_contents($usersFile),

        true

    );


}



if(!is_array($users)){

    $users=[];

}

?>

<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Users</title>


<link rel="stylesheet" href="style.css">


</head>



<body class="<?php echo $_COOKIE["theme"] ?? "dark"; ?>">



<div class="container">



<header class="panel-header">


<h2>

👥 Users

</h2>


<a class="back-btn" href="panel.php">

← Back

</a>


</header>





<div class="users-list">



<?php



if(count($users)==0){


echo "

<div class='upload-box'>

<h2>

No Users Found

</h2>

</div>

";


}





foreach($users as $user){



$username=$user["username"] ?? "Unknown";


$role=$user["role"] ?? "user";



$liked=count($user["liked_videos"] ?? []);


$saved=count($user["saved_videos"] ?? []);


$likedMusic=count($user["liked_music"] ?? []);


$savedMusic=count($user["saved_music"] ?? []);

?>



<div class="user-card">



<div class="profile-icon">

👤

</div>



<h3>

<?php echo htmlspecialchars($username); ?>

</h3>




<p>

<?php

echo $role=="admin"

?

"👑 Admin"

:

"👤 User";


?>

</p>





<div class="user-stats">


<span>

❤️ Videos:

<?php echo $liked; ?>

</span>



<span>

🔖 Saved:

<?php echo $saved; ?>

</span>



<span>

🎵 Liked Music:

<?php echo $likedMusic; ?>

</span>



<span>

🎧 Saved Music:

<?php echo $savedMusic; ?>

</span>



</div><?php if($username != $currentUser["username"]){ ?>



<div class="user-actions">



<a

class="role-user-btn"

href="change-role.php?username=<?php echo urlencode($username); ?>"

onclick="return confirm('Change user role?');"

>

👑 Change Role

</a>





<a

class="delete-user-btn"

href="delete-user.php?username=<?php echo urlencode($username); ?>"

onclick="return confirm('Delete this user?');"

>

🗑 Delete User

</a>



</div>



<?php } ?>



</div>





<?php


}



?>





</div>







<?php include "bottom-nav.php"; ?>





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