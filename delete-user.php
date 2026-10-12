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






$username = $_GET["username"] ?? "";



if($username==""){

    header("Location: users.php");
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







$newUsers=[];



foreach($users as $user){



    // جلوگیری از حذف خود ادمین

    if(

        ($user["username"] ?? "") == $username

        &&

        $username != $currentUser["username"]

    ){


        continue;


    }



    $newUsers[]=$user;


}






file_put_contents(

    $usersFile,

    json_encode(

        $newUsers,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);






header("Location: users.php");

exit;


?>