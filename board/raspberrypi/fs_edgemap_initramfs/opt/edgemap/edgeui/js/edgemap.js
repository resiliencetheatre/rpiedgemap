/*
 * This is stripped down version of edgemap.js
 * 
 */

/* Create marker from incoming Message */
function createMarkerFromMessage(index, lon, lat, markerText) {
	var ll = new maplibregl.LngLat(lon, lat);	
	// create the popup
	mapPinMarkerPopup[index] = new maplibregl.Popup({ offset: 35, closeOnClick: false,  }).setHTML(markerText);
	// create DOM element for the marker TODO: Array?
	var el = document.createElement('div');
	el.id = 'marker';
	mapPinMarker[index] = new maplibregl.Marker({
		color: "#FF515E",
		draggable: false
		})
		.setLngLat( ll )
		.setPopup(mapPinMarkerPopup[index])
		.addTo(map);
	mapPinMarker[index].togglePopup();
}

/* Create new dragable marker and push it to array for later use */
function newDragableMarker() {
	var newPopup = new maplibregl.Popup({ offset: 35, closeOnClick: false, }).setText('popup'+ Date.now());		
	var markerD = new maplibregl.Marker({
		draggable: 'true',
		id: 'c1'
	})
	.setLngLat( map.getCenter().toArray() )
	.setPopup(newPopup)
	.addTo(map);
	markerD._element.id = "dM-" + Date.now();
	// inline dragend function
	markerD.on('dragend', () => {
		var lngLat = markerD.getLngLat();
		var msgLatValue = String(lngLat.lat);
		var msgLonValue = String(lngLat.lng);	
		var templateValue = 'MARKER|[' + msgLonValue.substr(0,8) + ',' + msgLatValue.substr(0,8) + ']|';
		// Place marker info for msg out line for description type & send
		msgInput.value = templateValue;
		markerD.setPopup(new maplibregl.Popup().setHTML(templateValue)); // probably not needed
		lastDraggedMarkerId = markerD._element.id;
	});
	dragMarkers.push(markerD);
	dragPopups.push(newPopup);
}

function addPopupToMarker(popupText) {
	mapPinMarkerPopup.setText( popupText );
}

function eraseMsgLog() {
	document.getElementById('msgChannelLog').innerHTML = ""; 
}


function parse_query_string(query) {
  var vars = query.split("&");
  var query_string = {};
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    var key = decodeURIComponent(pair.shift());
    var value = decodeURIComponent(pair.join("="));
    // If first entry with this name
    if (typeof query_string[key] === "undefined") {
      query_string[key] = value;
      // If second entry with this name
    } else if (typeof query_string[key] === "string") {
      var arr = [query_string[key], value];
      query_string[key] = arr;
      // If third or later entry with this name
    } else {
      query_string[key].push(value);
    }
  }
  return query_string;
}


// Highrate marker animation function
function animateHighrateMarker(timestamp) {		
		var lat = document.getElementById('lat_highrate').innerHTML;
		var lon = document.getElementById('lon_highrate').innerHTML; 
		highrateMarker.setLngLat([lat,lon]);
		// Ensure it's added to the map. This is safe to call if it's already added.
		highrateMarker.addTo(map);
		// Request the next frame of the animation. ,
		requestAnimationFrame(animateHighrateMarker);
} 
// CoT target tail toggle
function toggleTail() {
	if (map.getLayer('route')) {
		hideTails();
	} else {
		showTails();
	}
}
// Add 'route' layer for LineString geojson display. 
// NOTE: Layer is added before 'drone' layer. 
function showTails() {
	if (!map.getLayer('route')) {
		/* line string layer */
		map.addLayer({
		'id': 'route',
		'type': 'line',
		'source': 'drone',
		'layout': {
		'line-join': 'round',
		'line-cap': 'round'
		},
		'paint': {
		'line-color':  ['get', 'color'],
		'line-width': ['get', 'width'],
		'line-opacity': ['get', 'opacity']
		},
		'filter': ['==', '$type', 'LineString']
		},'drone');
	}
}
function hideTails() {
	if (map.getLayer('route')) map.removeLayer('route'); 
}
// Options to change map style on fly.
// NOTE: Not in use, since style change loses symbols (TODO)
function setDarkStyle() {
	map.setStyle(style_FI_debug);
}
function setNormalStyle() {
	map.setStyle(style_FI);
}

