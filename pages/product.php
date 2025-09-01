<?php
include('../utilities/session.php');

$query = "SELECT a.* 
            FROM tbl_product AS a 
            LEFT JOIN tbl_category AS b ON b.name = a.category 
            WHERE b.status = 1 
            AND a.status >= 1 
            GROUP BY 
            CAST(SUBSTRING_INDEX(a.alignment, ' ', 1) AS UNSIGNED), 
            SUBSTRING(a.alignment, LOCATE(' ', a.alignment) + 1)";
$result = mysqli_query($conn, $query);
$productlist = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
                                    <h1>Product List</h1>
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
                                    <button id="showPopup" class="btn btn-success text-white mb-1">+ Add Product</button>
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
                                            <th>
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
                                            <!-- <th class="text-center">Product Image</th> -->
                                            <th class="text-center">Product Name</th>
                                            <th class="text-center">Tamil Name</th>
                                            <th class="text-center">Category Name</th>
                                            <th class="text-center">MRP</th>
                                            <th class="text-center">Selling Price</th>
                                            <th class="text-center">TYPE</th>
                                            <th class="text-center">Video URL</th>
                                            <th class="text-center">Availability</th>
                                            <th class="text-center">Alignment</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id='record'>
                                        <?php
                                        $serialNumber = 1;
                                        foreach ($productlist as $data) {
                                            $id = $data['id'];
                                            $imageUrl = ($data["image"] == '') ? "$admin_url/assets/images/logo.png" : "$admin_url/uploads/{$data['image']}";
                                        ?>
                                            <tr>
                                                <td><input type="checkbox" name="checkbox" class="checkbox" value=<?= $id ?> /></td>
                                                <td><?= $serialNumber++ ?></td>
                                                <!-- <td><img src="<?= $imageUrl ?>" alt='Product Image' style='max-width: 50px; max-height: 50px;'></td> -->
                                                <td><?= $data['name'] ?></td>
                                                <td><?= $data['tamil_name'] ?></td>
                                                <td><?= $data['category'] ?></td>
                                                <td>&#8377; <?= $data['mrp'] ?></td>
                                                <td>&#8377; <?= $data['selling_price'] ?></td>
                                                <td><?= $data['type'] ?></td>
                                                <td style="white-space: break-spaces;"><?= $data['url'] ?></td>
                                                <td>
                                                    <input type="checkbox" name="switch_<?= $id ?>" id="switch_<?= $id ?>" class="togglebox" <?= $data['status'] == 1 ? 'checked' : ''; ?> oninput="availability(<?= $id ?>)" />
                                                    <label for="switch_<?= $id ?>" style="padding-top: 5px;" class="toggle text-white"><b>Yes&nbsp;&nbsp;No</b></label>
                                                </td>
                                                <td>
                                                    <?= $data['alignment'] ?>
                                                    <input type="hidden" value="<?= $data['alignment'] ?>" class="alignment" />
                                                </td>
                                                <td>
                                                    <a class='btn btn-sm btn-icon btn-info' onclick="editImages(<?= $id ?>)" data-bs-toggle='tooltip' title='Images' data-bs-placement='top'>
                                                        <svg class='icon-20' width='20' fill='#fff' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                            <path d="M0 96C0 60.7 28.7 32 64 32l384 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 96zM323.8 202.5c-4.5-6.6-11.9-10.5-19.8-10.5s-15.4 3.9-19.8 10.5l-87 127.6L170.7 297c-4.6-5.7-11.5-9-18.7-9s-14.2 3.3-18.7 9l-64 80c-5.8 7.2-6.9 17.1-2.9 25.4s12.4 13.6 21.6 13.6l96 0 32 0 208 0c8.9 0 17.1-4.9 21.2-12.8s3.6-17.4-1.4-24.7l-120-176zM112 192a48 48 0 1 0 0-96 48 48 0 1 0 0 96z" stroke='currentColor' />
                                                        </svg>
                                                    </a>
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
                <input type="submit" class="btn btn-success w-100" value="Import" readonly />
            </form>
            <input id="importclosePopup" type='button' class="close btn btn-danger w-100 mt-3" value="Close" readonly />
        </div>
    </div>
    <!-- import - popup end-->

    <!-- Add - popup start-->
    <div id="overlay">
        <div id="popup">
            <h3 class="text-center mb-2">Add Product</h3>
            <form id='addDetails' enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="category">Category Name</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="" hidden>select</option>
                                <?php
                                // Fetch categories from the database
                                $query2 = "SELECT * FROM tbl_category WHERE status='1'";
                                $result2 = mysqli_query($conn, $query2);
                                if ($result2) {
                                    // Loop through categories and populate the dropdown
                                    while ($row2 = mysqli_fetch_assoc($result2)) {
                                        echo "<option value='" . htmlspecialchars($row2['name'], ENT_QUOTES) . "'>" . htmlspecialchars($row2['name']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Error fetching categories</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="name">Product Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="tamil_name">Tamil Name</label>
                            <input type="text" class="form-control" name="tamil_name" id="tamil_name">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="mrp">MRP</label>
                            <input type="number" class="form-control" name="mrp" id="mrp" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="selling_price">Selling Price</label>
                            <input type="number" class="form-control" name="selling_price" id="selling_price" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="type">TYPE</label>
                            <input type="text" class="form-control" name="type" id="type" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="alignment">Alignment</label>
                            <input type="text" class="form-control" name="alignment" id="alignment" oninput="alignValitate()" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="vurl">Video URL</label>
                            <input type="text" class="form-control" name="vurl" id="vurl">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="images">Images</label>
                            <input type="file" class="form-control" name="images[]" id="images" accept="image/*" multiple onchange="imagePreview('imagePreview')">
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="imagePreview"></div>
                    </div>
                </div>
                <input type="submit" class="btn btn-success w-100 mt-2" name="addData" value="Add" readonly />
            </form>
            <input id="closePopup" type='button' class="close btn btn-danger w-100 mt-3" value="Close" readonly />
        </div>
    </div>
    <!-- Add - popup end-->

    <!-- Edit - popup start-->
    <div id="editoverlay">
        <div id="editpopup">
            <h4 class="text-center mb-2">Edit Product</h4>
            <form id='editDetails'>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_category">Category Name</label>
                            <select name="edit_category" id="edit_category" class="form-control" required>
                                <option value="" hidden>select</option>
                                <?php
                                // Fetch categories from the database
                                $query3 = "SELECT * FROM tbl_category WHERE status='1'";
                                $result3 = mysqli_query($conn, $query3);
                                if ($result3) {
                                    // Loop through categories and populate the dropdown
                                    while ($row3 = mysqli_fetch_assoc($result3)) {
                                        echo "<option value='" . htmlspecialchars($row2['name'], ENT_QUOTES) . "'>" . htmlspecialchars($row2['name']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Error fetching categories</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_name">Product Name</label>
                            <input type="text" class="form-control" name="edit_name" id="edit_name" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_tamil_name">Tamil Name</label>
                            <input type="text" class="form-control" name="edit_tamil_name" id="edit_tamil_name">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_mrp">MRP</label>
                            <input type="number" class="form-control" name="edit_mrp" id="edit_mrp" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_selling_price">Selling Price</label>
                            <input type="number" class="form-control" name="edit_selling_price" id="edit_selling_price">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_type">TYPE</label>
                            <input type="text" class="form-control" name="edit_type" id="edit_type" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_vurl">Video URL</label>
                            <input type="text" class="form-control" name="edit_vurl" id="edit_vurl">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_alignment">Alignment</label>
                            <input type="text" class="form-control" name="edit_alignment" id="edit_alignment" oninput="alignValitate()" required>
                        </div>
                    </div>
                    <!-- <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label class="form-label" for="edit_image">Image</label>
                            <input type="file" class="form-control" name="edit_image" id="edit_image" accept="image/*" onchange="imagePreview('editimagePreview')">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div id="editimagePreview"></div>
                    </div> -->
                </div>
                <input type="hidden" name="edit_id" id="edit_id">
                <input type="submit" class="btn btn-success w-100" value="Update" readonly />
            </form>
            <input id="editclosePopup" class="btn btn-danger w-100 mt-3" value="Close" readonly />
        </div>
    </div>
    <!-- Edit - popup end-->

    <!-- Edit Images - popup start-->
    <div id="editImages" class="modal">
        <div class="modal-content">
            <h4 class="text-center mb-2">Edit Images</h4>
            <form id='editImgDetails'>
                <div class="row">
                    <div class="col-12 mb-3">
                        <div id="allimagePreview"></div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="edit_images">Images</label>
                            <input type="file" class="form-control" name="edit_images[]" id="edit_images" accept="image/*" multiple onchange="imagePreview('editimagePreview')" required />
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="editimagePreview"></div>
                    </div>
                </div>
                <input type="hidden" name="prd_edit_id" id="prd_edit_id">
                <input type="submit" id="editImgSubmit" class="btn btn-success w-100" value="Add" readonly />
            </form>
            <input id="closeModal" class="btn btn-danger w-100 mt-3" value="Close" readonly />
        </div>
    </div>
    <!-- Edit Images - popup end-->

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
        table.on('page.dt length.dt', function () {
            const info = table.page.info();
            const currentPage = info.page + 1;
            const currentLength = table.page.len();
            const newUrl = `<?= $admin_url ?>/pages/product?p=${currentPage}&l=${currentLength}`;
            history.replaceState(null, '', newUrl);
        });

        $('#overlay').hide();
        $('#editoverlay').hide();
        $("#editImages").hide();
        $('#importoverlay').hide();
        let alignArr = [];
        let delal;
        const product_list = <?= json_encode($productlist) ?>;

        // generate unique file name
        const generateUniqueFilename = () => {
            return 'img_' + Date.now().toString(36) + Math.random().toString(36).substring(2, 8) + '.webp';
        };

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
            num = (num.toString()).replace(/[^0-9]/g, '').substring(0, 3);
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

        // previewImage
        const imagePreview = (previewId) => {
            $("#" + previewId).empty(); // Clear previous previews
            const files = event.target.files;

            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                let reader = new FileReader();
                reader.onload = (e) => $(`#${previewId}`).append(`<img src="${e.target.result}" style="max-width: 100px; max-height: 200px; margin: 5px;">`);
                reader.readAsDataURL(file);
            }
        };

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
                url: '<?= $admin_url ?>/backend/product/',
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
                        url: '<?= $admin_url ?>/backend/product/',
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

        // edit images - start
        const editImages = (id) => {
            const product = product_list.find(item => item.id == id);
            $("#prd_edit_id").val(id);
            $("#allimagePreview").empty();

            // Check if images exist and are valid
            if (!product.images || product.images === "" || product.images === "[]") {
                $("#editImages").show();
                return;
            }

            let images;
            try {
                images = JSON.parse(product.images);
                if (!Array.isArray(images) || images.length === 0) {
                    $("#editImages").show();
                    return;
                }
            } catch (error) {
                console.error("Invalid image JSON:", error);
                $("#editImages").show();
                return;
            }

            // Store in a global variable or hidden field if needed
            window.currentImages = [...images]; // Copy to manipulate

            images.forEach((image, index) => {
                const imageId = `img-${index}`;
                $("#allimagePreview").append(`
                    <div id="${imageId}" style="position: relative; display: inline-block; margin: 5px;">
                        <img src="<?= $admin_url ?>/uploads/${image}" style="max-width: 100px; max-height: 200px;">
                        <span onclick="deleteImage(${index})" style="
                            position: absolute;
                            top: -8px;
                            right: -8px;
                            background: red;
                            color: white;
                            border: 1px solid #fff;
                            border-radius: 50%;
                            width: 20px;
                            height: 20px;
                            text-align: center;
                            line-height: 17px;
                            padding-left: 1px;
                            cursor: pointer;
                            font-weight: bold;
                            font-size: 14px;
                        ">×</span>
                    </div>
                `);
            });
            $("#editImages").show();
        };

        const deleteImage = (index) => {
            let img_name = window.currentImages[index];
            const updatedArray = window.currentImages.filter(item => item !== img_name);

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to delete this image?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: '<?= $admin_url ?>/backend/product/',
                        data: {
                            'id': $("#prd_edit_id").val(),
                            'img_name': img_name,
                            'images': JSON.stringify(updatedArray),
                            'req_type': 'deletImg',
                        },
                        success: (response) => {
                            if (response.trim() === "Success") {
                                if (window.currentImages && window.currentImages.length > index) {
                                    window.currentImages.splice(index, 1); // Remove image from array
                                }
                                $(`#img-${index}`).remove();

                                Swal.fire({
                                    title: "Image Deleted Successfully",
                                    icon: 'success',
                                    timer: 1000, // 1seconds
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: response.trim(),
                                    icon: "error",
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
        // edit images - end

        // fetch - start
        const editItem = (id) => {
            const result = product_list.find(item => item.id == id);
            if (result) {
                $('#edit_id').val(result.id);
                $('#edit_category').val(result.category);
                $('#edit_name').val(result.name);
                $('#edit_tamil_name').val(result.tamil_name);
                $('#edit_mrp').val(result.mrp);
                $('#edit_selling_price').val(result.selling_price);
                $('#edit_type').val(result.type);
                $('#edit_vurl').val(result.url);
                $('#edit_alignment').val(result.alignment);
                delal = result.alignment;

                // open editpopup                      
                $('#editoverlay').show();
            } else {
                Swal.fire({
                    title: "No Product Found",
                    icon: 'error',
                    customClass: {
                        confirmButton: 'my-swal-confirm-button',
                    },
                });
            }
        };
        // fetch - end

        // product availability - start
        const availability = (id) => {
            const status = (event.target.checked) ? 1 : 2;
            $.ajax({
                type: 'POST',
                url: '<?= $admin_url ?>/backend/product/',
                data: {
                    req_type: 'change',
                    status: status,
                    id: id
                },
                success: (res) => {
                    if (res == "Success") {
                        Swal.fire({
                            title: 'Product Availability Changed Successfully!',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
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
        };
        // product availability - end

        $(document).ready(function() {
            // open add popup          
            $('#showPopup').click(() => {
                $("#alignment").val(alignArr.length > 0 ? (Math.max(...alignArr) + 1) : 1);
                $('#overlay').show();
            });

            // open import popup          
            $('#importPopup').click(() => $('#importoverlay').show());

            // close add popup
            $('#closePopup').click(() => {
                $('#overlay').hide();
                $('#addDetails')[0].reset();
                $("#imagePreview").empty();
            });

            // close edit popup
            $('#editclosePopup').click(() => {
                alignArr.push(delal);
                $('#editoverlay').hide();
                $('#editDetails')[0].reset();
                $("#editimagePreview").empty();
            });

            // close import popup
            $('#importclosePopup').click(() => {
                $('#importoverlay').hide();
                $('#importData')[0].reset();
            });

            // close images popup
            $('#closeModal').click(() => {
                $('.modal').hide();
                $('#editImgDetails')[0].reset();
                $("#editimagePreview").empty();
                location.reload();
            });

            // ajax call
            const sendFormData = (formData, id) => {
                $.ajax({
                    type: 'POST',
                    url: '<?= $admin_url ?>/backend/product/',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (res) => {
                        if (res.trim() == "Success") {
                            Swal.fire({
                                title: `Details ${id === 2 ? "Updated" : "Uploaded"} Successfully!`,
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
                                switch (id) {
                                    case 1:
                                        $("#addDetails [type='submit']").prop('disabled', false).val("Add");
                                        break;

                                    case 2:
                                        $("#editDetails [type='submit']").prop('disabled', false).val("Update");
                                        break;

                                    case 3:
                                        $("#editImgDetails [type='submit']").prop('disabled', false).val("Add");
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

            // 1. add item - start
            $('#addDetails').submit(function(e) {
                $("#addDetails [type='submit']").prop('disabled', true).val("Adding...");
                e.preventDefault();
                let alignment = $("#alignment").val().trim();
                const aCheck = alignCheck(alignment);
                const files = $('#images')[0].files;
                if (aCheck) {
                    Swal.fire({
                        title: 'Alignment Already Exists!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $("#addDetails [type='submit']").prop('disabled', false).val("Add");
                    });
                    return;
                } else if ($("#name").val().trim() == "" || $("#type").val().trim() == "") {
                    Swal.fire({
                        title: 'Field Cannot be Empty!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $("#addDetails [type='submit']").prop('disabled', false).val("Add");
                    });
                    return;
                } else if (Number($("#selling_price").val()) > Number($("#mrp").val())) {
                    Swal.fire({
                        title: 'Selling Price must not be greater than MRP!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $("#addDetails [type='submit']").prop('disabled', false).val("Add");
                    });
                    return;
                } else {
                    const formData = new FormData();
                    formData.append("category", $("#category").val().trim());
                    formData.append("name", $("#name").val().trim());
                    formData.append("tamil_name", $("#tamil_name").val().trim());
                    formData.append("type", $("#type").val().trim());
                    formData.append("vurl", $("#vurl").val().trim());
                    formData.append("alignment", alignment);
                    formData.append("mrp", $("#mrp").val());
                    formData.append("selling_price", $("#selling_price").val());
                    formData.append("req_type", "add");

                    if (files.length === 0) {
                        formData.append('images[]', '');
                        sendFormData(formData, 1);
                        return;
                    }

                    const compressPromises = $.map(files, function(file, index) {
                        return compressImage(file).then(function(compressed) {
                            formData.append('images[]', compressed, `image_${index}.webp`);
                        });
                    });

                    Promise.all(compressPromises).then(function() {
                        sendFormData(formData, 1);
                    }).catch(err => {
                        Swal.fire({
                            title: "Please try again",
                            text: "Image compression failed !",
                            icon: 'error',
                            customClass: {
                                confirmButton: 'my-swal-confirm-button',
                            },
                        }).then(() => {
                            $("#editImgDetails [type='submit']").prop('disabled', false).val("Add");
                        });
                    });
                }
            });
            // add item - end

            // 2. Update item - start
            $('#editDetails').submit(function(e) {
                $("#editDetails [type='submit']").prop('disabled', true).val("Updating...");
                e.preventDefault();
                alignArr = alignArr.filter(item => item !== delal);
                let alignment = $("#edit_alignment").val().trim();
                const aCheck = alignCheck(alignment);
                if (aCheck) {
                    Swal.fire({
                        title: 'Alignment Already Exists!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $("#editDetails [type='submit']").prop('disabled', false).val("Update");
                    });
                    return;
                } else if ($("#edit_name").val().trim() == "" || $("#edit_type").val().trim() == "") {
                    Swal.fire({
                        title: 'Field Cannot be Empty!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $("#editDetails [type='submit']").prop('disabled', false).val("Update");
                    });
                    return;
                } else if (Number($("#edit_selling_price").val()) > Number($("#edit_mrp").val())) {
                    Swal.fire({
                        title: 'Selling Price must not be greater than MRP!',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $("#editDetails [type='submit']").prop('disabled', false).val("Update");
                    });
                    return;
                } else {
                    const formData = new FormData();
                    formData.append("edit_id", $("#edit_id").val());
                    formData.append("edit_category", $("#edit_category").val().trim());
                    formData.append("edit_name", $("#edit_name").val().trim());
                    formData.append("edit_tamil_name", $("#edit_tamil_name").val().trim());
                    formData.append("edit_type", $("#edit_type").val().trim());
                    formData.append("edit_vurl", $("#edit_vurl").val().trim());
                    formData.append("edit_alignment", alignment);
                    formData.append("edit_mrp", $("#edit_mrp").val());
                    formData.append("edit_selling_price", $("#edit_selling_price").val());
                    formData.append("req_type", "edit");
                    sendFormData(formData, 2);
                }
            });
            // Update item - end

            // 3. add images - start
            $('#editImgDetails').submit(function(e) {
                e.preventDefault();
                $("#editImgDetails [type='submit']").prop('disabled', true).val("Adding...");
                const id = $("#prd_edit_id").val();
                const imagesArray = product_list.find(item => item.id == id).images || '[]';
                const files = $('#edit_images')[0].files;

                const formData = new FormData();
                formData.append("edit_id", id);
                formData.append('oldimages', imagesArray);
                formData.append("req_type", "addImages");

                const compressPromises = $.map(files, function(file, index) {
                    return compressImage(file).then(function(compressed) {
                        formData.append('images[]', compressed, `image_${index}.webp`);
                    });
                });
                // return;
                Promise.all(compressPromises).then(function() {
                    sendFormData(formData, 3);
                }).catch(err => {
                    Swal.fire({
                        title: "Image compression failed",
                        icon: 'error',
                        customClass: {
                            confirmButton: 'my-swal-confirm-button',
                        },
                    }).then(() => {
                        $("#editImgDetails [type='submit']").prop('disabled', false).val("Add");
                    });
                });
            });
            // add images - end

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
                        window.location.href = "<?= $admin_url ?>/backend/export-data?export=product";
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
                        window.location.href = "<?= $admin_url ?>/backend/sample-export?type=product";
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
                    url: '<?= $admin_url ?>/backend/product/',
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