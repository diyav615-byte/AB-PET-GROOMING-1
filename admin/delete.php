<?php

include '../config/db.php';

if(isset($_GET['id']) && isset($_GET['table'])){

    $id = intval($_GET['id']);
    $table = $_GET['table'];

    $allowed_tables = ['boarding','appointments','contact_messages'];

    if(in_array($table, $allowed_tables)){

        $query = "DELETE FROM $table WHERE id = $id";

        if(mysqli_query($conn, $query)){

            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;

        } else {

            echo "Delete failed.";

        }

    } else {

        echo "Invalid table.";

    }

} else {

    echo "Missing data.";

}

?>

<?php

session_start();

include '../config/db.php';

require_once 'includes/ActivityLogger.php';

if (!isset($_GET['id']) || !isset($_GET['table'])) {
    die("Invalid Request");
}

$id = (int) $_GET['id'];

$table = $_GET['table'];

$allowedTables = [
    'bookings',
    'boarding',
    'services',
    'reviews',
    'contact_messages'
];

if (!in_array($table, $allowedTables)) {
    die("Table not allowed");
}

/*
|--------------------------------------------------------------------------
| GET OLD DATA
|--------------------------------------------------------------------------
*/

$oldQuery = mysqli_query(
    $conn,
    "SELECT * FROM $table WHERE id = $id"
);

$oldData = mysqli_fetch_assoc($oldQuery);

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

$delete = mysqli_query(
    $conn,
    "DELETE FROM $table WHERE id = $id");
    if ($delete) {

    echo "Deleted Successfully<br>";

    $logger = getActivityLogger($conn);

    $result = $logger->delete(
        $table,
        $id,
        $oldData
    );

    if ($result) {
        echo "Activity Logged";
    } else {
        echo "Log Failed";
    }

    exit;
}
;

/*
|--------------------------------------------------------------------------
| ACTIVITY LOG
|--------------------------------------------------------------------------
*/

if ($delete) {

    $logger = getActivityLogger($conn);

    $logger->delete(
        $table,
        $id,
        $oldData
    );

}

header("Location: " . $_SERVER['HTTP_REFERER']);

exit;

?>