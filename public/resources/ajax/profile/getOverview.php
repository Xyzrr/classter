<?php

$db = new SqlConnection();
$targetID = (int) $_GET["targetID"];
$userID = $_SESSION["userID"];

$db->query(
    "SELECT about, grade, studentID, teacherID, adminID, profileViewCount
    FROM Users
    LEFT JOIN Students USING (userID)
    LEFT JOIN Teachers USING (userID)
    LEFT JOIN Admins USING (userID)
    WHERE Users.userID = ${targetID};
");

$row = $db->fetchArray();

extract($row);
$role = Acadefly::getRole($row);
$aboutFormatted = Format::paragraphs(stripslashes($about));

$db->query(
	"SELECT
	COUNT(CASE WHEN postTypeID = 1 THEN 1 ELSE NULL END) AS questionCount,
	COUNT(CASE WHEN postTypeID = 2 THEN 1 ELSE NULL END) AS answerCount
	FROM Posts
	WHERE userID = ${targetID}
	");
extract($db->fetchArray());

$db->query(
	"SELECT
	COUNT(commentID) AS commentCount
	FROM Comments
	WHERE userID = ${targetID}
	");
extract($db->fetchArray());
$db->query(
	"SELECT
	COUNT(CASE WHEN voteTypeID = 1 THEN 1 ELSE NULL END) AS upVoteCount,
	COUNT(CASE WHEN voteTypeID = 2 THEN 1 ELSE NULL END) AS downVoteCount
	FROM Votes
	WHERE userID = ${targetID}
	");
extract($db->fetchArray());

extract($conf["vote_values"]);
$db->query(
    "SELECT
    SUM(CASE WHEN voteTypeID = 1 AND postTypeID = 1 THEN ${question_up} ELSE 0 END +
        CASE WHEN voteTypeID = 2 AND postTypeID = 1 THEN ${question_down} ELSE 0 END) AS questionRep,
    SUM(CASE WHEN voteTypeID = 1 AND postTypeID = 2 THEN ${answer_up} ELSE 0 END +
        CASE WHEN voteTypeID = 2 AND postTypeID = 2 THEN ${answer_down} ELSE 0 END) AS answerRep,
    SUM(CASE WHEN voteTypeID = 1 AND postTypeID = 3 THEN ${review_up} ELSE 0 END +
        CASE WHEN voteTypeID = 2 AND postTypeID = 3 THEN ${review_down} ELSE 0 END) AS reviewRep
    FROM Votes
    LEFT JOIN Posts USING (postID)
    WHERE Posts.userID = ${targetID}
    ");

extract($db->fetchArray());
$reputation = $questionRep + $answerRep + $reviewRep;

if($targetID == $userID) {
?>
<div class="col-half">
    <div class="profile-panel" id="about">
        <div class="profile-panel-header">
            <h2>About</h2>
            <button class="edit-button btn btn-default" data-profile="toggle-edit">
                <i class="fa fa-pencil"></i>
            </button>
        </div>
        <div data-profile="read">
            <p id="about-read">
                <?= $aboutFormatted ?>
            </p>
        </div>
        <form data-profile="edit">
            <div class="form-group">
                <textarea class="form-control" name="about" rows="8"><?= stripslashes($about) ?></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-default" data-profile="cancel-edit">Cancel</button>
                <button class="btn btn-primary" data-profile="save-edit">Save</button>
            </div>
        </form>
    </div>
</div>
<div class="col-half">
    <div class="profile-panel" id="info">
        <div class="profile-panel-header">
            <h2>Info</h2>
            <button class="edit-button btn btn-default" data-profile="toggle-edit">
                <i class="fa fa-pencil"></i>
            </button>
        </div>
        <div data-profile="read">
            <dl class="dl-horizontal">
            	<dt>Role</dt>
            	<dd><?= $role ?></dd>
            	<dt>Grade</dt>
            	<dd id="grade-read"><?= $grade ?></dd>
            </dl>
        </div>
        <form data-profile="edit">
            <div class="form-group">
                <label for="grade">Grade</label>
                <select name="grade" class="form-control">
                    <?php
                    for($i = 9; $i <= 12; $i++) {
                        if($grade == $i) {
                            echo "<option value='${i}' selected>${i}</option>";
                        } else {
                            echo "<option value='${i}'>${i}</option>";                            
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-default cancel-button" data-profile="cancel-edit">Cancel</button>
                <button class="btn btn-primary save-button" data-profile="save-edit">Save</button>
            </div>
        </form>
    </div>
</div>
<?php } else { ?>
<div class="col-half">
    <div class="profile-panel" id="about">
        <div class="profile-panel-header">
            <h2>About</h2>
        </div>
        <p id="about-read">
            <?= $about ?>
        </p>
    </div>
</div>
<div class="col-half">
    <div class="profile-panel">
        <div class="profile-panel-header">
            <h2>Info</h2>
        </div>
        <dl class="dl-horizontal">
        	<dt>Role</dt>
        	<dd><?= $role ?></dd>
        	<dt>Grade</dt>
        	<dd><?= $grade ?></dd>
        </dl>
    </div>
</div>
<?php } ?>
<div class="col-half">
    <div class="profile-panel">
        <div class="profile-panel-header">
            <h2>Stats</h2>
        </div>
        <table class="profile-stats table">
        	<tr>
        		<td>
        			<div class="value"><?= $questionCount ?></div>
        			<label for="question count">Questions</label>
        		</td>
        		<td>
        			<div class="value"><?= $answerCount ?></div>
        			<label for="answer count">Answers</label>
        		</td>
        		<td>
        			<div class="value"><?= $commentCount ?></div>
        			<label for="comment count">Comments</label>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div class="value"><?= $upVoteCount ?></div>
        			<label for="upvotes">Upvotes</label>
        		</td>
        		<td>
        			<div class="value"><?= $downVoteCount ?></div>
        			<label for="downvotes">Downvotes</label>
        		</td>
                <td>
                    <div class="value"><?= $profileViewCount ?></div>
                    <label for="profile views">Profile Views</label>
                </td>
        	</tr>
        </table>
    </div>
</div>
<div class="col-half">
    <div class="profile-panel" id="rep-panel">
        <div class="profile-panel-header">
            <h2>Reputation</h2>
        </div>
        <input type="hidden" value="<?= $questionRep ?>" id="question-rep">
        <input type="hidden" value="<?= $answerRep ?>" id="answer-rep">
        <input type="hidden" value="<?= $reviewRep ?>" id="review-rep">
        <div class="rep-total">
            <div class="value">
                <?= number_format($reputation) ?>
            </div>
            <label for="reputation">
                Total
            </label>
        </div>
        <canvas id="rep-pie-chart" width="200" height="200">
        </canvas>
    </div>
</div>