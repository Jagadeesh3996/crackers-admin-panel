<?php
include('./utilities/session.php');

// Fetch data from the database
$query = "SELECT p.*, c.discount FROM tbl_product p JOIN tbl_category c ON p.category = c.name WHERE p.status >= '1' and c.status = '1' GROUP BY CAST(SUBSTRING_INDEX(p.alignment, ' ', 1) AS UNSIGNED), SUBSTRING(p.alignment, LOCATE(' ', p.alignment) + 1) ASC";

$result = mysqli_query($conn, $query);
$totalProduct = mysqli_num_rows($result);

// Fetch data from the database
$query2 = "SELECT * FROM tbl_orders GROUP BY order_id";
$result2 = mysqli_query($conn, $query2);
$totalEstimate = mysqli_num_rows($result2);

// Fetch data from the database
$todayDate = date("Y-m-d");
$query3 = "SELECT * FROM tbl_orders WHERE date='$todayDate' GROUP BY order_id";
$result3 = mysqli_query($conn, $query3);
$todayOrder = mysqli_num_rows($result3);

// Fetch data from the database
$query4 = "SELECT * FROM tbl_billing GROUP BY bid";
$result4 = mysqli_query($conn, $query4);
$totalBilling = mysqli_num_rows($result4);

// Fetch data from the database
$query5 = "SELECT * FROM tbl_orders WHERE status >= 1 ORDER BY date DESC, id DESC LIMIT 10";
$result5 = mysqli_query($conn, $query5);
$orderItems = mysqli_fetch_all($result5, MYSQLI_ASSOC);
?>

<!Doctype html>
<html lang="en" dir="ltr">

<!-- head Start -->
<?php include('./utilities/head.php'); ?>
<style>
    .dataTables_length,
    .dataTables_filter,
    .dataTables_info,
    .dataTables_paginate {
        display: none !important;
    }
</style>
<!-- head END -->

