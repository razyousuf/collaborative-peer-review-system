<?php
include 'db_connect.php';
$users = $conn->query("SELECT id, CONCAT(firstname, ' ', lastname) AS name FROM users WHERE user_type = 2"); // Assuming user_type 2 is for reviewers or relevant user type
?>
<div class="container-fluid">
    <h3 class="text-dark mb-4">Add New Group</h3>
    <div class="card shadow">
        <div class="card-body">
            <form action="ajax.php?action=add_group" method="post" id="add_group_form">
                <div class="form-group">
                    <label for="group_name">Group Name:</label>
                    <input type="text" name="group_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="members">Select Members:</label>
                    <select multiple name="members[]" class="form-control">
                        <?php while($row = $users->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Add Group</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$('#add_group_form').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if(response.trim() == "1") {
                alert('Group added successfully.');
                window.location = 'group_list.php';
            } else if(response.trim() == "2") {
                alert('Group name already exists.');
            } else {
                alert('Failed to add group.');
            }
        }
    });
});
</script>
