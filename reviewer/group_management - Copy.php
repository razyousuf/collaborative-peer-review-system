<?php
include 'db_connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure there is a logged-in user
if (!isset($_SESSION['login_id'])) {
    echo "You must be logged in to view this page.";
    exit; // Or redirect to login page
}

$login_id = $_SESSION['login_id']; // The current logged in user's ID

// Fetch group IDs where the current user is a member
$group_ids_query = "SELECT DISTINCT gm.group_id
                    FROM group_members gm
                    WHERE gm.user_id = ?";
$stmt = $conn->prepare($group_ids_query);
$stmt->bind_param("i", $login_id);
$stmt->execute();
$result = $stmt->get_result();
$group_ids = [];
while ($row = $result->fetch_assoc()) {
    $group_ids[] = $row['group_id'];
}

// Convert group IDs array to a string to use in the IN clause
$group_ids_str = implode(',', $group_ids);

// Fetch all members of these groups
if (!empty($group_ids)) {
    $groups_query = "SELECT g.group_id, g.group_name, GROUP_CONCAT(u.firstname SEPARATOR ', ') AS members
                     FROM groups g
                     LEFT JOIN group_members gm ON gm.group_id = g.group_id
                     LEFT JOIN users u ON gm.user_id = u.id
                     WHERE g.group_id IN ($group_ids_str)
                     GROUP BY g.group_id, g.group_name";
    $groups_result = $conn->query($groups_query);
}
?>


<div class="container-fluid">
    <div class="card shadow">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="text-dark mb-0">My Groups</h4> <!-- mb-0 removes margin at the bottom -->
			<a class="btn btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_group" style="margin-left: auto;">
				<i class="fa fa-plus"></i> Add Private Group
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
						<?php if (isset($groups_result)): ?>
							<?php while ($row = $groups_result->fetch_assoc()): ?>
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
						<?php endif; ?>
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
            },
            error: function(xhr) {
                alert('Error: ' + xhr.statusText);
            }
        });
    }
}
</script>