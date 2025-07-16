<?php
include('../utilities/session.php');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<!-- head Start -->
<?php include('../utilities/head.php'); ?>
<style>
    input[type="date"] {
        cursor: pointer;
    }
</style>
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
                                    <h1>REPORTS</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header End -->
        </div>

        <!-- Hero Section Start -->
        <div class="conatiner-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row bg-color mb-0 p-3 rounded">

                                <div class="col-12 col-lg-4 col-sm-6">
                                    <div class="bd-example">
                                        <div class="form-group">
                                            <label class="form-label fw-bold text-white" for="dateRange">Date Range</label>
                                            <input type="text" name="dateRange" id="dateRange" class="form-control range_flatpicker2" placeholder="Select Date Range" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-white" for="order_status">Order Status</label>
                                        <select name="order_status" id="order_status" class="form-select">
                                            <option value="" hidden>Select Status</option>
                                            <option value=1>Order Received</option>
                                            <option value=5>Deliverd</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-sm-6 d-flex align-items-center mt-1">
                                    <div>
                                        <button class="btn btn-success mb-1" id="filter" onclick="getFilterData()">Filter</button>
                                        <button class="btn btn-danger mb-1" onclick="clearFilter()">Clear Filter</button>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-sm-6 d-flex align-items-center">
                                    <div>
                                        <button class="btn btn-success mb-1" onclick="exportData('pdf')">Export Pdf</button>
                                        <button class="btn btn-success mb-1" onclick="exportData('excel')">Export Excel</button>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="bg-color mt-3 mb-0 p-3 rounded text-white">
                                <p class="mb-0">Filter by : <span id="filterValue">N/A</span></p>
                            </div> -->

                            <div id="filterData"></div>
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
        let data = [];
        // let filters = "";

        // date picker
        const fp = flatpickr(".range_flatpicker2", {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: false // disables typing
        });

        // display data in table
        const displayData = () => {
            // Destroy the previous DataTable instance if it exists
            if ($.fn.dataTable.isDataTable('#filterTable')) {
                $('#filterTable').DataTable().destroy();
            }
            $("#filterData").empty(); // Clear the previous data

            // Start the table structure
            let table = `
                        <div class="table-responsive mt-3">
                            <table id="filterTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Number</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">Order value</th>
                                    </tr>
                                </thead>
                                <tbody>`;

            // Iterate over the data and generate rows
            data.forEach((item, index) => {
                table += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td class="text-center">${item.name}</td>
                            <td class="text-center">${item.phone}</td>
                            <td class="text-center">${item.address}</td>
                            <td class="text-center">&#8377; ${item.final_total.toLocaleString("en-IN", {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })}</td>
                        </tr>`;
            });

            // Close the table structure
            table += `
                            </tbody>
                        </table>
                    </div>`;

            // Append the table to the DOM
            $("#filterData").append(table);

            // Initialize DataTables to add pagination and other features
            $('#filterTable').DataTable({
                "paging": true, // Enable pagination
                "lengthChange": true, // Allow users to change the number of records per page
                "searching": false, // Enable the search box
                "ordering": true, // Enable column sorting
                "info": true, // Show information about table (like "Showing 1 to 10 of 50 entries")
                "autoWidth": false // Adjust column width automatically
            });
        };

        // getFilterData - start
        const getFilterData = () => {
            $("#filter").prop("disabled", true).text("filtering...");
            let dateRange = $("#dateRange").val() || " to ";
            let parts = dateRange.split(" to ");
            let from_date = parts[0];
            let to_date = parts[1] ?? "";
            let order_status = $("#order_status").val() || "";

            if (from_date === "" && to_date === "" && order_status === "") {
                data = [];
                displayData();
                warning("Enter the filter criteria!");
                $("#filter").prop("disabled", false).text("filter");
                return;
            }

            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/reports',
                dataType: 'json',
                data: {
                    req_type: 'filter_data',
                    from_date: from_date,
                    to_date: to_date,
                    order_status: order_status,
                },
                success: (response) => {

                    data = (response.status) ? response.data : [];

                    // filters = "";

                    // if (order_status) {
                    //     filters += `Status - ${order_status}, `;
                    // }
                    // if (from_date) {
                    //     filters += `Date - ${from_date}`;
                    // }
                    // if (to_date) {
                    //     filters += ` to ${to_date}`;
                    // }

                    // Optional: Remove trailing comma and space
                    // filters = filters.replace(/, $/, '');

                    // $('#filterValue').text(filters);

                    displayData();

                    $("#filter").prop("disabled", false).text("filter");

                    return false;
                },
                error: (xhr, status, error) => {
                    console.error(xhr.responseText);
                }
            });

        };

        // clear filters
        const clearFilter = () => {
            $("#order_status").val("");
            fp.clear();
            // filters = "";
            $('#filterValue').text('N/A');
            data = [];
            displayData();
        };

        // export data
        const exportData = (type) => {

            if (!data.length) {
                warning("No data found");
                return;
            }

            // data['filter'] = `Filter by : ` + filters;

            if (type === 'pdf') {
                // Show loading alert
                Swal.fire({
                    title: 'Preparing Report...',
                    text: 'Please wait while we generate the report.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '<?= $admin_url ?>/backend/exportpdf/',
                    data: {
                        data: JSON.stringify(data),
                        // filter: JSON.stringify(filters)
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob) {
                        // Close the loading modal
                        Swal.close();

                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = "reports.pdf";
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    },
                    error: function(xhr, status, error) {
                        console.error("PDF download failed:", error);
                        warning("Failed to export PDF.");
                    }
                });
            } else {
                // Show loading alert
                Swal.fire({
                    title: 'Preparing Report...',
                    text: 'Please wait while we generate the report.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('<?= $admin_url ?>/backend/exportexcel/', {
                        method: 'POST',
                        body: new URLSearchParams({
                            data: JSON.stringify(data),
                            // filter: JSON.stringify(filters)
                        })
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        // Close the loading modal
                        Swal.close();

                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'reports.xls';
                        a.click();
                        window.URL.revokeObjectURL(url);
                    });
            }
        };

        displayData();
    </script>
</body>

</html>