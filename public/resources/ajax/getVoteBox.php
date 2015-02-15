<?php

extract($_REQUEST);
extract($_SESSION);

$db = new SqlConnection();

$db->query("
	SELECT voteTypeID, Posts.userID AS posterID, Posts.postScore FROM Posts
	LEFT JOIN Votes ON Posts.postID = Votes.voteID AND Votes.userID = ${userID}
	WHERE Posts.postID = ${postID}
");
extract($db->fetchArray());

$vote = "none";
if(!empty($voteTypeID)) {
	if($voteTypeID == 1) {
		$vote = "up";
	} else {
		$vote = "down";
	}
}

if($posterID != $userID) {
	if($vote == "up") {
	    echo "<a class='up-arrow active'><i class='fa fa-angle-up'></i></a>";
	} else {
	    echo "<a class='up-arrow'><i class='fa fa-angle-up'></i></a>";
	}

	echo "
	    <span class='rep'>${postScore}</span>
	";

	if($vote == "down") {
	    echo "<a class='down-arrow active'><i class='fa fa-angle-down'></i></a>";
	} else {
	    echo "<a class='down-arrow'><i class='fa fa-angle-down'></i></a>";
	}
} else {
	echo "
		<span class='rep self'>${postScore}</span>
	";
}