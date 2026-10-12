<?php
	include 'init.php';

	$table = $_POST['table'] ?? '';
	$field = $_POST['field'] ?? '';
	$value = htmlspecialchars($_POST['value'] ?? '', ENT_QUOTES);

	if (!in_array($table, ALLOWED_TABLES, true) || !in_array($field, ALLOWED_FIELDS, true)) {
		http_response_code(400);
		die("ERROR invalid request");
	}

	try{
		$stmt = $pdo->prepare("SELECT *, ST_AsGeoJSON(geom) AS geojson FROM {$table} WHERE {$field} = :v");
		$stmt->execute([':v' => $value]);
		$features = [];
		foreach($stmt as $row) {
			unset($row['geom']);
			$geometry = json_decode($row['geojson']);
			unset($row['geojson']);
			$features[] = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
		}
		echo json_encode(["type" => "FeatureCollection", "features" => $features]);
	} catch(PDOException $e) {
		error_log($e->getMessage());
		echo "ERROR query failed";
	}
?>
