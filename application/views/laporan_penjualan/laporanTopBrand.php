<div class="row">
	<div class="col-md-12">
		<div class="form-inline pull-right">
			<div class="form-group">
        <div style="display:none;margin-bottom:20px" id="keypass">
          <!-- <a href="<?php echo base_url('laporan/exportExcelLaporanPenjualanPerkriteriaProdukPisahINV?dateStart='.$dateStart.'&dateEnd='.$dateEnd.'&toko='.$toko.'&tempat='.$tempat.'&customer='.$customer.'&kategori='.$kategori.'&subkategori='.$subkategori.'&subkategori2='.$subkategori2.'&id_supplier='.$id_supplier.'&id_brand='.$id_brand); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export to Excel (Pisah Per Invoice)</a>
          <a href="<?php echo base_url('laporan/exportExcelLaporanPenjualanPerkriteriaProduk?dateStart='.$dateStart.'&dateEnd='.$dateEnd.'&toko='.$toko.'&tempat='.$tempat.'&customer='.$customer.'&kategori='.$kategori.'&subkategori='.$subkategori.'&subkategori2='.$subkategori2.'&id_supplier='.$id_supplier.'&id_brand='.$id_brand); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export to Excel</a> -->
        </div>
        <!-- <a class="btn btn-con btn-success btn-rounded m-b-5" href="#openKey" data-toggle="modal"><i class="fa fa-cog"></i> Export Excel</a>
        <a href="<?php echo base_url('laporan/exportPDFLaporanPenjualanPerkriteriaProduk?dateStart='.$dateStart.'&dateEnd='.$dateEnd.'&toko='.$toko.'&tempat='.$tempat.'&customer='.$customer.'&kategori='.$kategori.'&subkategori='.$subkategori.'&subkategori2='.$subkategori2.'&id_supplier='.$id_supplier.'&id_brand='.$id_brand); ?>" class="btn btn-danger" target="_blank"><i class="fa fa-file-pdf-o"></i> Export to PDF</a> -->
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
                <td width="13%">Brand</td>
                <?php if($isAdmin==1){?><td align="right">Penjualan</td> <?php } ?>
            </tr>    

            <?php
                        $i = 1;
                        $profit = 0;
                        $diskon = 0;
                        $grandtotal = 0;

                        $count = $laporan->num_rows();

                        if($count > 0){

                        foreach($laporan->result() as $row){
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->brand; ?></td>
                        <?php if($isAdmin==1){?>
                          <td align="right"><?php echo number_format($row->t_harga_jual,'0',',','.'); ?></td> <?php } ?>
                      </tr>
                      <?php 
                        $total_terjual  = $total_terjual+($row->t_harga_jual);
                        $i++; 
                        } 
                      ?>
                      
<?php if($isAdmin==1){?>
                      <tr style="font-weight: bold;">
                        <td colspan="2" align="center">TOTAL</td>
                        <td align="right"><?php echo number_format($total_terjual,'0',',','.'); ?></td>
                      </tr>
<?php } ?>
                    <?php } else { ?>
                        <tr>
                          <td colspan="2" align="center">--BELUM ADA DATA UNTUK DITAMPILKAN--</td>
                        </tr>
                    <?php } ?>
		</table>
	</div>
</div>


<script type="text/javascript">
	$(".table-responsive").niceScroll();
  $('#btnCekPIN').on("click", function(){
      pin = $('#PIN').val();
      var urlCek = "<?php echo base_url('laporan/openKey'); ?>";

      $.ajax({
          type        : "POST",
          url         : urlCek,
          data        : {pin:pin},
          success     : function(data){
                          if(data==pin){
                              $('#keypass').show();
                          }else{
                              alert('Akses tidak ditemukan.');
                          }
                        $('#openKey').modal('hide');
          }
      });
  });
</script>
<div id="openKey" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cc"></i> Buka Akses</h4>
            </div>                             
            <div class="modal-body">
                <label>PIN</label>
                <input type="password" class="form-control" placeholder="xxx" id="PIN">
            </div>      
            <div class="modal-footer">
                <button class="btn btn-warning" id="btnCekPIN"><i class="fa fa-save"></i> Buka</button>
            </div>                            
        </div>

    </div>
</div>