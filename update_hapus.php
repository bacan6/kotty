<?php //die('created by Arisal Y.');
//die();
session_start();
error_reporting(0);
ini_set('display_errors',0);
$db_username = "bmkottbg";
$db_hostname = "localhost";
$db_password = "Ud0VfE.BYogWSnj!";
$db_name = "kottydb";
$con = mysqli_connect($db_hostname, $db_username, $db_password,$db_name) or die('tidak dapat terhubung ke server database');

$upd = 'update ap_produk set Uploaded="N"';
mysqli_query($con,$upd);
//mysqli_close($con);

// while(0!=1){
//$con = mysqli_connect($db_hostname, $db_username, $db_password,$db_name) or die('tidak dapat terhubung ke server database');

 

  
  //$db  = mysqli_select_db($con,$db_name);

  $s = "SELECT * from ap_produk where Uploaded='N'";
  $r = mysqli_query($con,$s);$n1=0;$dihapus=0;
  while ($w = mysqli_fetch_array($r, MYSQLI_ASSOC)){
    $n1++;
      $s1 = "SELECT * from ap_produk_price where id_produk='$w[id_produk]' order by id_toko,id_produk";
      $r1 = mysqli_query($con,$s1);
      $id_toko = '';$id_produk='';
      while ($w1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)){
            $n1++;
          if($id_toko!=$w1['id_toko']){
            $id_toko=$w1['id_toko'];
          }else{
            if($id_produk==$w1['id_produk']){
              $del = "delete from ap_produk_price where ID='$w1[ID]'";
              mysqli_query($con,$del);
              $dihapus++;
            }
          }
          $id_produk = $w1['id_produk'];
      }
      $s1 = 'SELECT * from stok_store where id_produk="'.$w['id_produk'].'" order by id_store,id_produk';
      $r1 = mysqli_query($con,$s1);
      $id_toko = '';$id_produk='';
      while ($w1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)){
        $n1++;
          if($id_toko!=$w1['id_store']){
            $id_toko=$w1['id_store'];
          }else{
            if($id_produk==$w1['id_produk']){
              $del = "delete from stok_store where ID='$w1[ID]'";
              echo $del.'<br>';
              mysqli_query($con,$del);
              $dihapus++;
            }
          }
          $id_produk = $w1['id_produk'];
      }
      $upd = 'update ap_produk set Uploaded="Y" where id_produk="'.$w['id_produk'].'"';
      mysqli_query($con,$upd);
      
  }
  //echo '<br>'.$n1.'<br>';
  mysqli_close($con);
  die('selesai '.$n1.', Dihapus:'.$dihapus);
//}