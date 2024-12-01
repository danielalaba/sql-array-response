<?php

require_once 'dbConfig.php';

function insertAnActivityLog($pdo, $operation, $username) {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    }

	$sql = "INSERT INTO activity_logs (operation, username, date_added) VALUES(?,?, NOW())";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$operation, $username]);

	if ($executeQuery) {
		return true;
	}

}

function getAllActivityLogs($pdo) {
	$sql = "SELECT * FROM activity_logs
			ORDER BY date_added DESC";
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute()) {
		return $stmt->fetchAll();
	}
}

function insertNewAdmin($pdo, $username, $password) {

	$checkUserSql = "SELECT * FROM users WHERE username = ?";
	$checkUserSqlStmt = $pdo->prepare($checkUserSql);
	$checkUserSqlStmt->execute([$username]);

	if ($checkUserSqlStmt->rowCount() == 0) {

		$sql = "INSERT INTO users (username,password) VALUES(?,?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username, $password]);

		if ($executeQuery) {
			$_SESSION['message'] = "User successfully inserted";
			return true;
		}

		else {
			$_SESSION['message'] = "An error occured from the query";
		}

	}
	else {
		$_SESSION['message'] = "User already exists";
	}


}

function loginUser($pdo, $username, $password) {
	$sql = "SELECT * FROM users WHERE username=?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username]);

	if ($stmt->rowCount() == 1) {
		$userInfoRow = $stmt->fetch();
		$usernameFromDB = $userInfoRow['username'];
		$passwordFromDB = $userInfoRow['password'];

		if ($password == $passwordFromDB) {
			$_SESSION['username'] = $usernameFromDB;
			$_SESSION['message'] = "Login successful!";
			return true;
		}

		else {
			$_SESSION['message'] = "Password is invalid, but user exists";
		}
	}


	if ($stmt->rowCount() == 0) {
		$_SESSION['message'] = "Username doesn't exist from the database. You may consider registration first";
	}

}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM pilot
			ORDER BY first_name ASC";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getUserByID($pdo, $id) {
	$sql = "SELECT * from pilot WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function searchForAUser($pdo, $searchQuery) {

	$sql = "SELECT * FROM pilot WHERE
			CONCAT(first_name, last_name, rank, successful_flights, aircraft_assigned)
			LIKE ?";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute(["%".$searchQuery."%"]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}


function insertNewUser ($pdo, $first_name, $last_name, $rank, $successful_flights, $aircraft_assigned, $username) {
    $response = array();

    // Insert the new pilot into the pilot table
    $sql = "INSERT INTO pilot (first_name, last_name, rank, successful_flights, aircraft_assigned) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $insertPilot = $stmt->execute([$first_name, $last_name, $rank, $successful_flights, $aircraft_assigned]);

        if ($insertPilot) {
            // Insert the activity log for the newly created pilot
            $operation = "Created new pilot: " . $first_name . " " . $last_name;
            $insertAnActivityLog = insertAnActivityLog($pdo, $operation, $username);

            if ($insertAnActivityLog) {
                $response = array(
                    "status" => "200",
                    "message" => "Pilot added and activity logged successfully!"
                );
            } else {
                $response = array(
                    "status" => "400",
                    "message" => "Insertion of activity log failed!"
                );
            }
        } else {
            $response = array(
                "status" => "400",
                "message" => "Insertion of pilot data failed!"
            );
        }
    } catch (PDOException $e) {
        // Log the error message for debugging
        error_log("Error inserting new pilot: " . $e->getMessage());
        $response = array(
            "status" => "500",
            "message" => "An error occurred while inserting the pilot: " . $e->getMessage()
        );
    }

    return $response;
}


function editUser($pdo, $first_name, $last_name, $rank,
$successful_flights, $aircraft_assigned, $user_id) {

	$sql = "UPDATE pilot
			SET first_name = ?,
				last_name = ?,
				rank = ?,
				successful_flights = ?,
				aircraft_assigned = ?,
			WHERE user_id = ?";

	$stmt = $pdo->prepare($sql);
	// Pass all 6 parameters (including the user_id)
	$executeQuery = $stmt->execute([$first_name, $last_name, $rank,
		$successful_flights, $aircraft_assigned, $user_id]);

	if ($executeQuery) {
		return true;
	}

	return false; // Explicit return for failure
}



function deleteUser($pdo, $user_id) {
	$sql = "DELETE FROM pilot
			WHERE user_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$user_id]);

	if ($executeQuery) {
        // Log the deletion into the activity_logs table
        insertAnActivityLog($pdo, 'Deleted pilot (ID: ' . $user_id . ')', $username);
        return true;
    }
    return false;
}
?>
