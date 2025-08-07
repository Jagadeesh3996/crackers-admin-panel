<?php
include('../utilities/session.php');

// Fetch data from the database
$query = "SELECT * FROM tbl_category WHERE status=1 GROUP BY 
      CAST(SUBSTRING_INDEX(alignment, ' ', 1) AS UNSIGNED), 
      SUBSTRING(alignment, LOCATE(' ', alignment) + 1) ASC";
$result = mysqli_query($conn, $query);
$categorylist = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                                    <h1>Categories</h1>
                                </div>
                                <div>
                                    <button class="btn btn-success text-white mb-1" id="exportData">
                                        <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                            <path fill="#fff" d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V288H216c-13.3 0-24 10.7-24 24s10.7 24 24 24H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zM384 336V288H494.1l-39-39c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l80 80c9.4 9.4 9.4 24.6 0 33.9l-80 80c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l39-39H384zm0-208H256V0L384 128z" />
                                        </svg>
                                        Export
                                    </button>
                                    <button class="btn btn-success text-white mb-1" id="sampleexportData">
                                        <svg width="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                            <path fill="#fff" d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 288c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128z" />
                                        </svg>
                                        Sample
                                    </button>
                                    <button class="btn btn-success text-white mb-1" id="importPopup">
                                        <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path fill="#fff" d="M128 64c0-35.3 28.7-64 64-64H352V128c0 17.7 14.3 32 32 32H512V448c0 35.3-28.7 64-64 64H192c-35.3 0-64-28.7-64-64V336H302.1l-39 39c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l80-80c9.4-9.4 9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l39 39H128V64zm0 224v48H24c-13.3 0-24-10.7-24-24s10.7-24 24-24H128zM512 128H384V0L512 128z" />
                                        </svg>
                                        Import
                                    </button>
                                    <button id="showPopup" class="btn btn-success text-white mb-1">+ Add Category</button>
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
                                <table id="datatable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:7%">
                                                <input type="checkbox" name="checkAll" id="checkAll" class="cr-p" onchange="checkAll()" />
                                                <a class='btn btn-sm btn-icon btn-danger' onclick="delItems()" data-bs-toggle='tooltip' title='Delete' data-bs-placement='top'>
                                                    <svg class='icon-20' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
                                                        <path d='M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                        <path d='M20.708 6.23975H3.75' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                        <path d='M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                    </svg>
                                                </a>
                                            </th>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Category Name</th>
                                            <th class="text-center">Discount (%)</th>
                                            <th class="text-center">Alignment</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id='record'>
                                        <?php
                                        $serialNumber = 1;
                                        foreach ($categorylist as $data) {
                                            $id = $data['id'];
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" class="checkbox" value=<?= $id ?> /></td>
                                                <td><?= $serialNumber++ ?></td>
                                                <td><?= $data['name'] ?></td>
                                                <td><?= $data['discount'] ?> %</td>
                                                <td>
                                                    <?= $data['alignment'] ?>
                                                    <input type="hidden" value="<?= $data['alignment'] ?>" class="alignment" />
                                                </td>
                                                <td>
                                                    <a class='btn btn-sm btn-icon btn-warning' onclick="editItem(<?= $id ?>)" data-bs-toggle='tooltip' title='Edit' data-bs-placement='top'>
                                                        <svg class='icon-20' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                            <path d='M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                            <path fill-rule='evenodd' clip-rule='evenodd' d='M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                                            <path d='M15.1655 4.60254L19.7315 9.16854' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
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

    <!-- import - popup start-->
    <div id="importoverlay">
        <div id="popup">
            <h3 class="text-center mb-2">Import CSV File</h3>
            <form id='importData' enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="csv_file">Upload File</label>
                            <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv" required>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-success w-100" value="Import" />
            </form>
            <input id="importclosePopup" type='button' class="close btn btn-danger w-100 mt-3" value="Close">
        </div>
    </div>
    <!-- import - popup end-->

    <!-- Add - popup start-->
    <div id="overlay">
        <div id="popup">
            <h3 class="text-center mb-2">Add Category</h3>
            <form id='addDetails'>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="name">Category Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="discount">Discount (%)</label>
                            <input type="number" class="form-control" name="discount" id="discount" oninput="valitate()" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="alignment">Alignment</label>
                            <input type="text" class="form-control" name="alignment" id="alignment" oninput="alignValitate()" required>
                        </div>
                    </div>
                </div>
                <input type="submit" id="submit" class="btn btn-success w-100" name="addData" value="Add" readonly />
            </form>
            <input id="closePopup" type='button' class="close btn btn-danger w-100 mt-3" value="Close">
        </div>
    </div>
    <!-- Add - popup end-->

    <!-- Edit - popup start-->
    <div id="editoverlay">
        <div id="editpopup">
            <h4 class="text-center mb-2">Edit Category</h4>
            <form id='editDetails'>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="edit_name">Category</label>
                            <input type="text" class="form-control" name="edit_name" id="edit_name" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="edit_discount">Discount (%)</label>
                            <input type="number" class="form-control" name="edit_discount" id="edit_discount" oninput="valitate()" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="edit_alignment">Alignment</label>
                            <input type="text" class="form-control" name="edit_alignment" id="edit_alignment" oninput="alignValitate()" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="edit_id" id="edit_id">
                <input type="submit" id="editSubmit" class="btn btn-success w-100" name="editData" value="Update" readonly />
            </form>
            <input id="editclosePopup" class="btn btn-danger w-100 mt-3" value="Close">
        </div>
    </div>
    <!-- Edit - popup end-->

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
            const newUrl = `<?= $admin_url ?>/pages/category?p=${currentPage}&l=${currentLength}`;
            history.replaceState(null, '', newUrl);
        });

        $('#overlay').hide();
        $('#editoverlay').hide();
        $('#importoverlay').hide();
        let alignArr = [];
        let delal;

        // store alignment - start
        const store = () => {
            const allRows = table.rows().nodes(); // All DOM rows across pages
            $(allRows).find(".alignment").each((index, element) => {
                let val = $(element).val();
                if (val !== "") {
                    alignArr.push(val);
                }
            });
        };
        // store alignment - end

        // Alignment validations - start
        const alignValitate = () => {
            let val = $(event.target).val().trim();
            let num = parseInt(val); // get only numbers
            let alp = val.match(/[a-zA-Z]+/g); // get only letters
            if (isNaN(num) && val != "") {
                $(event.target).val("");
                Swal.fire({
                    title: 'Please Enter Number!',
                    icon: 'warning',
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                });
                return;
            }
            num = (num.toString()).replace(/[^0-9]/g, '').substring(0, 2);
            if (alp) {
                alp = alp.join('');
                alp = alp.replace(/[^a-zA-Z]/g, '').substring(0, 1);
                $(event.target).val((num + alp).toUpperCase());
            } else {
                $(event.target).val(num);
            }
        };
        // Alignmentvalidations - end

        // Alignment Check - Start
        const alignCheck = (value) => (alignArr.includes(value));
        // Alignment Check - End

        // validations start
        const valitate = () => {
            const field = $(event.target).attr('id');
            $("#" + field).val($("#" + field).val().replace(/[^0-9]/g, '').substring(0, 2));
        };
        // validations end

        // delete items - start
        const delDatas = (id) => {
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/category/',
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
        // delete items - end

        //checkAllboxs - start
        const checkAll = () => {
            (event.target.checked) ? $(".checkbox").prop('checked', true): $(".checkbox").prop('checked', false);
        };
        //checkAllboxs - end

        //deleting many items - start
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
        //deleting many items - end

        // delete item - start
        const deleteItem = (id) => {
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
                        url: '<?= $admin_url ?>/backend/category/',
                        data: {
                            req_type: 'delete',
                            id: id
                        },
                        success: (res) => {
                            if (res == "Success") {
                                Swal.fire({
                                    title: 'Details deleted Successfully!',
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
                                });
                            }
                        },
                        error: (error) => {
                            console.log(error);
                        }
                    });
                }
            });
        };
        // delete item end

        // fetch - start
        const editItem = (id) => {
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/category/',
                data: {
                    req_type: 'fetch',
                    id: id
                },
                success: (res) => {
                    var result = JSON.parse(res);
                    if (result.status) {
                        $('#edit_id').val(result.data.id);
                        $('#edit_name').val(result.data.name);
                        $('#edit_discount').val(result.data.discount);
                        $('#edit_alignment').val(result.data.alignment);
                        delal = result.data.alignment;

                        // open editpopup                      
                        $('#editoverlay').show();
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
        // fetch - end

        $(document).ready(function() {
            // open add popup            
            $('#showPopup').click(() => {
                $("#alignment").val((isNaN(parseInt(alignArr[alignArr.length - 1])) ? 0 : parseInt(alignArr[alignArr.length - 1])) + 1);
                $('#overlay').show();
            });

            // open import popup
            $('#importPopup').click(() => $('#importoverlay').show());

            // close add popup
            $('#closePopup').click(() => {
                $('#overlay').hide();
                $("#addDetails")[0].reset();
            });

            // close editpopup
            $('#editclosePopup').click(() => {
                alignArr.push(delal);
                $('#editoverlay').hide();
                $("#editDetails")[0].reset();
            });

            // close importpopup
            $('#importclosePopup').click(() => {
                $('#importoverlay').hide();
                $('#importData')[0].reset();
            });

            // add item - start
            $('#addDetails').submit(function(e) {
                $('#submit').prop('disabled', true).val('Adding...');
                e.preventDefault();
                let alignment = $("#alignment").val().trim();
                const aCheck = alignCheck(alignment);
                const formData = {
                    'name': $("#name").val().trim(),
                    'discount': $("#discount").val(),
                    'alignment': alignment,
                    'req_type': 'add'
                };
                if (aCheck) {
                    Swal.fire({
                        title: 'Alignment Already Exists!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $('#submit').prop('disabled', false).val('Add');
                    });
                } else if ($("#name").val().trim() == "") {
                    Swal.fire({
                        title: 'Field Cannot be Empty!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $('#submit').prop('disabled', false).val('Add');
                    });
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '<?= $admin_url ?>/backend/category/',
                        data: formData,
                        success: (res) => {
                            if (res == "Success") {
                                Swal.fire({
                                    title: 'Details Uploaded Successfully!',
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
                                    $('#submit').prop('disabled', false).val('Add');
                                });
                            }
                        },
                        error: (error) => {
                            console.log(error);
                        }
                    });
                }
            });
            // add item - end

            // Update item - start
            $('#editDetails').submit(function(e) {
                $('#editSubmit').prop('disabled', true).val('Updating...');
                e.preventDefault();
                alignArr = alignArr.filter(item => item !== delal);
                let alignment = $("#edit_alignment").val().trim();
                const aCheck = alignCheck(alignment);
                const formData = {
                    'id': $("#edit_id").val(),
                    'name': $("#edit_name").val().trim(),
                    'discount': $("#edit_discount").val(),
                    'alignment': alignment,
                    'req_type': 'edit'
                };
                if (aCheck) {
                    Swal.fire({
                        title: 'Alignment Already Exists!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $('#editSubmit').prop('disabled', false).val('Update');
                    });
                } else if ($("#edit_name").val().trim() == "") {
                    Swal.fire({
                        title: 'Field Cannot be Empty!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $('#editSubmit').prop('disabled', false).val('Update');
                    });
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '<?= $admin_url ?>/backend/category/',
                        data: formData,
                        success: (res) => {
                            if (res == "Success") {
                                Swal.fire({
                                    title: 'Details Updated Successfully!',
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
                                    $('#editSubmit').prop('disabled', false).val('Update');
                                });
                            }
                        },
                        error: (error) => {
                            console.log(error);
                        }
                    });
                }
            });
            // Update item - end

            // ExportData - start
            $("#exportData").on("click", () => {
                Swal.fire({
                    title: 'Do You want Export the Data!',
                    icon: 'info',
                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Export"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?= $admin_url ?>/backend/export-data?export=category";
                    }
                });
            });
            // ExportData - end

            // Sample ExportData - start
            $("#sampleexportData").on("click", () => {
                Swal.fire({
                    title: 'Do You want Export the Sample file!',
                    icon: 'info',
                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Export"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?= $admin_url ?>/backend/sample-export?type=category";
                    }
                });
            });
            // Sample ExportData - end

            // Import file - start
            $('#importData').submit(function(e) {
                e.preventDefault();

                // Show loader alert
                Swal.fire({
                    title: 'Importing file...',
                    text: 'Please wait while the file is being processed.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading(); // Show the loader
                    }
                });

                const formData = new FormData();
                formData.append("csv_file", $('#csv_file')[0].files[0]);
                formData.append("req_type", "import");
                $.ajax({
                    type: 'POST',
                    url: '<?= $admin_url ?>/backend/category/',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (res) => {
                        if (res == "Success") {
                            Swal.close(); // Close the loader
                            Swal.fire({
                                title: 'File Imported Successfully!',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'my-swal-confirm-button',
                                },
                            }).then((response) => {
                                location.reload();
                            });
                        } else {
                            Swal.close(); // Close the loader
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
            });
            // Import file - end
        });
        store();
    </script>
</body>

</html>