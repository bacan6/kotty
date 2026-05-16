<link rel="stylesheet" href="<?php echo base_url(); ?>datepicker/css/bootstrap-datetimepicker.min.css">
<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"><i class="fa fa-ticket"></i> Data Voucher Fisik</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="panel panel-default" style="margin-bottom:15px;">
            		<div class="panel-heading clearfix">
            			<h4 class="panel-title pull-left" style="margin-top:6px;"><i class="fa fa-filter text-muted"></i> Filter</h4>
            			<div class="pull-right">
            				<a href="#add-voucher-struk" data-toggle="modal" class="btn btn-warning btn-sm" style="margin-right:6px;"><i class="fa fa-plus"></i> Tambah Voucher Struk</a>
            				<a href="#add-voucer" data-toggle="modal" class="btn btn-info btn-sm add-voucer"><i class="fa fa-plus"></i> Tambah Voucher Cetak</a>
            			</div>
            		</div>
            		<div class="panel-body" style="padding-bottom:10px;">
            			<div class="row">
            				<div class="col-lg-2 col-md-4 col-sm-6">
            					<div class="form-group" style="margin-bottom:10px;">
            						<label class="control-label" for="filter_voucher_date_start" style="font-weight:600;font-size:12px;color:#777;">Dibuat mulai</label>
            						<input type="text" id="filter_voucher_date_start" class="form-control input-sm datetimepicker-filter" placeholder="Waktu dibuat dari …" autocomplete="off"/>
            					</div>
            				</div>
            				<div class="col-lg-2 col-md-4 col-sm-6">
            					<div class="form-group" style="margin-bottom:10px;">
            						<label class="control-label" for="filter_voucher_date_end" style="font-weight:600;font-size:12px;color:#777;">Dibuat sampai</label>
            						<input type="text" id="filter_voucher_date_end" class="form-control input-sm datetimepicker-filter" placeholder="Waktu dibuat sampai …" autocomplete="off"/>
            					</div>
            				</div>
            				<div class="col-lg-2 col-md-4 col-sm-6">
            					<div class="form-group" style="margin-bottom:10px;">
            						<label class="control-label" for="filter_voucher_brand" style="font-weight:600;font-size:12px;color:#777;">Brand</label>
            						<select id="filter_voucher_brand" class="form-control input-sm">
            							<option value="">Semua brand</option>
            							<?php if (!empty($brand) && $brand->num_rows()) { foreach ($brand->result() as $br) { ?>
            							<option value="<?php echo (int)$br->id_brand; ?>"><?php echo htmlspecialchars($br->brand); ?></option>
            							<?php } } ?>
            						</select>
            					</div>
            				</div>
            				<div class="col-lg-2 col-md-4 col-sm-6">
            					<div class="form-group" style="margin-bottom:10px;">
            						<label class="control-label" for="filter_voucher_kind" style="font-weight:600;font-size:12px;color:#777;">Jenis</label>
            						<select id="filter_voucher_kind" class="form-control input-sm">
            							<option value="">Semua jenis</option>
            							<option value="cetak">Voucher cetak</option>
            							<option value="struk">Voucher struk</option>
            						</select>
            					</div>
            				</div>
            				<div class="col-lg-4 col-md-12 col-sm-6">
            					<div class="form-group" style="margin-bottom:0;">
            						<label class="control-label" style="font-weight:600;font-size:12px;color:#777;">Aksi</label>
            						<div class="btn-toolbar" style="margin-top:0;">
            							<div class="btn-group btn-group-sm">
            								<button type="button" class="btn btn-primary" id="filter_voucher_apply"><i class="fa fa-search"></i> Terapkan</button>
            								<button type="button" class="btn btn-default" id="filter_voucher_reset"><i class="fa fa-undo"></i> Reset</button>
            							</div>
            						</div>
            					</div>
            				</div>
            			</div>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12 table-responsive" id="data-voucer">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->
</div>

