<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>EdgeMap</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <script src="js/maplibre-gl.js"></script>
    <link href="js/maplibre-gl.css" rel="stylesheet" />
    <script src="js2/index.js"></script>
    <script src="js/milsymbol.js"></script>
    <script src="icons/feather.js"></script>
    <script src="js/edgemap.js"></script>
    <link href="css/edgemap-m.css" rel="stylesheet" />
    <link rel="apple-touch-icon" sizes="57x57" href="app-icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="app-icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="app-icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="app-icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="app-icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="app-icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="app-icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="app-icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="app-icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="app-icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="app-icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="app-icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="app-icon/favicon-16x16.png">
    <link rel="manifest" href="app-icon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="app-icon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>

<body  style="background-color:#000000" >
    <div id="map"></div>
    <pre id="features"></pre>
    <pre id="coordinates" class="coordinates"></pre>

    <div id="leftVideo">
        <img src="<?php echo $CAM[0]; ?>" id='cam1' width=100%;>
        <img src="<?php echo $CAM[1]; ?>" id='cam2' width=100%;>
        <img src="<?php echo $CAM[2]; ?>" id='cam3' width=100%;>
        <img src="<?php echo $CAM[3]; ?>" id='cam4' width=100%;>
        <img src="<?php echo $CAM[4]; ?>" id='cam5' width=100%;>
    </div>
    <div id="rightVideo">
        <img src="<?php echo $CAM[4]; ?>" id='cam6' width=100%;>
        <img src="<?php echo $CAM[3]; ?>" id='cam7' width=100%;>
        <img src="<?php echo $CAM[2]; ?>" id='cam8' width=100%;>
        <img src="<?php echo $CAM[1]; ?>" id='cam9' width=100%;>
        <img src="<?php echo $CAM[0]; ?>" id='cam10' width=100%;>
    </div>

    <div class="map-top-mobile-overlay">
        <center>
            <button class="button-mobile" onClick="window.location.reload();"  title='Reload map' ><i data-feather="refresh-cw" class="feather-normal"></i></button>
            <button style="display:none;" class="button-mobile" onclick="toggleTail();" title='tail of targets'><i data-feather="git-branch" class="feather-normal"></i></button> 
            <button style="display:none;" class="button-mobile-red" onClick="location.href='map.php';" title='Reload' ><i data-feather="external-link" class="feather-normal"></i></button>
        </center>	
    </div>

    <div class="map-right-zoom-overlay" id="rightZoomButtons">
        <div class="map-right-zoom-overlay-inner">
            <div id="legend" class="legend">
                <center><span id="zoomlevel" style="font-size:16px;"></span></center>
                <p>
                    <button class="button-zoom" width=45px; onclick="zoomIn();" >+</button>
                </p>
                <p>
                    <button class="button-zoom" width=45px; onclick="zoomOut();" >-</button>
                </p>
            </div>
        </div>
    </div>

    <div class="map-right-command-overlay" id="rightSensoryDisplay">
        <div class="map-right-command-overlay-inner">
            <div id="legend" class="legend">
                <p>
                <center><i id="statusChannelState" class="crosshair_status_yellow" data-feather="crosshair"></i></center>
                <div id="first-indicator" class="button-command-indicator"></div>
                <div id="second-indicator" class="button-command-indicator"></div>
                <div id="third-indicator" class="button-command-indicator"></div>
                </p>
            </div>
        </div>
    </div>

    <div class="map-bottom-statusbar-overlay" id="bottomBar">
        <div id="legend" class="legend">
                <table width=100% border=0>
                    <tr >
                    <td width=40%>
                        <img src="" id="debugImage" height=20; style="display:none;" ></img>
                        <i data-feather="mouse-pointer" class="feather-small"></i><span id="lat" onclick="getCoordinatesToClipboard()" ></span>,<span id="lon" onclick="getCoordinatesToClipboard()"></span>
                        <span id="copyStatusIcon" style="display:none;">Copied!</span><span style="" id="status"></span>
                        
                        <i style="display:none;" data-feather="alert-triangle" class="feather-small"  ></i><span style="display:none;"> SIMULATION</span>
                        <span><i style="display:none;" data-feather="chevrons-right" class="feather-small"></i> <span style="display:none;" id="socketStatus"></span></span> 
                        <span><i style="display:none;" data-feather="message-square" class="feather-small"></i> <span style="display:none;" id="msgSocketStatus"></span></span> 
                    </td>
                    <td></td>
                    <td align="right"; width=60%>
                        <span style="padding-right: 20px;" id="log-icon" onclick="toggleHillShadow();"><i data-feather="trending-up" class="feather-mid"></i></span> 
                        <span style="padding-right: 20px;" id="log-icon" onclick="openLanguageSelectBox();"><i data-feather="globe" class="feather-mid"></i></span> 
                        <span style="padding-right: 20px;" id="log-icon" onclick="openCoordinateSearchEntryBox();"><i data-feather="target" class="feather-mid"></i></span> 
                        <span style="padding-right: 20px;" id="info-icon"><i data-feather="help-circle" class="feather-mid"></i></span>
                        <span id="log-icon" onclick="openMessageEntryBox();"><i data-feather="menu" class="feather-mid"></i></span> 
                    </td>
                    </tr>
                </table>

        </div>
    </div>

    <div class="notify-box" id="info-box">
        <center>
        EdgeMap - off-line-map for resilience
        </center>
        <div class ="notify-box-small-content">
            <center>
            <p>
            Based on following open source components:
            </p>
            <p>
                MapLibre GL JS <a href="https://github.com/maplibre/maplibre-gl-js"><i data-feather="github" class="feather-small"></i></a>
                Milsymbol <a href="https://github.com/spatialillusions/milsymbol"><i data-feather="github" class="feather-small"></i></a><br>
                Feather icons <a href="https://github.com/feathericons/feather"><i data-feather="github" class="feather-small"></i></a>
                Zoneminder <a href="https://github.com/ZoneMinder/ZoneMinder/"><i data-feather="github" class="feather-small"></i></a>
                protomaps <a href="https://protomaps.com/"><i data-feather="link" class="feather-small"></i></a>
            </p>
            <p>
                Map data © OpenStreetMap contributors <a href="https://www.openstreetmap.org/copyright/"><i data-feather="link" class="feather-small"></i></a>
            </p>
            </center>
        </div>
        <center>
            <p style="font-size:16px" >© Resilience Theatre 2023 <a href="#"><i data-feather="link" class="feather-small"></i></a></p>
            <button class="button" id="infobox-close"><i data-feather="x-circle" class="feather-normal"></i> Close</button>
        </center>
    </div>

    <div class="languageSelect" id="languageSelectDialog" >
        <center>
        <p>
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('zh');"><i data-feather="globe" class="feather-mid"></i>CN</span> 
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('he');"><i data-feather="globe" class="feather-mid"></i>HE</span> 
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('ar');"><i data-feather="globe" class="feather-mid"></i>AR</span> 
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('ru');"><i data-feather="globe" class="feather-mid"></i>RU</span> 
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('uk');"><i data-feather="globe" class="feather-mid"></i>UKR</span> 
        </p>
        <p>
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('my');"><i data-feather="globe" class="feather-mid"></i>MY</span> 
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('de');"><i data-feather="globe" class="feather-mid"></i>DE</span> 
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('es');"><i data-feather="globe" class="feather-mid"></i>ES</span> 
            <span style="padding-right: 5px;" id="log-icon" onclick="changeLanguage('fr');"><i data-feather="globe" class="feather-mid"></i>FR</span> 
            <span style="padding-right: 20px;" id="log-icon" onclick="changeLanguage('en');"><i data-feather="globe" class="feather-mid"></i>EN</span> 
        </p>
        </center>
    </div>

    <div class="coordinateSearch" id="coordinateSearchEntry" >
        <table border=0 width=100%>
            <tr>
                <td width=90%>
                    <span class="coordinateSearchTitle">Coordinates:</span><input id="coordinateInput" type="text" class="coordinateSearchInput" maxlength="20" onkeypress="handleKeyPress(event)">
                </td>
                <td>
                
                <i data-feather="check-circle" class="feather-submitCallSignEntry" onClick='closeCoordinateSearchEntryBox();' ></i> 
                </td>
            </tr>
        </table>	
    </div>

    <div class="callSignEntry" id="callSignEntry" >
        <table border=0 width=100%>
            <tr>
                <td width=90%>
                    <span class="callsignTitle">Callsign:</span><input id="myCallSign" type="text" class="callSignInput" maxlength="5" >
                </td>
                <td>
                
                <i data-feather="check-circle" class="feather-submitCallSignEntry" onClick='closeCallSignEntryBox();' ></i> 
                </td>
            </tr>
        </table>	
    </div>

    <div class="log-window" id="log-window">	
        <table width=100% border=0>
        <tr>
            <td width=82% > 
                <div id="msgChannelLog" class="incomingMsg"></div>
            </td>
            <td valign=top align=center>
                <i data-feather="x-circle" class="feather-closeMsgEntry" onClick='closeMessageEntryBox();' ></i> <p>
                <i data-feather="map-pin" class="feather-cmdButtons" onClick='createNewDragableMarker();'></i><p>
                <i data-feather="trash" class="feather-cmdButtons" onClick='eraseMsgLog();' ></i><p>
                <i data-feather="at-sign" class="feather-cmdButtons" onClick='openCallSignEntryBox();'></i>
            </td>
        </tr>
        </table>
        <input type="text" id="msgInput" type="text" class="messageInputField" onfocus="ensureVisible(this)" >
        <button id="sendMsg" class="msgbutton" onClick='' title='send' ><i data-feather="send" class="feather-msgbutton"></i></button>
    </div>

    <div id="lat_highrate" style="display: none;"></div>
    <div id="lon_highrate" style="display: none;"></div>
    <div id="name_highrate" style="display: none;"></div>

