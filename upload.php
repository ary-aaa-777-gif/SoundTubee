<?php
require "auth.php";

if(!$isLoggedIn){
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Upload Video</title>

<link rel="stylesheet" href="style.css">

</head>


<body>


<div class="container">


<header class="panel-header">


<h2>
📤 Upload Video
</h2>


<a class="back-btn" href="panel.php">
← Back
</a>


</header>



<div class="upload-box">



<h2>
Upload New Video
</h2>



<form action="upload-handler.php" method="post" enctype="multipart/form-data">



<label class="file-label">

📁 Choose Video


<input

type="file"

name="video"

accept="video/*"

required

>


</label>




<br><br>




<input

class="tag-input"

type="text"

name="category"

placeholder="Category name"

required

>




<br><br>




<input

class="tag-input"

type="text"

name="subcategory"

placeholder="Subcategory name"

required

>




<br><br>




<button type="submit">

⬆ Upload Video

</button>



</form>



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