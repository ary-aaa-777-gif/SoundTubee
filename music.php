<?php

require "auth.php";


$theme = $_COOKIE["theme"] ?? "dark";


$musicFile = "music.json";


$musics = [];


if(file_exists($musicFile)){

    $musics = json_decode(

        file_get_contents($musicFile),

        true

    );

}


if(!is_array($musics)){

    $musics = [];

}





// وضعیت لایک و سیو کاربر

$userLikedMusic = [];

$userSavedMusic = [];


if($currentUser){

    $userLikedMusic = $currentUser["liked_music"] ?? [];

    $userSavedMusic = $currentUser["saved_music"] ?? [];

}





// فیلترها

$showLikedMusic = isset($_GET["liked"]);

$showSavedMusic = isset($_GET["saved"]);



// سرچ موزیک

$musicSearch = $_GET["music_search"] ?? "";



?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Music</title>



<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="music.css">



<style>


/* =========================
   GOOGLE STYLE MUSIC SEARCH
========================= */

.music-search{

    display:flex;

    gap:12px;

    align-items:center;

    margin:20px 0;

}


.music-search-wrapper{

    position:relative;

    flex:1;

    height:52px;

    display:flex;

    align-items:center;

}


.music-search-wrapper .music-search-icon{

    position:absolute;

    left:18px;

    width:21px;

    height:21px;

    color:#9aa0a6;

    pointer-events:none;

    z-index:2;

}


.music-search input{

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

}


.music-search input:hover{

    border-color:#7b8085;

}


.music-search input:focus{

    border-color:#8ab4f8;

    box-shadow:0 0 0 1px rgba(138,180,248,.15);

}


.music-search button{

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

}


.music-search button:hover{

    border:1px solid #5f6368;

    background:#3c4043;

}


.music-search button:focus,
.music-search button:focus-visible,
.music-search button:active{

    outline:none !important;

    box-shadow:none !important;

    -webkit-tap-highlight-color:transparent !important;

}


.music-search button svg{

    width:19px;

    height:19px;

    margin-left:8px;

}


