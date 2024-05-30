<?php 
include('db_connect.php'); 
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
$author_id = $_SESSION['login_id'];
?>

<div class="container-fluid">
    <div class="header">
        <h5>Welcome <?php echo $_SESSION['login_name'] . '! '; echo " Your User ID is: " . $_SESSION['login_id'];?></h5>
    </div>
    <div class="row">
	
		<!-- Groups I am member of (INCOMPLETE!!!) -->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=group_management" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
							// Prepare the statement to count papers shared with the logged-in user
							$stmt = $conn->prepare("
								SELECT COUNT(*) FROM group_members WHERE user_id = ?
 
							");
							// Bind the logged-in user's ID to the parameter in the prepared statement
							$stmt->bind_param("i", $_SESSION['login_id']);
							// Execute the statement
							$stmt->execute();
							// Fetch the count result
							$result = $stmt->get_result()->fetch_row();
							// Echo the count to display it on the page
							echo $result[0];
						?></h3>
						<p>My Groups</p>
				</div>
				<div class="icon">
                    <i class="fa fa-user-friends"></i>
                </div>
			</a>
		</div>
        <!-- My Shared Papers -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="./index.php?page=thesis" class="small-box bg-light shadow-sm border">
                <div class="inner">
					<?php
						if ($stmt = $conn->prepare("SELECT COUNT(*) FROM papers WHERE author_id = ?")) {
							$stmt->bind_param("i", $_SESSION['login_id']);
							$stmt->execute();
							$result = $stmt->get_result(); // get the mysqli result
							$count = $result->fetch_array()[0]; // fetch the count
							echo "<h3>$count</h3>";
						} else {
							echo "<h3>Error</h3>"; // Error handling
						}
						?>
                    <p>My papers</p>
                </div>
					<div class="icon">
						       <i class="fas fa-file-archive"></i>
					</div>
            </a>
        </div>
		<!-- Papers I have shared with Others (INCOMPLETE!!)-->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=my_shared_papers" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						// Prepare the query to count papers belonging to the author that have been reviewed
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT p.paper_id) as count 
							FROM papers p
							INNER JOIN paper_shares ps ON p.paper_id = ps.paper_id
							WHERE p.author_id = ?
						");
						// Bind the author's ID and execute
						$stmt->bind_param("i", $_SESSION['login_id']);
						$stmt->execute();
						$result = $stmt->get_result()->fetch_row();
						echo $result[0]; // Display the count
					?></h3>
					<p>papers I have Shared so far</p>

				</div>
				<div class="icon">
					<i class="fas fa-file-export"></i>
				</div>
			</a>
		</div>
		<!-- Papers Shared with Me -->
        <div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=to_review" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						// Prepare the statement to count papers shared with the logged-in user
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT ps.paper_id) AS shared_papers_count
							FROM paper_shares ps
							WHERE ps.user_id = ? OR ps.group_id IN (
								SELECT group_id FROM group_members WHERE user_id = ?
							)
						");
						// Bind the logged-in user's ID twice to the query parameters
						$stmt->bind_param("ii", $_SESSION['login_id'], $_SESSION['login_id']);
						// Execute the statement
						$stmt->execute();
						// Fetch the count result
						$result = $stmt->get_result()->fetch_assoc();
						// Echo the count to display it on the page
						echo $result['shared_papers_count'];
					?></h3>
					<p>Papers Shared With Me</p>
				</div>
				<div class="icon">
					<i class="fas fa-folder-open"></i>
					<i class="fas fa-arrow-down"></i>
				</div>
			</a>
		</div>
		<!-- Papers Reviewed by Others -->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=my_shared_papers" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT ps.user_id) AS reviewed_count
							FROM paper_shares ps
							JOIN papers p ON ps.paper_id = p.paper_id
							WHERE p.author_id = ? AND ps.review_status = 'Reviewed'
						");
						$stmt->bind_param("i", $_SESSION['login_id']);  // Bind the author_id
						$stmt->execute();
						$result = $stmt->get_result()->fetch_assoc(); // Fetch the result
						echo $result['reviewed_count']; // Display the count of reviewed papers
					?></h3>
					<p>Papers recieved the mark "Reviewed" so far</p>
				</div>
				<div class="icon">
					<i class="fas fa-check-circle"></i>
				</div>
			</a>
		</div>
		<!-- Papers Reviewed by Others -->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=my_shared_papers" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT ps.user_id) AS reviewer_count
							FROM paper_shares ps
							JOIN papers p ON ps.paper_id = p.paper_id
							WHERE p.author_id = ? AND ps.review_status = 'Reviewed'
						");
						$stmt->bind_param("i", $_SESSION['login_id']); // Bind the logged-in author's ID
						$stmt->execute();
						$result = $stmt->get_result()->fetch_assoc(); // Fetch the result
						echo $result['reviewer_count']; // Display the count of reviewers
					?></h3>
					<p>Reviewers Reviewed My Papers Individually</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
			</a>
		</div>
		<!-- Papers Rejeced by Others -->
				<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=my_shared_papers" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT ps.user_id) AS reviewer_count
							FROM paper_shares ps
							JOIN papers p ON ps.paper_id = p.paper_id
							WHERE p.author_id = ? AND ps.review_status = 'Rejected'
						");
						$stmt->bind_param("i", $_SESSION['login_id']); // Bind the logged-in author's ID
						$stmt->execute();
						$result = $stmt->get_result()->fetch_assoc(); // Fetch the result
						echo $result['reviewer_count']; // Display the count of reviewers
					?></h3>
					<p>Reviewers Rejected My Papers(Individually)</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
			</a>
		</div>
		<!-- Papers to Review -->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=to_review" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						// Prepare the statement to count papers shared with the logged-in user that haven't been reviewed yet
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT ps.paper_id) AS pending_review_count
							FROM paper_shares ps
							WHERE (ps.user_id = ? OR ps.group_id IN (
								SELECT group_id FROM group_members WHERE user_id = ?
							)) AND ps.review_status = 'In queue'
						");
						// Bind the logged-in user's ID twice to the query parameters
						$stmt->bind_param("ii", $_SESSION['login_id'], $_SESSION['login_id']);
						// Execute the statement
						$stmt->execute();
						// Fetch the count result
						$result = $stmt->get_result()->fetch_assoc();
						// Echo the count to display it on the page
						echo $result['pending_review_count'];
					?></h3>
					<p>Papers to Review (In Queue)</p>
				</div>
				<div class="icon">
					<i class="fa fa-list-alt"></i>
				</div>
			</a>
		</div>
       <!-- Papers I've Reviewed -->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=to_review" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT ps.paper_id) as count
							FROM paper_shares ps
							INNER JOIN papers p ON ps.paper_id = p.paper_id
							WHERE ps.user_id = ? AND ps.review_status = 'Reviewed'
						");
						$stmt->bind_param("i", $_SESSION['login_id']); // Assuming logged-in user ID stored in session as reviewer ID
						$stmt->execute();
						$result = $stmt->get_result()->fetch_row();
						echo $result[0]; // Display the count of papers reviewed
					?></h3>
					<p>Papers I've Reviewed Individually</p>
				</div>
				<div class="icon">
					<i class="fa fa-list-alt"></i>
				</div>
			</a>
		</div>

           <div class="col-12 col-sm-6 col-md-4">
              <div class="inner">
			  </br>
                <h3>My Grades Statistics</h3>
              </div>
              <div class="icon">
              </div>
            <!--</div> -->
          </div>
	</div>
     <!-- Review Statistics -->
    <div class="row">
        <div class="col-md-6">
            <select id="paperSelect" class="form-control mb-3">
                <option value="">Latest Paper Statistics</option>
                <?php
                $stmt = $conn->prepare("
										SELECT DISTINCT p.paper_id, p.title, u.firstname, u.lastname
										FROM papers p
										JOIN users u ON p.author_id = u.id
										JOIN paper_shares ps ON p.paper_id = ps.paper_id
										WHERE ps.review_status IN ('Reviewed') -- assuming 'Reviewed', mean the paper has been graded 
										AND p.author_id = ?
										ORDER BY p.paper_id DESC
									");
                $stmt->bind_param("i", $author_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $lastPaperId = '';
				while ($row = $result->fetch_assoc()) {
					$lastPaperId = $row['paper_id']; // Continuously update to the current paper's ID
					echo "<option value='{$row['paper_id']}'>{$row['title']}</option>";
				}
				?>
            </select>
            <canvas id="allReviewersChart"></canvas>
        </div>
        <div class="col-md-6">
            <select id="reviewerSelect" class="form-control mb-3">
                <option value="">Select a Specific Reviewer for statistics</option>
                <!-- Reviewers will be populated dynamically -->
            </select>
            <canvas id="specificReviewerChart"></canvas>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function initCharts(paper_id, specificReviewerId = null) {
        $.ajax({
            url: 'ajax.php',
            method: 'GET',
            data: { action: 'fetch_average_grades_by_paper', paper_id: paper_id },
            success: function(responseAll) {
                var dataAll = JSON.parse(responseAll);
                var labels = dataAll.map(d => d.criterion);
                var gradesAll = dataAll.map(d => d.average_grade);

                var ctxAll = document.getElementById('allReviewersChart').getContext('2d');
                new Chart(ctxAll, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average marks recieved for each criteria',
                            data: gradesAll,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1.5
                        }]
                    },
                    options: {
					scales: {
						y: {
							beginAtZero: true,
							min: 0,
							max: 5, // Maximum value
							stepSize: 0.5, // Step size
							ticks: {
								precision: 0 // Prevent decimal ticks caused by step size
							}
						}
					}
				}
			});

                if (specificReviewerId) {
                    fetchSpecificReviewerData(paper_id, specificReviewerId, labels);
                }
            }
        });
    }

    function fetchSpecificReviewerData(paper_id, reviewer_id, labels) {
        $.ajax({
            url: 'ajax.php',
            method: 'GET',
            data: { action: 'fetch_average_grades_by_paper_for_reviewer', paper_id: paper_id, reviewer_id: reviewer_id },
            success: function(responseSpecific) {
                var dataSpecific = JSON.parse(responseSpecific);
                var gradesSpecific = dataSpecific.map(d => d.average_grade);

                var ctxSpecific = document.getElementById('specificReviewerChart').getContext('2d');
                new Chart(ctxSpecific, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Marks by a pecific reviewer',
                            data: gradesSpecific,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1.5
                        }]
                    },
                    options: {
					scales: {
						y: {
							beginAtZero: true,
							min: 0,
							max: 5, // Maximum value
							stepSize: 0.5, // Step size
							ticks: {
								precision: 0 // Prevent decimal ticks caused by step size
							}
						}
					}
				}
			});
            }
        });
    }

    // Load the default chart for the first paper if available
    if ('<?php echo $lastPaperId; ?>' !== '') {
        initCharts('<?php echo $lastPaperId; ?>');
    }

    $('#paperSelect').change(function() {
        var selectedPaperId = $(this).val();
        initCharts(selectedPaperId);
    });

    $('#reviewerSelect').change(function() {
        var selectedReviewerId = $(this).val();
        var selectedPaperId = $('#paperSelect').val();
        if (selectedReviewerId) {
            initCharts(selectedPaperId, selectedReviewerId);
        }
    });

    // Populate dropdown with reviewers when a paper is selected
    $('#paperSelect').change(function() {
        var paper_id = $(this).val();
        if (paper_id) {
            $.ajax({
                url: 'ajax.php',
                method: 'GET',
                data: { action: 'fetch_reviewers', paper_id: paper_id },
                success: function(response) {
                    $('#reviewerSelect').empty().append('<option value="">Select a Reviewer</option>');
                    var reviewers = JSON.parse(response);
                    reviewers.forEach(function(reviewer) {
                        $('#reviewerSelect').append($('<option>', {
                            value: reviewer.id,
                            text: reviewer.name
                        }));
                    });
                }
            });
        }
    });
});
</script>
