<div class="row">
	<div class="col-md-12">
		<div class="form-inline pull-right">
			<div class="form-group">
				<a href="<?php echo base_url('laporan/exportExcelLaporanPenjualanPerkriteria?dateStart='.$dateStart.'&dateEnd='.$dateEnd.'&idKasir='.$idKasir.'&toko='.$toko.'&idCustomer='.$idCustomer.'&typeBayar='.$typeBayar.'&subAccount='.$subAccount); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export to Excel</a>
			</div>

			<div class="form-group">
				<a class="btn btn-info" onclick="printContent('area-print')"><i class="fa fa-print"></i> Print</a>
			</div>
		</div>
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-md-12 table-responsive" style="height: 600px;" id="area-print">
		<table class="table" style="font-size: 10px;">
							<tr style="font-weight: bold;">
		                          <td width="4%">No</td>
		                          <td width="12%">No Invoice</td>
		                          <td>Toko</td>
		                          <td>Tanggal</td>
		                          <td>Tipe Bayar</td>
		                          <td align="right">Subtotal</td>
		                          <td align="right">Ongkir</td>
		                          <td align="right">Diskon Member</td>
		                          <td align="right">Diskon</td>
		                          <td align="right">Poin Reimburs</td>
		                          <td align="right">Diskon Peritem</td>
		                          <td align="right">Total</td>
		                        </tr>

			                  <?php
		                        $i=1;
		                        $subtotal        = 0;
		                        $ongkir          = 0;
		                        $diskon_ch       = 0;
		                        $diskon          = 0;
		                        $poin_reimburs   = 0;
		                        $grand_total     = 0;
		                        $diskon_otomatis = 0;
		                        foreach($laporan->result() as $row){
		                      ?>

		                      <tr>
		                        <td><?php echo $i; ?></td>
		                        <td><a href="<?php echo base_url('laporan/invoice_penjualan?no_invoice='.$row->no_invoice); ?>" class="detailPenjualan" id="<?php echo $row->no_invoice; ?>"><?php echo $row->no_invoice; ?></a></td>
		                        <td><?php echo $row->store; ?></td>
		                        <td><?php echo date_format(date_create($row->tanggal),'d/m/y H:i'); ?></td>
		                        <td><?php echo $row->payment_type." ".$row->account; ?></td>
		                        <td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($row->ongkir,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($row->diskon_free,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($row->poin_value,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($row->diskon_otomatis,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis),'0',',','.'); ?></td>
		                      </tr>
		                      <?php 
		                          $i++; 
		                          $subtotal         = $subtotal+$row->total;
		                          $ongkir           = $ongkir+$row->ongkir;
		                          $diskon_ch        = $diskon_ch+$row->diskon;
		                          $diskon           = $diskon+$row->diskon_free;
		                          $poin_reimburs    = $poin_reimburs+$row->poin_value;
		                          $diskon_otomatis  = $diskon_otomatis+$row->diskon_otomatis;
		                          $grand_total      = $grand_total+(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis));
		                        } //end foreach
		                      ?>

		                      <!-- DECLARE TOTAL-->
		                      <tr style="background: white;font-weight: bold;">
		                        <td colspan="5" align="center">TOTAL</td>
		                        <td align="right"><?php echo number_format($subtotal,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($ongkir,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($diskon_ch,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($diskon,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($poin_reimburs,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($diskon_otomatis,'0',',','.'); ?></td>
		                        <td align="right"><?php echo number_format($grand_total,'0',',','.'); ?></td>
		                      </tr>
		</table>
		</div>
</div>

<script type="text/javascript">
	$(".table-responsive").niceScroll();

	$('.detailPenjualan').on("click",function(){
	    var noInvoice = this.id;

	    var url = "<?php echo base_url('laporan/detailPenjualan'); ?>";

	    $.ajax({
	              method      : "POST",
	              data        : {noInvoice : noInvoice},
	              url         : url,
	              beforeSend  : function(){
	                              var imageUrl = "<?php echo base_url('assets/loading.gif'); ?>";
	                              $('#dataPenjualan').html("<table width='100%'><tr><td colspan='12' align='center'><img src='"+imageUrl+"'/></td></tr></table>");
	                            },
	              success     : function(data){
	                              $('#dataPenjualan').html(data);
	                            }
	    });
	  });
</script>

