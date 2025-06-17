<!doctype html>
<html lang="en" dir="ltr">

<!-- head Start -->
<?php include('./utilities/head.php'); ?>
<!-- head END -->

<body data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <?php include('./utilities/loader.php') ?>
    <!-- loader END -->

    <!-- Main Content Start -->
    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                <div class="card-body">
                                    <!--Logo start-->
                                    <div class="logo-main">
                                        <div class="logo-normal">
                                            <center><img width="100px" src="<?= $admin_url ?>/assets/images/full_logo.png" alt="logo"></center>
                                        </div>
                                    </div>
                                    <!--logo End-->
                                    <!-- <h2 class="mb-2 text-center col-blue">Log In</h2> -->
                                    <!-- <p class="text-center mt-2">   Login to stay connected.</p> -->
                                    <form id='login_form'>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter Your  email" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="pwd" class="form-label">Password</label>
                                                    <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Enter Your password" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="checkbox" name="showpass" id="showpass" />
                                                <label for="showpass" class="clo">Show Password</label>
                                            </div>
                                            <!-- <div class="col-lg-12 d-flex justify-content-between">
                                                    <a href="forgot_pass.php" class='p-1'>Forgot Password</a>
                                                </div> -->

                                        </div>
                                        <div class="d-flex justify-content-center mt-3">
                                            <button type="submit" class="btn bg-color text-white"><b>Log In</b></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden bg-color">
                    <!-- <img src="<?= $admin_url ?>/assets/images/auth/01.png" class="img-fluid gradient-main animated-scaleX" alt="image"> -->
                </div>
            </div>
        </section>
    </div>
    <!-- Main Content End -->

    <!-- script Start -->
    <?php include('./utilities/script.php') ?>
    <!-- script End -->

    <script>
        // password show and hide
        $('#showpass').on('change', () => {
            ($('#showpass').prop('checked')) ? $('#pwd').attr('type', 'text'): $('#pwd').attr('type', 'password');
        });

        // user login 
        $(document).ready(function() {
            $('#login_form').submit(function(e) {
                e.preventDefault();
                const formdata = {
                    'email': $("#email").val(),
                    'pwd': $("#pwd").val(),
                    'req_type': 'login'
                };
                $.ajax({
                    url: '<?= $admin_url ?>/backend/login/',
                    type: 'POST',
                    data: formdata,
                    success: (res) => {
                        if (res.trim() === 'Success') {
                            Swal.fire({
                                title: 'Log In Successfully',
                                icon: 'success',
                                timer: 1000, // 1 second
                                showConfirmButton: false, // Hide the "Ok" button
                                customClass: {
                                    confirmButton: 'my-swal-confirm-button',
                                },
                                confirmButtonText: 'Ok'
                            }).then(() => {
                                window.location.href = '<?= $admin_url ?>/';
                            })
                        } else {
                            Swal.fire({
                                title: 'Mail And Password Does Not Match!',
                                icon: 'warning',
                                customClass: {
                                    confirmButton: 'my-swal-confirm-button',
                                },
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: (error) => {
                        console.log(error);
                    }
                });
            });
        });
    </script>
</body>

</html>