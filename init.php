<?php
	ob_start();
	session_start();

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
		// "ERROR" prefix is what the frontend expects
		die("ERROR: database connection failed");
	}

	// Tables and columns the app is allowed to query
	const ALLOWED_TABLES = ['nascentes_02','waterlines','polygons'];
	const ALLOWED_FIELDS = ['nome'];
?>