function centerMap(lat,lon) {
    map.flyTo({
        // These options control the ending camera position: centered at
        // the target, at zoom level 9, and north up.
        center: [lat,lon],
        zoom: 15,
        bearing: 0,
        // These options control the flight curve, making it move
        // slowly and zoom out almost completely before starting
        // to pan.
        speed: 0.8, // make the flying slow
        curve: 1,   // change the speed at which it zooms out

        // This can be any easing function: it takes a number between
        // 0 and 1 and returns another number between 0 and 1.
        easing (t) {
            return t;
        },
        // this animation is considered essential with respect to prefers-reduced-motion
        essential: true
    });
}

function zoomIn() {
	currentZoom = document.getElementById('zoomlevel').innerHTML;
	if ( currentZoom < 17 ) {
		currentZoom++;
		map.setZoom(currentZoom);
		document.getElementById('zoomlevel').innerHTML = currentZoom;
	}
}
function zoomOut() {
	currentZoom = document.getElementById('zoomlevel').innerHTML;
	if ( currentZoom > 6 ) {
		currentZoom--;
		map.setZoom(currentZoom);
		document.getElementById('zoomlevel').innerHTML = currentZoom;
	}
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

// Support function to check video server presense on network
// TODO: hard coded IP of ZM instance still present
function checkVideoServer(cb){
	var img = new Image();
	img.onerror = function() {
		cb(false)
	}
	img.onload = function() {
		cb(true)
	}
	// Use fixed ZM image element as test point
	img.src = "http://192.168.5.97/zm/graphics/spinner.png?t=" + (+new Date);
}
function videoPanelsVisible(videoAvail) {
	var x = document.getElementById("leftVideo");
	var y = document.getElementById("rightVideo");
	if ( videoAvail == true ) {
		x.style.display = "";
		y.style.display = "";
	} else {
		x.style.display = "none";
		y.style.display = "none";
		// Instead of just hide, we can also stop streams (for bw reasons)
		// Note that resume needs page reload.
		document.getElementById("cam1").src="";
		document.getElementById("cam2").src="";
		document.getElementById("cam3").src="";
		document.getElementById("cam4").src="";
		document.getElementById("cam5").src="";
		document.getElementById("cam6").src="";
		document.getElementById("cam7").src="";
		document.getElementById("cam8").src="";
		document.getElementById("cam9").src="";
		document.getElementById("cam10").src="";
	}
}
// This will update image based on JSON parsing every 2 s 
// Only dynamic field is dtg: sTimeStamp
// NOTE: we have ageSeconds - but needs to illustrate it still
// NOTE: There is open issue with updating image when it size changes
// 		 maplibre-gl-js throws an error on such change. To be checked.
function updateImage(sName, sTimeStamp, ageSeconds) {
	// SFGAUCR-----	Anticipated
	// SFGPUCR----- Present
	// SFGCUCR----- Fully capable
	// SFGDUCR----- Damaged
	if ( ageSeconds < 60 ) {
		symbolCode = "SFGCUCR-----"; 
	} else {
		symbolCode = "SFGDUCR-----";
	}
	var updatedSym = new ms.Symbol(symbolCode, { size:symbolSize,
		dtg: "",
		staffComments: "".toUpperCase(),
		additionalInformation: "".toUpperCase(),
		combatEffectiveness: "".toUpperCase(),
		type: "",
		padding: 5
	});
	var updateCanvasElement = updatedSym.asCanvas();
	var updateSymoffset = 0 - updatedSym.getAnchor().x;				
	var updatedImg = new Image();
	updatedImg.src = updateCanvasElement.toDataURL();
	if ( map.hasImage( sName ) ) {
		map.updateImage( sName, updatedImg, { width: 252, height: 65 });
	}
}	
// Create image function, creates image element initially. 
// TODO: Size mismatch is an issue still. 
function createImage(sName) {
	var updatedSym = new ms.Symbol("SFGPUCR-----", { size:symbolSize,
	dtg: "",
	staffComments: "".toUpperCase(),
	additionalInformation: "".toUpperCase(),
	combatEffectiveness: "READY".toUpperCase(),
	type: "",
	padding: 5
	});
	var updateCanvasElement = updatedSym.asCanvas();
	var updateSymoffset = 0 - updatedSym.getAnchor().x;
	var updatedImg = new Image();
	updatedImg.src = updateCanvasElement.toDataURL();
	map.addImage(sName,updatedImg, { width: 252, height: 65 });
}

function getCoordinatesToClipboard() {
	var copyText = document.getElementById('lat').innerHTML + "," + document.getElementById('lon').innerHTML;
	copyToClipboard(copyText);
    fadeIn( document.getElementById("copyStatusIcon"), 0 );
    fadeOut( document.getElementById("copyStatusIcon"), 1400 );
} 

function changeLanguage(language) {
    /* Complete this property list */
    map.setLayoutProperty('places_country', 'text-field', ['get',`name:${language}` ]);
    map.setLayoutProperty('places_subplace', 'text-field', ['get',`name:${language}` ]);
    map.setLayoutProperty('places_locality', 'text-field', ['get',`name:${language}` ]);
    map.setLayoutProperty('places_region', 'text-field', ['get',`name:${language}` ]);    
    map.setLayoutProperty('roads_labels_minor', 'text-field', ['get',`name:${language}` ]);
    map.setLayoutProperty('roads_labels_major', 'text-field', ['get',`name:${language}` ]);
    closeLanguageSelectBox();
}

function addCat(lon,lat) {
     map.loadImage(
            'https://upload.wikimedia.org/wikipedia/commons/7/7c/201408_cat.png',
            (error, image) => {
                if (error) throw error;
                map.addImage('cat', image);
                map.addSource('point', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': [
                            {
                                'type': 'Feature',
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [lon,lat]
                                }
                            }
                        ]
                    }
                });
                map.addLayer({
                    'id': 'points',
                    'type': 'symbol',
                    'source': 'point',
                    'layout': {
                        'icon-image': 'cat',
                        'icon-size': 0.25
                    }
                });
            }
        );
}


