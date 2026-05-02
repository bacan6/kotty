<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
                Laporan Stock Opname
            </h3>
            
            <div class="portlet-widgets">
                <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                <span class="divider"></span>
                <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">   
                    <div class="col-md-12" align="right">
                        <form action="<?php echo base_url('laporan/stock_opname'); ?>" method="get">
                            <div class="input-group" style="width: 30%;">
                                <input type="text" id="example-input1-group2" name="query" class="form-control" placeholder="Search">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-effect-ripple btn-primary"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row" style="margin-top: 30px;">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped" style="font-size: 12px;">
                            <tr style="font-weight: bold;">
                                <td width="5%">No</td>
                                <td width="15%">No SO</td>
                                <td>Tanggal</td>
                                <td>PIC</td>
                                <td>Keterangan</td>
                            </tr>
                            <?php
                                if(empty($this->uri->segment(3))){
                                    $i=0+1;
                                } else {
                                    $i=$this->uri->segment(3)+1;
                                }

                                foreach($data_so->result() as $row){
                            ?>
                            <tr>
                                <td align="center"><?php echo $i; ?></td>
                                <td><a href="<?php echo base_url('laporan/stock_opname_report?no_so='.$row->no_so); ?>"><?php echo $row->no_so; ?></a></td>
                                <td>
                                    <?php
                                        $tanggal = date_create($row->tanggal);

                                        echo date_format($tanggal,'d M Y');
                                    ?>
                                </td>
                                <td><?php echo $row->first_name; ?></td>
                                <td><?php echo $row->keterangan; ?></td>
                            </tr>
                            <?php $i++; } ?>
                        </table>
                    </div>
                </div>   

                <div class="row" style="text-align: center;">
                    <?php
                        echo $paging;
                    ?>
                </div>  
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
