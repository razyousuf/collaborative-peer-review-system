<?php
ob_start();
date_default_timezone_set("Europe/London");
//header('Content-Type: application/json');
//$action = $_GET['action'];  // Default to an empty string if 'action' is not set
$action = $_POST['action'] ?? $_GET['action'] ?? '';
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'save_subject'){
	$save = $crud->save_subject();
	if($save)
		echo $save;
}
if($action == 'delete_subject'){
	$save = $crud->delete_subject();
	if($save)
		echo $save;
}
if($action == 'save_class'){
	$save = $crud->save_class();
	if($save)
		echo $save;
}
if($action == 'delete_class'){
	$save = $crud->delete_class();
	if($save)
		echo $save;
}
if($action == 'save_academic'){
	$save = $crud->save_academic();
	if($save)
		echo $save;
}
if($action == 'delete_academic'){
	$save = $crud->delete_academic();
	if($save)
		echo $save;
}
if($action == 'make_default'){
	$save = $crud->make_default();
	if($save)
		echo $save;
}
if($action == 'save_criteria'){
	$save = $crud->save_criteria();
	if($save)
		echo $save;
}
if($action == 'delete_criteria'){
	$save = $crud->delete_criteria();
	if($save)
		echo $save;
}
if($action == 'save_grades'){
	$save = $crud->save_grades();
	if($save)
		echo $save;
}
if($action == 'save_question'){
	$save = $crud->save_question();
	if($save)
		echo $save;
}
if($action == 'delete_question'){
	$save = $crud->delete_question();
	if($save)
		echo $save;
}

if($action == 'save_criteria_question'){
	$save = $crud->save_criteria_question();
	if($save)
		echo $save;
}
if($action == 'save_criteria_order'){
	$save = $crud->save_criteria_order();
	if($save)
		echo $save;
}

if($action == 'save_question_order'){
	$save = $crud->save_question_order();
	if($save)
		echo $save;
}
if($action == 'save_faculty'){
	$save = $crud->save_faculty();
	if($save)
		echo $save;
}
if($action == 'delete_faculty'){
	$save = $crud->delete_faculty();
	if($save)
		echo $save;
}
if($action == 'save_student'){
	$save = $crud->save_student();
	if($save)
		echo $save;
}
if($action == 'delete_student'){
	$save = $crud->delete_student();
	if($save)
		echo $save;
}
if($action == 'save_restriction'){
	$save = $crud->save_restriction();
	if($save)
		echo $save;
}
if($action == 'save_evaluation'){
	$save = $crud->save_evaluation();
	if($save)
		echo $save;
}

if($action == 'get_class'){
	$get = $crud->get_class();
	if($get)
		echo $get;
}
if($action == 'get_report'){
	$get = $crud->get_report();
	if($get)
		echo $get;
}
// At the appropriate location within the ajax.php file
if ($action == 'upload_file') {
    if (isset($_FILES['file'], $_SESSION['login_id'], $_POST['title'], $_POST['abstract'])) {
        $file = $_FILES['file'];
        $user_id = $_SESSION['login_id']; // Retrieved from session
        $title = $_POST['title']; // Retrieved from POST data
        $abstract = $_POST['abstract']; // Retrieved from POST data
        
        // Call the function with all required parameters
        $upload = $crud->upload_file($file, $user_id, $title, $abstract);
        
        if ($upload === 'File uploaded successfully') {
            echo $upload;
        } else {
            echo $upload; // Will contain the error message
        }
    } else {
        echo 'All data is not set. Make sure file, title, and abstract are provided.';
    }
}

if($action == 'save_paper'){
    $response = $crud->save_or_update_paper($_POST, $_FILES['file']);
    if ($response)
		echo $response;
}



    switch($action) {
        case 'get_paper':
            $paper_id = $_POST['paper_id'];
            echo json_encode($crud->get_paper_details($paper_id));
            break;
        case 'update_paper':
            $paper_id = $_POST['paper_id'];
            $title = $_POST['title'];
            $abstract = $_POST['abstract'];
            $file_size = $_POST['size'];
            $review_status = $_POST['review_status'];
            echo $crud->update_paper($paper_id, $title, $abstract, $file_size, $review_status) ? "success" : "error";
            break;
        case 'delete_paper':
            $paper_id = $_POST['paper_id'];
            if($paper_id){
				$delete = $crud->delete_paper($paper_id);
				echo $delete;
			} else {
				echo 'Paper ID not provided.';
			}
        default:
            // Handle other actions or error
            break;
    }
