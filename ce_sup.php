<?php //die('created by Arisal Y.');
error_reporting(E_ALL);ini_set('display_errors',1);
    $db_username = "gbuser8B";
    $db_hostname = "localhost";
    $db_password = "dMRGkWZ6qzOv4wAC";
    $db_name = "glamindoF1";
 

  $con = mysqli_connect($db_hostname, $db_username, $db_password) or die('tidak dapat terhubung ke server database');
  $db  = mysqli_select_db($con,$db_name);

//     $s = "SELECT i.id_produk,max(i.tanggal) as tanggal,n.id_toko from ap_invoice_item i join ap_invoice_number n on n.no_invoice=i.no_invoice 
// group by n.id_toko,i.id_produk order by i.tanggal desc";
//   $r = mysqli_query($con,$s);$n1=0;$n=0;
//   while ($w = mysqli_fetch_array($r)){
//       $n1++;
//     $upd = "UPDATE stok_store set last_sales='$w[tanggal]' where id_produk='$w[id_produk]' and id_store='$w[id_toko]'";
//     if(mysqli_query($con,$upd)) $n++;
//   }
//   echo number_format($n1,0).'<br>';
//   echo number_format($n,0);

//   $s = "SELECT i.sku,max(i.tanggal) as tanggal,n.diterimaDi from receive_item i join receive_order n on n.no_receive=i.no_receive 
// group by n.diterimaDi,i.sku order by i.tanggal desc";
//   $r = mysqli_query($con,$s);$n1=0;$n=0;
//   while ($w = mysqli_fetch_array($r)){
//       $n1++;
//     $upd = "UPDATE stok_store set last_receives='$w[tanggal]' where id_produk='$w[sku]' and id_store='$w[diterimaDi]'";
//     if(mysqli_query($con,$upd)) $n++;
//   }
//   echo number_format($n1,0).'<br>';
//   echo number_format($n,0);