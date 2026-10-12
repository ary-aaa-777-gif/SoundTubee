<?php

require "auth.php";


$dataFile = "data.json";
$categoryFile = "categories.json";



// خواندن ویدیوها

if(file_exists($dataFile)){

    $videos=json_decode(

        file_get_contents($dataFile),

        true

    );

}else{

    $videos=[];

}



if(!is_array($videos)){

    $videos=[];

}





// خواندن دسته ها

if(file_exists($categoryFile)){

    $categories=json_decode(

        file_get_contents($categoryFile),

        true

    );

}else{

    $categories=[];

}



if(!is_array($categories)){

    $categories=[];

}





$currentCategory=$_GET["category"] ?? null;

$currentSub=$_GET["sub"] ?? null;


$currentAll=isset($_GET["all"]);


$currentLiked=isset($_GET["liked"]);


$currentSaved=isset($_GET["saved"]);



$search=$_GET["search"] ?? "";





$theme=$_COOKIE["theme"] ?? "dark";






// لایک و سیو کاربر

$userLiked=[];

$userSaved=[];



if($currentUser){


    $userLiked=$currentUser["liked_videos"] ?? [];


    $userSaved=$currentUser["saved_videos"] ?? [];


}






function cleanName($name){


    return ucwords(

        str_replace("_"," ",$name)

    );


}





function videoExists($video){


    return isset($video["file"])

    &&

    file_exists(

        "videos/".$video["file"]

    );


}





function getSubcategories($category,$categories){


    if(

        isset($categories[$category]["children"])

        &&

        is_array($categories[$category]["children"])

    ){

        return $categories[$category]["children"];

    }


    return [];

}




function getVideoList(

$videos,

$category=null,

$sub=null,

$search="",

$liked=[],

$saved=[],

$likedMode=false,

$savedMode=false

){



$result=[];



foreach($videos as $video){



if(!isset($video["file"])){

continue;

}





$file=$video["file"];





if($likedMode && !in_array($file,$liked)){

continue;

}



if($savedMode && !in_array($file,$saved)){

continue;

}





if(

$category!==null

&&

($video["category"] ?? "")!=$category

){

continue;

}





if(

$sub!==null

&&

($video["subcategory"] ?? "")!=$sub

){

continue;

}





if($search!=""){



$text=strtolower(

($video["name"] ?? "")

." ".

($video["category"] ?? "")

." ".

($video["subcategory"] ?? "")

);



if(strpos($text,strtolower($search))===false){

continue;

}


}





$result[]=$video;



}



return $result;


}






foreach($videos as &$video){


if(!isset($video["likes"])){

$video["likes"]=0;

}


}


unset($video);



?>
<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Film</title>



<link rel="stylesheet" href="style.css">



<style>

/* =========================
   GOOGLE STYLE SEARCH
========================= */

.top-bar{

    display:flex;

    flex:1;

    gap:12px;

    align-items:center;

}


.search-wrapper{

    position:relative;

    flex:1;

    height:52px;

    display:flex;

    align-items:center;

}


.search-wrapper .search-icon{

    position:absolute;

    left:18px;

    width:21px;

    height:21px;

    color:#9aa0a6;

    pointer-events:none;

    z-index:2;

}


.search-box{

    width:100%;

    height:52px;

    border:1px solid #5f6368;

    outline:none;

    border-radius:28px;

    background:transparent;

    color:white;

    padding:0 20px 0 52px;

    font-size:16px;

    transition:.2s ease;

    -webkit-tap-highlight-color:transparent !important;

}


.search-box:hover{

    border-color:#7b8085;

}


.search-box:focus{

    border-color:#8ab4f8;

    box-shadow:0 0 0 1px rgba(138,180,248,.15);

}


.search-submit-btn{

    height:46px;

    min-width:110px;

    padding:0 24px;

    border:none;

    border-radius:24px;

    background:#303134;

    color:#e8eaed;

    font-size:15px;

    font-weight:400;

    cursor:pointer;

    display:flex;

    align-items:center;

    justify-content:center;

    transition:.2s ease;

    outline:none;

    -webkit-tap-highlight-color:transparent !important;

}


