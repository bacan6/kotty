<table class="table table-bordered table-striped" style="font-size: 10px;">
	<tr style="background: #2A303A;color:white;font-weight: bold;">
		<td width="5%" align="center">No</td>
		<td width="10%">No Request</td>
		<td>User Request</td>
		<td>Nama Item</td>
		<td>Tanggal Request</td>
		<td width="10%">Jumlah Request</td>
		<td width="10%">Satuan</td>
		<td width="5%"></td>
	</tr>

	<?php
		$i = 1;

		$count = $wait_approve->num_rows();

		if($count > 0){
		foreach($wait_approve->result() as $row){
	?>
	<tr>
		<td align="center"><?php echo $i; ?></td>
		<td><?php echo $row->purchase_no; ?></td>
		<td><?php echo $row->nama_user; ?></td>
		<td><?php echo $row->nama_bahan; ?></td>
		<td><?php echo date_format(date_create($row->tanggal_request),'d M Y H:i'); ?></td>
		<td><?php echo $row->qty; ?></td>
		<td><?php echo $row->satuan; ?></td>
		<td align="center"><a href="#modal-approval" data-toggle="modal" class="show-modal-request" id="<?php echo $row->purchase_no; ?>"><i class="fa fa-list-ul"></i></a></td>
	</tr>
	<?php $i++; } } else { ?>
	<tr>
		<td colspan="8" align="center">Belum ada Data Untuk Ditampilkan</td>
	</tr>
	<?php } ?>
</table>

<div id="modal-approval" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Purchase Request</h4>
            </div>

            <div class="modal-body" id="list-request">
                                               
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	$('.show-modal-request').on("click",function(){
		no_request = this.id;

		url = "<?php echo base_url('purchase_approval/item_list_request'); ?>";

		$('#list-request').load(url,{id_request : no_request});		
	});
</script>