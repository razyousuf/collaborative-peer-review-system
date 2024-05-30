<?php include('db_connect.php'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="ajax.php?action=upload_file" id="manage_paper" method="post" enctype="multipart/form-data">
                <input type="hidden" name="paper_id" value="<?php echo isset($paper_id) ? $paper_id : ''; ?>">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" class="form-control" required value="<?php echo isset($title) ? $title : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="abstract">Abstract:</label>
                    <textarea name="abstract" class="form-control"><?php echo isset($abstract) ? $abstract : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="key_words">Key Words:</label>
                    <textarea name="key_words" class="form-control"><?php echo isset($key_words) ? $key_words : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="file">Upload File:</label>
                    <input type="file" name="file" class="form-control" required />
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Save</button>
                    <button type="button" class="btn btn-secondary" onclick="location.href = 'index.php?page=home'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('#manage_paper').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response);  // Assuming response will notify if the upload/save was successful
            if (response.trim() === "success") {
                alert('Paper uploaded and saved successfully.');
                location.href = 'index.php?page=papers_list';  // Redirect to papers list or wherever appropriate
            } else {
                alert('Error: ' + response);
            }
        },
        error: function(err) {
            console.error('Error submitting form: ', err);
            alert('Error uploading file.');
        }
    });
});
</script>
