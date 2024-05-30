<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_subject" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="10%">
					<col width="45%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Paper Title</th>
						<th>Author</th>						
						<th>Reviewer</th>
						<th>Score</th>
						<th>Status</th>						
					
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM paper_review_info WHERE paper_Status = 'reviewed' OR 1=1 ORDER BY paper_id asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>						
						<td><b><a data-id =<?php echo $row['paper_id'] ?> class = 'manage_review' "><?php echo $row['title'] ?></a></b></td>
						<td><b><?php echo $row['author_name'] ?></b></td>						
						<td><b><?php echo $row['reviewer_name'] ?></b></td>
						<td><b><?php echo $row['score'] ?></b></td>
						<td><b><?php echo $row['status'] ?></b></td>
				
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.new_subject').click(function(){
			uni_modal("New subject","<?php echo $_SESSION['login_view_folder'] ?>manage_subject.php")
		})
		$('.manage_subject').click(function(){
			uni_modal("Manage subject","<?php echo $_SESSION['login_view_folder'] ?>manage_subject.php?id="+$(this).attr('data-id'))
		})
		$('.manage_review').click(function(){
			uni_modal("Manage review","<?php echo $_SESSION['login_view_folder'] ?>paper_reviews_list.php?id="+$(this).attr('data-id'))
		})
	$('.delete_subject').click(function(){
	_conf("Are you sure to delete this subject?","delete_subject",[$(this).attr('data-id')])
	})
		$('#list').dataTable()
	})
	function delete_subject($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_subject',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>