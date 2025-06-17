    <!-- Library Bundle Script -->
    <script src="<?= $admin_url ?>/assets/js/core/libs.min.js"></script>

    <!-- External Library Bundle Script -->
    <script src="<?= $admin_url ?>/assets/js/core/external.min.js"></script>

    <!-- Widgetchart Script -->
    <script src="<?= $admin_url ?>/assets/js/charts/widgetcharts.js"></script>

    <!-- mapchart Script -->
    <script src="<?= $admin_url ?>/assets/js/charts/vectore-chart.js"></script>
    <script src="<?= $admin_url ?>/assets/js/charts/dashboard.js"></script>

    <!-- fslightbox Script -->
    <script src="<?= $admin_url ?>/assets/js/plugins/fslightbox.js"></script>

    <!-- Settings Script -->
    <script src="<?= $admin_url ?>/assets/js/plugins/setting.js"></script>

    <!-- Slider-tab Script -->
    <script src="<?= $admin_url ?>/assets/js/plugins/slider-tabs.js"></script>

    <!-- Form Wizard Script -->
    <script src="<?= $admin_url ?>/assets/js/plugins/form-wizard.js"></script>

    <!-- AOS Animation Plugin-->
    <script src="<?= $admin_url ?>/assets/vendor/aos/dist/aos.js"></script>

    <!-- App Script -->
    <script src="<?= $admin_url ?>/assets/js/hope-ui.js" defer></script>

    <!-- sweet alert -->
    <script src="<?= $admin_url ?>/assets/js/sweetalert2.all.min.js"></script>

    <!-- Chart js CDN link -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let active = "<?= $active ?>";
        let navActive = active.split(".")[0];
        $("." + navActive).addClass('active');

        const logoutPopup = () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                customClass: {
                    confirmButton: 'my-swal-confirm-button',
                },
                confirmButtonText: 'Yes, Logout!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= $admin_url ?>/utilities/logout/';
                }
            });
        };
    </script>