// Example from maplibre-gl pulsedot
function addDot(lon,lat) {
    const size = 100;
    const pulsingDot = {
        width: size,
        height: size,
        data: new Uint8Array(size * size * 4),

        // get rendering context for the map canvas when layer is added to the map
        onAdd () {
            const canvas = document.createElement('canvas');
            canvas.width = this.width;
            canvas.height = this.height;
            this.context = canvas.getContext('2d');
        },
        // called once before every frame where the icon will be used
        render () {
            const duration = 1000;
            const t = (performance.now() % duration) / duration;
            const radius = (size / 2) * 0.3;
            const outerRadius = (size / 2) * 0.7 * t + radius;
            const context = this.context;
            // draw outer circle
            context.clearRect(0, 0, this.width, this.height);
            context.beginPath();
            context.arc(
                this.width / 2,
                this.height / 2,
                outerRadius,
                0,
                Math.PI * 2
            );
            context.fillStyle = `rgba(255, 200, 200,${1 - t})`;
            context.fill();
            // draw inner circle
            context.beginPath();
            context.arc(
                this.width / 2,
                this.height / 2,
                radius,
                0,
                Math.PI * 2
            );
            context.fillStyle = 'rgba(255, 100, 100, 1)';
            context.strokeStyle = 'white';
            context.lineWidth = 2 + 4 * (1 - t);
            context.fill();
            context.stroke();
            // update this image's data with data from the canvas
            this.data = context.getImageData(
                0,
                0,
                this.width,
                this.height
            ).data;
            // continuously repaint the map, resulting in the smooth animation of the dot
            map.triggerRepaint();
            // return `true` to let the map know that the image was updated
            return true;
        }
    };
    // 
    map.addImage('pulsing-dot', pulsingDot, {pixelRatio: 2});
    map.addSource('points', {
        'type': 'geojson',
        'data': {
            'type': 'FeatureCollection',
            'features': [
                {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [lon,lat]
                    }
                }
            ]
        }
    });
    map.addLayer({
        'id': 'points',
        'type': 'symbol',
        'source': 'points',
        'layout': {
            'icon-image': 'pulsing-dot'
        }
    });
}

