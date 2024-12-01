<?php require_once 'core/handleForms.php'; ?>
<?php require_once 'core/models.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Insert New Pilot</h1>
	<form method="POST" action="core/handleForms.php">
		<p>
			<label for="first_name">First Name</label>
			<input type="text" name="first_name">
		</p>
		<p>
			<label for="last_name">Last Name</label>
			<input type="text" name="last_name">
		</p>
		<p>
			<label for="rank">Rank</label>
			<input type="text" name="rank">
		</p>
		<p>
			<label for="successful_flights">Successful Flights</label>
			<input type="number" name="successful_flights">
		</p>
		<p>
			<label for="aircraft_assigned">Aircraft Assigned</label>
			<input type="number" name="aircraft_assigned">
            <input type="submit" name="insertUserBtn" value="Insert User">
		</p>
	</form>
</body>
</html>