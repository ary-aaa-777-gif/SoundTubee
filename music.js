let musicList = [];

let currentMusicIndex = -1;


const audio = document.getElementById("main-audio");

const titleBox = document.getElementById("player-title");

const artistBox = document.getElementById("player-artist");

const coverBox = document.getElementById("player-cover-img");

const playBtn = document.getElementById("play-btn");

const progress = document.getElementById("music-progress");

const currentTimeBox = document.getElementById("current-time");

const durationBox = document.getElementById("duration");




// اگر پلیر در صفحه وجود نداشت

if(!audio){

    console.log("Music player not found");

}




// گرفتن لیست موزیک‌ها

fetch("music.json?v=" + Date.now())

.then(response => response.json())

.then(data => {


    musicList = data;


    restoreMusic();



});







// پخش موزیک

function playMusic(index){


    if(!musicList[index]){

        return;

    }



    currentMusicIndex=index;


    let song=musicList[index];



    audio.src="music/"+song.file;



    titleBox.innerHTML=song.title;


    artistBox.innerHTML=song.artist;


    coverBox.src="music-covers/"+song.cover;




    audio.play();



    playBtn.innerHTML="⏸";



    saveMusicState();



}







// توقف و پخش

function toggleMusic(){



    if(audio.paused){



        audio.play();


        playBtn.innerHTML="⏸";



    }

    else{



        audio.pause();


        playBtn.innerHTML="▶";



    }



}







// آهنگ بعدی

function nextMusic(){


    if(musicList.length===0){

        return;

    }



    currentMusicIndex++;



    if(currentMusicIndex>=musicList.length){


        currentMusicIndex=0;


    }



    playMusic(currentMusicIndex);



}







// آهنگ قبلی

function previousMusic(){


    if(musicList.length===0){

        return;

    }



    currentMusicIndex--;



    if(currentMusicIndex<0){


        currentMusicIndex=musicList.length-1;


    }



    playMusic(currentMusicIndex);



}// ذخیره وضعیت موزیک

function saveMusicState(){


    if(currentMusicIndex < 0){

        return;

    }



    localStorage.setItem(

        "currentMusic",

        currentMusicIndex

    );



    localStorage.setItem(

        "musicTime",

        audio.currentTime

    );



    localStorage.setItem(

        "musicPlaying",

        !audio.paused ? "true" : "false"

    );



}









// برگرداندن وضعیت قبلی

function restoreMusic(){



    let savedIndex = localStorage.getItem(

        "currentMusic"

    );



    let savedTime = localStorage.getItem(

        "musicTime"

    );



    let wasPlaying = localStorage.getItem(

        "musicPlaying"

    );







    if(savedIndex === null){

        return;

    }






    currentMusicIndex = Number(savedIndex);




    let song = musicList[currentMusicIndex];





    if(!song){

        return;

    }







    audio.src="music/"+song.file;



    titleBox.innerHTML=song.title;


    artistBox.innerHTML=song.artist;


    coverBox.src="music-covers/"+song.cover;






    audio.addEventListener(

        "loadedmetadata",

        function(){



            if(savedTime){


                audio.currentTime=Number(savedTime);


            }



        },

        {once:true}

    );






    // فقط وضعیت دکمه را حفظ می‌کنیم

    // autoplay اجباری نمی‌کنیم چون مرورگر بلاک می‌کند

    if(wasPlaying==="true"){

    audio.play()
    .then(()=>{

        playBtn.innerHTML="⏸";

    })
    .catch(()=>{

        playBtn.innerHTML="▶";

    });


}
else{


    playBtn.innerHTML="▶";


}




}









// وقتی آهنگ تمام شد

audio.addEventListener(

"ended",

function(){


    nextMusic();


}

);









// بروزرسانی زمان

audio.addEventListener(

"timeupdate",

function(){



    if(!audio.duration){

        return;

    }





    let percent =

    (audio.currentTime / audio.duration) * 100;





    progress.value=percent;





    currentTimeBox.innerHTML=

    formatTime(audio.currentTime);





    durationBox.innerHTML=

    formatTime(audio.duration);






    saveMusicState();



}

);









// تغییر زمان آهنگ

progress.addEventListener(

"input",

function(){



    if(audio.duration){



        audio.currentTime=

        (this.value/100)*audio.duration;



    }



}

);function formatTime(time){


    if(isNaN(time)){


        return "0:00";


    }




    let min=Math.floor(time/60);


    let sec=Math.floor(time%60);




    if(sec<10){


        sec="0"+sec;


    }




    return min+":"+sec;


}









// وقتی صفحه تغییر می‌کند یا فیلم پخش می‌شود
// فقط وضعیت موزیک حفظ می‌شود

function pauseMusicForVideo(){


    if(audio && !audio.paused){



        audio.pause();



        saveMusicState();



        if(playBtn){


            playBtn.innerHTML="▶";


        }



    }


}









// ادامه موزیک بعد از بسته شدن فیلم

function resumeMusicAfterVideo(){



    let wasPlaying = localStorage.getItem(

        "musicPlaying"

    );





    if(wasPlaying==="true"){



        audio.play()

        .then(()=>{


            if(playBtn){


                playBtn.innerHTML="⏸";


            }



        })

        .catch(()=>{



            // مرورگر اجازه پخش خودکار نداد

            console.log("Waiting for user interaction");



        });



    }



}









// برای استفاده از index.php

window.pauseMusicForVideo = pauseMusicForVideo;

window.resumeMusicAfterVideo = resumeMusicAfterVideo;