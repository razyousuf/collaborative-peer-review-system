<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_criteria"><i class="fa fa-plus"></i> Add New Crteria</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">ID</th>
						<th>Name</th>
						<th>Descriptoin</th>
						<th>Grading Scheme</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT
						criteria_list.c_id,
						criteria_list.name,
						criteria_list.description,
						criteria_list.grading_scheme
					FROM
						criteria_list
					ORDER BY
						criteria_list.c_id ASC");


					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td  required style="font-size: 14px;"><?php echo ucwords($row['name']) ?></td>
						<td  required style="font-size: 14px;"><?php echo $row['description'] ?></td>
						<td  required style="font-size: 14px;"><?php echo nl2br(htmlspecialchars($row['grading_scheme'])) ?></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
							  <div class="dropdown-menu" style="">
							  <div class="dropdown-divider"></div>
		                    <a class="dropdown-item" href="./index.php?page=edit_criteria&c_id=<?php echo $row['c_id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                    <a class="dropdown-item delete_criteria" href="javascript:void(0)" data-id="<?php echo $row['c_id'] ?>">Delete</a>
		                    </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<script>
	$(document).ready(function(){
	
	$('.delete_criteria').click(function(){
	_conf("Are you sure to delete this criteria?","delete_criteria",[$(this).attr('data-id')])
	})
	$('#list').dataTable()
	})
	function delete_criteria($c_id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_criteria',
			method:'POST',
			data:{c_id:$c_id},
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