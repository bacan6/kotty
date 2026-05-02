<?php
	foreach($riwayatPenerimaan as $row){
?>
<tr>
	<td><a href="<?php echo base_url('penerimaanWorkOrder/invoiceReceive?no_receive='.$row->no_receive); ?>" target="__blank"><?php echo $row->no_receive; ?></a></td>
	<td><?php echo $row->received_by; ?></td>
	<td><?php echo $row->checked_by; ?></td>
	<td><?php echo date_format(date_create($row->tanggal_terima),'d/m/y'); ?></td>
	<td>
		<?php 
      		if($row->diterimaDi==0){
      			echo "Gudang";
      		} else {
      			echo $this->model1->namaStore($row->diterimaDi);
      		}
      	?>
	</td>
</tr>
<?php } ?>