<div class="wraper container-fluid">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12" style="text-align: right;">
            <a href="<?php echo base_url('kupon_member_ambil'); ?>" class="btn btn-success btn-rounded m-b-5"> &laquo; Kembali</a> 
        </div>
    </div>
<style>
h5,table,td,th{font-family:'Courier New'}
</style>
    <div class="panel panel-default" >
        <div class="panel-body" id="print-area">
            <div class="hidden-print">
                <div class="pull-right">
                    <a href="#" onclick="printContent('print-area')" class="btn btn-inverse"><i class="fa fa-print"></i></a>
                </div>
            </div>

            <div class="row">
                <div class= "col-md-12" id="dataContent">
                    <img src="<?php echo base_url('logo.png'); ?>" style="margin-left:auto;margin-right:auto;display:block;height:40px;"/>
                    <h4 align="center"><?php echo str_replace('Miss Glam','',$store->store);?></h4>
                    <h5 align="center">Pengambilan Produk Redeem</h5>
                     
                    <center>
                    <table>
                        <tr style="border-top:dashed 1px #000;">
                            <td width="160px"></td>
                            <td width="20px"></td>
                            <td width="80px" align="right"></td>
                            <td align="right" width="80px"></td>
                        </tr>

                        
                        <tr>
                            <td colspan="4"><?php echo $kupon->nm_kupon?></td> 
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">&nbsp;</td>
                            <td style="vertical-align:top;" align="right">x 1</td>
                            <td align="right" style="vertical-align:top;"><?php echo $kupon->jml_point?> poin</td>    
                        </tr>

                        <?php
                        
                     ?> 

  

                        <tr style="border-top: dashed 1px #000;">
                            <td colspan="4" align="center">
                                <?php echo $_GET['no_kupon']." | ".date_format(date_create($tanggal),'d-m-y')." | ".date_format(date_create($tanggal),'H:i:s'); ?>
                                <br>
                                Nama Kasir: <?php echo $kasir->first_name; ?>
                                <br>
                                Nama Customer: <?php echo $member->nama; ?>
                                <br>
                                ID Customer: <?php echo $member->id_customer; ?>
                                <br>
                                Sisa Poin: <?php echo $member->point; ?>
                            </td>
                        </tr>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
