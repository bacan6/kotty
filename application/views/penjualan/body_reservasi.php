<div id="payment_total_notif" style="width: 350px;position: fixed;z-index: 1;right: 8px;top:8px;display: none;" >
    <div class="alert alert-danger" style="opacity: 0.9;">
        <table width="100%" style="font-size: 18px;">
            <tr>
                <td width="50%">Total Belanja</td>
                <td align="right" id="total_belanja_notif"></td>
            </tr>

            <tr>
                <td width="50%">Jumlah Bayar</td>
                <td align="right" id="jumlah_bayar_notif"></td>
            </tr>

            <tr>
                <td width="50%">Kembali</td>
                <td align="right" id="kembali_notif"></td>
            </tr>
        </table>
    </div>
</div>


<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <form action="<?php echo base_url('penjualan/penjualan_reservasi_sql'); ?>" id="submit-penjualan" method="post">	
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <a href="<?php echo base_url('penjualan/data_reservasi'); ?>"> <i class="fa fa-book"></i> Data Reservasi </a>
                        </div>

                        <div class="col-md-12" style="text-align: right;margin-top: 30px;">
                            <button type="submit" class="btn btn-icon btn-primary m-b-5" id="button-submit"> <i class=" fa fa-check-square-o"></i> Submit</button>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                		<div class="col-md-8">
                			
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                        				<input type="hidden" id="produk-ajax" name="customer" style="width: 100%;">
                        			</div>

                        			<div class="form-group" style="margin-top: 20px;">
                        				<table class="table table-striped" style="font-size: 13px;">
                        					<thead>
            	            					<tr style="background: #2A303A;color:white;font-weight: bold;">
            	            						<td width="10%">SKU</td>
            	            						<td>Nama Produk</td>
            	            						<td width="13%" align="right">Harga Jual</td>
            	            						<td width="13%" align="center">Qty</td>
            	            						<td width="13%" align="right">Total Harga</td>
                                                    <td width="13%" align="right">Discount</td>
                                                    <td width="13%" align="right">Grand Total</td>
            	            						<td width="3%"></td>
            	            					</tr>
                        					</thead>

                        					<input type="hidden" id="sdf" value=0>
                        					<tbody id="data-input">
                        						<tr id="default">
                        							<td colspan="8" align="center">--Belum Ada Data--</td>
                        						</tr>
                        					</tbody>
                        				</table>
                        			</div>
                                </div>
                            </div>

                            <!--<div class="row" style="position:fixed;bottom:10px;">
                              <a href="#myModal" data-toggle="modal" class="btn btn-rounded m-b-5" style="background: #ddd;"><i class="fa fa-gift"></i> Free Item</a>
                              <a class="btn btn-rounded m-b-5" style="background: #ddd;"><i class="fa fa-credit-card"></i> Voucher</a>
                              <a class="btn btn-rounded m-b-5" style="background: #ddd;"><i class="fa fa-bank"></i> Tax</a>
                            </div>-->

                		</div>

                		<div class="col-md-4" style="border-left:solid 0.1px #ccc;padding-left: 15px;">
                			<div class="row">
                				<div class="col-md-12" style="border-bottom: solid 0.1px #ccc;">
                					<table class="table" style="font-size: 14px;">
                						<tr>
                							<td width="50%" style="font-weight: bold;color:#25aff4;"><i class="fa fa-crosshairs"></i> Subtotal</td>
                							<td id="total_purchase" align="right" style="font-weight: bold;color:#25aff4;"></td>
                						</tr>
                						<tr>
                							<td><i class="fa fa-car"></i> Ongkir</td>
                							<td id="ongkir_text" align="right"></td>
                						</tr>
                						<!--<tr>
                							<td><i class="fa fa-money"></i> Diskon Channel</td>
                							<td id="diskon_text" align="right"></td>
                						</tr>-->
                                        <tr>
                                            <td><i class="fa fa-bullhorn"></i> Diskon Promosi</td>
                                            <td id="diskon_promosi" align="right"></td>
                                        </tr>

                                        <tr>
                                            <td><i class="fa fa-bullhorn"></i> Diskon Otomatis</td>
                                            <td id="diskon_otomatis" align="right"></td>
                                        </tr>
                                        <!--<tr>
                                            <td><i class='fa fa-tree'></i> Poin Reimbursment</td>
                                            <td id="poin-value-reimburs" align="right"></td>
                                        </tr>-->
                						<tr>
                							<td style="color:#25aff4;"><i class='fa fa-bank'></i> <b>TOTAL</b></td>
                							<td id="grand_total" align="right" style="font-weight: bold;"></td>
                						</tr>
                					</table> 
                				</div>
                			</div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                                            <input type="text" placeholder="Atas Nama" class="form-control" name="atas_nama"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                            <input type="text" placeholder="No HP" class="form-control" name="no_hp"/>
                                        </div>
                                    </div>

                                    <div class="form-group" id="data-customer">

                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-car"></i></span>
                                            <input type="text" id="ongkir" name="ongkir" class="form-control" placeholder="Ongkir">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-minus"></i></span>
                                            <input type="text" class="form-control" placeholder="Diskon" id="diskon">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            <input type="text" class="form-control" placeholder="Down Payment" id="jumlah_bayar" name="down_payment" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                            <select class="form-control" name="type_bayar" id="type_bayar">
                                                <?php
                                                    foreach($payment_type->result() as $pt){
                                                ?>
                                                    <option value="<?php echo $pt->id; ?>"><?php echo $pt->payment_type; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="tempo-place">
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
                                            <textarea class="form-control" placeholder="Keterangan" name="keterangan"></textarea>
                                        </div>
                                    </div>

                                    <!--<div class="form-group">
                                        <input type="checkbox" id="profil-address" value="1"> Gunakan Alamat Profil ?
                                    </div>

                                    <div id="profil-address-form">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                <input type="text" class="form-control" placeholder="No HP" name="no_hp" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                                <textarea class="form-control" placeholder="Alamat Pengiriman" name="alamat" id="alamat"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                             <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                                <select class="select2" name="provinsi" id="provinsi">
                                                    <option value="">--Provinsi--</option>
                                                    <?php
                                                        foreach($provinsi->result() as $prv){
                                                    ?>
                                                    <option value="<?php echo $prv->id_provinsi; ?>"><?php echo $prv->nama_provinsi; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                                <select class="select2" id="list-kabupaten" name="kabupaten">
                                                    <option>--Kabupaten--</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                                <select class="select2" id="list-kecamatan" name="kecamatan">
                                                    <option>--Kecamatan--</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>-->

                                    <div class="form-group" align="right">
                                        <input type="hidden" id="total_purchase_temp" name="total_purchase" value="0"/>
                                        <input type="hidden" id="diskon_temp" name="diskon" value="0"/>
                                        <input type="hidden" id="ongkir_temp" value="0"/>
                                        <input type="hidden" id="diskon_promosi_temp" name="diskon_promosi_temp" value="0"/>
                                        <input type="hidden" id="diskon_otomatis_temp" name="diskon_otomatis_temp" value="0"/>
                                        <input type="hidden" id="poin_temp" name="poin_temp" value="0"/>
                                    </div>
                                </div>
                            </div>
                		</div>
                	</div>
                </form>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<div id="myModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Free Item</h4>
            </div>                             
            <div class="modal-body" id="free-item-form">
                <div class="form-group">
                    <input type="hidden" id="mySelect2" name="free-item" style="width: 100%;" />
                </div>                                   
            </div>                                    
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
