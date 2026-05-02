<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="SOLUSI POS | solusipos.my.id">
        <meta name="author" content="Arisal Yanuarafi">

        <link rel="shortcut icon" href="<?php echo base_url('assets/favicon.png?3'); ?>">

        <title><?php echo $pageTitle; ?></title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url('assets'); ?>/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url('assets'); ?>/css/bootstrap-reset.css" rel="stylesheet">

        <!--Animation css-->
        <link href="<?php echo base_url('assets'); ?>/css/animate.css" rel="stylesheet">
        <link href="<?php echo base_url('assets'); ?>/css/loader.css" rel="stylesheet">

        <!--Icon-fonts css-->
        <link href="<?php echo base_url('assets'); ?>/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

        <!--notification-->
        <link href="<?php echo base_url('assets'); ?>/assets/notifications/notification.css" rel="stylesheet" />
        <!-- sweet alerts -->
        <link href="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.min.css" rel="stylesheet">
        
        <!-- DataTables -->
        <link href="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url('assets'); ?>/css/style.css?453" rel="stylesheet">
        <link href="<?php echo base_url('assets'); ?>/css/helper.css" rel="stylesheet">
        <link href="<?php echo base_url('assets'); ?>/assets/dropzone/dropzone.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets'); ?>/assets/select2/select2.css" />
        <link href="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.min.css" rel="stylesheet" />
        <style type="text/css"> 
             /* The switch - the box around the slider */
            .switch {
              position: relative;
              display: inline-block;
              width: 60px;
              height: 34px;
            }

            /* Hide default HTML checkbox */
            .switch input {display:none;}

            /* The slider */
            .slider {
              position: absolute;
              cursor: pointer;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
              background-color: #ccc;
              -webkit-transition: .4s;
              transition: .4s;
            }

            .slider:before {
              position: absolute;
              content: "";
              height: 26px;
              width: 26px;
              left: 4px;
              bottom: 4px;
              background-color: white;
              -webkit-transition: .4s;
              transition: .4s;
            }

            input:checked + .slider {
              background-color: #2196F3;
            }

            input:focus + .slider {
              box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
              -webkit-transform: translateX(26px);
              -ms-transform: translateX(26px);
              transform: translateX(26px);
            }

            /* Rounded sliders */
            .slider.round {
              border-radius: 34px;
            }

            .slider.round:before {
              border-radius: 50%;
            } 
        </style>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            function printContent(el){
                    var restorepage = document.body.innerHTML;
                    var printcontent = document.getElementById(el).innerHTML;
                    document.body.innerHTML = printcontent;
                    window.print();
                    document.body.innerHTML = restorepage;
                }
        </script>
    </head>


    <body>
        
        <!-- Aside Start-->
        <aside class="left-panel<?php 
        if(substr($_SERVER['REQUEST_URI'],0,10)=='/cek_harga') echo " collapsed" ?>"
        <?php 
        if(substr($_SERVER['REQUEST_URI'],0,10)=='/penjualan') echo " style='display:none'" ?>>

            <!-- brand -->
            <div class="logo">
                <a href="<?php echo base_url(); ?>" class="logo-expanded">
                   <img src="<?php echo base_url('assets/Logo_square-05.png'); ?>" width="80%"/>
                </a>
            </div>
            <!-- / brand -->
        
            <!-- Navbar Start -->
            <nav class="navigation" style="margin-top: 0px;">
                <ul class="list-unstyled">

                    <?php
                        $permitAccess = json_decode($permitAccess);
                        $permitAccessSub = json_decode($permitAccessSub);


                        foreach($navigation as $row){

                          $accessMenu = in_array($row->id,$permitAccess);

                          if($accessMenu > 0){

                          $slug = $row->slug;

                          if($row->slug!=''){
                    ?>
                          <li><a href="<?php echo base_url($row->slug); ?>"><i class="<?php echo $row->icon; ?>"></i> <span class="nav-label"><?php echo $row->menu; ?></span></a></li>

                    <?php } else { ?>
                        <li class="has-submenu"><a href=""><i class="<?php echo $row->icon; ?>"></i><span class="nav-label"><?php echo $row->menu; ?></span></a>
                            <ul class="list-unstyled">
                                  <?php
                                    $submenu = $this->model1->submenu($row->id);

                                    foreach($submenu as $dt){
                                      $accessSubMenu = in_array($dt->idSub,$permitAccessSub);

                                      if($accessSubMenu > 0){
                                  ?>
                                  <li><a href="<?php echo base_url($dt->slug); ?>"><?php echo $dt->menu; ?></a></li> 
                                  <?php } } ?>
                            </ul>
                        </li>
                  <?php
                        }//end if slug 
                      }//end if access menu
                    }//end foreach navigation
                  ?>
                    
                </ul>
            </nav>
                
        </aside>
        <!-- Aside Ends-->


        <!--Main Content Start -->
        <section class="content" <?php 
        if(substr($_SERVER['REQUEST_URI'],0,10)=='/penjualan') echo " style='margin-left:0!important'" ?>>
            
            <!-- Header -->
            <header class="top-head container-fluid">
                <button type="button" class="navbar-toggle pull-left" <?php 
        if(substr($_SERVER['REQUEST_URI'],0,10)=='/penjualan') echo "onclick=\"$('.left-panel').show();;$('.content').css('margin-left','75px')\"" ?>>
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="<?php echo base_url('assets/Logo_white.png'); ?>" class='img-responsive' width='200px' style='position:relative;float:left;margin-top:12px;' />

                
             
                
                <!-- Left navbar -->
                <nav class=" navbar-default" role="navigation">
                

                    <!-- Right navbar -->
                    <ul class="nav navbar-nav navbar-right top-menu top-right-menu">  
                        <!-- user login dropdown start-->
                        <li class="dropdown text-center">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"> 
                                <span class="username"><?php echo $this->ion_auth->user()->row()->first_name ?> </span> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu pro-menu fadeInUp animated" tabindex="5003" style="overflow: hidden; outline: none;">
                                <li><a href="<?php echo base_url('setting/editUser?id_user='.$this->ion_auth->user()->row()->id); ?>"><i class="fa fa-lock"></i> Ganti Password</a></li>
                                <li><a href="<?php echo base_url('logout'); ?>"><i class="fa fa-sign-out"></i> Log Out</a></li>
                            </ul>
                        </li>
                        <!-- user login dropdown end -->       
                    </ul>
                    <!-- End right navbar -->
                </nav>
                
            </header>
            <!-- Header Ends -->


            <!-- Page Content Start -->
            <!-- ================== -->