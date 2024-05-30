<?php 
include('db_connect.php');
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'admin_class.php';
$crud = new Action(); // Assuming the Action class is auto-included by your system

$shared_papers = $crud->getMySharedPapers($_SESSION['login_id']);
?>

<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header">
            <h4 class="text-dark mb-0">Papers I have shared so far</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Share ID</th>
                            <th>Paper Title</th>
                            <th>Share Type</th>
                            <th>Reviewers</th>
                            <th>Date Shared</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($shared_papers as $row): ?>
                        <tr>
                            <td><?php echo $row['share_id']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['share_type']; ?></td>
                            <td><?php echo $row['shared_with']; ?></td>
                            <td><?php echo $row['share_date']; ?></td>
                            <td><?php echo $row['deadline']; ?></td>
                            <td class="<?php echo $row['status_class']; ?>"><?php echo $row['review_status']; ?></td>
                            <td>
                                <button onclick="cancelSharing(<?= $row['share_id']; ?>)" class="btn btn-danger">Cancel Sharing</button>
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
    // Initialize DataTables
    $('#dataTable').DataTable();
});

function cancelSharing(shareId) {
    if (confirm("Are you sure you want to cancel sharing this paper?")) {
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: { action: 'cancel_sharing', share_id: shareId },
            success: function(response) {
                if (response.trim() == '1') {
                    alert('Sharing cancelled successfully');
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Failed to cancel sharing');
                }
            },
            error: function(xhr) {
                alert('Error cancelling sharing: ' + xhr.responseText);
            }
        });
    }
}
</script>
