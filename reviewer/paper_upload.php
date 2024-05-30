<?php
include 'db_connect.php';

$paper_id = isset($_GET['paper_id']) ? $_GET['paper_id'] : null;
$title = $abstract = $key_words = ""; // Default empty values

if ($paper_id) {
    // Fetch existing paper details if editing
    $stmt = $conn->prepare("SELECT * FROM papers WHERE paper_id = ? AND author_id = ?");
    $stmt->bind_param("i", $paper_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $paper = $result->fetch_assoc();
        $title = $paper['title'];
        $abstract = $paper['abstract'];
        $key_words = $paper['key_words'];
    } else {
        echo "<p>Paper not found.</p>";
        exit;
    }
}
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="ajax.php?action=save_paper" id="manage_paper" method="post" enctype="multipart/form-data">
                <input type="hidden" name="paper_id" value="<?php echo $paper_id; ?>">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" class="form-control" required value="<?php echo $title; ?>">
                </div>
                <div class="form-group">
                    <label for="abstract">Abstract:</label>
                    <textarea name="abstract" class="form-control"><?php echo $abstract; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="key_words">Key Words:</label>
                    <textarea name="key_words" class="form-control"><?php echo $key_words; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="file">Upload File:</label>
                    <input type="file" name="file" class="form-control">
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary"><?php echo $paper_id ? 'Update Details' : 'Save Details'; ?></button>
                    <button type="button" class="btn btn-secondary" onclick="location.href = 'index.php?page=thesis'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('#share_paper_form').submit(function(e){
    e.preventDefault();
    $.ajax({
        url: 'ajax.php?action=share_paper',
        type: 'POST',
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function(response){
            let data = JSON.parse(response);
            if(data.status === 'success') {
                alert(data.message);
                window.location.href = 'index.php?page=thesis';  // Redirect to home on success
            } else {
                alert(data.message);
            }
        },
        error: function(){
            alert('There was an error sharing the paper.');
        }
    });
});

</script>