//////////////////////////////////////////////////Groups
	if(isset($_GET['action'])) {
		$action = $_GET['action'];

		switch($action) {
			case 'add_group':
				$group_name = $_POST['group_name'];
				$members = isset($_POST['members']) ? $_POST['members'] : [];
				echo $crud->add_group($group_name, $members);
				break;
			case 'update_group':
				$group_id = $_POST['group_id'];
				$group_name = $_POST['group_name'];
				$members = isset($_POST['members']) ? $_POST['members'] : [];
				echo $crud->update_group($group_id, $group_name, $members) ? "1" : "0";
				break;
			case 'delete_group':
				$group_id = $_POST['group_id'];
				echo $crud->delete_group($group_id) ? "1" : "0";
				break;
		}
	}
	// In ajax.php under your action switch
	if(isset($_POST['action'])) {
		$action = $_POST['action'];
		switch($action) {
			case 'leave_group':
				$group_id = $_POST['group_id'];
				$user_id = $_SESSION['login_id']; // Assuming you have session start and login logic handled
				echo $crud->leave_group($group_id, $user_id);
				break;
			// other cases
		}
	}

////////////////////////////////////////////////Share papers	\\\\\\\\\\\\\
	if($action === 'share_paper') {
		$paper_id = $_POST['paper_id'];
		$share_type = $_POST['share_type'];
		$group_ids = isset($_POST['group_id']) ? $_POST['group_id'] : [];
		$user_ids = isset($_POST['user_id']) ? $_POST['user_id'] : [];
		$deadline = $_POST['deadline'];
		$is_anonymous = isset($_POST['is_anonymous']) ? true : false;
		$author_id = $_SESSION['login_id']; // Assuming the author's ID is stored in session when logged in

		// Log for debugging purposes
		error_log("Sharing details: " . print_r($_POST, true));

		// Call the function with the author_id
		$result = $crud->share_paper($paper_id, $share_type, $group_ids, $user_ids, $deadline, $is_anonymous, $author_id);

		// Output the result
		echo $result ? "1" : "0";
	}

	
// In ajax.php
if(isset($_GET['action']) && $_GET['action'] == 'get_shared_papers_with_user') {
    echo json_encode($crud->getSharedPapersWithUser($_SESSION['login_id']));
    exit;
}

/////////////////////////////////////////////////My Shared Papers
if(isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'get_my_shared_papers':
			//$data = $crud->fetchMySharedPapers(); // Ensure this method is implemented
			$data = $crud->getMySharedPapers($_SESSION['login_id']);
			echo json_encode(['success' => true, 'data' => $data]);
			break;
		case 'reviewed_by':
			// Example error handling
			try {
				$shareId = $_POST['share_id'];
				$reviewDetails = $crud->getReviewDetails($shareId);
				echo json_encode(['success' => true, 'data' => $reviewDetails]);
			} catch (Exception $e) {
				echo json_encode(['success' => false, 'message' => $e->getMessage()]);
			}
			break;
		default:
			//echo json_encode(['success' => false, 'message' => 'Invalid action']);
			error_log("Unsupported action attempted: " . $action);

	}
}


if(isset($_POST['action'])) {
    switch($_POST['action']) {
        case 'cancel_sharing':
            $share_id = $_POST['share_id'];
            echo $crud->cancelSharing($share_id); // Assuming this method exists and works correctly
            break;
        default:
        //echo json_encode(['success' => false, 'message' => "Unsupported action: $action"]);
			error_log("Unsupported action attempted: " . $action);
			//break;
    }
}

