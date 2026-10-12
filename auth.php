<?php


session_start();



$userFile = "users.json";



$currentUser = null;


$users = [];







// خواندن کاربران


if(file_exists($userFile)){



    $users = json_decode(

        file_get_contents($userFile),

        true

    );



}







if(!is_array($users)){



    $users = [];



}









// سازگار کردن کاربران قدیمی


$changed = false;



foreach($users as $index=>$user){



    if(!isset($users[$index]["liked_videos"])){

        

        $users[$index]["liked_videos"] =

        $user["liked"] ?? [];



        $changed = true;



    }







    if(!isset($users[$index]["saved_videos"])){

        

        $users[$index]["saved_videos"] =

        $user["saved"] ?? [];



        $changed = true;



    }







    if(!isset($users[$index]["liked_music"])){

        

        $users[$index]["liked_music"] = [];



        $changed = true;



    }







    if(!isset($users[$index]["saved_music"])){

        

        $users[$index]["saved_music"] = [];



        $changed = true;



    }



}









// ذخیره تغییرات


if($changed){



    file_put_contents(

        $userFile,

        json_encode(

            $users,

            JSON_PRETTY_PRINT |

            JSON_UNESCAPED_UNICODE

        )

    );



}











// بررسی Session


if(isset($_SESSION["user_id"])){




    foreach($users as $user){





        if(

            $user["id"]

            ==

            $_SESSION["user_id"]

        ){





            $currentUser = $user;



            break;



        }



    }




}









// اگر Session نبود، بررسی Token


if(

    $currentUser === null

    &&

    isset($_COOKIE["remember_token"])

){



    $token = $_COOKIE["remember_token"];





    foreach($users as $user){





        if(

            isset($user["token"])

            &&

            $user["token"] === $token

        ){





            $currentUser = $user;






            $_SESSION["user_id"] = $user["id"];


            $_SESSION["username"] = $user["username"];


            $_SESSION["role"] = $user["role"];





            break;



        }




    }






}











// وضعیت ورود


$isLoggedIn = $currentUser !== null;





?>