<body>
    <!-- loader Start -->
    <?php include('./utilities/loader.php'); ?>
    <!-- loader END -->

    <!-- sidenav Start -->
    <?php include('./utilities/side_nav.php'); ?>
    <!-- sidenav END -->

    <!-- Main Content Start -->
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--topnav Start-->
            <?php include('./utilities/top_nav.php') ?>
            <!--topnav End-->

            <!-- header Start -->
            <?php include('./utilities/header.php') ?>
            <!-- header End -->
        </div>

        <!-- hero section start -->
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">

                <div class="col-12">
                    <div class="row row-cols-1">
                        <div class="overflow-hidden d-slider1 ">
                            <ul class="p-0 m-0 mb-2 swiper-wrapper list-inline">
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <div class="progress-widget">
                                            <svg fill="none" width="50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                <path class="dasicon" d="M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0H109.6C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9l-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-12.4 0-24.3-1.9-35.4-5.3V384H128V250.6c-11.2 3.5-23.2 5.4-35.6 5.4c-5.5 0-11-.4-16.3-1.1l-.1 0c-4.1-.6-8.1-1.3-12-2.3V384v64c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V384 252.6c-4 1-8 1.8-12.3 2.3z" />
                                            </svg>
                                            <div class="progress-detail">
                                                <h5 class="mb-2">Total Product</h5>
                                                <h4 class="counter"><?= $totalProduct ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="800">
                                    <div class="card-body">
                                        <div class="progress-widget">
                                            <svg width="40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                                <path class="dasicon" d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
                                            </svg>
                                            <div class="progress-detail">
                                                <h5 class="mb-2">Total Estimate</h5>
                                                <h4 class="counter"><?= $totalEstimate ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="900">
                                    <div class="card-body">
                                        <div class="progress-widget">
                                            <svg width="40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                <path class="dasicon" d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                                            </svg>
                                            <div class="progress-detail">
                                                <h5 class="mb-2">Today Esitmate</h5>
                                                <h4 class="counter"><?= $todayOrder ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1000">
                                    <div class="card-body">
                                        <div class="progress-widget">
                                            <svg width="40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="none">
                                                <path class="dasicon" d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                                            </svg>
                                            <div class="progress-detail">
                                                <h5 class="mb-2">Total Billing</h5>
                                                <h4 class="counter"><?= $totalBilling ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <!-- <div class="swiper-button swiper-button-next"></div>
                                <div class="swiper-button swiper-button-prev"></div> -->
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card" data-aos="fade-up" data-aos-delay="800">
                                <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
                                    <div class="header-title d-flex">
                                        <div>
                                            <input type="checkbox" name="sales" id="sales" class="salesbox" oninput="schange()" />
                                            <label for="sales" class="salesbtn pt-1 text-white"></label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="order" id="order" class="orderbox" oninput="ochange()" />
                                            <label for="order" class="orderbtn pt-1 text-white">Order</label>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <select name="period" id="period" class="cr-p p-1 text-light rounded-3 bg-color" onchange="period()">
                                            <option value="All">All Time</option>
                                            <option value="7">Last 7 days</option>
                                            <option value="30">Last 30 days</option>
                                            <option value="365">Last 365 days</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="lineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <h4 class="ms-3">Last 10 Orders</h4>
                                        <table id="datatable" class="table table-striped" data-toggle="data-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Order No</th>
                                                    <th class="text-center">Customer Name</th>
                                                    <th class="text-center">Customer Number</th>
                                                    <th class="text-center">Customer Address</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id='record'>
                                                <?php
                                                $serialNumber = 1;
                                                foreach ($orderItems as $oitem) {
                                                    $id = $oitem['id'];
                                                ?>
                                                    <tr>
                                                        <td><?= $serialNumber++ ?></td>
                                                        <td><?= $oitem['date'] ?></td>
                                                        <td><?= $oitem['order_id'] ?></td>
                                                        <td><?= $oitem['name'] ?></td>
                                                        <td><?= $oitem['phone'] ?></td>
                                                        <td style="white-space: break-spaces;"><?= $oitem['address'] ?></td>
                                                        <td>&#8377; <?= number_format($oitem['final_total']) ?></td>
                                                        <td>
                                                            <select class='status_<?= $id ?> p-1 text-white rounded-3' onchange="statuschg(<?= $id ?>)"
                                                                <?php
                                                                $bgColor = ($oitem['status'] == 1) ? '#fd7e14' : (($oitem['status'] == 2) ? '#198754' : (($oitem['status'] == 3) ? '#dc3545' : '#6165e8'));
                                                                echo "style='background-color:$bgColor'";
                                                                ?>>
                                                                <option value=1 <?= ($oitem['status'] == 1) ? 'selected' : ''; ?>>Order Received</option>
                                                                <option value=2 <?= ($oitem['status'] == 2) ? 'selected' : ''; ?>>AMT Pending</option>
                                                                <option value=3 <?= ($oitem['status'] == 3) ? 'selected' : ''; ?>>Amt Received</option>
                                                                <option value=4 <?= ($oitem['status'] == 4) ? 'selected' : ''; ?>>Packing</option>
                                                                <option value=5 <?= ($oitem['status'] == 5) ? 'selected' : ''; ?>>Deliverd</option>
                                                                <option value=6 <?= ($oitem['status'] == 6) ? 'selected' : ''; ?>>Cancelled</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <a class='btn btn-sm btn-icon btn-success' href='<?= $admin_url ?>/pages/orderpdf?oid=<?= $oitem['order_id'] ?>' target='_blank' data-bs-toggle='tooltip' title='View' data-bs-placement='top'>
                                                                <svg class='icon-20' width='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512' stroke='currentColor'>
                                                                    <path fill='#fff' d='M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z' />
                                                                </svg>
                                                            </a>
                                                            <!-- <a class='btn btn-sm btn-icon btn-danger' onclick="deleteItem(<?= $id ?>)" data-bs-toggle='tooltip' title='Delete' data-bs-placement='top'>
                                                                <svg class='icon-20' data-item-id='<?= $id ?>' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
                                                                    <path d='M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                                    <path d='M20.708 6.23975H3.75' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                                    <path d='M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                                </svg>
                                                            </a> -->
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- hero section end -->

        <!-- Footer Section Start -->
        <?php include('./utilities/footer.php') ?>
        <!-- Footer Section End -->
    </main>
    <!-- Main Content End -->

    <!-- script Start -->
    <?php include('./utilities/script.php') ?>
    <!-- script End -->

    <script>
        let pi = 0;
        salesList = [];
        ordersList = [];

        // $('#datatable').DataTable({
        //     paging: false, // 🔸 Hide pagination
        //     searching: false, // 🔸 Hide search box
        //     info: false, // 🔸 Hide "Showing x of y entries"
        //     lengthChange: false, // 🔸 Hide entries per page dropdown
        //     // ordering: false // 🔸 Disable column sorting (optional)
        // });

        const chart = (listX, value, label) => {
            const maxValue = Math.max(...value);
            if (pi) {
                pic.destroy();
            }
            let color = (label == "Sales") ? "#008000" : "#ffc107";
            const data = {
                labels: [...listX],
                datasets: [{
                    label: label,
                    data: [...value],
                    fill: true,
                    borderColor: color,
                    tension: 0.1
                }],
            };
            const config = {
                type: 'line',
                data: data,
                options: {
                    scales: {
                        y: {
                            suggestedMin: 0, // Minimum value
                            suggestedMax: (maxValue + 2), // Maximum value
                        }
                    }
                },
            };
            pic = new Chart($("#lineChart"), config);
            pi++;
        };
        const schange = () => {
            if ($("#sales").prop("checked")) {
                $("#sales").prop("checked", true);
                $("#order").prop("checked", false);
                period();
            } else {
                $("#sales").prop("checked", false);
                $("#order").prop("checked", true);
                period();
            }
        };
        const ochange = () => {
            if ($("#order").prop("checked")) {
                $("#sales").prop("checked", false);
                $("#order").prop("checked", true);
                period();
            } else {
                $("#sales").prop("checked", true);
                $("#order").prop("checked", false);
                period();
            }
        };
        $(document).ready(function() {
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/chart/',
                data: {
                    req_type: 'getSales'
                },
                success: (res) => {
                    const result = JSON.parse(res);
                    if (result.status) {
                        salesList = [...result.data];
                        period();
                    } else {
                        Swal.fire({
                            title: res.trim(),
                            icon: 'error',
                        });
                    }
                },
                error: (error) => {
                    console.log(error);
                }
            });
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/chart/',
                data: {
                    req_type: 'getOrders'
                },
                success: (res) => {
                    const result = JSON.parse(res);
                    if (result.status) {
                        ordersList = [...result.data];
                        period();
                    } else {
                        Swal.fire({
                            title: res.trim(),
                            icon: 'error',
                        });
                    }
                },
                error: (error) => {
                    console.log(error);
                }
            });
            $("#sales").prop("checked", true);
        });
        const filterDataByDateRange = (data, startDate, endDate) => {
            return data.filter(item => {
                const ndate = new Date(item.date);
                return ndate >= startDate && ndate <= endDate;
            });
        };
        const period = () => {
            const time = $("#period").val();
            let today = new Date();
            let endDate = new Date(today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0'));
            let startDate;
            if (time == "All") {
                let dates = ($("#sales").prop("checked")) ? salesList.map(item => item.date) : ordersList.map(item => item.date);
                const dateObjects = dates.map(dateString => new Date(dateString));
                startDate = new Date(Math.min(...dateObjects));
            } else {
                today.setDate(today.getDate() - time);
                startDate = new Date(today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0'));
            }
            let filterData = ($("#sales").prop("checked")) ? filterDataByDateRange(salesList, startDate, endDate) : filterDataByDateRange(ordersList, startDate, endDate);
            let listX = filterData.map(item => item.date);
            let sales = filterData.map(item => item.value);
            ($("#sales").prop("checked")) ? chart(listX, sales, "Sales"): chart(listX, sales, "Orders");
        };
    </script>
</body>

</html>