function removeDot() {
    if (map.getImage("pulsing-dot")) {
        map.removeImage('pulsing-dot');
    }
    if (map.getLayer("points")) {
        map.removeLayer('points');
    }
    if (map.getSource("points")) {
        map.removeSource('points');
    }
}

// Nice example from stackoverflow how to capture coordinates on click to clipboard
// [1] https://stackoverflow.com/questions/51805395/navigator-clipboard-is-undefined
function copyToClipboard(textToCopy) {
	// navigator clipboard api needs a secure context (https)
	if (navigator.clipboard && window.isSecureContext) {
		// navigator clipboard api method'
		return navigator.clipboard.writeText(textToCopy);
	} else {
		// text area method
		let textArea = document.createElement("textarea");
		textArea.value = textToCopy;
		// make the textarea out of viewport
		textArea.style.position = "fixed";
		textArea.style.left = "-999999px";
		textArea.style.top = "-999999px";
		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();
		return new Promise((res, rej) => {
			// here the magic happens
			document.execCommand('copy') ? res() : rej();
			textArea.remove();
		});
	}
}

// Generate callsign for demos
function genCallSign() {
	var	min=0;
	var max=11;
	var nummin=10
	var nummax=50
	const csItems = ["ASTRA","BLACK","GOOFY","HAME","KAYA","SHOG","TIGER","VAN","WOLF","GOAT","IRON","NOMAD"];
	var csIndex = Math.floor(Math.random() * (max - min + 1) ) + min;
	var numValue = Math.floor(Math.random() * (nummax - nummin + 1) ) + nummin;
	var callSign=csItems[csIndex] + "-" + numValue;
	return callSign; 
}

// Dialog functions

function openLanguageSelectBox() {
    console.log("opening language");
    fadeIn(languageSelectDialogDiv,100);
    console.log("opening language 2");
}
function closeLanguageSelectBox() {
    fadeOut(languageSelectDialogDiv,100);
}
function openCoordinateSearchEntryBox() {
    document.getElementById('coordinateInput').value="";
    fadeIn(coordinateEntryBoxDiv,100);
    document.getElementById("coordinateInput").focus();
}
function closeCoordinateSearchEntryBox() {
    fadeOut(coordinateEntryBoxDiv,100);
}
function openCallSignEntryBox() {
    fadeIn(callSignEntryBoxDiv,200);
}
function closeCallSignEntryBox() {
    fadeOut(callSignEntryBoxDiv,200);
}
    
