<?php

require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['registerUserBtn'])) {

	$username = $_POST['username'];
	$password = sha1($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$insertQuery = insertNewadmin($pdo, $username, $password);

		if ($insertQuery) {
			header("Location: ../login.php");
		}
		else {
			header("Location: ../register.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure the input fields
		are not empty for registration!";

		header("Location: ../login.php");
	}

}




if (isset($_POST['loginUserBtn'])) {

	$username = $_POST['username'];
	$password = sha1($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = loginUser($pdo, $username, $password);

		if ($loginQuery) {
			header("Location: ../index.php");
		}
		else {
			header("Location: ../login.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure the input fields
		are not empty for the login!";
		header("Location: ../login.php");
	}

}



if (isset($_GET['logoutAUser'])) {
	unset($_SESSION['username']);
	header('Location: ../login.php');
}

if (isset($_POST['insertUserBtn'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $rank = $_POST['rank'];
    $successfulFlights = $_POST['successful_flights'];
    $aircraftAssigned = $_POST['aircraft_assigned'];
    try {
        $stmt = $pdo->prepare("INSERT INTO pilot (first_name, last_name, rank, successful_flights, aircraft_assigned)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $rank, $successfulFlights, $aircraftAssigned]);
        $_SESSION['message'] = "Pilot added successfully!";
    } catch(PDOException $e) {
        $_SESSION['message'] = "Error adding pilot: " . $e->getMessage();
    }

    header("Location: ../index.php");
    exit();
}


if (isset($_POST['editUserBtn'])) {
    $user_id = $_GET['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $rank = $_POST['rank'];
    $successful_flights = $_POST['successful_flights'];
    $aircraft_assigned = $_POST['aircraft_assigned'];

    try {
        // Prepare the SQL query for updating the pilot
        $stmt = $pdo->prepare("UPDATE pilot SET first_name = ?, last_name = ?, rank = ?, successful_flights = ?, aircraft_assigned = ? WHERE user_id = ?");
        $executeQuery = $stmt->execute([$first_name, $last_name, $rank, $successful_flights, $aircraft_assigned, $user_id]);

        if ($executeQuery) {
            // Log the activity: 'Updated pilot'
            insertAnActivityLog($pdo, 'Updated pilot (ID: ' . $user_id . ')', $updated_by);
            $_SESSION['message'] = "Pilot updated successfully!";
        } else {
            $_SESSION['message'] = "No changes made to the pilot.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error updating pilot: " . $e->getMessage();
    }

    header("Location: ../index.php");
    exit();
}


if (isset($_POST['deleteUserBtn'])) {
	$deleteUser = deleteUser($pdo,$_GET['id']);

	if ($deleteUser) {
		$_SESSION['message'] = "Successfully deleted!";
		header("Location: ../index.php");
	}
}

if (isset($_GET['searchBtn'])) {
	$searchForAUser = searchForAUser($pdo, $_GET['searchInput']);
	foreach ($searchForAUser as $row) {
		echo "<tr>
				<td>{$row['first_name']}</td>
				<td>{$row['last_name']}</td>
				<td>{$row['rank']}</td>
				<td>{$row['successful_flights']}</td>
				<td>{$row['aircraft_assigned']}</td>

			  </tr>";
	}
}

?>
