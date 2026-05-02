<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12 table-responsive">
            			<table class="table table-bordered" style="font-size: 12px;">
                    <tr style="font-weight: bold;">
                      <td width="5%">No</td>
                      <td>Kategori</td>
                    </tr>
 
                    <?php
                      $i = 1;
                      foreach($kategori as $row){
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->kategori; ?></td>
                      
                    </tr>

                      <?php
                        $id_kategori = $row->id_kategori;

                        $level_2_kategori = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori))->result();

                        foreach($level_2_kategori as $dt){
                      ?>

                        <tr>
                          <td></td>
                          <td style="padding-left: 30px;"><li><?php echo $dt->kategori_level_1; ?></li></td>
                        </tr>

                        <?php
                          $id_kategori_2 = $dt->id;

                          $level_3_kategori = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));

                          foreach($level_3_kategori->result() as $tg){

                        ?>
                        <tr>
                          <td></td>
                          <td style="padding-left: 50px;"><li><?php echo $tg->kategori_3; ?></li></td>
                        </tr>
                        <?php } ?>
                      <?php } ?>

                    <?php $i++; } ?>
                  </table>
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

$pdf->SetFont('Arial','',10);