.search-submit-btn:hover{

    border:1px solid #5f6368;

    background:#3c4043;

}


.search-submit-btn:focus,
.search-submit-btn:focus-visible,
.search-submit-btn:active{

    outline:none !important;

    box-shadow:none !important;

    -webkit-tap-highlight-color:transparent !important;

}


.search-submit-btn svg{

    width:19px;

    height:19px;

    margin-left:8px;

}


@media(max-width:600px){

    .top-bar{

        gap:8px;

    }


    .search-wrapper{

        height:48px;

    }


    .search-box{

        height:48px;

        font-size:15px;

        padding:0 16px 0 46px;

    }


    .search-wrapper .search-icon{

        left:15px;

        width:19px;

        height:19px;

    }


    .search-submit-btn{

        height:44px;

        min-width:48px;

        width:48px;

        padding:0;

        border-radius:50%;

    }


    .search-submit-btn span{

        display:none;

    }


    .search-submit-btn svg{

        width:19px;

        height:19px;

        margin:0;

    }

}


/* =========================
   GLOBAL MOBILE CLICK FIX
========================= */

html,
body,
a,
button,
label,
[role="button"]{

    -webkit-tap-highlight-color:transparent !important;

    tap-highlight-color:transparent !important;

    -webkit-touch-callout:none;

}


a,
button,
label,
[role="button"]{

    -webkit-user-select:none;

    user-select:none;

    -webkit-focus-ring-color:transparent !important;

}


a:focus,
a:focus-visible,
a:active,
button:focus,
button:focus-visible,
button:active,
label:focus,
label:focus-visible,
label:active,
[role="button"]:focus,
[role="button"]:focus-visible,
[role="button"]:active{

    outline:none !important;

    box-shadow:none !important;

    -webkit-tap-highlight-color:transparent !important;

    tap-highlight-color:transparent !important;

}


button{

    -webkit-appearance:none !important;

    appearance:none !important;

}


a:active,
button:active,
label:active,
[role="button"]:active{

    filter:none !important;

    opacity:1 !important;

}


/* =========================
   VIDEO ACTION ICONS
========================= */

.video-buttons .icon-btn{

    padding:0;

    border:none;

    background:transparent;

    color:#fff;

    cursor:pointer;

    display:flex;

    align-items:center;

    justify-content:center;

    transition:.2s ease;

    -webkit-tap-highlight-color:transparent !important;

    box-shadow:none;

    outline:none;

}


.video-buttons .icon-btn:focus,
.video-buttons .icon-btn:focus-visible,
.video-buttons .icon-btn:active{

    outline:none !important;

    box-shadow:none !important;

    background:transparent !important;

}


.video-buttons .video-action-icon{

    width:25px;

    height:25px;

    display:block;

}


.video-buttons .like-action-icon{

    width:28px;

    height:28px;

}


.video-buttons .like-btn[data-liked="true"]{

    color:#ff1f3d;

}


.video-buttons .save-btn,
.video-buttons .comment-btn{

    background:transparent;

    border:none;

}

</style>



</head>




<body class="<?php echo $theme; ?>">



<div class="container">



<header>



<div class="header-wrapper">





<form class="top-bar" method="get" action="index.php">



<div class="search-wrapper">


<svg

class="search-icon"

viewBox="0 0 24 24"

fill="none"

aria-hidden="true"

>

    <circle

    cx="11"

    cy="11"

    r="6.5"

    stroke="currentColor"

    stroke-width="2"

    ></circle>

    <path

    d="M16 16L20.5 20.5"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    ></path>

</svg>



<input

class="search-box"

type="text"

name="search"

placeholder="Search videos..."

value="<?php echo htmlspecialchars($search); ?>"

autocomplete="off"

>

</div>



<button

class="search-submit-btn"

type="submit"

aria-label="Search"

>

<svg

viewBox="0 0 24 24"

fill="none"

aria-hidden="true"

>

    <circle

    cx="11"

    cy="11"

    r="6.5"

    stroke="currentColor"

    stroke-width="2"

    ></circle>

    <path

    d="M16 16L20.5 20.5"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    ></path>

