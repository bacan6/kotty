<br>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Jenis</td>
        <td>Voucher</td>
        <td>Nama Voucer</td>
        <td>Tgl Berlaku</td>
        <td>Nilai</td>
        <td>Jumlah</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($voucer->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
      <td><?php echo (isset($row->voucher_struk) && (int)$row->voucher_struk === 1)
        ? '<span class="label label-warning">Struk</span>'
        : '<span class="label label-info">Cetak</span>'; ?></td>
      <td><?php if (isset($row->voucher_struk) && (int)$row->voucher_struk === 1) { ?>
        <span class="text-muted">—</span>
      <?php } else { ?>
      <a href="<?php echo base_url('Voucergenerate/show_voucer/'); ?><?php echo $row->id_generate; ?>" class="btn btn-success btn-xs">Lihat Voucher</a>
      <?php } ?></td>
   		<td><?php echo $row->nm_voucher; ?></td>
      <td><?php echo date("d F Y H:i",strtotime($row->berlaku_mulai)); ?> - <?php echo date("d F Y H:i",strtotime($row->berlaku_selesai)); ?></td>
      <td><?php echo (isset($row->nilai_tipe) && $row->nilai_tipe === 'percent') ? htmlspecialchars($row->nilai).'%' : number_format($row->nilai); ?></td>
      <td><?php echo $row->jml_voucher; ?></td>
   		<td>
        <a href="#edit-voucer-modal" data-toggle="modal" class="edit_voucer" id="<?php echo $row->id_generate; ?>"><i class="fa fa-pencil"></i></a> |
        <a class="hapus_voucer" id="<?php echo $row->id_generate; ?>" ><i class="fa fa-trash"></i></a> 
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<div class="modal fade" id="edit-voucer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit voucer</h4>
      </div>
        <div id="body-edit-voucer"></div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('#datatable').dataTable();

  $(document).on("click",".edit_voucer",function(){
    id = this.id;

    url = "<?php echo base_url('Voucergenerate/form_edit_voucer'); ?>";

    $('#body-edit-voucer').load(url,{id : id});
  });

  
    $(document).on("click",".hapus_voucer",function(){
    id = this.id;

    url = "<?php echo base_url('Voucergenerate/hapus_voucer'); ?>";

    swal({
      title: "Hapus batch voucher?",
      text: "Seluruh data batch ini akan dihapus dan tidak dapat dikembalikan.",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Ya, hapus",
      cancelButtonText: "Batal",
      closeOnConfirm: false
    },
    function(){
      $.post(url,{id : id}, function(){
        var u = (typeof window.getVoucherListUrl === 'function') ? window.getVoucherListUrl() : "<?php echo base_url('Voucergenerate/data_voucer'); ?>";
        $('#data-voucer').load(u);
      });
      swal("Terhapus", "Data voucher telah dihapus.", "success");
    });
  });
</script>