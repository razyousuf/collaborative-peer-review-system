<?php include('db_connect.php'); ?>
<?php 
function ordinal_suffix1($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
 ?>
 <div class="col-12">
    <div class="card">
      <div class="card-body">
        Welcome <?php echo $_SESSION['login_name'];
		?>!
        <br>
        <div class="col-md-5">
       
        </div>
      </div>
    </div>
</div>
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM users WHERE user_type = 1 ")->num_rows; ?></h3>

                <p>Total Administrators</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-friends"></i>
              </div>
            </div>
          </div>
           <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM users WHERE user_type = 2")->num_rows; ?></h3>

                <p>Total Reviewers</p>
              </div>
              <div class="icon">
               <i class="fa fa-users"></i>
              </div>
            </div>
          </div>



          <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM paper_shares")->num_rows; ?></h3>

                <p>Total shared files</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
            </div>
          </div>
		   <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM paper_shares WHERE review_status = 'In queue'")->num_rows; ?></h3>

                <p>Total Pending papers</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
            </div>
          </div>
		   <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM paper_shares WHERE review_status = 'reviewed'")->num_rows; ?></h3>

                <p>Total Reviewed Papers</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
            </div>
          </div>
		  <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
              <div class="inner">
                <h3><?php echo $conn->query("SELECT * FROM paper_shares WHERE review_status = 'rejected'")->num_rows; ?></h3>

                <p>Total Rejected Papers</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
            </div>
          </div>
      </div>


	
     <!-- Review Statistics -->
    <div class="row">
        <div class="col-md-6">
            <select id="paperSelect" class="form-control mb-3">
                <option value="">Select a Thesis for Statistics</option>
                <?php
                $stmt = $conn->prepare("
									SELECT p.paper_id, p.title, u.firstname
									FROM papers p
									JOIN users u ON p.author_id = ?
									ORDER BY p.paper_id DESC
								");
                $stmt->bind_param("i", $author_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $lastPaperId = '';
				// Inside your loop that fetches paper data
				while ($row = $result->fetch_assoc()) {
					$lastPaperId = $row['paper_id']; // Continuously update to the current paper's ID
					$paperTitleAuthor = $row['title'] . " [" . $row['firstname'] . "]"; // Concatenate title and author
					echo "<option value='{$row['paper_id']}'>$paperTitleAuthor</option>";
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
                            label: 'Average marks for each criteria',
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
                            label: 'Average marks for each criteria (by a pecific reviewer)',
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
