<?php 

$file = "log.json";
$entryLimit = 500;
$previewTimeZone = "Europe/Berlin";//Only for preview output

$logData = array();
function addToLog($sentData,$fp) {
	global $file;
	global $logData;
	global $entryLimit;	
	$guid = rand(); //Generate Random Number
	
	$entry = array();
	$timestamp = time();
	
	$entry['time'] = $timestamp;
	$entry['guid'] = $guid;
	
	foreach ($sentData as $key => $value) {
		$entry[$key] = $value;
	}
	
	$logData = array_reverse($logData); //Reverse array to put recent entries to end
	array_push($logData, $entry); //Add to existing entries
	
	if( count($logData) > $entryLimit){ //If more entries than allowed
		$logData = array_reverse($logData); //Reverse array to maintain order of recent entries	
		$logDataFiltered = array();
	
		$counter = 0;
		foreach ($logData as $entry) {
			if ($counter == $entryLimit){
				break;
			}
			$counter = $counter + 1;
			array_push($logDataFiltered, $entry); //Add to filtered entries
		}
		
		$logData = $logDataFiltered;
		$logData = array_reverse($logData); //Reverse array to maintain order of recent entries	
	}
	
	$logData = array_reverse($logData); //Reverse array to keep recent entries in the first place
	
	rewind($fp);
	ftruncate($fp, 0);
	fwrite($fp, json_encode($logData)); //Save new data to file
}



if ( file_exists($file) ) { //Check if data exists

	$fp = fopen($file, 'r+');
	flock($fp, LOCK_EX); //Lock file to avoid other processes writing to it simlutanously 
	
	$jsonData = stream_get_contents($fp);
	if($jsonData == ""){
		echo "Could not read Log file (maybe empty)";
		die;
	}
	$logData = json_decode($jsonData, true);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(json_decode($_POST['data'], true))) { //If data received via POST
		
		//Debug
		//$debug = fopen('debug.txt', 'w');
		//fwrite($debug, $_POST['data']); //Save new data to file
		//fclose($debug);
		//Debug
		
		addToLog( json_decode($_POST['data'], true),$fp );
	}
	elseif (!empty($_GET)) { //If data received via GET
		if ( isset($_GET['delete']) ){ //If specific entry should be deleted
			if (empty($logData)) {
				echo "Could not read Log file (maybe empty)";
				die;
			}
			
			$counter = 0;
			$targetFound = false;
			
			foreach ($logData as $entryExisting) { //Get every entry
				foreach ($entryExisting as $key => $value) { //Get key and timestamp values
				
					if ($key == 'guid' && $value == $_GET['delete']){ //If target key has been found
						unset($logData[$counter]);//Remove entry from logData
						$targetFound = true;
						break 2;
					}
					
				}
				$counter = $counter + 1;
			}
			
			if($targetFound == true){
				$logData = array_values($logData); //Rebuild index
				
				rewind($fp);
				ftruncate($fp, 0);
				fwrite($fp, json_encode($logData)); //Save new data to file
				
				echo 'Deleted';
			}
			else{
				echo "Entry not found (maybe already deleted?)";
			}		
			
		}
	}

	
	flock($fp, LOCK_UN); //Unlock file for further access
	fclose($fp);	
}



?>

<!DOCTYPE html>
<html>
	<head>
		<title>AnyLog</title>
	</head>
	<body>
		<?php 
			if ( empty($_GET) && file_exists($file) ) { //Check if data exists
				foreach ($logData as $index => $entry) {
					$elementCount = count($entry);
					$counter = 0;
					
					foreach ($entry as $key => $value) {
						$counter = $counter + 1;
						
						if($key == 'time'){
							$time = DateTime::createFromFormat('U', $value)->setTimezone(new DateTimeZone($previewTimeZone))->format('d.m.Y \a\t H:i'); //Convert to datetime object							
							echo $time . ' - ';
						}
						else{
							echo $key . ' = ' . $value;
						}
						
						
						if( $counter != $elementCount && $key != 'time'){
							echo ' | ';
						}
						
					}
					
					echo "<br>";
				}
			}
		?>
	</body>
</html>