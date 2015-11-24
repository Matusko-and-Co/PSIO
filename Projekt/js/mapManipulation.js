var currentMapSample = 0; // momentálne zobrazený snímok mapy
var mapData; // dvojrozmerné pole, uchováva údaje získané z JSON súboru
var running = 0; // 0-> animacia nebeží, 1-> animácia beží dopredu, -1 -> dozadu
var interval; // rýchlosť animácie (interval v ms medzi volaniami runAnimation)
var rangeInput; //HTML element rangeInput, slider na nastavenie snímku


/* Získa dáta zo servera.
*	@successListener - funkcia, čo má robiť aj bola požiadavka úspešná
*	@errorListener - funkcia, čo má robiť ak bola požiadavka neúspešná
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

/*
* Nastaví farbu mapy
* @row - riadok matice, v ktorom je 8 hodnôt percentuálneho počtu nakazených
*        podľa krajov
*/
function updateRegionTextAndColor(row){
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
    document.getElementById("text"+i).innerHTML = row[i]+"%";
  }
}

/*
* Zastaví animáciu
*/
function stopAnimation(){
  running = 0;
}

/*
* Inicializuje mapu
* Zaktívni prvky ovládania mapy
*/
function initializeMap(data, textStatus, jqXHR){
  mapData = data;
  updateRegionTextAndColor(mapData[0]);

  var mapControls = document.getElementsByClassName("mapControl");
  for (i = 0; i < mapControls.length; i++) {
    mapControls[i].disabled = false;
  }
  rangeInput = document.getElementById("rangeInput");
  rangeInput.max = mapData.length-1;
  rangeInput.value = 0;
}

/*
* Posunie mapu o 1 snimok
* @direction smer, ktorým sa má pohnúť
*/
function changeMapManually(direction){
  direction = parseInt(direction);
  move = currentMapSample + direction
  if (move == -1 || move >= mapData.length){
    return
  }
  else{
    currentMapSample = move;
    rangeInput.value = currentMapSample;
    updateRegionTextAndColor(mapData[currentMapSample]);
  }
}

/*
* Reprezentuje beh animácie
* Rekurzívne volá sama seba s daným časovým intervalom
*/
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
        updateRegionTextAndColor(mapData[currentMapSample]);
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
        updateRegionTextAndColor(mapData[currentMapSample]);
    }, interval);
  }
}

/*
* Nastaví mapu na konrkétny snímok
* @value číslo snímky, ktorá sa má zobraziť
*/
function setSample(value){
  currentMapSample = value;
  updateRegionTextAndColor(mapData[currentMapSample]);
}

/*
* Spustí animáciu dopredu
*/
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

/*
* Spustí animáciu dozadu
*/
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

/*
* Zvýši rýchlosť animácie (x2)
*/
function increaseAnimationSpeed(){
  interval /= 2;
}

/*
* Zníži rýchlosť animácie (x0.5)
*/
function decreaseAnimationSpeed(){
  interval *= 2;
}

/*
* Funkcia spracuje chybu (ak nebolo ziskanie JSON údajov zo servera úspešné)
*/
function handleError(){
  alert("error");
}

/*
* Naloaduje mapu zo servera volaním funkcia getDataFromServer
* Ak je volanie úspešné, pokračuje volaním initializeMap
* V opačnom prípade volá funkciu handleError
*/
function loadMap(){
  getDataFromServer(initializeMap, handleError);
}
