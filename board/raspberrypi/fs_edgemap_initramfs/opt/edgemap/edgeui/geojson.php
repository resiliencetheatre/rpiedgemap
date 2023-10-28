<?php
// TODO: More sanity check of invalid or empty data !
$db = new SQLite3('test.db');

// linkline between nodes (0/1)
$LINK_LINE = $_GET['linkline'];

//
// Query NAME's first
//
$res = $db->query('SELECT DISTINCT NAME FROM COT_DATA order by ID DESC');
$x=1;
while ($row = $res->fetchArray()) {
	// echo "{$row['ID']} {$row['NAME']} {$row['TIME']} {$row['LAT']} {$row['LON']} \n";
	if ( $row['NAME'] != "" ) {
		$NAME[$x] = "{$row['NAME']}";
		$x++;
	}
}  

//
// Count of items in DB
//
$ITEM_COUNT = $x-1;

// 
// Query each target: name, time, lat and lon (latest position)
// 
for ($loop = 1; $loop < $x; $loop++)  {
	$db = new SQLite3('test.db');
	$res = $db->query('SELECT * FROM COT_DATA WHERE NAME like "'.$NAME[$loop].'" order by ID DESC LIMIT 1 ');
	while ($row = $res->fetchArray()) {
		if ( $row['NAME'] != "" && $row['LAT'] != "" && $row['LON'] != ""  ) {
			$ITEM_NAME[$loop] = "{$row['NAME']}";
			$ITEM_TIME[$loop] = "{$row['TIME']}";
			$ITEM_LAT[$loop] = "{$row['LAT']}";
			$ITEM_LON[$loop] = "{$row['LON']}";
		}
	} 
}

/* 
 * Tail query 
 */
for ($loop = 1; $loop < $x; $loop++)  {
	
	$TAIL_LEN=20;
	$TAIL_NAME= $NAME[$loop]; // "2106";
	$db = new SQLite3('test.db');
	$res = $db->query('SELECT * FROM COT_DATA WHERE NAME like "'.$TAIL_NAME.'" order by ID DESC LIMIT '.$TAIL_LEN );
	$count=1;
	while ($row = $res->fetchArray()) {
		if ( $row['LAT'] != "" && $row['LON'] != ""  ) {
			$TAIL_LAT[$loop][$count] = "{$row['LAT']}";
			$TAIL_LON[$loop][$count] = "{$row['LON']}";
			$count++;
		}	
	}

}


/* 
 * Output geojson of targets 
 */ 
$loop = $ITEM_COUNT;
echo '
{ "type": "FeatureCollection", 
  "features": [';
	for ($x = 1; $x <= $loop; $x++)
	{
		echo ' 
			  { "type": "Feature",
			  "geometry": {"type": "Point", "coordinates": ['.$ITEM_LON[$x] .','.$ITEM_LAT[$x].']},
			  "properties": { "prop0": "'.$ITEM_NAME[$x].'",
			  "time-stamp": "'.$ITEM_TIME[$x].'" }
			  }
		';
		if ($x < $loop) {
			echo ",";
		}
	}
	echo ",";

	/* 
	 * Linestring (between from and to)
	 */
	if ( $LINK_LINE == "1" ) {
		$from=1;
		$to=2;
		$LON = $ITEM_LON[$from];
		$LAT = $ITEM_LAT[$from];
		$LON_2 = $ITEM_LON[$to];
		$LAT_2 = $ITEM_LAT[$to];
		echo '{ "type": "Feature",
			  "geometry": {"type": "LineString", "coordinates": [ ['.$LON .','.$LAT.'],['.$LON_2 .','.$LAT_2.'] ]},
			  "properties": { "color": "green", "width": 5, "opacity": 1 }
			  }
		';
		echo ",";
	}

	/* 
	 * Tail test 
	 */
	 for ($outer_loop = 1; $outer_loop < $x; $outer_loop++)  {
		 
		// start of one tail
		echo '{ "type": "Feature",
				  "geometry": {"type": "LineString",
				  "coordinates": [ ';
		
		for ($loop = 1; $loop < $TAIL_LEN-4 ; $loop++)  {
			if ( $TAIL_LON[$outer_loop][$loop] != "" && $TAIL_LAT[$outer_loop][$loop] != "" && $TAIL_LON[$outer_loop][$loop+1] != "" && $TAIL_LAT[$outer_loop][$loop+1] != "" ) {
				echo '  ['.$TAIL_LON[$outer_loop][$loop]  .','.$TAIL_LAT[$outer_loop][$loop].'],['.$TAIL_LON[$outer_loop][$loop+1] .','.$TAIL_LAT[$outer_loop][$loop+1].']  ';
				if ($loop < $TAIL_LEN-5) {
					if ( $TAIL_LON[$outer_loop][$loop+1] != "" && $TAIL_LAT[$outer_loop][$loop+1] != "" && $TAIL_LON[$outer_loop][$loop+2] != "" && $TAIL_LAT[$outer_loop][$loop+2] != "" ) {
						echo ",";
					}
				}
			}
		}	
		echo '] },
		  "properties": {"color": "blue", "width": 5 , "opacity": 0.6 }
		  }';
		if ($outer_loop < $x -1) {
			echo ",";
		}
		// end of one tail
	
	}
	
	
echo "]
	  }";
?>
