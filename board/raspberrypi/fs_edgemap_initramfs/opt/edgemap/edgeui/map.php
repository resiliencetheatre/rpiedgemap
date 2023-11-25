<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>EdgeMap</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <script src="js/maplibre-gl.js"></script>
    <link href="js/maplibre-gl.css" rel="stylesheet" />
    <script src="protomaps-js/index.js"></script>
    <script src="js/milsymbol.js"></script>    
    <script src="js/feather.js"></script>
    <script src="js/edgemap.js"></script>
    <link href="css/edgemap.css" rel="stylesheet" />
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

    <div class="map-top-callsign-overlay">
    <center>
        <table border=0 width=100% >
        <tr>
            <td width=5% >
                <div class="tooltip"> 
                    <i id="msgSocketStatus" style="display: none; " class="feather-small" data-feather="message-square"></i>
                    <span class="tooltiptext">Message socket connected</span>
                </div>
            </td>
            <td width=5% >
                <div class="tooltip"> 
                    <i id="highRateSocketStatus" style="display: none; "  class="feather-small" data-feather="map-pin" ></i>
                    <span class="tooltiptext">Highrate socket connected</span>
                </div>
            </td>
            <td width=80%>
                <center>
                <div class="tooltip">
                    <span id="callSignDisplay" style="padding-right: 5px;" ></span> 
                    <span class="tooltiptext">Your callsign</span>
                </div>
                </center>
            </td>
            <td width=10%>
                <div class="tooltipright"> 
                    <i id="trackingIndicator" style="display: none;"  class="feather-small" data-feather="send" ></i>
                    <span class="tooltiptextright">Sharing your location</span>
                </div>
            </td>
        </tr>
        </table>
    </center>
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

    <div class="map-right-upload-overlay" id="cameracontrol">
        <div class="map-right-upload-overlay-inner">
            <div id="legend" class="legend">
                    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
                    <form id="uploadform" action="upload.php" method="post" enctype="multipart/form-data" target="dummyframe">
                      <center>
                      <input type="file" name="fileToUpload" id="fileToUpload" onchange="submitImage();" hidden>
                      <input type="hidden" name="lat" value="" size ="40" />
                      <input type="hidden" name="lon" value="" size ="40" />
                      <input type="hidden" name="callsign" value="" size ="40" />
                      <label for="fileToUpload"><i data-feather="camera" class="feather-normal"></i></label>
                      </center>
                    </form>
            </div>
            <div><span id="gpsStatus"></span></div>
        </div>
    </div>
    
    <div class="map-bottom-statusbar-overlay" id="bottomBar">
        <div id="legend" class="legend">
                <table width=100% border=0>
                    <tr >
                    <td width=40%>
                        
                        <div class="tooltipbottomleft">                         
                            <i style="display:none;" data-feather="mouse-pointer" class="feather-small"></i><span id="lat" onclick="getCoordinatesToClipboard()" ></span>,<span id="lon" onclick="getCoordinatesToClipboard()"></span>
                            <span class="tooltiptextbottomleft">Coordinates from map click.<p>Click on coordinates copies them to clipboard</p></span>
                        </div>
                        
                        <span id="copyStatusIcon" style="display:none;">Copied!</span><span style="" id="status"></span>
                        <img src="" id="debugImage" height=20; style="display:none;" ></img>
                        <i style="display:none;" data-feather="alert-triangle" class="feather-small"  ></i><span style="display:none;"> SIMULATION</span>
                    </td>
                    <td width=10%></td>
                    <td align="right"; width=50%>
                        
                        <div class="tooltipbottomright">
                            <span style="padding-right: 15px;" id="log-icon" onclick="toggleHillShadow();"><i data-feather="trending-up" class="feather-mid"></i></span> 
                            <span class="tooltiptextbottomright">Enable terrain model</span>
                        </div>
                        
                        <div class="tooltipbottomright">
                            <span style="padding-right: 15px;" id="log-icon" onclick="openLanguageSelectBox();"><i data-feather="globe" class="feather-mid"></i></span> 
                            <span class="tooltiptextbottomright">Change map language</span>
                        </div>
                        
                        <div class="tooltipbottomright">
                            <span style="padding-right: 15px;" id="log-icon" onclick="openCoordinateSearchEntryBox();"><i data-feather="target" class="feather-mid"></i></span> 
                            <span class="tooltiptextbottomright">Search with coordinates</span>
                        </div>
                        
                        
                        <div class="tooltipbottomright">    
                            <span style="padding-right: 15px;" id="info-icon"><i data-feather="help-circle" class="feather-mid"></i></span>
                            <span class="tooltiptextbottomright">About Edgemap</span>
                        </div>    
                            
                        <div class="tooltipbottomright">
                            <span id="log-icon" onclick="openMessageEntryBox();"><i data-feather="menu" class="feather-mid"></i></span> 
                            <span class="tooltiptextbottomright">Messaging & markers</span>
                        </div> 
                        
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
                protomaps <a href="https://protomaps.com/"><i data-feather="link" class="feather-small"></i></a><br>
                Map data © OpenStreetMap contributors <a href="https://www.openstreetmap.org/copyright/"><i data-feather="link" class="feather-small"></i></a>
            </p>
            </center>
        </div>
            <center><div class ="notify-box-small-content">Terrain data attribution:</div></center>
            <div class="attribution-div">
            * ArcticDEM terrain data DEM(s) were created from DigitalGlobe, Inc., imagery and funded under National Science Foundation awards 1043681, 1559691, and 1542736;
            * Australia terrain data © Commonwealth of Australia (Geoscience Australia) 2017;
            * Austria terrain data © offene Daten Österreichs – Digitales Geländemodell (DGM) Österreich;
            * Canada terrain data contains information licensed under the Open Government Licence – Canada;
            * Europe terrain data produced using Copernicus data and information funded by the European Union - EU-DEM layers;
            * Global ETOPO1 terrain data U.S. National Oceanic and Atmospheric Administration
            * Mexico terrain data source: INEGI, Continental relief, 2016;
            * New Zealand terrain data Copyright 2011 Crown copyright (c) Land Information New Zealand and the New Zealand Government (All rights reserved);
            * Norway terrain data © Kartverket;
            * United Kingdom terrain data © Environment Agency copyright and/or database right 2015. All rights reserved;
            * United States 3DEP (formerly NED) and global GMTED2010 and SRTM terrain data courtesy of the U.S. Geological Survey.
            </div>
        <center>
            <p style="font-size:16px" >© Resilience Theatre 2023 <a href="#"><i data-feather="link" class="feather-small"></i></a></p>
            <button class="attribution-button" id="infobox-close"><i data-feather="x-circle" class="feather-normal"></i> Close</button>
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
                    <span class="coordinateSearchTitle">Coordinates:</span><input id="coordinateInput" type="text" class="coordinateSearchInput" maxlength="20" onkeypress="handleKeyPress(event)" onfocus="ensureVisible(this)">
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
        <input type="text" id="msgInput" type="text" class="messageInputField" onfocus="ensureVisible(this)"  >
        <button id="sendMsg" class="msgbutton" onClick='' title='send' ><i data-feather="send" class="feather-msgbutton"></i></button>
    </div>

    <div class="map-bottom-log-entries" id="bottomLog" style="display: none;">
    <table width=100% border=0>
        <tr>
        <td ><i data-feather="info" id="notifyMessageIcon" class="feather-submitCallSignEntry"></i></td>
        <td width=80%><div id="notifyMessage"></div></td>
        </tr>
    </table>
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
                
    Edgemap UI
    -----------------------------------------------------------------
    * Enabled: Highrate, geolocate markers over msg channel
    * Enabled: terrain,  milsymbols use (as DOM element because maplibre-gl change)
    * Removed: CoT json support, sniper control examples
    * Supports only pmtiles OSM planet and raster satellite sources
    * Requires gwsocket and tacmsgrouter for messaging to work

    (C) Resilience-Theatre 2023
    
    [1] https://www.spatialillusions.com/milsymbol/documentation.html
    [2] https://maplibre.org/maplibre-gl-js/docs/API/

    TODO:   * presense notify msg
            * img upload
              https://stackoverflow.com/questions/41318487/adding-metadata-to-html5-file-upload
              https://stackoverflow.com/questions/6381247/posting-js-variable-from-html-form
    */
    
    

    var map = new maplibregl.Map({
      container: 'map',
      zoom: 1,
      minZoom: 1,
      style: "styles/style.json"
    });
    
    const edgemapUiVersion = "v0.2";
    var intialZoomLevel=1;
	var symbolSize = 30;
    
    // geojson url
    var geojsonUrl = 'geojson.php?linkline=1';
    var geoJsonLayerActive = true;
	
	// One user created pin marker for a demo
	const mapPinMarker = [];
	const mapPinMarkerPopup = [];
	var mapPinMarkerCount = 0;
    
    // Sensor markers from message (in DEVELOPMENT)
    const sensorMarker = [];
    const sensorMarkerPopup = [];
	
	// Second way to handle draggable markers (try out)
	var dragMarkers = [];
	var dragPopups = [];
	var indexOfDraggedMarker;
	var lastDraggedMarkerId;
	
	// Generate random callsign for a demo
	var callSign = genCallSign();
	document.getElementById('myCallSign').value = callSign;
    document.getElementById('callSignDisplay').innerHTML = callSign; 
    // populate callsign and default lat,lon into image upload form
    const formInfo = document.forms['uploadform'];
    formInfo.callsign.value = callSign; 
    formInfo.lat.value = "-"; 
    formInfo.lon.value = "-"; 
    
    // Draggable marker for sharing over msg channel
    var dragMarker;
    var dragMarkerPopup = new maplibregl.Popup({offset: 35});

    // We have one highrate marker as an example
	var highrateMarker;
	var highRateCreated=false;
    var milSymbolHighrate = new ms.Symbol("SFGPUCR-----", { size:20,
                dtg: "",
                staffComments: "Highrate".toUpperCase(),
                additionalInformation: "Highrate 20 Hz".toUpperCase(),
                combatEffectiveness: "".toUpperCase(),
                type: "",
                padding: 5
                });
    var milSymHighrateMarker = milSymbolHighrate.asDOM();

    // Geolocate to trackMessage markers
    const trackMessageMarkers = []; 
    var lastKnownCoordinates;
    
    // Milsymbol for trackMessage
    const trackMessageMarkerGraph = new ms.Symbol("SFGPUCR-----", { size:20,
                dtg: "",
                staffComments: "".toUpperCase(),
                additionalInformation: "".toUpperCase(),
                combatEffectiveness: "".toUpperCase(),
                type: "",
                padding: 5
                });
    var trackMessageMarkerGraphDom = trackMessageMarkerGraph.asDOM();

    // Create marker from messaging window
	function createNewDragableMarker() {
		newDragableMarker();
	}
    function $(selector) {
        return document.querySelector(selector);
    }
    
    // msgsocket
    var msgSocket;
    var highrateSocket;
    var wsProtocol = null;
    if(window.location.protocol === 'http:')
            wsProtocol = "ws://";
    else
            wsProtocol = "wss://";
    var wsHost = location.host;

    // 
    // Websocket for highrate
    // TODO: Check busy connecting
    // 
    highrateSocket = new WebSocket(wsProtocol+wsHost+':7890');    
    highrateSocket.onopen = function(event) {
        document.getElementById('highRateSocketStatus').style="display:block;"; 
    };
    
    highrateSocket.onmessage = function(event) {
			var incomingMessage = event.data;
			var trimmedString = incomingMessage.substring(0, 80);
			const positionArray = trimmedString.split(",");
			// TODO: Validate data better
			$('#lat_highrate').innerHTML =  positionArray[1];
			$('#lon_highrate').innerHTML =  positionArray[0];
			$('#name_highrate').innerHTML =  positionArray[2];
			var targetSymbol = positionArray[3];
            
			// Create highrate highrateMarker from first incoming message 
			if ( highRateCreated == false ) {
                highrateMarker = new maplibregl.Marker({
                    element: milSymHighrateMarker
				});
				requestAnimationFrame(animateHighrateMarker);
                highRateCreated = true;
			}
		};
		highrateSocket.onclose = function(event) {
            document.getElementById('highRateSocketStatus').style="display:none;";
		};

    // 
    // Websocket for messaging
    // * remember you need tacmsgrouter to run 
    //
    msgSocket = new WebSocket(wsProtocol+wsHost+':7990');    
    msgSocket.onopen = function(event) {
        document.getElementById('msgSocketStatus').style="display:block;"; 
    };
    
    //
    // msgSocket incoming
    //
    msgSocket.onmessage = function(event) {
        var incomingMessage = event.data;
        var trimmedString = incomingMessage.substring(0, 200);
        // We should NOT show messages which are starting with our callsign.
        if ( trimmedString.startsWith($('#myCallSign').value) == true ) {
            console.log("My own message detected, discarding.");
        } else {
            // Marker payload if we have format: [FROM]|MARKER|[LAT,LON]|[MESSAGE]
            // Drag marker: [FROM]|dragMarker|[LAT,LON]|["drag_message"/"dragend_message"]
            // TODO: Specify geolocate track marker format                
            const msgArray=trimmedString.split("|");
            const msgFrom =  msgArray[0];
            const msgType =  msgArray[1];
            const msgLocation =  msgArray[2];
            const msgMessage =  msgArray[3];
            
            if ( msgArray.length == 4 ) 
            {
                // Geolocate marker from message
                if ( msgType === "trackMarker" ) {
                    const location = msgLocation;
                    const locationNumbers = location.replace(/[\])}[{(]/g, '');
                    const locationArray = locationNumbers.split(",");
                    createTrackMarkerFromMessage(locationArray[0], locationArray[1],msgFrom,msgMessage);
                }
                // Drag marker
                if ( msgType === "dragMarker" ) {                        
                    const location = msgLocation;
                    const locationNumbers = location.replace(/[\])}[{(]/g, '');
                    const locationArray = locationNumbers.split(",");
                    dragMarker.setLngLat([ locationArray[0], locationArray[1] ]);
                    dragMarkerPopup.setText(msgFrom + " " + msgMessage);
                    
                    if ( msgMessage.includes("dragged") ) {
                        if ( !dragMarkerPopup.isOpen() ) {
                            dragMarkerPopup.addTo(map);
                        }
                    } 
                     if ( msgMessage.includes("released") )  {
                        if ( dragMarkerPopup.isOpen() ) {
                            dragMarkerPopup.remove();
                        }
                    } 
                }
                // Messaging 'drop in' marker
                if ( msgType === "dropMarker" ) {
                    const location = msgLocation;
                    const locationNumbers = location.replace(/[\])}[{(]/g, '');
                    const locationArray = locationNumbers.split(",");
                    const markerText = "<b>" + msgFrom + "</b>:" + msgMessage + "<br>" + locationArray[1]+","+locationArray[0];		
                    createMarkerFromMessage(mapPinMarkerCount, locationArray[0], locationArray[1],markerText );
                    mapPinMarkerCount++;                        
                }
                //
                // sensorMarker|[31.3275146,62.6350321]|path12,alive ping,SFGPUCR-----
                // Sensor marker: [from]|sensorMarker|[LAT,LON]|markedId,markerStatus
                //
                if ( msgType == "sensorMarker" ) {
                    const location = msgLocation;
                    const locationNumbers = location.replace(/[\])}[{(]/g, '');
                    const locationArray = locationNumbers.split(",");   
                    const sensorDataArray = msgMessage.split(",");
                    const sensorId = sensorDataArray[0];
                    const sensorStatus = sensorDataArray[1];
                    const sensorSymbol = sensorDataArray[2];
                    createSensorMarker(locationArray[0], locationArray[1],sensorId,sensorStatus,sensorSymbol);
                }

            }
            // Normal message 
            if ( msgArray.length != 4 && msgType != "dragMarker" && msgType != "trackMarker" ) {
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
    
    // msgSocket disconnect: remove top bar icon and notify user
    msgSocket.onclose = function(event) {
        document.getElementById('msgSocketStatus').style="display:none;";
        fadeIn(document.getElementById("bottomLog") ,400);
        const notifyMsgIcon=document.getElementById("notifyMessageIcon");
        document.getElementById("notifyMessage").innerHTML="Message channel disconnected! Try reloading page.";
        setTimeout(() => {
            fadeOut(document.getElementById("bottomLog") ,400);
        }, "5000");
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
    // Drag marker
    //
    dragMarker = new maplibregl.Marker({
        draggable: true
    });    
    dragMarker.setLngLat([0,0]);
    dragMarker.setPopup(dragMarkerPopup);
    dragMarkerPopup.setText("shared marker");
    dragMarkerPopup.addTo(map);
    dragMarker.on('dragend', onDragEnd);
    dragMarker.on('drag', onDrag);
    dragMarker.addTo(map);
    
    //
    // Reference draggable marker with MilSymbols
    //
    // Use as template or test terrain model with it
    // 
    var graphMarker;
    var graphMarkerPopup = new maplibregl.Popup({offset: 35});
    const milSymbolGraph = new ms.Symbol("SFGPUCR-----", { size:20,
                dtg: "",
                staffComments: "".toUpperCase(),
                additionalInformation: "".toUpperCase(),
                combatEffectiveness: "READY".toUpperCase(),
                type: "",
                padding: 5
                }).asDOM();
    graphMarker = new maplibregl.Marker({
        element: milSymbolGraph,
        draggable: true
    });    
    graphMarker.setLngLat([15,15]);
    graphMarker.setPopup(graphMarkerPopup);
    graphMarkerPopup.setText("Graphical marker");
    graphMarker.addTo(map);

    //
    // PNG from milsymbols to statusbar (unused)
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
    document.getElementById('debugImage').style="display:none;";

    // TODO: REFACTOR THIS !!!
    // This is 'placeholder' symbol. It generates only 'symoffset' and
	// this needs to be refactored away. 
	var sym = new ms.Symbol("SFGCUCR-----", { size:symbolSize,
		dtg: "",
		staffComments: "".toUpperCase(),
		additionalInformation: "".toUpperCase(),
		combatEffectiveness: "".toUpperCase(),
		type: "".toUpperCase(),
		padding: 5
		});
	var canvasElement = sym.asCanvas();
	var symoffset = 0 - sym.getAnchor().x;	// todo: make array of these
	var img = new Image();
	img.src = canvasElement.toDataURL();

    console.log("symoffset: ",symoffset);


    //
    // Interval loading
    //
    var mapLoaded=false;
    var request = new XMLHttpRequest();
    window.setInterval(function () {


    if ( geoJsonLayerActive && mapLoaded ) {
        // 
        // Get geojson 
        // NOTE: You need cotsim -> curlcot -> taky for this to work
        //
        request.open('GET', geojsonUrl, true);
        request.onload = function () {
            if (this.status >= 200 && this.status < 400) {
                // var indicator = document.getElementById( 'first-indicator' );
                // indicator.style.backgroundColor = '#0F0';
                // First do 'geojson' parse to create symbol images
                // TODO: Check is this 'name' capture done in right way, it's bit a hack
                var name;
                var another = JSON.parse(this.response, function (key, value) {			
                    // Target name (targetName) 
                    if ( key == "targetName" ) {
                        name=value;
                        // Check symbol availability and create if needed
                        if ( !map.hasImage( value ) ) {
                            createImage(value);
                        }
                    }
                    // Update image with timestamp 
                    if ( key == "time-stamp" ) {
                        // Test to calculate 'age' of fix. Not in use.
                        // Note: cotsim, curlcot does not provide time
                        // on test tracks! Just location.
                        let currentTime = new Date();
                        let expireTime = new Date(value);
                        let ageSeconds = (currentTime - expireTime ) / (1000 );
                        roundedAge = Math.round(ageSeconds);
                        roundedAgeString = roundedAge.toString();
                        updateImage(name, value, roundedAgeString ); // DEVELOPMENT * DO NOT ENABLE ON PRODUCTION *
                    }
                });
                // Second: set 'json' to 'drone' source.
                var json = JSON.parse(this.response);
                if (  map.getSource('drone') ) {
                    map.getSource('drone').setData(json);
                }
                // Time of update to UI
                // var today = new Date();
                // document.getElementById('status').innerHTML = today.toISOString();
                // indicator.style.backgroundColor = 'transparent';
            } else
            {
                // var indicator = document.getElementById( 'first-indicator' );
                // indicator.style.backgroundColor = '#F00';
            }
        };
        request.send();
    } else {
        console.log("Map not loaded or geoJsonLayer is false");
    }
    

        
    
    
    
    
    
            
    }, 5000 );
    
    //
    // Set an event listener that will fire
    // when the map has finished loading
    //
    map.on('load', function () {
        
        fadeIn(document.getElementById("bottomLog") ,400);
        document.getElementById("notifyMessage").innerHTML="EdgeMap "+ edgemapUiVersion +" ready!";
        setTimeout(() => {
            fadeOut(document.getElementById("bottomLog") ,1000);
        }, "5000");
        
        if ( geoJsonLayerActive  ) {
            
            // 
            // 'drone' is target layer for geojson data
            // * Calculating icon-offset for symbology text changes is still TODO
            // 
            map.addSource('drone', { type: 'geojson', data: geojsonUrl });
            map.addLayer({
                'id': 'drone',
                'type': 'symbol',
                'source': 'drone',
                'layout': {
                    'icon-image': ['get', 'targetName'], 
                    'icon-anchor': 'center',  // left
                    'icon-offset': [0,0],   // symoffset       
                    'icon-allow-overlap': true,
                    'icon-ignore-placement': true, 
                    'text-allow-overlap': true,
                    'text-field': ['get', 'targetName'],
                    'text-font': [
                    'Noto Sans Regular'
                    ],
                    'text-offset': [0, 1.2],
                    'text-anchor': 'top'
                    },
                    'paint': {
                      "text-color": "#00f",
                      "text-halo-color": "#eee",
                      "text-halo-width": 2,
                      "text-halo-blur": 2
                    },
                    'filter': ['==', '$type', 'Point']
            });
            // Enable tails for targets
            showTails();
        }
        console.log("Map loaded()");
        mapLoaded=true;
        
    }); 
    
    
    
    
    
    
    
    // 
    // Feature debug if off by default (use D to enable)
    //
    document.getElementById('features').style.display = 'none';     
    
    //
    // Geolocate (requires TLS)
    // about:config => geo.enabled
    //
    
    // Initialize the geolocate control
    let geolocate = new maplibregl.GeolocateControl({
      positionOptions: {
          enableHighAccuracy: true
      },
      trackUserLocation: true
    });
    // Add the control to the map.
    map.addControl(geolocate);
    
    geolocate.on('trackuserlocationstart', function() {
      console.log('A trackuserlocationstart event has occurred.')
      document.getElementById('trackingIndicator').style="display:block;";
      document.getElementById('gpsStatus').innerHTML = "GPS";
    });
    
    // On 'track end' deliver last known coordinates and 'Stopped' message
    geolocate.on('trackuserlocationend', function() {
      console.log('A trackuserlocationend event has occurred.')
      document.getElementById('trackingIndicator').style="display:none;"; 
      document.getElementById('gpsStatus').innerHTML = "";
      
      var dragLocationPayload = callSign + `|trackMarker|${lastKnownCoordinates.longitude},${lastKnownCoordinates.latitude}|Stopped`;
        var msgPayload = dragLocationPayload + '\n';
        msgSocket.send( msgPayload );
        console.log("Sent payload: " + msgPayload);
    });

    // Call back for position updates, fire 'trackMarker' message from these
    geolocate.on('geolocate', function(pos) {
        const crd = pos.coords;
        lastKnownCoordinates = pos.coords;
        /*
        console.log("Your current position is:");
        console.log(`Latitude : ${crd.latitude}`);
        console.log(`Longitude: ${crd.longitude}`);
        console.log(`More or less ${crd.accuracy} meters.`);
        */
        // Populate image upload form fields, just in case someone likes to take a foto
        const formInfo = document.forms['uploadform'];
        formInfo.lat.value = `${crd.latitude}`;
        formInfo.lon.value = `${crd.longitude}`;
        document.getElementById('gpsStatus').innerHTML = "GPS";

        // Create trackMarker message
        var dragLocationPayload = callSign + `|trackMarker|${crd.longitude},${crd.latitude}|tracking`;
        var msgPayload = dragLocationPayload + '\n';
        msgSocket.send( msgPayload );
    });

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
    
    map.on('zoom', function () {
            let zoom = map.getZoom();
            document.getElementById('zoomlevel').innerHTML = zoom.toFixed(0);
        });
   

    //
    // Keypress functions
    //
    function handleKeyPress(e){
     var key=e.keyCode || e.which;
      if (key==13){
        let inputValue = document.getElementById('coordinateInput').value;
        const coordValue = inputValue.split(",");
        if ( check_lat_lon(coordValue[1],coordValue[0]) == true) {
            console.log("AddDot");
            addDot(coordValue[1],coordValue[0]);
        }
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

