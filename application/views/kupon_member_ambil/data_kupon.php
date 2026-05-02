<br>
<div class="col-lg-12" style="margin-bottom:10px;font-size:15px;font-family:'courier new';">
  ID Customer: <b><?php echo $id_customer; ?></b><br>
  Nama Customer: <b><?php echo $nama; ?></b><br>
  Jumlah Poin: <b><?php echo $poin; ?></b>
</div>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Gambar</td>
        <td>Nama kupon</td>
        <td>Tgl Berlaku</td>
        <td>Sisa/Jumlah</td>
        <td>Point</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($kupon->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
      <td><a href="<?php echo base_url('/'); ?>uploads/files/kupon/<?php echo $row->gambar; ?>" target="_blank"><img class="img-thumbnail" src="<?php echo base_url('/'); ?>uploads/files/kupon/<?php echo $row->gambar; ?>" style="width: 100px;"></td>
   		<td><b><?php echo $row->nm_kupon; ?></b></td>
      <td><?php echo date("d F Y H:i",strtotime($row->tgl_berlaku)); ?> - <?php echo date("d F Y H:i",strtotime($row->tgl_expired)); ?></td>
      <td><?php echo $row->sisa; ?> / <?php echo $row->jml; ?></td>
      <td><h4><?php echo $row->jml_point; ?></h4></td>
   		<td>
        <a href="#!" class="ambil_produk btn btn-info" id="<?php echo $row->id_kupon_member; ?>" data-id_kupon="<?php echo $row->id_kupon; ?>">Ambil Produk</a>
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>



<script type="text/javascript">

  $('#datatable').dataTable();

  
    $(document).on("click",".ambil_produk",function(){
    id = this.id;
    id_kupon = $(this).data('id_kupon');

    url = "<?php echo base_url('kupon_member_ambil/ambil_produk'); ?>";
    kupon    = "<?php echo base_url('kupon_member_ambil/data_kupon?id_customer='.$id_customer); ?>";

    swal({
      title: "Are you sure?",
      text: "Anda akan mengambil produk redeem ini.",
      type: "info",
      showCancelButton: true,
      confirmButtonColor: "green",
      confirmButtonText: "Ya, saya yakin!",
      closeOnConfirm: false
    },
    function(){
      var id_customer = $("#id_customer").val();
      $.post(url,{id : id,id_customer:id_customer,id_kupon:id_kupon}, function(data){
        $('#data-kupon').load(kupon);
        var urlRedirect = "<?php echo base_url('kupon_member_ambil/invoice?no_kupon='); ?>"+data;
        window.location.replace(urlRedirect);
      });
      swal("Ditukarkan!", "Pengambilan produk berhasil.", "success");
    });
  });
</script>