<div class="modal fade" id="add-voucer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Voucher Cetak</h4>
      </div>
      <form method="post" action="" enctype="multipart/form-data" id="submit">
      <div class="modal-body">
        
        <div class="form-group">
          <label for="nama_voucer">Nama Voucher</label>
        	<input type="text" name="nama_voucer" class="form-control" placeholder="Nama Voucher" id="nama_voucer"/>
        </div>
        <div class="form-group">
          <label for="brand_voucher">Brand</label>
          <select name="brand[]" id="brand_voucher" class="select2" multiple="multiple" data-placeholder="Pilih brand">
            <?php if (!empty($brand) && $brand->num_rows()) { foreach ($brand->result() as $br) { ?>
            <option value="<?php echo (int)$br->id_brand; ?>"><?php echo htmlspecialchars($br->brand); ?></option>
            <?php } } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="id_produk_voucher">Produk (SKU)</label>
          <input type="hidden" id="id_produk_voucher" style="width:100%;"/>
        </div>
        <div class="form-group">
          <label for="tgl_mulai">Tanggal berlaku</label>
          <input type="text" name="berlaku_mulai" class="form-control datetimepicker" placeholder="Tanggal Berlaku" id="tgl_mulai"/>
        </div>
        <div class="form-group">
          <label for="tgl_selesai">Tanggal expired</label>
          <input type="text" name="berlaku_selesai" class="form-control datetimepicker" placeholder="Tanggal Expired" id="tgl_selesai"/>
        </div>
        <div class="form-group">
          <label for="jumlah">Jumlah voucher</label>
          <input type="text" name="jumlah" class="form-control" placeholder="Jumlah" id="jumlah"/>
        </div>
        <div class="form-group">
          <label for="nilai_tipe">Tipe nilai</label>
          <select name="nilai_tipe" id="nilai_tipe" class="form-control">
            <option value="rp">Rupiah (Rp)</option>
            <option value="percent">Persen (%)</option>
          </select>
        </div>
        <div class="form-group" id="nilai_wrap_add">
          <label for="nilai">Nilai</label>
          <input type="text" name="nilai" class="form-control" placeholder="Nominal Rp atau persen, contoh: 50000 atau 10" id="nilai"/>
          <span class="help-block text-danger" id="nilai_hint_add" style="display:none;"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary add-voucer-sql">Tambah</button>
      </div>
    </form>
    </div>
  </div>
</div>

