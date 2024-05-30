<?php 
include 'db_connect.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$query = "SELECT g.group_id, g.group_name, GROUP_CONCAT(u.firstname SEPARATOR ', ') AS members
          FROM groups g
          LEFT JOIN group_members gm ON g.group_id = gm.group_id
          LEFT JOIN users u ON gm.user_id = u.id
          GROUP BY g.group_id";

$groups = $conn->query($query);
?>


<div class="container-fluid">
    <div class="card shadow">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="text-dark mb-0">Groups Management</h4> <!-- mb-0 removes margin at the bottom -->
			<a class="btn btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_group" style="margin-left: auto;">
				<i class="fa fa-plus"></i> Add New Group
			</a>
	</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Group ID</th>
                            <th>Group Name</th>
                            <th>Members</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $groups->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['group_id']; ?></td>
                            <td><?php echo $row['group_name']; ?></td>
                            <td><?php echo $row['members']; ?></td>
                            <td>
                                <a href="edit_group.php?group_id=<?php echo $row['group_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteGroup(<?php echo $row['group_id']; ?>)">Delete</button>
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
function deleteGroup(group_id) {
    if(confirm('Are you sure you want to delete this group?')) {
        $.ajax({
            url: 'ajax.php?action=delete_group',
            method: 'POST',
            data: { group_id: group_id },
            success: function(resp) {
                if(resp == 1) {
                    alert('Group deleted successfully.');
                    location.reload();
                } else {
                    alert('Failed to delete group.');
                }
            }
        });
    }
}
</script>