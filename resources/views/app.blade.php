<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laravel with Inertia</title>
		@vite
	</head>
	<body class="antialiased bg-gray-900">
		<strong>Database Connected: </strong>
<?php
    try {
        \DB::connection('mysql')->getPDO();
        echo \DB::connection('mysql')->getDatabaseName();
        } catch (\Exception $e) {
        echo $e;
    }
?>
	</body>
</html>
