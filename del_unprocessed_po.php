<?php //die('created by Arisal Y.');
//error_reporting(E_ALL);ini_set('display_errors',1);
    $db_username = "gbuser8B";
    $db_hostname = "localhost";
    $db_password = "dMRGkWZ6qzOv4wAC";
    $db_name = "glamindoF1";
 

  $con = mysqli_connect($db_hostname, $db_username, $db_password) or die('tidak dapat terhubung ke server database');
  $db  = mysqli_select_db($con,$db_name);

  $limit = date('Y-m-d',strtotime("-14 days"));
  $s = "SELECT * from purchase_order where tanggal_po < '$limit' and status not in ('3')";
  $r = mysqli_query($con,$s);$n1=0;
  while ($w = mysqli_fetch_array($r)){
      $n1++;
      if($w['status']=='1'){
        $cek = "SELECT * from receive_order where no_po='$w[no_po]'";
        $r1 = mysqli_query($con,$cek);
        $num = mysqli_num_rows($r1);
        while($w1 = mysqli_fetch_array($r1)){
          $upd = "UPDATE purchase_order set status=3 where no_po='$w[no_po]'";
          mysqli_query($con,$upd);
        }
        if($num==0){
          $ins1 = "INSERT into purchase_order_deleted SELECT * from purchase_order where no_po='$w[no_po]'";
          //mysqli_query($con,$ins1);

          $ins2 = "INSERT into purchase_item_deleted SELECT * from purchase_item where no_po='$w[no_po]'";
          //mysqli_query($con,$ins2);

          $del1 = "UPDATE purchase_order set status='9' where no_po='$w[no_po]'";
          mysqli_query($con,$del1);

          //$del2 = "DELETE from purchase_item where no_po='$w[no_po]'";
          //mysqli_query($con,$del2);
        }
      }else{
          $ins1 = "INSERT into purchase_order_deleted SELECT * from purchase_order where no_po='$w[no_po]'";
          //mysqli_query($con,$ins1);

          $ins2 = "INSERT into purchase_item_deleted SELECT * from purchase_item where no_po='$w[no_po]'";
          //mysqli_query($con,$ins2);

          $del1 = "UPDATE purchase_order set status='9' where no_po='$w[no_po]'";
          mysqli_query($con,$del1);

          //$del2 = "DELETE from purchase_item where no_po='$w[no_po]'";
          //mysqli_query($con,$del2);
      }
    
  }