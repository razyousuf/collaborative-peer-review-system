<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="header">
        <h4>Welcome <?php echo $_SESSION['login_name'] . '! '; echo "User ID: " . $_SESSION['login_id'];?></h4>
    </div>
    <div class="row">
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
                    <p>My Files</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-friends"></i>
                </div>
            </a>
        </div>
		<!-- Papers Reviewed by Others -->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=reviewed_by_others" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						// Prepare the query to count reviewed papers
                        $stmt = $conn->prepare("SELECT COUNT(*) as count 
												FROM paper_shares ps
												JOIN papers p ON ps.paper_id = p.paper_id
												WHERE p.author_id = ?
											");
						// Bind the author's ID and execute
						$stmt->bind_param("i", $_SESSION['login_id']); // Only one parameter needed
						$stmt->execute();
						$result = $stmt->get_result()->fetch_row(); // Fetch the result
						echo $result[0]; // Display the count
					?></h3>
					<p>Shared With Me</p>
				</div>
			</a>
		</div>
		<!-- Papers Reviewed by Others -->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=reviewed_by_others" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						// Prepare the query to count reviewed papers
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT p.paper_id) as count 
							FROM papers p
							WHERE p.author_id = ? AND p.review_status = 'Reviewed'
						");
						// Bind the author's ID and execute
						$stmt->bind_param("i", $_SESSION['login_id']); // Only one parameter needed
						$stmt->execute();
						$result = $stmt->get_result()->fetch_row(); // Fetch the result
						echo $result[0]; // Display the count
					?></h3>
					<p>Reviewed by Others</p>
				</div>
			</a>
		</div>

		<!-- Papers Reviewed by Others -->
		<div class="col-12 col-sm-6 col-md-4">
			<a href="./index.php?page=reviewed_by_others" class="small-box bg-light shadow-sm border">
				<div class="inner">
					<h3><?php
						// Prepare the query to count reviewed papers
						$stmt = $conn->prepare("
							SELECT COUNT(DISTINCT ps.paper_id) as count 
							FROM paper_shares ps
							WHERE ps.user_id = ? AND ps.review_status = 'Rejected'
						");
						// Bind the author's ID and execute
						$stmt->bind_param("i", $_SESSION['login_id']); // Only one parameter needed
						$stmt->execute();
						$result = $stmt->get_result()->fetch_row(); // Fetch the result
						echo $result[0]; // Display the count
					?></h3>
					<p>Rejected by Reviewers</p>
				</div>
			</a>
		</div>
		
        <!-- Papers Shared with Me -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="./index.php?page=to_review.php" class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3><?php
                         $stmt = $conn->prepare("SELECT COUNT(*) as count 
												FROM paper_shares ps
												JOIN papers p ON ps.paper_id = p.paper_id
												WHERE ps.user_id = ? AND ps.review_status = 'In queue'
											");
                        $stmt->bind_param("i", $_SESSION['login_id']);
                        $stmt->execute();
                        echo $stmt->get_result()->fetch_row()[0];
                    ?></h3>
                    <p>Dissertations to Review</p>
                </div>
            </a>
        </div>

        <!-- Papers I've Reviewed -->
        <div class="col-12 col-sm-6 col-md-4">
            <a href="./index.php?page=to_review" class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3><?php
                        $stmt = $conn->prepare("SELECT COUNT(*) as count 
												FROM paper_shares ps
												JOIN papers p ON ps.paper_id = p.paper_id
												WHERE ps.user_id = ? AND p.review_status = 'Reviewed'
											");
                        $stmt->bind_param("i", $_SESSION['login_id']);
                        $stmt->execute();
                        echo $stmt->get_result()->fetch_row()[0];
                    ?></h3>
                    <p>Dissertations I've Reviewed</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Review Statistics -->
   <div class="container-fluid">
    <div class="row">
        <!-- Graph for Specific Reviewer -->
            <div class="col-md-6">
            <canvas id="allReviewersChart"></canvas>
        </div>
        <div class="col-md-6">
            <select id="reviewerSelect" class="form-control">
                <option value="">Select a Reviewer</option>
                <!-- Options will be filled dynamically from server -->
            </select>
            <canvas id="specificReviewerChart"></canvas>
        </div>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    var paper_id = 1; // Set this dynamically or from user input

    function initCharts(specificReviewerId = null) {
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
                            label: 'All Reviewers',
                            data: gradesAll,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
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
                            label: 'Specific Reviewer',
                            data: gradesSpecific,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    }

    initCharts(); // Load the default chart for all reviewers

    $('#reviewerSelect').change(function() {
        var selectedReviewerId = $(this).val();
        if (selectedReviewerId) {
            initCharts(selectedReviewerId);
        } else {
            initCharts(); // Reload the all reviewers chart if no specific reviewer is selected
        }
    });

    // Populate dropdown with reviewers
    $.ajax({
        url: 'ajax.php',
        method: 'GET',
        data: { action: 'fetch_reviewers' },
        success: function(response) {
            var reviewers = JSON.parse(response);
            reviewers.forEach(function(reviewer) {
                $('#reviewerSelect').append($('<option>', {
                    value: reviewer.id,
                    text: reviewer.name
                }));
            });
        }
    });
});
</script>
