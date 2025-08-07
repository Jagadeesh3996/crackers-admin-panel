<?php
    include("../utilities/db.php");

    function GetSales(){
        extract($_REQUEST);
        global $conn;
        // Query to fetch state details
        $query = "SELECT date_column as date, SUM(count) AS value
                    FROM (
                        SELECT date AS date_column, COUNT(*) AS count FROM tbl_billing WHERE status = 5 GROUP BY date ASC
                        UNION ALL
                        SELECT date AS date_column, COUNT(*) AS count FROM tbl_orders WHERE status = 5 GROUP BY date ASC
                    ) AS subquery
                    GROUP BY date ASC";

        $result = mysqli_query($conn, $query);
        $list = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($list ,$row);
            }
            // Return state details in JSON format
            echo json_encode(['status' => true, 'data' => $list]);
        } else {
            echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
        }
    }

    function GetOrders(){
        extract($_REQUEST);
        global $conn;
        // Query to fetch state details
        $query2 = "SELECT date , COUNT(*) AS value FROM tbl_orders GROUP BY date ASC";
        $result2 = mysqli_query($conn, $query2);
        $list2 = [];

        if ($result2) {
            while ($row2 = mysqli_fetch_assoc($result2)) {
                array_push($list2 ,$row2);
            }
            // Return state details in JSON format
            echo json_encode(['status' => true, 'data' => $list2]);
        } else {
            echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
        }
    }

    switch ($_REQUEST['req_type']) {
        case 'getSales':
            GetSales();
            break;

        case 'getOrders':
            GetOrders();
            break;
    }
?>