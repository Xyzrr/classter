<?php

$db = new SqlConnection();

?>

<div class="container">
	<div class="profile-panel">
		<table class="table">
			<thead>
				<tr>
					<th>Block</th>
					<th>Start Time</th>
					<th>End Time</th>

					<?php
					$db->query(
						"SELECT *
						FROM BlockDays
						");
					while($row = $db->fetchArray()) {
						extract($row);
						echo "<th>${blockDayName}</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
			<?php
			$db->query(
				"SELECT *
				FROM DayScheduleBlocks
				WHERE dayScheduleID = 1
				ORDER BY startTime
				");
			while($row = $db->fetchArray()) {
				extract($row);
				echo "
					<tr>
						<td>${blockName}</td>
						<td>${startTime}</td>
						<td>${endTime}</td>
				";

				$db2 = new SqlConnection();
				$db2->query(
					"SELECT * FROM blockPeriods
					WHERE dayScheduleBlockID = ${dayScheduleBlockID}
					");
				while($row2 = $db2->fetchArray()) {
					extract($row2);
					echo "<td>${period}</td>";
				}
				echo "</tr>";
			}
			?>
			</tbody>
		</table>
	</div>
</div>