function openMessageEntryBox() {
    const canVibrate = window.navigator.vibrate
    if (canVibrate) window.navigator.vibrate(100)
    if ( logDiv.style.display == "" || logDiv.style.display == "none" )
    {
        const canVibrate = window.navigator.vibrate
        if (canVibrate) window.navigator.vibrate([200, 100, 200]);
        fadeIn(logDiv,200);
        fadeOut(zoomDiv,200);
        fadeOut(sensorDiv,200);
        fadeOut(bottomBarDiv,200);
    }
    document.getElementById("msgInput").focus();
}
function closeMessageEntryBox() {
    if ( logDiv.style.display == "" )
    {
      fadeIn(logDiv,200);
      fadeOut(zoomDiv,200);
      fadeOut(sensorDiv,200);
    } else {
      if (logDiv.style.display !== "none" ) {      
        fadeOut(logDiv,200);
        fadeIn(zoomDiv,200);
        fadeIn(sensorDiv,200);
        fadeIn(bottomBarDiv,200);
      } else {
        fadeIn(logDiv,200);
        fadeOut(zoomDiv,200);
        fadeOut(sensorDiv,200);
      }
    }
}

// fade in/out experiment
function fadeIn( elem, ms )
{
  if( ! elem )
    return;

  elem.style.opacity = 0;
  elem.style.filter = "alpha(opacity=0)";
  elem.style.display = "inline-block";
  elem.style.visibility = "visible";

  if( ms )
  {
    var opacity = 0;
    var timer = setInterval( function() {
      opacity += 50 / ms;
      if( opacity >= 1 )
      {
        clearInterval(timer);
        opacity = 1;
      }
      elem.style.opacity = opacity;
      elem.style.filter = "alpha(opacity=" + opacity * 100 + ")";
    }, 50 );
  }
  else
  {
    elem.style.opacity = 1;
    elem.style.filter = "alpha(opacity=1)";
  }
}

function fadeOut( elem, ms )
{
  if( ! elem )
    return;

  if( ms )
  {
    var opacity = 1;
    var timer = setInterval( function() {
      opacity -= 50 / ms;
      if( opacity <= 0 )
      {
        clearInterval(timer);
        opacity = 0;
        elem.style.display = "none";
        elem.style.visibility = "hidden";
      }
      elem.style.opacity = opacity;
      elem.style.filter = "alpha(opacity=" + opacity * 100 + ")";
    }, 50 );
  }
  else
  {
    elem.style.opacity = 0;
    elem.style.filter = "alpha(opacity=0)";
    elem.style.display = "none";
    elem.style.visibility = "hidden";
  }
}

function isHidden(el) {
    return (el.offsetParent === null)
}

// draglab
function onDrag() {
    const lngLat = dragMarker.getLngLat();
    var dragLocationPayload = `dragMarker|dragMarker|${lngLat.lng},${lngLat.lat}|drag_message`;
    var msgPayload = dragLocationPayload + '\n';
    msgSocket.send( msgPayload ); 
}
    
function onDragEnd() {
    const lngLat = dragMarker.getLngLat();
    var dragLocationPayload = `dragMarker|dragMarker|${lngLat.lng},${lngLat.lat}|dragend_message`;
    var msgPayload = dragLocationPayload + '\n';
    msgSocket.send( msgPayload );
}

//
// Show features for debugging, if you enabled this
// change display: [block/none] on edgemap-m.css: #features 
// 
function showFeatures(e)
{
        const features = map.queryRenderedFeatures(e.point);
        // Limit the number of properties we're displaying for
        // legibility and performance
        const displayProperties = [
            'type',
            'properties',
            'id',
            'layer',
            'source',
            'sourceLayer',
            'state'
        ];
        const displayFeatures = features.map((feat) => {
            const displayFeat = {};
            displayProperties.forEach((prop) => {
                displayFeat[prop] = feat[prop];
            });
            return displayFeat;
        });
        document.getElementById('features').innerHTML = JSON.stringify(
            displayFeatures,
            null,
            2
        );
}

function toggleHillShadow() {
    if ( isHidden(logDiv) ) {
        const visibility = map.getLayoutProperty(
            "hills",
            'visibility'
        );
        if (visibility === 'visible') {
            map.setLayoutProperty("hills", 'visibility', 'none');
        } else {
            map.setLayoutProperty("hills", 'visibility', 'visible');
        }   
    }
}


