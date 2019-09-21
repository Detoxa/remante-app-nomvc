<?php

header('Content-Type: application/json');

$conn = mysqli_connect('172.16.238.12', 'root', '', 'remante_app');
        mysqli_set_charset($conn, "utf8");
    if($conn->connect_error){
        die("Connection Failed!" . $conn->connect_error);
    }

$result = array('error'=>false);
$action = '';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action == 'read') {

    $sql = $conn->query("SELECT *,
                products.product_id as productId,
                products.product_name as productName,
                products.product_description as productDescription,
                products.product_price as productPrice,
                products.product_ean as productEan,
                brands.brand_name as brandName,
                categories.category_name as categoryName
                FROM products
                INNER JOIN brands
                ON products.brand_id = brands.brand_id
                INNER JOIN categories
                ON products.category_id = categories.category_id
                ORDER BY products.product_id ASC
                ");

    $products = array();
    while($row = $sql->fetch_assoc()){
        array_push($products, $row);
    }

    $result['products'] = $products;

    //var_dump($result);
}

if ($action == 'create') {
    
    $product_name = $_POST['product_name'];
    $product_des = $_POST['product_des'];
    $product_price = $_POST['product_price'];
    $product_ean = $_POST['product_ean'];
    $product_brand = $_POST['product_brand'];
    $product_category = $_POST['product_category'];


    $sql = $conn->query("INSERT INTO products (product_name,product_description,product_price,product_ean,brand_id,category_id) VALUES ('$product_name', '$product_des', '$product_price', '$product_ean', '$product_brand', '$product_category')");

    //var_dump($result);

    if($sql){
        $result['message'] = "Produkt přidán!";
    } else {
        $result['error'] = true;
        $result['message'] = "Asi vítr Máchale, něco je špatně!";
    }

}

if ($action == 'update') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_des = $_POST['product_des'];
    $product_price = $_POST['product_price'];
    $product_ean = $_POST['product_ean'];
    $product_brand = $_POST['product_brand'];
    $product_category = $_POST['product_category'];


    $sql = $conn->query("UPDATE products SET product_name = '$product_name', product_description = '$product_des',product_price = '$product_price',product_ean = '$product_ean',brand_id = '$product_brand',category_id = '$product_category' WHERE  product_id = '$product_id'");

    //var_dump($result);

    if($sql){
        $result['message'] = "Produkt editován!";
    } else {
        $result['error'] = true;
        $result['message'] = "Asi vítr Máchale, něco je špatně!";
    }

}

if ($action == 'delete') {
    $product_id = $_POST['product_id'];
    
    $sql = $conn->query("DELETE from products WHERE product_id = '$product_id'");

    //var_dump($result);

    if($sql){
        $result['message'] = "Produkt smazán!";
    } else {
        $result['error'] = true;
        $result['message'] = "Asi vítr Máchale, něco je špatně!";
    }

}

$conn->close();
echo json_encode($result);
