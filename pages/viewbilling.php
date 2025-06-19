<?php
include('../utilities/session.php');

// Fetch data from the database
$query = "SELECT * FROM tbl_billing WHERE status >= 1 ORDER BY date DESC, id DESC";
$result = mysqli_query($conn, $query);
$billItems = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                                    <h1>BILL LIST</h1>
                                </div>
                                <div>
                                    <a href="<?= $admin_url ?>/pages/billing/"><button type="button" class="btn btn-success">
                                            <svg class='icon-20' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path fill="#ffffff" d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                                            </svg>&nbsp;
                                            Billing
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
            <!-- Header End -->
        </div>

        <!-- Hero Section Start -->
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped" data-toggle="data-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" name="checkAll" id="checkAll" class="cr-p" onchange="checkAll()">
                                                <a class='btn btn-sm btn-icon btn-danger' onclick="delItems()" data-bs-toggle='tooltip' title='Delete' data-bs-placement='top'>
                                                    <svg class='icon-20' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
                                                        <path d='M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                        <path d='M20.708 6.23975H3.75' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                        <path d='M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                    </svg>
                                                </a>
                                            </th>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Billing No</th>
                                            <th class="text-center">Customer Name</th>
                                            <th class="text-center">Customer Number</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id='record'>
                                        <?php
                                        $serialNumber = 1;
                                        foreach ($billItems as $bitem) {
                                            $id = $bitem['id'];
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" class="checkbox" value=<?= $id ?> /></td>
                                                <td><?= $serialNumber++ ?></td>
                                                <td><?= $bitem['date'] ?></td>
                                                <td><?= $bitem['bid'] ?></td>
                                                <td><?= $bitem['name'] ?></td>
                                                <td><?= $bitem['mobile'] ?></td>
                                                <td>&#8377; <?= number_format($bitem['amount']) ?></td>
                                                <td>
                                                    <select class='status_<?= $id ?> p-1 text-white rounded-3' onchange="statuschg(<?= $id ?>)"
                                                        <?php
                                                        $bgColor = ($bitem['status'] == 1) ? '#fd7e14' : (($bitem['status'] == 2) ? '#198754' : '#dc3545');
                                                        echo "style='background-color:$bgColor'";
                                                        ?>>
                                                        <option value='1' <?= ($bitem['status'] == 1) ? 'selected' : ''; ?>>Pending</option>
                                                        <option value='2' <?= ($bitem['status'] == 2) ? 'selected' : ''; ?>>Completed</option>
                                                        <option value='3' <?= ($bitem['status'] == 3) ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <a class='btn btn-sm btn-icon btn-success' href='<?= $admin_url ?>/pages/billingpdf.php?bid=<?= $bitem['bid'] ?>' target='_blank' data-bs-toggle='tooltip' title='View' data-bs-placement='top'>
                                                        <svg class='icon-20' width='20' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512' stroke='currentColor'>
                                                            <path fill='#fff' d='M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z' />
                                                        </svg>
                                                    </a>
                                                    <a class='btn btn-sm btn-icon btn-danger' onclick="deleteItem(<?= $id ?>)" data-bs-toggle='tooltip' title='Delete' data-bs-placement='top'>
                                                        <svg class='icon-20' data-item-id='<?= $id ?>' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
                                                            <path d='M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                            <path d='M20.708 6.23975H3.75' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                            <path d='M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
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

    <!-- script - start -->
    <?php include('../utilities/script.php') ?>
    <!-- script - end -->

    <script>
        // delete datas - start
        const delDatas = (id) => {
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/billing/',
                data: {
                    req_type: 'delete',
                    id: id
                },
                success: (response) => {
                    console.log(response.trim());
                },
                error: (error) => {
                    console.log(error);
                }
            });
        };
        // delete datas end

        //checkAllboxs - start
        const checkAll = () => {
            (event.target.checked) ? $(".checkbox").prop('checked', true): $(".checkbox").prop('checked', false);
        };
        //checkAllboxs - end

        //deleting many datas - start
        const delItems = () => {
            const checkedDatas = $(".checkbox:checked");
            if (checkedDatas.length <= 0) {
                Swal.fire({
                    title: "No Datas Selected!",
                    icon: "error",
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                });
                return;
            }
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: 'my-swal-confirm-button',
                },
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    checkedDatas.each((index, element) => {
                        delDatas($(element).val());
                    });
                    return true;
                }
            }).then((result) => {
                if (result) {
                    Swal.fire({
                        title: "Datas Deleted Successfully",
                        icon: "success",
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        window.location.reload(true);
                    });
                } else {
                    Swal.fire({
                        title: "Error in Deleting Datas!",
                        icon: "error",
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    })
                }
            });
        };
        //deleting many datas - end

        // status change - start
        const statuschg = (id) => {
            $.ajax({
                type: "POST",
                url: '<?= $admin_url ?>/backend/billing/',
                data: {
                    'status': $(".status_" + id).val(),
                    'id': id,
                    'req_type': 'status',
                },
                success: (response) => {
                    if (response.trim() === "success") {
                        Swal.fire({
                            title: "Status Updated successfully",
                            icon: "success",
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        console.log(response.trim());
                    }
                },
                error: (error) => {
                    console.log(error);
                }
            });
        };
        //status change -end

        // delete bill - start
        const deleteItem = (id) => {
            const itemId = $(event.target).data('item-id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: 'my-swal-confirm-button',
                },
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '<?= $admin_url ?>/backend/billing/',
                        data: {
                            req_type: 'delete',
                            id: itemId
                        },
                        success: (response) => {
                            if (response.trim() === "success") {
                                Swal.fire({
                                    title: "Bill deleted successfully!",
                                    icon: "success",
                                    customClass: {
                                        confirmButton: 'my-swal-confirm-button',
                                    },
                                }).then(() => {
                                    window.location.reload(true);
                                });
                            } else {
                                console.log(response.trim());
                            }
                        },
                        error: (error) => {
                            console.log(error);
                        }
                    });
                }
            });
        };
        // delete bill end
    </script>
</body>

</html>