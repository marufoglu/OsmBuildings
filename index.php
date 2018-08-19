<!DOCTYPE html>
<!--suppress ALL -->
<html>
<head>
  <title>OSM Buildings</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
    }

    #map {
      width: 100%;
      height: 100%;
    }

    .control {
      position: absolute;
      left: 0;
      z-index: 1000;
    }

    .control.tilt {
      top: 0;
    }

    .control.rotation {
      top: 45px;
    }

    .control.zoom {
      top: 90px;
    }

    .control.zoom button{
      font-weight: normal;
    }

    .control button {
      width: 30px;
      height: 30px;
      margin: 15px 0 0 15px;
      border: 1px solid #999999;
      background: #ffffff;
      opacity: 0.6;
      border-radius: 5px;
      box-shadow: 0 0 5px #666666;
      font-weight: bold;
      text-align: center;
    }

    .control button:hover {
      opacity: 1;
      cursor: pointer;
    }
  </style>
  <link rel="stylesheet" href="assets/css/OSMBuildings.css">
  <script src="assets/js/OSMBuildings.js"></script>
  <script src="assets/data/data.js"></script>
</head>

<body>
<div id="map"></div>

<div class="control tilt">
  <button class="dec">&#8601;</button>
  <button class="inc">&#8599;</button>
</div>

<div class="control rotation">
  <button class="inc">&#8630;</button>
  <button class="dec">&#8631;</button>
</div>

<div class="control zoom">
  <button class="dec">-</button>
  <button class="inc">+</button>
</div>

<script>
  const osmb = new OSMBuildings({
    container: 'map',
    zoom: 10,
    minZoom: 8,
    maxZoom: 19,
    position: { latitude: 39.022223, longitude: 43.373761 },
    state: true, // stores map position/rotation in url
    attribution: '© 3D <a href="https://osmbuildings.org/copyright/">OSM Buildings</a>'
  });

  osmb.addMapTiles(
    'https://{s}.tiles.mapbox.com/v3/osmbuildings.kbpalbpk/{z}/{x}/{y}.png',
    {
      attribution: '© Data <a href="https://openstreetmap.org/copyright/">OpenStreetMap</a> · © Map <a href="https://mapbox.com/">Mapbox</a>'
    }
  );

  osmb.addGeoJSON(data);

  // on pointer up
  osmb.on('pointerup', e => {
    // check for event targets aka clicked buildings
    const features = e.target;

    // if none, remove any previous selection and return
    if (!features) {
      osmb.highlight(feature => {});
      return;
    }

    // store id's from seleted items...
    const featureIDList = features.map(feature => feature.id);

    // ...then is is faster: set highlight color for matching features
    osmb.highlight(feature => {
      if (featureIDList.indexOf(feature.id) > -1) {
        return '#ffffff';
      }
    });
  });

  const controlButtons = document.querySelectorAll('.control button');

  controlButtons.forEach(button => {
    button.addEventListener('click', e => {
      const parentClassList = button.parentNode.classList;
      const direction = button.classList.contains('inc') ? 1 : -1;
      let increment, property;

      if (parentClassList.contains('tilt')) {
        property = 'Tilt';
        increment = direction*10;
      }
      if (parentClassList.contains('rotation')) {
        property = 'Rotation';
        increment = direction*10;
      }
      if (parentClassList.contains('zoom')) {
        property = 'Zoom';
        increment = direction*1;
      }
      if (property) {
        osmb['set'+ property](osmb['get'+ property]()+increment);
      }
    });
  });


</script>
</body>
</html>
