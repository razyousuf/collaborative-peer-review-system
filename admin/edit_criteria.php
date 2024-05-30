<?php
include 'db_connect.php';
if(isset($_GET['c_id']) && !empty($_GET['c_id'])) {
    $stmt = $conn->prepare("SELECT * FROM criteria_list WHERE c_id = ?");
    $stmt->bind_param("i", $_GET['c_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $qry = $result->fetch_assoc();
        foreach($qry as $k => $v) {
            $$k = $v;
        }
        include 'new_criteria.php';
    } else {
        die('No criteria found with that ID');
    }
} else {
    die('Invalid Criteria ID');
}

?>