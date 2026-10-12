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


$dataFile="data.json";
$userFile="users.json";
$commentsFile="comments.json";


$file=$_POST["file"] ?? "";


if($file==""){
    header("Location: settings.php");
    exit;
}


$file=basename($file);





// حذف فیلم از data.json

$videos=[];


if(file_exists($dataFile)){

    $videos=json_decode(
        file_get_contents($dataFile),
        true
    );

}


if(!is_array($videos)){

    $videos=[];

}



$newVideos=[];



foreach($videos as $video){


    if(
        isset($video["file"])
        &&
        $video["file"]==$file
    ){



        // حذف فایل ویدیو

        if(file_exists("videos/".$file)){

            unlink("videos/".$file);

        }





        // حذف thumbnail

        if(
            isset($video["thumbnail"])
            &&
            $video["thumbnail"]!=""
            &&
            file_exists(
                "thumbnails/".$video["thumbnail"]
            )
        ){

            unlink(
                "thumbnails/".$video["thumbnail"]
            );

        }



        continue;


    }



    $newVideos[]=$video;


}




file_put_contents(

    $dataFile,

    json_encode(

        $newVideos,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);









// پاک کردن لایک و سیو کاربران


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



    $lists=[

        "liked",

        "saved",

        "liked_videos",

        "saved_videos"

    ];




    foreach($lists as $list){



        if(isset($user[$list])){



            $user[$list]=array_values(

                array_filter(

                    $user[$list],

                    function($item) use ($file){

                        return $item!=$file;

                    }

                )

            );



        }



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









// حذف کامنت‌های فیلم


if(file_exists($commentsFile)){



    $comments=json_decode(

        file_get_contents($commentsFile),

        true

    );



    if(!is_array($comments)){

        $comments=[];

    }




    $newComments=[];



    foreach($comments as $comment){



        if(

            isset($comment["video_id"])

            &&

            $comment["video_id"]==$file

        ){


            continue;


        }



        $newComments[]=$comment;



    }




    file_put_contents(

        $commentsFile,

        json_encode(

            $newComments,

            JSON_PRETTY_PRINT |

            JSON_UNESCAPED_UNICODE

        )

    );



}





header("Location: settings.php");

exit;

?>