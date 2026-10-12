<?php


require "auth.php";



if(!$isLoggedIn){


    header("Location: login.php");

    exit;


}




$commentsFile="comments.json";

$dataFile="data.json";




$comments=[];

$videos=[];





// خواندن کامنت ها


if(file_exists($commentsFile)){


    $comments=json_decode(

        file_get_contents($commentsFile),

        true

    );


}



if(!is_array($comments)){


    $comments=[];


}







// خواندن فیلم ها برای پیدا کردن اسم فیلم


if(file_exists($dataFile)){


    $videos=json_decode(

        file_get_contents($dataFile),

        true

    );


}



if(!is_array($videos)){


    $videos=[];


}






?>





<!DOCTYPE html>

<html lang="fa" dir="rtl">



<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">



<title>My Comments</title>



<link rel="stylesheet" href="style.css">



<style>


.comments-box{


max-width:700px;

margin:40px auto;

}



.comment-card{


background:#161616;

color:white;

padding:20px;

border-radius:15px;

margin-bottom:15px;

}



.comment-video{


color:#e50914;

font-weight:bold;

font-size:18px;

}



.comment-text{


margin:15px 0;

font-size:16px;

}



.comment-date{


color:#aaa;

font-size:13px;

}



.back-btn{


display:block;

background:#e50914;

color:white;

text-align:center;

padding:12px;

border-radius:12px;

text-decoration:none;

margin-bottom:20px;

}



</style>



</head>





<body class="<?php echo $_COOKIE["theme"] ?? "dark"; ?>">



<div class="container">


<div class="comments-box">



<a href="panel.php" class="back-btn">


← Back to Panel


</a>




<h2>


💬 My Comments


</h2>
<?php


$found=false;



foreach($comments as $comment){



    if(

        isset($comment["user_id"])

        &&

        $comment["user_id"] == $currentUser["id"]

    ){



        $found=true;



        $videoName="Unknown Video";





        foreach($videos as $video){



            if(

                isset($video["id"])

                &&

                isset($comment["video_id"])

                &&

                $video["id"] == $comment["video_id"]

            ){



                $videoName=$video["name"] ?? "Untitled";

                break;


            }



        }





?>





<div class="comment-card">





<div class="comment-video">


🎬

<?php echo htmlspecialchars($videoName); ?>


</div>







<div class="comment-text">


<?php echo htmlspecialchars($comment["text"]); ?>


</div>







<div class="comment-date">


📅

<?php echo htmlspecialchars($comment["date"] ?? ""); ?>


</div>







</div>







<?php


    }


}






if(!$found){



echo "

<p style='color:#aaa;text-align:center'>


You have no comments yet


</p>

";


}





?>







</div>

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