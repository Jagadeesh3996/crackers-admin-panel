<?php
    include('session.php');
    require('dompdf/autoload.inc.php');
    include('db.php');

    use Dompdf\Dompdf;
    $dompdf = new Dompdf();

    // check url parameter  is set or not
    if (isset($_GET['oid'])) {
        $oid = $_GET['oid'];
    } else {
        header("Location: orders.php");
        exit();
    }

    // fetch data
    $query2 = "select * from tbl_orders where order_id='$oid' and status>='1' limit 1";
    $result2 = mysqli_query($conn, $query2);
    if ($pitem = mysqli_fetch_array($result2)) {
        $date = $pitem['date'];
        $c_name = $pitem['name'];
        $c_email = $pitem['email'];
        $c_mobile = $pitem['phone'];
        $c_whatsapp = $pitem['whatsapp'];
        $c_address = $pitem['address'];
        $total = $pitem['total'];
        $pcharge = $pitem['packing_charge'];
        $promodiscount = $pitem['promotion_discount'];
        $finialtotal = $pitem['final_total'];
        $products = json_decode($pitem['products']);
        $dateonly = date("d-m-Y");
    
        // Create HTML content for the PDF
        $html = '
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Estimate Report</title>
                        <link rel="shortcut icon" href="./assets/images/logo.png" type="image/x-icon" />
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@100..900&display=swap" rel="stylesheet">
                        <style>
                            .tamil {
                              font-family: "Noto Sans Tamil", sans-serif;
                              font-optical-sizing: auto;
                              font-weight: 400;
                              font-style: normal;
                              font-variation-settings: "wdth" 100;
                            }
                            body {
                                font-family: "Arial", sans-serif;
                            }
                            td{
                                padding: 5px;
                            }
                            .bd{
                                border: 2px solid black;
                            }
                            .pd-10{
                                padding: 10px;
                            }
                            .rbd td{
                                border: 2px solid black;
                                text-align: center;
                            }
                            .fs-20{
                                font-size: 20px;
                            }
                            .none h4,
                            .none p{
                                margin:5px;
                            }
                            .rline{
                                border-right: 2px solid black;
                            }
                            .bline{
                                border-bottom: 2px solid black;
                            }
                            .tline{
                                border-top: 2px solid black;
                            }
                            .right{
                                text-align:right;
                            }
                            .none h4{
                                text-decoration: underline;
                            }
                        </style>
                    </head>
                    <body>
                        <main class="main">
                            <table class="bd" style="width:100%" cellspacing="0">
                                <tr class="bd">
                                    <td colspan="3" class="bline">Estimate No : <b>'.$oid.'</b></td>
                                    <td colspan="3" class="bline"><b>ESTIMATE REPORT</b></td>
                                    <td colspan="2" class="bline right">Date : <b>'.$date.'</b></td>
                                </tr>
                                <tr class="bd">
                                    <td colspan="4" class="bline">Phone : <b>'.$site_mobile_number.' , '.$site_whatsapp_number.' </b></td>
                                    <td colspan="4" class="bline right">Email : <b>'.$site_email.'</b></td>
                                </tr>
                                <tr>
                                    <td class="bline"></td>
                                    <td colspan="6" class="bline"><center>
                                        <h2><b>'.$site_name.'</b></h2>
                                        <p>'.$site_address.'</p>
                                    </center></td>
                                    <td class="bline"></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="none rline">
                                        <h4><center><b>Customer Details</b></center></h4>
                                        <p class="tamil">Name : <b>'.$c_name.'</b></p>
                                        <p>Mobile : <b>'.$c_mobile.'</b></p>
                                        <p>Whatsapp : <b>'.$c_whatsapp.'</b></p>
                                        <p>E-Mail Id : <b>'.$c_email.'</b></p>
                                        <p class="tamil">Address : <b>'.$c_address.'</b></p>
                                        <p class="tamil">Refer by : <b>'.$pitem['refer'].'</b></p>
                                    </td>
                                    <td colspan="4" class="none">
                                        <h4><center><b>Bank Details</b></center></h4>
                                        <p>Acc Name : <b>'.$account_holder_name.'</b></p>
                                        <p>Acc Number : <b>'.$account_number.'</b></p>
                                        <p>IFSC Code : <b>'.$ifsc_code.'</b></p>
                                        <p>Bank Name : <b>'.$bank_name.'</b></p>
                                        <p>Branch : <b>'.$branch_name.'</b></p>
                                    </td>
                                </tr>
                                <tr class="rbd">
                                    <td><b>S.No</b></td>
                                    <td><b>Code</b></td>
                                    <td style="min-width:1000px" colspan="2"><b>Product Name</b></td>
                                    <td><b>MRP (Rs)</b></td>
                                    <td><b>Quantity</b></td>
                                    <td><b>Price (Rs)</b></td>
                                    <td><b>Amount (Rs)</b></td>
                                </tr>';

                                $k = 1;
                                $totalqty = 0;
                                $nettotal = 0;
                                foreach($products as $key=>$prod){
                                    $totalqty += $prod->prd_qty;
                                    $nettotal += $prod->prd_nettotal;
                                    $p_id = $prod->prd_id;
                                    $q2 = "select * from tbl_product where id='$p_id' limit 1";
                                    $res2 = mysqli_query($conn, $q2);
                                    $item = mysqli_fetch_array($res2);
                                    $html .= '<tr class="rbd">
                                                <td>'.$k.'</td>
                                                <td>'.$item['alignment'].'</td>
                                                <td colspan="2">'.$prod->prd_name.'</td>
                                                <td>'.number_format($prod->prd_mrp,2).'</td>
                                                <td>'.$prod->prd_qty.'</td>
                                                <td>'.number_format($prod->prd_price,2).'</td>
                                                <td>'.number_format($prod->prd_total,2).'</td>
                                            </tr>';
                                    $k++;
                                }
                        $html .= '
                                <tr class="right">
                                    <td colspan="7">Net Total (Rs) : </td>
                                    <td><b>'.number_format($nettotal,2).'</b></td>
                                </tr>
                                <tr class="right">
                                    <td colspan="7">Discount Price (Rs) : </td>
                                    <td><b>'.number_format(($nettotal - $total),2).'</b></td>
                                </tr>
                                <tr class="right">
                                    <td colspan="7">Sub Total (Rs) : </td>
                                    <td><b>'.number_format($total,2).'</b></td>
                                </tr>
                                <tr class="right">
                                    <td colspan="7">Packing Charge (Rs) : </td>
                                    <td><b>'.number_format($pcharge,2).'</b></td>
                                </tr>
                                <tr class="right">
                                    <td colspan="7">Promotion Discount (Rs) : </td>
                                    <td><b>'.number_format($promodiscount,2).'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="tline">Total items : <b>'.($k-1).'</b></td>
                                    <td colspan="2" style="text-align:center;" class="tline">Total Quantity : <b>'.number_format($totalqty).'</b></td>
                                    <td colspan="3" class="tline right"><b>Overall Total (Rs) : </b></td>
                                    <td colspan="1" class="tline right"><b>'.number_format($finialtotal,2).'</b></td>
                                </tr>
                            </table>
                        </main>
                    </body>
                </html>';

        // Initialize dompdf
        $dompdf-> set_option('enable_html5_parser', TRUE);	    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream("'$site_name'_'$dateonly'.pdf", ["Attachment" => false]);	            
    } else {
        header("Location: orders.php");
        exit();
    }
?>