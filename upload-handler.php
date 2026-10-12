<?php

require "auth.php";
require "config.php";


if(!$isLoggedIn){

    header("Location: login.php");
    exit;

}



if($_SERVER["REQUEST_METHOD"] !== "POST"){

    echo "Invalid request";
    exit;

}





if(

    !isset($_FILES["video"])

    ||

    $_FILES["video"]["error"] !== 0

){

    echo "No video selected!";
    exit;

}





$videoFolder = "videos/";
$thumbFolder = "thumbnails/";

$pendingFile = "pending.json";




if(!is_dir($videoFolder)){

    mkdir($videoFolder,0777,true);

}



if(!is_dir($thumbFolder)){

    mkdir($thumbFolder,0777,true);

}





$originalName = basename(

    $_FILES["video"]["name"]

);




$extension = pathinfo(

    $originalName,

    PATHINFO_EXTENSION

);



$newName = uniqid("video_").".".$extension;



$videoID = uniqid("vid_");



$videoPath = $videoFolder.$newName;





if(move_uploaded_file(

    $_FILES["video"]["tmp_name"],

    $videoPath

)){



    $thumbName = pathinfo(

        $newName,

        PATHINFO_FILENAME

    ).".jpg";



    $thumbPath = $thumbFolder.$thumbName;



    shell_exec(

        "ffmpeg -i \"$videoPath\" -ss 00:00:01 -vframes 1 \"$thumbPath\" -y"

    );





    $duration = shell_exec(

        "ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 \"$videoPath\""

    );



    $duration = round(floatval($duration));





    $category = trim($_POST["category"] ?? "");



    $subcategory = trim($_POST["subcategory"] ?? "");





    if($category==""){

        $category="Uncategorized";

    }





    if($subcategory==""){

        $subcategory="General";

    }




    // خواندن pending.json


    if(file_exists($pendingFile)){


        $pending=json_decode(

            file_get_contents($pendingFile),

            true

        );


    }

    else{


        $pending=[];


    }





    if(!is_array($pending)){


        $pending=[];


    }





    // اضافه کردن به لیست انتظار


    $pending[]=[



        "id"=>$videoID,



        "file"=>$newName,



        "name"=>$originalName,



        "thumbnail"=>$thumbName,



        "category"=>$category,



        "subcategory"=>$subcategory,



        "duration"=>$duration,



        "uploader"=>$currentUser["username"] ?? "Unknown",



        "date"=>date("d M Y")



    ];







    file_put_contents(


        $pendingFile,


        json_encode(


            $pending,


            JSON_PRETTY_PRINT |


            JSON_UNESCAPED_UNICODE


        )


    );









    echo "


    <!DOCTYPE html>


    <html lang='fa' dir='rtl'>


    <head>


    <meta charset='UTF-8'>


    <link rel='stylesheet' href='style.css'>


    </head>





    <body>





    <div class='container'>





    <div class='upload-box'>





    <h1>⏳ Upload Pending</h1>





    <p>

    Your video is waiting for admin approval

    </p>





    <a class='back-btn' href='index.php'>

    Back to Library

    </a>





    </div>





    </div>





    </body>


    </html>





    ";






}

else{



    echo "Upload failed";


}



?>