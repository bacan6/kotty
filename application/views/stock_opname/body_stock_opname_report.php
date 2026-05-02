<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"></h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">
            <div class="portlet-widgets">
              <a class="btn btn-primary" onclick="printContent('print-area')"><i class="fa fa-print"></i> Print</a>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" id="print-area">
              <div class="row">
              	<div class="col-md-12">
              		<table width="100%">
                   
                    <?php
                      if($header_so->type==1){
                    ?>

                        <?php
                            foreach($header->result() as $hd){
                        ?>

                        <tr>
                            <td style="text-align: center;">
                                <h5>Laporan Stock Opname</h5>
                                <h4>Gudang</h4> 
                            </td>
                        </tr>
                        <?php } ?>

                    <?php } else { ?>
                      <tr>
                            <td style="text-align: center;">
                                <h5>Laporan Stock Opname</h5>
                                <h4><?php echo $this->model1->namaStore($header_so->store); ?></h4> 
                            </td>
                        </tr>
                    <?php } ?>
                    </table>
              	</div>
              </div>

              <div class="row">
              	<div class="col-md-12">
              		<table width="100%">
              			<tr>
              				<td width="15%">No SO</td>
              				<td width="1%">:</td>
              				<td><?php echo $header_so->no_so; ?></td>
              			</tr>

              			<tr>
              				<td width="15%">Tanggal</td>
              				<td width="1%">:</td>
              				<td>
              					<?php 
              						$date = date_create($header_so->tanggal);

              						echo date_format($date,'d M Y'); 
              					?>
              				</td>
              			</tr>

              			<tr>
              				<td width="15%">Keterangan</td>
              				<td width="1%">:</td>
              				<td><?php echo $header_so->keterangan; ?></td>
              			</tr>
              		</table>

              		<table style="font-size: 12px;" border="1" width="100%">
              			<tr style="font-size: 12px;font-weight: bold;">
              				<td width="5%">No</td>
              				<td width="15%">SKU</td>
              				<td>Item</td>
              				<td width="10%" align="right">Stok Sistem</td>
              				<td width="10%" align="right">Fisik</td>
              				<td width="10%" align="right">Selisih</td>
              			</tr>

              			<?php
              				$i=1;
              				foreach($item_so->result() as $dt){
              			?>
              			<tr>
              				<td><?php echo $i; ?></td>
              				<td><?php echo $dt->sku; ?></td>
              				<td>
                        <?php 
                          echo $dt->nama_produk;
                        ?>
                      </td>
              				<td align="right"><?php echo number_format($dt->last_stok,'2',',','.'); ?></td>
              				<td align="right"><?php echo number_format($dt->new_stok,'2',',','.'); ?></td>
              				<td align="right"><?php echo number_format($dt->new_stok-$dt->last_stok,'2',',','.'); ?></td>
              			</tr>
              			<?php $i++; } ?>
              		</table>
              	</div>
              </div>

            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
