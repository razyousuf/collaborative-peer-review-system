<?php
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="" id="manage_criteria" method="post">
				<!-- Hidden input for criterion ID; if set, we're updating; if not, we're adding a new criterion -->
				<input type="hidden" name="c_id" value="<?php echo isset($c_id) ? $c_id : ''; ?>">

				<!-- Other form fields here -->
				<div class="form-group">
					<label for="name">Name:</label>
					<input type="text" name="name" class="form-control" required value="<?php echo isset($name) ? $name : ''; ?>">
				</div>
				<div class="form-group">
					<label for="description">Description:</label>
					<textarea name="description" class="form-control"><?php echo isset($description) ? $description : ''; ?></textarea>
				</div>
				<div class="form-group">
					<label for="grading_scheme">Grading Scheme:</label>
					<textarea name="grading_scheme" class="form-control" ><?php echo isset($grading_scheme) ? $grading_scheme : ''; ?></textarea>
				</div>
				    <div class="form-group text-center"> <!-- Centering the buttons -->
						<button type="submit" class="btn btn-primary">Save Criteria</button>
						<button type="button" class="btn btn-secondary" onclick="location.href = 'index.php?page=criteria_list'">Cancel</button>
					</div>
			</form>
        </div>
    </div>
</div>
<style>
    img#cimg{
        height: 15vh;
        width: 15vh;
        object-fit: cover;
        border-radius: 100% 100%;
    }
</style>
<script>
    $('#manage_criteria').submit(function(e){
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url:'ajax.php?action=save_criteria',
			data: new FormData(document.getElementById('manage_criteria')), // Corrected line
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
				console.log("Response received:", resp); // Check the exact response from the server
				if(resp.trim() == "1"){
					alert_toast('Data successfully saved.', "success");
					setTimeout(function(){
						location.replace('index.php?page=criteria_list')
					},750)
				} else if(resp.trim() == "2"){
					$('#msg').html("<div class='alert alert-danger'>Already exist.</div>");
					$('[name="name"]').addClass("border-danger");
					end_load();
				} else {
					console.error("Error response:", resp); // Log any other response for debugging
					_conf("An error occurred, please check all info and try again later.", "Error", [$(this).attr('data-id')]);
					setTimeout(function(){
						location.reload();
					},1500)
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error("AJAX error: " + textStatus + ': ' + errorThrown); // This will help identify AJAX request issues
			}


        })
    })
</script>
