<?php

if(isset($musicPlayerLoaded) && $musicPlayerLoaded){
    return;
}

$musicPlayerLoaded = true;

?>

<div id="music-player" class="music-player">

    <div class="player-cover">
        <img
            id="player-cover-img"
            src="music-covers/default.png"
            alt="cover">
    </div>

    <div class="player-center">

        <div class="player-info">

            <div id="player-title">
                No Music
            </div>

            <div id="player-artist">
                --
            </div>

        </div>

        <div class="player-progress">

            <span id="current-time">
                0:00
            </span>

            <input
                type="range"
                id="music-progress"
                value="0"
                min="0"
                max="100">

            <span id="duration">
                0:00
            </span>

        </div>

    </div>

    <div class="player-controls">

        <button type="button" onclick="previousMusic()">
            ⏮
        </button>

        <button
            type="button"
            id="play-btn"
            onclick="toggleMusic()">
            ▶
        </button>

        <button type="button" onclick="nextMusic()">
            ⏭
        </button>

    </div>

    <audio id="main-audio"></audio>

</div>

<link rel="stylesheet" href="music.css">