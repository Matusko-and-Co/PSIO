var currentMapSample = 0;
var mapData;
var running = 0; // 0-> animacia nebezi, 1-> animacia bezi dopredu, -1 -> dozadu
var interval; // rychlost animacie (interval v ms medzi volaniami runAnimation)
var rangeInput;


/* Získa dáta zo servera.
*	@successListener - funkcia, čo má robiť aj bola požiadavka úspešná
*	@errorListener - funkcia, čo má robiť aj bola požiadavka neúspešná
*/
function getDataFromServer(successListener, errorListener){
  jQuery.ajax({
    url: 'data/data.json',
    contentType: "application/json",
    success: function(data, textStatus, jqXHR){
      var i,j;
      for(i = 0; i < data.length; i++){
        for(j = 0; j < data[0].length; j++){
          data[i][j] = parseFloat(data[i][j]);
        }
      }
      successListener(data, textStatus, jqXHR);
    },
    error: errorListener,
    dataType: 'json'
  });
}

/* Nastavi farbu mapy
* @row - riadok matice, v ktorom je 8 hodnot percentualneho poctu nakazenych
*        podla krajov (bude treba zoradit HTML, aby to sedelo so vstupom)
*/
function updateRegionColor(row){
  for (i = 0; i < row.length; i++){
    if (row[i] > 90) newColor = "#ff2b2b";
    else if (row[i] > 80) color = "#ec3e28";
    else if (row[i] > 70) color = "#d84828";
    else if (row[i] > 60) color = "#c95e25";
    else if (row[i] > 50) color = "#bb7222";
    else if (row[i] > 40) color = "#aa7a22";
    else if (row[i] > 30) color = "#998322";
    else if (row[i] > 20) color = "#888b22";
    else if (row[i] > 10) color = "#779422";
    else color = "#55a522";
    document.getElementById(i).style.fill = color;
  }
}

function stopAnimation(){
  running = 0;
}

function initializeMap(data, textStatus, jqXHR){
  mapData = data;
  updateRegionColor(mapData[0]);

  var mapControls = document.getElementsByClassName("mapControl");
  for (i = 0; i < mapControls.length; i++) {
    mapControls[i].disabled = false;
  }
  rangeInput = document.getElementById("rangeInput");
  rangeInput.max = mapData.length-1;
  rangeInput.value = 0;
}

function changeMapManually(direction){
  direction = parseInt(direction);
  move = currentMapSample + direction
  if (move == -1 || move >= mapData.length){
    return
  }
  else{
    currentMapSample = move;
    rangeInput.value = currentMapSample;
    updateRegionColor(mapData[currentMapSample]);
  }
}

function runAnimation(){
  if (running == 1){
    if (currentMapSample == mapData.length-1){
      running = 0;
      return;
    }
    currentMapSample++;
    rangeInput.value = currentMapSample;
    setTimeout(function() {
        requestAnimationFrame(runAnimation);
        updateRegionColor(mapData[currentMapSample]);
    }, interval);
  }
  if (running == -1){
    if (currentMapSample == 0){
      running = 0;
      return;
    }
    currentMapSample--;
    rangeInput.value = currentMapSample;
    setTimeout(function() {
        requestAnimationFrame(runAnimation);
        updateRegionColor(mapData[currentMapSample]);
    }, interval);
  }
}

function setSample(value){
  currentMapSample = value;
  updateRegionColor(mapData[currentMapSample]);
}

function startAnimation(){
  if (running == -1){
    running = 1;
  }
  if (running == 0){
    interval = 500;
    running = 1;
    runAnimation();
  }
}

function reverseAnimation(){
  if (running == 1){
    running = -1;
  }
  if (running == 0){
    interval = 500;
    running = -1;
    runAnimation();
  }
}

function increaseAnimationSpeed(){
  interval /= 2;
}

function decreaseAnimationSpeed(){
  interval *= 2;
}

/*
* Funkcia spracuje chybu (ak nebolo ziskanie JSON udajov zo servera uspesne)
*/
function handleError(){
  alert("error");
}

function loadMap(){
  getDataFromServer(initializeMap, handleError);
}
