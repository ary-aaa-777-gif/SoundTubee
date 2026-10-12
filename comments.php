<?php

require "auth.php";

$commentsFile = "comments.json";

$videoID = $_GET["id"] ?? "";

if($videoID==""){
    echo "ERROR";
    exit;
}


// خواندن کامنت‌ها

if(file_exists($commentsFile)){

    $comments=json_decode(
        file_get_contents($commentsFile),
        true
    );

}else{

    $comments=[];

}


if(!is_array($comments)){
    $comments=[];
}


// ثبت کامنت

if(
    $_SERVER["REQUEST_METHOD"]=="POST"
    &&
    $currentUser
){

    $text=trim($_POST["comment"] ?? "");


    if($text!=""){

        $comments[]=[

            "video_id"=>$videoID,

            "user_id"=>$currentUser["id"],

            "username"=>$currentUser["username"],

            "text"=>$text,

            "date"=>date("d M Y")

        ];


        file_put_contents(

            $commentsFile,

            json_encode(

                $comments,

                JSON_PRETTY_PRINT |

                JSON_UNESCAPED_UNICODE

            )

        );


    }


}


?>

<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">

<title>Comments</title>

<link rel="stylesheet" href="style.css">


<style>

body{

background:#000;

color:white;

font-family:sans-serif;

}


.comments-box{

max-width:600px;

margin:auto;

padding:20px;

}


.comment{

background:#1c1c1c;

padding:15px;

border-radius:12px;

margin-bottom:12px;

}


.username{

color:#00ff88;

font-weight:bold;

}


textarea{

width:100%;

height:90px;

border-radius:10px;

padding:10px;

resize:none;

}


button{

background:#e50914;

color:white;

border:none;

padding:10px 20px;

border-radius:10px;

cursor:pointer;

margin-top:10px;

}


</style>


</head>


<body>


<div class="comments-box">


<h2>

💬 Comments

</h2>



<?php


$found=false;


foreach($comments as $comment){


    if(
        isset($comment["video_id"])
        &&
        $comment["video_id"]==$videoID
    ){

        $found=true;

?>


<div class="comment">


<div class="username">

<?php

echo htmlspecialchars(
$comment["username"]
);

?>

</div>


<p>

<?php

echo htmlspecialchars(
$comment["text"]
);

?>

</p>


<small>

<?php

echo $comment["date"];

?>

</small>


</div>


<?php

    }


}



if(!$found){

echo "<p style='color:#aaa'>No comments yet</p>";

}



?>





<?php if($currentUser){ ?>


<form method="post">


<textarea

name="comment"

placeholder="Write a comment..."

required

></textarea>


<br>


<button>

Send

</button>


</form>


<?php } else { ?>


<p>

Login to comment

</p>


<?php } ?>



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