<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Rekap Stock Opname per Brand</h3> 
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12" style="padding: 20px;">
            <form method="get" action="<?php echo base_url('so_peritem/rekapBrand');?>">
                <div class="col-lg-4">
                    <label>Pilih Tahun</label>
                    <select class="select2" id="tahun" name="tahun">
                        <?php 
                        $ckY = $tahun==date('Y')?'selected':'';
                        $ckY1 = $tahun==date('Y')-1?'selected':'';
                        ?>
                        <option value="<?php echo date('Y')?>" <?php echo $ckY?>><?php echo date('Y')?></option>
                        <option value="<?php echo date('Y')-1?>" <?php echo $ckY1?>><?php echo date('Y')-1?></option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label>Pilih Toko</label>
                    <select class="select2" id="toko" name="toko">
                        <?php 
                        foreach($toko->result() as $tk){
                            $sel = $tk->id_store==$_GET['toko']?'selected':'';
                        ?>
                        <option value="<?php echo $tk->id_store; ?>" <?php echo $sel?>><?php echo $tk->store; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label>&nbsp;</label>
                    <input type="submit" class="btn btn-success" value="Filter" style="width:100%;margin-top:3px">
                </div>
                <div class="col-lg-2">
                    <label>&nbsp;</label>
                    <a class="btn btn-default" onclick="printContent('dataReport')" style="width:100%;margin-top:3px"><i class="fa fa-print"></i> Print</a>
                </div>
            </form>
        </div>
    </div>
    <div class="portlet" id="dataReport"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12" style="padding: 20px;">
                        <table class="table table-bordered" style="font-size:12px;" id="datatable">  
                           <thead>
                               <tr style="font-weight: bold;">
                                    <td width="5%" style="text-align: center;vertical-align: middle;">No</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Brand / Kategori</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Januari</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Februari</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Maret</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">April</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Mei</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Juni</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Juli</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Agustus</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">September</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Oktober</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">November</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Desember</td>
                               </tr>

                            </thead>
                        </table>
                   </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
