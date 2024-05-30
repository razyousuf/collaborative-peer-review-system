<?php include('db_connect.php'); ?>

<div class="container-fluid">
	<div class="header">
		<h4>Welcome <?php echo $_SESSION['login_name'] . '! '; ?></h4>
	</div>
    <div class="card shadow"> 
	<div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="text-dark mb-0">Papers Management</h4> <!-- mb-0 removes margin at the bottom -->
    <a class="btn btn-sm btn-default btn-flat border-primary" href="./index.php?page=paper_upload" style="margin-left: auto;">
        <i class="fa fa-plus"></i> Add New Paper
    </a>
</div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>Unique ID</th>
							<th>Paper Title</th>
							<th>Paper Abstract</th>
							<th>Paper Author</th>
							<th>Uploaded Date</th>
							<th>File Size</th>
							<th>Review Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							// Ensure you have session_start() at the beginning of your script to access $_SESSION
							$papers = $conn->prepare("SELECT p.*, CONCAT(u.firstname, ' ', u.lastname) as author, u.id as author_id, p.submission_date FROM papers p LEFT JOIN users u ON p.author_id = u.id WHERE p.author_id = ? ORDER BY p.submission_date DESC");
							$papers->bind_param("i", $_SESSION['login_id']);
							$papers->execute();
							$result = $papers->get_result();
							while($row = $result->fetch_assoc()):?>
								<tr>
									<td><?php echo $row['paper_id']; ?></td>
									<td><a href="<?php echo $row['paperurl']; ?>" target="_blank"><?php echo $row['title']; ?></a></td>
									<td><?php echo substr($row['abstract'], 0, 50).'...'; ?></td>
									<td><?php echo $row['author']; ?></td>
									<td><?php echo date('Y-m-d H:i:s', strtotime($row['submission_date'])); ?></td> 
									<td><?php echo $row['size']; ?> MB</td>
									<td><?php echo $row['review_status']; ?></td>
									<td>
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle" type="button" id="actionMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Actions
											</button>
											<div class="dropdown-menu" aria-labelledby="actionMenuButton">
												<a class="dropdown-item" href="./index.php?page=share_paper&paper_id=<?php echo $row['paper_id']; ?>">Share</a>
												<a class="dropdown-item" href="./index.php?page=edit_paper&paper_id=<?php echo $row['paper_id']; ?>">Edit</a>
												<a class="dropdown-item delete_paper" href="javascript:void(0)" data-id="<?php echo $row['paper_id'] ?>">Delete</a>
											</div>
										</div>
									</td>
								</tr>
						<?php endwhile; ?>
					</tbody>

				</table>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Initialize DataTables, if you're using it
    $('#paper-list').DataTable();

    // Delete paper event
    $('.delete_paper').click(function(){
        var paper_id = $(this).data('id');
        if(confirm('Are you sure you want to delete this paper?')) {
            start_load(); // If you have a loading function
            $.ajax({
                url: 'ajax.php?action=delete_paper',
                method: 'POST',
                data: { paper_id: paper_id },
                success: function(resp){
                    if(resp == 1){
                        alert_toast('Paper successfully deleted', 'success');
                        setTimeout(function(){
                            location.reload();
                        }, 500);
                    }
                }
            });
        }
    });
});
</script>
