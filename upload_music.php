<?php

require "auth.php";

require_once "getid3/getid3.php";


if(!$isLoggedIn){

    header("Location: login.php");
    exit;

}



$message="";



if($_SERVER["REQUEST_METHOD"]=="POST"){


$title = trim($_POST["title"] ?? "");

$artist = trim($_POST["artist"] ?? "");




if(
    isset($_FILES["music"])
    &&
    $_FILES["music"]["error"] == 0
){



$musicName = time()."_".basename($_FILES["music"]["name"]);



$musicPath = "music/".$musicName;



move_uploaded_file(

    $_FILES["music"]["tmp_name"],

    $musicPath

);





// -------------------------
// استخراج کاور واقعی MP3
// -------------------------


$coverName = "";

$getID3 = new getID3();


$fileInfo = $getID3->analyze($musicPath);


if(
    isset($fileInfo["comments"]["picture"][0]["data"])
){


    $pictureData =

    $fileInfo["comments"]["picture"][0]["data"];



    $mime =

    $fileInfo["comments"]["picture"][0]["image_mime"];



    if($mime=="image/jpeg"){

        $extension="jpg";

    }

    else{

        $extension="png";

    }



    $coverName = time().".".$extension;



    file_put_contents(

        "music-covers/".$coverName,

        $pictureData

    );


}






// اگر کاور داخل آهنگ نبود
// کاور ساده بساز


if($coverName==""){



$coverName = time().".png";


$image = imagecreatetruecolor(

600,

600

);



$background=imagecolorallocate(

$image,

25,

25,

25

);



$white=imagecolorallocate(

$image,

255,

255,

255

);



$red=imagecolorallocate(

$image,

229,

9,

20

);



imagefill(

$image,

0,

0,

$background

);



imagestring(

$image,

5,

270,

170,

"♪",

$red

);



imagestring(

$image,

5,

80,

300,

substr($title,0,25),

$white

);



imagestring(

$image,

4,

80,

350,

substr($artist,0,25),

$white

);



imagepng(

$image,

"music-covers/".$coverName

);



imagedestroy($image);



}






// -------------------------
// ذخیره اطلاعات موزیک
// -------------------------



$musicFile="music.json";


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






$musics[]=[


"id"=>uniqid(),


"title"=>$title,


"artist"=>$artist,


"file"=>$musicName,


"cover"=>$coverName,


"uploader"=>$currentUser["username"],


"likes"=>0,


"saves"=>0


];





file_put_contents(

$musicFile,

json_encode(

$musics,

JSON_PRETTY_PRINT |

JSON_UNESCAPED_UNICODE

)

);





$message="✅ Music uploaded";



}



}



?><!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Upload Music</title>


<link rel="stylesheet" href="style.css">



<style>


.music-upload-box{


max-width:500px;

margin:40px auto;

background:#161616;

padding:25px;

border-radius:20px;

color:white;


}




.music-upload-box h1{


text-align:center;

color:#e50914;


}




.music-upload-box input{


width:100%;

padding:12px;

margin:10px 0;

border:none;

border-radius:10px;

background:#222;

color:white;


}





.music-upload-box input[type="file"]{


cursor:pointer;


}




.music-upload-box input[type="file"]::file-selector-button{


background:#16a34a;

color:white;

border:none;

padding:10px 15px;

border-radius:10px;

cursor:pointer;

margin-left:10px;


}





.upload-btn{


width:100%;

padding:13px;

background:#16a34a;

color:white;

border:none;

border-radius:12px;

font-size:18px;

cursor:pointer;


}





.message{


text-align:center;

color:#22c55e;

margin:15px;


}




.back-btn{


display:block;

text-align:center;

margin-top:20px;

background:#333;

color:white;

padding:12px;

border-radius:12px;

text-decoration:none;


}





.back-btn:hover{


background:#444;


}



</style>


</head>




<body class="<?php echo $_COOKIE["theme"] ?? "dark"; ?>">



<div class="music-upload-box">



<h1>

🎵 Upload Music

</h1>




<?php

if($message!=""){

echo "<div class='message'>$message</div>";

}

?>





<form method="post" enctype="multipart/form-data">





<input

type="text"

name="title"

placeholder="Song name"

required>





<input

type="text"

name="artist"

placeholder="Artist name"

required>






<input

type="file"

name="music"

accept="audio/*"

required>






<button class="upload-btn">

⬆ Upload

</button>





</form>





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