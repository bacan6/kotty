<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>


<div id="payment_total_notif" style="width: 100%;position: fixed;z-index: 10001;left:0;top:0;height:100%;display: none; border:1px dotted #ccc;background:rgba(4, 4, 4, 0.73);" >
    <div class="alert alert-danger" style="margin-top:13%;margin-left:30%;padding:20px;width:40%;border:6px solid #fff" >
        <table width="100%" style="font-size: 45px;">
            <tr style="border-bottom: 1px solid #ccc">
                <td width="50%" style="font-size:28px">Total Belanja</td>
                <td align="right" id="total_belanja_notif"></td>
            </tr>

            <tr style="border-bottom: 1px solid #ccc"> 
                <td width="50%" style="font-size:28px">Jumlah Bayar</td>
                <td align="right" id="jumlah_bayar_notif"></td>
            </tr>

            <tr style="border-bottom: 1px solid #ccc">
                <td width="50%" style="font-size:28px">Kembali</td>
                <td align="right" id="kembali_notif" style="font-weight: bold"></td>
            </tr>
        </table>
    </div>
</div>


<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" style="background:#FDFDFD; border-radius:9px">
                
                    <div class="row">
                        <div class="col-md-7" align="left">
                            <a href="<?php echo base_url('penjualan/data_penjualan'); ?>"> <i class="fa fa-book"></i> Data Penjualan </a> | <!--<a href="<?php echo base_url('penjualan/reservasi'); ?>"> <i class="fa fa-history"></i> Reservasi </a> |--> <a href="<?php echo base_url('penjualan/pendingList'); ?>"> <i class="fa fa-clock-o"></i> Daftar Pending </a> | <a href="<?php echo base_url('penjualan/retur'); ?>"> <i class="fa fa-cog"></i> Retur </a>
                            | <a href="<?php echo base_url('penjualan/setoran_kasir'); ?>"> <i class="fa fa-money"></i> Setoran Kasir </a>
                            | <a href="<?php echo base_url('penjualan/setoranList'); ?>"> <i class="fa fa-clock-o"></i> Daftar Set. Kasir </a>
                            <span style="color:orange;font-weight:bold">--- F4 = Bayar | F3 = Cari Produk</span>
                        </div>
         

                        <div class="col-md-5" style="text-align: right;">
                            <button id="button-submit" class="btn btn-icon btn-danger btn-rounded m-b-5"> <i class=" fa fa-check-square-o"></i> Submit</button>

                            <button id="pendingTrx" class="btn btn-con btn-warning btn-rounded m-b-5"><i class="fa fa-clock-o"></i> Pending</button>                            

                            <a class="btn btn-con btn-danger btn-rounded m-b-5" id="cancelButton" href="<?php echo base_url('penjualan/cancelTrx'); ?>"><i class="fa fa-times"></i> Batal</a>
                            <a class="btn btn-con btn-success btn-rounded m-b-5" href="#approvalKasir" data-toggle="modal" onclick="$('#chartID').val('');$('#userApprover').focus();"><i class="fa fa-cog"></i></a>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 5px;">
                		

                		<div class="col-md-3" style="padding-right: 15px;">
                			<div class="row">
                				<div class="col-md-12" style="border-bottom: solid 0.1px #ccc;border-top: solid 2px #ccc;background:#fbede8;padding-top:4px;border-bottom-left-radius:9px">
                					<table class="table" style="font-size: 12px;">
                						<tr>
                							<td width="50%" style="font-weight: bold;color:#25aff4;"><i class="fa fa-crosshairs"></i> Subtotal</td>
                							<td id="total_purchase" align="right" style="font-weight: bold;color:#25aff4;"></td>
                						</tr>

                                        <tr id="diskonPeritem">
                                            <!--<td><i class="fa fa-bullhorn"></i> Diskon Peritem</td>
                                            <td id="diskon_otomatis" align="right"></td>-->
                                        </tr>

                						<tr id="ongkirText">
                							
                							
                						</tr>
                						<tr id="diskonMember">
  
                						</tr>
                                        <tr id="diskon_promosi">
                                            
                                        </tr>
                                        <tr id="poin-value-reimburs">
    
                                        </tr>
                                        <tr id="voucherFisik">
    
                                        </tr>
                                        <tr id="voucher">
    
                                        </tr>
                                        <tr id="surcharge">
    
                                        </tr>
                                        
                						<tr>
                							<td colspan="2" style="color:#25aff4;" ><i class='fa fa-bank'></i> <b>TOTAL</b><br>
                                            <p id="grand_total" align="center" style="width:100%;font-weight: bold;font-size:42px;color:red;"></p>
                                            </td>
                						</tr>
                                        
                					</table> 
                				</div>

                                <div class="col-md-12" style="margin-top:10px">
                                <?php 
                                    foreach($banner->result() as $bn){
                                        echo "
                                                <img src='".base_url('uploads/files/banner/'.$bn->gambar)."' alt='kotty' class='img-responsive'>";

                                    }
                                    ?>
                                </div>
                			</div>
                            
                            
                		</div>
                        <div class="col-md-9">                			
                            <div class="row">
                                <div class="col-md-12" style="">
                                    <div class="form-group">
                        				<input type="hidden" id="produk-ajax" name="customer" style="width: 100%;">
                        			</div>

                        			<div class="form-group" style="margin-top: 20px;height: calc(100vh - 380px);" id="tableNiceScroll">
                        				<?php
                                            if(empty($idStore)){
                                                echo "<p style='text-align:center;font-size:20px;font-weight:bold;color:red;'>Akun anda belum ditugaskan di toko manapun, silahkan hubungi admin</p>";
                                            } else {
                                        ?>

                                        <table class="table table-striped table-bordered" style="font-size: 12px;">
                        					<thead style="overflow-y: none">
            	            					<tr style="background: #d00d51;border:1px solid #eba995;color:#fff;font-weight: bold;">
            	            						<td width=200>Nama Produk</td>
            	            						<td width="9%" align="right">Harga Jual</td>
            	            						<td align="center" width=80>Qty</td>
            	            						<td width="10%" align="right">Total Harga</td>
                                                    <td width="80" align="right">Discount</td>
                                                    <td width="10" align="right">Disc (%)</td>
                                                    <td width="13%" align="right">Grand Total</td>
            	            						<td width="3%"></td>
            	            					</tr>
                        					</thead>

                        					<tbody id="data-input" style="overflow-y: scroll!important">

                        					</tbody>
                        				</table>
                                        <?php } ?>
                        			</div>
                                </div>
                            </div>
                		</div>
                        <div class="col-md-12" style="border: 1px solid #ccc;padding-left:10px;background:#d00d51;border-radius:9px;position:absolute;z-index:100;bottom:15px;width:90%;right:5%">
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                            <select class="select2" name="type_bayar" id="type_bayar">
                                                <?php
                                                $arr_kasir = array(4,11,13,31,19,23,30);
                                                    foreach($payment_type->result() as $pt){
                                                        if($pt->id==5){
                                                            if(!in_array($idUser,$arr_kasir)){ ?>
                                                                <option value="<?php echo $pt->id; ?>"><?php echo $pt->payment_type; ?></option>
                                                            <?php
                                                            }
                                                        }else{ ?>
                                                          <option value="<?php echo $pt->id; ?>"><?php echo $pt->payment_type; ?></option>  
                                                       <?php }
                                                        
                                                ?>
                                                    
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="form-group" id="tempo-place">
                                    </div>
                                </div>
                                
<!-- AKTIFKAN                   
                                    <div class="form-group" style="text-align: right;">
                                        <a href="#opsiPengirimanModal" data-backdrop="static" data-keyboard="false" data-toggle="modal" class="btn btn-default btn-rounded"><i class="fa fa-plus"></i> Tambah Opsi Pengiriman</a>
                                    </div>
                                    
                                    
--> 
                                <?php if ($idUser!=9 && $idUser!=24){?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user" style="color:#007223;"></i></span>
                                            <input type="hidden" id="customer-form" name="customer" style="width: 100%;">
                                        </div>
                                    </div>
                                    <div class="form-group" id="data-customer">

                                    </div>
                                </div>
                                <?php } ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-minus"></i></span>
                                            <input type="text" class="form-control" placeholder="Diskon" id="diskon" value="<?php if($diskonPromosi > 0){echo $diskonPromosi;}?>" style="text-align: right;" readonly>
                                            <span class="input-group-addon"><a href="#approvalKasir" data-toggle="modal" onclick="$('#chartID').val('diskon');$('#userApprover').focus();"><i class="fa fa-cog"></i></a></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
                                            <textarea class="form-control" placeholder="Keterangan" id="keterangan" name="keterangan" style="min-height:20px!important;height:34px!important"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                                <input type="text" class="form-control" placeholder="SeriVoucher" id="seri_voucher" name="seri_voucher">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calculator"></i></span>
                                                <input type="text" class="form-control" placeholder="Qty" id="totalQty" style="font-size:20px;text-align:right;font-weight:bold" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            <input type="text" class="form-control" placeholder="Jumlah Bayar" id="jumlah_bayar" name="jumlah_bayar" style="font-size:25px;text-align:right;font-weight:bold" 
                                            <?php if($idUser==6){ echo "disabled";}else{ echo "required";}?>>
                                        </div>
                                    </div>
                                    
                                </div>
                                
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

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
<!-- AKTIFKAN -->

<div id="customerModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Tambah Customer</h4>
            </div>                             
            <div class="modal-body" id="customerFormAdd">
                <div class="row">
                    <div class="col-md-6">
                            <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-md-2 control-label">No Member</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="noMember" required>
                                    <label id="labelNoMember" style="color:red;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Nama Customer</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="namaCustomer" required>
                                    <label id="labelNamaCust" style="color:red;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kontak</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="kontak" required>
                                    <label id="labelKontak" style="color:red;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Email</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="email" required>
                                    <label id="labelEmail" style="color:red;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Tanggal Lahir</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control datepicker" id="tanggalLahir" required readonly>
                                </div>
                            </div>


                                <div class="form-group">
                                        <label class="col-md-2 control-label">Kategori Customer</label>
                                        <div class="col-md-10">
                                            <select class="form-control" id="kategoriCustomer" required>
                                                <option value="">--Pilih Kategori--</option>
                                                <?php
                                                    foreach($group_customer->result() as $cs){
                                                ?>
                                                <option value="<?php echo $cs->id_group; ?>"><?php echo $cs->group_customer; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                            <div class="form-group">
                                <label class="col-md-2 control-label">Diskon Member</label>
                                <div class="col-md-10">
                                    <input type="number" id="setDiskonMember" class="form-control" min="0" max="100" required>
                                </div>
                            </div>

                            </form> 
                    </div>

                    <div class="col-md-6">
                        <form class="form-horizontal" role="form">
                         <div class="form-group">
                                <label class="col-md-2 control-label">Alamat</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" id="alamat" required></textarea>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label class="col-md-2 control-label">Provinsi</label>
                                <div class="col-md-10">
                                    <select class="select2" id="provinsi" required>
                                        <option value="">--Pilih Provinsi--</option>
                                        <?php
                                            //foreach($provinsi->result() as $pro){
                                        ?>
                                        <option value="<?php //echo $pro->id_provinsi; ?>"><?php //echo $pro->nama_provinsi; ?></option>
                                        <?php //} ?>
                                    </select>
                                </div>
                            </div> -->

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kabupaten</label>
                                <div class="col-md-10">
                                    <select class="select2" id="list-kabupaten" required>
                                        <option value="">--Pilih Kabupaten--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kecamatan</label>
                                <div class="col-md-10">
                                    <select class="select2" id="list-kecamatan" name="kecamatan" required>
                                        <option value="">--Pilih Kecamatan--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="text-align: right;">
                                <div class="col-md-12">
                                    <a class="btn btn-primary" id="simpanMember">Simpan</a>
                                </div>
                            </div> 
                        </form>
                    </div>
                </div>                                 
            </div>                                    
        </div>
    </div>
</div>

<div id="opsiPengirimanModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-car"></i> Alamat Pengiriman</h4>
            </div>                             
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        Gunakan Alamat Customer Terpilih ? <input type="checkbox" id="alamatCustomer"/> 
                    </div>
                </div>

                <div class="row" id="alamatContent" style="margin-top: 10px;">
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nama Penerima</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="namaPenerima" required>
                                    <label id="labelNamaCust" style="color:red;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">No HP</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="kontakPenerima" required>
                                    <label id="labelKontak" style="color:red;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Ekspedisi</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="ekspedisi">
                                        <option value="">--Pilih Ekspedisi--</option>
                                        <?php
                                            foreach($ekspedisi as $eks){
                                        ?>
                                        <option value="<?php echo $eks->id_ekspedisi; ?>"><?php echo $eks->ekspedisi; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div> 

                            <div class="form-group">
                                <label class="col-md-2 control-label">Ongkir</label>
                                <div class="col-md-10">
                                    <input type="text" id="ongkir" name="ongkir" class="form-control" placeholder="Ongkir" value="<?php if($ongkir > 0){echo $ongkir;} ?>">
                                </div>
                            </div>
                        </form>                            
                    </div>

                    <div class="col-md-6">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Alamat</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" id="alamatPenerima" required></textarea>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label class="col-md-2 control-label">Provinsi</label>
                                <div class="col-md-10">
                                    <select class="select2" id="provinsiPenerima">
                                        <option value="">--Pilih Provinsi--</option>
                                        <?php
                                            //foreach($provinsi->result() as $prp){
                                        ?>
                                        <option value="<?php //echo $prp->id_provinsi; ?>"><?php //echo $prp->nama_provinsi; ?></option>
                                        <?php //} ?>
                                    </select>
                                </div>
                            </div> -->

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kabupaten</label>
                                <div class="col-md-10">
                                    <select class="select2" id="kabupatenPenerima">
                                        <option value="">--Pilih Kabupaten--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kecamatan</label>
                                <div class="col-md-10">
                                    <select class="select2" id="kecamatanPenerima">
                                        <option value="">--Pilih Kecamatan--</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>     
            </div>        
            <div class="modal-footer">
                <button class="btn btn-primary" id="hidePengiriman"><i class="fa fa-save"></i> Simpan</button>
            </div>                            
        </div>

    </div>
</div>
<div id="approvalKasir" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-check"></i> Persetujuan Akses</h4>
            </div>                             
            <div class="modal-body">

                <div class="row" id="approvalContent" style="margin-top: 10px;">
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form">
                            <input type="hidden" class="form-control" id="userApprover">
                            
                            <input type="hidden" id="chartID">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-10">
                                    <select id="select_scan" onchange="login_selectuser()" class='select2'>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $s = 0;
                                            foreach($approver->result() as $app){
                                                //$s = ($app->username=='robert')?base64_encode(base_url('kepala_kasir/verification?user_id='.$app->username)):$s;
                                                $value = base64_encode(base_url('kepala_kasir/verification?user_id='.$app->username));

                                                // new fingerprint
                                                $webhook = $this->model_crypto->encrypt(base_url('kepala_kasir/verification_new'));
                                                $user = $this->model_crypto->encrypt($app->username);
                                        ?>
                                        <option value="<?php echo $value; ?>" webhook_new="<?php echo $webhook?>" fingerprint="<?php echo $app->fingerprint1?>" user_new="<?php echo $user?>" username="<?php echo $app->username; ?>"><?php echo $app->Nama; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label id="labelpwd" style="color:red;"></label>
                                </div>
                            </div>

                        </form>                            
                    </div>
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" onsubmit="$('#verifyApproval').click();return false;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Verifikasi</label>
                                <div class="col-md-10">
                                    <a href="" id="button_login" class="btn btn-success btn-xs">Login</a>
                                    <a href="" id="button_login_new" class="btn btn-warning btn-xs">Login New</a>
                                    <label id="labelvrf" style="color:green;"></label>
                                    <?php 
                                    // $arUsr = array(45,44,43,40,47,46);
                                    //if(in_array($idUser,$arUsr)){?>
                                        <!-- <input type="password" class="form-control" id="passApprover"> -->
                                    <?php //}else{ ?>
                                        <input type="hidden" class="form-control" id="passApprover">
                                    <?php //} ?>
                                    
                                </div>
                            </div>

                        </form>
                    </div>

                </div>     
            </div>      
            <div class="modal-footer">
                <button class="btn btn-info" id="verifyApproval"><i class="fa fa-check"></i> Diskon</button>
                <button class="btn btn-info" id="verifyApprovalDelete"><i class="fa fa-trash"></i> Hapus Item</button>
                <button class="btn btn-info" id="verifyApprovalQty"><i class="fa fa-plus"></i> Update Qty</button>
                
                <button class="btn btn-warning" id="verifyApprovalPending"><i class="fa fa-clock-o"></i> Pending</button>
                <button class="btn btn-danger" id="verifyApprovalCancel"><i class="fa fa-times"></i> Batal Transaksi</button>
                <button class="btn btn-success" id="lockAll"><i class="fa fa-lock"></i> Kunci Semua</button>
            </div>                            
        </div>

    </div>
</div>
<div id="qrisModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-qrcode"></i> Metode Pembayaran: QRIS</h4>
            </div>                             
            <div class="modal-body" id="qrisBody">
                Loading...
            </div>      
            <div class="modal-footer">
                <button class="btn btn-green" id="qrisCheckPayment"><i class="fa fa-refresh"></i> Check Payment</button>
            </div>                            
        </div>

    </div>
</div>

<div id="bsiModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cc"></i> BSI Hasanah Card | Debit BSI Visa</h4>
            </div>                             
            <div class="modal-body">
                <label>No. Kartu</label>
                <input type="number" class="form-control" placeholder="xxx" id="nama_penerima">
            </div>      
            <div class="modal-footer">
                <button class="btn btn-warning" id="btnCekBSI"><i class="fa fa-save"></i> Simpan</button>
            </div>                            
        </div>

    </div>
</div>