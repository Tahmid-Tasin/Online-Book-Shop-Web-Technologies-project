<?php
include "../config/database.php";

header('Content-Type: application/json');

$q = trim($_GET['q']);
$filter = $_GET['filter'];

if($filter !== "title" && $filter !== "author"){
    echo json_encode([]);
    exit;
}

$search = "%" . $q . "%";

$sql = "SELECT * FROM books WHERE $filter LIKE ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $search
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = [];

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

echo json_encode($data);
?>