<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Data Reservasi</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
	            <div class="row">
	                <div class="col-md-12">
	                  <div class="form-group pull-right" style="width:300px;">
	                      <form action="<?php echo base_url('penjualan/data_reservasi'); ?>" method="get">
	                        <div class="input-group m-t-10">
	                            <input type="text" id="example-input2-group2" name="query" class="form-control" placeholder="Pencarian">
	                            <span class="input-group-btn">
	                              <button type="submit" class="btn btn-effect-ripple btn-primary">Search</button>
	                            </span>
	                        </div>
	                    </form>
	                  </div>
	                </div>
	              </div>

            	<div class="row">
            		<div class="col-md-12">
            			<table class="table table-bordered table-striped" style="font-size: 11px;">
                            <tr style="background: #2A303A;color:white;font-weight: bold;">
                                <td width="4%">No</td>
                                <td>No Reservasi</td>
                                <td>Atas Nama</td>
                                <td align="right">Subtotal</td>
                                <td align="right">Ongkir</td>
                                <td align="right">Diskon Produk</td>
                                <td align="right">Diskon</td>
                                <td align="right">Grand Total</td>
                                <td align="right">Down Payment</td>
                            </tr>

                            <?php
                            	$i = 1;
                            	foreach($daftar_penjualan as $row){
                            ?>
                           	<tr>
                           		<td><?php echo $i; ?></td>
                           		<td><a href="<?php echo base_url('penjualan/nota_reservasi?no_reservasi='.$row->no_reservasi); ?>"><?php echo $row->no_reservasi; ?></a></td>
                           		<td><?php echo $row->atas_nama; ?></td>
                           		<td align="right"><?php echo number_format($row->total_reservasi,'0',',','.'); ?></td>
                           		<td align="right"><?php echo number_format($row->ongkir,'0',',','.'); ?></td>
                           		<td align="right"><?php echo number_format($row->diskon_produk,'0',',','.'); ?></td>
                           		<td align="right"><?php echo number_format($row->diskon_promosi,'0',',','.'); ?></td>
                           		<td align="right"><?php echo number_format(($row->total_reservasi+$row->ongkir)-($row->diskon_produk+$row->diskon_promosi),'0',',','.'); ?> </td>
                           		<td align="right"><?php echo number_format($row->down_payment,'0',',','.'); ?></td>
                           	</tr>
                           	<?php $i++; } ?>
                        </table>
            		</div>
            	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
