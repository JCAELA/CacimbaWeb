<?php
	include 'init.php';

	function clean($k){ return htmlspecialchars($_POST[$k] ?? '', ENT_QUOTES); }

	$request = $_POST['request'] ?? '';

	// table => [name column, extra text columns => POST keys, geometry POST key]
	$configs = [
		'nascentes' => ['table'=>'nascentes_02', 'name'=>'nome_nova_nascente', 'geom'=>'nascente_geometry',
			'cols'=>['tipo'=>'tipo_nascente','est_conserv'=>'estado_nascente','qual_agua'=>'qual_agua','uso'=>'uso']],
		'rios' => ['table'=>'waterlines', 'name'=>'nome_novo_rio', 'geom'=>'rio_geometry',
			'cols'=>['tipo'=>'tipo_rio','est_conserv'=>'estado_rio','qual_agua'=>'qual_agua_rio','uso'=>'uso_rio']],
		'lagos' => ['table'=>'polygons', 'name'=>'nome_novo_lago', 'geom'=>'lago_geometry',
			'cols'=>['origem'=>'origem_novo_lago','tipo'=>'tipo_lago','est_conserv'=>'estado_lago','qual_agua'=>'qual_agua_lago','uso'=>'uso_lago']],
	];

	if (!isset($configs[$request])) {
		http_response_code(400);
		die("ERROR invalid request");
	}
	$c = $configs[$request];

	try{
		$nome = clean($c['name']);
		$geomJson = $_POST[$c['geom']] ?? '';

		$stmt = $pdo->prepare("SELECT 1 FROM {$c['table']} WHERE nome = :n");
		$stmt->execute([':n' => $nome]);
		if ($stmt->rowCount() > 0) {
			echo "ERROR: O nome já existe.<br> Por favor escolha um novo nome";
			exit;
		}

		$columns = array_merge(['nome'], array_keys($c['cols']), ['geom']);
		$params  = [':nome' => $nome];
		$ph      = [':nome'];
		foreach ($c['cols'] as $col => $key) {
			$params[":$col"] = clean($key);
			$ph[] = ":$col";
		}
		$params[':geom'] = $geomJson;
		$ph[] = 'ST_SetSRID(ST_GeomFromGeoJSON(:geom),4326)';

		$sql = "INSERT INTO {$c['table']} (".implode(',', $columns).") VALUES (".implode(',', $ph).")";
		$pdo->prepare($sql)->execute($params);
	} catch(PDOException $e) {
		error_log($e->getMessage());
		echo "ERROR: não foi possível guardar os dados";
	}
?>
