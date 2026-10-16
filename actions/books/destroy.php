<?php
require_once __DIR__ . "/../../config/database.php";


if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === "GET") {   
   try {
    $pdo->beginTransaction();
    $id = $_GET['id'];
    //delete relation data 

    $query = "delete from book_author where book_id = :book_id";
    $stmt = $pdo->prepare($query);
    $stmt   ->execute([
        'book_id' => $id
    ]);

//delete book data
 $query = "delete from book_author where book_id = :book_id";
    $stmt = $pdo->prepare($query);
    $stmt   ->execute([
        'book_id' => $id
    ]);

$pdo->commit();

header("Location: ../../pages/books/index.php");

   } catch (Throwable $e) {
    $pdo->rollBack();
    throw $e;
   } 
   
}

?>
