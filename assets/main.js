const playlistButton = document.querySelectorAll(".pl-button");

const container = document.getElementById("main-view");
let elementosDeAudio = document.querySelectorAll("audio");
let currentSongIndex = 0;
let pause = 0;
let singlePlayButton = document.querySelectorAll(".single-song-play");

async function fetchPlayList(id) {
  const response = await fetch(
    `index.php?c=listaReproduccion&fetch-prueba=${id}`
  );
  const data = await response.json();
  return data;
}
async function loadPlaylist(id) {
  let elementos = "";
  container.innerHTML = "";
  const playlist = await fetchPlayList(id);
  elementos += `<header class="pl-header">`;
  elementos += `<img src="${playlist.img}" width="200px"/>`;
  elementos += `<h1 class="pl-title">${playlist.name}</h1>`;
  elementos += `<a href="index.php?c=cancion&pl-id=${playlist.id}">Añadir Cancion</a><br>`;
  elementos += `</header>`;
  if (playlist.songs) {
    elementos += `<button id="pl-play" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
            <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
        </svg></button>`;
    playlist.songs = playlist.songs.map((song) => JSON.parse(song));
    let contador = 0;
    playlist.songs.forEach((song) => {
      elementos += `<p>${song.title} - ${song.author}</p>
                    <p>Duración: ${song.duration}</p>
                    <audio controls>
                    <source src="${song.mp3}" type="audio/mpeg">
                    Your browser does not support the audio element.
                    </audio>
                    <button class="single-song-play" value="${contador}">Play</button>`;
      contador++;
    });
  }

  container.innerHTML += elementos;
  elementosDeAudio = document.querySelectorAll("audio");
  singlePlayButton = document.querySelectorAll(".single-song-play");
  singlePlayButton.forEach((boton) => {
    boton.addEventListener("click", () => {
      elementosDeAudio[currentSongIndex].pause();
      currentSongIndex = boton.value;
      startSongBarUpdate();
      playSong();
    });
  });
  document.getElementById("pl-play").addEventListener("click", () => {
    document
      .getElementById("barra-musica")
      .setAttribute("max", elementosDeAudio[currentSongIndex].duration);
    playSong();
    document.getElementById("play").addEventListener("click", playSong);
    document.getElementById("next").addEventListener("click", playNextSong);
    document.getElementById("back").addEventListener("click", playBackSong);
    document.getElementById("barra-musica").value = 0;
    startSongBarUpdate();
  });
}

playlistButton.forEach((boton) => {
  boton.addEventListener("click", () => {
    container.innerHTML = "";
    const id = boton.value;
    loadPlaylist(id);
  });
});

function playSong() {
  if (pause) {
    elementosDeAudio[currentSongIndex].pause();
    pause = 0;
  } else {
    elementosDeAudio[currentSongIndex].play();
    pause = 1;
  }
}

function playNextSong() {
  elementosDeAudio[currentSongIndex].pause();
  currentSongIndex = (currentSongIndex + 1) % elementosDeAudio.length;
  clearPlayer();
  startSongBarUpdate();
  elementosDeAudio[currentSongIndex].play();
}

function playBackSong() {
  elementosDeAudio[currentSongIndex].pause();
  currentSongIndex = (currentSongIndex - 1) % elementosDeAudio.length;
  clearPlayer();
  startSongBarUpdate();
  elementosDeAudio[currentSongIndex].play();
}

function calcSongTime(duration) {
  return Math.floor(duration) * 60 + (duration - Math.floor(duration)) * 100;
}

function startSongBarUpdate() {
  const barraMusica = document.getElementById("barra-musica");
  const timeElement = document.getElementById("time");
  const currentSong = elementosDeAudio[currentSongIndex];
  clearPlayer();
  // Comienza la actualización continua de la barra de progreso
  const updateInterval = setInterval(() => {
    // Verifica si la canción actual ha terminado
    if (currentSong.ended) {
      playNextSong(); // Reproduce la siguiente canción
      clearInterval(updateInterval); // Detiene la actualización
      return;
    }

    document.getElementById("next").addEventListener("click", () => {
      clearInterval(updateInterval);
      return;
    });

    document.getElementById("back").addEventListener("click", () => {
      clearInterval(updateInterval);
      return;
    });

    singlePlayButton.forEach((boton) => {
      boton.addEventListener("click", () => {
        clearInterval(updateInterval);
        return;
      });
    });

    // Actualiza la barra de progreso
    const currentTime = currentSong.currentTime;
    const duration = currentSong.duration;
    timeElement.textContent = Math.ceil(currentTime);
    barraMusica.value = currentTime;
  }, 1000); // Actualiza cada segundo (ajusta según lo necesites)
}

function clearPlayer() {
  const barraMusica = document.getElementById("barra-musica");
  const timeElement = document.getElementById("time");
  barraMusica.value = 0;
  timeElement.textContent = 0;
  elementosDeAudio[currentSongIndex].currentTime = 0;
}

// Agrega un evento 'ended' a cada elemento de audio para detectar el final de la reproducción
elementosDeAudio.forEach((audio, index) => {
  audio.addEventListener("ended", () => {
    clearPlayer();
    playNextSong();
  });
});

document.getElementById("barra-musica").addEventListener("change", () => {
  const nuevoTime = document.getElementById("barra-musica").value;
  elementosDeAudio[currentSongIndex].currentTime = nuevoTime;
  document.getElementById("time").textContent = nuevoTime;
});

document.getElementById("play").addEventListener("click", playSong);
document.getElementById("next").addEventListener("click", playNextSong);
document.getElementById("back").addEventListener("click", playBackSong);