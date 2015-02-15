<?php

class Register {
	public $feedback = array("success" => false);

    public function doRegistration()
    {
        if ($this->checkRegistrationData()) {
            $this->createNewUser();
        }
        
        echo json_encode($this->feedback);
    }

    private function checkRegistrationData() {

        if (strlen($_POST['user-password']) < 6) {
            $this->feedback["password"] = "Password has a minimum length of 6 characters";
            return false;
        } elseif (empty($_POST['user-email'])) {
            $this->feedback["email"] = "Email cannot be empty";
            return false;
        } elseif (strlen($_POST['user-email']) > 64) {
            $this->feedback["email"] = "Email cannot be longer than 64 characters";
            return false;
        } elseif (!filter_var($_POST['user-email'], FILTER_VALIDATE_EMAIL)) {
            $this->feedback["email"] = "Your email address is not in a valid email format";
            return false;
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user-first-name'])) {
            $this->feedback["firstName"] = "First name must be alphanumeric, 2 to 64 characters";
            return false;
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user-last-name'])) {
            $this->feedback["lastName"] = "Last name must be alphanumeric, 2 to 64 characters";
            return false;
        }
        return true;
    }

    private function createNewUser() {
		$db = new SqlConnection();

		$firstName = $db->escapeString($_POST['user-first-name']);
		$lastName = $db->escapeString($_POST['user-last-name']);
		$password = md5($_POST['user-password']);
		$email = $db->escapeString($_POST['user-email']);

		$db->query(
		"SELECT * 
		FROM Users 
		WHERE email = '${email}';
		");

		if($db->numRows() == 1) {
			$this->feedback["email"] = "That email's already registered.";
		} else {
			$db->query(
			"INSERT INTO Users SET
			firstName = '${firstName}',
			lastName = '${lastName}',
			password = '${password}',
			email = '${email}',
            creationDate = NOW();
			");

			$lastID = $db->lastID();

			$db->query(
			"INSERT INTO Students SET
			userID = '${lastID}';
			");

			$_SESSION["userID"] = $lastID;
			$_SESSION["user-is-logged-in"] = true;
            $row = array("firstName" => $firstName, "lastName" => $lastName);
            $_SESSION["userName"] = Acadefly::getName($row);
			$this->feedback["success"] = true;
		}
    }
}

$register = new Register();
$register->doRegistration();