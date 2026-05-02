<table class="table table-bordered table-striped" style="font-size: 10px;">
	<tr style="background: #2A303A;color:white;font-weight: bold;">
		<td width="5%" align="center">No</td>
		<td width="10%">No Request</td>
		<td>User Request</td>
		<td>Nama Item</td>
		<td>Tanggal Request</td>
		<td>Tanggal Approved</td>
		<td width="5%"></td>
	</tr>

	<?php
		$i = 1;

		$count = $approved_request->num_rows();

		if($count > 0){

		foreach($approved_request->result() as $row){
	?>
	<tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $row->purchase_no; ?></td>
		<td><?php echo $row->nama_user; ?></td>
		<td><?php echo $row->nama_bahan; ?></td>
		<td><?php echo date_format(date_create($row->tanggal_request),'d M Y H:i'); ?></td>
		<td><?php echo date_format(date_create($row->approved_date),'d M Y H:i'); ?></td>
		<td align="center"><a href="#approved-item" data-toggle="modal" class="approved-item" id="<?php echo $row->purchase_no; ?>"><i class="fa fa-list-ul"></i></a></td>
	</tr>
	<?php $i++; } } else { ?>

	<tr>
		<td align="center" colspan="7">Belum Ada Data Untuk Ditampilkan</td>
	</tr>

	<?php } ?>
</table>

<div id="approved-item" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Approved Request</h4>
            </div>

            <div class="modal-body" id="request-item-approve">
                                               
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	$('.approved-item').on("click",function(){
		id = this.id;
		url = "<?php echo base_url('purchase_approval/approved_item'); ?>";

		$('#request-item-approve').load(url,{id_request : id});
	});
</script>