<?php
include 'db_connect.php';

$paper_id = isset($_GET['paper_id']) ? $_GET['paper_id'] : 0;
$paper = null;

// Fetch paper details if paper_id is present
if($paper_id) {
    $paper = $conn->query("SELECT * FROM papers WHERE paper_id = $paper_id")->fetch_assoc();
}
$users = $conn->query("SELECT id, firstname, lastname FROM users WHERE user_type = 2"); // Assuming 2 is for reviewers
$groups = $conn->query("SELECT group_id, group_name FROM groups");
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form id="share_paper_form" action="ajax.php?action=share_paper" method="post">
                <input type="hidden" name="paper_id" value="<?php echo $paper_id; ?>">
                <div class="form-group col-md-2">
                    <label for="share_type">Share Type:</label>
                    <select name="share_type" id="share_type" class="form-control">
                        <option value="all">All Reviewers</option>
                        <option value="group">Specific Group</option>
                        <option value="individual">Specific Reviewers</option>
                    </select>
                </div>

                <div class="form-group" id="group_select" style="display:none;">
                    <label for="group_id">Group:</label>
                    <div class="checkbox-list">
                        <?php while ($group = $groups->fetch_assoc()): ?>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="group_id[]" value="<?= $group['group_id'] ?>" id="group_<?= $group['group_id'] ?>">
								<label class="form-check-label" for="group_<?= $group['group_id'] ?>"><?= $group['group_name'] ?></label>
							</div>
						<?php endwhile; ?>
							<button type="button" class="btn btn-link select-all" style="color: #007bff; border: 1px solid #007bff; padding: 5px 10px;">Select All</button>
							<button type="button" class="btn btn-link deselect-all" style="color: #dc3545; border: 1px solid #dc3545; padding: 5px 10px;">Deselect All</button>
                    </div>
                </div>

                <div class="form-group col-md-6" id="user_select" style="display:none;>
                    <label for="user_id">Reviewer:</label>
                    <div class="checkbox-list">
                        <?php while ($user = $users->fetch_assoc()): ?>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="user_id[]" value="<?= $user['id'] ?>" id="user_<?= $user['id'] ?>">
								<label class="form-check-label" for="user_<?= $user['id'] ?>"><?= $user['firstname'] . ' ' . $user['lastname'] ?></label>
							</div>
						<?php endwhile; ?>
							<button type="button" class="btn btn-link select-all" style="color: #007bff; border: 1px solid #007bff; padding: 5px 10px;">Select All</button>
							<button type="button" class="btn btn-link deselect-all" style="color: #dc3545; border: 1px solid #dc3545; padding: 5px 10px;">Deselect All</button>
                    </div>
                </div>

                <div class="form-group  col-md-2">
                    <label for="deadline">Review Deadline:</label>
                    <input type="datetime-local" name="deadline" class="form-control" min="<?= date('Y-m-d') ?>T00:00">
                </div>

                <div class="form-group">
                    <label><input type="checkbox" name="is_anonymous"> Share Anonymously</label>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Share</button>
                    <button type="button" class="btn btn-secondary" onclick="location.href = 'index.php?page=home'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.getElementById('share_type').addEventListener('change', function() {
    var groupSelect = document.getElementById('group_select');
    var userSelect = document.getElementById('user_select');
    groupSelect.style.display = 'none';
    userSelect.style.display = 'none';

    if (this.value === 'group') {
        groupSelect.style.display = 'block';
    } else if (this.value === 'individual') {
        userSelect.style.display = 'block';
    }
});

document.querySelectorAll('.select-all').forEach(function(button) {
    button.addEventListener('click', function() {
        var checkboxes = this.closest('.checkbox-list').querySelectorAll('.form-check-input');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = true;
        });
    });
});

document.querySelectorAll('.deselect-all').forEach(function(button) {
    button.addEventListener('click', function() {
        var checkboxes = this.closest('.checkbox-list').querySelectorAll('.form-check-input');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    });
});

document.getElementById('share_paper_form').addEventListener('submit', function(e) {
    e.preventDefault(); // Stop the form from submitting normally
    var formData = new FormData(this); // Create a FormData object from the form

    $.ajax({
        url: $(this).attr('action'), // Get the URL to send the data to from the form's action attribute
        method: 'POST', // Set method as POST
        data: formData, // Data sent to the server
        contentType: false, // Don't set any content type header
        processData: false, // Don't process the data
        success: function(response) {
            if(response.trim() === "1"){
                alert('Paper successfully shared/updated.'); // Show success message
						// Trigger notification for each selected user
						var selectedUsers = document.querySelectorAll('input[name="user_id[]"]:checked');
						selectedUsers.forEach(function(user) {
							var userId = user.value;
							// AJAX call to send notification
							$.ajax({
								url: 'ajax.php?action=send_notification',
								method: 'POST',
								data: { user_id: userId, message: 'A new paper has been shared with you.' },
								success: function(response) {
									console.log('Notification sent to user with ID: ' + userId);
								},
								error: function(xhr, status, error) {
									console.error('Error sending notification:', error);
								}
							});
						});

						setTimeout(function(){
							window.location = 'index.php?page=home'; // Redirect after a delay
						}, 200); // Redirect delay in milliseconds
            } else {
                alert('Failed to share the paper.'); // Show error message
            }
        },
        error: function() {
            alert('Error sharing paper.'); // Show error message if AJAX call fails
        }
    });
});


</script>
