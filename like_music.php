<?php

require "auth.php";


if(!$isLoggedIn){

    echo "LOGIN_REQUIRED";
    exit;

}



$userFile="users.json";
$musicFile="music.json";


$file=$_GET["file"] ?? "";



if($file==""){

    echo "ERROR";
    exit;

}





// پیدا کردن کاربر

$userIndex=-1;


foreach($users as $index=>$user){


    if($user["id"]==$currentUser["id"]){


        $userIndex=$index;
        break;


    }


}



if($userIndex==-1){

    echo "ERROR";
    exit;

}





// ساخت لیست لایک موزیک

if(!isset($users[$userIndex]["liked_music"])){

    $users[$userIndex]["liked_music"]=[];

}





$liked=false;



foreach(
    $users[$userIndex]["liked_music"]
    as $key=>$item
){


    if($item==$file){


        $liked=true;


        unset(
            $users[$userIndex]["liked_music"][$key]
        );


        break;


    }


}





if(!$liked){


    $users[$userIndex]["liked_music"][]=$file;


}





$users[$userIndex]["liked_music"]=array_values(

    $users[$userIndex]["liked_music"]

);






// خواندن موزیک‌ها

$musics=[];


if(file_exists($musicFile)){


    $musics=json_decode(

        file_get_contents($musicFile),

        true

    );


}



if(!is_array($musics)){

    $musics=[];

}





// تغییر تعداد لایک

foreach($musics as &$music){


    if(
        isset($music["file"])
        &&
        $music["file"]==$file
    ){



        if(!isset($music["likes"])){

            $music["likes"]=0;

        }



        if($liked){

            $music["likes"]--;

        }
        else{

            $music["likes"]++;

        }



        if($music["likes"]<0){

            $music["likes"]=0;

        }


        break;

    }


}


unset($music);







file_put_contents(

    $userFile,

    json_encode(

        $users,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);







file_put_contents(

    $musicFile,

    json_encode(

        $musics,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);






// برگرداندن تعداد لایک

foreach($musics as $music){


    if($music["file"]==$file){


        echo $music["likes"];

        exit;


    }


}



echo "0";

?>