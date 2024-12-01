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
	<?php $getUserByID = getUserByID($pdo, $_GET['id']); ?>
	<h1>Edit the user!</h1>
	<form action="core/handleForms.php?user_id=<?php echo $_GET['id']; ?>" method="POST">
		<p>
			<label for="first_name">First Name</label>
			<input type="text" name="first_name" value="<?php echo $getUserByID['first_name']; ?>">
		</p>
		<p>
			<label for="last_name">Last Name</label>
			<input type="text" name="last_name" value="<?php echo $getUserByID['last_name']; ?>">
		</p>
		<p>
			<label for="rank">Rank</label>
			<input type="text" name="rank" value="<?php echo $getUserByID['rank']; ?>">
		</p>
		<p>
			<label for="successful_flights">Successful Flights</label>
			<input type="number" name="successful_flights" value="<?php echo $getUserByID['successful_flights']; ?>">
		</p>
		<p>
			<label for="aircraft_assigned">Aircraft Assigned</label>
			<input type="number" name="aircraft_assigned" value="<?php echo $getUserByID['aircraft_assigned']; ?>">
		</p>
        <input type="submit" value="Save" name="editUserBtn">
	</form>
</body>
</html>
