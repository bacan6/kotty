<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Add Customer</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row" style="margin-top: 30px;">
            		<div class="col-md-12">
            			<form action="<?php echo base_url('customer/add_customer_sql'); ?>" class="form-horizontal" role="form" method="post">                                    
                            <div class="form-group">
                                <label class="col-md-2 control-label">Barcode</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="id_customer" required>
                              	</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nomor Kartu</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="no_kartu" required>
                              	</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nomor Identitas</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="no_id" required>
                              	</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nama Customer</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="nama" required>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kontak</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="kontak" id="kontak" required>
                                    <span id="label-kontak"></span>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Email</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Tanggal Lahir</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control datepicker" name="tanggal_lahir" id="datepicker" required readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Alamat</label>
                                <div class="col-md-10">
                                	<textarea class="form-control" name="alamat" required></textarea>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Provinsi</label>
                                <div class="col-md-10">
                                	<select class="select2" id="provinsi" name="provinsi" >
                                		<option value="">--Pilih Provinsi--</option>
                                		<?php
                                			foreach($provinsi->result() as $pro){
                                		?>
                                		<option value="<?php echo $pro->id_provinsi; ?>"><?php echo $pro->nama_provinsi; ?></option>
                                		<?php } ?>
                                	</select>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kabupaten</label>
                                <div class="col-md-10">
                                	<select class="select2" id="list-kabupaten" name="kabupaten" >
                                		<option value="">--Pilih Kabupaten--</option>
                                	</select>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kecamatan</label>
                                <div class="col-md-10">
                                	<select class="select2" id="list-kecamatan" name="kecamatan" >
                                		<option value="">--Pilih Kecamatan--</option>
                                	</select>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kategori</label>
                                <div class="col-md-10">
                                	<select class="form-control" name="group" id="group_customer" required>
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
                                <label class="col-md-2 control-label">Diskon Harga</label>
                                <div class="col-md-10">
                                	<input type="number" name="diskon" class="form-control" min="0" max="100" >
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-10">
                                   <input type="text" class="form-control" placeholder="Password" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Aktivasi</label>
                                <div class="col-md-10">
                                	<input type="radio" name="activated" value="1" /> Aktif
                                    <input type="radio" name="activated" value="0" /> Belum Aktif
                              	</div>
                            </div>

                            <div class="form-group" style="text-align: right;">
                            	<div class="col-md-12">
                            		<input type="submit" class="btn btn-primary" value="Submit">
                            	</div>
                            </div>
                     	</form>
            		</div>
            	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