</svg>


<span>

Search

</span>

</button>



</form>







<button

class="theme-btn"

onclick="changeTheme()"

>

🎨

</button>







</div>



</header>








<?php



// صفحه اصلی دسته ها



if(

$currentCategory===null

&&

!$currentAll

&&

!$currentLiked

&&

!$currentSaved

&&

$search==""

){



?>





<h2 class="category-title">


📂 Categories


</h2>







<div class="categories">







<a

class="category-card main-category-card"

href="index.php?all=1"

>



<div class="main-card-name">


ALL


</div>







<div class="subcategory-list">



<?php



foreach($categories as $cat=>$info){



echo "

<p>

".cleanName($cat)."

</p>

";



}



?>



</div>





</a>







<?php



foreach($categories as $cat=>$info){



?>





<a

class="category-card main-category-card"

href="index.php?category=<?php echo urlencode($cat); ?>"

>





<div class="main-card-name">


<?php echo cleanName($cat); ?>


</div>








<div class="subcategory-list">



<?php



if(

isset($info["children"])

&&

is_array($info["children"])

){



foreach($info["children"] as $sub=>$subInfo){



echo "

<p>

".cleanName($sub)."

</p>

";



}



}



?>



</div>







</a>







<?php



}



?>





</div>







<?php



}



?>
<?php



// نمایش زیر دسته ها



if(

$currentCategory!==null

&&

$search==""

&&

!$currentLiked

&&

!$currentSaved

){



?>





<h2 class="category-title">


📂

<?php echo cleanName($currentCategory); ?>


</h2>





<a

class="back-btn"

href="index.php"

>

← Back to Categories

</a>







<h3 class="subcategory-title">


Subcategories


</h3>







<div class="categories">





<?php



$subs=getSubcategories(

$currentCategory,

$categories

);



foreach($subs as $sub=>$info){



$cover="";



foreach($videos as $v){



if(

($v["category"] ?? "")==$currentCategory

&&

($v["subcategory"] ?? "")==$sub

&&

isset($v["thumbnail"])

&&

file_exists("thumbnails/".$v["thumbnail"])

){


$cover="thumbnails/".$v["thumbnail"];

break;


}



}



?>







<a

class="category-card"

href="index.php?category=<?php echo urlencode($currentCategory); ?>&sub=<?php echo urlencode($sub); ?>"

>





<?php if($cover!=""){ ?>



<img src="<?php echo $cover; ?>">



<?php }else{ ?>



<div class="no-thumbnail">

🎬

</div>



<?php } ?>






<div class="category-name">


<?php echo cleanName($sub); ?>


</div>





</a>






<?php



}



?>






</div>





<?php



}



?>









<?php



// آماده سازی لیست فیلم ها



