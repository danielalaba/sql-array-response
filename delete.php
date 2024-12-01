<?php require_once 'core/models.php'; ?>
<?php require_once 'core/dbConfig.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<h1>Are you sure you want to delete this user?</h1>
	<?php $getUserByID = getUserByID($pdo, $_GET['id']); ?>
	<div class="container" style="border-style: solid; border-color: red; background-color: #ffcbd1;height: 500px;">
    <p><strong>First Name:</strong> <?php echo htmlspecialchars($getUserByID['first_name']); ?></p>
					<p><strong>Last Name:</strong> <?php echo htmlspecialchars($getUserByID['last_name']); ?></p>
					<p><strong>Email:</strong> <?php echo htmlspecialchars($getUserByID['rank']); ?></p>
					<p><strong>Phone:</strong> <?php echo htmlspecialchars($getUserByID['successful_flights']); ?></p>
					<p><strong>Aircraft Assigned:</strong> <?php echo htmlspecialchars($getUserByID['aircraft_assigned']); ?></p>

		<div class="deleteBtn" style="float: right; margin-right: 10px;">
			<form action="core/handleForms.php?id=<?php echo $_GET['id']; ?>" method="POST">
				<input type="submit" name="deleteUserBtn" value="Delete" style="background-color: #f69697; border-style: solid;">
			</form>
		</div>

	</div>
</body>
</html>
