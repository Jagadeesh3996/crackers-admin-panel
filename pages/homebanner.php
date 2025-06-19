<?php
include('../utilities/session.php');

// Fetch homebanner from the database
$query = "SELECT * FROM tbl_homebanner WHERE status = 1 ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$hbanner = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                                    <h1>Home Banner<span class="text-uppercase"></h1>
                                </div>
                                <div>
                                    <button id="showPopup" class="btn btn-success text-white">+ Add Banner</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="iq-header-img">
                        <img src="../assets/images/dashboard/top-header.png" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
                    </div> -->
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
                                <?php foreach ($hbanner as $data): ?>
                                    <div class="col-md-6 col-12">
                                        <div class="card p-3" style="background: #E6F5F6;border:none">
                                            <img class="img-fluid m-auto" style="width:100%;height: 250px" src="<?= $admin_url ?>/uploads/homebanners/<?= $data['banner']; ?>" alt="<?= $data['banner']; ?>">
                                            <a style="position: absolute;right:0;top:0" class='width-fit-content btn btn-sm btn-icon btn-danger' onclick="delItem(<?= $data['id']; ?>, '<?= $data['banner']; ?>')" data-bs-toggle='tooltip' data-bs-placement='top' title='Delete'>
                                                <span class='btn-inner'>
                                                    <svg class='icon-20' width='20' viewBox='0 0 24 24' fill='none'
                                                        xmlns='http://www.w3.org/2000/svg' stroke='currentColor'>
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
            <h3 class="text-center mb-2">Add Home Banner</h3>
            <form id='addDetails' enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="form-label" for="image">Home Banner</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*" required onchange="imagePreview('bannerPreview')">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div id="bannerPreview"></div>
                    </div>
                </div>
                <input type="hidden" name="req_type" id="req_type" value="addhb">
                <input type="submit" id="addSubmit" class="btn btn-success w-100" name="addData" value="Add" />
            </form>
            <input id="closePopup" type="button" class="close btn btn-danger mt-3 w-100" value="Close">
        </div>
    </div>
    <!-- Add - popup end-->

    <!-- script Start -->
    <?php include('../utilities/script.php') ?>
    <!-- script End -->

    <script>
        $('#overlay').hide();

        // previewImage
        const imagePreview = (previewId) => {
            $("#" + previewId).empty(); // Clear previous previews
            const file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => $("#" + previewId).append(`<img src="${e.target.result}" style="max-width: 100%; max-height: 200px; margin: 5px;">`);
            reader.readAsDataURL(file);
        };

        $(document).ready(function() {
            // open add popup            
            $('#showPopup').click(() => $('#overlay').show());

            // close add popup
            $('#closePopup').click(() => {
                $('#overlay').hide();
                $("#addDetails")[0].reset();
                $("#bannerPreview").empty();
            });

            // add banner - start
            $('#addDetails').submit(function(e) {
                $('#addSubmit').prop('disabled', true).val("Adding...");
                e.preventDefault();
                const formData = new FormData($(this)[0]);
                $.ajax({
                    type: 'POST',
                    url: '<?= $admin_url ?>/backend/banner/',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (res) => {
                        if (res == "Success") {
                            Swal.fire({
                                title: 'Banner Uploaded Successfully!',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'my-swal-confirm-button',
                                },
                            }).then((response) => {
                                $('#addSubmit').prop('disabled', false).val("Add");
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
                                $('#addSubmit').prop('disabled', false).val("Add");
                            });
                        }
                    },
                    error: (error) => {
                        console.log(error);
                    }
                });
            });
            // add - end
        });

        // delete - start
        const delItem = (id, name) => {
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
                        url: '<?= $admin_url ?>/backend/banner/',
                        data: {
                            req_type: 'deletehb',
                            id: id,
                            name: name,
                        },
                        success: (res) => {
                            if (res == "Success") {
                                Swal.fire({
                                    title: 'Banner Deleted Successfully!',
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
    </script>

</body>

</html>