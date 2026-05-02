
    <?php
	foreach($list_kasir as $row){

        $id_kasir = $row->id_user;

        $cek_modal = $this->model_penjualan->cek_status_kasir($id_kasir,$tanggal);

        //cek status kasir

        if($cek_modal < 1){
    ?>

            <a data-toggle="modal" href="#myModal" id="<?php echo $row->id_user; ?>" class="input-modal" data-tanggal ="<?php echo $tanggal; ?>">
                <div class="col-md-3 col-sm-6">
                    <div class="widget-panel widget-style-1 bg-info">
                        <i class="fa fa-toggle-off"></i> 
                        <h2 class="m-0 counter text-white"><?php echo $row->nama_user;?></h2>
                        <div class="text-white">-</div>
                    </div>
                </div>
            </a>

        <?php } else { ?>
            
            <?php
                //cek close or not
                $cekClose = $this->model_penjualan->cekClose($id_kasir,$tanggal);

                if($cekClose < 1){
            ?>

                    <a data-toggle="modal" href="#closing-kasir" id="<?php echo $row->id_user; ?>" data-tanggal ="<?php echo $tanggal; ?>" class="closing-kasir">
                        <div class="col-md-3 col-sm-6">
                            <div class="widget-panel widget-style-1 bg-success">
                                <i class="fa fa-money"></i> 
                                <h2 class="m-0 counter text-white"><?php echo $row->nama_user;?></h2>
                                <!-- view for kasir modal-->
                                <?php
                                    //tampilkan modal kasir yang telah terinput
                                    $modal_kasir = $this->model_penjualan->modal_kasir($id_kasir,$tanggal);

                                    foreach($modal_kasir as $md){
                                ?>
                                
                                <div class="text-white">
                                    Modal : <?php echo number_format($md->modal,'0',',','.'); ?> <br>
                                    Tanggal : <?php echo date_format(date_create($md->tanggal),'d M Y H:i'); ?>
                                </div>

                                <?php } ?>
                                <!-- end modal kasir-->
                            </div>
                        </div>
                    </a>
                <?php } else { ?>
                    <a data-toggle="modal" href="#closing-kasir-close" id="<?php echo $row->id_user; ?>" data-tanggal ="<?php echo $tanggal; ?>" class="closing-kasir-close">
                        <div class="col-md-3 col-sm-6">
                            <div class="widget-panel widget-style-1 bg-danger">
                                <i class="fa fa-money"></i> 
                                <h2 class="m-0 counter text-white"><?php echo $row->nama_user;?></h2>
                                <!-- view for kasir modal-->
                                <?php
                                    //tampilkan modal kasir yang telah terinput
                                    $modal_kasir = $this->model_penjualan->modal_kasir($id_kasir,$tanggal);

                                    foreach($modal_kasir as $md){
                                ?>
                                
                                <div class="text-white">
                                    Modal : <?php echo number_format($md->modal,'0',',','.'); ?> <br>
                                    Tanggal : <?php echo date_format(date_create($md->tanggal),'d M Y H:i'); ?>
                                </div>

                                <?php } ?>
                                <!-- end modal kasir-->
                            </div>
                        </div>
                    </a> 
                <?php } ?>
        <?php } ?>  


<?php } ?>