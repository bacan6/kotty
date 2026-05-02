<?php //die('created by Arisal Y.');
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

  if($_GET['what']=='sellout_67'){
    if(isset($_GET['date'])){
        $datex = $_GET['date'];
    }else $datex = date('Y-m-d');
    // Kotty ID (126694-004)
    
    
    $s = "SELECT i.id_produk,i.qty,a.nama_produk,b.brand,i.tanggal 
            FROM `ap_invoice_item` i 
                left outer join ap_produk a on a.id_produk=i.id_produk 
                left outer join brand b on b.id_brand=a.id_brand 
                left join ap_invoice_number n on n.no_invoice=i.no_invoice
            where 
                b.brand in ('Beyondly',
                            'Biodef',
                            'Earth Love Life',
                            'Emina',
                            'Instaperfect Crystallure',
                            'Kahf',
                            'Labore',
                            'Make Over',
                            'New Modern',
                            'Nutrikind',
                            'Oh My Glam',
                            'Putri',
                            'Tavi',
                            'Wardah',
                            'WATSONS',
                            'Wonderly') 
                and i.tanggal like '".$datex."'
                and a.nama_produk not like '%Tebus Murah%'
                and i.id_produk not in ('99998717012025','998903012025')
                and n.id_toko in (6,7)";
    $r = mysqli_query($con,$s);
    while ($w = mysqli_fetch_array($r, MYSQLI_ASSOC)){
        $dataArray[] = [
            'barcode' => $w['id_produk'],
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

        //var_dump($data);
    
        $json_string = json_encode($data, JSON_PRETTY_PRINT);
        // die(print_r('<pre>'.$json_string.'</pre>'));

    kirim_data_sellout($data);
    
    // $_SESSION['today'] = $datex;

    mysqli_close($con);
    // if($datex=='2024-12-03') die('selesai');
    // else{
    //     echo "<html><head><title>tunggu</title></head><body><script>window.location.reload(true);</script></body></html>";
    // }

  }else if($_GET['what']=='sellout_8'){
    if(isset($_GET['date'])){
        $datex = $_GET['date'];
    }else $datex = date('Y-m-d');

    // Kotty PNY (126694-005)

    $s = "SELECT i.id_produk,i.qty,a.nama_produk,b.brand ,i.tanggal
            FROM `ap_invoice_item` i 
                left outer join ap_produk a on a.id_produk=i.id_produk 
                left outer join brand b on b.id_brand=a.id_brand 
                left join ap_invoice_number n on n.no_invoice=i.no_invoice
            where 
                b.brand in ('Beyondly',
                            'Biodef',
                            'Earth Love Life',
                            'Emina',
                            'Instaperfect Crystallure',
                            'Kahf',
                            'Labore',
                            'Make Over',
                            'New Modern',
                            'Nutrikind',
                            'Oh My Glam',
                            'Putri',
                            'Tavi',
                            'Wardah',
                            'WATSONS',
                            'Wonderly') 
                and i.tanggal like '".$datex."'
                and i.id_produk not in ('99998717012025','998903012025')
                and a.nama_produk not like '%Tebus Murah%'
                and n.id_toko=8";
    $r = mysqli_query($con,$s);
    $no = 0;
    while ($w = mysqli_fetch_array($r, MYSQLI_ASSOC)){
        $dataArray[] = [
            'barcode' => $w['id_produk'],
            'code' => '',
            'qty' => $w['qty'],
            'product_description' => htmlspecialchars($w['nama_produk'], ENT_QUOTES, 'UTF-8'),
            'brand' => $w['brand'],
            'date'  => date('d-m-Y',strtotime($w['tanggal']))
        ];
        $no++;
        
        }

        // Data yang akan dikirim
        $data = [
            'data'          => $dataArray,
            'access_key'    => '5c68207ff6-25a0440b5968-46e885720a-5e6fc90d-02ba73-1f19577a77acd6'
        ];
    


    kirim_data_sellout($data);
    

    mysqli_close($con);
    echo "SELESAI ".$no;

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
            'brand' => $w['brand'],
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
    
    $hasil = listProduk();
    // hapus data sebelumnya
    $query = "DELETE FROM ap_produk_paragon";
    $r = mysqli_query($con,$query);

    //die(var_dump($hasil['data']));

    // Iterasi melalui data produk
    $s = '';
    foreach ($hasil['data'] as $product) {
            $s .= "('".$product['barcode']."','".$product['name']."','".$product['brand']."'),";
        }
    
    if(!empty($s)){
        $s = substr($s,0,-1);

        $query = "INSERT INTO ap_produk_paragon
                    (id_produk,nama_produk,brand)
                VALUES
                    $s";
        $r = mysqli_query($con,$query);

        $query = "UPDATE ap_produk_paragon,ap_produk
                    set ap_produk.nama_produk=ap_produk_paragon.nama_produk
                WHERE ap_produk.id_produk=ap_produk_paragon.id_produk";
        $r = mysqli_query($con,$query);
        $affected_rows = mysqli_affected_rows($con);

        echo "UPDATE: " . $affected_rows;

        $query = "INSERT IGNORE INTO ap_produk
                    (id_produk,nama_produk,id_brand,satuan,`status`,`type`,brand,departemen)
                SELECT ap_produk_paragon.id_produk,ap_produk_paragon.nama_produk,
                brand.id_brand,concat('Pcs'),concat('1'),concat('1'),
                ap_produk_paragon.brand,concat('import paragon ',now()) from
                ap_produk_paragon 
                left join brand on brand.brand=ap_produk_paragon.brand";
        $r = mysqli_query($con,$query);
        $affected_rows = mysqli_affected_rows($con);

        echo "INSERT: " . $affected_rows;
        
    }
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
    // Jika API membutuhkan header khusus (misalnya Authorization), tambahkan header
    $headers = array(
        "Authorization: Bearer 009", // Ganti dengan token yang sesuai
        "Content-Type: application/json", // Kirimkan data sebagai JSON
        "User-Agent: PostmanRuntime/7.28.0", // Ganti dengan User-Agent dari Postman
        "Origin: https://api-customer-platform.pti-cosmetics.com", // Jika server memerlukan header Origin
        "Referer: https://api-customer-platform.pti-cosmetics.com" // Jika server memerlukan header Referer
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

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
    // URL tujuan API
    $apiUrl = 'https://api-customer-platform.pti-cosmetics.com/api/v1/products/list'; // Ganti dengan URL yang sesuai

    // Data yang akan dikirimkan (sebagai JSON)
    $data = array(
        'access_key' => '5ee8198a85-e00f991993b8-491c63e0cf-4e48736d-d2b3a2-c363218dd7a012'
    );
    $jsonData = json_encode($data);

    // Inisialisasi cURL
    $ch = curl_init($apiUrl);

    // Set opsi cURL untuk melakukan POST request
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Menyimpan hasil response
    curl_setopt($ch, CURLOPT_POST, true);            // Menggunakan metode POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Kirim data sebagai query string

    // Jika API membutuhkan header khusus (misalnya Authorization), tambahkan header
    $headers = array(
        "Authorization: Bearer 009", // Ganti dengan token yang sesuai
        "Content-Type: application/json", // Kirimkan data sebagai JSON
        "User-Agent: PostmanRuntime/7.28.0", // Ganti dengan User-Agent dari Postman
        "Origin: https://api-customer-platform.pti-cosmetics.com", // Jika server memerlukan header Origin
        "Referer: https://api-customer-platform.pti-cosmetics.com" // Jika server memerlukan header Referer
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    // Eksekusi permintaan dan ambil responsenya
    $response = curl_exec($ch);

    $json_string = json_encode($response, JSON_PRETTY_PRINT);

    // Cek jika terjadi error
    $data = '';
    if(curl_errno($ch)) {
        //echo 'Curl error: ' . curl_error($ch);
    } else {
        // Jika tidak ada error, tampilkan hasil response
        // Parsing JSON menjadi array PHP
        $data = json_decode($response, true); // Menggunakan `true` untuk mendapatkan hasil dalam bentuk array asosiatif
    }
    // Tutup koneksi cURL
    curl_close($ch);
    return $data;


    
    


    
  }