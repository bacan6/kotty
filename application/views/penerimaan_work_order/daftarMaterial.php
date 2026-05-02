<?php
      $x = 1;
	foreach($bahanBakuOrder as $dt){
?>
      <tr>
            <td><?php echo $x; ?></td>
            <td><?php echo $dt->nama_bahan; ?></td>
            <td>
            	<?php 
            		echo $dt->qty." "; 

            		$adjPermaterial = $this->modelWorkOrder->adjPermaterial($dt->sku,$noWO);

            		foreach($adjPermaterial as $row){
            			if($row->qty > 0){
            				echo "(+".$row->qty.")";
            			} else {
            				echo "(".$row->qty.")";
            			}
            		}
            	?>
            </td>
            <td><?php echo $dt->satuan; ?></td>
      </tr>
<?php $x++; } ?>