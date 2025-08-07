<?php
include('../utilities/session.php');

// Fetch data from the database
$query = "SELECT * FROM tbl_orders where status = 5 order by date DESC, id DESC";
$result = mysqli_query($conn, $query);
$orderItems = mysqli_fetch_all($result, MYSQLI_ASSOC);
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

            <!-- Header Start -->
            <div class="iq-navbar-header bg-color" style="height: 215px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center">
                                <div>
                                    <h1>Transport</h1>
                                </div>
                                <div>
                                    <a href="<?= $admin_url ?>/pages/transport_details/" class="btn btn-success text-white">Transport Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="iq-header-img">
                        <img src="../assets/images/banner.png" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
                    </div> -->
            </div>
            <!-- Header End -->
        </div>

        <!-- Hero Section Start -->
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Order No</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Number</th>
                                            <th class="text-center">Whatsapp</th>
                                            <th class="text-center">Customer Address</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Transport</th>
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
                                                <td><?= $oitem['whatsapp'] ?></td>
                                                <td style="white-space: break-spaces;"><?= $oitem['address'] ?></td>
                                                <td>&#8377; <?= number_format($oitem['final_total']) ?></td>
                                                <td>
                                                    <button class="btn btn-success" onclick="openPopUp(<?= $id ?>, <?= $oitem['phone']; ?>, '<?= $oitem['email']; ?>')">Send</button>
                                                </td>
                                                <td>
                                                    <a class='btn btn-sm btn-icon btn-success' href='<?= $admin_url ?>/pages/orderpdf?oid=<?= $oitem['order_id'] ?>' target='_blank' data-bs-toggle='tooltip' title='View' data-bs-placement='top'>
                                                        <svg class='icon-20' width='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512' stroke='currentColor'>
                                                            <path fill='#fff' d='M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z' />
                                                        </svg>
                                                    </a>
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
        <!-- Hero Section End -->

        <!-- Footer Section Start -->
        <?php include('../utilities/footer.php') ?>
        <!-- Footer Section End -->
    </main>
    <!-- Main Content End -->

    <!-- Add - popup start-->
    <div id="overlay">
        <div id="popup">
            <h3 class="text-center mb-2">Transport Details</h3>
            <form id='addDetails'>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="transport">Transport Name</label>
                            <select name="transport" id="transport" class="form-control" onchange="getDetails()" required>
                                <option value="" hidden>Select Name</option>
                                <?php
                                // Fetch categories from the database
                                $query2 = "SELECT * FROM tbl_transport_details WHERE status='1'";
                                $result2 = mysqli_query($conn, $query2);
                                if ($result2) {
                                    // Loop through categories and populate the dropdown
                                    while ($row2 = mysqli_fetch_assoc($result2)) {
                                        echo "<option value='{$row2['id']}'>{$row2['name']}</option>";
                                    }
                                } else {
                                    echo "<option value=''>Error fetching categories</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="mobile">Transport Number</label>
                            <input type="number" class="form-control" name="mobile" id="mobile" readonly required />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="lrno">LR No</label>
                            <input type="text" class="form-control" name="lrno" id="lrno" required />
                        </div>
                    </div>
                </div>
                <input type="hidden" name="cid" id="cid" value="" />
                <input type="hidden" name="cphone" id="cphone" value="" />
                <input type="hidden" name="cemail" id="cemail" value="" />
                <input type="submit" id="submit" class="btn btn-success w-100" name="addData" value="Send" />
            </form>
            <input id="closePopup" type='button' class="close btn btn-danger w-100 mt-3" value="Close" />
        </div>
    </div>
    <!-- Add - popup end-->

    <!-- script - start -->
    <?php include('../utilities/script.php') ?>
    <!-- script - end -->

    <script>
        // get the params
        const urlParams = new URLSearchParams(window.location.search);
        const pageParam = parseInt(urlParams.get("p")) || 1;
        const lengthParam = parseInt(urlParams.get("l")) || 10;

        // table Initialize
        const table = $('#datatable').DataTable();

        // set number of items per page
        table.page.len(lengthParam).draw();

        // Set page number (after setting length!)
        table.page(pageParam - 1).draw('page');

        // When page number changes, update URL
        table.on('page.dt length.dt', function() {
            const info = table.page.info();
            const currentPage = info.page + 1;
            const currentLength = table.page.len();
            const newUrl = `<?= $admin_url ?>/pages/transport?p=${currentPage}&l=${currentLength}`;
            history.replaceState(null, '', newUrl);
        });

        $('#overlay').hide();

        // close popup
        $('#closePopup').click(() => {
            $('#overlay').hide();
            $("#addDetails")[0].reset();
        });

        // open popup
        const openPopUp = (id, phone, email) => {
            $("#cid").val(id);
            $("#cphone").val(phone);
            $("#cemail").val(email);
            $('#overlay').show();
        };

        // number validate start
        const numvalitate = () => $(event.target).val($(event.target).val().replace(/[^0-9]/g, '').substring(0, 10));
        // number validate end

        const getDetails = () => {
            let id = $("#transport").val();
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/transport/',
                data: {
                    req_type: 'get_td',
                    id: id
                },
                success: (res) => {
                    let result = JSON.parse(res);
                    if (result.status) {
                        $('#mobile').val(result.data.number);
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
                error: (error) => {
                    console.log(error);
                }
            });
        };

        // add - start
        $('#addDetails').submit(function(e) {
            $('#submit').prop('disabled', true).val('Sending...');
            e.preventDefault();
            let transport = $("#transport").val().trim();
            let lrno = $("#lrno").val().trim();
            let mobile = $("#mobile").val();
            const formData = {
                'transport': transport,
                'lrno': lrno,
                'mobile': mobile,
                'email': $("#cemail").val().trim(),
                'number': $("#cphone").val().trim(),
                'sender_mail': '<?= $site_email ?>',
                'req_type': 'send'
            };
            if (lrno == "") {
                Swal.fire({
                    title: 'Field Cannot be Empty!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                }).then(() => {
                    $('#submit').prop('disabled', false).val('Send');
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: '<?= $admin_url ?>/backend/transport/',
                    data: formData,
                    success: (res) => {
                        if (res == "Success") {
                            Swal.fire({
                                title: 'Transport Details Send Successfully!',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'my-swal-confirm-button',
                                },
                            }).then((response) => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: res.trim(),
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'my-swal-confirm-button',
                                },
                            }).then(() => {
                                $('#submit').prop('disabled', false).val('Send');
                            });
                        }
                    },
                    error: (error) => {
                        console.log(error);
                    }
                });
            }
        });
        // add - end
    </script>
</body>

</html>