<div class="modal fade" id="add-voucher-struk" tabindex="-1" role="dialog" aria-labelledby="modalStrukLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalStrukLabel">Tambah Voucher Struk</h4>
      </div>
      <form method="post" action="" enctype="multipart/form-data" id="submit-struk">
      <div class="modal-body">
        <div class="form-group">
          <label for="nama_voucer_struk">Nama Voucher</label>
          <input type="text" name="nama_voucer" class="form-control" placeholder="Nama Voucher" id="nama_voucer_struk"/>
        </div>
        <p class="text-muted" style="font-size:12px;"><strong>Lingkup pakai voucher</strong></p>
        <div class="form-group">
          <label for="brand_voucher_struk">Brand (pakai)</label>
          <select name="brand[]" id="brand_voucher_struk" class="select2-struk" multiple="multiple" data-placeholder="Pilih brand">
            <?php if (!empty($brand) && $brand->num_rows()) { foreach ($brand->result() as $br) { ?>
            <option value="<?php echo (int)$br->id_brand; ?>"><?php echo htmlspecialchars($br->brand); ?></option>
            <?php } } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="id_produk_voucher_struk">Produk (SKU) — pakai</label>
          <input type="hidden" id="id_produk_voucher_struk" style="width:100%;"/>
        </div>
        <div class="form-group">
          <label for="tgl_mulai_struk">Tanggal berlaku</label>
          <input type="text" name="berlaku_mulai" class="form-control datetimepicker" placeholder="Tanggal Berlaku" id="tgl_mulai_struk"/>
        </div>
        <div class="form-group">
          <label for="tgl_selesai_struk">Tanggal expired</label>
          <input type="text" name="berlaku_selesai" class="form-control datetimepicker" placeholder="Tanggal Expired" id="tgl_selesai_struk"/>
        </div>
        <div class="form-group">
          <label for="jumlah_struk">Jumlah voucher</label>
          <input type="text" name="jumlah" class="form-control" placeholder="Jumlah" id="jumlah_struk"/>
        </div>
        <div class="form-group">
          <label for="nilai_tipe_struk">Tipe nilai</label>
          <select name="nilai_tipe" id="nilai_tipe_struk" class="form-control">
            <option value="rp">Rupiah (Rp)</option>
            <option value="percent">Persen (%)</option>
          </select>
        </div>
        <div class="form-group" id="nilai_wrap_struk">
          <label for="nilai_struk">Nilai</label>
          <input type="text" name="nilai" class="form-control" placeholder="Nilai" id="nilai_struk"/>
          <span class="help-block text-danger" id="nilai_hint_struk" style="display:none;"></span>
        </div>
        <hr/>
        <div class="form-group">
          <label for="minimal_belanja_struk">Minimal belanja (pakai potongan)</label>
          <input type="text" name="minimal_belanja" class="form-control" placeholder="Rp (opsional)" id="minimal_belanja_struk"/>
        </div>
        <div class="form-group">
          <label for="min_get_voucher_struk_input">Minimal belanja dapat voucher</label>
          <input type="text" name="min_get_voucher_struk" class="form-control" placeholder="Rp (opsional)" id="min_get_voucher_struk_input"/>
        </div>
        <div class="form-group">
          <label for="start_voucher_struk_input">Mulai periode voucher struk</label>
          <input type="text" name="start_voucher_struk" class="form-control datetimepicker" placeholder="YYYY-MM-DD HH:mm:ss" id="start_voucher_struk_input"/>
        </div>
        <div class="form-group">
          <label for="end_voucher_struk_input">Selesai periode voucher struk</label>
          <input type="text" name="end_voucher_struk" class="form-control datetimepicker" placeholder="YYYY-MM-DD HH:mm:ss" id="end_voucher_struk_input"/>
        </div>
        <p class="text-muted" style="font-size:12px;"><strong>Syarat mendapat voucher struk</strong></p>
        <div class="form-group">
          <label for="brand_voucher_struk_get">Brand</label>
          <select name="brand_struk_get[]" id="brand_voucher_struk_get" class="select2-struk-get" multiple="multiple" data-placeholder="Pilih brand">
            <?php if (!empty($brand) && $brand->num_rows()) { foreach ($brand->result() as $br) { ?>
            <option value="<?php echo (int)$br->id_brand; ?>"><?php echo htmlspecialchars($br->brand); ?></option>
            <?php } } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="id_produk_voucher_struk_get">Produk (SKU)</label>
          <input type="hidden" id="id_produk_voucher_struk_get" style="width:100%;"/>
        </div>
        <div class="form-group">
          <label for="kategori_struk_get">Kategori</label>
          <select name="kategori_struk_get" id="kategori_struk_get" class="select2-struk-kat" data-placeholder="Pilih kategori (opsional)">
            <option value=""></option>
            <?php if (!empty($kategori_struk_options)) { foreach ($kategori_struk_options as $opt) { ?>
            <option value="<?php echo (int) $opt['id']; ?>"><?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?></option>
            <?php } } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="subkategori_struk_get">Subkategori</label>
          <select name="subkategori_struk_get[]" id="subkategori_struk_get" class="select2-struk-sub" multiple="multiple" data-placeholder="Pilih subkategori">
            <?php if (!empty($subkategori_struk_options)) { foreach ($subkategori_struk_options as $opt) { ?>
            <option value="<?php echo (int) $opt['id']; ?>"><?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?></option>
            <?php } } ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-warning">Tambah</button>
      </div>
    </form>
    </div>
  </div>
</div>