@media(max-width:600px){

    .music-search{

        gap:8px;

    }


    .music-search-wrapper{

        height:48px;

    }


    .music-search input{

        height:48px;

        font-size:15px;

        padding:0 16px 0 46px;

    }


    .music-search-wrapper .music-search-icon{

        left:15px;

        width:19px;

        height:19px;

    }


    .music-search button{

        height:44px;

        min-width:48px;

        width:48px;

        padding:0;

        border-radius:50%;

    }


    .music-search button span{

        display:none;

    }


    .music-search button svg{

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


/* ==========================
   MUSIC CONTAINER
========================== */

.music-container{

    padding:20px;

}





.music-title{

    text-align:center;

    color:#e50914;

    margin:30px;

}





.song-row{

    height:90px;

    background:#161616;

    border-radius:15px;

    margin-bottom:15px;

    display:flex;

    align-items:center;

    padding:10px;

    gap:15px;

    color:white;

    overflow:hidden;

    box-sizing:border-box;

}





.song-cover{

    width:70px;

    height:70px;

    border-radius:10px;

    object-fit:cover;

    flex-shrink:0;

}





.song-info{

    flex:1;

    min-width:0;

    overflow:hidden;

}





.song-name{

    font-size:18px;

    font-weight:bold;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

}





.song-artist{

    color:#aaa;

    font-size:14px;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

}





.song-likes{

    color:#ff4444;

    margin-top:5px;

}




.song-buttons{

    display:flex;

    gap:10px;

    flex-shrink:0;

}



.song-buttons button{

    border:none;

    background:#222;

    color:white;

    font-size:20px;

    width:40px;

    height:40px;

    padding:0;

    border-radius:10px;

    cursor:pointer;

    display:flex;

    align-items:center;

    justify-content:center;

}



.play-song{

    background:#16a34a!important;

}





/* ==========================
   INSTAGRAM STYLE BOOKMARK
========================== */


.save-btn{

    background:transparent !important;

    width:40px !important;

    height:40px !important;

}



.save-btn .save-icon{

    width:24px;

    height:24px;

    display:block;

}



.save-btn:hover{

    opacity:.75;

}





/* ==========================
   MOBILE
========================== */


@media(max-width:600px){


    .song-row{

        height:75px;

        gap:8px;

        padding:8px;

    }



    .song-cover{

        width:55px;

        height:55px;

    }



    .song-name{

        font-size:14px;

    }



    .song-artist{

        font-size:12px;

    }



    .song-buttons{

        gap:6px;

    }



    .song-buttons button{

        width:32px;

        height:32px;

        font-size:15px;

    }



    .save-btn .save-icon{

        width:21px;

        height:21px;

    }


}





/* ==========================
   SMALL PHONES
========================== */


@media(max-width:380px){


    .song-buttons button{

        width:30px;

        height:30px;

    }



    .save-btn .save-icon{

        width:20px;

        height:20px;

    }


}


</style>



</head>



<body class="<?php echo $theme; ?>">



<div class="container">



<div class="music-container">



<h1 class="music-title">

🎵 Music Library

</h1>





<form class="music-search" method="get">


<div class="music-search-wrapper">


<svg

class="music-search-icon"

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

type="text"

name="music_search"

placeholder="Search music..."

value="<?php echo htmlspecialchars($musicSearch); ?>"

autocomplete="off"

>



</div>



<button

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







<?php



if(count($musics)==0){



echo "


<div class='music-empty'>


🎧


<h2>No songs available</h2>


</div>


";



}








foreach($musics as $index=>$song){





$isLiked = in_array(

    $song["file"],

    $userLikedMusic

);





$isSaved = in_array(

    $song["file"],

    $userSavedMusic

);





// فیلتر سرچ موزیک

if($musicSearch!=""){



    $searchText = strtolower(

        ($song["title"] ?? "")

        ." ".

        ($song["artist"] ?? "")

    );



    if(

        strpos(

            $searchText,

            strtolower($musicSearch)

        ) === false

    ){

        continue;

    }



}





// فقط موزیک های لایک شده

if(

    $showLikedMusic

    &&

    !$isLiked

){

    continue;

}





// فقط موزیک های سیو شده

if(

    $showSavedMusic

    &&

    !$isSaved

){

    continue;

}



?>





<div class="song-row">





<img

class="song-cover"

src="music-covers/<?php echo htmlspecialchars($song['cover']); ?>"

alt="cover"

>







<div class="song-info">



<div class="song-name">

<?php echo htmlspecialchars($song["title"]); ?>

</div>





<div class="song-artist">

<?php echo htmlspecialchars($song["artist"]); ?>

</div>





<div class="song-likes">

❤️ <?php echo $song["likes"] ?? 0; ?>

</div>




</div>









<div class="song-buttons">





<!-- PLAY -->

<button

class="play-song"

onclick="playMusic(<?php echo $index; ?>)"

>

▶

</button>







<!-- LIKE -->

<button

class="like-btn"

data-liked="<?php echo $isLiked ? 'true':'false'; ?>"

onclick="likeMusic('<?php echo htmlspecialchars($song['file'], ENT_QUOTES); ?>',this)"

>

<?php echo $isLiked ? "❤️":"🤍"; ?>

</button>







<!-- SAVE -->

<button

class="save-btn"

data-saved="<?php echo $isSaved ? 'true':'false'; ?>"

onclick="saveMusic('<?php echo htmlspecialchars($song['file'], ENT_QUOTES); ?>',this)"

>

<?php if($isSaved){ ?>


<svg

class="save-icon"

viewBox="0 0 24 24"

fill="currentColor"

aria-label="Saved"

>

<path d="M5 3.5C5 2.67 5.67 2 6.5 2h11c.83 0 1.5.67 1.5 1.5V22l-7-4.8L5 22V3.5Z"></path>

</svg>


<?php }else{ ?>


<svg

class="save-icon"

viewBox="0 0 24 24"

fill="none"

aria-label="Save"

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







<!-- DOWNLOAD -->

<a

class="download-btn"

href="music/<?php echo htmlspecialchars($song['file']); ?>"

download

aria-label="Download"

>

⬇

</a>







</div>





</div>





<?php



}



?>





</div>

</div>






<?php include "music-player.php"; ?>


<?php include "bottom-nav.php"; ?>







<script src="music.js"></script>









<script>


function likeMusic(file,button){



fetch(

"like_music.php?file="

+ encodeURIComponent(file)

)



.then(response=>response.text())



.then(data=>{



let row = button.closest(".song-row");



let count = row.querySelector(".song-likes");



if(count){

    count.innerHTML = "❤️ " + data;

}







if(button.dataset.liked=="true"){



    button.dataset.liked="false";


    button.innerHTML="🤍";



}

else{



    button.dataset.liked="true";


    button.innerHTML="❤️";



}



});



}















function bookmarkOutline(){


return `

<svg

class="save-icon"

viewBox="0 0 24 24"

fill="none"

aria-label="Save"

>

<path

d="M6.5 3h11c.83 0 1.5.67 1.5 1.5V21l-7-4.8L5.5 21V4.5C5.5 3.67 5.67 3 6.5 3Z"

stroke="currentColor"

stroke-width="2"

stroke-linecap="round"

stroke-linejoin="round"

></path>

</svg>

`;

}



function bookmarkFilled(){


return `

<svg

class="save-icon"

viewBox="0 0 24 24"

fill="currentColor"

aria-label="Saved"

>

<path d="M5 3.5C5 2.67 5.67 2 6.5 2h11c.83 0 1.5.67 1.5 1.5V22l-7-4.8L5 22V3.5Z"></path>

</svg>

`;

}













function saveMusic(file,button){



fetch(

"save_music.php?file="

+ encodeURIComponent(file)

)



.then(response=>response.text())



.then(()=>{






if(button.dataset.saved=="true"){



    button.dataset.saved="false";


    button.innerHTML=bookmarkOutline();



}

else{



    button.dataset.saved="true";


    button.innerHTML=bookmarkFilled();



}



});



}



</script>



<script>

/* =========================
   MOBILE CLICK FIX
========================= */

document.addEventListener("pointerdown", function(event){

    let element = event.target.closest(
        "a, button, [role='button']"
    );

    if(element){

        element.style.webkitTapHighlightColor="transparent";

    }

});


document.addEventListener("touchend", function(event){

    let element = event.target.closest(
        "a, button, [role='button']"
    );

    if(element){

        requestAnimationFrame(function(){

            if(document.activeElement === element){

                element.blur();

            }

        });

    }

}, {passive:true});


document.addEventListener("click", function(event){

    let element = event.target.closest(
        "a, button, [role='button']"
    );

    if(element){

        requestAnimationFrame(function(){

            if(document.activeElement === element){

                element.blur();

            }

        });

    }

});

</script>



</body>


</html>
```
