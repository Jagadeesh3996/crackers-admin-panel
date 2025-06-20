<?php
include('../utilities/session.php');

$query = "SELECT * FROM tbl_invoice ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);
$id = $row['id'] ?? 0;
//create billing id
$year = substr((date('Y')), -2);
$invoice = 'Invoice/' . $year . '-' . ($year + 1) . '/' . sprintf("%03d", ($id + 1));
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<!-- head Start -->
<?php include('../utilities/head.php'); ?>
<!-- head END -->

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

            <!-- header Start -->
            <div class="iq-navbar-header bg-color" style="height: 215px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center">
                                <div>
                                    <h1>INVOICE</h1>
                                </div>
                                <div>
                                    <a href="<?= $admin_url ?>/pages/viewinvoice/"><button type="button" class="btn btn-success">
                                            <svg class='icon-20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512' stroke='currentColor'>
                                                <path fill='#fff' d='M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z' />
                                            </svg>&nbsp;
                                            View Invoice
                                        </button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="iq-header-img">
                        <img src="../assets/images/dashboard/top-header.png" alt="header"
                            class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
                    </div> -->
            </div>
            <!-- header End -->
        </div>

        <!-- Hero Section Start -->
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <form class="row" id="billingdata" method="POST">
                <div class="container-fluid">
                    <div class="col-sm-12" style="overflowX: hidden;">
                        <div class="card">
                            <div class="row p-3 g-2 cform text-center">
                                <div class="col-12 col-lg-9 row g-2 text-center">
                                    <div class="col-12 col-sm-6 col-xxl-4 text-center"><input type="text " name="cname" id="cname" placeholder="Customer Name" required /></div>
                                    <div class="col-12 col-sm-6 col-xxl-4 text-center"><input type="number" name="cnumber" id="cnumber" placeholder="Phone Number" oninput="valitate()" required /></div>
                                    <div class="col-12 col-sm-6 col-xxl-4 text-center"><input type="number" name="cwhatsno" id="cwhatsno" placeholder="Whatsapp Number" oninput="valitate()" /></div>
                                    <div class="col-12 col-sm-6 col-xxl-4 cr-p text-center"><input type="date" name="bdate" id="bdate" oninput="dateValitate()" /></div>
                                    <div class="col-12 col-sm-6 col-xxl-4 text-center">
                                        <select name="cidp" id="cidp" class="cr-p" onchange="idProof()">
                                            <option value="">Select Any Id proof</option>
                                            <option value="Aadhar Card">Aadhar Card</option>
                                            <option value="Driving Licence">Driving Licence</option>
                                            <option value="Passport">Passport</option>
                                            <option value="Voter Id">Voter Id</option>
                                            <option value="Ration Card">Ration Card</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 col-xxl-4 text-center"><input type="text" name="cidproof" id="cidproof" placeholder="Id proof" readonly /></div>
                                    <div class="col-12 col-sm-6 col-xxl-4 text-center">
                                        <select name="cmop" id="cmop" class="cr-p">
                                            <option value="">Select Payment</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Net Banking">Net Banking</option>
                                            <option value="UPI Id">UPI</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 col-xxl-4 text-center"><input type="text " name="gst" id="gst" placeholder="Customer GST no" /></div>
                                    <div class="col-12 col-sm-6 col-xxl-4 text-center">
                                        <input type="checkbox" name="packing" id="packing" class="packbox" oninput="pcharge()" />
                                        <label for="packing" class="packbtn pt-1 text-white"></label>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 py-2"><textarea name="caddress" id="caddress" placeholder="Customer Address..."></textarea></div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive flex">
                                    <div>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="wnor">s.no</th>
                                                </tr>
                                            </thead>
                                            <tbody id="show_sno"></tbody>
                                        </table>
                                    </div>
                                    <div>
                                        <table class="table table-striped" role="grid" data-bs-toggle="data-table">
                                            <thead>
                                                <tr>
                                                    <th class="wbig">Product Name</th>
                                                    <th class="wnor">MRP Price (Rs)</th>
                                                    <th class="wnor">Quantity</th>
                                                    <th class="wnor">Discount (%)</th>
                                                    <th class="wnor">Discount Price (Rs)</th>
                                                    <th>Total (Rs)</th>
                                                    <th class="wnor">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="addItems"></tbody>
                                            <tr>
                                                <td colspan="1" style="text-align:left;">
                                                    <button type="button" id="addBox" class="btn btn-success">+ Add Item</button>
                                                </td>
                                                <td class="totaltr" colspan="3">Total Quantity : <input type="number" readonly value="0" id="totalQty" name="totalQty" /></td>
                                                <td class="totaltr" colspan="1">Total Amount (Rs) : </td>
                                                <td class="totaltr" colspan="1"><input type="number" readonly value="0" id="totalAmt" name="totalAmt" /></td>
                                            </tr>
                                            <tr class="packdiv">
                                                <td colspan="4" style="text-align:Right;">Packing Charge (%) : <input type="number" id="pach" name="pach" oninput="pcharge()" value="" placeholder="0" /></td>
                                                <td colspan="1">Charge (Rs) : </td>
                                                <td colspan="1"><input type="number" readonly value="0" id="fpach" name="fpach" /></td>
                                            </tr>
                                            <tr class="gstdiv">
                                                <td colspan="4" style="text-align:Right;">IGST (%) : <input type="number" id="igstp" name="igstp" oninput="calc_igst()" value="" placeholder="0" /></td>
                                                <td colspan="1">IGST (Rs) : </td>
                                                <td colspan="1"><input type="number" readonly value="0" id="figst" name="figst" /></td>
                                            </tr>
                                            <tr class="gstdiv">
                                                <td colspan="4" style="text-align:Right;">SGST (%) : <input type="number" id="sgstp" name="sgstp" oninput="calc_sgst()" value="" placeholder="0" /></td>
                                                <td colspan="1">SGST (Rs) : </td>
                                                <td colspan="1"><input type="number" readonly value="0" id="fsgst" name="fsgst" /></td>
                                            </tr>
                                            <tr class="totaltr">
                                                <td colspan="4"></td>
                                                <td colspan="1">RoundOff Amount (Rs) : </td>
                                                <td colspan="1"><input type="number" readonly value="0" id="roAmt" name="roAmt" /></td>
                                            </tr>
                                            <tr class="totaltr">
                                                <td colspan="5"><input type="hidden" id="prdlist" name="prdlist" /></td>
                                                <td colspan="1"><button type="submit" class="btn btn-success">Submit</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Hero Section End -->

        <!-- Footer Section Start -->
        <?php include('../utilities/footer.php') ?>
        <!-- Footer Section End -->
    </main>
    <!-- Main Content End -->

    <!-- edit section Start -->
    <section class="modal" id="editProducts">
        <div class="modal-content">
            <form method="post" id="prdbill">
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label class="form-label" for="p_name">Product Name</label>
                        <input type="text" class="form-control" name="p_name" id="p_name" readonly />
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label" for="p_price">Product Price</label>
                        <input type="text" class="form-control" name="p_price" id="p_price" readonly />
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label" for="p_dis">Discount (%)</label>
                        <input type="number" class="form-control" name="p_dis" id="p_dis" oninput="calcprice()" />
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label" for="p_disprice">Discount Price</label>
                        <input type="number" class="form-control" name="p_disprice" id="p_disprice" readonly />
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label" for="p_qty">Quantity</label>
                        <input type="number" class="form-control" name="p_qty" id="p_qty" oninput="calc()" required />
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="form-label" for="p_total">Total</label>
                        <input type="text" class="form-control" name="p_total" id="p_total" readonly />
                    </div>
                </div>
                <input type="submit" class="btn btn-success w-100" name="editData" value="Ok" />
            </form>
            <input id="closeBtn" type='button' class="close btn btn-danger mt-3 " value="Close" />
        </div>
    </section>
    <!-- edit section End -->

    <!-- script - start -->
    <?php include('../utilities/script.php') ?>
    <!-- script - end -->

    <script>
        let list = [];
        itemno = 1;
        nameList = [];
        disper = 80;
        productList = [];
        todayDate = 0;
        $(".totaltr").hide();
        $("#editProducts").hide();
        $(".gstdiv").hide();
        $(".packdiv").hide();

        // set min date
        let today = new Date();
        const dd = String(today.getDate()).padStart(2, '0');
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const yyyy = today.getFullYear();
        todayDate = yyyy + '-' + mm + '-' + dd;
        $("#bdate").attr('min', todayDate);
        $("#bdate").attr('max', (yyyy + 1) + '-' + mm + '-' + dd);

        // date validate start
        const dateValitate = () => {
            let fulldate = $("#bdate").val();
            let year = fulldate.split("-")[0];
            let month = fulldate.split("-")[1];
            let date = fulldate.split("-")[2];
            console.log(fulldate);
            $("#bdate").val((year.replace(/[^0-9]/g, '').substring(0, 4)) + "-" + month + "-" + date);
        }
        // date validate end

        // number validate start
        const valitate = () => {
            const field = $(event.target).attr('id');
            $("#" + field).val($("#" + field).val().replace(/[^0-9]/g, '').substring(0, 13));
        }
        // number validate end

        // id proof
        const idProof = () => {
            if ($("#cidp").val() != "") {
                $("#cidproof").prop('readonly', false);
                $("#cidproof").prop('required', true);
            } else {
                $("#cidproof").prop('readonly', true);
                $("#cidproof").prop('required', false);
            }
        };

        // billing page functions start
        $(window).on('scroll', () => {
            if (($(".listitems").val()) == "") {
                $(".itli").css('display', 'none');
            }
        });
        const addsno = () => {
            $("#show_sno").empty();
            const sno = $("#addItems tr").length;
            if (sno == 0) {
                $(".totaltr").hide();
            };
            let n = 1;
            while (n <= sno) {
                const newsno = $('<tr></tr>').html(`<td class="wnor">${n}</td>`);
                $("#show_sno").append(newsno);
                n++;
            };
        };
        const calc = () => {
            $("#p_qty").val($("#p_qty").val().replace(/[^0-9]/g, '').substring(0, 3));
            $("#p_total").val((($("#p_qty").val()) * ($("#p_disprice").val())).toFixed(2));
        };
        const roamt = () => {
            let total = parseFloat($("#totalAmt").val());
            let igst = parseFloat($("#figst").val());
            let sgst = parseFloat($("#fsgst").val());
            let pamt = parseFloat($("#fpach").val());
            $("#roAmt").val(Math.round(total + igst + sgst + pamt));
        };
        const calc_igst = () => {
            if ($("#addItems tr").length == 0) {
                $(".gstdiv").hide();
                $("#igstp").val("");
                return;
            } else {
                $(".gstdiv").show();
            }
            $("#igstp").val($("#igstp").val().replace(/[^0-9]/g, '').substring(0, 2));
            let gst = $("#igstp").val();
            let total = $("#totalAmt").val();
            let gstamt = total * (gst / 100);
            $("#figst").val(gstamt.toFixed(2));
            roamt();
        };
        const calc_sgst = () => {
            if ($("#addItems tr").length == 0) {
                $(".gstdiv").hide();
                $("#sgstp").val("");
                return;
            } else {
                $(".gstdiv").show();
            }
            $("#sgstp").val($("#sgstp").val().replace(/[^0-9]/g, '').substring(0, 2));
            let gst = $("#sgstp").val();
            let total = $("#totalAmt").val();
            let gstamt = total * (gst / 100);
            $("#fsgst").val(gstamt.toFixed(2));
            roamt();
        };
        const pcharge = () => {
            if ($("#addItems tr").length == 0) {
                $(".packdiv").hide();
                $("#pach").val("");
                return;
            } else if ($("#packing").prop("checked")) {
                $(".packdiv").show();
            } else {
                $(".packdiv").hide();
                $("#pach").val("");
            }
            $("#pach").val($("#pach").val().replace(/[^0-9]/g, '').substring(0, 2));
            let charge = $("#pach").val();
            let total = $("#totalAmt").val();
            let amt = total * (charge / 100);
            $("#fpach").val(amt.toFixed(2));
            roamt();
        };
        const closelist = () => $('.itli').hide();
        $(document).on('click', (e) => {
            if (!$(e.target).closest('.listitems').length) {
                $('.itli').hide();
            }
        });
        const total = () => {
            let totalAmt = 0;
            qty = 0;
            $(".total").each((index, element) => {
                totalAmt += Number($(element).val());
            });
            $(".quantity").each((index, element) => {
                if (($($(".price")[index]).val()) > 0) {
                    qty += Number($(element).val());
                }
            });
            $("#totalAmt").val(totalAmt.toFixed(2));
            $("#totalQty").val(qty);
            calc_igst();
            calc_sgst();
            pcharge();
        };
        const calcQ = () => {
            const itemid = ($(event.target).attr('id')).split("_")[1];
            $("#quantity_" + itemid).val($("#quantity_" + itemid).val().replace(/[^0-9]/g, '').substring(0, 3));
            $("#total_" + itemid).val((($("#quantity_" + itemid).val()) * ($("#dis_price_" + itemid).val())).toFixed(2));
            total();
        };
        const checkprd = () => {
            const itemid = ($(event.target).attr('id')).split("_")[1];
            const pname = $("#listitems_" + itemid).val();
            const item = list.filter(({
                name
            }) => name == pname)[0] || {};
            if ($.isEmptyObject(item)) {
                $("#listitems_" + itemid).val("");
                $("#price_" + itemid).val("");
                $("#discount_" + itemid).val("");
                $("#dis_price_" + itemid).val("");
                $("#quantity_" + itemid).val("");
            }
            calcQ();
        };
        $("#prdbill").on('submit', (e) => {
            const itemid = ($(event.target).attr('class')).split("_")[1];
            e.preventDefault();
            if ($("#p_total").val() == 0) {
                $("#quantity_" + itemid).val("");
                $("#discount_" + itemid).val("");
                $("#dis_price_" + itemid).val("");
                $("#total_" + itemid).val("");
            } else {
                $("#quantity_" + itemid).val($("#p_qty").val());
                $("#discount_" + itemid).val($("#p_dis").val());
                $("#dis_price_" + itemid).val($("#p_disprice").val());
                $("#total_" + itemid).val($("#p_total").val());
            }
            total();
            $("#prdbill").removeClass("item_" + itemid);
            $('#editProducts').hide();
        });
        $("#closeBtn").on('click', () => {
            const itemid = ($(event.target).attr('class')).split("_")[1];
            $("#prdbill").removeClass("item_" + itemid);
            $('#editProducts').hide();
        });
        const calcprice = () => {
            $("#p_dis").val($("#p_dis").val().replace(/[^0-9]/g, '').substring(0, 2));
            const mrp = $("#p_price").val();
            $("#p_disprice").val((mrp - (mrp * ($("#p_dis").val()) / 100)).toFixed(2));
            calc();
        };
        const addvalues = (pid) => {
            const itemid = ($(event.target).attr('id')).split("_")[1];
            const item = list.filter(({
                id
            }) => id == pid)[0] || {};
            $(".itemlist_" + itemid).hide();
            $("#itemid_" + itemid).val(item.id);
            $("#listitems_" + itemid).val(item.name);
            $("#price_" + itemid).val(item.mrp);
            $("#discount_" + itemid).val(disper);
            $("#dis_price_" + itemid).val((item.mrp - (item.mrp * disper / 100)).toFixed(2));
            checkprd();
        };
        const deleteItem = () => {
            const itemid = ($(event.target).data('item-id')).split("_")[1];
            $(".item_" + itemid).remove();
            addsno();
            total();
        };
        const editItem = () => {
            const itemid = ($(event.target).data('item-id')).split("_")[1];
            $("#prdbill").addClass("item_" + itemid);
            $("#p_name").val($("#listitems_" + itemid).val());
            $("#p_price").val($("#price_" + itemid).val());
            $("#p_qty").val($("#quantity_" + itemid).val());
            $("#p_dis").val($("#discount_" + itemid).val());
            $("#p_disprice").val($("#dis_price_" + itemid).val());
            $("#p_total").val($("#total_" + itemid).val());
            $("#editProducts").show();
        };
        const searchitem = () => {
            const itemid = ($(event.target).attr('id')).split("_")[1];
            $(".itemlist_" + itemid).empty();
            let value = $("#listitems_" + itemid).val();
            const results = nameList.filter(item => item.list.toLowerCase().includes(value.toLowerCase()));
            results.map((item) => {
                let li = `<li id="no_${itemid}" class="border-bottom border-1 border-secondary" onclick="addvalues(${item.id})">${item.id} - ${item.name}</li>`;
                $(".itemlist_" + itemid).append(li);
                $(".itemlist_" + itemid).css('display', 'block');
            });
        };
        // billing page functions end

        // calculate discount price start
        const caldis = () => {
            const itemid = ($(event.target).attr('id')).split("_")[1];
            $("#price_" + itemid).val($("#price_" + itemid).val().replace(/[^0-9]/g, '').substring(0, 6));
            $("#discount_" + itemid).val($("#discount_" + itemid).val().replace(/[^0-9]/g, '').substring(0, 2));
            $("#dis_price_" + itemid).val(($("#price_" + itemid).val() - (($("#discount_" + itemid).val() / 100) * ($("#price_" + itemid).val()))).toFixed(2));
            calcQ();
        };
        // calculate discount price end

        // get products start
        $(document).ready(() => {
            $('#addBox').on('click', () => {
                $(".totaltr").show();
                const newItem = $('<tr></tr>').addClass("noitems item_" + itemno).html(
                    `<td class="wbig">
                            <input id="itemid_${itemno}" type="hidden" />
                            <input id="listitems_${itemno}" class="listitems" type="text" placeholder="Enter here ..." />
                        </td>
                        <td class="wnor"><input type="text" id="price_${itemno}" placeholder="0" value="" class="price" oninput="caldis()"/></td>
                        <td class="wnor"><input type="text" id="quantity_${itemno}" class="quantity" placeholder="0" oninput="calcQ()"/></td>
                        <td class="wnor"><input type="text" id="discount_${itemno}" placeholder="0" oninput="caldis()" /></td>
                        <td class="wnor"><input type="text" id="dis_price_${itemno}" placeholder="0" class="cr" readonly /></td>
                        <td><input type="text" id="total_${itemno}" placeholder="0" class="cr b-0 total" readonly /></td>
                        <td>
                            <a class='btn btn-sm btn-icon btn-danger' data-bs-toggle='tooltip' title='Delete' data-bs-placement='top' onclick='deleteItem()' data-item-id='del_${itemno}'>
                                <svg class='icon-20' data-item-id='del_${itemno}' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
                                    <path d='M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    <path d='M20.708 6.23975H3.75' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    <path d='M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                </svg>
                            </a>
                        </td>`
                );
                $("#addItems").append(newItem);
                addsno();
                calc_igst();
                calc_sgst();
                pcharge();
                $.ajax({
                    type: 'POST',
                    url: '<?= $admin_url ?>/backend/billing/',
                    dataType: 'json',
                    data: {
                        req_type: 'getitem'
                    },
                    success: (response) => {
                        // Handle success response
                        list = [...(response.product)];
                        nameList = list.map(({
                            id,
                            name
                        }) => ({
                            id: id,
                            name: name,
                            list: (id + ',' + name)
                        }));
                        itemno++;
                    },
                    error: (xhr, status, error) => {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            });
        });
        // get products end

        // billing start
        $("#billingdata").on("submit", (e) => {
            $("#billingdata [type='submit']").prop('disabled', true).text("Submitting...");
            e.preventDefault();
            const cname = $("#cname").val().trim();
            const mobile = $("#cnumber").val();
            const whatsapp = $("#cwhatsno").val();
            const bdate = $("#bdate").val() == "" ? todayDate : $("#bdate").val();
            if (cname == "") {
                Swal.fire({
                    title: "Name cannot be empty!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $("#billingdata [type='submit']").prop('disabled', false).text("Submit");
                });
                return;
            };
            if (mobile != "" && mobile.length < 10) {
                Swal.fire({
                    title: "Please enter valid Mobile number!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $("#billingdata [type='submit']").prop('disabled', false).text("Submit");
                });
                return;
            };
            if (whatsapp != "" && whatsapp.length < 10) {
                Swal.fire({
                    title: "Please enter valid Whatsapp number!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $("#billingdata [type='submit']").prop('disabled', false).text("Submit");
                });
                return;
            };
            $(".noitems").each((index, element) => {
                const id = ($(element).attr('class').split(' ')[1]).split('_')[1];
                if (($("#price_" + id).val()) != "" && ($("#quantity_" + id).val()) != "") {
                    let prdlist = {
                        "p_id": $("#itemid_" + id).val(),
                        "p_name": $("#listitems_" + id).val(),
                        "p_mrp": $("#price_" + id).val(),
                        "p_qty": $("#quantity_" + id).val(),
                        "p_dis": $("#discount_" + id).val() || 0,
                        "p_disprice": $("#dis_price_" + id).val(),
                        "p_total": $("#total_" + id).val(),
                    };
                    productList.push(prdlist);
                }
            });
            $("#prdlist").val(JSON.stringify(productList));
            if (productList.length == 0) {
                Swal.fire({
                    title: "Please Enter Product Details Correctly!",
                    icon: "warning",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $("#billingdata [type='submit']").prop('disabled', false).text("Submit");
                });
                return;
            };
            let formdata = {
                "name": cname,
                "invoice": "<?= $invoice ?>",
                "mobile": mobile,
                "whatsapp": whatsapp,
                "idproof": $("#cidp").val(),
                "idnumber": $("#cidproof").val(),
                "date": bdate,
                "mop": $("#cmop").val(),
                "address": $("#caddress").val(),
                "prdlist": $("#prdlist").val(),
                "amount": $("#roAmt").val(),
                "gstno": $("#gst").val(),
                "igst": $("#figst").val(),
                "sgst": $("#fsgst").val(),
                "packamt": $("#fpach").val(),
                "req_type": "addinvoice",
            };
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/billing/',
                data: formdata,
                success: (response) => {
                    if (response.trim() == "success") {
                        Swal.fire({
                            title: 'Invoice Created successfully!',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            window.open("<?= $admin_url ?>/pages/invoicepdf?invoice=<?= $invoice ?>", "_blank");
                            window.location.reload(true);
                        })
                    } else {
                        Swal.fire({
                            title: response.trim(),
                            icon: 'error',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            $("#billingdata [type='submit']").prop('disabled', false).text("Submit");
                        });
                    }
                },
                error: (xhr, status, error) => {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
        // billing end
    </script>
</body>

</html>