<?php
//session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$type = 'users';
		$type2 = array("","admin","reviewer","student");
		$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."'  ");
		
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
	
			$_SESSION['login_type'] = $_SESSION['login_user_type'];		
			$_SESSION['login_view_folder'] = $type2[$_SESSION['login_type']].'/';
			
			return 1;
		}else{
			return 2;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname) as name FROM users where id = '".$student_code."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['rs_'.$key] = $value;
			}
		return 1;
		}else{
			return 3;
		}
	}
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
	
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data , date_created = CURRENT_TIMESTAMP"); 
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}
		$_SESSION['testing'] = $data;
		if($save){
			return 1;
		}else{
			return 3;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
				if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user(){
		extract($_POST);
		$data = "";
		$type = 'users';
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table','password')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 1;
		
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(!empty($password))
			$data .= " ,password=md5('$password') ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
		//	echo "UPDATE users set $data where id = $id";
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name'])){
					$_SESSION['login_avatar'] = $fname;
			}
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete){
			return 1; // the user deleted successfully
		}
	}
	function save_system_settings(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set $data where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if($save){
			foreach($_POST as $k => $v){
				if(!is_numeric($k)){
					$_SESSION['system'][$k] = $v;
				}
			}
			if($_FILES['cover']['tmp_name'] != ''){
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image(){
		extract($_FILES['file']);
		if(!empty($tmp_name)){
			$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
			$move = move_uploaded_file($tmp_name,'assets/uploads/'. $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path =explode('/',$_SERVER['PHP_SELF']);
			$currentPath = '/'.$path[1]; 
			if($move){
				return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/'.$fname;
			}
		}
	}
	function save_subject(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM subject_list where code = '$code' and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO subject_list set $data");
		}else{
			$save = $this->db->query("UPDATE subject_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_subject(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM subject_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_class(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM class_list where (".str_replace(",",'and',$data).") and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		if(isset($user_ids)){
			$data .= ", user_ids='".implode(',',$user_ids)."' ";
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO class_list set $data");
		}else{
			$save = $this->db->query("UPDATE class_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_class(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM class_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_academic(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM academic_list where (".str_replace(",",'and',$data).") and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		$hasDefault = $this->db->query("SELECT * FROM academic_list where is_default = 1")->num_rows;
		if($hasDefault == 0){
			$data .= " , is_default = 1 ";
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO academic_list set $data");
		}else{
			$save = $this->db->query("UPDATE academic_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_academic(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM academic_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function make_default(){
		extract($_POST);
		$update= $this->db->query("UPDATE academic_list set is_default = 0");
		$update1= $this->db->query("UPDATE academic_list set is_default = 1 where id = $id");
		$qry = $this->db->query("SELECT * FROM academic_list where id = $id")->fetch_array();
		if($update && $update1){
			foreach($qry as $k =>$v){
				if(!is_numeric($k))
					$_SESSION['academic'][$k] = $v;
			}

			return 1;
		}
	}
	function save_criteria(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM criteria_list where (".str_replace(",",'and',$data).") and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		
		if(empty($id)){
			$lastOrder= $this->db->query("SELECT * FROM criteria_list order by abs(order_by) desc limit 1");
		$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
		$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO criteria_list set $data");
		}else{
			$save = $this->db->query("UPDATE criteria_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_criteria(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM criteria_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_criteria_order(){
		extract($_POST);
		$data = "";
		foreach($criteria_id as $k => $v){
			$update[] = $this->db->query("UPDATE criteria_list set order_by = $k where id = $v");
		}
		if(isset($update) && count($update)){
			return 1;
		}
	}

	function save_question(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		
		if(empty($id)){
			$lastOrder= $this->db->query("SELECT * FROM question_list where academic_id = $academic_id order by abs(order_by) desc limit 1");
			$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
			$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO question_list set $data");
		}else{
			$save = $this->db->query("UPDATE question_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_question(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM question_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_question_order(){
		extract($_POST);
		$data = "";
		foreach($qid as $k => $v){
			$update[] = $this->db->query("UPDATE question_list set order_by = $k where id = $v");
		}
		if(isset($update) && count($update)){
			return 1;
		}
	}
	function save_faculty(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			
		}
		$check = $this->db->query("SELECT * FROM users where id ='$school_id' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 3;
		
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function delete_faculty(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_student(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function delete_student(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_task(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO task_list set $data");
		}else{
			$save = $this->db->query("UPDATE task_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_task(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_progress(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'progress')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!isset($is_complete))
			$data .= ", is_complete=0 ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO task_progress set $data");
		}else{
			$save = $this->db->query("UPDATE task_progress set $data where id = $id");
		}
		if($save){
		if(!isset($is_complete))
			$this->db->query("UPDATE task_list set status = 1 where id = $task_id ");
		else
			$this->db->query("UPDATE task_list set status = 2 where id = $task_id ");
			return 1;
		}
	}
	function delete_progress(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_progress where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_restriction(){
		extract($_POST);
		$filtered = implode(",",array_filter($rid));
		if(!empty($filtered))
			$this->db->query("DELETE FROM restriction_list where id not in ($filtered) and academic_id = $academic_id");
		else
			$this->db->query("DELETE FROM restriction_list where  academic_id = $academic_id");
		foreach($rid as $k => $v){
			$data = " academic_id = $academic_id ";
			$data .= ", faculty_id = {$faculty_id[$k]} ";
			$data .= ", class_id = {$class_id[$k]} ";
			$data .= ", subject_id = {$subject_id[$k]} ";
			if(empty($v)){
				$save[] = $this->db->query("INSERT INTO restriction_list set $data ");
			}else{
				$save[] = $this->db->query("UPDATE restriction_list set $data where id = $v ");
			}
		}
			return 1;
	}
	function save_evaluation(){
		extract($_POST);
		$data = " student_id = {$_SESSION['login_id']} ";
		$data .= ", academic_id = $academic_id ";
		$data .= ", subject_id = $subject_id ";
		$data .= ", class_id = $class_id ";
		$data .= ", restriction_id = $restriction_id ";
		$data .= ", faculty_id = $faculty_id ";
		$save = $this->db->query("INSERT INTO evaluation_list set $data");
		if($save){
			$eid = $this->db->insert_id;
			foreach($qid as $k => $v){
				$data = " evaluation_id = $eid ";
				$data .= ", question_id = $v ";
				$data .= ", rate = {$rate[$v]} ";
				$ins[] = $this->db->query("INSERT INTO evaluation_answers set $data ");
			}
			if(isset($ins))
				return 1;
		}
	}
	function get_class(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT c.id,concat(c.curriculum,' ',c.level,' - ',c.section) as class,s.id as sid,concat(s.code,' - ',s.subject) as subj FROM restriction_list r inner join class_list c on c.id = r.class_id inner join subject_list s on s.id = r.subject_id where r.faculty_id = {$fid} and academic_id = {$_SESSION['academic']['id']} ");
		while($row= $get->fetch_assoc()){
			$data[]=$row;
		}
		return json_encode($data);

	}
	function get_report(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT * FROM evaluation_answers where evaluation_id in (SELECT evaluation_id FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id and subject_id = $subject_id and class_id = $class_id ) ");
		$answered = $this->db->query("SELECT * FROM evaluation_list where academic_id = {$_SESSION['academic']['id']} and faculty_id = $faculty_id and subject_id = $subject_id and class_id = $class_id");
			$rate = array();
		while($row = $get->fetch_assoc()){
			if(!isset($rate[$row['question_id']][$row['rate']]))
			$rate[$row['question_id']][$row['rate']] = 0;
			$rate[$row['question_id']][$row['rate']] += 1;

		}
		// $data[]= $row;
		$ta = $answered->num_rows;
		$r = array();
		foreach($rate as $qk => $qv){
			foreach($qv as $rk => $rv){
			$r[$qk][$rk] =($rate[$qk][$rk] / $ta) *100;
		}
	}
	$data['tse'] = $ta;
	$data['data'] = $r;
		
		return json_encode($data);

	}
	
	function save_grades() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grades']) && isset($_POST['paper_id'])) {
        $grades = $_POST['grades'];
        $paper_id = $_POST['paper_id'];
        $reviewer_id = $_SESSION['login_id'] ?? 0; // Fallback to 0 if session var isn't set
        if (!$reviewer_id) {
            return 'error: reviewer_id not set';
        }

        // Begin transaction
        $this->db->begin_transaction();

        try {
            foreach ($grades as $grade) {
                $c_id = $grade['c_id'];
                $grade_value = $grade['grade'];

                // Check if the grade already exists
                $check_sql = "SELECT * FROM grades WHERE paper_id = ? AND c_id = ? AND reviewer_id = ?";
                $check_stmt = $this->db->prepare($check_sql);
                $check_stmt->bind_param("iii", $paper_id, $c_id, $reviewer_id);
                $check_stmt->execute();
                $result = $check_stmt->get_result();

                if ($result->num_rows == 0) {
                    // Insert the new grade
                    $insert_sql = "INSERT INTO grades (paper_id, c_id, grade, reviewer_id) VALUES (?, ?, ?, ?)";
                    $insert_stmt = $this->db->prepare($insert_sql);
                    $insert_stmt->bind_param("iiii", $paper_id, $c_id, $grade_value, $reviewer_id);
                    $insert_stmt->execute();
                } else {
                    // Handle the case where the reviewer has already submitted grades
                    // For now, we'll just skip the existing grades. 
                    $sql = "UPDATE grades SET grade = ? WHERE paper_id = ? AND reviewer_id = ? AND c_id = ?";
					$update_stmt = $this->db->prepare($sql);
					$update_stmt->bind_param("iiii", $grade_value, $paper_id, $reviewer_id, $c_id);
					$update_stmt->execute();
                    //continue; // Or handle accordingly
                }
            }

            // Commit transaction
            $this->db->commit();

            return 'success';
        } catch (mysqli_sql_exception $exception) {
            // Rollback transaction
            $this->db->rollback();

            // Log the error or handle it as needed
            $error_message = $exception->getMessage();
            return 'error: ' . $error_message;
        }
    } else {
        return 'invalid request';
    }
	}

	//////////////////////////////////////////////////////////
    public function upload_file($file, $user_id, $title, $abstract) {
		$upload_directory = 'uploads/';
		$max_file_size = 10485760; // 10 MB
		$allowed_types = array('pdf', 'doc', 'docx');

		$file_name = $file['name'];
		$file_tmp = $file['tmp_name'];
		$file_size = $file['size'];
		$file_error = $file['error'];

		// Convert bytes to MB for easier readability and storage
		$file_size_mb = $file_size / 1048576;

		// Secure way to handle file extension
		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

		if ($file_error === 0) {
			if ($file_size <= $max_file_size) {
				if (in_array($file_ext, $allowed_types)) {
					$new_filename = uniqid('', true) . '.' . $file_ext;
					$file_destination = $upload_directory . $new_filename;

					// Ensure upload directory exists
					if (!is_dir($upload_directory) && !mkdir($upload_directory, 0777, true)) {
						return 'Failed to create upload directory';
					}

					if (move_uploaded_file($file_tmp, $file_destination)) {
						// Insert file info into the database
						$sql = "INSERT INTO papers (author_id, title, abstract, paperurl, size, submission_date) VALUES (?, ?, ?, ?, ?, NOW())";
						$stmt = $this->db->prepare($sql);
						if ($stmt) {
							$stmt->bind_param('isssd', $user_id, $title, $abstract, $file_destination, $file_size_mb);
							if ($stmt->execute()) {
								return 'File uploaded successfully';
							} else {
								return 'Failed to save file info to database';
							}
						} else {
							return 'Database error: ' . $this->db->error;
						}
					} else {
						return 'Failed to move uploaded file';
					}
				} else {
					return 'Invalid file type. Allowed types: ' . implode(', ', $allowed_types);
				}
			} else {
				return 'File size exceeds the maximum limit of ' . ($max_file_size / 1048576) . ' MB';
			}
		} else {
			return 'There was an error uploading your file';
		}
	}


	function save_or_update_paper($data, $file) {
		$paper_id = isset($data['paper_id']) ? $data['paper_id'] : null;
		$title = $data['title'];
		$abstract = $data['abstract'];
		$key_words = $data['key_words']; // Ensure to use or validate key_words if needed

		if ($paper_id) {
			// Update logic
			return $this->update_paper($paper_id, $title, $abstract, $file['size'], 'In queue');
		} else {
			// Insert logic
			return $this->upload_file($file, $_SESSION['login_id'], $title, $abstract);
		}
	}

    // Function to get paper details including the submission date
	function get_paper_details($paper_id) {
		$stmt = $this->db->prepare("SELECT p.*, u.firstname, u.lastname, p.submission_date FROM papers p JOIN users u ON p.author_id = u.id WHERE p.paper_id = ?");
		$stmt->bind_param("i", $paper_id);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->fetch_assoc();
	}


    // Function to update paper details
    function update_paper($paper_id, $title, $abstract, $file_size, $review_status) {
		// Convert size from bytes to MB if not already done
		$file_size_mb = $file_size / 1048576;

		$stmt = $this->db->prepare("UPDATE papers SET title = ?, abstract = ?, size = ?, review_status = ? WHERE paper_id = ?");
		$stmt->bind_param("ssdsi", $title, $abstract, $file_size_mb, $review_status, $paper_id);
		$stmt->execute();
		return $stmt->affected_rows > 0;
	}


    // Function to delete a paper
	function delete_paper($paper_id) {
		$stmt = $this->db->prepare("DELETE FROM papers WHERE paper_id = ?");
		$stmt->bind_param("i", $paper_id);
		if($stmt->execute()) {
			return 1;
		} else {
			return $this->db->error;
		}
	}
	////////////////////////////////////////////////Group\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	public function add_group($group_name, $members) {
		// Check if group name already exists
		$stmt = $this->db->prepare("SELECT * FROM groups WHERE group_name = ?");
		$stmt->bind_param("s", $group_name);
		$stmt->execute();
		if($stmt->get_result()->num_rows > 0) {
			return "Group name already exists.";
		}

		// Add new group
		$stmt = $this->db->prepare("INSERT INTO groups (group_name) VALUES (?)");
		$stmt->bind_param("s", $group_name);
		if($stmt->execute()) {
			$group_id = $this->db->insert_id;
			foreach ($members as $member_id) {
				$member_stmt = $this->db->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
				$member_stmt->bind_param("ii", $group_id, $member_id);
				$member_stmt->execute();
			}
			return "1";
		}
		return "0";
	}

	public function update_group($group_id, $group_name, $members) {
		// Update group name
		$stmt = $this->db->prepare("UPDATE groups SET group_name = ? WHERE group_id = ?");
		$stmt->bind_param("si", $group_name, $group_id);
		$stmt->execute();
		
		// Update members
		if($stmt->affected_rows) {
			// Remove existing members
			$stmt = $this->db->prepare("DELETE FROM group_members WHERE group_id = ?");
			$stmt->bind_param("i", $group_id);
			$stmt->execute();

			// Add new members
			foreach ($members as $member_id) {
				$member_stmt = $this->db->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
				$member_stmt->bind_param("ii", $group_id, $member_id);
				$member_stmt->execute();
			}
			return "1";
		}
		return "0";
	}

	public function delete_group($group_id) {
		// Delete group members first
		$stmt = $this->db->prepare("DELETE FROM group_members WHERE group_id = ?");
		$stmt->bind_param("i", $group_id);
		$stmt->execute();

		// Then delete the group
		$stmt = $this->db->prepare("DELETE FROM groups WHERE group_id = ?");
		$stmt->bind_param("i", $group_id);
		$stmt->execute();

		return $stmt->affected_rows > 0 ? "1" : "0";
	}

	// Leave group!!!
	// Add this method to your Action class in admin_class.php
	public function leave_group($group_id, $user_id) {
		$stmt = $this->db->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
		$stmt->bind_param("ii", $group_id, $user_id);
		if($stmt->execute()) {
			return 1; // Success
		} else {
			return 0; // Failure
		}
	}

//////////////////////////////////////////////////////////////////Share Papers
    // Function to share a paper with groups or individuals
	public function share_paper($paper_id, $share_type, $group_ids = [], $user_ids = [], $deadline, $is_anonymous, $author_id) {
		$is_anonymous = $is_anonymous ? 1 : 0;
		$share_date = date('Y-m-d H:i:s');
		$errors = [];

		try {
			if ($share_type === 'all') {
				$reviewers = $this->db->query("SELECT id FROM users WHERE user_type = 2"); // Assuming '2' is for reviewers
				while ($reviewer = $reviewers->fetch_assoc()) {
					$stmt_upsert = $this->db->prepare("INSERT INTO paper_shares (paper_id, share_type, user_id, author_id, share_date, deadline, is_anonymous) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE share_date = VALUES(share_date), deadline = VALUES(deadline), is_anonymous = VALUES(is_anonymous)");
					$stmt_upsert->bind_param("isisssi", $paper_id, $share_type, $reviewer['id'], $author_id, $share_date, $deadline, $is_anonymous);
					$stmt_upsert->execute();
				}
			} elseif ($share_type === 'group') {
				foreach ($group_ids as $group_id) {
					$stmt_upsert = $this->db->prepare("INSERT INTO paper_shares (paper_id, share_type, group_id, author_id, share_date, deadline, is_anonymous) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE share_date = VALUES(share_date), deadline = VALUES(deadline), is_anonymous = VALUES(is_anonymous)");
					$stmt_upsert->bind_param("isiissi", $paper_id, $share_type, $group_id, $author_id, $share_date, $deadline, $is_anonymous);
					$stmt_upsert->execute();
				}
			} elseif ($share_type === 'individual') {
				foreach ($user_ids as $user_id) {
					$stmt_upsert = $this->db->prepare("INSERT INTO paper_shares (paper_id, share_type, user_id, author_id, share_date, deadline, is_anonymous) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE share_date = VALUES(share_date), deadline = VALUES(deadline), is_anonymous = VALUES(is_anonymous)");
					$stmt_upsert->bind_param("isisssi", $paper_id, $share_type, $user_id, $author_id, $share_date, $deadline, $is_anonymous);
					$stmt_upsert->execute();
				}
			}

			if (!empty($errors)) {
				return implode("\n", $errors);
			}
			return true;  // Assuming successful sharing if no errors
		} catch (Exception $e) {
			error_log("Error sharing paper: " . $e->getMessage());
			return false;
		}
	}



	// Helper function to check if a paper has already been shared with a specific user
	private function already_shared($paper_id, $user_id) {
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM paper_shares WHERE paper_id = ? AND user_id = ?");
		$stmt->bind_param("ii", $paper_id, $user_id);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		return $count > 0;
	}
	// Helper function to check if a paper has already been shared with a specific group
	private function already_shared_group($paper_id, $group_id) {
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM paper_shares WHERE paper_id = ? AND group_id = ?");
		$stmt->bind_param("ii", $paper_id, $group_id);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		return $count > 0;
	}
		/////////////////////////////////////////////////My Shared Papers
		public function getMySharedPapers($user_id) {
		$papers = [];
		$query = "SELECT ps.share_id, p.title, ps.share_type, ps.share_date, ps.deadline, ps.review_status, GROUP_CONCAT(u.firstname SEPARATOR ', ') AS reviewers
				  FROM paper_shares ps
				  LEFT JOIN papers p ON ps.paper_id = p.paper_id
				  LEFT JOIN users u ON ps.user_id = u.id
				  WHERE p.author_id = ?
				  GROUP BY ps.share_id";

		$stmt = $this->db->prepare($query);
		$stmt->bind_param("i", $user_id);
		$stmt->execute();
		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$row['status_class'] = $this->getStatusClass($row['review_status']);
			$row['shared_with'] = $this->formatShareType($row['share_type'], $row['reviewers']);
			$papers[] = $row;
		}
		return $papers;
	}

	private function getStatusClass($status) {
		switch($status) {
			case 'In queue': return 'text-warning';
			case 'Reviewed': return 'text-success';
			case 'Rejected': return 'text-danger';
			default: return '';
		}
	}

	public function formatShareType($type, $names) {
		switch($type) {
			case 'all': return 'All Reviewers';
			case 'group': return 'Group(s)' . $names; // Adjust to fetch group names if needed
			case 'individual': return $names;
			default: return 'N/A';
				//error_log("Unsupported action attempted: " . $action);				
		}
	}
	

	// Method to cancel sharing a paper
	public function cancelSharing($share_id) {
		$stmt = $this->db->prepare("DELETE FROM paper_shares WHERE share_id = ?");
		$stmt->bind_param("i", $share_id);
		if($stmt->execute()) {
			return "1"; // Success
		} else {
			return "0"; // Failure
		}
	}
///////////////////////////////////////////////////////Total Shared Papers\\\\\\\\\\\\\\\\\\\\\
public function totalSharedPapers() {
    $papers = [];
    $query = "SELECT ps.share_id, p.title, ps.share_type, au.firstname AS author_name, 
              ps.share_date, ps.deadline, ps.review_status, 
              GROUP_CONCAT(u.firstname SEPARATOR ', ') AS reviewers
              FROM paper_shares ps
              LEFT JOIN papers p ON ps.paper_id = p.paper_id
              LEFT JOIN users au ON p.author_id = au.id
              LEFT JOIN users u ON ps.user_id = u.id
              GROUP BY ps.share_id";

    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['status_class'] = $this->getStatusClass($row['review_status']);
        $row['shared_with'] = $this->formatShareType($row['share_type'], $row['reviewers']);
        $papers[] = $row;
    }
    return $papers;
}

	
//////////////////////////////////////////////To Review\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	// In admin_class.php
	public function getSharedPapersWithUser($user_id) {
		$stmt = $this->db->prepare("
			SELECT ps.share_id, ps.paper_id, ps.share_type, ps.group_id, ps.share_date, ps.deadline, ps.user_id, ps.review_status, 
				   p.title, CONCAT(u.firstname, ' ', u.lastname) AS author_name, p.size, p.paperurl
			FROM paper_shares ps
			JOIN papers p ON ps.paper_id = p.paper_id
			JOIN users u ON p.author_id = u.id
			WHERE ps.user_id = ? OR ps.group_id IN (
				SELECT group_id FROM group_members WHERE user_id = ?
			)
		");
		$stmt->bind_param("ii", $user_id, $user_id);
		$stmt->execute();
		if (!$stmt) {
			error_log("SQL Error: " . $this->db->error);
			return false;
		}
		return $stmt->get_result();
	}

	public function reviewPaper($paper_id) {
		// Assuming there is a status to set when a paper review starts
		$stmt = $this->db->prepare("UPDATE papers SET review_status = 'In Review' WHERE paper_id = ?");
		$stmt->bind_param("i", $paper_id);
		if ($stmt->execute()) {
			return true;
		} else {
			error_log("Error setting paper to review: " . $stmt->error);
			return false;
		}
	}
	
	public function markAsReviewed($paper_id) {
		$stmt = $this->db->prepare("UPDATE paper_shares SET review_status = 'Reviewed' WHERE share_id = ?");
		$stmt->bind_param("i", $paper_id);
		if ($stmt->execute()) {
			return true;
		} else {
			error_log("Error updating review status: " . $stmt->error);
			return false;
		}
	}

    // Reject a paper
    public function rejectPaper($paper_id) {
		$stmt = $this->db->prepare("UPDATE paper_shares SET review_status = 'Rejected' WHERE share_id = ?");
        $stmt->bind_param("i", $paper_id);
		if ($stmt->execute()) {
			return true;
		} else {
			error_log("Error updating review status: " . $stmt->error);
			return false;
		}
    }

	public function hasUserReviewed($paper_id, $user_id) {
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM paper_shares WHERE paper_id = ? AND paper_shares.reviewer_id = ?");
		$stmt->bind_param("ii", $paper_id, $user_id);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		return $count > 0;
	}
	//////////////////////////////////////////// Statistics To access All papers!!!\\\\\\\\\\\\\\\\\\\\\\\\\\\
	 // Fetch average grades for all reviewers for a specific paper
    public function fetchAverageGrades($paper_id, $reviewer_id = null) {
		//foreach ($paper_ids as paper_id) {
        if ($reviewer_id) {
            $stmt = $this->db->prepare("
                SELECT cl.name AS criterion, g.grade AS average_grade
                FROM grades g
                JOIN criteria_list cl ON g.c_id = cl.c_id
                WHERE g.paper_id = ? AND g.reviewer_id = ?
                GROUP BY cl.c_id
            ");
            $stmt->bind_param("ii", $paper_id, $reviewer_id);
        } else {
            $stmt = $this->db->prepare("
                SELECT cl.name AS criterion, AVG(g.grade) AS average_grade
                FROM grades g
                JOIN criteria_list cl ON g.c_id = cl.c_id
                WHERE g.paper_id = ?
                GROUP BY cl.c_id
            ");
            $stmt->bind_param("i", $paper_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $grades = [];
        while ($row = $result->fetch_assoc()) {
            $grades[] = $row;
        }
        return $grades;
    }
	//}
       // Fetch all reviewers
    public function fetchAllReviewers() {
        $stmt = $this->db->prepare("SELECT id, CONCAT(firstname, ' ', lastname) AS name FROM users WHERE user_type = '2'");            
		//$stmt->bind_param("i", $paper_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reviewers = [];
        while ($row = $result->fetch_assoc()) {
            $reviewers[] = $row;
        }
        return $reviewers;
    }



		//////////////////////////////////////////// Statistics To access their own papers!!!\\\\\\\\\\\\\\\\\\\\\\\\\\\
	 public function fetchAuthoredPapers($author_id) {
        $stmt = $this->db->prepare("
            SELECT p.paper_id, p.title
            FROM papers p
            WHERE p.author_id = ?
        ");
        $stmt->bind_param("i", $author_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $papers = [];
        while ($row = $result->fetch_assoc()) {
            $papers[] = $row;
        }
        return $papers;
    }
	
	////////////////////////////////////Collaborative Review\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
	// Fetch paper information for collaborative review
    public function fetchPaperDetails($paper_id) {
        $stmt = $this->db->prepare("SELECT * FROM paper_shares WHERE paper_id = ?");
        $stmt->bind_param("i", $paper_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Fetch annotations for a specific paper, including reviewer names
	public function fetchAnnotations($paper_id, $share_id, $page) {
		$query = "SELECT a.*, u.firstname FROM annotations a
				  JOIN users u ON a.reviewer_id = u.id
				  WHERE a.paper_id = ? AND a.share_id = ? AND a.page = ?
				  ORDER BY a.annotation_id ASC";
		$stmt = $this->db->prepare($query);
		$stmt->bind_param("iii", $paper_id, $share_id, $page);
		$stmt->execute();
		return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}


    // Save an annotation or a reply
    public function saveAnnotation($data) {
        $stmt = $this->db->prepare("INSERT INTO annotations (paper_id, reviewer_id, share_id, type, content, page) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiissi", $data['paper_id'], $data['reviewer_id'], $data['share_id'], $data['type'], $data['content'], $data['page']);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Update an existing annotation
    public function updateAnnotation($annotation_id, $content) {
        $stmt = $this->db->prepare("UPDATE annotations SET content = ? WHERE annotation_id = ?");
        $stmt->bind_param("si", $content, $annotation_id);
        $stmt->execute();
        return $stmt->affected_rows;
    }
	
	///////////////////////////////Likes/Replies\\\\\\\\\\\\\
/*	public function fetch_reply_details() {
		$query = "SELECT r.reply_id, r.comment_id, r.replier_id, r.reply, u.firstname, u.lastname 
				  FROM annotation_replies r 
				  JOIN users u ON r.replier_id = u.id";
		$stmt = $this->db->query($query);
		$replies = [];
		while ($row = $stmt->fetch_assoc()) {
			$replies[] = $row;
		}
		return $replies;
	}
	public function fetch_like_details() {
		$query = "SELECT a.like_id, a.comment_id, a.reply_id, a.liker_id, u.firstname, u.lastname 
				  FROM annotation_likes a 
				  JOIN users u ON a.liker_id = u.id";
		$stmt = $this->db->query($query);
		$likes = [];
		while ($row = $stmt->fetch_assoc()) {
			$likes[] = $row;
		}
		return $likes;
	}

*/
	// Method to save a reply to an annotation
    public function save_reply() {
        extract($_POST);  // $comment_id, $reply
        $replier_id = $_SESSION['login_id'];  // Assuming logged in user's ID is stored in session
        $query = "INSERT INTO annotation_replies (comment_id, replier_id, reply) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        if ($stmt->execute([$comment_id, $replier_id, $reply])) {
            return json_encode(['success' => true, 'message' => 'Reply added successfully']);
        } else {
            return json_encode(['success' => false, 'message' => 'Failed to add reply']);
        }
    }

    // Method to save a like to a comment or reply
    public function save_like() {
		$comment_id = $_POST['comment_id'];
		$liker_id = $_SESSION['login_id']; // Ensure this is set at login

		// Check if the like already exists
		$checkQuery = "SELECT 1 FROM annotation_likes WHERE comment_id = ? AND liker_id = ?";
		$checkStmt = $this->db->prepare($checkQuery);
		$checkStmt->execute([$comment_id, $liker_id]);
		if ($checkStmt->fetch()) {
			// Fetch the current like count for no change scenario
			$likeCount = $this->fetch_likes_count($comment_id);
			return json_encode(['success' => true, 'message' => 'Already liked', 'likeCount' => $likeCount]);
		}

		// Insert the new like if not already liked
		$insertQuery = "INSERT INTO annotation_likes (comment_id, liker_id) VALUES (?, ?)";
		$insertStmt = $this->db->prepare($insertQuery);
		if ($insertStmt->execute([$comment_id, $liker_id])) {
			$likeCount = $this->fetch_likes_count($comment_id);
			return json_encode(['success' => true, 'likeCount' => $likeCount, 'message' => 'Like added successfully']);
		} else {
			return json_encode(['success' => false, 'message' => 'Failed to add like']);
		}
	}
	
	public function fetch_likes_count() {
		$query = "SELECT comment_id, COUNT(like_id) AS likes_count FROM annotation_likes GROUP BY comment_id";
		$stmt = $this->db->query($query);
		$likes = [];
		while ($row = $stmt->fetch_assoc()) {
			$likes[$row['comment_id']] = $row['likes_count'];
		}
		return $likes;
	}

/////////////////////////////////////////Notification Area-_-_-_-_-_-_
public function sendNotification() {
        // Check if the required data is provided
        if (!isset($_POST['user_id']) || !isset($_POST['message'])) {
            echo json_encode(array('success' => false, 'message' => 'Incomplete data provided.'));
            exit;
        }

        // Get user ID and message from the POST data
        $userId = $_POST['user_id'];
        $message = $_POST['message'];

        // Perform any necessary checks or validations before sending the notification

        // Here, you can implement the logic to send the notification, such as saving it to a notifications table in the database,
        // or using a messaging service like Firebase Cloud Messaging (FCM) for push notifications.

        // For demonstration purposes, let's just return a success message
        echo json_encode(array('success' => true, 'message' => 'Notification sent successfully.'));
    }

function checkNotifications() {
    // Perform database query to get the notification count
    // Example:
    $notificationCount = 10; // Fetch the count from the database
    
    // Return the count as a JSON response
    echo json_encode(array('success' => true, 'count' => $notificationCount));
    exit;
}

	
}
?>