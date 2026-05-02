<?php
    foreach($hutang_ditagih->result() as $row){
?>
    <tr>
        <td>
<?php
    $uri = $this->uri->segment(1);

    if($uri!='laporan'){
?>
    <a href="<?php echo base_url('finance/invoice_penagihan?no_tagihan='.$row->no_tagihan); ?>"><?php echo $row->no_tagihan; ?></a>
    <?php } else {  
        echo $row->no_tagihan;
    } ?>
    </td>
    <td><?php echo $row->supplier; ?></td>
    <td align="center">
        <?php
            $jatuh_tempo = date_create($row->jatuh_tempo);

            echo date_format($jatuh_tempo,'d F Y');
        ?>
    </td>

    <td align="right">
        <?php 
            $no_tagihan = $row->no_tagihan;
            $hutang_harian = $this->modelLaporan->hutang_harian($no_tagihan);              						
                						
            if($hutang_harian!=''){
                echo number_format($hutang_harian,'0',',','.');
            } else {
                echo "";
            }
        ?>
    </td>
    <td align="right">
        <?php
            $hutang_7_hari = $this->modelLaporan->hutang_7_hari($no_tagihan);
                					
            if($hutang_7_hari!=''){
                echo number_format($hutang_7_hari,'0',',','.');
            } else {
                echo "";
            }

        ?>
    </td>
    <td align="right">
        <?php
            $hutang_14_hari = $this->modelLaporan->hutang_14_hari($no_tagihan);
                					
            if($hutang_14_hari!=''){
                echo number_format($hutang_14_hari,'0',',','.');
            } else {
                echo "";
            }
        ?>
    </td>
    <td align="right">
        <?php
            $hutang_less_25 = $this->modelLaporan->hutang_less_25($no_tagihan);
                					
            if($hutang_less_25!=''){
                echo number_format($hutang_less_25,'0',',','.');
            } else {
                echo "";
            }
        ?>
    </td>
    <td align="right">
        <?php
            $hutang_25 = $this->modelLaporan->hutang_25($no_tagihan);
                					
            if($hutang_25!=''){
                echo number_format($hutang_25,'0',',','.');
            } else {
                echo "";
            }
        ?>
    </td>
    <td align="right">
        <?php
            $hutang_lebih_tempo = $this->modelLaporan->hutang_lebih_tempo($no_tagihan);
                					
            if($hutang_lebih_tempo!=''){
                echo number_format($hutang_lebih_tempo,'0',',','.');
            } else {
                echo "";
            }
        ?>
    </td>
</tr>
<?php } ?>