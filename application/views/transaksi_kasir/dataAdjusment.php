<table class="table" style="font-size:11px;">
    <tr style="background: #2A303A;color:white;font-weight: bold;">
        <th style="width:3%;">No</th>
        <th width="13%">No Invoice</th>
        <th>Jam</th>
        <th>Tipe Bayar</th>
        <th style="text-align:right;">Subtotal</th>
        <th style="text-align:right;">Ongkir</th>
        <th style="text-align:right;">Diskon Member</th>
        <th style="text-align:right;">Diskon</th>
        <th style="text-align:right;">Poin Reimburs</th>
        <th style="text-align:right;">Diskon Peritem</th>
        <th style="text-align:right;">Total</th>
    </tr>

    <?php
        $i = 1;
        foreach($setAdjusment as $row){
    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $row->no_invoice; ?></td>
        <td><?php echo date_format(date_create($row->tanggal),'H:i'); ?></td>
        <td><label class="label label-success"><a href="#modalAdjusment" class="adjustDialog" data-toggle="modal" id="<?php echo $row->no_invoice; ?>" style="color:white;"><?php echo $row->payment_type." ".$row->account; ?></a></label></td>
        <td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
        <td align="right"><?php echo number_format($row->ongkir,'0',',','.'); ?></td>
        <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
        <td align="right"><?php echo number_format($row->diskon_free,'0',',','.'); ?></td>
        <td align="right"><?php echo number_format($row->poin_value,'0',',','.'); ?></td>
        <td align="right"><?php echo number_format($row->diskon_otomatis,'0',',','.'); ?></td>
        <td align="right"><?php echo number_format(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis),'0',',','.'); ?></td>
    </tr>
    <?php $i++; } ?>
</table>

<script type="text/javascript">
    $('.adjustDialog').on("click",function(){
        var urlAdj = "<?php echo base_url('kasir/modalAdjustment'); ?>";
        var noInvoice = this.id;
        var tanggal = "<?php echo $tanggal; ?>";
        var idUser = "<?php echo $idUser; ?>";

        $('.bodyAdjustPaymentType').load(urlAdj,{noInvoice : noInvoice, tanggal : tanggal, idUser : idUser});   
    });
</script>