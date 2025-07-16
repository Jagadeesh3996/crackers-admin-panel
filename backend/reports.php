
<?php
include("../utilities/db.php");

function FilterData()
{
    extract($_REQUEST);
    global $conn;
    try {

        $query = "SELECT 
                    a.name, 
                    a.phone, 
                    a.address, 
                    a.final_total 
                    FROM `tbl_orders` AS a 
                    WHERE ";

        $params = [];
        $types = "";

        if (!empty($order_status)) {
            $query .= "a.status = ? ";
            $params[] = $order_status;
            $types .= "i";
        }

        if (!empty($order_status) && !empty($from_date) && empty($to_date)) {
            $query .= "AND ";
        }

        if (!empty($from_date) && empty($to_date)) {
            $query .= "a.date = ? ";
            $params[] = $from_date;
            $types .= "s";
        }

        if (!empty($order_status) && !empty($from_date) && !empty($to_date)) {
            $query .= "AND ";
        }

        if (!empty($from_date) && !empty($to_date)) {
            $query .= "a.date BETWEEN ? AND ? ";
            $params[] = $from_date;
            $params[] = $to_date;
            $types .= "ss";
        }

        $query .= "ORDER BY a.id DESC";

        $stmt = $conn->prepare($query);

        // Bind parameters dynamically
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception('Error executing query: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        if (!empty($data)) {
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'message' => 'Error fetching data', 'error' => $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
}

switch ($_REQUEST['req_type']) {
    case 'filter_data':
        FilterData();
        break;
};
?>