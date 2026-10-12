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



$categoryFile = "categories.json";
$dataFile = "data.json";
$musicFile = "music.json";




// خواندن دسته ها

if(file_exists($categoryFile)){


    $categories=json_decode(

        file_get_contents($categoryFile),

        true

    );


}
else{


    $categories=[];


}



if(!is_array($categories)){


    $categories=[];


}






// خواندن فیلم ها

if(file_exists($dataFile)){


    $videos=json_decode(

        file_get_contents($dataFile),

        true

    );


}
else{


    $videos=[];


}



if(!is_array($videos)){


    $videos=[];


}






// خواندن موزیک ها


if(file_exists($musicFile)){


    $musics=json_decode(

        file_get_contents($musicFile),

        true

    );


}
else{


    $musics=[];


}



if(!is_array($musics)){


    $musics=[];


}





function cleanName($name){


    return ucwords(

        str_replace("_"," ",$name)

    );


}


?><!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Website Settings</title>


<link rel="stylesheet" href="style.css">



<style>


.settings-box{

max-width:800px;

margin:30px auto;

background:#161616;

padding:25px;

border-radius:20px;

}



h1{

text-align:center;

color:#e50914;

}



.form-box{

background:#222;

padding:20px;

border-radius:15px;

margin-bottom:25px;

}



input,select{

padding:10px;

border-radius:10px;

border:none;

margin:5px;

}



button{

cursor:pointer;

}



.add-btn{

background:#16a34a;

color:white;

border:none;

padding:10px 20px;

border-radius:10px;

}



.category-row,
.video-row{

display:flex;

justify-content:space-between;

align-items:center;

background:#222;

padding:15px;

margin-top:10px;

border-radius:12px;

}



.sub-row{

background:#333;

margin:8px 0 8px 30px;

padding:12px;

border-radius:10px;

}



.trash{

background:#dc2626;

color:white;

border:none;

border-radius:8px;

width:35px;

height:35px;

}



.thumbnail-small{

width:80px;

height:50px;

object-fit:cover;

border-radius:8px;

}



</style>


</head>



<body class="<?php echo $_COOKIE["theme"] ?? "dark"; ?>">



<div class="settings-box">


<h1>

⚙️ Manage Website

</h1>





<div class="form-box">


<h2>

➕ New Category

</h2>



<form action="add_category.php" method="post">


<input

type="text"

name="category"

placeholder="Category name"

required

>



<button class="add-btn">

Create

</button>



</form>


</div>







<div class="form-box">


<h2>

➕ New Subcategory

</h2>



<form action="add_subcategory.php" method="post">


<select name="category">



<?php


foreach($categories as $cat=>$info){


echo "

<option value='".htmlspecialchars($cat)."'>


".cleanName($cat)."


</option>

";


}


?>


</select>





<input

type="text"

name="subcategory"

placeholder="Subcategory name"

required

>




<button class="add-btn">

Create

</button>




</form>


</div>







<h2>

📂 Categories

</h2>




<?php


foreach($categories as $cat=>$info){


?>



<div class="category-row">


<b>

📂 <?php echo cleanName($cat); ?>

</b>




<form action="delete_category.php" method="post">


<input

type="hidden"

name="category"

value="<?php echo htmlspecialchars($cat); ?>"

>


<button class="trash">

🗑

</button>



</form>



</div>



<?php



if(isset($info["children"])){

foreach($info["children"] as $sub=>$value){


?>



<div class="sub-row">


↳ <?php echo cleanName($sub); ?>



<form action="delete_subcategory.php" method="post">


<input

type="hidden"

name="category"

value="<?php echo htmlspecialchars($cat); ?>"

>



<input

type="hidden"

name="subcategory"

value="<?php echo htmlspecialchars($sub); ?>"

>




<button class="trash">

🗑

</button>



</form>



</div>



<?php


}


}


}


?><hr>

<h2>
🎬 Manage Videos
</h2>


<?php


if(count($videos)==0){

    echo "<p>No videos found</p>";

}



foreach($videos as $video){


    if(!isset($video["file"])){

        continue;

    }


    $file=$video["file"];

    $name=$video["name"] ?? "Untitled";

    $cat=$video["category"] ?? "";

    $sub=$video["subcategory"] ?? "";

    $thumb=$video["thumbnail"] ?? "";


?>


<div class="video-row">


<div>


<?php

if(
    $thumb!=""
    &&
    file_exists("thumbnails/".$thumb)
){

?>


<img

class="thumbnail-small"

src="thumbnails/<?php echo htmlspecialchars($thumb); ?>"

>


<?php

}

?>


<br>


<b>

🎬 <?php echo htmlspecialchars($name); ?>

</b>


<br>


📂 <?php echo htmlspecialchars($cat); ?>


<?php

if($sub!=""){

    echo " / ".htmlspecialchars($sub);

}

?>


</div>




<form action="delete_video.php" method="post">


<input

type="hidden"

name="file"

value="<?php echo htmlspecialchars($file); ?>"

>


<button class="trash">

🗑

</button>



</form>



</div>



<?php

}


?>






<hr>


<h2>

🎵 Manage Music

</h2>



<?php


if(count($musics)==0){

    echo "<p>No music found</p>";

}



foreach($musics as $music){


$file=$music["file"] ?? "";

$title=$music["title"] ?? "Untitled";

$artist=$music["artist"] ?? "";

$cover=$music["cover"] ?? "";


?>


<div class="video-row">


<div>


<?php

if(
    $cover!=""
    &&
    file_exists("music-covers/".$cover)
){

?>


<img

class="thumbnail-small"

src="music-covers/<?php echo htmlspecialchars($cover); ?>"

>


<?php

}

?>


<br>


<b>

🎵 <?php echo htmlspecialchars($title); ?>

</b>


<br>


🎤 <?php echo htmlspecialchars($artist); ?>


</div>






<form action="delete_music.php" method="post">


<input

type="hidden"

name="file"

value="<?php echo htmlspecialchars($file); ?>"

>


<button class="trash">

🗑

</button>



</form>



</div>



<?php


}


?>





<br>



<a href="index.php" class="back-btn">

← Back to Library

</a>



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