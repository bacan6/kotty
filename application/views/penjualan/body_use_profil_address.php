<?php 
	foreach($customer->result() as $dt){
?>
	<div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
	       <input type="text" class="form-control" placeholder="No HP" name="no_hp" value="<?php echo $dt->kontak; ?>" required>
	    </div>
    </div>

	<div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-home"></i></span>
	       <textarea class="form-control" placeholder="Alamat Pengiriman" name="alamat" id="alamat"><?php echo $dt->alamat; ?></textarea>
	   </div>
    </div>

	<div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-building"></i></span>
    	    <select class="form-control" name="provinsi" id="provinsi">
    	        <option value="">--Provinsi--</option>
    	        <?php
    	            foreach($provinsi->result() as $prv){
    	        ?>
    	        <option value="<?php echo $prv->id_provinsi; ?>" <?php if($prv->id_provinsi == $dt->id_provinsi){echo "selected";}?>><?php echo $prv->nama_provinsi; ?></option>
    	        <?php } ?>
    	    </select>
        </div>
	</div>

	<div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-building"></i></span>
    	    <select class="form-control" id="list-kabupaten" name="kabupaten" required>
                <option value="">--Pilih Kabupaten--</option>
                    <?php
                        $id_provinsi = $dt->id_provinsi;

                        $kabupaten = $this->db->get_where("ae_kabupaten", array("id_provinsi" => $id_provinsi));

                        foreach($kabupaten->result() as $kb){
                   	?>
                        <option value="<?php echo $kb->kabupaten_id; ?>" <?php if($kb->kabupaten_id==$dt->id_kabupaten){echo "selected";}?>><?php echo $kb->nama_kabupaten; ?></option>
                    <?php
                        }
                	?>
            </select>
        </div>
	</div>

	<div class="form-group">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-building"></i></span>
    	    <select class="form-control" id="list-kecamatan" name="kecamatan" required>
                <option value="">--Pilih Kecamatan--</option>
                <?php
                    $id_kabupaten = $dt->id_kabupaten;

                    $kecamatan = $this->db->get_where("ae_kecamatan", array("kabupaten_id" => $id_kabupaten));

                    foreach($kecamatan->result() as $kc){
               	?>
                    <option value="<?php echo $kc->id_kecamatan; ?>" <?php if($kc->id_kecamatan==$dt->id_kecamatan){echo "selected";}?>><?php echo $kc->kecamatan; ?></option>
                <?php
                    }
                ?>
            </select>
        </div>
	</div>
<?php } ?>

<script type="text/javascript">

			$('#provinsi').change(function(){
                url = "<?php echo base_url('penjualan/list_kabupaten'); ?>";
                id = $('#provinsi').val();
                $('#list-kabupaten').load(url,{id : id});
            });


            $('#list-kabupaten').change(function(){
                url= "<?php echo base_url('penjualan/list_kecamatan'); ?>";
                
                id = $('#list-kabupaten').val(); 

                $('#list-kecamatan').load(url,{id : id});
            });
</script>