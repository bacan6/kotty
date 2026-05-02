<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Edit Customer</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row" style="margin-top: 30px;">
            		<div class="col-md-12">
            			<form action="<?php echo base_url('customer/edit_customer_sql'); ?>" class="form-horizontal" role="form" method="post">                                    
                            <?php
                                foreach($customer->result() as $row){
                            ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nomor Kartu</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="no_kartu" value="<?php echo $row->no_kartu; ?>" required>
                              	</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nomor Identitas</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="no_id" value="<?php echo $row->no_id; ?>" required>
                              	</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nama Customer</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="nama" value="<?php echo $row->nama; ?>" required>
                                    <input type="hidden" name="id_customer" value="<?php echo $_GET['id']; ?>"/>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Tanggal Lahir</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control datepicker" placeholder="Tanggal Lahir" value="<?php echo $row->tanggal_lahir; ?>" name="tanggal_lahir" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kontak</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="kontak" value="<?php echo $row->kontak; ?>" required>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Alamat</label>
                                <div class="col-md-10">
                                	<textarea class="form-control" name="alamat" required><?php echo $row->alamat; ?></textarea>
                              	</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Kategori</label>
                                <div class="col-md-10">
                                	<select class="form-control" name="group" required>
                                		<option value="">--Pilih Kategori--</option>
                                		<?php
                                			foreach($group_customer->result() as $cs){
                                		?>
                                		<option value="<?php echo $cs->id_group; ?>" <?php if($row->kategori==$cs->id_group){echo"selected";} ?>><?php echo $cs->group_customer; ?></option>
                                		<?php } ?>
                                	</select>
                              	</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Aktivasi</label>
                                <div class="col-md-10">
                                	<input type="radio" name="activated" value="1" <?php if($row->activated==1){echo "checked";}?> /> Aktif
                                    <input type="radio" name="activated" value="0" <?php if($row->activated==0){echo "checked";}?> /> Belum Aktif
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-10">
                                	<input type="text" class="form-control" name="Password" value=""><p align="center">*/ Isi hanya bila ingin merubah password</p>
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
                                		<option value="<?php echo $pro->id_provinsi; ?>" <?php if($pro->id_provinsi==$row->id_provinsi){echo "selected";} ?>><?php echo $pro->nama_provinsi; ?></option>
                                		<?php } ?>
                                	</select>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kabupaten</label>
                                <div class="col-md-10">
                                	<select class="select2" id="list-kabupaten" name="kabupaten" >
                                		<option value="">--Pilih Kabupaten--</option>
                                        <?php
                                           $id_provinsi = $row->id_provinsi;

                                            $kabupaten = $this->db->get_where("ae_kabupaten", array("id_provinsi" => $id_provinsi));

                                            foreach($kabupaten->result() as $kb){
                                        ?>
                                            <option value="<?php echo $kb->kabupaten_id; ?>" <?php if($kb->kabupaten_id==$row->id_kabupaten){echo "selected";}?>><?php echo $kb->nama_kabupaten; ?></option>
                                        <?php
                                            }
                                        ?>
                                	</select>
                              	</div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Kecamatan</label>
                                <div class="col-md-10">
                                	<select class="select2" id="list-kecamatan" name="kecamatan" >
                                		<option value="">--Pilih Kecamatan--</option>
                                	    <?php
                                            $id_kabupaten = $row->id_kabupaten;

                                            $kecamatan = $this->db->get_where("ae_kecamatan", array("kabupaten_id" => $id_kabupaten));

                                            foreach($kecamatan->result() as $kc){
                                        ?>
                                            <option value="<?php echo $kc->id_kecamatan; ?>" <?php if($kc->id_kecamatan==$row->id_kecamatan){echo "selected";}?>><?php echo $kc->kecamatan; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                              	</div>
                            </div>

                            

                            <div class="form-group">
                                <label class="col-md-2 control-label">Diskon Harga</label>
                                <div class="col-md-10">
                                	<input type="number" name="diskon" class="form-control" min="0" max="100" value="<?php echo $row->diskon; ?>" >
                              	</div>
                            </div>

                            <?php
                                $kategori_customer = $row->kategori;

                                if($kategori_customer==4 or $kategori_customer==5){
                            ?>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-10">
                                   <input type="text" class="form-control" value="<?php echo $row->username; ?>" name="username">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-10">
                                   <input type="text" class="form-control" placeholder="Password" name="password">
                                </div>
                            </div>

                            <?php } ?>                   

                            <div class="form-group" style="text-align: right;">
                            	<div class="col-md-12">
                            		<input type="submit" class="btn btn-primary" value="Submit">
                            	</div>
                            </div>
                            <?php } ?>
                     	</form>
            		</div>
            	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
