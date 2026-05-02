<div class="wraper container-fluid">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12" style="text-align: right;">
            <a href="<?php echo base_url('Voucergenerate/show_voucer/'.$id); ?>" class="btn btn-success btn-rounded m-b-5"> &laquo; Kembali</a>
        </div>
    </div>

    <div class="panel panel-default" >
        <div class="panel-body" id="print-area">
<style type="text/css" media="all">
    h5,table,td,th,.voucher{font-family:'Courier New'}
    .voucher{
        width: 500px;
        height: 300px;
        float: left;
        margin: 15px;
        border: 2px solid #000;
        padding: 15px;
    }
    .v-head{
          float: left;
        width: 100%;
    }
    .v-foot{
          float: left;
          width: 100%;
    }
    .v-isi{
        float: left;
        height: 145px;
    }
    .v-logo{
        float: left;
        width: 50%;
    }
    .v-kode{
        width: 50%;
        float: right;
        text-align: right;
        font-size: 28px;
    }
    .v-harga{
        float: left;
        width: 50%;
        font-weight: bold;
    }
    .v-harga-span{font-size: 42px}
    .v-berlaku{font-size: 15px}
    .v-bar{
        width: 50%;
        float: right;
    }
    .v-name{
        font-size: 26px;
    }

 @media print {
    .pageBreak {
        page-break-after: always;
    }
}

    </style>
            <div class="hidden-print">
                <div class="pull-right">
                    <a href="#" onclick="printContent('print-area')" class="btn btn-inverse"><i class="fa fa-print"></i></a>
                </div>
            </div>

            <div class="row">
                <div class= "col-md-12" id="dataContent">
                    
                    <?php
                    $lineCounter = 0;
                        foreach($voucher->result() as $v){
                            $lineCounter++;
                    ?>
                    <div class="voucher">
                        <div class="v-head">
                            <div class="v-logo">
                                <img src="<?php echo base_url('logo.png'); ?>" style="width: 200px;"/>
                            </div>
                            <div class="v-kode">
                                <?php echo $v->id_voucher ?>
                            </div>
                        </div>
                        <div class="v-isi">
                            <h5 class="v-name"><?php echo $v->nm_voucher; ?></h5>
                        </div>
                        <div class="v-foot">
                            <div class="v-harga">
                                <span class="v-harga-span">Rp <?php echo number_format($v->nilai,0,',','.'); ?></span>
                                <span class="v-berlaku">Berlaku sampai <?php echo date('d-m-Y',strtotime($v->berlaku_selesai))?></span>
                            </div>
                            <div class="v-bar">
                                <img src="<?php echo base_url('barcode.php?h=60&f=png&s=code-128&d='.$v->id_voucher); ?>" style="width: 180px;position: relative;right: -65px;bottom: 10px;">
                            </div>
                        </div>  
                    </div>
                   <?php 
                    if($lineCounter % 3 == 0) {
                            echo '<div class="pageBreak"></div>' . PHP_EOL;
                        }

               } ?>
                </div>
            </div>
        </div>
    </div>

</div>
