<?php
	ob_start();
	session_start();

	// Never print PHP errors as HTML (it breaks the JSON); log them instead
	ini_set('display_errors', '0');
	ini_set('log_errors', '1');
	ini_set('memory_limit', '512M');
	set_time_limit(60);

	// If PHP dies with a fatal error, return it as plain text starting with ERROR
	register_shutdown_function(function(){
		$e = error_get_last();
		if ($e && in_array($e['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
			while (ob_get_level()) { ob_end_clean(); }
			echo "ERROR fatal: ".$e['message'];
		}
	});

	// Credentials come from environment variables (set them in Railway -> Variables)
	$host = getenv('DB_HOST');
	$port = getenv('DB_PORT') ?: '5432';
	$name = getenv('DB_NAME');
	$user = getenv('DB_USER');
	$pass = getenv('DB_PASSWORD');

	try{
		$dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode=require";
		$opt = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false
		];
		$pdo = new PDO($dsn, $user, $pass, $opt);
	} catch (PDOException $e){
		error_log("DB connection failed: ".$e->getMessage());
		die("ERROR: database connection failed");
	}

	// Tables and columns the app is allowed to query
	const ALLOWED_TABLES = ['nascentes_02','waterlines','polygons'];
	const ALLOWED_FIELDS = ['nome'];
?>