///////////////////////////// To Review\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if(isset($_GET['action'])) {
    $action = $_GET['action'];

    switch($action) {
        // Existing cases...
		case 'review_paper':
            if(isset($_POST['paper_id'])) {
                $paper_id = $_POST['paper_id'];
                echo $crud->reviewPaper($paper_id) ? "1" : "Failed to start review";
            } else {
                echo "No paper ID provided.";
            }
            break;
		
        case 'mark_reviewed':
            if(isset($_POST['paper_id'])) {
                $paper_id = $_POST['paper_id'];
                echo $crud->markAsReviewed($paper_id) ? "1" : "0";
            } else {
                echo "0";
            }
            break;

        case 'reject_paper':
            if(isset($_POST['paper_id'])) {
                $paper_id = $_POST['paper_id'];
                echo $crud->rejectPaper($paper_id) ? "1" : "0";
            } else {
                echo "0";
            }
            break;

        // Existing cases...
    }
}
/////////////////////////////////////////Statistics to acee\\\\\\\\\\\\\\\\\\\\\\\\\
	if(isset($_GET['action'])) {
		switch($_GET['action']) {
			case 'fetch_reviewers':
				//if(isset($_GET['paper_id'])) {
				//$paper_id = $_GET['paper_id'];
				// Assuming $crud is your object that handles database operations
				echo json_encode($crud->fetchAllReviewers());
				break;
				
			case 'fetch_average_grades_by_paper_for_reviewer':
				$paper_id = $_GET['paper_id'];
				$reviewer_id = $_GET['reviewer_id'];
				echo json_encode($crud->fetchAverageGrades($paper_id, $reviewer_id));
				break;
			case 'fetch_average_grades_by_paper':
				$paper_id = $_GET['paper_id'];
				echo json_encode($crud->fetchAverageGrades($paper_id));
				break;
			// other cases...
		}
	}
///////////////////////////Review Collaboratively\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//header('Content-Type: application/json');
//$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'get_paper_details':
        $paper_id = $_GET['paper_id'] ?? null;
        if ($paper_id) {
            echo json_encode($crud->fetchPaperDetails($paper_id));
        } else {
            echo json_encode(['success' => false, 'message' => 'Paper ID is required']);
        }
        break;

    case 'fetch_annotations':
		$paper_id = $_POST['paper_id'] ?? null;
		$share_id = $_POST['share_id'] ?? null;
		$page = $_POST['page'] ?? null;
		if ($paper_id && $share_id && $page) {
			$annotations = $crud->fetchAnnotations($paper_id, $share_id, $page);
			$like_counts = $crud->fetch_likes_count(); // Fetch like counts
			echo json_encode(['success' => true, 'annotations' => $annotations, 'likeCounts' => $like_counts]);
			//echo json_encode(['success' => true, 'annotations' => $annotations]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
		}
		break;



    case 'save_annotation':
        $data = [
            'paper_id' => $_POST['paper_id'] ?? 0,
            'reviewer_id' => $_POST['reviewer_id'] ?? 0,
            'share_id' => $_POST['share_id'] ?? 0,
            'type' => $_POST['type'] ?? 0, // Default to '0' for annotation
            'content' => $_POST['content'] ?? '',
            'page' => $_POST['page'] ?? 0
        ];
        $result = $crud->saveAnnotation($data);
        echo json_encode($result ? ['success' => true, 'id' => $result] : ['success' => false, 'message' => 'Failed to save annotation']);
        break;
	default:
		// Log this occurrence if possible, to analyze these requests later
		error_log("Unsupported action attempted: " . $action);
    //default:
    //    echo json_encode(['success' => false, 'message' => "Unsupported action: $action"]);
    //    break;
}

////////////////////////////////////////////////Like and Reply\\\\\\\\\\\\\\\\
$response = '';
switch ($action) {
    case 'save_reply':
        $response = $crud->save_reply();
        break;
    case 'save_like':
        $response = $crud->save_like();
        break;
    default:
        //$response = json_encode(['success' => false, 'message' => "Unsupported action: $action"]);
		// Log this occurrence if possible, to analyze these requests later
		error_log("Unsupported action attempted: " . $action);
		break;
}
echo $response;

///////////////////////////////////////////Notification Area\\\\\\\\\\\\\\\\\\\\
switch ($action) {
    case 'send_notification':
        sendNotification();
        break;
	case 'check_notifications':
        checkNotifications();
    // Add other cases for different actions if needed
    default:
        //echo 'Invalid action.';
		error_log("Unsupported action attempted: " . $action);

} 

ob_end_flush();
?>
