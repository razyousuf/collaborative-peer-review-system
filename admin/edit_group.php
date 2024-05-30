<?php
include 'db_connect.php';
$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0;
$group = $conn->query("SELECT * FROM groups WHERE group_id = $group_id")->fetch_assoc();
?>
<div class="container-fluid">
    <h3 class="text-dark mb-4">Edit Group</h3>
    <div class="card shadow">
        <div class="card-body">
            <form action="ajax.php?action=update_group" method="post">
                <input type="hidden" name="group_id" value="<?php echo $group['group_id']; ?>">
                <div class="form-group">
                    <label for="group_name">Group Name:</label>
                    <input type="text" name="group_name" class="form-control" required value="<?php echo $group['group_name']; ?>">
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Update Group</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$('form').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        success: function(response) {
            if(response == "1") {
                alert('Group updated successfully.');
                window.location = 'group_list.php';
            } else {
                alert('Failed to update group.');
            }
        }
    });
});
</script>
