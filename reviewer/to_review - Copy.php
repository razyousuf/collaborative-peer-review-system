<?php 
include('db_connect.php');

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'admin_class.php';
$crud = new Action(); // Assuming the Action class is auto-included by your system or configurational file
?>

<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header">
            <h4 class="text-dark mb-0">Papers Shared with Me for Review</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Unique ID</th>
                            <th>Paper Title</th>
                            <th>Author</th>
                            <th>Share Date</th>
                            <th>Deadline</th>
                            <th>File Size</th>
                            <th>Review Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $papers = $crud->getSharedPapersWithUser($_SESSION['login_id']);
                        foreach($papers as $row):
                            $status_class = '';
                            switch($row['review_status']) {
                                case 'In queue':
                                    $status_class = 'text-warning'; // Orange for 'In queue'
                                    break;
                                case 'Reviewed':
                                    $status_class = 'text-success'; // Green for 'Reviewed'
                                    break;
                                case 'Rejected':
                                    $status_class = 'text-danger'; // Red for 'Rejected'
                                    break;
                            }
                            $disabled = ($row['review_status'] !== 'In queue') ? 'disabled' : '';
                        ?>
                        <tr>
                            <td><?php echo $row['paper_id']; ?></td>
                            <td>
                                <?php if($row['review_status'] !== 'Rejected'): ?>
                                    <a href="<?php echo $row['paperurl']; ?>" target="_blank"><?php echo $row['title']; ?></a>
                                <?php else: ?>
                                    <?php echo $row['title']; // No link for rejected papers ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['author_name']; ?></td>
                            <td><?php echo $row['share_date']; ?></td>
                            <td><?php echo $row['deadline']; ?></td>
                            <td><?php echo $row['size']; ?> MB</td>
                            <td class="<?php echo $status_class; ?>"><?php echo $row['review_status']; ?></td>
                            <td>
                                <button class="btn btn-primary <?php echo $disabled; ?>" onclick="markAsReviewed(<?php echo $row['paper_id']; ?>)" <?php echo $disabled; ?>>Mark as Reviewed</button>
                                <button class="btn btn-danger <?php echo $disabled; ?>" onclick="rejectPaper(<?php echo $row['paper_id']; ?>)" <?php echo $disabled; ?>>Reject</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#dataTable').DataTable();
	
	// Function to initiate the review process
    $('.review_paper').click(function(){
        var paper_id = $(this).data('id');
        if(confirm('Are you sure you want to review this paper?')) {
            $.ajax({
                url: 'ajax.php?action=review_paper',
                method: 'POST',
                data: { paper_id: paper_id },
                success: function(resp){
                    if(resp == 'success'){
                        alert('Paper reviewed.');
                        location.reload();
                    } else {
                        alert('Failed to review paper.');
                    }
                }
            });
        }
    });
	
    $('.mark_reviewed').click(function(){
        var paper_id = $(this).data('id');
        if(confirm('Are you sure you want to mark this paper as Reviewed?')) {
            $.ajax({
                url: 'ajax.php?action=mark_reviewed',
                method: 'POST',
                data: { paper_id: paper_id },
                success: function(resp){
                    if(resp == 1){
                        alert('Paper marked as reviewed');
                        location.reload();
                    } else {
                        alert('Failed to update status');
                    }
                }
            });
        }
    });

    $('.reject_paper').click(function(){
        var paper_id = $(this).data('id');
        if(confirm('Are you sure you want to reject this paper?')) {
            $.ajax({
                url: 'ajax.php?action=reject_paper',
                method: 'POST',
                data: { paper_id: paper_id },
                success: function(resp){
                    if(resp == 1){
                        alert('Paper rejected');
                        location.reload();
                    } else {
                        alert('Failed to update status');
                    }
                }
            });
        }
    });
});
</script>
