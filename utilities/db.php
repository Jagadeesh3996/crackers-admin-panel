<?php
error_reporting(0);
// $conn = mysqli_connect("localhost", "u342669607_newadmin", 'jV[1Y@eV3', "u342669607_newadmin");
$conn = mysqli_connect("localhost", "root", "", "demo");
date_default_timezone_set("Asia/Kolkata");
if (!$conn) {
    echo "not connected database";
}
// Set character set to UTF-8
mysqli_set_charset($conn, "utf8");

if (isset($_SERVER['HTTPS'])) {
    $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
} else {
    $protocol = "http";
}

$query = "SELECT * FROM tbl_shopsetting";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_array($result);

// estimate page status
$site_status = $data['website_status'];

// Site Details
$site_data = json_decode($data['shop_details'], true);

// for local
$domain = "localhost/kickvy";
$site_url = $protocol . '://' . $domain . '/site';
$admin_url = $protocol . '://' . $domain . '/admin';

// for live
// $domain = $site_data['shop_url'];
// $site_url = $protocol . '://newsite.' . $domain;
// $admin_url = $protocol . '://newadmin.' . $domain;
$site_name = $site_data['shop_name'];
$site_code = $site_data['shop_code'];
$billing_discount = $site_data['billing_discount'];
$site_whatsapp_number = $site_data['whatsapp_number'];
$site_mobile_number = $site_data['mobile_number'];
$site_alternate_mobile_number = $site_data['alternate_mobile_number'];
$site_email = $site_data['email'];
$site_googlemap_location_url = $site_data['googlemap_location_url'];
$site_googlemap_embed_url = $site_data['googlemap_embed_url'];
$site_minimum_order = $site_data['shop_minimum_order'];
$site_address = $site_data['address'];
$site_facebook = $site_data['facebook'];
$site_instagram = $site_data['instagram'];
$site_youtube = $site_data['youtube'];
$site_twitter = $site_data['twitter'];

// scanner Details
$scan_list = json_decode($data['scanner_details'], true);

$g = 0;
$ph = 0;
$pt = 0;
$ot = 0;
foreach ($scan_list as $key => $value) {
    if ($g == 0 && $value[0] == "Google Pay") {
        $site_googlepay_number = $value[1];
        $g = 1;
    }
    if ($ph == 0 && $value[0] == "Phone Pay") {
        $site_phonepay_number = $value[1];
        $ph = 1;
    }
    if ($pt == 0 && $value[0] == "Paytm") {
        $site_paytm_number = $value[1];
        $pt = 1;
    }
    if ($ot == 0 && $value[0] == "Other UPI") {
        $site_otherupi = $value[1];
        $ot = 1;
    }
}

// Bank Details
$bank_list = json_decode($data['bank_details'], true);

foreach ($bank_list as $key => $value) {
    $bank_name = $value[0];
    $account_holder_name = $value[1];
    $account_number = $value[2];
    $ifsc_code = $value[3];
    $branch_name = $value[4];
    break;
}
