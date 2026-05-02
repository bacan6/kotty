<div class="row">
	<div class="col-md-12">
		<div class="form-inline pull-right">
			<div class="form-group">
				<a href="<?php echo base_url('laporan/exportExcelLaporanPenjualanPerkasirDetail?start='.$start.'&end='.$end.'&idStore='.$idStore.'&idKasir='.$idKasir.'&idPayment='.$idPayment.''); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Detail to Excel</a>
        <a href="<?php echo base_url('laporan/exportExcelLaporanPenjualanPerkasirDetail2?start='.$start.'&end='.$end.'&idStore='.$idStore.'&idKasir='.$idKasir.'&idPayment='.$idPayment.''); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Struk to Excel</a>
			</div>

			<div class="form-group">
				<a class="btn btn-info" onclick="printContent('area-print')"><i class="fa fa-print"></i> Print</a>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top: 20px;">
	<div class="col-md-12 table-responsive" id="area-print">
                      <h3 style="text-align: center;">Laporan Penjualan Perkasir</h3>
                      <h3 style="text-align: center;"><?php echo $nama_kasir; ?></h3>
                      <h4 style="text-align: center;">Periode</h4>
                      <h5 style="text-align: center;"><?php echo date_format(date_create($start),'d F Y')." - ".date_format(date_create($end),'d F Y'); ?></h5>


                    <table class="table" style="font-size:11px;">
                      <tr style="font-weight: bold;">
                        <td width="4%">No</td>
                        <td width="13%">No Invoice</td>
                        <td>Tanggal</td>
                        <td>Tipe Bayar</td>
                        <td align="right">Subtotal</td>
                        <td align="right">Diskon Member</td>
                        <td align="right">Diskon</td>
                        <td align="right">Poin Reimburs</td>
                        <td align="right">Diskon Peritem</td>
                        <td align="right">Voucher</td>
                        <td align="right">Surcharge</td>
                        <td align="right">Total</td>
                      </tr>

                      <?php
                        $rows = $laporanPerkasir->num_rows();
                        error_reporting(0);
                        if($rows < 1){
                      ?>

                      <tr>
                        <td colspan="11" align="center">--BELUM ADA DATA UNTUK DI TAMPILKAN--</td>
                      </tr>
                      <?php } else { ?>

                      <?php
                        $i=1;
                        $subtotal         = 0;
                        $ongkir           = 0;
                        $diskon_ch        = 0;
                        $diskon           = 0;
                        $poin_reimburs    = 0;
                        $grand_total      = 0;
                        $diskon_otomatis  = 0;
                        $surcharge        = 0;
                        $voucher          = 0;
                        $grand_total_account = array();
                        $grand_total_payment_type = array();
                        foreach($laporanPerkasir->result() as $row){
                      ?>

                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><a href="#modalDetail" class="detailPenjualan" id="<?php echo $row->no_invoice; ?>" data-toggle="modal"><?php echo $row->no_invoice; ?></a></td>
                        <td><?php echo date_format(date_create($row->tanggal),'d/m/y H:i'); ?></td>
                        <td><?php echo $row->payment_type." ".$row->account; ?></td>
                        <td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon_free,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->poin_value,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon_otomatis,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->voucher,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->surcharge,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis+$row->voucher)+$row->surcharge,'0',',','.'); ?></td>
                      </tr>
                      <?php 
                          $i++; 
                          $subtotal         = $subtotal+$row->total;
                          $ongkir           = $ongkir+$row->ongkir;
                          $diskon_ch        = $diskon_ch+$row->diskon;
                          $diskon           = $diskon+$row->diskon_free;
                          $poin_reimburs    = $poin_reimburs+$row->poin_value;
                          $diskon_otomatis  = $diskon_otomatis+$row->diskon_otomatis;
                          $surcharge        = $surcharge+$row->surcharge;
                          $voucher          = $voucher+$row->voucher;
                          $grand_total      = $grand_total+(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis+$row->voucher))+$row->surcharge;
                          if ($row->tipe_bayar>1){
                            $row->account = empty($row->account)? '-':$row->account;
                            $grand_total_account[$row->account] += ($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis+$row->voucher)+$row->surcharge;
                          }
                          
                          $grand_total_payment_type[$row->payment_type] += ($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis+$row->voucher)+$row->surcharge;
                        } //end foreach
                      ?>

                      <!-- DECLARE TOTAL-->
                      <tr style="background: white;font-weight: bold;">
                        <td colspan="4" align="center">TOTAL</td>
                        <td align="right"><?php echo number_format($subtotal,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon_ch,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($poin_reimburs,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon_otomatis,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($voucher,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($surcharge,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($grand_total,'0',',','.'); ?></td>
                      </tr>

                      <?php 
                    foreach($grand_total_payment_type as $key => $value){
                      ?>
                      <!-- DECLARE BY PAYMENT TYPE-->
                      <tr style="background: white;font-weight: bold;">
                        <td colspan="11" align="right"><?php echo $key?></td>
                        <td align="right"><?php echo number_format($value,'0',',','.'); ?></td>
                        
                      </tr>
                    <?php
                  } // end foreach  payment type

                 
                    foreach($grand_total_account as $key2 => $value2){
                      ?> 
                      <!-- DECLARE BY PAYMENT TYPE-->
                      <tr style="background: white;">
                        <td colspan="11" align="right"><?php echo $key2?></td>
                        <td align="right"><?php echo number_format($value2,'0',',','.'); ?></td>
                        
                      </tr>

                   <?php } // end foreach account
                    
                    } ?>
                      
                    </table>
                  </div>
                  </div>
<script type="text/javascript">
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


