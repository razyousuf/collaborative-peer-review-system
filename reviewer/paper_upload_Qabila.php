<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<div class="col-lg-12">	
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_paper" method = "POST" enctype="multipart/form-data">
				<input type="hidden" name="paper_id"  value="<?php echo isset($id) ? $paper_id : '';  ?>">
                <input type="hidden" name="author_id"  value="<?php echo isset($id) ? $author_id : $_SESSION['login_id'];  ?>">
                <input type="hidden" name="paper_status"  value="<?php echo isset($id) ? $paper_status : 'In queue';  ?>">
                <input type="hidden" name="size"  value="<?php echo isset($id) ? $size : '0';  ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Paper Title</label>
							<input type="text" name="paper_title" class="form-control form-control-sm" required value="<?php echo isset($paper_title) ? $paper_title:'' ?> " >
						</div>
                        <div class="form-group">
							<label class="control-label">Key Words</label>
							<input type="text" class="form-control form-control-sm" name="key_words"  value="<?php echo isset($key_words) ? $key_words : '' ?>">
                            <small><i>Please seprate with commas.</i></small>
						</div>
                        <div class="form-group">
							<label for="" class="control-label">Paper</label>
							<div class="custom-file">
		                      <input type="file" class="custom-file-input" id="customFile"  required name="paperurl" >
		                      <label class="custom-file-label" for="customFile">Choose file</label>
		                    </div>
						</div>						
						<div class="form-group">
							<label class="control-label">Author Type</label>
                            <select name="author_type" id="" class="custom-select custom-select-sm" required>
                                <option value="public">Public</option>
                                <option value="anonymus">Anonymus</option>
                            </select>							
						</div>
                        <div class = "form-group">
                            <label for="Deadline">Review Deadline:</label><br>
                            <input type="datetime-local" id="review_deadline" required name="review_deadline" class="form-control form-control-sm">
                        </div>
					</div>
					<div class="col-md-6">
                    <div class="form-group">
							<label for="" class="control-label">Abstract</label>
							<textarea rows="15" name="abstract" class="form-control form-control-sm"  value=""><?php echo isset($abstract) ? $abstract : '' ?></textarea> 
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=home'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>	
	$('#manage_paper').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()		
        if($('[name="paper_title"]').val() == ''){
            $('[name="paper_title"]').addClass("border-danger")
					end_load()
					return false;
        }else{
            $('[name="paper_title"]').removeClass("border-danger")
        }
        if($('[name="paperurl"]').val() == ''){
            $('[name="paper_url"]').addClass("border-danger")
					end_load()
					return false;
        }else{
            $('[name="paperurl"]').removeClass("border-danger")
        }        		
		$.ajax({
			url:'ajax.php?action=save_paper',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=home')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>This paper is already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}else{
					_conf("An error occured, please check all info and try again later." + resp,"Error",[$(this).attr('data-id')])
					setTimeout(function(){
						//location.reload()
					},)//1500)
				}				
			}
		})
	})
</script>