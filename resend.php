<?php 
die('created by Arisal Y.');
// error_reporting(E_ALL);
// ini_set('display_errors',1);
// $id = $_GET['store']+0;
// if (empty($id)) die('store?');
session_start();
ini_set('memory_limit', '-1');
ini_set('max_execution_time',  8000);
ini_set('max_input_time', '-1');
ini_set('session.gc_maxlifetime', 10800);
ini_set('default_socket_timeout', 6000);
$db_username = "bmkottbg";
$db_hostname = "localhost";
$db_password = "Ud0VfE.BYogWSnj!";
$db_name = "kottydb";
 

  $con = mysqli_connect($db_hostname, $db_username, $db_password,$db_name) or die('tidak dapat terhubung ke server database');
$datex = $_GET['period'];
  if($_GET['what']=='sellout_67'){
    // if(empty($_SESSION['today'])){
    //     $datex = '2024-01-16';
    // }else{
    //     $datex = date('Y-m-d',strtotime($_SESSION['today']. " +1 day"));
    // }
    // echo $datex;
    
    echo $datex;
    
    $s = "SELECT i.id_produk,i.qty,a.nama_produk,b.brand,i.tanggal 
            FROM `ap_invoice_item` i 
                left outer join ap_produk a on a.id_produk=i.id_produk 
                left outer join brand b on b.id_brand=a.id_brand 
                left join ap_invoice_number n on n.no_invoice=i.no_invoice
            where 
                b.brand in ('Biodef',
                        'Crystallure',
                        'Earth Love Life',
                        'Emina',
                        'Instaperfect',
                        'Kahf',
                        'Labore',
                        'Make Over',
                        'OMG',
                        'Putri',
                        'Tavi',
                        'Wardah',
                        'Wonderly') 
                and i.tanggal like '".$datex."'
                and n.id_toko in (6,7)";
    $r = mysqli_query($con,$s);
    while ($w = mysqli_fetch_array($r, MYSQLI_ASSOC)){
        $dataArray[] = [
            'barcode' => trim($w['id_produk']),
            'code' => '',
            'qty' => $w['qty'],
            'product_description' => htmlspecialchars($w['nama_produk'], ENT_QUOTES, 'UTF-8'),
            'brand' => $w['brand'],
            'date'  => date('d-m-Y',strtotime($w['tanggal']))
        ];
        
        }

        // Data yang akan dikirim
        $data = [
            'data'          => $dataArray,
            'access_key'    => '77033b8f04-bd6a9d542892-3116a3d112-288a9bb7-717783-1d606a464976c8'
        ];
    
        //$json_string = json_encode($data, JSON_PRETTY_PRINT);
        //die(var_dump($dataArray));

    kirim_data_sellout($data);
    
    // $_SESSION['today'] = $datex;

    mysqli_close($con);
    // if($datex=='2024-12-03') die('selesai');
    // else{
    //     echo "<html><head><title>tunggu</title></head><body><script>window.location.reload(true);</script></body></html>";
    // }

  }else if($_GET['what']=='sellout_8'){
    //$datex = date('Y-m-d');
    $s = "SELECT i.id_produk,i.qty,a.nama_produk,b.brand ,i.tanggal
            FROM `ap_invoice_item` i 
                left outer join ap_produk a on a.id_produk=i.id_produk 
                left outer join brand b on b.id_brand=a.id_brand 
                left join ap_invoice_number n on n.no_invoice=i.no_invoice
            where 
                b.brand in ('Biodef',
                        'Crystallure',
                        'Earth Love Life',
                        'Emina',
                        'Instaperfect',
                        'Kahf',
                        'Labore',
                        'Make Over',
                        'OMG',
                        'Putri',
                        'Tavi',
                        'Wardah',
                        'Wonderly') 
                and i.tanggal like '".$datex."'
                and n.id_toko=8";
    $r = mysqli_query($con,$s);
    while ($w = mysqli_fetch_array($r, MYSQLI_ASSOC)){
        $dataArray[] = [
            'barcode' => trim($w['id_produk']),
            'code' => '',
            'qty' => $w['qty'],
            'product_description' => htmlspecialchars($w['nama_produk'], ENT_QUOTES, 'UTF-8'),
            'brand' => htmlspecialchars($w['brand'], ENT_QUOTES, 'UTF-8'),
            'date'  => date('d-m-Y',strtotime($w['tanggal']))
        ];
        
        }

        // Data yang akan dikirim
        $data = [
            'data'          => $dataArray,
            'access_key'    => '5c68207ff6-25a0440b5968-46e885720a-5e6fc90d-02ba73-1f19577a77acd6'
        ];
    


    kirim_data_sellout($data);
    

    mysqli_close($con);
    echo "SELESAI";

  }else if($_GET['what']=='stok'){
    $s = "SELECT i.id_produk,i.stok as qty,a.nama_produk,b.brand 
            FROM `stok_store` i 
                left outer join ap_produk a on a.id_produk=i.id_produk 
                left outer join brand b on b.id_brand=a.id_brand 
            where 
                b.brand='Wardah' 
                and i.id_store='7' limit 20";
    $r = mysqli_query($con,$s);
    while ($w = mysqli_fetch_array($r, MYSQLI_ASSOC)){
        $dataArray[] = [
            'barcode' => $w['id_produk'],
            'code' => '',
            'qty' => $w['qty'],
            'product_description' => htmlspecialchars($w['nama_produk'], ENT_QUOTES, 'UTF-8'),
            'brand' => htmlspecialchars($w['brand'], ENT_QUOTES, 'UTF-8'),
            'date'  => date('d-m-Y')
        ];
        
        }

        // Data yang akan dikirim
        $data = [
            'data'          => $dataArray,
            'access_key'    => 'b71e70e496-c423f87ad32e-a4e7f155e5-c534df4c-aa3aca-92773f243a7ed5',
            'customer_id'   => '125774-000'
        ];
    


    kirim_data_stok($data);
    

    mysqli_close($con);
    echo "SELESAI";

  }else if ($_GET['what']=='list'){
    listProduk();
  }
  
  

  function kirim_data_sellout($data){
    // echo "<pre>";
    // var_dump(json_encode($data,JSON_PRETTY_PRINT));
    // echo "</pre>";
    // Inisialisasi cURL
    $ch = curl_init('https://api-customer-platform.pti-cosmetics.com/api/v1/automated/json-upload-sellout'); // Ganti dengan URL server Anda

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);

    // Eksekusi request dan dapatkan respons
    $response = curl_exec($ch);

    // Cek jika ada kesalahan
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        // Tampilkan respons
        var_dump('Response: ' . $response);
    }

    // Tutup cURL
    curl_close($ch);
  }

  function kirim_data_stok($data){
    echo "<pre>";
    var_dump(json_encode($data,JSON_PRETTY_PRINT));
    echo "</pre>";
    // Inisialisasi cURL
    $ch = curl_init('https://x-beta-sellout.paradev.io/api/v1/automated/json-upload-stock'); // Ganti dengan URL server Anda

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);

    // Eksekusi request dan dapatkan respons
    $response = curl_exec($ch);

    // Cek jika ada kesalahan
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        // Tampilkan respons
        echo 'Response: ' . $response;
    }

    // Tutup cURL
    curl_close($ch);
  }

  function listProduk(){
    // Inisialisasi cURL
    $data = array( 'access_key'    => '77033b8f04-bd6a9d542892-3116a3d112-288a9bb7-717783-1d606a464976c8');
    var_dump(json_encode($data));
    $ch = curl_init('https://api-customer-platform.pti-cosmetics.com/api/v1/products/list'); // Ganti dengan URL server Anda

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);

    // Eksekusi request dan dapatkan respons
    $response = curl_exec($ch);

    // Cek jika ada kesalahan
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        // Tampilkan respons
        echo 'Response: ' . $response;
    }

    // Tutup cURL
    curl_close($ch);
  }