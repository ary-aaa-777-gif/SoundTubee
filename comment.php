<?php


include "config.php";



$videoID = $_POST["id"] ?? "";

$text = trim(

    $_POST["comment"] ?? ""

);






if($videoID=="" || $text==""){


    echo "error";

    exit;


}






$commentFile = "comments.json";






if(file_exists($commentFile)){



    $comments = json_decode(

        file_get_contents($commentFile),

        true

    );



}else{


    $comments=[];


}







if(!is_array($comments)){


    $comments=[];


}













if(!isset($comments[$videoID])){


    $comments[$videoID]=[];


}









$comments[$videoID][] = [


    "user"=>"Guest",


    "text"=>$text,


    "date"=>date("d M Y H:i")


];









file_put_contents(

    $commentFile,

    json_encode(

        $comments,

        JSON_PRETTY_PRINT |

        JSON_UNESCAPED_UNICODE

    )

);






echo "success";



?>