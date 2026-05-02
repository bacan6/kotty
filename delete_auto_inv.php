<?php //die('created by Arisal Y.');
error_reporting(0);
ini_set('display_errors',0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time',  8000);
ini_set('max_input_time', '-1');
ini_set('session.gc_maxlifetime', 10800);
ini_set('default_socket_timeout', 6000);
$db_username = "bmkottbg";
$db_hostname = "localhost";
$db_password = "Ud0VfE.BYogWSnj!";
$db_name = "kottydb";
 

  $con = mysqli_connect($db_hostname, $db_username, $db_password) or die('tidak dapat terhubung ke server database');
  $db  = mysqli_select_db($con,$db_name);

  $tanggal = date('Y-m-d H:i:s',strtotime("-8 days"));
  $s = "SELECT n.no_invoice,n.total,n.tanggal,sum(i.qty),n.id_toko,n.poin from ap_invoice_number n left outer join ap_invoice_item i on i.no_invoice=n.no_invoice where i.qty is null and n.tanggal >= '$tanggal' group by n.no_invoice;";
  $r = mysqli_query($con,$s);$n1=0;
  while ($w = mysqli_fetch_array($r)){
    $n++;
      $q = "DELETE from ap_invoice_number where no_invoice='$w[no_invoice]'";
      mysqli_query($con,$q);
      echo $q.'<hr>';

      $q = "DELETE from stok_store_kartu where no_transaksi='$w[no_invoice]'";
      mysqli_query($con,$q);
      echo $q.'<hr>';

      $q = "UPDATE ap_customer set point=(point-$w[poin]) where id_customer='$w[id_customer]'";
      mysqli_query($con,$q);

      echo $q.'<hr>';
  }
  echo "<b>Total: $n1</b>";