<?php include('db_connect.php');
function ordinal_suffix1($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
$astat = array("Not Yet Started","Started","Closed");
 ?>

<div class="col-12">
    <div class="card">
      <div class="card-body">
        Welcome <?php echo $_SESSION['login_name']. '! '.$_SESSION['login_view_folder'];
			if($_SESSION['login_user_type']==1){
				echo 'you have full access '. $_SESSION['login_user_type'];
			}elseif($_SESSION['login_user_type']==2){
				echo 'you have partial access'. $_SESSION['login_user_type'];
			}elseif($_SESSION['login_user_type']==3){
				echo 'you have limited access'.$_SESSION['login_user_type'];
			}else{
				echo 'the other type of user '. $_SESSION['login_user_type'];
			}
			echo $_SESSION['login_type'];
		?>
        <br>
        <div class="col-md-5">
          <div class="callout callout-info">
            <h5><b>Academic Year: <?php echo $_SESSION['academic']['year'].' '.(ordinal_suffix1($_SESSION['academic']['semester'])) ?> Semester</b></h5>
            <h6><b>Evaluation Status: <?php echo $astat[$_SESSION['academic']['status']] ?></b></h6>
          </div>
        </div>
      </div>
    </div>
</div>
