<?php
include('../utilities/session.php');

// fetch data
$query = "SELECT * FROM tbl_shopsetting WHERE status = 1 AND id = 1 LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$shop_details = json_decode($row['shop_details'], true);
$scan_details = $row['scanner_details'] == null ? '{}' : $row['scanner_details'];
$bank_details = $row['bank_details'] == null ? '{}' : $row['bank_details'];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<!-- head Start -->
<?php include('../utilities/head.php'); ?>
<!-- head END -->
<style>
    @media (max-width:576px) {
        .espage {
            flex-direction: column;
        }
    }
</style>

<body>
    <!-- loader Start -->
    <?php include('../utilities/loader.php') ?>
    <!-- loader End -->

    <!-- sidenav Start -->
    <?php include('../utilities/side_nav.php') ?>
    <!-- sidenav End -->

    <!-- Main Content Start -->
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--topnav Start-->
            <?php include('../utilities/top_nav.php') ?>
            <!--topnav End-->

            <!-- Header Start -->
            <?php include('../utilities/header.php') ?>
            <!-- Header End -->
        </div>

        <!-- Hero Section Start -->
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex form-group espage">
                                <h3 class="card-title text-dark m-0 mt-1">Estimate Page Status : </h3>
                                <input type="checkbox" name="website_status" id="website_status" class="togglebox2" <?= $row['website_status'] == 1 ? 'checked' : ''; ?> onchange="webStatus()" />
                                <label for="website_status" class="toggle2 ps-2 pt-1 ms-2 mt-1 text-white"><b>Running&nbsp;&nbsp;Shut&nbsp;Down</b></label>
                            </div>
                        </div>
                        <div class="card-header text-center">
                            <div class="header-title">
                                <h3 class="card-title text-dark">Shop Settings</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="d-flex">
                                        <h3 class="card-title text-dark m-0 mt-1">Shop Details</h3>
                                    </div>
                                </div>
                                <div class="row ms-2">
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="shop_name">Shop Name</label>
                                            <input type="text" class="form-control shop" name="shop_name" id="shop_name" value="<?= $shop_details['shop_name'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="shop_code">Shop Code</label>
                                            <input type="text" class="form-control shop" name="shop_code" id="shop_code" value="<?= $shop_details['shop_code'] ?>" oninput="codevalitate()" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="shop_url">Shop Domain</label>
                                            <input type="text" class="form-control shop" name="shop_url" id="shop_url" value="<?= $shop_details['shop_url'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="shop_minimum_order">Minimum Order Value (Rs)</label>
                                            <input type="number" class="form-control shop" name="shop_minimum_order" id="shop_minimum_order" value="<?= $shop_details['shop_minimum_order'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="billing_discount">Billing Discount (%)</label>
                                            <input type="text" class="form-control shop" name="billing_discount" id="billing_discount" value="<?= $shop_details['billing_discount'] ?>" oninput="valitate()" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="whatsapp_number">Whatsapp Number</label>
                                            <input type="text" class="form-control shop" name="whatsapp_number" id="whatsapp_number" value="<?= $shop_details['whatsapp_number'] ?>" oninput="numbervalitate()" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="mobile_number">Mobile Number</label>
                                            <input type="text" class="form-control shop" name="mobile_number" id="mobile_number" value="<?= $shop_details['mobile_number'] ?>" oninput="numbervalitate()" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="alternate_mobile_number">Alternate Mobile Number</label>
                                            <input type="text" class="form-control shop" name="alternate_mobile_number" id="alternate_mobile_number" value="<?= $shop_details['alternate_mobile_number'] ?>" oninput="numbervalitate()" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="email">Email ID</label>
                                            <input type="email" class="form-control shop" name="email" id="email" value="<?= $shop_details['email'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="googlemap_location_url">GoogleMap Location URL</label>
                                            <input type="text" class="form-control shop" name="googlemap_location_url" id="googlemap_location_url" value="<?= $shop_details['googlemap_location_url'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="googlemap_embed_url">GoogleMap Embed URL</label>
                                            <input type="text" class="form-control shop" name="googlemap_embed_url" id="googlemap_embed_url" value="<?= $shop_details['googlemap_embed_url'] ?>" oninput="getSrc()" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control shop" name="address" id="address" readonly><?= $shop_details['address'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="gst_no">GST No</label>
                                            <input type="text" class="form-control shop" name="gst_no" id="gst_no" value="<?= $shop_details['gst_no'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <hr class="hr-horizontal">
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="d-flex">
                                        <h3 class="card-title text-dark m-0 mt-1">Social Media</h3>
                                    </div>
                                </div>
                                <div class="row ms-2">
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="facebook">Facebook</label>
                                            <input type="text" class="form-control shop" name="facebook" id="facebook" value="<?= $shop_details['facebook'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="instagram">Instagram</label>
                                            <input type="text" class="form-control shop" name="instagram" id="instagram" value="<?= $shop_details['instagram'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="youtube">YouTube</label>
                                            <input type="text" class="form-control shop" name="youtube" id="youtube" value="<?= $shop_details['youtube'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="twitter">Twitter</label>
                                            <input type="text" class="form-control shop" name="twitter" id="twitter" value="<?= $shop_details['twitter'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <hr class="hr-horizontal">
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="d-flex">
                                        <h3 class="card-title text-dark m-0 mt-1">Scanner Details</h3>
                                        <input type="button" id="add_scan" value="add" class="btn btn-success text-uppercase ms-2">
                                    </div>
                                </div>
                                <div id="listScan" class="row ms-2"></div>
                                <div class="col-12">
                                    <hr class="hr-horizontal">
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="d-flex">
                                        <h3 class="card-title text-dark m-0 mt-1">Bank Details</h3>
                                        <input type="button" id="add_bank" value="add" class="btn btn-success text-uppercase ms-2">
                                    </div>
                                </div>
                                <div id="listBank" class="row ms-2"></div>
                            </div>
                            <div class="text-center">
                                <input type="button" id="edit_btn" value="edit" class="btn btn-warning text-uppercase ms-2">
                                <input type="button" id="update_btn" value="update" class="btn btn-success text-uppercase ms-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero Section End -->

        <!-- Footer Section Start -->
        <?php include('../utilities/footer.php') ?>
        <!-- Footer Section End -->
    </main>

    <!-- add scanner popup - start -->
    <div id="overlay">
        <div id="popup">
            <h3 class="text-center mb-2">Add Scanner Details</h3>
            <form id='addScan'>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="spay">Select Pay</label>
                            <select name="spay" id="spay" class="form-control" required>
                                <option value="" hidden>Select Pay</option>
                                <option value="Google Pay">Google Pay</option>
                                <option value="Phone Pay">Phone Pay</option>
                                <option value="Paytm">Paytm</option>
                                <option value="Other UPI">Other UPI</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="number">Number</label>
                            <input type="text" class="form-control" name="number" id="number" required>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-success w-100" value="Submit" />
            </form>
            <input id="closeoverlay" type='button' class="close btn btn-danger w-100 mt-3" value="Close">
        </div>
    </div>
    <!-- add scanner popup - end -->

    <!-- add bank popup - start -->
    <div class="modal" id="addpopup">
        <div class="modal-content">
            <h3 class="text-center mb-2">Add Bank Details</h3>
            <form id='addBank'>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="bankname">Bank Name</label>
                            <input type="text" class="form-control" name="bankname" id="bankname" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="ahname">Account Holder Name</label>
                            <input type="text" class="form-control" name="ahname" id="ahname" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="acnumber">Account Number</label>
                            <input type="number" class="form-control" name="acnumber" id="acnumber" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="ifsccode">IFSC Code</label>
                            <input type="text" class="form-control" name="ifsccode" id="ifsccode" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="brnname">Branch Name</label>
                            <input type="text" class="form-control" name="brnname" id="brnname" required>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-success w-100" value="Submit" />
            </form>
            <input id="bankclosepopup" type='button' class="close btn btn-danger w-100 mt-3" value="Close">
        </div>
    </div>
    <!-- add bank popup - end -->

    <!-- edit bank popup - start -->
    <div class="modal" id="editbankpopup">
        <div class="modal-content">
            <h3 class="text-center mb-2">Edit Bank Details</h3>
            <form id='editBank'>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_bankname">Bank Name</label>
                            <input type="text" class="form-control" name="edit_bankname" id="edit_bankname" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_ahname">Account Holder Name</label>
                            <input type="text" class="form-control" name="edit_ahname" id="edit_ahname" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_acnumber">Account Number</label>
                            <input type="number" class="form-control" name="edit_acnumber" id="edit_acnumber" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_ifsccode">IFSC Code</label>
                            <input type="text" class="form-control" name="edit_ifsccode" id="edit_ifsccode" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_brnname">Branch Name</label>
                            <input type="text" class="form-control" name="edit_brnname" id="edit_brnname" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="edit_id" id="edit_id" required>
                <input type="submit" class="btn btn-success w-100" value="Submit" />
            </form>
            <input id="bankcloseeditpopup" type='button' class="close btn btn-danger w-100 mt-3" value="Close">
        </div>
    </div>
    <!-- edit bank popup - end -->

    <!-- script - start -->
    <?php include('../utilities/script.php') ?>
    <!-- script - end -->

    <script>
        $("#update_btn").hide();
        $("#add_scan").hide();
        $("#add_bank").hide();
        $("#overlay").hide();
        $("#addpopup").hide();
        $("#editbankpopup").hide();
        let scan_details = {
            ...<?= $scan_details ?>
        };
        let bank_details = {
            ...<?= $bank_details ?>
        };

        // get src from iframe
        const getSrc = () => {
            const iframeString = $("#googlemap_embed_url").val().trim();
            const match = iframeString.match(/src="([^"]+)"/);
            const src = match ? match[1] : $("#googlemap_embed_url").val().trim();
            $("#googlemap_embed_url").val(src);
        };

        // validations start
        const valitate = () => {
            const field = $(event.target).attr('id');
            $("#" + field).val($("#" + field).val().replace(/[^0-9]/g, '').substring(0, 2));
        };
        // validations end

        // shopcodevalidations start
        const codevalitate = () => {
            const field = $(event.target).attr('id');
            const code = $("#" + field).val().replace(/[^a-zA-Z0-9]/g, '').substring(0, 3);
            $("#" + field).val(code.toUpperCase());
        };
        // shopcodevalidations end

        // number validate start
        const numbervalitate = () => {
            const field = $(event.target).attr('id');
            $("#" + field).val($("#" + field).val().replace(/[^0-9]/g, '').substring(0, 10));
        };
        // number validate end

        // delete scanner - start
        const delScan = () => {
            const scid = $(event.target).data("item-id");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this data!",
                icon: "warning",
                customClass: {
                    confirmButton: 'my-swal-confirm-button',
                },
                showCancelButton: true,
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#scan_" + scid).remove();
                    delete scan_details[scid];
                    Swal.fire({
                        title: 'Data deleted Successfully!',
                        icon: 'success',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    });
                }
            });
        };
        // delete scanner - end

        // delete bank - start
        const delBank = () => {
            const bkid = $(event.target).data("item-id");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this data!",
                icon: "warning",
                customClass: {
                    confirmButton: 'my-swal-confirm-button',
                },
                showCancelButton: true,
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#bank_" + bkid).remove();
                    delete bank_details[bkid];
                    Swal.fire({
                        title: 'Data deleted Successfully!',
                        icon: 'success',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    });
                }
            });
        };
        // delete bank - end

        // edit - fetch bank - start
        const editBank = () => {
            const bkid = $(event.target).data("item-id");
            $('#edit_id').val(bkid);
            $("#edit_bankname").val(bank_details[bkid][0]);
            $("#edit_ahname").val(bank_details[bkid][1]);
            $("#edit_acnumber").val(bank_details[bkid][2]);
            $("#edit_ifsccode").val(bank_details[bkid][3]);
            $("#edit_brnname").val(bank_details[bkid][4]);
            $("#editbankpopup").show();
        };
        // edit - fetch bank - end

        // scanner details - start
        const scanList = () => {
            let sckeys = Object.keys(scan_details);
            $("#listScan").empty();
            sckeys.forEach(item => {
                const newItem = $('<div></div>').addClass("col-md-3 col-12").attr("id", "scan_" + item).html(
                    `<div class="card p-1 text-center" style="background: #90d4d3;border:none">
                            <ul class="p-2 m-0" style="background: #fff;list-style:none;">
                                <li class="mb-1"><h5>${scan_details[item][0]}</h5></span></li>
                                <li class="mb-1">${scan_details[item][1]}</li>
                            </ul>
                            <a style="position: absolute;right:0;top:0" class='width-fit-content btn btn-sm btn-icon btn-danger edb' data-item-id='${item}' onclick="delScan()" data-bs-toggle='tooltip' data-bs-placement='top' title='Delete'>
                                <svg class='icon-20' data-item-id='${item}' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
                                    <path d='M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    <path d='M20.708 6.23975H3.75' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    <path d='M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                </svg>
                            </a>
                        </div>`
                );
                $("#listScan").append(newItem);
            });
        };
        $(document).ready(function() {
            // open add popup          
            $('#add_scan').click(() => $('#overlay').show());

            // close add popup
            $('#closeoverlay').click(() => {
                $('#overlay').hide();
                $('#addScan')[0].reset();
            });

            // add scanner - start
            $('#addScan').submit(function(e) {
                e.preventDefault();
                const spay = $("#spay").val();
                const number = $("#number").val();
                let lastItem = Object.keys(scan_details).pop();
                let scanitem = (lastItem) ? Number(lastItem.split("_")[1]) : 0;
                let key = `sc_${scanitem + 1}`;
                scan_details = {
                    ...scan_details,
                    [key]: [spay, number]
                };
                $('#overlay').hide();
                $('#addScan')[0].reset();
                scanList();
            });
            // add scanner - end
        });
        // scanner details - end

        // bank details - start
        const bankList = () => {
            let bkkeys = Object.keys(bank_details);
            $("#listBank").empty();
            bkkeys.forEach(item => {
                const newItem = $('<div></div>').addClass("col-md-4 col-12").attr("id", "bank_" + item).html(
                    `<div class="card p-1 text-center" style="background: #90d4d3;border:none">
                            <ul class="p-2 m-0" style="background: #fff;list-style:none;">
                                <li class="mb-1">Bank Name : ${bank_details[item][0]}</li>
                                <li class="mb-1">Account Holder Name : ${bank_details[item][1]}</li>
                                <li class="mb-1">Account Number : ${bank_details[item][2]}</li>
                                <li class="mb-1">IFSC Code : ${bank_details[item][3]}</li>
                                <li class="mb-1">Branch Name : ${bank_details[item][4]}</li>
                            </ul>
                            <a style="position: absolute;left:0;top:0" class='width-fit-content btn btn-sm btn-icon btn-warning edb' data-item-id='${item}' onclick="editBank()" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit'>
                                <svg class='icon-20' data-item-id='${item}' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                    <path d='M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    <path fill-rule='evenodd' clip-rule='evenodd' d='M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    <path d='M15.1655 4.60254L19.7315 9.16854' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                </svg>
                            </a>                                                       
                            <a style="position: absolute;right:0;top:0" class='width-fit-content btn btn-sm btn-icon btn-danger edb' data-item-id='${item}' onclick="delBank()" data-bs-toggle='tooltip' data-bs-placement='top' title='Delete'>
                                <svg class='icon-20' data-item-id='${item}' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
                                    <path d='M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    <path d='M20.708 6.23975H3.75' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    <path d='M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                </svg>
                            </a>
                        </div>`
                );
                $("#listBank").append(newItem);
            });
        };
        $(document).ready(function() {
            // open add popup          
            $('#add_bank').click(() => $('#addpopup').show());

            // close add popup
            $('#bankclosepopup').click(() => {
                $('#addpopup').hide();
                $('#addBank')[0].reset();
            });

            // close edit popup
            $('#bankcloseeditpopup').click(() => {
                $('#editbankpopup').hide();
                $('#editBank')[0].reset();
            });

            // add bank - start
            $('#addBank').submit(function(e) {
                e.preventDefault();
                const bankname = $("#bankname").val().trim();
                const ahname = $("#ahname").val().trim();
                const acnumber = $("#acnumber").val();
                const ifsccode = $("#ifsccode").val().trim();
                const brnname = $("#brnname").val().trim();
                let lastItem = Object.keys(bank_details).pop();
                let bankitem = (lastItem) ? Number(lastItem.split("_")[1]) : 0;
                let key = `bk_${bankitem + 1}`;
                if (bankname == "" || ahname == "" || acnumber == "" || ifsccode == "" || brnname == "") {
                    Swal.fire({
                        title: "Field cannot be Empty!",
                        icon: "warning",
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    });
                    return;
                };
                bank_details = {
                    ...bank_details,
                    [key]: [bankname, ahname, acnumber, ifsccode, brnname]
                };
                $('#addpopup').hide();
                $('#addBank')[0].reset();
                bankList();
            });
            // add bank - end

            // edit bank - start
            $('#editBank').submit(function(e) {
                e.preventDefault();
                const bankname = $("#edit_bankname").val().trim();
                const ahname = $("#edit_ahname").val().trim();
                const acnumber = $("#edit_acnumber").val();
                const ifsccode = $("#edit_ifsccode").val().trim();
                const brnname = $("#edit_brnname").val().trim();
                let key = $("#edit_id").val();
                if (bankname == "" || ahname == "" || acnumber == "" || ifsccode == "" || brnname == "") {
                    Swal.fire({
                        title: "Field cannot be Empty!",
                        icon: "warning",
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    });
                    return;
                };
                bank_details = {
                    ...bank_details,
                    [key]: [bankname, ahname, acnumber, ifsccode, brnname]
                };
                $('#editbankpopup').hide();
                $('#editBank')[0].reset();
                bankList();
            });
            // edit bank - end
        });
        // bank details - end

        const webStatus = () => {
            const website_status = $("#website_status").is(':checked') ? 1 : 2;
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/shopsetting/',
                data: {
                    req_type: "web_status",
                    website_status: website_status,
                },
                success: (response) => {
                    if (response.trim() == "success") {
                        Swal.fire({
                            title: 'Estimate Page Status Updated!',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            window.location.reload(true);
                        });
                    } else {
                        Swal.fire({
                            title: res.trim(),
                            icon: 'error',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        });
                    }
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                }
            });
        };

        // shop details - start
        $("#edit_btn").click(() => {
            $('.shop').attr('readonly', false).css("border", "2px solid #90d4d3");
            $("#edit_btn").hide();
            $("#update_btn").show();
            $("#add_scan").show();
            $("#add_bank").show();
            $(".edb").show();
        });

        $("#update_btn").click(() => {
            $('#update_btn').prop('disabled', true).val('updating...');
            const sName = $('#shop_name').val().trim();
            const sUrl = $('#shop_url').val().trim();
            const email = $('#email').val().trim();
            const location = $('#googlemap_location_url').val().trim();
            const embed = $('#googlemap_embed_url').val().trim();
            const ads = $('#address').val().trim();
            const address = ads.replace(/\n/g, ' ');
            const mobile = $('#mobile_number').val();
            const whatsapp = $("#whatsapp_number").val();
            const altNumber = $("#alternate_mobile_number").val();
            const mov = $('#shop_minimum_order').val();
            const billdis = $('#billing_discount').val();
            const gstno = $("#gst_no").val().trim();
            if (sName == "" || sUrl == "" || address == "" || mov == "" || billdis == "" || mobile == "" || whatsapp == "" || gstno == "") {
                Swal.fire({
                    title: "Field cannot be Empty!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $('#update_btn').prop('disabled', false).val('update');
                });
                return;
            };
            if (!(/^[6789]\d{9}$/.test(mobile))) {
                Swal.fire({
                    title: "Enter Valid Mobile number!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $('#update_btn').prop('disabled', false).val('update');
                });
                return;
            };
            if (!(/^[6789]\d{9}$/.test(whatsapp))) {
                Swal.fire({
                    title: "Enter Valid WhatsApp number!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $('#update_btn').prop('disabled', false).val('update');
                });
                return;
            };
            if (altNumber != "" && !(/^[6789]\d{9}$/.test(altNumber))) {
                Swal.fire({
                    title: "Enter Valid Alternate number!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $('#update_btn').prop('disabled', false).val('update');
                });
                return;
            };
            if (email != "" && !(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email))) {
                Swal.fire({
                    title: "Enter Valid Email Id!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $('#update_btn').prop('disabled', false).val('update');
                });
                return;
            };

            let data = {
                'shop_name': sName,
                'shop_code': $("#shop_code").val(),
                'shop_url': sUrl,
                'shop_minimum_order': mov,
                'billing_discount': billdis,
                'whatsapp_number': whatsapp,
                'mobile_number': mobile,
                'alternate_mobile_number': altNumber,
                'email': email,
                'googlemap_location_url': location,
                'googlemap_embed_url': embed,
                'address': address,
                'gst_no': gstno,
                'facebook': $("#facebook").val().trim(),
                'instagram': $("#instagram").val().trim(),
                'youtube': $("#youtube").val().trim(),
                'twitter': $("#twitter").val().trim(),
            };

            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/shopsetting/',
                data: {
                    req_type: "up_shop",
                    data: data,
                    scanner: scan_details,
                    bank: bank_details,
                },
                success: (response) => {
                    if (response.trim() == "success") {
                        Swal.fire({
                            title: 'Shop Settings Updated successfully!',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            window.location.reload(true);
                        });
                    } else {
                        Swal.fire({
                            title: res.trim(),
                            icon: 'error',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            $('#update_btn').prop('disabled', false).val('update');
                        });
                    }
                },
                error: (xhr, status, error) => {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
        // shop details - end

        scanList();
        bankList();
        $(".edb").hide();
    </script>
</body>

</html>