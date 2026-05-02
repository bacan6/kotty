<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
                SO Periode 
                <?php
                	$month_array = array(
                							"01" => "Januari",
                							"02" => "Februari",
                							"03" => "Maret",
                							"04" => "April",
                							"05" => "Mei",
                							"06" => "Juni",
                							"07" => "Juli",
                							"08" => "Agustus",
                							"09" => "September",
                							"10" => "Oktober",
                							"11" => "Nopember",
                							"12" => "Desember"
                					    );

                	echo $month_array[$month].' '.$tahun;
                ?>
            </h3>
            
            <div class="portlet-widgets">
                <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                <span class="divider"></span>
                <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                	<div class="col-md-12">
                		<form action="<?php echo base_url('stock_opname/index'); ?>" method="get">
	                		<div class="form-inline" align="right">
	                			<div class="form-group">
	                				<select class="form-control" name="bulan">
		                				<option value="01">Januari</option>
		                				<option value="02">Februari</option>
		                				<option value="03">Maret</option>
		                				<option value="04">April</option>
		                				<option value="05">Mei</option>
		                				<option value="06">Juni</option>
		                				<option value="07">Juli</option>
		                				<option value="08">Agustus</option>
		                				<option value="09">September</option>
		                				<option value="10">Oktober</option>
		                				<option value="11">Nopember</option>
		                				<option value="12">Desember</option>
	                				</select>
	                			</div>

	                			<div class="form-group">
	                				<select class="form-control" name="tahun">
	                					<?php
	                						$x=2009;
	                						for($x=date('Y');$x>2009;$x--){
	                					?>
	                					<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
	                					<?php } ?>
	                				</select>
	                			</div>

	                			<div class="form-group">
	                				<input type="submit" class="btn btn-default" value="Submit"/>
	                			</div>
	                		</div>
                		</form>
                	</div>
                </div>

                <div class="row" style="margin-top: 20px;">
                	<div class="col-md-12">
                		<table class="table table-bordered table-striped" style="font-size:12px;">
                			<tr style="background: #2A303A;color:white;font-weight: bold;">
                				<td width="5%" align="center">No</td>
                				<td>SKU</td>
                				<td>Nama Bahan</td>
                				<td align="right">Harga Average</td>
                				<td align="right">Stok Sistem</td>
                				<td align="right">Stok Actual</td>
                				<td align="right">Selisih</td>
                			</tr>

                			<?php
                				$i=1;
                				foreach($hasil_so->result() as $row){
                					$sku = $row->sku;
                			?>
                			<tr>
                				<td><?php echo $i; ?></td>
                				<td><?php echo $row->sku; ?></td>
                				<td><?php echo $row->nama_bahan; ?></td>
                				<td align="right">
                					<?php
                						$harga_avg = $this->model1->harga_average_permonth($bulan,$tahun,$sku);

                						echo number_format($harga_avg,'0',',','.');
                					?> IDR
                				</td>
                				<td align="right">
                					<?php
                						echo $row->last_stok;
                					?>
                				</td>
                				<td align="right">
                					<?php 
                						echo $row->new_stok;
                					?>
                				</td>
                				<td align="right">
                					<?php
                						echo $row->last_stok-$row->new_stok;
                					?>
                				</td>
                			</tr>
                			<?php $i++; } ?>
                		</table>
                	</div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
