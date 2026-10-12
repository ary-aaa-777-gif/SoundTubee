```php
<?php

require "auth.php";
require "config.php";

$dataFile = "data.json";

if(file_exists($dataFile)){
    $videos = json_decode(
        file_get_contents($dataFile),
        true
    );
}else{
    $videos = [];
}

if(!is_array($videos)){
    $videos = [];
}

$exploreVideos = [];

foreach($videos as $video){

    if(!isset($video["file"])){
        continue;
    }

    $file = $video["file"];

    if(!file_exists("videos/".$file)){
        continue;
    }

    if(
        isset($video["category"])
        &&
        strtolower($video["category"]) ==
        strtolower($EXPLORE_CATEGORY)
    ){
        continue;
    }

    if(isset($video["duration"])){
        if($video["duration"] > $SHORT_VIDEO_LIMIT){
            continue;
        }
    }

    $exploreVideos[] = $video;
}

shuffle($exploreVideos);

$theme = $_COOKIE["theme"] ?? "dark";

?>

<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Explore</title>

<link rel="stylesheet" href="style.css">

<style>

html,
body{

    margin:0;
    padding:0;

    background:#000;

    height:100%;

    overflow:hidden;

}


/* =========================
   EXPLORE
========================= */

.explore-container{

    height:100vh;

    overflow-y:auto;

    scroll-snap-type:y mandatory;

    scroll-behavior:smooth;

    overscroll-behavior:none;

}


/* =========================
   REEL
========================= */

.reel{

    height:100vh;

    width:100%;

    scroll-snap-align:start;

    scroll-snap-stop:always;

    position:relative;

    background:#000;

    display:flex;

    align-items:center;

    justify-content:center;

}


/* =========================
   VIDEO
========================= */

.reel-video{

    width:100%;

    height:100vh;

    max-width:100%;

    max-height:100vh;

    object-fit:contain;

    background:#000;

}


/* =========================
   ACTIONS
========================= */

.reel-actions{

    position:absolute;

    right:20px;

    bottom:120px;

    display:flex;

    flex-direction:column;

    align-items:center;

    gap:14px;

}


/* =========================
   ACTION BUTTON
========================= */

.reel-actions button{

    width:56px;

    height:56px;

    padding:0;

    border:none;

    border-radius:0;

    background:transparent;

    color:#fff;

    cursor:pointer;

    display:flex;

    align-items:center;

    justify-content:center;

    transition:.2s ease;

    -webkit-tap-highlight-color:transparent;

}


/* بدون زوم عجیب روی موبایل */

.reel-actions button:active{

    transform:scale(.88);

}


/* =========================
   SVG ICONS
========================= */

.reel-icon{

    width:34px;

    height:34px;

    display:block;

}


/* قلب */

.like-icon{

    width:37px;

    height:37px;

}


/* =========================
   LIKE ACTIVE
========================= */

.like-btn[data-liked="true"]{

    color:#ff1f3d;

}


/* =========================
   COUNT
========================= */

.action-count{

    color:#fff;

    font-size:15px;

    font-weight:500;

    line-height:1;

    margin-top:-7px;

    margin-bottom:3px;

    text-align:center;

    text-shadow:0 1px 4px rgba(0,0,0,.8);

}


/* =========================
   DESKTOP
========================= */

@media (min-width:601px){

    .reel-video{

        width:auto;

        height:90vh;

        max-width:80vw;

    }

}


/* =========================
   MOBILE
========================= */

@media (max-width:600px){

    .explore-container{

        scroll-snap-type:y mandatory;

        -webkit-overflow-scrolling:touch;

    }

    .reel{

        height:100svh;

    }

    .reel-video{

        height:100svh;

    }

    .reel-actions{

        right:12px;

        bottom:110px;

        gap:11px;

    }

    .reel-actions button{

        width:52px;

        height:52px;

        background:transparent;

    }

    .reel-icon{

        width:32px;

        height:32px;

    }

    .like-icon{

        width:36px;

        height:36px;

    }

    .action-count{

        font-size:14px;

    }

}


/* =========================
   EXTRA SMALL
========================= */

@media (max-width:380px){

    .reel-actions{

        right:8px;

        bottom:100px;

    }

    .reel-actions button{

        width:49px;

        height:49px;

    }

    .reel-icon{

        width:30px;

        height:30px;

    }

    .like-icon{

        width:34px;

        height:34px;

    }

}

</style>

</head>


<body class="<?php echo $theme; ?>">


<div class="explore-container">


<?php

if(count($exploreVideos)==0){

    echo "

    <div style='color:white;text-align:center;padding-top:40vh'>

        No videos available

    </div>

    ";

}


foreach($exploreVideos as $video){

    $file = $video["file"];

    $likes = $video["likes"] ?? 0;

    $isLiked = false;

    $isSaved = false;


    if($currentUser){

        $isLiked = in_array(
            $file,
            $currentUser["liked_videos"] ?? []
        );

        $isSaved = in_array(
            $file,
            $currentUser["saved_videos"] ?? []
        );

    }

?>

<div class="reel">


<video

class="reel-video"

playsinline

loop

autoplay

preload="auto"

>

<source
src="videos/<?php echo htmlspecialchars($file); ?>"
>

</video>


<div class="reel-actions">


<!-- =========================
     LIKE
========================= -->

<button

class="like-btn"

data-liked="<?php echo $isLiked ? "true":"false"; ?>"

onclick="likeVideo('<?php echo htmlspecialchars($file); ?>',this)"

aria-label="Like"

>

<?php

if($isLiked){

    echo '

    <svg
    class="reel-icon like-icon"
    viewBox="0 0 24 24"
    fill="currentColor"
    >

        <path d="M12 21.2s-7.1-4.7-9.5-9C.7 9.1 2.2 5.4 6 4.7c2.2-.4 4.1.7 5.2 2.4C12.3 5.4 14.2 4.3 16.4 4.7c3.8.7 5.3 4.4 3.5 7.5-2.4 4.3-9.5 9-9.5 9Z"></path>

    </svg>

    ';

}else{

    echo '

    <svg
    class="reel-icon like-icon"
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

    ';

}

?>

</button>


<div class="action-count like-count">

<?php echo $likes; ?>

</div>


<!-- =========================
     COMMENTS
========================= -->

<button

class="comment-btn"

onclick="openComments('<?php echo htmlspecialchars($file); ?>')"

aria-label="Comments"

>

<svg
class="reel-icon"
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

</button>


<!-- =========================
     SAVE
========================= -->

<button

class="save-btn"

data-saved="<?php echo $isSaved ? "true":"false"; ?>"

onclick="saveVideo('<?php echo htmlspecialchars($file); ?>',this)"

aria-label="Save"

>

<?php

if($isSaved){

    echo '

    <svg
    class="reel-icon"
    viewBox="0 0 24 24"
    fill="currentColor"
    >

        <path d="M6.5 2.5h11c.8 0 1.5.7 1.5 1.5v17l-7-4.8-7 4.8V4c0-.8.7-1.5 1.5-1.5Z"></path>

    </svg>

    ';

}else{

    echo '

    <svg
    class="reel-icon"
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

    ';

}

?>

</button>


</div>


</div>


<?php

}

?>


</div>


<script>

/* =========================
   REELS
========================= */

const reels =
document.querySelectorAll(".reel");

const videos =
document.querySelectorAll(".reel-video");


/* =========================
   AUTO PLAY
========================= */

const observer =
new IntersectionObserver(

(entries)=>{

    entries.forEach(entry=>{

        let video =
        entry.target.querySelector(".reel-video");

        if(entry.isIntersecting){

            video.play().catch(()=>{});

        }else{

            video.pause();

            video.currentTime = 0;

        }

    });

},

{
    threshold:0.75
}

);


reels.forEach(reel=>{

    observer.observe(reel);

});


/* =========================
   VIDEO CLICK
========================= */

videos.forEach(video=>{

    video.addEventListener("click",()=>{

        if(video.paused){

            video.play().catch(()=>{});

        }else{

            video.pause();

        }

    });

});


/* =========================
   LIKE
========================= */

function likeVideo(file,button){

    fetch(
        "like.php?file="+encodeURIComponent(file)
    )

    .then(response => response.text())

    .then(data=>{

        let count =
        button.parentElement.querySelector(
            ".like-count"
        );

        if(count){

            count.innerHTML = data;

        }


        if(button.dataset.liked === "true"){

            button.dataset.liked = "false";

            button.innerHTML = `

            <svg
            class="reel-icon like-icon"
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

        }else{

            button.dataset.liked = "true";

            button.innerHTML = `

            <svg
            class="reel-icon like-icon"
            viewBox="0 0 24 24"
            fill="currentColor"
            >

                <path d="M12 21.2s-7.1-4.7-9.5-9C.7 9.1 2.2 5.4 6 4.7c2.2-.4 4.1.7 5.2 2.4C12.3 5.4 14.2 4.3 16.4 4.7c3.8.7 5.3 4.4 3.5 7.5-2.4 4.3-9.5 9-9.5 9Z"></path>

            </svg>

            `;

        }

    });

}


/* =========================
   SAVE
========================= */

function saveVideo(file,button){

    fetch(
        "save.php?file="+encodeURIComponent(file)
    )

    .then(response => response.text())

    .then(()=>{

        if(button.dataset.saved === "true"){

            button.dataset.saved = "false";

            button.innerHTML = `

            <svg
            class="reel-icon"
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

            `;

        }else{

            button.dataset.saved = "true";

            button.innerHTML = `

            <svg
            class="reel-icon"
            viewBox="0 0 24 24"
            fill="currentColor"
            >

                <path d="M6.5 2.5h11c.8 0 1.5.7 1.5 1.5v17l-7-4.8-7 4.8V4c0-.8.7-1.5 1.5-1.5Z"></path>

            </svg>

            `;

        }

    });

}


/* =========================
   COMMENTS
========================= */

function openComments(file){

    window.location.href =
    "comments.php?id="+encodeURIComponent(file);

}


/* =========================
   NEXT VIDEO
========================= */

videos.forEach((video,index)=>{

    video.addEventListener("ended",()=>{

        let next =
        reels[index+1];

        if(next){

            next.scrollIntoView({
                behavior:"smooth"
            });

        }

    });

});

</script>

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
```
