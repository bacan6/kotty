<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="shortcut icon" href="<?php echo base_url('assets/favicon.png'); ?>">

        <title>Solusi POS - Login</title>

        


        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url('assets/'); ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url('assets/'); ?>css/bootstrap-reset.css" rel="stylesheet">

        <!--Animation css-->
        <link href="<?php echo base_url('assets/'); ?>css/animate.css" rel="stylesheet">

        <!--Icon-fonts css-->
        <link href="<?php echo base_url('assets/'); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href="<?php echo base_url('assets/'); ?>assets/ionicon/css/ionicons.min.css" rel="stylesheet" />

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>assets/morris/morris.css">


        <!-- Custom styles for this template -->
        <link href="<?php echo base_url('assets/'); ?>css/style.css" rel="stylesheet">
        <link href="<?php echo base_url('assets/'); ?>css/helper.css" rel="stylesheet">
        

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->

    </head>


    <body style="background: rgb(68,68,68);
background: radial-gradient(circle, rgba(68,68,68,1) 30%, rgba(34,34,34,1) 86%);">

        <div class="wrapper-page animated fadeInDown">
            <div class="panel panel-color panel-default" style="background:#e91a62;box-shadow: 0 0 3px #333;border-radius: 6px;">
                <div class="panel-heading" style="text-align: center;background:#e91a62"> 
                  <img src="<?php echo base_url('assets/kotty_front.png'); ?>" width="300px"/>
                </div> 

                <form class="form-horizontal m-t-40" method="post" action="<?php echo base_url('login/auth'); ?>">
                    <?php
                      echo $this->session->userdata("message");
                    ?>

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" id="username" placeholder="Username" name="username">
                        </div>
                    </div>
                    <div class="form-group ">
                        
                        <div class="col-xs-12">
                            <input class="form-control" type="password" placeholder="Password" name="password">
                        </div>
                    </div>
                    
                    <div class="form-group text-right">
                        <div class="col-xs-12">
                            <span id='path'></span>
                            <button class="btn btn-warning w-md" type="submit">Login</button>
                        </div>
                    </div>
                    <div class="form-group m-t-30">
                        <!--<div class="col-sm-7">
                            <a href="recoverpw.html"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                        </div>
                        <div class="col-sm-5 text-right">
                            <a href="register.html">Create an account</a>
                        </div>-->
                        
                    </div>
                    
                </form>

            </div>
            <!--<center style="color:#fff">Powered by: <b>SOLUSI POS</b></center>-->
        </div>

    


        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url('assets/'); ?>js/jquery.js"></script>
        <script src="<?php echo base_url('assets/'); ?>js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('assets/'); ?>js/pace.min.js"></script>
        <script src="<?php echo base_url('assets/'); ?>js/wow.min.js"></script>
        <script src="<?php echo base_url('assets/'); ?>js/jquery.nicescroll.js" type="text/javascript"></script>
            

        <!--common script for all pages-->
        <script src="<?php echo base_url('assets/'); ?>js/jquery.app.js"></script>
        <script>$("#username").focus();
        var get_uid = "<?php echo base_url('login/generate_uid'); ?>";
        var get_uid_new = "<?php echo base_url('login/generate_uid_new'); ?>";
        $('#username').on("change",function(){
            var user_id = $('#username').val();
            $.ajax({
                method : "POST",
                url : get_uid,
                dataType : 'json',
                data : {user_id : user_id},
                success : function(resp){	
                    $('#path').html("<a href='finspot:FingerspotVer;"+resp.path+"' style='border:1px solid darkyellow;background:darkgreen;color:white;font-weight:bold;margin-right:5px;padding:5px;border-radius:4px'>Fingerprint</a>");
                }
            }); 
            
            $.ajax({
                method : "POST",
                url : get_uid_new,
                dataType : 'json',
                data : {user_id : user_id},
                success : function(resp){	
                    var webhook = encodeURIComponent(resp.webhook);
                    var user = encodeURIComponent(resp.username);
                    var fingerprint = encodeURIComponent(resp.fingerprint);
                    $('#path').append(`<a href="fpsolusi://identify?webhook=${webhook}&username=${user}&fingerprint=${fingerprint}" style="border:1px solid darkyellow;background:darkyellow;color:white;font-weight:bold;margin-right:5px;padding:5px;border-radius:4px">Fingerprint New</a>`);
                    setTimeout(function(){
                        window.location=`<?php echo base_url('login/messages?msg=done&m=')?>${resp.base}`;
                    },7000)
                }
            });
        });
        
        </script>

    
    </body>
</html>
