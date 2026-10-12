<?php


require "auth.php";



if(!$isLoggedIn){


    header("Location: login.php");

    exit;


}




// فیلم‌ها

$likedVideos = $currentUser["liked_videos"] ?? [];

$savedVideos = $currentUser["saved_videos"] ?? [];


// موزیک‌ها

$likedMusic = $currentUser["liked_music"] ?? [];

$savedMusic = $currentUser["saved_music"] ?? [];





$likedCount = count($likedVideos);

$savedCount = count($savedVideos);

$likedMusicCount = count($likedMusic);

$savedMusicCount = count($savedMusic);



?>





<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">



<title>User Panel</title>



<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="music.css">



<style>

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


a,
button,
[role="button"]{

    touch-action:manipulation;

}


/* =========================
   PANEL SVG ICONS
========================= */

.panel-svg-icon{

    width:21px;

    height:21px;

    display:inline-block;

    vertical-align:middle;

    margin-left:8px;

    flex-shrink:0;

}


.panel-item{

    display:flex;

    align-items:center;

    justify-content:center;

    gap:7px;

}


/* آیکون داخل کارت آمار */

.stat-svg-icon{

    width:35px;

    height:35px;

    display:block;

}


/* آیکون Bookmark ذخیره شده */

.saved-stat-icon{

    color:white;

}

</style>



</head>





<body class="<?php echo $_COOKIE["theme"] ?? "dark"; ?>">





<div class="container">



<?php include "music-player.php"; ?>





<div class="profile-card">





<div class="profile-icon">

👤

</div>





<div class="profile-name">


<?php echo htmlspecialchars($currentUser["username"]); ?>


</div>





<div class="profile-role">


<?php



if($currentUser["role"]=="admin"){


echo "👑 Admin";


}

else{


echo "👤 User";


}



?>



</div>





</div>





<div class="dashboard-stats">



<div class="stat-card">


<div class="stat-icon">

❤️

</div>


<div class="stat-number">


<?php echo $likedCount; ?>


</div>


<div class="stat-title">

Liked Videos

</div>


</div>





<div class="stat-card">


<div class="stat-icon">


<svg

class="stat-svg-icon saved-stat-icon"

viewBox="0 0 24 24"

fill="none"

aria-hidden="true"

>

    <path

    d="M6.5 3h11c.83 0 1.5.67 1.5 1.5V21l-7-4.8L5.5 21V4.5C5.5 3.67 5.67 3 6.5 3Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    stroke-linejoin="round"

    ></path>

</svg>


</div>


<div class="stat-number">


<?php echo $savedCount; ?>


</div>


<div class="stat-title">

Saved Videos

</div>


</div>





<div class="stat-card">


<div class="stat-icon">

🎵

</div>


<div class="stat-number">


<?php echo $likedMusicCount; ?>


</div>


<div class="stat-title">

Liked Music

</div>


</div>





<div class="stat-card">


<div class="stat-icon">


<svg

class="stat-svg-icon saved-stat-icon"

viewBox="0 0 24 24"

fill="none"

aria-hidden="true"

>

    <path

    d="M6.5 3h11c.83 0 1.5.67 1.5 1.5V21l-7-4.8L5.5 21V4.5C5.5 3.67 5.67 3 6.5 3Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    stroke-linejoin="round"

    ></path>

</svg>


</div>


<div class="stat-number">


<?php echo $savedMusicCount; ?>


</div>


<div class="stat-title">

Saved Music

</div>


</div>





</div>






<div class="panel-menu">





<a class="panel-item" href="index.php">


🏠 Home


</a>





<a class="panel-item" href="index.php?liked=1">


❤️ Liked Videos


</a>





<a class="panel-item" href="index.php?saved=1">


<svg

class="panel-svg-icon"

viewBox="0 0 24 24"

fill="none"

aria-hidden="true"

>

    <path

    d="M6.5 3h11c.83 0 1.5.67 1.5 1.5V21l-7-4.8L5.5 21V4.5C5.5 3.67 5.67 3 6.5 3Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    stroke-linejoin="round"

    ></path>

</svg>


Saved Videos


</a>






<a class="panel-item" href="music.php?liked=1">


🎵 Liked Music


</a>





<a class="panel-item" href="music.php?saved=1">


<svg

class="panel-svg-icon"

viewBox="0 0 24 24"

fill="none"

aria-hidden="true"

>

    <path

    d="M6.5 3h11c.83 0 1.5.67 1.5 1.5V21l-7-4.8L5.5 21V4.5C5.5 3.67 5.67 3 6.5 3Z"

    stroke="currentColor"

    stroke-width="2"

    stroke-linecap="round"

    stroke-linejoin="round"

    ></path>

</svg>


Saved Music


</a>





<a class="panel-item" href="upload.php">


⬆ Upload Video


</a>





<a class="panel-item" href="upload_music.php">


🎵 Upload Music


</a>






<a class="panel-item" href="my-comments.php">


💬 My Comments


</a>





</div>






<?php if($currentUser["role"]=="admin"){ ?>






<div class="admin-box">





<div class="admin-title">


👑 Admin Panel


</div>






<a class="admin-item" href="settings.php">


⚙ Manage Website


</a>





<a class="admin-item" href="pending.php">


📥 Pending Uploads


</a>





<a class="admin-item" href="users.php">


👥 Users


</a>





</div>





<?php } ?>



<a class="panel-item" href="logout.php">


🚪 Logout


</a>






</div>






<script src="music.js"></script>



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