<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>EdgeMap</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<script src="js/maplibre-gl.js"></script>
<link href="js/maplibre-gl.css" rel="stylesheet" />
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
<body>

<div id="map"></div>
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
	<button class="button-mobile" onClick="window.location.reload();"  title='reload page' ><i data-feather="refresh-cw" class="feather-normal"></i></button>
	<button class="button-mobile" onclick="toggleTail();" title='tail of targets'><i data-feather="git-branch" class="feather-normal"></i></button> 
	<button class="button-mobile-red" onClick="location.href='index.html';" title='Back to theatre selection' ><i data-feather="external-link" class="feather-normal"></i></button>
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
			<table width=100%>
				<tr>
				<td>
					<i data-feather="mouse-pointer" class="feather-small"></i><span id="lat" onclick="getCoordinatesToClipboard()" ></span>,<span id="lon" onclick="getCoordinatesToClipboard()"></span>
					<i style="display:none;" data-feather="activity" class="feather-small"></i><span style="display:none;" id="status"></span> 
					<i style="display:none;" data-feather="alert-triangle" class="feather-small"  ></i><span style="display:none;"> SIMULATION</span>
					<span><i style="display:none;" data-feather="chevrons-right" class="feather-small"></i> <span style="display:none;" id="socketStatus"></span></span> 
					<span><i style="display:none;" data-feather="message-square" class="feather-small"></i> <span style="display:none;" id="msgSocketStatus"></span></span> 
				</td>
				<td align="right";>
					<span id="log-icon" onclick="openMessageEntryBox();"><i data-feather="menu" class="feather-mid"></i></span> 
					<span style="display:none;" id="info-icon"><i data-feather="help-circle" class="feather-mid"></i></span>
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
			Milsymbol <a href="https://github.com/spatialillusions/milsymbol"><i data-feather="github" class="feather-small"></i></a>
			Feather icons <a href="https://github.com/feathericons/feather"><i data-feather="github" class="feather-small"></i></a>
			Zoneminder <a href="https://github.com/ZoneMinder/ZoneMinder/"><i data-feather="github" class="feather-small"></i></a>
		</p>
		<p>
			Map data © OpenStreetMap contributors <a href="https://www.openstreetmap.org/copyright/"><i data-feather="link" class="feather-small"></i></a>
		</p>
		</center>
	</div>
	<center>
		<p style="font-size:16px" >© Resilience Theatre 2022 <a href="#"><i data-feather="link" class="feather-small"></i></a></p>
		<button class="button" id="infobox-close"><i data-feather="x-circle" class="feather-normal"></i> Close</button>
	</center>
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
	<input type="text" id="msgInput" type="text" class="messageInputField"  >
	<button id="sendMsg" class="msgbutton" onClick='' title='send' ><i data-feather="send" class="feather-msgbutton"></i></button>
</div>

<div id="lat_highrate" style="display: none;"></div>
<div id="lon_highrate" style="display: none;"></div>
<div id="name_highrate" style="display: none;"></div>