if(

$currentAll

||

$currentLiked

||

$currentSaved

||

$search!=""

||

(

$currentCategory!==null

&&

$currentSub!==null

)

){





$videoList=getVideoList(


$videos,


$currentCategory,


$currentSub,


$search,


$userLiked,


$userSaved,


$currentLiked,


$currentSaved



);



?>








<h2 class="library-title">



<?php



if($currentLiked){


echo "❤️ Liked Videos";


}

elseif($currentSaved){


echo "🔖 Saved Videos";


}

elseif($search!=""){


echo "🔍 Search Results";


}

else{


echo "🎥 Videos";


}



?>



</h2>









<div class="video-grid">





<?php



if(count($videoList)==0){



echo "

<p style='color:#aaa'>


No videos found


</p>

";



}





foreach($videoList as $video){



if(!videoExists($video)){


continue;


}





$file=$video["file"];



$name=$video["name"] ?? "Untitled";



$thumbnail=$video["thumbnail"] ?? "";



$likes=$video["likes"] ?? 0;



$isLiked=in_array(

$file,

$userLiked

);



$isSaved=in_array(

$file,

$userSaved

);





?>








<div class="video-card">





<div class="thumbnail-box">



<?php if(

$thumbnail!=""

&&

file_exists("thumbnails/".$thumbnail)

){ ?>


<img src="thumbnails/<?php echo $thumbnail; ?>">



<?php }else{ ?>


<div class="no-thumbnail">

🎬

</div>


<?php } ?>



</div>







<video

class="hidden-video"

controls

loop

>

<source src="videos/<?php echo $file; ?>">


</video>









<div class="video-info">



<h3>

<?php echo htmlspecialchars($name); ?>

</h3>





<p class="like-count">

❤️ <?php echo $likes; ?>

</p>






<div class="video-buttons">





<button

class="play-btn"

onclick="playVideo(this)"

>

▶ Play

</button>






<button

class="like-btn icon-btn"

data-liked="<?php echo $isLiked?'true':'false'; ?>"

onclick="likeVideo('<?php echo htmlspecialchars($file, ENT_QUOTES); ?>',this)"

aria-label="Like"

>

<?php if($isLiked){ ?>

<svg

class="video-action-icon like-action-icon"

viewBox="0 0 24 24"

fill="currentColor"

>

    <path d="M12 21.2s-7.1-4.7-9.5-9C.7 9.1 2.2 5.4 6 4.7c2.2-.4 4.1.7 5.2 2.4C12.3 5.4 14.2 4.3 16.4 4.7c3.8.7 5.3 4.4 3.5 7.5-2.4 4.3-9.5 9-9.5 9Z"></path>

</svg>

<?php }else{ ?>

<svg

class="video-action-icon like-action-icon"

viewBox="0 0 24 24"

fill="none"

>

    <path

    d="M20.8 8.9c0 5-8.8 10.7-8.8 10.7S3.2 13.9 3.2 8.9C3.2 6.1 5.2 4 8 4c1.7 0 3.1.8 4 2.2C12.9 4.8 14.3 4 16 4c2.8 0 4.8 2.1 4.8 4.9Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linejoin="round"

    ></path>

</svg>

<?php } ?>

</button>







<button

class="save-btn icon-btn"

data-saved="<?php echo $isSaved?'true':'false'; ?>"

onclick="saveVideo('<?php echo htmlspecialchars($file, ENT_QUOTES); ?>',this)"

aria-label="Save"

>

<?php if($isSaved){ ?>

<svg

class="video-action-icon"

viewBox="0 0 24 24"

fill="currentColor"

>

    <path d="M6.5 2.5h11c.8 0 1.5.7 1.5 1.5v17l-7-4.8-7 4.8V4c0-.8.7-1.5 1.5-1.5Z"></path>

</svg>

<?php }else{ ?>

<svg

class="video-action-icon"

viewBox="0 0 24 24"

fill="none"

>

    <path

    d="M6.5 3h11c.83 0 1.5.67 1.5 1.5V21l-7-4.8L5.5 21V4.5C5.5 3.67 5.67 3 6.5 3Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    stroke-linejoin="round"

    ></path>

</svg>

<?php } ?>

</button>







<a

class="comment-btn icon-btn"

href="comments.php?id=<?php echo urlencode($video["id"] ?? $file); ?>"

aria-label="Comments"

>

<svg

class="video-action-icon"

viewBox="0 0 24 24"

fill="none"

>

    <path

    d="M20 11.5C20 16.2 16.2 20 11.5 20C9.8 20 8.3 19.5 7 18.7L3.5 20L4.8 16.5C4 15.2 3.5 13.7 3.5 12C3.5 7.3 7.3 3.5 12 3.5C16.2 3.5 20 7.3 20 11.5Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    stroke-linejoin="round"

    ></path>

</svg>

</a>







</div>





</div>





</div>







<?php



}



?>






</div>






<?php



}



?>
<script>


function playVideo(button){


    // قطع موزیک هنگام پخش فیلم

    let audio=document.getElementById("main-audio");


    if(audio && !audio.paused){

        audio.pause();

        let playBtn=document.getElementById("play-btn");

        if(playBtn){

            playBtn.innerHTML="▶";

        }

    }






    let card=button.closest(".video-card");



    let thumbnail=card.querySelector(".thumbnail-box");


    let video=card.querySelector(".hidden-video");





    thumbnail.style.display="none";


    video.style.display="block";


    video.play();



}









