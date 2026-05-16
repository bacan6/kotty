<!-- Page Content Ends -->
            <!-- ================== -->

            <!-- Footer Start -->
            <footer class="footer">
                <?php echo $footer; ?>
            </footer>
            <!-- Footer Ends -->
        </section>
        <!-- Main Content Ends -->

        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/modernizr.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/pace.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/wow.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.nicescroll.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/chat/moment-2.2.1.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/datepicker/js/moment.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/datepicker/js/bootstrap-datetimepicker.min.js"></script>

        <!-- Counter-up -->
        <script src="<?php echo base_url('assets'); ?>/js/waypoints.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.counterup.min.js" type="text/javascript"></script>

        <!-- sweet alerts -->
        <script src="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.init.js"></script>

        <script src="<?php echo base_url('assets'); ?>/js/jquery.app.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <style type="text/css">.modal-open .select2-drop { z-index: 11050 !important; }</style>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/dataTables.bootstrap.js"></script>
      
        <script type="text/javascript">
             var urlAjaxProdukVoucher = "<?php echo base_url('Voucergenerate/ajax_produk_voucher'); ?>";

             $('.datetimepicker').datetimepicker({
             format: 'YYYY-MM-DD HH:mm:ss',
              widgetPositioning: {
                  horizontal: 'right',
                  vertical: 'bottom'
              }
          
        });

            $('.datetimepicker-filter').datetimepicker({
             format: 'YYYY-MM-DD HH:mm:ss',
              widgetPositioning: { horizontal: 'right', vertical: 'bottom' }
        });

            var voucerBase = "<?php echo base_url('Voucergenerate/data_voucer'); ?>";
            window.getVoucherListUrl = function () {
                var q = {};
                var ds = $('#filter_voucher_date_start').val();
                var de = $('#filter_voucher_date_end').val();
                var br = $('#filter_voucher_brand').val();
                var kind = $('#filter_voucher_kind').val();
                if (ds) q.date_start = ds;
                if (de) q.date_end = de;
                if (br) q.brand = br;
                if (kind) q.kind = kind;
                var s = $.param(q);
                return s ? voucerBase + '?' + s : voucerBase;
            };
            function reloadVoucherTable() {
                $('#data-voucer').load(window.getVoucherListUrl());
            }
            window.reloadVoucherTable = reloadVoucherTable;
            reloadVoucherTable();

            $('#filter_voucher_apply').on('click', reloadVoucherTable);
            $('#filter_voucher_reset').on('click', function () {
                $('#filter_voucher_date_start,#filter_voucher_date_end').val('');
                $('#filter_voucher_brand,#filter_voucher_kind').val('');
                reloadVoucherTable();
            });

            function validateNilaiClient(nilaiTipe, nilaiRaw) {
                var t = $.trim(String(nilaiRaw || ''));
                var normalized = t.replace(/\s+/g, '').replace(/,/g, '.');
                if (normalized === '' || isNaN(normalized)) return 'Nilai tidak valid';
                var num = parseFloat(normalized);
                if (nilaiTipe === 'percent') {
                    if (num > 100) return 'Nilai persentase tidak boleh lebih dari 100';
                    if (num < 0) return 'Nilai persentase tidak boleh negatif';
                }
                return null;
            }
            window.validateNilaiClient = validateNilaiClient;

            function showNilaiFeedbackAdd(err) {
                var $w = $('#nilai_wrap_add');
                var $h = $('#nilai_hint_add');
                if (err) {
                    $w.addClass('has-error');
                    $h.text(err).show();
                    swal({ title: 'Validasi nilai', text: err, type: 'error', confirmButtonText: 'OK' });
                } else {
                    $w.removeClass('has-error');
                    $h.hide().text('');
                }
            }

            function runNilaiValidateAdd() {
                var err = validateNilaiClient($('#nilai_tipe').val(), $('#nilai').val());
                showNilaiFeedbackAdd(err);
                return err === null;
            }

            $(document).on('change', '#nilai_tipe', function () {
                if ($('#nilai').val() !== '') runNilaiValidateAdd();
            });
            $(document).on('change blur', '#nilai', function () {
                if ($('#nilai').val() !== '') runNilaiValidateAdd();
                else showNilaiFeedbackAdd(null);
            });

            var $itemWrap = $('#data-voucer-item');
            if ($itemWrap.length && $itemWrap.data('load-url')) {
                $itemWrap.load($itemWrap.data('load-url'));
            }

          $('#submit').submit(function(e){
              e.preventDefault();
                if (!runNilaiValidateAdd()) return false;
                var fdata = new FormData(this);
                var skuVals = $('#id_produk_voucher').select2('val');
                if (skuVals) {
                    if (!$.isArray(skuVals)) skuVals = [skuVals];
                    skuVals.forEach(function (id) {
                        if (id) fdata.append('id_produk[]', id);
                    });
                }
                url = "<?php echo base_url('Voucergenerate/add_voucer_sql'); ?>";

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
                        } catch (e) {}
                    }
                    $('#submit').trigger("reset");
                    $('#brand_voucher').select2('val', '');
                    $('#id_produk_voucher').select2('val', []);
                    showNilaiFeedbackAdd(null);
                    reloadVoucherTable();
                    $('#add-voucer').modal('hide');

                    if (parseInt(data, 10) > 0) {
                        swal('Berhasil', 'Voucher berhasil ditambahkan.', 'success');
                    } else {
                         swal({ title: 'Gagal', text: 'Voucher gagal ditambahkan.', type: 'error', confirmButtonText: 'OK' });
                    }
                }
             })
          
          });
          jQuery(".select2").select2({
            width: '100%'
        });

        /* Bootstrap modal focus trap blocks Select2 search (dropdown/search not inside .modal) */
        if ($.fn.modal && $.fn.modal.Constructor) {
            $.fn.modal.Constructor.prototype.enforceFocus = function () {};
        }

        function destroySkuSelect2Add() {
            var $sku = $('#id_produk_voucher');
            if ($sku.length && $sku.data('select2')) {
                $sku.select2('destroy');
            }
        }

        function initSkuSelect2Add() {
            var $sku = $('#id_produk_voucher');
            if (!$sku.length) return;
            destroySkuSelect2Add();
            $sku.select2({
                multiple: true,
                placeholder: 'Cari SKU / nama (min 3 huruf)',
                width: '100%',
                minimumInputLength: 3,
                ajax: {
                    url: urlAjaxProdukVoucher,
                    dataType: 'json',
                    quietMillis: 100,
                    data: function (term, page) {
                        return {
                            term: term,
                            brands: ($('#brand_voucher').val() || []).join(',')
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
                    callback([]);
                }
            });
        }

        $('#add-voucer').on('show.bs.modal', function () {
            showNilaiFeedbackAdd(null);
        });
        $('#add-voucer').on('shown.bs.modal', function () {
            initSkuSelect2Add();
        });
        $('#add-voucer').on('hidden.bs.modal', function () {
            destroySkuSelect2Add();
        });

        $('#brand_voucher').on('change', function () {
            if ($('#id_produk_voucher').data('select2')) {
                $('#id_produk_voucher').select2('val', []);
            }
        });

            function showNilaiFeedbackStruk(err) {
                var $w = $('#nilai_wrap_struk');
                var $h = $('#nilai_hint_struk');
                if (err) {
                    $w.addClass('has-error');
                    $h.text(err).show();
                    swal({ title: 'Validasi nilai', text: err, type: 'error', confirmButtonText: 'OK' });
                } else {
                    $w.removeClass('has-error');
                    $h.hide().text('');
                }
            }
            function runNilaiValidateStruk() {
                var err = validateNilaiClient($('#nilai_tipe_struk').val(), $('#nilai_struk').val());
                showNilaiFeedbackStruk(err);
                return err === null;
            }
            $(document).on('change', '#nilai_tipe_struk', function () {
                if ($('#nilai_struk').val() !== '') runNilaiValidateStruk();
            });
            $(document).on('change blur', '#nilai_struk', function () {
                if ($('#nilai_struk').val() !== '') runNilaiValidateStruk();
                else showNilaiFeedbackStruk(null);
            });

            function destroySkuSelect2Struk() {
                var $sku = $('#id_produk_voucher_struk');
                if ($sku.length && $sku.data('select2')) {
                    $sku.select2('destroy');
                }
            }
            function destroySkuSelect2StrukGet() {
                var $sku = $('#id_produk_voucher_struk_get');
                if ($sku.length && $sku.data('select2')) {
                    $sku.select2('destroy');
                }
            }
            function initSkuSelect2Struk() {
                var $sku = $('#id_produk_voucher_struk');
                if (!$sku.length) return;
                destroySkuSelect2Struk();
                $sku.select2({
                    multiple: true,
                    placeholder: 'Cari SKU / nama (min 3 huruf)',
                    width: '100%',
                    minimumInputLength: 3,
                    ajax: {
                        url: urlAjaxProdukVoucher,
                        dataType: 'json',
                        quietMillis: 100,
                        data: function (term, page) {
                            return {
                                term: term,
                                brands: ($('#brand_voucher_struk').val() || []).join(',')
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
                        callback([]);
                    }
                });
            }
            function initSkuSelect2StrukGet() {
                var $sku = $('#id_produk_voucher_struk_get');
                if (!$sku.length) return;
                destroySkuSelect2StrukGet();
                $sku.select2({
                    multiple: true,
                    placeholder: 'Cari SKU / nama (min 3 huruf)',
                    width: '100%',
                    minimumInputLength: 3,
                    ajax: {
                        url: urlAjaxProdukVoucher,
                        dataType: 'json',
                        quietMillis: 100,
                        data: function (term, page) {
                            return {
                                term: term,
                                brands: ($('#brand_voucher_struk_get').val() || []).join(',')
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
                        callback([]);
                    }
                });
            }

            $('#add-voucher-struk').on('show.bs.modal', function () {
                showNilaiFeedbackStruk(null);
            });
            $('#add-voucher-struk').on('shown.bs.modal', function () {
                $('#brand_voucher_struk').select2({ width: '100%' });
                $('#brand_voucher_struk_get').select2({ width: '100%' });
                if ($('#kategori_struk_get').length) {
                    $('#kategori_struk_get').select2({ width: '100%', allowClear: true });
                }
                if ($('#subkategori_struk_get').length) {
                    $('#subkategori_struk_get').select2({ width: '100%' });
                }
                initSkuSelect2Struk();
                initSkuSelect2StrukGet();
            });
            $('#add-voucher-struk').on('hidden.bs.modal', function () {
                if ($('#brand_voucher_struk').data('select2')) {
                    $('#brand_voucher_struk').select2('destroy');
                }
                if ($('#brand_voucher_struk_get').data('select2')) {
                    $('#brand_voucher_struk_get').select2('destroy');
                }
                if ($('#kategori_struk_get').length && $('#kategori_struk_get').data('select2')) {
                    $('#kategori_struk_get').select2('destroy');
                }
                if ($('#subkategori_struk_get').length && $('#subkategori_struk_get').data('select2')) {
                    $('#subkategori_struk_get').select2('destroy');
                }
                destroySkuSelect2Struk();
                destroySkuSelect2StrukGet();
            });
            $('#brand_voucher_struk').on('change', function () {
                if ($('#id_produk_voucher_struk').data('select2')) {
                    $('#id_produk_voucher_struk').select2('val', []);
                }
            });
            $('#brand_voucher_struk_get').on('change', function () {
                if ($('#id_produk_voucher_struk_get').data('select2')) {
                    $('#id_produk_voucher_struk_get').select2('val', []);
                }
            });

            $('#submit-struk').submit(function (e) {
                e.preventDefault();
                if (!runNilaiValidateStruk()) return false;
                var fdata = new FormData(this);
                var skuVals = $('#id_produk_voucher_struk').select2('val');
                if (skuVals) {
                    if (!$.isArray(skuVals)) skuVals = [skuVals];
                    skuVals.forEach(function (id) {
                        if (id) fdata.append('id_produk[]', id);
                    });
                }
                var skuValsGet = $('#id_produk_voucher_struk_get').select2('val');
                if (skuValsGet) {
                    if (!$.isArray(skuValsGet)) skuValsGet = [skuValsGet];
                    skuValsGet.forEach(function (id) {
                        if (id) fdata.append('id_produk_struk_get[]', id);
                    });
                }
                $.ajax({
                    url: "<?php echo base_url('Voucergenerate/add_voucher_struk_sql'); ?>",
                    type: 'post',
                    data: fdata,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        var d = typeof data === 'string' ? data.trim() : data;
                        if (typeof d === 'string' && d.charAt(0) === '{') {
                            try {
                                var j = JSON.parse(d);
                                if (j.ok === false) {
                                    swal({ title: 'Validasi', text: j.msg || 'Data tidak valid', type: 'error', confirmButtonText: 'OK' });
                                    return;
                                }
                            } catch (e2) {}
                        }
                        $('#submit-struk').trigger('reset');
                        $('#brand_voucher_struk').select2('val', '');
                        $('#brand_voucher_struk_get').select2('val', '');
                        if ($('#kategori_struk_get').length && $('#kategori_struk_get').data('select2')) {
                            $('#kategori_struk_get').select2('val', '');
                        }
                        if ($('#subkategori_struk_get').length && $('#subkategori_struk_get').data('select2')) {
                            $('#subkategori_struk_get').select2('val', '');
                        }
                        $('#id_produk_voucher_struk').select2('val', []);
                        $('#id_produk_voucher_struk_get').select2('val', []);
                        showNilaiFeedbackStruk(null);
                        reloadVoucherTable();
                        $('#add-voucher-struk').modal('hide');
                        if (parseInt(data, 10) > 0) {
                            swal('Berhasil', 'Voucher struk berhasil ditambahkan.', 'success');
                        } else {
                            swal({ title: 'Gagal', text: 'Data gagal disimpan.', type: 'error', confirmButtonText: 'OK' });
                        }
                    }
                });
            });
       
        </script>
    </body>
</html>
