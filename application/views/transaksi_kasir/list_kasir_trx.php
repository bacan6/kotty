<div class="col-md-12">
    <h3><?php echo date_format(date_create($tanggal),'d M Y'); ?></h3>
</div>

<div class="col-md-12" style="margin-top: 20px;">

    <table class="table">
        <tr>
            <th width="5%">No</th>
            <th>Nama Kasir</th>
            <th width="30%" style="text-align: right;">Modal Awal</th>
            <th width="20%" style="text-align: right;">Status</th>
        </tr>

        <?php
            $i = 1;
            foreach($list_kasir as $ksr){
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $ksr->first_name; ?></td>
            <td align="right">
                <?php
                    $id_kasir = $ksr->id;
                    $modal_kasir = $this->model_penjualan->modal_kasir($id_kasir,$tanggal);

                    foreach($modal_kasir as $dt){
                        echo number_format($dt->modal,'0',',','.');
                    }
                ?>
            </td>
            <td align="right">
                <?php
                    $cek_modal = $this->model_penjualan->cek_status_kasir($id_kasir,$tanggal);

                    //cek status kasir
                    if($cek_modal < 1){
                        echo "<label class='label label-info'><a style='color:white;' data-toggle='modal' href='#myModal' id='".$ksr->id."' class='input-modal' data-tanggal ='".$tanggal."'>Not Register</a></label>";
                    } else {
                        $cekClose = $this->model_penjualan->cekClose($id_kasir,$tanggal);

                        if($cekClose < 1){
                            echo "<label class='label label-success'><a data-toggle='modal' style='color:white;' href='#closing-kasir' id='".$ksr->id."' data-tanggal ='".$tanggal."' class='closing-kasir'>Registered</a></label> <label class='label label-warning'><a data-toggle='modal' style='color:white;' target='__blank' href='".base_url('kasir/adjusment?id='.$ksr->id.'&tanggal='.$tanggal)."'>Adjusment</a></label>";
                        } else {
                            echo "<label class='label label-danger'><a style='color:white;' data-toggle='modal' href='#closing-kasir-close' id='".$ksr->id."' data-tanggal ='".$tanggal."' class='closing-kasir-close'>Closed</a></label>";
                        }
                    }
                ?>
            </td>
        </tr>
        <?php $i++; } ?>
    </table>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Modal Kasir</h4>
            </div>
            
            <div class="modal-body" id="form-modal">
            
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="simpan-modal">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="closing-kasir" data-backdrop="static" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Closing Kasir</h4>
            </div>
            
            <div class="modal-body" id="form-closing" style="padding:30px;">
            
            </div>

            <div class="modal-footer" id="removeFooter">
                <a class="btn btn-success" onclick="printContent('form-closing')" id="printExecute" data-tanggal="<?php echo $tanggal; ?>"><i class="fa fa-print"></i> Print</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="proses-closing">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="closing-kasir-close" data-backdrop="static" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Closing Kasir</h4>
            </div>
            
            <div class="modal-body" id="form-closing-close" style="padding:30px;">
            
            </div>

            <div class="modal-footer">
                <a class="btn btn-primary" onclick="printContent('form-closing-close')" id="printExecute" data-tanggal="<?php echo $tanggal; ?>"><i class="fa fa-print"></i> Print</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
	$('.input-modal').on("click",function(){
		url = "<?php echo base_url('kasir/form_modal'); ?>";

        tanggal = $(this).data('tanggal');
		id = this.id;
        $('#form-modal').load(url,{id : id, tanggal : tanggal});
    });

    $('#simpan-modal').on("click",function(){
        id_kasir    = $('#id_kasir').val();
        modal_kasir = $('#modal_kasir').val();
        tanggal     = $('#tanggal').val();

        url     = "<?php echo base_url('kasir/input_modal_sql'); ?>";
        url_trx = "<?php echo base_url('kasir/list_kasir_trx'); ?>"; 

        $.post(url,{id_kasir : id_kasir, modal_kasir : modal_kasir, tanggal : tanggal}, function(){
            $("#myModal").modal('hide');
            $('#list-kasir-trx').load(url_trx,{tanggal : tanggal});
            $('.modal-backdrop').remove();
        });
    });

    $('.closing-kasir').on("click",function(){
        url = "<?php echo base_url('kasir/form_closing_kasir'); ?>";

        id = this.id;
        tangal = $(this).data('tanggal');
        $('#form-closing').load(url,{id : id, tanggal : tanggal});
    });

    $('.closing-kasir-close').on("click",function(){
        url = "<?php echo base_url('kasir/closingInsertSuccess'); ?>";

        id = this.id;
        tanggal = $(this).data('tanggal');
        $('#form-closing-close').load(url,{tanggal : tanggal, idUser : id});
    });

    $('#proses-closing').on("click",function(){
        jsonObj     = [];

        idUser      = $('#idUser').val();
        tanggal     = $('#tanggal').val();

        url         = "<?php echo base_url('kasir/closingInsertSuccess'); ?>";
        urlTrx      = "<?php echo base_url('kasir/list_kasir_trx'); ?>"; 
        printButton = "<?php echo base_url('kasir/printButton'); ?>";

        $("input[id=value]").each(function(){
            var valueObj    = $(this).val();
            var paymentType = $(this).data('payment_type');
            var accountType = $(this).data('account_type'); 

            item = {};

            item['value'] = valueObj;
            item['paymentType'] = paymentType;
            item['accountType'] = accountType;

            jsonObj.push(item);
        });

        $.ajax({
            method          : "POST",
            url             : "<?php echo base_url('kasir/submit_closing_sql'); ?>",
            data            : {param : JSON.stringify(jsonObj), idUser : idUser, tanggal : tanggal},
            beforeSend      : function(){
                                $('#form-closing').load("<?php echo base_url('kasir/loader'); ?>");
                              }
        }).done(function(data){
            $("#form-closing").load(url,{idUser : idUser, tanggal : tanggal});
            $('#removeFooter').load(printButton,{tanggal : tanggal});
        });
    });

    $('#printExecute').on("click",function(){
        tanggal = $(this).data('tanggal');

        urlTrx      = "<?php echo base_url('kasir/list_kasir_trx'); ?>";

        $('#list-kasir-trx').load(urlTrx,{tanggal : tanggal});
    });
</script>