<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->    
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
	                <div class="col-md-12" style="text-align: right;">
	                    <a onclick="printContent('area-print')" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
	                </div>
                </div>

                <div class="row" id="area-print">
                	<div class="col-md-12">
                		<table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <h4><?php echo $hd->nama_perusahaan; ?></h4> 
                                    <h5>Adjusment Work Order</h5>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table width="100%" style="margin-top: 30px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">No Adjusment</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $infoAdj->no_adjusment; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">No Work Order</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $infoAdj->noWO; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">Tanggal</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo date_format(date_create($infoAdj->tanggal),'d M Y'); ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">Keterangan</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $infoAdj->keterangan; ?></td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="vertical-align: top;">

                                </td>
                            </tr>
                        </table>


                        <table style="margin-top: 20px;font-size:12px;width: 100%;" border="1">
                            <tr style="font-weight: bold;">
                                <td width="5%" align="center">No</td>
                                <td width="70%">Nama Bahan</td>
                                <td>Qty</td>
                                <td>Satuan</td>
                            </tr>

                            <?php
                                $i = 1;
                                foreach($itemAdj as $row){
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row->nama_bahan; ?></td>
                                <td><?php echo $row->qty; ?></td>
                                <td><?php echo $row->satuan; ?></td>
                            </tr>
                            <?php $i++; } ?>

                        </table>       

                        <table width="100%" style="margin-top: 20px;">
                            <tr style="height: 40px;">
                                <td width="50%" style="text-align: center;">
                                    <center>
                                    <table width="50%">
                                        <tr>
                                            <td style="height: 120px;border-bottom: solid 1px black;text-align: center;">
                                                Yang Mengajukan
                                            </td>
                                        </tr>
                                    </table>
                                    </center>
                                </td>

                                <td width="50%" style="text-align: center;">
                                    <center>
                                    <table width="50%">
                                        <tr>
                                            <td style="height: 120px;border-bottom: solid 1px black;text-align: center;">
                                                Pemeriksa
                                            </td>
                                        </tr>
                                    </table>
                                    </center>
                                </td>
                            </tr>
                        </table> 
                	</div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
