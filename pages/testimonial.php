<?php
include('../utilities/session.php');

// Fetch data from the database
$query = "SELECT * FROM tbl_testimonial WHERE status = 1 ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$testimonial = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en" dir="ltr">

<!-- head Start -->
<?php include('../utilities/head.php'); ?>
<!-- head END -->

<body>
    <!-- loader Start -->
    <?php include('../utilities/loader.php'); ?>
    <!-- loader END -->

    <!-- sidenav Start -->
    <?php include('../utilities/side_nav.php'); ?>
    <!-- sidenav END -->

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
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h1>Testimonials<span class="text-uppercase"></h1>
                                </div>
                                <div>
                                    <button id="showPopup" class="btn btn-success text-white">+ Add Testimonial</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header End -->
        </div>

        <!-- hero section start -->
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($testimonial as $data): ?>
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="card p-3" style="background: #E6F5F6;border:none">
                                            <h6 class="text-center mb-2"><?= $data['name']; ?></h6>
                                            <img class="img-fluid rounded-circle m-auto" style="width: 150px;height:150px;padding:10px" src="<?= $admin_url ?>/uploads/testimonials/<?= $data['image'] ?>" alt="profile-image" />
                                            <p class="text-center mb-0">“ <?= $data['review']; ?> ”</p>
                                            <a style="position:absolute;left:0;top:0" class='width-fit-content btn btn-sm btn-icon btn-warning' onclick="editItem(<?= $data['id']; ?>)" data-bs-toggle='tooltip' data-bs-placement='top' title='Edit'>
                                                <span class='btn-inner'>
                                                    <svg class='icon-20' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                                        <path
                                                            d='M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341'
                                                            stroke='currentColor' stroke-width='1.5' stroke-linecap='round'
                                                            stroke-linejoin='round'></path>
                                                        <path fill-rule='evenodd' clip-rule='evenodd'
                                                            d='M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z'
                                                            stroke='currentColor' stroke-width='1.5' stroke-linecap='round'
                                                            stroke-linejoin='round'></path>
                                                        <path d='M15.1655 4.60254L19.7315 9.16854' stroke='currentColor'
                                                            stroke-width='1.5' stroke-linecap='round'
                                                            stroke-linejoin='round'></path>
                                                    </svg>
                                                </span>
                                            </a>
                                            <a style="position:absolute;right:0;top:0" class='width-fit-content btn btn-sm btn-icon btn-danger' onclick="delItem(<?= $data['id']; ?>, '<?= $data['image'] ?>')" data-bs-toggle='tooltip' data-bs-placement='top' title='Delete'>
                                                <span class='btn-inner'>
                                                    <svg class='icon-20' width='20' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
                                                        <path
                                                            d='M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826'
                                                            stroke='currentColor' stroke-width='1.5' stroke-linecap='round'
                                                            stroke-linejoin='round'></path>
                                                        <path d='M20.708 6.23975H3.75' stroke='currentColor'
                                                            stroke-width='1.5' stroke-linecap='round'
                                                            stroke-linejoin='round'></path>
                                                        <path
                                                            d='M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973'
                                                            stroke='currentColor' stroke-width='1.5' stroke-linecap='round'
                                                            stroke-linejoin='round'></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- hero section end -->

        <!-- Footer Section Start -->
        <?php include('../utilities/footer.php') ?>
        <!-- Footer Section End -->
    </main>
    <!-- Main Content End -->

    <!-- Add - popup start-->
    <div id="overlay">
        <div id="popup">
            <h3 class="text-center mb-2">Add Testimonial</h3>
            <form id="addDetails" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="review">Review</label>
                            <textarea name="review" class="form-control" id="review" cols="30" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="image">Client Profile</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*" required onchange="imagePreview('imagePreview')">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div id="imagePreview"></div>
                    </div>
                </div>
                <input type="submit" id="submit" class="btn btn-success w-100" name="addData" value="Add" />
            </form>
            <input id="closePopup" type="button" class="close btn btn-danger mt-3 w-100" value="Close" />
        </div>
    </div>
    <!-- Add - popup end-->

    <!-- Edit - popup start-->
    <div id="editoverlay">
        <div id="editpopup">
            <h3 class="text-center mb-2">Edit Testimonial</h3>
            <form id='editDetails' enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_name">Name</label>
                            <input type="text" class="form-control" name="edit_name" id="edit_name" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label class="form-label" for="edit_review">Review</label>
                            <textarea name="edit_review" class="form-control" id="edit_review" cols="30" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_image">Client Profile</label>
                            <input type="file" class="form-control" name="edit_image" id="edit_image" accept="image/*" onchange="imagePreview('edit_imagePreview')" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div id="edit_imagePreview"></div>
                    </div>
                </div>
                <input type="hidden" name="edit_old_image" id="edit_old_image" />
                <input type="hidden" name="edit_id" id="edit_id" />
                <input type="submit" id="editSubmit" class="btn btn-success w-100" name="editData" value="Update" />
            </form>
            <input id="editclosePopup" type="button" class="close btn btn-danger mt-3 w-100" value="Close" />
        </div>
    </div>
    <!-- Edit - popup end-->

    <!-- script Start -->
    <?php include('../utilities/script.php') ?>
    <!-- script End -->

    <script>
        $('#overlay').hide();
        $('#editoverlay').hide();

        // previewImage
        const imagePreview = (previewId) => {
            $("#" + previewId).empty(); // Clear previous previews
            const file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => $("#" + previewId).append(`<img src="${e.target.result}" style="max-width: 100%; max-height: 200px; margin: 5px;">`);
            reader.readAsDataURL(file);
        };

        // ajax call
        const sendFormData = (formData, id) => {
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/testimonial/',
                data: formData,
                contentType: false,
                processData: false,
                success: (response) => {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status) {
                        Swal.fire({
                            title: response.message,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then((response) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: response.message,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            switch (id) {
                                case 1:
                                    $("#submit").prop('disabled', false).val("Add");
                                    break;

                                case 2:
                                    $('#editSubmit').prop('disabled', false).val('Update');
                                    break;
                            };
                        });
                    }
                },
                error: (error) => {
                    console.log(error);
                }
            });
        };

        $(document).ready(function() {
            // open add popup            
            $('#showPopup').click(() => $('#overlay').show());

            // close add popup
            $('#closePopup').click(() => {
                $('#overlay').hide();
                $("#addDetails")[0].reset();
                $("#imagePreview").empty();
            });

            // close edit popup
            $('#editclosePopup').click(() => {
                $('#editoverlay').hide();
                $("#editDetails")[0].reset();
                $("#edit_imagePreview").empty();
            });

            // add banner - start
            $('#addDetails').submit(function(e) {
                $('#submit').prop('disabled', true).val('Adding...');
                e.preventDefault();
                const file = $('#image')[0].files[0];
                if ($("#name").val().trim() == "" || $("#review").val().trim() == "") {
                    Swal.fire({
                        title: 'Field Cannot br Empty!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $('#submit').prop('disabled', false).val('Add');
                    });
                } else {
                    const formData = new FormData();
                    formData.append("name", $("#name").val().trim());
                    formData.append("review", $("#review").val().trim());
                    formData.append("req_type", "add");

                    if (file) {
                        compressImage(file).then(function(compressed) {
                            formData.append('image[]', compressed, 'image_0.webp');
                            sendFormData(formData, 1);
                        }).catch(function(error) {
                            Swal.fire({
                                title: "Please try again",
                                text: "Image compression failed !",
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'my-swal-confirm-button',
                                },
                            }).then(() => {
                                $('#submit').prop('disabled', false).val('Add');
                            });
                        });
                    } else {
                        Swal.fire({
                            title: "Please try again",
                            text: "File not found !",
                            icon: 'error',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            $('#submit').prop('disabled', false).val('Add');
                        });
                    }
                }
            });
            // add - end

            // update - start            
            $('#editDetails').submit(function(e) {
                $('#editSubmit').prop('disabled', true).val('Updating...');
                e.preventDefault();
                const file = $('#edit_image')[0].files[0];
                if ($("#edit_name").val().trim() == "" || $("#edit_review").val().trim() == "") {
                    Swal.fire({
                        title: 'Field Cannot br Empty!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $('#editSubmit').prop('disabled', false).val('Update');
                    });
                } else {
                    const editFormData = new FormData();
                    editFormData.append("id", $("#edit_id").val().trim());
                    editFormData.append("name", $("#edit_name").val().trim());
                    editFormData.append("review", $("#edit_review").val().trim());
                    editFormData.append("image_name", $("#edit_old_image").val().trim());
                    editFormData.append("req_type", "edit");

                    if (file) {
                        compressImage(file).then(function(compressed) {
                            editFormData.append('image[]', compressed, 'image_0.webp');
                            sendFormData(editFormData, 2);
                        }).catch(function(error) {
                            Swal.fire({
                                title: "Please try again",
                                text: "Image compression failed !",
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'my-swal-confirm-button',
                                },
                            }).then(() => {
                                $('#editSubmit').prop('disabled', false).val('Update');
                            });
                        });
                    } else {
                        editFormData.append('image[]', []);
                        sendFormData(editFormData, 2);
                    }

                    // $.ajax({
                    //     type: 'POST',
                    //     url: '<?= $admin_url ?>/backend/testimonial/',
                    //     data: editFormData,
                    //     contentType: false,
                    //     processData: false,
                    //     success: (res) => {
                    //         if (res == "Success") {
                    //             Swal.fire({
                    //                 title: 'Details Updated Successfully!',
                    //                 icon: 'success',
                    //                 customClass: {
                    //                     confirmButton: 'my-swal-confirm-button',
                    //                 },
                    //             }).then(() => {
                    //                 location.reload();
                    //             });
                    //         } else {
                    //             Swal.fire({
                    //                 title: res.trim(),
                    //                 icon: 'error',
                    //                 customClass: {
                    //                     confirmButton: 'my-swal-confirm-button',
                    //                 },
                    //             }).then(() => {
                    //                 $('#editSubmit').prop('disabled', false).val('Update');
                    //             });
                    //         }
                    //     },
                    //     error: (error) => {
                    //         console.log(error);
                    //     }
                    // });
                }
            });
            //update - end

        });

        // delete - start
        const delItem = (id, name) => {
            console.log(id);
            Swal.fire({
                title: 'Are you sure?',
                text: 'You Want to Delete this file!',
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'my-swal-confirm-button',
                },
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: '<?= $admin_url ?>/backend/testimonial/',
                        data: {
                            req_type: 'delete',
                            id: id,
                            name: name,
                        },
                        success: (res) => {
                            if (res == "Success") {
                                Swal.fire({
                                    title: 'Data Deleted Successfully!',
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
        // delete - end  

        // fetch - start
        const editItem = (id) => {
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/testimonial/',
                data: {
                    req_type: 'fetch',
                    id: id
                },
                success: (res) => {
                    var result = JSON.parse(res);
                    if (result.status) {
                        $('#edit_id').val(result.data.id);
                        $('#edit_name').val(result.data.name);
                        $('#edit_review').val(result.data.review);
                        $('#edit_old_image').val(result.data.image);
                        $('#edit_imagePreview').empty();
                        $('#edit_imagePreview').append(`<img src="<?= $admin_url ?>/uploads/testimonials/${result.data.image}" style="max-width: 100%; max-height: 200px; margin: 5px;">`);

                        // open editpopup                      
                        $('#editoverlay').show();
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
        };
        // fetch - end
    </script>

</body>

</html>