<?php

class Login {
	public $feedback = array("success" => false);

	public function doLogin() {
		$this->loginWithPostData();

		echo json_encode($this->feedback);
	}

	private function loginWithPostData() {
		if($this->checkLoginFormDataNotEmpty()) {
			$this->checkPasswordCorrectnessAndLogin();
		}
	}

	private function checkLoginFormDataNotEmpty() {
        if (!empty($_POST['user_email']) && !empty($_POST['user_password'])) {
            return true;
        } elseif (empty($_POST['user_email'])) {
            $this->feedback["email"] = "Please fill in email field.";
        } elseif (empty($_POST['user_password'])) {
            $this->feedback["password"] = "Please fill in password field.";
        }
        // default return
        return false;
	}

	private function checkPasswordCorrectnessAndLogin() {
		$db = new SqlConnection();
		$user_email = $db->escapeString($_POST["user_email"]);
		$db->query(
		"SELECT userID, email, password, firstName, lastName
		FROM Users
		WHERE email = '${user_email}'
		LIMIT 1
		");
		if($db->numRows() < 1) {
            $this->feedback["email"] = "Invalid email.";
		} else {
			$row = $db->fetchArray();
			if(md5($_POST["user_password"]) == $row["password"]) {
				$_SESSION["userID"] = $row["userID"];
				$_SESSION["userName"] = Acadefly::getName($row);
				$_SESSION["user-is-logged-in"] = true;
				$this->feedback["success"] = true;

				$token = $db->escapeString($_SESSION["userID"] . "a" . rand(100000000000000000000, 999999999999999999999));
				$db->query(
					"INSERT INTO LoginTokens SET
					token = '${token}',
					creationDate = NOW()
				");

				setcookie("login_token", $token, time() + 60*60*24*365, "/");
			} else {
				$this->feedback["password"] = "Invalid password.";
			}
		}

		return false;
	}
}

$login = new Login();
$login->doLogin();