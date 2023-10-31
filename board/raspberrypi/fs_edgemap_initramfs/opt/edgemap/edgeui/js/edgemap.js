//
// This is planet.mbtiles version where planet.mbtiles is served
// by tileserver-gl-light running on RPi4 image.
// 
// 
var styleDynamic = 'styles/style.json.osmliberty';

var bounds_FI = [
    [-180, -90],
    [180, 85.06]
];

/* Create marker from incoming Message */
function createMarkerFromMessage(index, lon, lat, markerText) {
    var ll = new maplibregl.LngLat(lon, lat);
    mapPinMarkerPopup[index] = new maplibregl.Popup({
        offset: 35,
        closeOnClick: false,
    }).setHTML(markerText);
    // create DOM element for the marker TODO: Array?
    var el = document.createElement('div');
    el.id = 'marker';
    mapPinMarker[index] = new maplibregl.Marker({
            color: "#FF515E",
            draggable: false
        })
        .setLngLat(ll)
        .setPopup(mapPinMarkerPopup[index])
        .addTo(map);
    mapPinMarker[index].togglePopup();
}

/* Create new dragable marker and push it to array for later use */
function newDragableMarker() {
    var newPopup = new maplibregl.Popup({
        offset: 35,
        closeOnClick: false,
    }).setText('popup' + Date.now());
    var markerD = new maplibregl.Marker({
            draggable: 'true',
            id: 'c1'
        })
        .setLngLat(map.getCenter().toArray())
        .setPopup(newPopup)
        .addTo(map);
    markerD._element.id = "dM-" + Date.now();
    // inline dragend function
    markerD.on('dragend', () => {
        var lngLat = markerD.getLngLat();
        var msgLatValue = String(lngLat.lat);
        var msgLonValue = String(lngLat.lng);
        var templateValue = 'MARKER|[' + msgLonValue.substr(0, 8) + ',' + msgLatValue.substr(0, 8) + ']|';
        // Place marker info for msg out line for description type & send
        msgInput.value = templateValue;
        markerD.setPopup(new maplibregl.Popup().setHTML(templateValue)); 
        lastDraggedMarkerId = markerD._element.id;
    });
    dragMarkers.push(markerD);
    dragPopups.push(newPopup);
}

function addPopupToMarker(popupText) {
    mapPinMarkerPopup.setText(popupText);
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
    highrateMarker.setLngLat([lat, lon]);
    // Ensure it's added to the map. This is safe to call if it's already added.
    highrateMarker.addTo(map);
    // Request the next frame of the animation.
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
                'line-color': ['get', 'color'],
                'line-width': ['get', 'width'],
                'line-opacity': ['get', 'opacity']
            },
            'filter': ['==', '$type', 'LineString']
        }, 'drone');
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

function zoomIn() {
    currentZoom = document.getElementById('zoomlevel').innerHTML;
    if (currentZoom < 17) {
        currentZoom++;
        map.setZoom(currentZoom);
        document.getElementById('zoomlevel').innerHTML = currentZoom;
    }
}

function zoomOut() {
    currentZoom = document.getElementById('zoomlevel').innerHTML;
    if (currentZoom > 6) {
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
function checkVideoServer(cb) {
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
    if (videoAvail == true) {
        x.style.display = "";
        y.style.display = "";
    } else {
        x.style.display = "none";
        y.style.display = "none";
        // Instead of just hide, we can also stop streams (for bw reasons)
        // Note that resume needs page reload.
        document.getElementById("cam1").src = "";
        document.getElementById("cam2").src = "";
        document.getElementById("cam3").src = "";
        document.getElementById("cam4").src = "";
        document.getElementById("cam5").src = "";
        document.getElementById("cam6").src = "";
        document.getElementById("cam7").src = "";
        document.getElementById("cam8").src = "";
        document.getElementById("cam9").src = "";
        document.getElementById("cam10").src = "";
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
    if (ageSeconds < 60) {
        symbolCode = "SFGCUCR-----";
    } else {
        symbolCode = "SFGDUCR-----";
    }
    var updatedSym = new ms.Symbol(symbolCode, {
        size: symbolSize,
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
    if (map.hasImage(sName)) {
        map.updateImage(sName, updatedImg, {
            width: 252,
            height: 65
        });
    }
}
// Create image function, creates image element initially. 
// TODO: Size mismatch is an issue still. 
function createImage(sName) {
    var updatedSym = new ms.Symbol("SFGCUCR-----", {
        size: symbolSize,
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
    map.addImage(sName, updatedImg, {
        width: 252,
        height: 65
    });
}

function getCoordinatesToClipboard() {
    var copyText = document.getElementById('lat').innerHTML + "," + document.getElementById('lon').innerHTML;
    copyToClipboard(copyText);
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
    var min = 0;
    var max = 11;
    var nummin = 10
    var nummax = 50
    const csItems = ["ASTRA", "BLACK", "GOOFY", "HAME", "KAYA", "SHOG", "TIGER", "VAN", "WOLF", "GOAT", "IRON", "NOMAD"];
    var csIndex = Math.floor(Math.random() * (max - min + 1)) + min;
    var numValue = Math.floor(Math.random() * (nummax - nummin + 1)) + nummin;
    var callSign = csItems[csIndex] + "-" + numValue;
    return callSign;
}
