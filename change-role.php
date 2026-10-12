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






// جلوگیری از تغییر نقش خودش

if($username == $currentUser["username"]){

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






foreach($users as &$user){



    if(

        ($user["username"] ?? "") == $username

    ){



        if(

            ($user["role"] ?? "user")

            ==

            "admin"

        ){


            $user["role"]="user";


        }

        else{


            $user["role"]="admin";


        }



        break;


    }



}



unset($user);







file_put_contents(

    $usersFile,

    json_encode(

        $users,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);







header("Location: users.php");

exit;


?>