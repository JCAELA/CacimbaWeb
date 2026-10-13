<?php
	include 'init.php';

	$table = $_POST['table'] ?? '';
	if (!in_array($table, ALLOWED_TABLES, true)) {
		http_response_code(400);
		die("ERROR invalid table");
	}

	try{
		$result = $pdo->query("SELECT *, ST_AsGeoJSON(geom) AS geojson FROM {$table}");
		$features = [];
		foreach($result as $row) {
			unset($row['geom']);
			$geometry = json_decode($row['geojson']);
			unset($row['geojson']);
			$features[] = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
		}
		$out = json_encode(["type" => "FeatureCollection", "features" => $features],
			JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR);
		if ($out === false) {
			echo "ERROR json: ".json_last_error_msg();
		} else {
			echo $out;
		}
	} catch(Throwable $e) {
		error_log($e->getMessage());
		echo "ERROR ".$e->getMessage();
	}
?>
