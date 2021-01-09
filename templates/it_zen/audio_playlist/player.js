const background = document.querySelector('#background'); // background derived from album cover below
const thumbnail = document.querySelector('#thumbnail'); // album cover 
const song = document.querySelector('#song'); // audio object

const songArtist = document.querySelector('.song-artist'); // element where track artist appears
const songTitle = document.querySelector('.song-title'); // element where track title appears
const progressBar = document.querySelector('#progress-bar'); // element where progress bar appears


let pPause = document.querySelector('#play-pause'); // element where play and pause image appears
const playListDivs = document.getElementsByClassName("play-list-row")

songIndex = 0;

thumbnails = ['https://sfdumitru.org/images/audio_player/img/monastery.jpg']; // object storing paths for album covers and backgrounds

// function where pp (play-pause) element changes based on playing boolean value - if play button clicked, change pp.src to pause button and call song.play() and vice versa.
let playing = true;
function playPause() {
    if (playing) {
        const song = document.querySelector('#song'),
        thumbnail = document.querySelector('#thumbnail');

        pPause.src = 'https://sfdumitru.org/images/audio_player/icons/pause.png';
        thumbnail.style.transform = "scale(1.15)";
        
        song.play();
        playing = false;
    } else {
        pPause.src = 'https://sfdumitru.org/images/audio_player/icons/play.png';
        thumbnail.style.transform = "scale(1)"
        
        song.pause();
        playing = true;
    }
}

// automatically play the next song at the end of the audio object's duration
song.addEventListener('ended', function(){
    nextSong();
});

// function where songIndex is incremented, song/thumbnail image/background image/song artist/song title changes to next index value, and playPause() runs to play next track 
function nextSong() {
    songIndex++;
    if (songIndex >= playListDivs.length) {
        songIndex = 0;
    };
    playSongByIndex(songIndex);
}

// function where songIndex is decremented, song/thumbnail image/background image/song artist/song title changes to previous index value, and playPause() runs to play previous track 
function previousSong() {
    songIndex--;
    if (songIndex < 0) {
        songIndex = 1;
    };
    playSongByIndex(songIndex);
}


function playSongByIndex(songIndex) {
    div_song = playListDivs[songIndex];
    var selectedSongName = div_song.getAttribute("song-name");
    var selectedOwner = div_song.getAttribute("song-owner");
    var selectedLink = div_song.getAttribute("song-link");
    playSong(selectedSongName, selectedOwner, selectedLink);
}


function playSong(song_name, song_owner, song_link) {

    song.src = song_link;
    thumbnail.src = thumbnails[0];

    songArtist.innerHTML = song_owner;
    songTitle.innerHTML = song_name;

    playing = true;
    playPause();

}

// update progressBar.max to song object's duration, same for progressBar.value, update currentTime/duration DOM
function updateProgressValue() {
    progressBar.max = song.duration;
    progressBar.value = song.currentTime;
    document.querySelector('.currentTime').innerHTML = (formatTime(Math.floor(song.currentTime)));
    if (document.querySelector('.durationTime').innerHTML === "NaN:NaN") {
        document.querySelector('.durationTime').innerHTML = "0:00";
    } else {
        document.querySelector('.durationTime').innerHTML = (formatTime(Math.floor(song.duration)));
    }
};

// convert song.currentTime and song.duration into MM:SS format
function formatTime(seconds) {
    let min = Math.floor((seconds / 60));
    let sec = Math.floor(seconds - (min * 60));
    if (sec < 10){ 
        sec  = `0${sec}`;
    };
    return `${min}:${sec}`;
};

// run updateProgressValue function every 1/2 second to show change in progressBar and song.currentTime on the DOM
setInterval(updateProgressValue, 500);

// function where progressBar.value is changed when slider thumb is dragged without auto-playing audio
function changeProgressBar() {
    song.currentTime = progressBar.value;
};



function getPlaylistItem(song_index) {
    div_playlist = `
        <div class="play-list-row" data-track-row="${song_index}">
            <div class="track-title">
                <a class="playlist-track" href="#">${songTitles[song_index]}</a>
            </div>
        </div>`;
    return div_playlist;
};


function generatePlaylist() {
    var song_index;
    for (song_index = 0; song_index < songs.length; song_index++) {
        playlist_view.innerHTML += getPlaylistItem(song_index);
    }
};

function addPlaylistListener() {
    var idx;
    var playListRows = document.getElementsByClassName("play-list-row")
    for (idx = 0; idx < playListRows.length; idx++) {
        var playListLink = playListRows[idx].children[0].children[0];

        //Playlist link clicked.
        playListLink.addEventListener("click", function(e) {
            e.preventDefault();
            var selectedSongName = this.parentNode.parentNode.getAttribute("song-name");
            var selectedOwner = this.parentNode.parentNode.getAttribute("song-owner");
            var selectedLink = this.parentNode.parentNode.getAttribute("song-link");
            var song_id = this.parentNode.parentNode.getAttribute("id");
            song_id = song_id.split('-');
            song_id = song_id[song_id.length-1];
            songIndex = parseInt(song_id);
            playSong(selectedSongName, selectedOwner, selectedLink);
        }, false);
    }


}

addPlaylistListener();

