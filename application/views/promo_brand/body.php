<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>
<link href="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-dollar"></i> Setting Promo Khusus Brand</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
            			<a href="<?php echo base_url('promo_brand/daftar_po'); ?>"><i class="fa fa-book"></i> Daftar Promo Khusus Brand</a>
            		</div>
            	</div>

                
            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                <select class="select2" id="toko" required>
                                    <option value="">--Pilih Toko--</option>
                                    <?php
                                        foreach($store->result() as $tk){
                                    ?>
                                    <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                    <?php } ?>
                                </select>
                            </div>		
            			</div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                                <select class="select2" id="brand" required>
                                    <option value="">--Pilih Brand--</option>
                                    <?php
                                        foreach($brand->result() as $sp){
                                            $sel = ($_SESSION['id_brand']==$sp->id_brand)?'selected':'';
                                    ?>
                                    <option value="<?php echo $sp->id_brand; ?>" <?php echo $sel?>><?php echo $sp->brand; ?></option>
                                    <?php } ?>
                                </select>
                            </div>		
            			</div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cogs"></i></span>
                                <select class="select2" id="rules_type" required>
                                    <option value="">--Pilih Jenis Promo--</option>
                                    <option value="Count2Price">Minimal berapa (item) - diskon sekian (rupiah)</option>
                                    <option value="Count2Percent">Minimal berapa (item) - diskon sekian (persen)</option>
                                    <option value="Sum2Price">Minimal berapa (rupiah) - diskon sekian (rupiah)</option>
                                    <option value="Sum2Percent">Minimal berapa (rupiah) - diskon sekian (persen)</option>
                                </select>
                            </div>		
            			</div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control datepicker" placeholder="Tanggal Mulai" id="tanggalMulai" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control datepicker" placeholder="Tanggal Selesai" id="tanggalSelesai" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <label>Minimal Belanja</label>
                                    <input type="text" class="form-control" placeholder="isi hanya angka" id="minBelanja">
                                </div>
                            </div>
                        </div>
            			<div class="col-lg-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <label>Diskon</label>
                                    <input type="text" class="form-control" placeholder="isi hanya angka" id="discount">
                                </div>
                            </div>
                        </div>
            		</div>

            		<div class="col-md-6">
            			<div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <textarea id="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            <div class="input-group">
                                <input type='checkbox' id='setJam' value='1' checked required> Hanya Jam Tertentu? 
                            </div>
                            <div class="input-group">
                                <label>Jam Mulai</label>
                                <input placeholder="Jam Mulai" type="text" id="timepicker1" class="form-control timepicker" required>
                            </div>
                            <div class="input-group">
                            <label>Jam Selesai</label>
                                <input placeholder="Jam Selesai" type="text" id="timepicker2" class="form-control timepicker" required>
                            </div>
                        </div>
            		</div>
                    <div class='col-md-3'>
                    <div class="form-group">
                            <div class="input-group">
                                <input type='checkbox' id='setHari' value='1' checked required> Hanya Hari Tertentu? 
                            </div>
                            <div class="input-group">
                            <label>Pilih Hari</label>
                                <select class="select2" id="HariID" multiple placeholder="klik disini">
                                    <option value="0" selected>Minggu</option>
                                    <option value="1" selected>Senin</option>
                                    <option value="2" selected>Selasa</option>
                                    <option value="3" selected>Rabu</option>
                                    <option value="4" selected>Kamis</option>
                                    <option value="5" selected>Jumat</option>
                                    <option value="6" selected>Sabtu</option>
                                </select>
                            </div>
                        </div>
                    </div>
            	</div>  
            	<div class="row" style="margin-top: 20px;">
                    <div class="col-md-12" style="text-align: right;">
                        <button class="btn btn-primary" id="prosesPO"><i class="fa fa-save"></i> Simpan</button>
                    </div>
            	</div>      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
