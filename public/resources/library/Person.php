<?php

class Person {
	private $connection;
	private $variables;

	function __construct($userID) {
		$this->connection = new SqlConnection();

		$this->connection->query("
            SELECT *, Users.userID AS userID
            FROM Users
	        LEFT JOIN Students USING (userID)
	        LEFT JOIN Teachers USING (userID)
	        LEFT JOIN Admins USING (userID)
	        LEFT JOIN Thumbnails USING (thumbnailID)
            WHERE Users.userID = ${userID};
        ");

        $row = $this->connection->fetchArray();
        $this->variables = array(
        	"userID" => $row["userID"],
        	"firstName" => $row["firstName"],
		    "lastName" => $row["lastName"],
		    "grade" => $row["grade"],
		    "email" => $row["email"],
		    "about" => $row["about"],
		    "aboutFormatted" => $row["about"],
		    "name" => Acadefly::getName($row),
		    "title" => Acadefly::getTitle($row),
		    "homeURL" => $row["homeURL"],
		    "thumbnail" => Acadefly::getThumbnail($row, "large")
        );        
	}

	public function renderPublicProfile () {
        $this->prepareVariables();

        $layout = new MainLayout("public-profile.php", $this->variables);
        $layout->render();
	}

	public function renderProfile () {
		$this->prepareVariables();

	  	$layout = new MainLayout("profile.php", $this->variables);
	    $layout->render();
	}

	public function renderEditProfile () {
		$layout = new MainLayout("edit-profile.php", $this->variables);
    	$layout->render();
	}

	private function prepareVariables () {
		Format::htmlspecialArray($this->variables);
	    $this->variables["aboutFormatted"] = Format::paragraphs($this->variables["about"]);

	    $homeURL = $this->variables["homeURL"];
	    $this->variables["externalLink"] =
        $homeURL? "<a href='${homeURL}' class='btn btn-default link'><i class='fa fa-external-link'></i></a>" : "";
	}
}

?>