<script>
	
	// Take URL parameters for bounds and map name
	var query = window.location.search.substring(1);
	var qs = parse_query_string(query);
	
	/*  MAP PARAMETERS
		=========================================================
		1.  Select and set ACTIVE map bondaries and 'map name'
			[bounds] 	See: edgemap.js
			[map name] 	See: mbtile metadata
		2.  Set automatic center 'on load' coordinates if required. 
			Empty values => map middle point is used for center.		
		3.  GeoJSON source URL. Script which produces geojson.
		
	*/
    
	// var bounds = eval('bounds_' + qs.code);
	var targetMap = qs.map;
	var intialZoomLevel=7;
	var centerCoordinates_lat = '';
	var centerCoordinates_lon = '';
	if ( qs.zoom === undefined ) {
		intialZoomLevel = 7;
	} else {
		intialZoomLevel = qs.zoom;
	}
	if ( qs.lat == undefined || qs.lon === undefined ) 
	{
		centerCoordinates_lat = '';
		centerCoordinates_lon = '';
	} else {
		centerCoordinates_lat =  qs.lat;
		centerCoordinates_lon =  qs.lon;
	}

	// Instead of dedicated style, use dynamic (which gets rewritten on fly)
	var mapStyle = styleDynamic; 
	
	// GeoJson source example (disabled)
	var url = 'geojson.php?linkline=0';
	var symbolSize = 30;
	// We have one highrate marker as an example (disabled)
	var highrateMarker;
	var highRateCreated=false;
	// One user created pin marker for a demo
	const mapPinMarker = [];
	const mapPinMarkerPopup = [];
	var mapPinMarkerCount = 0;
	// Second way to handle draggable markers
	var dragMarkers = [];
	var dragPopups = [];
	var indexOfDraggedMarker;
	var lastDraggedMarkerId;
	// Generate Call Sign for demo
	var callSign = genCallSign();
	document.getElementById('myCallSign').value = callSign;
		
	function createNewDragableMarker() {
		newDragableMarker();
	}
	
	// 
	// Websocket connection to 'gwsocket' to receive highrate navigation
	// updates from 'live' targets. Note that gwsocket needs to create
	// pipe files and if highrate is run first, nothing works. This is bug.
	// Check that /tmp/wscontrol has pipe file attribute. 
	// example: gwsocket --pipein=/tmp/wscontrol
	//
	window.onload = function() {
		function $(selector) {
			return document.querySelector(selector);
		}
		// websocket for highrate target 		
		var wsProtocol = null;
		if(window.location.protocol === 'http:')
				wsProtocol = "ws://";
		else
				wsProtocol = "wss://";
		
        var wsHost = location.host;
	
		// Socket protocol, host and port
		var socket = new WebSocket(wsProtocol+wsHost+':7890');
		var msgSocket = new WebSocket(wsProtocol+wsHost+':7990');
		var statusSocket = new WebSocket(wsProtocol+wsHost+':7995');
		socket.onopen = function(event) {
			$('#socketStatus').innerHTML = 'HIGH RATE CONNECTED';
		};
		
        // Incoming highrate WS data. Placed on hidden div's to be read
		// by requestAnimationFrame() function. Do this better if you can.
		// TODO: Use Global variable instead
        // This is not used in this version.
		socket.onmessage = function(event) {
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
				var highrateName = document.getElementById('name_highrate').innerHTML;
				var highrateSymbol = new ms.Symbol(targetSymbol, { size:symbolSize,
					dtg: "",
					staffComments: highrateName.toUpperCase(),
					additionalInformation: "HIGHRATE TARGET (20 Hz)".toUpperCase(),
					combatEffectiveness: "".toUpperCase(),
					type: "".toUpperCase(),
					padding: 5
					});
				var canvasElement_highrate = highrateSymbol.asCanvas();
				var highrateSymbolOffset = 0 - highrateSymbol.getAnchor().x;
				var offsetMath = ( highrateSymbol.getSize().width / 2 ) - highrateSymbol.getAnchor().x;
				// Create image form symbol canvas
				var highrateSymbolImage = new Image();
				highrateSymbolImage.src = canvasElement_highrate.toDataURL();
				highrateMarker = new maplibregl.Marker(highrateSymbolImage,{
					offset: {
						x: offsetMath ,
						y: 0
					}
				});
				requestAnimationFrame(animateHighrateMarker);
				highRateCreated = true;
			}			
		};
		socket.onclose = function(event) {
			$('#socketStatus').innerHTML = 'HIGH RATE DISCONNECTED ' + event.reason;
			
		};
		/* Connect WS for messaging */
		msgSocket.onopen = function(event) {
			$('#msgSocketStatus').innerHTML = 'MSG CONNECTED';
		};
		/* 	msgSocket incoming */
		msgSocket.onmessage = function(event) {
			var incomingMessage = event.data;
			var trimmedString = incomingMessage.substring(0, 200);
			// We should NOT show messages which are starting with our callsign.
			if ( trimmedString.startsWith($('#myCallSign').value) == true ) {
				console.log("My own message detected, discarding.");
			} else {
				openMessageEntryBox(); 
				// TODO: validate & parse etc (this is just an example)
				$('#msgChannelLog').innerHTML += trimmedString;
				$('#msgChannelLog').innerHTML += "<br>";
				var scrollElement = document.getElementById('msgChannelLog');
				scrollElement.scrollTop = scrollElement.scrollHeight;
				// Marker payload if we have format: [FROM]|MARKER|[LAT,LON]|[MESSAGE]
				const msgArray=trimmedString.split("|");
				if ( msgArray.length == 4 ) 
				{
					/* Debug incoming fields
					console.log("From: ", msgArray[0]); 	// FROM 
					console.log("Type: ", msgArray[1]); 	// MARKER
					console.log("Location: ", msgArray[2]); // [lat,lon] 
					console.log("Message: ", msgArray[3]); 	// msg
					*/
					var location = msgArray[2];
					var locationNumbers = location.replace(/[\])}[{(]/g, '');
					const locationArray = locationNumbers.split(",");
					var markerText = "<b>" + msgArray[0] + "</b>:" + msgArray[3] + "<br>" + locationArray[1]+","+locationArray[0];		
					createMarkerFromMessage(mapPinMarkerCount, locationArray[0], locationArray[1],markerText );
					mapPinMarkerCount++;
				} 
			}
		};
		/* 	msgSocket outgoing */
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
				// console.log("Element ID ",loop," ID:", dragMarkers[loop]._element.id ); // YES
				if ( draggedMarkerID.localeCompare(dragMarkers[loop]._element.id) == 0 ) {
					grabbedIndex = loop;
						dragMarkers[grabbedIndex].setPopup(new maplibregl.Popup({ closeOnClick: false, }).setHTML(msgPayload)); 
						dragMarkers[grabbedIndex].togglePopup();
						lastDraggedMarkerId = ""; 
				}
			}
			
		};
		/* msgSocket disconnect function */
		msgSocket.onclose = function(event) {
			$('#msgSocketStatus').innerHTML = 'MSG DISCONNECTED ' + event.reason;
		};
		//
		// websocket for status channel (7995)
		// example: gwsocket -p 7995 --pipein=/tmp/statusin
		//
		statusSocket.onopen = function(event) {			
			document.getElementById("statusChannelState").classList.remove('crosshair_status_yellow');
			document.getElementById("statusChannelState").classList.add('crosshair_status_green');
		};
		// Incoming WS data for Sniper Control example
		statusSocket.onmessage = function(event) {
			var incomingMessage = event.data;
			var trimmedString = incomingMessage.substring(0, 200);
			// 
			// Example how you could parse websocket data to status display 
			// with low latency. 
			// Run: gwsocket -p 7995 --pipein=/tmp/statusin 
			// Test with: echo -n "[indicator],[on]/[off]" > /tmp/statusin
			// 
			const statusArray = trimmedString.split(",");
			var firstIndicator = document.getElementById( 'first-indicator' ); 
			var secondIndicator = document.getElementById( 'second-indicator' ); 
			var thirdIndicator = document.getElementById( 'third-indicator' ); 
			var indicator=statusArray[0];
			var state=statusArray[1];
			// First
			if ( indicator.localeCompare('1') == 0 && state.localeCompare('on') == 0 ) {
					firstIndicator.style.backgroundColor = '#0F0';
			}
			if ( indicator.localeCompare('1') == 0 && state.localeCompare('off') == 0 ) {
					firstIndicator.style.backgroundColor = '#F00';
			}
			if ( indicator.localeCompare('1') == 0 && state.localeCompare('idle') == 0 ) {
					firstIndicator.style.backgroundColor = '';
			}
			// Second
			if ( indicator.localeCompare('2') == 0 && state.localeCompare('on') == 0 ) {
					secondIndicator.style.backgroundColor = '#0F0';
			}
			if ( indicator.localeCompare('2') == 0 && state.localeCompare('off') == 0 ) {
					secondIndicator.style.backgroundColor = '#F00';
			}
			if ( indicator.localeCompare('2') == 0 && state.localeCompare('idle') == 0 ) {
					secondIndicator.style.backgroundColor = '';
			}
			// third
			if ( indicator.localeCompare('3') == 0 && state.localeCompare('on') == 0 ) {
					thirdIndicator.style.backgroundColor = '#0F0';
			}
			if ( indicator.localeCompare('3') == 0 && state.localeCompare('off') == 0 ) {
					thirdIndicator.style.backgroundColor = '#F00';
			}
			if ( indicator.localeCompare('3') == 0 && state.localeCompare('idle') == 0 ) {
					thirdIndicator.style.backgroundColor = '';
			}
		};
		statusSocket.onclose = function(event) {
			document.getElementById("statusChannelState").classList.remove('crosshair_status_green');
			document.getElementById("statusChannelState").classList.add('crosshair_status_red');
		};
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
	
	// 
	// 'log-window' open and close logic 
	//
	const logIcon = document.getElementById("log-icon");
	const logDiv = document.getElementById("log-window");
	const zoomDiv = document.getElementById("rightSensoryDisplay");
	const sensorDiv = document.getElementById("rightZoomButtons");
	const bottomBarDiv = document.getElementById("bottomBar");
	const callSignEntryBoxDiv =  document.getElementById("callSignEntry");
	
	function openCallSignEntryBox() {
		// callSignEntry
		callSignEntryBoxDiv.style.display = "block";
	}
	function closeCallSignEntryBox() {
		// callSignEntry
		callSignEntryBoxDiv.style.display = "none";
	}
		
	function openMessageEntryBox() {
		console.log("openMessageEntryBox() : ", logDiv.style.display);
		const canVibrate = window.navigator.vibrate
		if (canVibrate) window.navigator.vibrate(100)
		if ( logDiv.style.display == "" || logDiv.style.display == "none" )
		{
			const canVibrate = window.navigator.vibrate
			if (canVibrate) window.navigator.vibrate([200, 100, 200]);
			
			logDiv.style.display = "block";
			zoomDiv.style.display = "none";
			sensorDiv.style.display = "none";
			bottomBarDiv.style.display = "none"; 
		} 
	}
	
	function closeMessageEntryBox() {
		if ( logDiv.style.display == "" )
		{
		  logDiv.style.display = "block";
		  zoomDiv.style.display = "none";
		  sensorDiv.style.display = "none";
		} else {
		  if (logDiv.style.display !== "none" ) {
			logDiv.style.display = "none";
			zoomDiv.style.display = "block";
			sensorDiv.style.display = "block";
			bottomBarDiv.style.display = "block";
		  } else {
			logDiv.style.display = "block";
			zoomDiv.style.display = "none";
			sensorDiv.style.display = "none";
		  }
		}
	}
	// setRTLTextPlugin
	maplibregl.setRTLTextPlugin('js/mapbox-gl-rtl-text.js',null,true);
	//
	// Create MAP
	//
	var map = new maplibregl.Map({
	  container: 'map',
	  zoom: 1,
	  minZoom: 1,
	  style: "styles/style.json.osmliberty"
	});
	/* Geolocate
       NOTE: This requires SSL connection to work.
    */
	map.addControl(
		new maplibregl.GeolocateControl({
		positionOptions: {
		enableHighAccuracy: true
		},
		trackUserLocation: true
		})
	); 
	// 
	// Transform request for local sprite and glyphs at style(s)
	// See: https://github.com/mapbox/mapbox-gl-js/pull/9225
	// 
	map.setTransformRequest( (url, resourceType) => {
		// sprites and glyphs in style
		if (/^local:\/\//.test(url)) {
			// console.log("TransformRequest debug:", location.href);
			// console.log("TransformRequest debug #2: ", location.protocol+'//'+location.host);
			// return { url: new URL(url.substr('local://'.length), location.href).href };
			return { url: new URL(url.substr('local://'.length), location.protocol+'//'+location.host).href };
		}
		// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp/test
		if (/replaceme/.test(url)) {
			var newUrl = url.replace(/replaceme/g, targetMap );
			return { url: newUrl };
		}
		
		}
	);

	document.getElementById('zoomlevel').innerHTML = intialZoomLevel;
	feather.replace()
	/* Capture click coordinates to UI */
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
	//
	// Check video server on page load and set panes if videotoken has been supplied
	//
	if( getUrlParameter('videotoken') ) {
		checkVideoServer(function(res){
			videoPanelsVisible(res);
		});
	} else {
		videoPanelsVisible(false);
	}
	
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
	var symoffset = 0 - sym.getAnchor().x;	// TODO: make array of these
	var img = new Image();
	img.src = canvasElement.toDataURL();
	
	// Load map & get geojson from 'url' 
	map.on('load', function () {
		/*  Interval request function (2000 is default interval) 
            NOTE: This is disabled in this version. */
		var request = new XMLHttpRequest();
        if ( 0 ) 
        {
            window.setInterval(function () {
                
                request.open('GET', url, true);
                request.onload = function () {
                    if (this.status >= 200 && this.status < 400) {
                        // var indicator = document.getElementById( 'first-indicator' );
                        // indicator.style.backgroundColor = '#0F0';
                        // First do 'json' parse to create symbol images
                        // TODO: Check is this 'name' capture done in right way, it's bit a hack
                        var name;
                        var another = JSON.parse(this.response, function (key, value) {			
                            /* Target name (prop0) */
                            if ( key == "prop0" ) {
                                name=value;
                                /* Check symbol availability and create if needed */
                                if ( !map.hasImage( value ) ) {
                                    createImage(value);
                                }
                            }
                            /* Update image with timestamp */
                            if ( key == "time-stamp" ) {
                                /* Test to calculate 'age' of fix. Not in use. */
                                let currentTime = new Date();
                                let expireTime = new Date(value);
                                let ageSeconds = (currentTime - expireTime ) / (1000 );
                                roundedAge = Math.round(ageSeconds);
                                roundedAgeString = roundedAge.toString();
                                updateImage(name, value, roundedAgeString );
                            }
                        });
                        // Second. set 'json' to 'drone' source.
                        var json = JSON.parse(this.response);
                        map.getSource('drone').setData(json);
                        // Time of update to UI
                        var today = new Date();
                        document.getElementById('status').innerHTML = today.toISOString();
                        // indicator.style.backgroundColor = 'transparent';
                    } else
                    {
                        // var indicator = document.getElementById( 'first-indicator' );
                        // indicator.style.backgroundColor = '#F00';
                    }
                };
                request.send();
            }, 10000);
        } // if(0)
	
	/* Satellite layers */
	map.addLayer(
		{
			id: "satellite",
			type: "raster",
			source: "saakow"
		},
	);
	map.addLayer(
		{
			id: "satellite2",
			type: "raster",
			source: "moga"
		},
	);
	map.addLayer(
		{
			id: "satellite3",
			type: "raster",
			source: "kyiv"
		},
	);
	map.addLayer(
		{
			id: "satellite4",
			type: "raster",
			source: "lowerhitch"
		},
	);

	/* Zoom value update */
	map.on('zoom', function () {
			let zoom = map.getZoom();
            document.getElementById('zoomlevel').innerHTML = zoom.toFixed(0);
    	});
	
    
    /* 'drone' is target layer for geojson fetched targets */
    
    if (0) {
        map.addSource('drone', { type: 'geojson', data: url });
        map.addLayer({
            'id': 'drone',
            'type': 'symbol',
            'source': 'drone',
            'layout': {
                'icon-image': ['get', 'prop0'], // 'symbol-image',
                'icon-anchor': 'left',
                'icon-offset': [symoffset,0],
                'icon-allow-overlap': true,
                'icon-ignore-placement': true, 
                'text-allow-overlap': true,
                'text-field': ['get', 'prop0'],
                'text-font': [
                'Noto Sans Regular'
                ],
                'text-offset': [0, 1.8],
                'text-anchor': 'top'
                },
                'paint': {
                  "text-color": "#00f",
                  "text-halo-color": "#fff",
                  "text-halo-width": 1,
                  "text-halo-blur": 1
                },
            'filter': ['==', '$type', 'Point']
        }); 
       showTails(); 
    } // if (0)
    
	
}); // end of onload

	
	
</script>
</body>
</html>

