<?php    

Acadefly::verifyUserIsLoggedIn();
$userID = $_SESSION["userID"];

if(!empty($_GET["id"])) {
    $targetID = $_GET["id"];
    if($userID != $targetID) {
        $viewCounter = new ViewCounter();
        $viewCounter->addProfileView($targetID);
    }
} else {
    $targetID = $userID;
}

$db = new SqlConnection();
$db->query("
    SELECT *, Users.userID AS targetID
    FROM Users
    LEFT JOIN Students USING (userID)
    LEFT JOIN Teachers USING (userID)
    LEFT JOIN Admins USING (userID)
    LEFT JOIN Thumbnails USING (thumbnailID)
    WHERE Users.userID = ${targetID};
");

$row = $db->fetchArray();
$row["name"] = htmlspecialchars(Acadefly::getName($row));
$row["thumbnail"] = Acadefly::getThumbnail($row, "large");
$row["scripts"] = array("validate", "footable", "charts", "select", "schedule", "editable", "profile");
$layout = new MainLayout("profile.php", $row);
$layout->render();