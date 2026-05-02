<?php die('created by Arisal Y.');
error_reporting(E_ALL);ini_set('display_errors',1);
    $db_username = "";
    $db_hostname = "";
    $db_password = "";
    $db_name = "";
 

  $con = mysqli_connect($db_hostname, $db_username, $db_password) or die('tidak dapat terhubung ke server database');
  $db  = mysqli_select_db($con,$db_name);

  $s = "SELECT * from ap_kategori";
  $r = mysqli_query($con,$s);$n1=0;
  while ($w = mysqli_fetch_array($r)){
      $n1++;
    echo $w['kategori'].'<br>';  
    $s1 = "SELECT * from ap_kategori_1 where id_kategori='$w[id_kategori]' order by id_kategori,id";
    $r1 = mysqli_query($con,$s1);$n2=0;
    while($w1 = mysqli_fetch_array($r1)){
        $n2++;
            $urut = $n1.str_pad($n2, 2, "0", STR_PAD_LEFT);
            echo $urut.$w2['kategori_3'].'<br>';
            $update = "UPDATE ap_kategori_1 set urutan='$urut' where id='$w1[id]'";
            mysqli_query($con,$update);
        $s2 = "SELECT * from ap_kategori_2 where id_kategori_1='$w1[id]' order by id,id_kategori_1";
        $r2 = mysqli_query($con,$s2);$n3=0;
        while ($w2 = mysqli_fetch_array($r2)){
            $n3++;
            $urut = $n1.str_pad($n2, 2, "0", STR_PAD_LEFT).str_pad($n3, 3, "0", STR_PAD_LEFT);
            echo $urut.$w2['kategori_3'].'<br>';
            $update = "UPDATE ap_kategori_2 set urutan='$urut' where id='$w2[id]'";
            mysqli_query($con,$update);
        }
    }
  }