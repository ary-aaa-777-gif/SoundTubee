<?php

require "auth.php";
require "config.php";


if(!$isLoggedIn){

    header("Location: login.php");
    exit;

}



if($currentUser["role"] !== "admin"){

    echo "Access Denied";
    exit;

}





$id = $_GET["id"] ?? "";



if($id==""){

    header("Location: pending.php");
    exit;

}






$pending=[];


if(file_exists($pendingFile)){


    $pending=json_decode(

        file_get_contents($pendingFile),

        true

    );


}



if(!is_array($pending)){

    $pending=[];

}





$newPending=[];



foreach($pending as $video){



    if($video["id"]==$id){



        // حذف فایل ویدیو


        if(isset($video["file"])

        &&

        file_exists("videos/".$video["file"])

        ){


            unlink(

                "videos/".$video["file"]

            );


        }






        // حذف thumbnail


        if(isset($video["thumbnail"])

        &&

        file_exists("thumbnails/".$video["thumbnail"])

        ){


            unlink(

                "thumbnails/".$video["thumbnail"]

            );


        }



    }

    else{


        $newPending[]=$video;


    }



}







file_put_contents(

    $pendingFile,

    json_encode(

        $newPending,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);







header("Location: pending.php");

exit;


?>