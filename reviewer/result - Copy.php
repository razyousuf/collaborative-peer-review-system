<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session only if it hasn't been started yet
}	
include 'db_connect.php';

if (isset($_SESSION['login_id'])){ //&& $_SESSION['user_type'] == 3) {
    $reviewer_id = $_SESSION['login_id']; // Assuming user_id is set at login
    echo "The logged-in reviewer's ID is: " . $reviewer_id;
} else {
    echo 'Error: You must be logged in as a reviewer to access this page.';
}

$paper_id = isset($_GET['paper_id']) ? (int)$_GET['paper_id'] : 0;

//_______________________________________________________________ For the Overall Average Grade (A, B, C..) ___________________________________
// Function to map numeric grades to letter grades
function numeric_to_letter_grade($average) {
    if ($average >= 4) return 'A';
    if ($average >= 3) return 'B';
    if ($average >= 2) return 'C';
    if ($average >= 1) return 'D';
    return 'F'; // Anything below 21 is an 'F'
}

// Fetch reviewer_id from the users table where user_type is 3 (reviewer)

?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-body">
            <table class="table tabe-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Grading Scheme</th>
                        <th>Give Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT
                        criteria_list.c_id,
                        criteria_list.name,
                        criteria_list.description,
                        criteria_list.grading_scheme
                    FROM
                        criteria_list
                    ORDER BY
                        criteria_list.c_id ASC");

                    while ($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td style="font-size: 14px;"><?php echo ucwords($row['name']) ?></td>
                        <td style="font-size: 14px;"><?php echo $row['description'] ?></td>
                        <td style="font-size: 14px;"><?php echo nl2br(htmlspecialchars($row['grading_scheme'])) ?></td>
                        <td class="text-center">
                            <!-- Example of correctly linked label and select -->
                            <select id="grade_<?php echo $row['c_id']; ?>" class="form-control grade-select" data-id="<?php echo $row['c_id'] ?>">
                                <option value="">Select Grade:</option>
                                    <option value="5">A (81-100)</option>
									<option value="4">B (61-80)</option>
									<option value="3">C (41-60)</option>
									<option value="2">D (21-40)</option>
									<option value="1">F (0-20)</option>
                                
                            </select>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-header">
        <div class="card-tools">
            <button class="btn btn-block btn-sm btn-default btn-flat border-primary save-grades"><i class="fa fa-save"></i> Save Grades</button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var reviewer_id = <?php echo json_encode($reviewer_id); ?>;
        
        $('.save-grades').click(function() {
            var grades = [];
            $('.grade-select').each(function() {
                var c_id = $(this).data('id');
                var grade = $(this).val();
                if (grade != '') {
                    grades.push({ c_id: c_id, grade: grade });
                }
            });
            if (grades.length > 0) {
                $.ajax({
                    url: 'ajax.php?action=save_grades',
                    method: 'POST',
                    data: { reviewer_id: reviewer_id, grades: grades, paper_id: 16 },
                    success: function(resp) {
                        if(resp.trim() === 'success') {
                            alert('Grades saved successfully.');
                            // You can also reload the page to see the changes or navigate to another page
                            location.reload();
                        } else {
                            // Display an error message
                            alert('Failed to save grades: ' + resp);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            } else {
                alert('Please select at least one grade.');
            }
        });
    });
</script>


<?php

		$paper_id = 16;
		// $reviewer_id = 16;
		// Prepare the SQL statement to fetch the average grade for a specific reviewer and paper
		$stmt = $conn->prepare("
			SELECT 
				p.title as paper_title,
				u.firstname, 
				u.lastname, 
				AVG(g.grade) AS average_grade
			FROM 
				grades g 
			INNER JOIN 
				users u ON g.reviewer_id = u.id 
			INNER JOIN
				papers p ON g.paper_id = p.paper_id
			WHERE 
				g.paper_id = ? AND g.reviewer_id = ?
			GROUP BY 
				g.reviewer_id, p.title
		");

		if (!$stmt) {
			die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
		}

		$stmt->bind_param("ii", $paper_id, $reviewer_id);
		$stmt->execute();
		$stmt->bind_result($paper_title, $firstname, $lastname, $average_grade);
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			echo "<h4>Average Grade by you:{$firstname} </h4>";
			echo "<table class='table table-bordered'>";
			echo "<tr><th>Paper ID</th><th>Paper Title</th><th>Reviewer Name</th><th>Average Grade</th></tr>";
			while ($stmt->fetch()) {
				echo "<tr><td>" . htmlspecialchars($paper_id) . "</td>";
				echo "<td>" . htmlspecialchars($paper_title) . "</td>";
				echo "<td>" . htmlspecialchars($firstname) . " " . htmlspecialchars($lastname) . "</td>";
				echo "<td>" . numeric_to_letter_grade($average_grade) . "</td></tr>";
			}
			echo "</table>";
		} else {
			echo "No grades found for the specified reviewer and paper ID.";
		}
		$stmt->close();
?>


<?php

	// Now add the new logic to retrieve and display average grades at the end of the page

	// Retrieve average grades for each paper
	$avg_sql = "SELECT paper_id, title, average_grade FROM average_grades";
	$avg_result = $conn->query($avg_sql);

	echo "<h4>Updates of Average Grade:</h4>";
	if ($avg_result->num_rows > 0) {
		echo "<table class='table table-bordered'>";
		echo "<tr><th>Paper ID</th><th>Paper Title</th><th>Average Grade</th></tr>";
		while ($avg_row = $avg_result->fetch_assoc()) {
			$letter_grade = numeric_to_letter_grade($avg_row['average_grade']);
			echo "<tr>";
			echo "<td>" . $avg_row['paper_id'] . "</td>";
			echo "<td>" . $avg_row['title'] . "</td>";
			echo "<td>" . $letter_grade . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "No average grades found.";
	}

?>


