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



?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Pending Uploads</title>


<link rel="stylesheet" href="style.css">


</head>



<body class="<?php echo $_COOKIE["theme"] ?? "dark"; ?>">



<div class="container">





<header class="panel-header">


<h2>

📥 Pending Uploads

</h2>



<a class="back-btn" href="panel.php">

← Back

</a>



</header>









<?php


if(count($pending)==0){


echo "

<div class='upload-box'>

<h2>

✅ No Pending Videos

</h2>

</div>

";


}



?>









<div class="video-grid">



<?php foreach($pending as $video){ ?>





<div class="video-card">





<div class="thumbnail-box">



<?php if(

isset($video["thumbnail"])

&&

file_exists("thumbnails/".$video["thumbnail"])

){ ?>


<img src="thumbnails/<?php echo $video["thumbnail"]; ?>">



<?php }else{ ?>


<div class="no-thumbnail">

🎬

</div>


<?php } ?>



</div>









<div class="video-info">



<h3>

<?php echo htmlspecialchars($video["name"]); ?>

</h3>



<p>

👤

<?php echo htmlspecialchars($video["uploader"]); ?>

</p>



<p>

📂

<?php echo htmlspecialchars($video["category"]); ?>

/

<?php echo htmlspecialchars($video["subcategory"]); ?>

</p>



<p>

📅

<?php echo $video["date"]; ?>

</p>







<div class="video-buttons">





<a href="approve-video.php?id=<?php echo $video["id"]; ?>">


<button class="play-btn">

✅ Approve

</button>


</a>








<a href="reject-video.php?id=<?php echo $video["id"]; ?>">


<button class="delete-btn">

❌

</button>


</a>





</div>







</div>





</div>





<?php } ?>





</div>







<?php include "bottom-nav.php"; ?>



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