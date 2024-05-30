<?php
include 'db_connect.php';

// Check if the paper ID is set in the query string
if(isset($_GET['paper_id']) && !empty($_GET['paper_id'])) {
    // Prepare the SQL statement to fetch the paper data
    $stmt = $conn->prepare("SELECT * FROM papers WHERE paper_id = ?");
    $stmt->bind_param("i", $_GET['paper_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if any paper is found
    if($result->num_rows > 0) {
        // Fetch the paper data
        $qry = $result->fetch_assoc();
        // Use variable variables to assign fetched data to named variables
        foreach($qry as $k => $v) {
            $$k = $v;
        }
        
        // Include the form for editing
        // Assume 'paper_upload.php' contains the form for editing paper details
        include 'paper_upload.php';
    } else {
        // No paper found with the given ID
        die('No paper found with that ID');
    }
} else {
    // No paper ID provided in the query string
    die('Invalid paper ID');
}
?>
