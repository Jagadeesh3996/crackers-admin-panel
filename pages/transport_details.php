<?php
include('../utilities/session.php');
setlocale(LC_NUMERIC, 'en_IN');

// Fetch data from the database
$query = "SELECT * FROM tbl_transport_details where status='1' order by id DESC";
$result = mysqli_query($conn, $query);
$statelist = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                                    <h1>Transport Details</h1>
                                </div>
                                <div>
                                    <a href="<?= $admin_url ?>/pages/transport/" class="btn btn-success text-white">Transport</a>
                                    <button id="showPopup" class="btn btn-success text-white">+ Add Details</button>
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
                                <table id="datatable" class="table table-striped" data-toggle="data-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
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
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Number</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id='record'>
                                        <?php
                                        $serialNumber = 1;
                                        foreach ($statelist as $state) {
                                            $id = $state['id'];
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" class="checkbox" value=<?= $id ?> /></td>
                                                <td><?= $serialNumber++ ?></td>
                                                <td><?= $state['name'] ?></td>
                                                <td><?= $state['number'] ?></td>
                                                <td>
                                                    <!--<a class='btn btn-sm btn-icon btn-warning' onclick="editItem(<?= $id ?>)" data-bs-toggle='tooltip' title='Edit' data-bs-placement='top'>-->
                                                    <!--    <svg class='icon-20' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>-->
                                                    <!--        <path d='M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>-->
                                                    <!--        <path fill-rule='evenodd' clip-rule='evenodd' d='M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>-->
                                                    <!--        <path d='M15.1655 4.60254L19.7315 9.16854' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>-->
                                                    <!--    </svg>-->
                                                    <!--</a> -->
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

    <!-- Add - popup start-->
    <div id="overlay">
        <div id="popup">
            <h3 class="text-center mb-2">Add Details</h3>
            <form id='addDetails'>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="name">Transport Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="number">Transport Number</label>
                            <input type="number" class="form-control" name="number" id="number" oninput="valitate()" required>
                        </div>
                    </div>
                </div>
                <input type="submit" id="submit" class="btn btn-success w-100" name="addData" value="Add" />
            </form>
            <input id="closePopup" type='button' class="close btn btn-danger w-100 mt-3" value="Close">
        </div>
    </div>
    <!-- Add - popup end-->

    <!-- Edit - popup start-->
    <!--<div id="editoverlay">-->
    <!--    <div id="editpopup">-->
    <!--        <h4 class="text-center mb-2">Edit Details</h4>-->
    <!--        <form id='editDetails'>-->
    <!--            <div class="row">-->
    <!--                <div class="col-12">-->
    <!--                    <div class="form-group">-->
    <!--                        <label class="form-label" for="statename">State Name</label>-->
    <!--                        <input type="text" class="form-control" name="statename" id="edit_statename" required>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-12">-->
    <!--                    <div class="form-group">-->
    <!--                        <label class="form-label" for="movalue">Minimum Order Value</label>-->
    <!--                        <input type="text" class="form-control" name="movalue" id="edit_movalue" required>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-12">-->
    <!--                    <div class="form-group">-->
    <!--                        <label class="form-label" for="mxvalue">Maximum Order Value</label>-->
    <!--                        <input type="text" class="form-control" name="mxvalue" id="edit_mxvalue" required>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-12">-->
    <!--                    <div class="form-group">-->
    <!--                        <label class="form-label" for="pcharge">Packing Charge (%)</label>-->
    <!--                        <input type="text" class="form-control" name="pcharge" id="edit_pcharge" oninput="valitate()" required>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <input type="hidden" name="edit_id" id="edit_id">-->
    <!--            <input type="submit" id="editSubmit" class="btn btn-success w-100" name="editData" value="Update" />-->
    <!--        </form>-->
    <!--        <input id="editclosePopup" class="btn btn-danger w-100 mt-3" value="Close">-->
    <!--    </div>-->
    <!--</div>-->
    <!-- Edit - popup end-->

    <!-- script - start -->
    <?php include('../utilities/script.php') ?>
    <!-- script - end -->

    <script>
        $('#overlay').hide();
        // $('#editoverlay').hide();

        // validations start
        const valitate = () => {
            const field = $(event.target).attr('id');
            $("#" + field).val($("#" + field).val().replace(/[^0-9]/g, '').substring(0, 10));
        }
        // validations end

        // delete items - start
        const delDatas = (id) => {
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/transport/',
                data: {
                    req_type: 'delete_td',
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
        // delete items end

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
                        url: '<?= $admin_url ?>/backend/transport/',
                        data: {
                            req_type: 'delete_td',
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

        $(document).ready(function() {
            // open add popup            
            $('#showPopup').click(() => $('#overlay').show());

            // close add popup
            $('#closePopup').click(() => {
                $('#overlay').hide();
                $("#addDetails")[0].reset();
            });

            // close editpopup
            // $('#editclosePopup').click(()=>{
            //     $('#editoverlay').hide();
            //     $("#editDetails")[0].reset();
            // });

            // add item - start
            $('#addDetails').submit(function(e) {
                $('#submit').prop('disabled', true).val('Adding...');
                e.preventDefault();
                const formData = {
                    'name': $("#name").val().trim(),
                    'number': $("#number").val(),
                    'req_type': 'add_td'
                };
                if ($("#name").val().trim() == "") {
                    Swal.fire({
                        title: 'Name Cannot be Empty!',
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
                        url: '<?= $admin_url ?>/backend/transport/',
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
            // $('#editDetails').submit(function (e) {
            //     $('#editSubmit').prop('disabled', true).val('Updating');
            //     e.preventDefault();
            //     const formData = {
            //         'state_id': $("#edit_id").val(),
            //         'statename': $("#edit_statename").val().trim(),
            //         'movalue': $("#edit_movalue").val(),
            //         'mxvalue': $("#edit_mxvalue").val(),
            //         'pcharge': $("#edit_pcharge").val(),
            //         'req_type': 'edit'
            //     };
            //     if($("#edit_statename").val().trim()==""){
            //         Swal.fire({
            //             title: 'State Cannot be Empty!',
            //             icon: 'error',
            //             customClass: {
            //                 confirmButton: 'my-swal-confirm-button',
            //             },
            //         }).then(() => {
            //             $('#editSubmit').prop('disabled', false).val('Update');
            //         });
            //     }else{
            //         $.ajax({
            //             type: 'POST',
            //             url: 'backend/delivery.php',
            //             data: formData,
            //             success: (res)=>{
            //                 if(res == "Success"){
            //                     Swal.fire({
            //                         title: 'Details Updated Successfully!',
            //                         icon: 'success',
            //                         customClass: {
            //                             confirmButton: 'my-swal-confirm-button',
            //                         },
            //                     }).then((response) => {
            //                         location.reload();
            //                     });
            //                 }else{
            //                     Swal.fire({
            //                         title: res.trim(),
            //                         icon: 'error',
            //                         customClass: {
            //                             confirmButton: 'my-swal-confirm-button',
            //                         },
            //                      }).then(() => {
            //                          $('#editSubmit').prop('disabled', false).val('Update');
            //                      });
            //                 }
            //             },
            //             error: (error)=>{
            //                 console.log(error);
            //             }
            //         });
            //     }
            // });
            // Update item - end
        });

        // fetch - start
        //  const editItem = (id)=>{
        //     $.ajax({
        //         type:'POST',
        //         url: 'backend/delivery.php',
        //         data: {req_type:'fetch', state_id:id},
        //         success: (res)=>{
        //             var result = JSON.parse(res);
        //             if(result.status){
        //                 $('#edit_id').val(result.data.id);
        //                 $('#edit_statename').val(result.data.state);
        //                 $('#edit_movalue').val(result.data.minimum_order_value);                        
        //                 $('#edit_mxvalue').val(result.data.max_order_value);                        
        //                 $('#edit_pcharge').val(result.data.packing_charge); 

        //                 // open editpopup                      
        //                 $('#editoverlay').show();
        //             }else{
        //                 Swal.fire({
        //                     title: res.trim(),
        //                     icon: 'error',
        //                     customClass: {
        //                         confirmButton: 'my-swal-confirm-button',
        //                     },
        //                 });
        //             }
        //         },
        //         error: (error)=>{
        //             console.log(error);
        //         }
        //     });
        // };
        // fetch - end
    </script>
</body>

</html>