<script>
/*
     _____    _            __  __             
    | ____|__| | __ _  ___|  \/  | __ _ _ __  
    |  _| / _` |/ _` |/ _ \ |\/| |/ _` | '_ \ 
    | |__| (_| | (_| |  __/ |  | | (_| | |_) |
    |_____\__,_|\__, |\___|_|  |_|\__,_| .__/ 
                |___/                  |_|   
                
    Stripped down version of Edgemap UI:
    -----------------------------------------------------------------
    * Removed: CoT json support, highrate and sniper control examples
    * Removed: terrain,  milsymbols use
    * Supports only pmtiles OSM planet and raster satellite sources
    * Requires gwsocket and tacmsgrouter for messaging to work

    (C) Resilience-Theatre 2023

*/
	
    var intialZoomLevel=1;
	var symbolSize = 30;
	
	// One user created pin marker for a demo
	const mapPinMarker = [];
	const mapPinMarkerPopup = [];
	var mapPinMarkerCount = 0;
	
	// Second way to handle draggable markers (try out)
	var dragMarkers = [];
	var dragPopups = [];
	var indexOfDraggedMarker;
	var lastDraggedMarkerId;
	
	// Generate Call Sign for demo
	var callSign = genCallSign();
	document.getElementById('myCallSign').value = callSign;
    
    // Draggable marker for sharing over msg channel
    var dragMarker;

    // Create marker from messaging window
	function createNewDragableMarker() {
		newDragableMarker();
	}
    function $(selector) {
        return document.querySelector(selector);
    }
    
    // msgsocket
    var msgSocket;
    var wsProtocol = null;
    if(window.location.protocol === 'http:')
            wsProtocol = "ws://";
    else
            wsProtocol = "wss://";
    var wsHost = location.host;

    // connect websocket for messaging
    msgSocket = new WebSocket(wsProtocol+wsHost+':7990');    
    msgSocket.onopen = function(event) {
        $('#msgSocketStatus').innerHTML = 'MSG CONNECTED';
    };
    
    // msgSocket incoming 
    msgSocket.onmessage = function(event) {
        var incomingMessage = event.data;
        var trimmedString = incomingMessage.substring(0, 200);
        // We should NOT show messages which are starting with our callsign.
        if ( trimmedString.startsWith($('#myCallSign').value) == true ) {
            console.log("My own message detected, discarding.");
        } else {
            // Marker payload if we have format: [FROM]|MARKER|[LAT,LON]|[MESSAGE]
            // Drag marker: dragMarker|dragMarker|[LAT,LON]|["drag_message"/"dragend_message"]                
            const msgArray=trimmedString.split("|");
            /*
            console.log("From: ", msgArray[0]); 	// FROM 
            console.log("Type: ", msgArray[1]); 	// MARKER
            console.log("Location: ", msgArray[2]); // [lat,lon] 
            console.log("Message: ", msgArray[3]); 	// msg
            */
            if ( msgArray.length == 4 ) 
            {
                if ( msgArray[0] === "dragMarker" ) {
                    console.log("marker dragend data received!");                        
                    var location = msgArray[2];
                    var locationNumbers = location.replace(/[\])}[{(]/g, '');
                    const locationArray = locationNumbers.split(",");
                    dragMarker.setLngLat([ locationArray[0], locationArray[1] ]);
                }
                if ( msgArray[1] === "MARKER" ) {
                    var location = msgArray[2];
                    var locationNumbers = location.replace(/[\])}[{(]/g, '');
                    const locationArray = locationNumbers.split(",");
                    var markerText = "<b>" + msgArray[0] + "</b>:" + msgArray[3] + "<br>" + locationArray[1]+","+locationArray[0];		
                    createMarkerFromMessage(mapPinMarkerCount, locationArray[0], locationArray[1],markerText );
                    mapPinMarkerCount++;                        
                }
            }
            if ( msgArray.length != 4 && msgArray[0] != "dragMarker" ) {
                openMessageEntryBox(); 
                // TODO: sanitize, validate & parse etc (this is just an example)
                $('#msgChannelLog').innerHTML += trimmedString;
                $('#msgChannelLog').innerHTML += "<br>";
                var scrollElement = document.getElementById('msgChannelLog');
                scrollElement.scrollTop = scrollElement.scrollHeight;
            }
        }
    };
    
    // msgSocket outgoing 
    var input = document.getElementById("msgInput");
    input.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("sendMsg").click();
        }
    }); 
    
    $('#sendMsg').onclick = function(e) {
        var msgPayload = $('#myCallSign').value + '|' + $('#msgInput').value + '\n';
        msgSocket.send( msgPayload );
        $('#msgChannelLog').innerHTML += msgPayload  + '<br>';
        $('#msgInput').value = '';
        var scrollElement = document.getElementById('msgChannelLog');
        scrollElement.scrollTop = scrollElement.scrollHeight;
        // If marker dragend has filled message field, allow appended content to be
        // updated into dragged marker popup. 
        // lastDraggedMarkerId is set by 'dragend' inline function.
        var draggedMarkerID = lastDraggedMarkerId; 
        // Grab index where ID is found. TODO: Handle error state
        var grabbedIndex;
        for ( loop=0; loop < dragMarkers.length ; loop++) {	
            // console.log("Element ID ",loop," ID:", dragMarkers[loop]._element.id );
            if ( draggedMarkerID.localeCompare(dragMarkers[loop]._element.id) == 0 ) {
                grabbedIndex = loop;
                dragMarkers[grabbedIndex].setPopup(new maplibregl.Popup({ closeOnClick: false, }).setHTML(msgPayload)); 
                dragMarkers[grabbedIndex].togglePopup();
                lastDraggedMarkerId = ""; 
            }
        }
    };
    
    // msgSocket disconnect function 
    msgSocket.onclose = function(event) {
        $('#msgSocketStatus').innerHTML = 'MSG DISCONNECTED ' + event.reason;
    };
    
    // 'info window' open and close logic
    const targetDiv = document.getElementById("info-box");
    const btn = document.getElementById("infobox-close");
    const infoIcon = document.getElementById("info-icon");
    btn.onclick = function () {
      if (targetDiv.style.display !== "none") {
        targetDiv.style.display = "none";
      } else {
        targetDiv.style.display = "block";
      }
    };
    infoIcon.onclick = function () {
      if ( targetDiv.style.display == "" )
      {
          targetDiv.style.display = "block";
      } else {
          if (targetDiv.style.display !== "none" ) {
            targetDiv.style.display = "none";
          } else {
            targetDiv.style.display = "block";
          }
        }
    };
	
    // 'log-window' open and close logic variables
    const logIcon = document.getElementById("log-icon");
    const logDiv = document.getElementById("log-window");
    const zoomDiv = document.getElementById("rightSensoryDisplay");
    const sensorDiv = document.getElementById("rightZoomButtons");
    const bottomBarDiv = document.getElementById("bottomBar");
    const callSignEntryBoxDiv =  document.getElementById("callSignEntry");
    const coordinateEntryBoxDiv =  document.getElementById("coordinateSearchEntry");
    const languageSelectDialogDiv =  document.getElementById("languageSelectDialog");     
    // Set rtl text plugin and pmtiles protocol
    maplibregl.setRTLTextPlugin('js/mapbox-gl-rtl-text.js',null,true);
    let protocol = new pmtiles.Protocol();
    maplibregl.addProtocol("pmtiles",protocol.tile);

    //
    //  _ __ ___   __ _ _ __  
    // | '_ ` _ \ / _` | '_ \ 
    // | | | | | | (_| | |_) |
    // |_| |_| |_|\__,_| .__/ 
    //                 |_|  
    //
    var map = new maplibregl.Map({
      container: 'map',
      zoom: 1,
      minZoom: 1,
      style: "styles/style.json"
    });

    // Drag marker
    dragMarker = new maplibregl.Marker({
        draggable: true
    });    
    dragMarker.setLngLat([0,0]);
    dragMarker.on('dragend', onDragEnd);
    dragMarker.on('drag', onDrag);
    
    //
    // Proof that we get PNG from milsymbols to statusbar
    //
    var milSymbolTest = new ms.Symbol("SFGCUCR-----", { size:symbolSize,
        dtg: "",
        staffComments: "".toUpperCase(),
        additionalInformation: "".toUpperCase(),
        combatEffectiveness: "".toUpperCase(),
        type: "".toUpperCase(),
        padding: 5
        }).asCanvas().toDataURL();
    document.getElementById('debugImage').src = milSymbolTest; 
    document.getElementById('features').style.display = 'none';     
    //
    // Geolocate (requires TLS)
    //
    map.addControl(
        new maplibregl.GeolocateControl({
        positionOptions: {
        enableHighAccuracy: true
        },
        trackUserLocation: true
        })
    );     
    // Required for sprite loading
    map.setTransformRequest( (url, resourceType) => {
            if (/^local:\/\//.test(url)) {
                return { url: new URL(url.substr('local://'.length), location.protocol+'//'+location.host).href };
            }
        }
    );
    document.getElementById('zoomlevel').innerHTML = intialZoomLevel;
    feather.replace();
    
    // Capture click coordinates to UI 
    map.on('mousedown', function (e) {	
        JSON.parse(JSON.stringify(e.lngLat.wrap()) , (key, value) => {
          if ( key == 'lat' ) {
              let uLat = value.toString();
              document.getElementById('lat').innerHTML = uLat.substring(0,10);
          }
          if ( key == 'lng' ) {
              let uLon = value.toString();
              document.getElementById('lon').innerHTML = uLon.substring(0,10);
          }
        });	
    });
    
    // mapOnLoad() function
    map.on('load', function () {
        console.log("map on load()");
        // Zoom value update 
        map.on('zoom', function () {
            let zoom = map.getZoom();
            document.getElementById('zoomlevel').innerHTML = zoom.toFixed(0);
        });
        dragMarker.addTo(map);
    }); 

    
    //
    // Keypress functions
    //
    function handleKeyPress(e){
     var key=e.keyCode || e.which;
      if (key==13){
        let inputValue = document.getElementById('coordinateInput').value;
        const coordValue = inputValue.split(",");
        // centerMap(coordValue[1],coordValue[0]);
        addDot(coordValue[1],coordValue[0]);
        // addCat(coordValue[1],coordValue[0]);
        document.getElementById('coordinateInput').value="";   
        closeCoordinateSearchEntryBox();
      }
    }

    document.addEventListener("keyup", function(event) {
        const key = event.key;
        if (key === "m") {
           if ( isHidden(logDiv) ) openMessageEntryBox();
        }
        // Enable map features debugging if needed
         if (key === "D") {
            if ( isHidden(logDiv) ) { 
                if ( document.getElementById('features').style.display === 'block' ) {
                    document.getElementById('features').style.display = 'none'; 
                    map.off('mousemove', showFeatures );
                } else {
                    document.getElementById('features').style.display = 'block'; 
                    map.on('mousemove', showFeatures );
                }
                
            }
        }
        // Open coordinate find only if message entry (logDiv) is hidden
        if (key === "f") {   
            if ( isHidden(logDiv) ) {
                removeDot();
                openCoordinateSearchEntryBox();
                document.getElementById('coordinateInput').value="";
            }
        }
        if (key === "Escape") {
            document.getElementById('coordinateInput').value="";   
            if ( !isHidden(coordinateEntryBoxDiv) ) closeCoordinateSearchEntryBox();
            if ( !isHidden(languageSelectDialogDiv) ) closeLanguageSelectBox();
            if ( !isHidden(logDiv) ) closeMessageEntryBox();
        }
        if (key === "h") {
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
        
        
    });

</script>
</body>
</html>

