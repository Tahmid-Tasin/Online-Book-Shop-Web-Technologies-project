<?php
session_start();

include "../config/database.php";

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode([
        "message"=>"Login required"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);
$quantity = intval($_POST['quantity']);

$stock_check = mysqli_prepare(
    $conn,
    "SELECT stock FROM books WHERE id=?"
);

mysqli_stmt_bind_param(
    $stock_check,
    "i",
    $book_id
);

mysqli_stmt_execute($stock_check);
$stock_result = mysqli_stmt_get_result($stock_check);
$book = mysqli_fetch_assoc($stock_result);

if($book['stock'] < $quantity){
    echo json_encode([
        "message"=>"Stock not available"
    ]);
    exit;
}

$check = mysqli_prepare(
    $conn,
    "SELECT id, quantity
     FROM cart
     WHERE user_id=? AND book_id=?"
);

mysqli_stmt_bind_param(
    $check,
    "ii",
    $user_id,
    $book_id
);

mysqli_stmt_execute($check);
$result = mysqli_stmt_get_result($check);

if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_assoc($result);
    $new_qty = $row['quantity'] + $quantity;
    $update = mysqli_prepare(
        $conn,
        "UPDATE cart
         SET quantity=?
         WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $update,
        "ii",
        $new_qty,
        $row['id']
    );

    mysqli_stmt_execute($update);

}
else
{
    $insert = mysqli_prepare(
        $conn,
        "INSERT INTO cart(user_id,book_id,quantity)
         VALUES(?,?,?)"
    );

    mysqli_stmt_bind_param(
        $insert,
        "iii",
        $user_id,
        $book_id,
        $quantity
    );
    mysqli_stmt_execute($insert);
}

echo json_encode([
    "message"=>"Added To Cart"
]);
?>