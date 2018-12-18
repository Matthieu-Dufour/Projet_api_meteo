
//Coords de vitry 
var xy = [48.72192, 4.5850624];

// création de la map avec niveau de zoom
var map = L.map('map').setView(ny, 6);

// création du calque images
L.tileLayer('http://korona.geog.uni-heidelberg.de/tiles/roads/x={x}&y={y}&z={z}', {
	maxZoom: 20
}).addTo(map);

// ajout d'un markeur
var marker = L.marker(xy).addTo(map);

// ajout d'un popup
marker.bindPopup('<h3>VLF City</h3>');