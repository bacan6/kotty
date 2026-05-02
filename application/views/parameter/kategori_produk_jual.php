<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
                Kategori LEVEL 1
            </h3>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12 table-responsive">
                  <a href="<?php echo base_url('parameter/kategori_level_1'); ?>" class="btn btn-primary">Add Kategori Level 1</a> <a class="btn btn-success" href="<?php echo base_url('parameter/kategori_level_2'); ?>">Add Kategori Level 2</a> <a href="<?php echo base_url('parameter/kategori_level_3'); ?>" class="btn btn-success">Add Kategori Level 3</a>
                  <br>
                  <br>

                  <?php 
                    if($this->session->userdata("message") !=''){
                      echo $this->session->userdata("message");
                    }
                  ?>
            			<table class="table" style="font-size: 12px;">
                    <tr style="font-weight: bold;">
                      <td width="5%">No</td>
                      <td>Kategori</td>
                      <td width="5%"></td>
                    </tr>
 
                    <?php
                      $i = 1;
                      foreach($kategori as $row){
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->id_kategori.' - '.$row->kategori; ?></td>
                      <td align="center">
                        <a target="_blank" href="<?php echo base_url('parameter/edit_kategori_level_1?id='.$row->id_kategori); ?>"><i class="fa fa-pencil"></i></a> 
                        <a onclick="return confirm('Apakah Anda Yakin ?')" target="_blank" href="<?php echo base_url('parameter/kategori_level_1_hapus?id='.$row->id_kategori); ?>"><i class="fa fa-trash"></i></td></a>
                    </tr>

                      <?php
                        $id_kategori = $row->id_kategori;

                        $level_2_kategori = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori))->result();

                        foreach($level_2_kategori as $dt){
                      ?>

                        <tr>
                          <td></td>
                          <td style="padding-left: 30px;"><li><?php echo $dt->id.' - '.$dt->kategori_level_1; ?></li></td>
                          <td align="center"><a target="_blank" href="<?php echo base_url('parameter/edit_kategori_level2?id='.$dt->id); ?>"><i class="fa fa-pencil"></i></a> <a target="_blank" href="<?php echo base_url('parameter/hapusKategoriLevel2SQL?id='.$dt->id); ?>" onclick="return confirm('Are You Sure ?');"><i class="fa fa-trash"></i></a></td>
                        </tr>

                        <?php
                          $id_kategori_2 = $dt->id;

                          $level_3_kategori = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));

                          foreach($level_3_kategori->result() as $tg){

                        ?>
                        <tr>
                          <td></td>
                          <td style="padding-left: 50px;"><li><?php echo $tg->id.' - '.$tg->kategori_3; ?></li></td>
                          <td align="center"><a target="_blank" href="<?php echo base_url('parameter/editKategoriLevel3?id='.$tg->id); ?>"><i class="fa fa-pencil"></i></a> <a href="<?php echo base_url('parameter/hapusKategori3?id='.$tg->id); ?>"><i class="fa fa-trash"></i></a></td>
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

