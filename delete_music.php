<?php

require "auth.php";


if(
    !$isLoggedIn
    ||
    $currentUser["role"]!="admin"
){

    header("Location: index.php");
    exit;

}



$musicFile = "music.json";
$userFile = "users.json";


$file = $_POST["file"] ?? "";


if($file==""){

    header("Location: settings.php");
    exit;

}



$file = basename($file);





// خواندن موزیک ها

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





$newMusics=[];



foreach($musics as $music){



    if(
        isset($music["file"])
        &&
        $music["file"]==$file
    ){



        // حذف فایل موزیک

        if(file_exists("music/".$file)){


            unlink("music/".$file);


        }





        // حذف کاور

        if(
            isset($music["cover"])
            &&
            $music["cover"]!=""
            &&
            file_exists("music-covers/".$music["cover"])
        ){


            unlink("music-covers/".$music["cover"]);


        }



        continue;


    }




    $newMusics[]=$music;


}






// ذخیره موزیک های باقی مانده


file_put_contents(

    $musicFile,

    json_encode(

        $newMusics,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);









// حذف لایک و سیو موزیک از کاربران


$users=[];


if(file_exists($userFile)){


    $users=json_decode(

        file_get_contents($userFile),

        true

    );


}



if(!is_array($users)){

    $users=[];

}







foreach($users as &$user){



    if(isset($user["liked_music"])){


        $user["liked_music"]=array_values(

            array_filter(

                $user["liked_music"],

                function($item) use ($file){

                    return $item!=$file;

                }

            )

        );


    }






    if(isset($user["saved_music"])){


        $user["saved_music"]=array_values(

            array_filter(

                $user["saved_music"],

                function($item) use ($file){

                    return $item!=$file;

                }

            )

        );


    }




}



unset($user);






file_put_contents(

    $userFile,

    json_encode(

        $users,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);







header("Location: settings.php");

exit;


?>