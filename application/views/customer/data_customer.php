<br>

<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
      <td width="5%" align="center">No</td>
      <td width="20%">Nama</td>
      <td>Kontak</td>
      <td>Tgl Lahir</td>
      <td width="10%">Tanggal Input</td>
      <td width="25%">Alamat</td>
      <td>Kategori</td>
      <td width="10%">Diskon (%)</td>
      <td width="10%">Point</td>
      <td width="10%">Aktif?</td>
      <td width="12%"></td>
    </tr>
</thead>
<tbody>

   	<?php
   		$i=1;
   		foreach($customer->result() as $row){
   	?>
   	<tr <?php echo ($row->activated=='1'? "":"bgcolor=grey"); ?>>
        <td align="center"><?php echo $i; ?></td>
        <td><?php echo $row->nama; ?></td>
        <td><?php echo $row->kontak; ?></td>
        <td><?php echo $row->tanggal_lahir; ?></td>
        <td><?php echo date_format(date_create($row->tanggal_gabung),'d M Y'); ?></td>
        <td><?php echo $row->alamat." - ".$row->nama_provinsi." - ".$row->nama_kabupaten." - ".$row->kecamatan; ?></td>
        <td><?php echo $row->group_customer; ?></td>
        <td><?php echo $row->diskon; ?></td>
        <td><?php echo $row->point; ?></td>
        <td><?php echo ($row->activated=='1'? "Ya":"Tidak"); ?></td>
        <td align="center" >
          <div class="izin" style="display:none;">
            <a href="<?php echo base_url('customer/hapus_customer?id='.$row->id_customer); ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-icon btn-danger m-b-5"><i class="fa fa-trash"></i></a> <a href="<?php echo base_url('customer/edit_customer?id='.$row->id_customer); ?>" class="btn btn-icon btn-info m-b-5"><i class="fa fa-pencil"></i></a>
          </div>
          </td>
      </tr>
    <?php $i++; } ?>
    </tbody>
</table>

<script type="text/javascript">

  $('#datatable').dataTable();
</script>