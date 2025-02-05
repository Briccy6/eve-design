<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: logout.php');
    exit;
} else {
    // Check if the product ID is valid
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    if( $total == 0 ) {
        header('location: logout.php');
        exit;
    }
}

try {
    // Begin a transaction
    $pdo->beginTransaction();

    // Get the featured photo and delete it from the folder
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $p_featured_photo = $row['p_featured_photo'];
        if (file_exists('../assets/uploads/'.$p_featured_photo)) {
            unlink('../assets/uploads/'.$p_featured_photo);
        }
    }

    // Get other product photos and delete them from the folder
    $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $photo = $row['photo'];
        if (file_exists('../assets/uploads/product_photos/'.$photo)) {
            unlink('../assets/uploads/product_photos/'.$photo);
        }
    }

    // Delete from tbl_product_photo (child table) first
    $statement = $pdo->prepare("DELETE FROM tbl_product_photo WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));

    // Delete from tbl_product_size
    $statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));

    // Delete from tbl_product_color
    $statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));

    // Delete from tbl_rating
    $statement = $pdo->prepare("DELETE FROM tbl_rating WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));

    // Handle order and payment deletions
    $statement = $pdo->prepare("SELECT * FROM tbl_order WHERE product_id=?");
    $statement->execute(array($_REQUEST['id']));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        // Delete from tbl_payment
        $statement1 = $pdo->prepare("DELETE FROM tbl_payment WHERE payment_id=?");
        $statement1->execute(array($row['payment_id']));
    }

    // Delete from tbl_order
    $statement = $pdo->prepare("DELETE FROM tbl_order WHERE product_id=?");
    $statement->execute(array($_REQUEST['id']));

    // Finally, delete from tbl_product (parent table)
    $statement = $pdo->prepare("DELETE FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));

    // Commit the transaction
    $pdo->commit();

    // Redirect to product page
    header('location: product.php');
    
} catch (Exception $e) {
    // Rollback the transaction if something goes wrong
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
}

?>