function likeVideo(file,button){



fetch(

"like.php?file="+encodeURIComponent(file)

)



.then(response=>response.text())



.then(data=>{



let count=button.closest(".video-info")

.querySelector(".like-count");



if(count){


count.innerHTML="❤️ "+data;


}






if(button.dataset.liked=="true"){


button.dataset.liked="false";


button.innerHTML=`

<svg

class="video-action-icon like-action-icon"

viewBox="0 0 24 24"

fill="none"

>

    <path

    d="M20.8 8.9c0 5-8.8 10.7-8.8 10.7S3.2 13.9 3.2 8.9C3.2 6.1 5.2 4 8 4c1.7 0 3.1.8 4 2.2C12.9 4.8 14.3 4 16 4c2.8 0 4.8 2.1 4.8 4.9Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linejoin="round"

    ></path>

</svg>

`;

}

else{


button.dataset.liked="true";


button.innerHTML=`

<svg

class="video-action-icon like-action-icon"

viewBox="0 0 24 24"

fill="currentColor"

>

    <path d="M12 21.2s-7.1-4.7-9.5-9C.7 9.1 2.2 5.4 6 4.7c2.2-.4 4.1.7 5.2 2.4C12.3 5.4 14.3 4.3 16.4 4.7c3.8.7 5.3 4.4 3.5 7.5-2.4 4.3-9.5 9-9.5 9Z"></path>

</svg>

`;


}



});



}









function saveVideo(file,button){



fetch(

"save.php?file="+encodeURIComponent(file)

)



.then(response=>response.text())



.then(()=>{



if(button.dataset.saved=="true"){


button.dataset.saved="false";


button.innerHTML=`

<svg

class="video-action-icon"

viewBox="0 0 24 24"

fill="none"

>

    <path

    d="M6.5 3h11c.83 0 1.5.67 1.5 1.5V21l-7-4.8-7 4.8V4.5C5.5 3.67 5.67 3 6.5 3Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    stroke-linejoin="round"

    ></path>

</svg>

`;

}

else{


button.dataset.saved="true";


button.innerHTML=`

<svg

class="video-action-icon"

viewBox="0 0 24 24"

fill="currentColor"

>

    <path d="M6.5 2.5h11c.8 0 1.5.7 1.5 1.5v17l-7-4.8-7 4.8V4c0-.8.7-1.5 1.5-1.5Z"></path>

</svg>

`;

}



});



}











// بستن فیلم وقتی بیرون کلیک شد


document.addEventListener(

"click",

function(event){



document.querySelectorAll(".video-card").forEach(function(card){



if(!card.contains(event.target)){



let video=card.querySelector(".hidden-video");

let thumbnail=card.querySelector(".thumbnail-box");



if(video && video.style.display=="block"){



video.pause();


video.currentTime=0;


video.style.display="none";



if(thumbnail){


thumbnail.style.display="flex";


}



}



}



});



}



);











// تغییر تم



function changeTheme(){



let themes=[

"dark",

"red",

"blue",

"purple",

"light"

];



let current=document.body.className;



let index=themes.indexOf(current);



index++;



if(index>=themes.length){

index=0;

}




document.body.className=themes[index];





document.cookie=

"theme="+themes[index]+";path=/;max-age=31536000";



}


/* =========================
   MOBILE CLICK FIX
========================= */

document.addEventListener("pointerdown", function(event){

    let element=event.target.closest(
        "a, button, [role='button']"
    );

    if(element){

        element.style.webkitTapHighlightColor="transparent";

    }

});


document.addEventListener("touchend", function(event){

    let element=event.target.closest(
        "a, button, [role='button']"
    );

    if(element){

        requestAnimationFrame(function(){

            if(document.activeElement===element){

                element.blur();

            }

        });

    }

},{passive:true});


document.addEventListener("click", function(event){

    let element=event.target.closest(
        "a, button, [role='button']"
    );

    if(element){

        requestAnimationFrame(function(){

            if(document.activeElement===element){

                element.blur();

            }

        });

    }

});

</script>






<?php include "bottom-nav.php"; ?>



</div>


</body>


</html>
```
