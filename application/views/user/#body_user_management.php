<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">User</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
				<?php if ($idUser<=2){?>
            	<div class="row">
            		<div class="col-md-12">
	            		<div class="form-inline" style="text-align: right;">
	            			<div class="form-group">
	            				<a href="<?php echo base_url('setting/tambah_user'); ?>" class="btn btn-primary">Tambah User</a>
	            			</div>
	            		</div>
            		</div>
            	</div>
					<?php } ?>
            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-12 table-borderedsponsive">
            			<table class="table table-bordered" id="myTable" style="font-size:12px;">
            				<thead>
	            				<tr style="font-weight: bold;">
							        <td width="5%" style="text-align: center;">No</td>
							        <td width="25%">Nama User</td>
							        <td>Kontak</td>
							        <td>Email</td>
							        <td>Status</td>
							        <td width="6%"></td>
							   	</tr>
						   </thead>

						   <tbody>
							   	<?php 
							   		$i=1;
							   		foreach($user->result() as $row){
							   	?>
							   	<tr>
							   		<td style="text-align: center; "><?php echo $i; ?></td>
							   		<td><?php echo $row->first_name." ".$row->last_name; ?></td>
							   		<td><?php echo $row->phone; ?></td>
							   		<td><?php echo $row->email; ?></td>
							   		<td><?php if($row->active==1){echo"Aktif";} else {echo "Non Aktif";} ?></td>
							   		<td style="text-align: center;"><a href="<?php echo base_url('setting/editUser?id_user='.$row->id); ?>"><i class="fa fa-edit"></i></a></td>
							   	</tr>
							   	<?php $i++; } ?>
						   </tbody>
            			</table>
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>