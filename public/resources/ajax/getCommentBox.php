<?php

extract($_REQUEST);
extract($_SESSION);

$db = new SqlConnection();
$db->query(
	"SELECT *, userID AS commenterID, Comments.creationDate AS commentCreationDate FROM Comments
	INNER JOIN Users USING (userID)
	WHERE postID = ${postID}
	");
while($row = $db->fetchArray()) {
	extract($row);
	$name = Acadefly::getName($row);
	$commentBody = stripslashes($commentBody);
	$relativeTime = Acadefly::timeToString($commentCreationDate);
	echo "
	<div class='comment-wrapper' data-id='${commentID}'>
		<div class='left'>
		</div>
		<div class='right'>
			<span class='comment-body'>${commentBody} - </span>
			<a href='" . HOME . "/profile/?id=${commenterID}' class='author'>${name}</a>
			<span class='date'>- ${relativeTime} ago</span>
	";
	if($userID == $commenterID) {
		echo "<a class='delete-button'><i>&times;</i></a>";
	}
	echo "
		</div>
	</div>
	";
}
echo "
<div class='comment-button-wrapper'>
	<a class='comment-button'>Add a comment</a>
</div>
<div class='comment-form'>
	<div class='form-group'>
		<textarea class='form-control comment-textarea'></textarea>
	</div>
	<button class='btn btn-default btn-sm cancel'>Cancel</button>
	<button class='btn btn-primary btn-sm post-comment'>Add Comment</button>
</div>
";