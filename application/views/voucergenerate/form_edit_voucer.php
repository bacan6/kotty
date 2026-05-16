<?php
	foreach ($voucer->result() as $row) {
		$is_struk = isset($row->voucher_struk) && (int)$row->voucher_struk === 1;
		$sel_brand = isset($row->brand_ids) && $row->brand_ids !== '' ? array_filter(array_map('intval', explode(',', $row->brand_ids))) : array();
		$sel_brand_get = isset($row->brand_ids_voucher_struk) && $row->brand_ids_voucher_struk !== '' ? array_filter(array_map('intval', explode(',', $row->brand_ids_voucher_struk))) : array();
		$sel_sub_get = isset($row->subkategori_ids_voucher_struk) && $row->subkategori_ids_voucher_struk !== '' ? array_filter(array_map('intval', explode(',', $row->subkategori_ids_voucher_struk))) : array();
		$sel_kat_get = isset($row->kategori_id_voucher_struk) && (int) $row->kategori_id_voucher_struk > 0 ? (int) $row->kategori_id_voucher_struk : 0;
		$nt = (isset($row->nilai_tipe) && $row->nilai_tipe === 'percent') ? 'percent' : 'rp';
		$sku_preload = array();
		foreach ($produk_preview as $pid => $label) {
			$sku_preload[] = array('id' => (string)$pid, 'text' => $label);
		}
		$sku_preload_struk_get = array();
		foreach ($produk_preview_struk_get as $pid => $label) {
			$sku_preload_struk_get[] = array('id' => (string)$pid, 'text' => $label);
		}
		$sku_ids_csv = implode(',', array_map('strval', array_keys($produk_preview)));
		$sku_ids_csv_struk_get = implode(',', array_map('strval', array_keys($produk_preview_struk_get)));
		$mb = isset($row->minimal_belanja) && $row->minimal_belanja !== null && $row->minimal_belanja !== '' ? $row->minimal_belanja : '';
		$mg = isset($row->min_get_voucher_struk) && $row->min_get_voucher_struk !== null && $row->min_get_voucher_struk !== '' ? $row->min_get_voucher_struk : '';
		$svs = isset($row->start_voucher_struk) && $row->start_voucher_struk ? $row->start_voucher_struk : '';
		$evs = isset($row->end_voucher_struk) && $row->end_voucher_struk ? $row->end_voucher_struk : '';
?>
<form method="post" action="" enctype="multipart/form-data" id="submit2">
<div class="modal-body">
  <input type="hidden" name="id" value="<?php echo $row->id_generate; ?>"/>
  <p style="margin-bottom:12px;"><?php echo $is_struk
    ? '<span class="label label-warning">Voucher struk</span>'
    : '<span class="label label-info">Voucher cetak</span>'; ?></p>
	<div class="form-group">
    <label for="nama_voucer2">Nama Voucher</label>
		<input type="text" name="nama_voucer" class="form-control" placeholder="Nama voucer" id="nama_voucer2" value="<?php echo htmlspecialchars($row->nm_voucher); ?>"/>
	</div>
  <?php if ($is_struk) { ?>
  <p class="text-muted" style="font-size:12px;"><strong>Lingkup pakai voucher</strong></p>
  <?php } ?>
  <div class="form-group">
    <label for="brand_voucher_edit">Brand<?php echo $is_struk ? ' (pakai)' : ''; ?></label>
    <select name="brand[]" id="brand_voucher_edit" class="select2" multiple="multiple" data-placeholder="Pilih brand">
      <?php foreach ($brand->result() as $br) {
        $sel = in_array((int)$br->id_brand, $sel_brand, true) ? ' selected' : '';
      ?>
      <option value="<?php echo (int)$br->id_brand; ?>"<?php echo $sel; ?>><?php echo htmlspecialchars($br->brand); ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="form-group">
    <label for="id_produk_voucher_edit">Produk (SKU)<?php echo $is_struk ? ' — pakai' : ''; ?></label>
    <input type="hidden" id="id_produk_voucher_edit" style="width:100%;" value="<?php echo htmlspecialchars($sku_ids_csv); ?>"/>
  </div>
	<div class="form-group">
    <label for="tgl_mulai2">Tanggal berlaku</label>
		<input type="text" name="berlaku_mulai" class="form-control datetimepicker" value="<?php echo htmlspecialchars($row->berlaku_mulai); ?>" placeholder="Tanggal Berlaku" id="tgl_mulai2"/>
	</div>
	<div class="form-group">
    <label for="tgl_selesai2">Tanggal expired</label>
		<input type="text" name="berlaku_selesai" class="form-control datetimepicker" value="<?php echo htmlspecialchars($row->berlaku_selesai); ?>" placeholder="Tanggal Selesai" id="tgl_selesai2"/>
	</div>
  <?php if ($is_struk) { ?>
  <div class="form-group">
    <label for="jumlah_edit_struk">Jumlah voucher</label>
    <input type="text" name="jumlah" class="form-control" id="jumlah_edit_struk" value="<?php echo htmlspecialchars((string)$row->jml_voucher); ?>"/>
  </div>
  <?php } ?>
  <div class="form-group">
    <label for="nilai_tipe_edit">Tipe nilai</label>
    <select name="nilai_tipe" id="nilai_tipe_edit" class="form-control">
      <option value="rp"<?php echo $nt === 'rp' ? ' selected' : ''; ?>>Rupiah (Rp)</option>
      <option value="percent"<?php echo $nt === 'percent' ? ' selected' : ''; ?>>Persen (%)</option>
    </select>
  </div>
  <div class="form-group" id="nilai_wrap_edit">
    <label for="jml2">Nilai</label>
		<input type="text" name="nilai" class="form-control" placeholder="Nilai" value="<?php echo htmlspecialchars($row->nilai); ?>" id="jml2"/>
    <span class="help-block text-danger" id="nilai_hint_edit" style="display:none;"></span>
	</div>

  <?php if ($is_struk) { ?>
  <hr/>
  <div class="form-group">
    <label for="minimal_belanja_edit">Minimal belanja (pakai potongan)</label>
    <input type="text" name="minimal_belanja" class="form-control" id="minimal_belanja_edit" value="<?php echo htmlspecialchars((string)$mb); ?>"/>
  </div>
  <div class="form-group">
    <label for="min_get_edit">Minimal belanja dapat voucher</label>
    <input type="text" name="min_get_voucher_struk" class="form-control" id="min_get_edit" value="<?php echo htmlspecialchars((string)$mg); ?>"/>
  </div>
  <div class="form-group">
    <label for="start_voucher_struk_edit">Mulai periode voucher struk</label>
    <input type="text" name="start_voucher_struk" class="form-control datetimepicker" id="start_voucher_struk_edit" value="<?php echo htmlspecialchars((string)$svs); ?>"/>
  </div>
  <div class="form-group">
    <label for="end_voucher_struk_edit">Selesai periode voucher struk</label>
    <input type="text" name="end_voucher_struk" class="form-control datetimepicker" id="end_voucher_struk_edit" value="<?php echo htmlspecialchars((string)$evs); ?>"/>
  </div>
  <p class="text-muted" style="font-size:12px;"><strong>Syarat mendapat voucher struk</strong></p>
  <div class="form-group">
    <label for="brand_voucher_edit_struk_get">Brand</label>
    <select name="brand_struk_get[]" id="brand_voucher_edit_struk_get" class="select2" multiple="multiple" data-placeholder="Pilih brand">
      <?php foreach ($brand->result() as $br) {
        $selg = in_array((int)$br->id_brand, $sel_brand_get, true) ? ' selected' : '';
      ?>
      <option value="<?php echo (int)$br->id_brand; ?>"<?php echo $selg; ?>><?php echo htmlspecialchars($br->brand); ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="form-group">
    <label for="id_produk_voucher_edit_struk_get">Produk (SKU)</label>
    <input type="hidden" id="id_produk_voucher_edit_struk_get" style="width:100%;" value="<?php echo htmlspecialchars($sku_ids_csv_struk_get); ?>"/>
  </div>
  <div class="form-group">
    <label for="kategori_struk_get_edit">Kategori</label>
    <select name="kategori_struk_get" id="kategori_struk_get_edit" class="select2-struk-kat-edit" data-placeholder="Pilih kategori (opsional)">
      <option value=""></option>
      <?php if (!empty($kategori_struk_options)) { foreach ($kategori_struk_options as $opt) {
        $selkat = $sel_kat_get === (int) $opt['id'] ? ' selected' : '';
      ?>
      <option value="<?php echo (int) $opt['id']; ?>"<?php echo $selkat; ?>><?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?></option>
      <?php } } ?>
    </select>
  </div>
  <div class="form-group">
    <label for="subkategori_struk_get_edit">Subkategori</label>
    <select name="subkategori_struk_get[]" id="subkategori_struk_get_edit" class="select2" multiple="multiple" data-placeholder="Pilih subkategori">
      <?php if (!empty($subkategori_struk_options)) { foreach ($subkategori_struk_options as $opt) {
        $selsub = in_array((int) $opt['id'], $sel_sub_get, true) ? ' selected' : '';
      ?>
      <option value="<?php echo (int) $opt['id']; ?>"<?php echo $selsub; ?>><?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?></option>
      <?php } } ?>
    </select>
  </div>
  <?php } ?>

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button type="submit" class="btn btn-primary edit-voucer-sql">Simpan</button>
</div>
</form>


<?php } ?>

<script type="text/javascript">
 var urlAjaxProdukVoucher = "<?php echo base_url('Voucergenerate/ajax_produk_voucher'); ?>";
 var voucherSkuPreloadEdit = <?php echo json_encode($sku_preload); ?>;
 var voucherSkuPreloadEditStrukGet = <?php echo json_encode($sku_preload_struk_get); ?>;
 var editFormHasStrukGet = <?php echo isset($is_struk) && $is_struk ? 'true' : 'false'; ?>;
 $('.datetimepicker').datetimepicker({
 	 format: 'YYYY-MM-DD HH:mm:ss',
          widgetPositioning: {
              horizontal: 'right',
              vertical: 'bottom'
          }

        });
    function showNilaiFeedbackEdit(err) {
      var $w = $('#nilai_wrap_edit');
      var $h = $('#nilai_hint_edit');
      if (err) {
        $w.addClass('has-error');
        $h.text(err).show();
        swal({ title: 'Validasi nilai', text: err, type: 'error', confirmButtonText: 'OK' });
      } else {
        $w.removeClass('has-error');
        $h.hide().text('');
      }
    }
    function runNilaiValidateEdit() {
      var vc = window.validateNilaiClient;
      var err = (typeof vc === 'function') ? vc($('#nilai_tipe_edit').val(), $('#jml2').val()) : null;
      showNilaiFeedbackEdit(err);
      return err === null;
    }
    $('#nilai_tipe_edit').on('change', function () {
      if ($('#jml2').val() !== '') runNilaiValidateEdit();
    });
    $('#jml2').on('change blur', function () {
      if ($('#jml2').val() !== '') runNilaiValidateEdit();
      else showNilaiFeedbackEdit(null);
    });

	 $('#submit2').submit(function(e){
              e.preventDefault();
                if (!runNilaiValidateEdit()) return false;
                var fdata = new FormData(this);
                var skuVals = $('#id_produk_voucher_edit').select2('val');
                if (skuVals) {
                    if (!$.isArray(skuVals)) skuVals = [skuVals];
                    skuVals.forEach(function (id) {
                        if (id) fdata.append('id_produk[]', id);
                    });
                }
                if ($('#id_produk_voucher_edit_struk_get').length && $('#id_produk_voucher_edit_struk_get').data('select2')) {
                  var skuG = $('#id_produk_voucher_edit_struk_get').select2('val');
                  if (skuG) {
                    if (!$.isArray(skuG)) skuG = [skuG];
                    skuG.forEach(function (id) {
                      if (id) fdata.append('id_produk_struk_get[]', id);
                    });
                  }
                }
                url = "<?php echo base_url('Voucergenerate/edit_voucer_sql'); ?>";

             $.ajax({
                  url: url,
                  type: 'post',
                  data: fdata,
                  contentType: false,
                  processData: false,
                  success: function(data){
                    var d = typeof data === 'string' ? data.trim() : data;
                    if (typeof d === 'string' && d.charAt(0) === '{') {
                        try {
                            var j = JSON.parse(d);
                            if (j.ok === false) {
                                swal({ title: 'Validasi', text: j.msg || 'Data tidak valid', type: 'error', confirmButtonText: 'OK' });
                                return;
                            }
                        } catch (ex) {}
                    }
                    showNilaiFeedbackEdit(null);
                    var u = (typeof window.getVoucherListUrl === 'function') ? window.getVoucherListUrl() : "<?php echo base_url('Voucergenerate/data_voucer'); ?>";
                    $('#data-voucer').load(u);
                    $('#edit-voucer-modal').modal('hide');
                    $('.modal-backdrop').hide();

                    if (parseInt(data, 10) > 0) {
                        swal('Berhasil', 'Voucher berhasil diperbarui.', 'success');
                    } else {
                         swal('Gagal', 'Voucher gagal diperbarui.', 'error');
                    }
                }
             })

          });

  $('#brand_voucher_edit').select2({ width: '100%' });
  $('#id_produk_voucher_edit').select2({
    multiple: true,
    width: '100%',
    placeholder: 'Cari SKU / nama (min 3 huruf)',
    minimumInputLength: 0,
    ajax: {
      url: urlAjaxProdukVoucher,
      dataType: 'json',
      quietMillis: 100,
      data: function (term, page) {
        return {
          term: term,
          brands: ($('#brand_voucher_edit').val() || []).join(',')
        };
      },
      results: function (data) {
        var myResults = [];
        $.each(data, function (index, item) {
          myResults.push({ id: item.id, text: item.text });
        });
        return { results: myResults };
      }
    },
    initSelection: function (element, callback) {
      var pre = voucherSkuPreloadEdit || [];
      if (pre.length) {
        callback(pre);
        return;
      }
      var raw = $.trim(element.val() || '');
      if (!raw) {
        callback([]);
        return;
      }
      callback($.map(raw.split(','), function (id) {
        id = $.trim(id);
        return id ? { id: id, text: id } : null;
      }));
    }
  });
  setTimeout(function () {
    if (voucherSkuPreloadEdit && voucherSkuPreloadEdit.length) {
      $('#id_produk_voucher_edit').select2('data', voucherSkuPreloadEdit);
    }
  }, 0);

  if (editFormHasStrukGet && $('#brand_voucher_edit_struk_get').length) {
    $('#brand_voucher_edit_struk_get').select2({ width: '100%' });
    $('#id_produk_voucher_edit_struk_get').select2({
      multiple: true,
      width: '100%',
      placeholder: 'Cari SKU / nama (min 3 huruf)',
      minimumInputLength: 0,
      ajax: {
        url: urlAjaxProdukVoucher,
        dataType: 'json',
        quietMillis: 100,
        data: function (term, page) {
          return {
            term: term,
            brands: ($('#brand_voucher_edit_struk_get').val() || []).join(',')
          };
        },
        results: function (data) {
          var myResults = [];
          $.each(data, function (index, item) {
            myResults.push({ id: item.id, text: item.text });
          });
          return { results: myResults };
        }
      },
      initSelection: function (element, callback) {
        var pre = voucherSkuPreloadEditStrukGet || [];
        if (pre.length) {
          callback(pre);
          return;
        }
        var raw = $.trim(element.val() || '');
        if (!raw) {
          callback([]);
          return;
        }
        callback($.map(raw.split(','), function (id) {
          id = $.trim(id);
          return id ? { id: id, text: id } : null;
        }));
      }
    });
    setTimeout(function () {
      if (voucherSkuPreloadEditStrukGet && voucherSkuPreloadEditStrukGet.length) {
        $('#id_produk_voucher_edit_struk_get').select2('data', voucherSkuPreloadEditStrukGet);
      }
    }, 0);
    $('#brand_voucher_edit_struk_get').on('change', function () {
      if ($('#id_produk_voucher_edit_struk_get').data('select2')) {
        $('#id_produk_voucher_edit_struk_get').select2('val', []);
      }
    });
    if ($('#kategori_struk_get_edit').length) {
      $('#kategori_struk_get_edit').select2({ width: '100%', allowClear: true });
    }
    if ($('#subkategori_struk_get_edit').length) {
      $('#subkategori_struk_get_edit').select2({ width: '100%' });
    }
  }
</script>
