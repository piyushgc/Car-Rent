<?php
include('config.php');
session_start();

function getLastSearchedItems($conn) {
    $sql = "SELECT * FROM searched_items ORDER BY id DESC LIMIT 4";
    $result = $conn->query($sql);
    $items = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row['item_name'];
        }
    }
    return $items;
}

function calculateTotalPrice() {
    global $conn;
    $totalPrice = 0;

    $result = mysqli_query($conn, "SELECT * FROM cart");

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

            $product_sql = "SELECT * FROM products WHERE id = $product_id";
            $product_result = mysqli_query($conn, $product_sql);
            $product_row = mysqli_fetch_assoc($product_result);

            $totalPrice += $quantity * $product_row['price'];
        }
    } else {
        echo "Error fetching cart items: " . mysqli_error($conn);
    }

    return $totalPrice;
}

if (isset($_POST['checkout'])) {
    $totalPrice = calculateTotalPrice();
    $order_date = date('Y-m-d H:i:s');

    $insert_sql = "INSERT INTO orders (order_date, total_price, delivery_status) VALUES ('$order_date', $totalPrice, 'pending')";
    mysqli_query($conn, $insert_sql);

    $_SESSION['order_id'] = mysqli_insert_id($conn);

    header("Location: deliveryDetails.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['productId'])) {
        $productId = $_POST['productId'];

        // Remove the item from the cart table
        $delete_query = "DELETE FROM cart WHERE product_id = $productId";
        $result = mysqli_query($conn, $delete_query);

        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Failed to remove the item from the cart.']);
            exit;
        }
    }
}

$sql = "SELECT * FROM cart";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale-1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rento - Cart</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d6a8ce8d77.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <style>
        body{
            margin: 0;
            font-family: system-ui;
            /* background-color: gainsboro; */
        }
        #sticky{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 11%;
        }

        .dropdown {
            float: left;
            overflow: hidden;
        }

        .dropdown .dropbtn {
            font-size: 16px;  
            border: none;
            outline: none;
            color: white;
            padding: 26px 16px;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover, .dropdown:hover .dropbtn {
            background-color: red;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        .menu{
            background: #122949;
            text-align: center;
        }
        .container{
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: 0px 50px 50px 5%;
            width: 90%;
            text-align: center;
        }
        .logo{
            width: 9%;
            height:61px;
            padding-bottom:8px;
        }
        .menu-ul{
            padding: 0;
            margin: 0;
            display: inline;
        }
        .menu-ul li{
            font-size: 17px;
            display: inline-block;
            list-style: none;
            padding: 25px 25px 26px 25px;
        }
        .a-menu{
            text-decoration: none;
            color: white;
        }
        .menu-ul li:hover{
            /* background: #5b8c5a;
            transform:scale(1.1) */
            color:#c7d9f3;
        }
        .search-box{
            border: 1px solid white;
            border-radius: 50px;
            background: white;
            width: max-content;
            display: inline-block;
            margin: 8px;
        }
        .search-box:hover{
            box-shadow: 0 0 10px #122949;
            border: 1px solid #122949;
        }
        .search-input{
            width: 150px;
            border: none;
            font-size: 16px;
            background: transparent;
            outline: none;
            margin: 1px 0 0 10px;
        }
        i{
            /* font-size: 20px; */
            margin: 0 10px 0 0;
        }
        button{
            background: transparent;
            border: none;
            outline: none;
        }
        /* .container,.deals-container{
            margin: 0px 50px 50px 5%;
            width: 90%;
            text-align: center;
        } */
        .product-image {
        float: left;
        width: 20%;
        }

        .product-details {
        float: left;
        width: 37%;
        }

        .product-price {
        float: left;
        width: 12%;
        }

        .product-quantity {
        float: left;
        width: 10%;
        }

        .product-removal {
        float: left;
        width: 9%;
        }

        .product-line-price {
        float: left;
        width: 12%;
        text-align: right;
        }

        /* This is used as the traditional .clearfix class */
        .group:before, .shopping-cart:before, .column-labels:before, .product:before, .totals-item:before,
        .group:after,
        .shopping-cart:after,
        .column-labels:after,
        .product:after,
        .totals-item:after {
        content: '';
        display: table;
        }

        .group:after, .shopping-cart:after, .column-labels:after, .product:after, .totals-item:after {
        clear: both;
        }

        .group, .shopping-cart, .column-labels, .product, .totals-item {
        zoom: 1;
        }

        /* Apply clearfix in a few places */
        /* Apply dollar signs */
        .product .product-price:before, .product .product-line-price:before, .totals-value:before {
        content: '$';
        }

        /* Body/Header stuff */
        body {
        padding: 0px 30px 30px 20px;
        font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-weight: 100;
        }

        h1 {
        font-weight: 100;
        }

        label {
        color: #aaa;
        }

        .shopping-cart {
        margin-top: -45px;
        }

        /* Column headers */
        .column-labels label {
        padding-bottom: 15px;
        margin-bottom: 15px;
        border-bottom: 1px solid black;
        }
        .column-labels .product-image, .column-labels .product-details, .column-labels .product-removal {
        text-indent: -9999px;
        }

        /* Product entries */
        .product {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        }
        .product .product-image {
        text-align: center;
        }
        .product .product-image img {
        width: 100px;
        }
        .product .product-details .product-title {
        margin-right: 20px;
        font-family: "HelveticaNeue-Medium", "Helvetica Neue Medium";
        }
        .product .product-details .product-description {
        margin: 5px 20px 5px 0;
        line-height: 1.4em;
        }
        .product .product-quantity input {
        width: 40px;
        }
        .product .remove-product {
        border: 0;
        padding: 4px 8px;
        background-color: #c66;
        color: #fff;
        font-family: "HelveticaNeue-Medium", "Helvetica Neue Medium";
        font-size: 12px;
        border-radius: 3px;
        }
        .product .remove-product:hover {
        background-color: #a44;
        }

        /* Totals section */
        .totals .totals-item {
        float: right;
        clear: both;
        width: 100%;
        margin-bottom: 10px;
        }
        .totals .totals-item label {
        float: left;
        clear: both;
        width: 79%;
        text-align: right;
        }
        .totals .totals-item .totals-value {
        float: right;
        width: 21%;
        text-align: right;
        }
        .totals .totals-item-total {
        font-family: "HelveticaNeue-Medium", "Helvetica Neue Medium";
        }

        .checkout {
        float: right;
        border: 0;
        margin-top: 20px;
        padding: 6px 25px;
        background-color: #122949;
        color: #fff;
        font-size: 25px;
        border-radius: 3px;
        }

        .checkout:hover {
        background-color: #8e96a1;
        }

        /* Make adjustments for tablet */
        @media screen and (max-width: 650px) {
        .shopping-cart {
            margin: 0;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .column-labels {
            display: none;
        }

        .product-image {
            float: right;
            width: auto;
        }
        .product-image img {
            margin: 0 0 10px 10px;
        }

        .product-details {
            float: none;
            margin-bottom: 10px;
            width: auto;
        }

        .product-price {
            clear: both;
            width: 70px;
        }

        .product-quantity {
            width: 100px;
        }
        .product-quantity input {
            margin-left: 20px;
        }

        .product-quantity:before {
            content: 'x';
        }

        .product-removal {
            width: auto;
        }

        .product-line-price {
            float: right;
            width: 70px;
        }
        }
        @media screen and (max-width: 350px) {
        .product-removal {
            float: right;
        }

        .product-line-price {
            float: right;
            clear: left;
            width: auto;
            margin-top: 10px;
        }

        .product .product-line-price:before {
            content: 'Item Total: $';
        }

        .totals .totals-item label {
            width: 60%;
        }
        .totals .totals-item .totals-value {
            width: 40%;
        }
        }
        footer{
            padding-top: 0;
            margin-top: 20%;
            /* font-family: cursive; */
            text-align: center;
            margin-bottom:10px;
            background-size: contain;
        }
        .foot i{
            /* margin-bottom:0px; */
            padding: 100px;
        }
        footer a{
            text-decoration: none;
            color:black;
        }
    </style> 
</head>

<body translate="no">
    <div class="menu" id="sticky">
        <div class="container">
            <img src="images/images.png" class="logo">
            <ul class="menu-ul">
                <a href="./index.php" class="a-menu"><li>Home</li></a>
                <a href="./categories.php" class="a-menu"><li>Categories</li></a>
                <a href="./product.php" class="a-menu"><li>Products</li></a>
                <a href="./cart.php" class="a-menu"><li>Cart</li></a>
            </ul>
            <div class="search-box">
                <form action="searchResult.php" method="GET">
                    <input type="text" name="search" value="<?php echo isset($search) ? $search : ''; ?>" placeholder="Search" class="search-input" id="search-input">
                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
                <div class="dropdown-content" id="search-dropdown"></div>
            </div>
        </div>
    </div>
    <h1 style="margin-top: 150px">Booking Details</h1>
    <div class="shopping-cart">
        <div class="column-labels">
            <label class="product-image">Image</label>
            <label class="product-details">Product</label>
            <label class="product-price">Price/Day</label>
            <label class="product-quantity">Quantity</label>
            <label class="product-removal">Remove</label>
            <label class="product-line-price">Total</label>
        </div>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

            $product_sql = "SELECT * FROM products WHERE id = $product_id";
            $product_result = mysqli_query($conn, $product_sql);
            $product_row = mysqli_fetch_assoc($product_result);

        ?>
            <div class="product">
                <div class="product-image">
                    <img src="<?php echo $product_row['image_url']; ?>" />
                </div>
                <div class="product-details">
                    <div class="product-title"><?php echo $product_row['name']; ?></div>
                    <p class="product-description"><?php echo $product_row['description']; ?></p>
                </div>
                <div class="product-price"><?php echo $product_row['price']; ?></div>
                <div class="product-quantity">
                    <input type="number" value="<?php echo $quantity; ?>" min="1">
                </div>
                <div class="product-removal">
                <button class="remove-product" data-product-id="<?php echo $product_row['id']; ?>">Remove</button>
                </div>
                <div class="product-line-price"><?php echo $quantity * $product_row['price']; ?></div>
            </div>

        <?php
        }
        ?>

        <div class="totals">
            <div class="totals-item">
            <label>Subtotal</label>
            <div class="totals-value" id="cart-subtotal">71.97</div>
            </div>
            <div class="totals-item">
            <label>Tax (5%)</label>
            <div class="totals-value" id="cart-tax">3.60</div>
            </div>
            <div class="totals-item">
            <label>Security Amount</label>
            <div class="totals-value" id="cart-shipping">15.00</div>
            </div>
            <div class="totals-item totals-item-total">
            <label>Grand Total</label>
            <div class="totals-value" id="cart-total">90.57</div>
            </div>
        </div>
        
        <form method="POST">
            <button type="submit" name="checkout" class="checkout">Checkout</button>
        </form>

    </div>
    
    <footer  class='foot'>
        <i>&copy;2024 Rento <a href="#">All Rights Reserved</a></i>
    </footer>

    <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script id="rendered-js" >
        var taxRate = 0.05;
        var shippingRate = 15.00;
        var fadeTime = 300;

        $('.product-quantity input').change(function () {
        updateQuantity(this);
        });

        $('.product-removal button').click(function () {
        removeItem(this);
        });

        function recalculateCart()
        {
        var subtotal = 0;

        $('.product').each(function () {
            subtotal += parseFloat($(this).children('.product-line-price').text());
        });

        var tax = subtotal * taxRate;
        var shipping = subtotal > 0 ? shippingRate : 0;
        var total = subtotal + tax + shipping;

        $('.totals-value').fadeOut(fadeTime, function () {
            $('#cart-subtotal').html(subtotal.toFixed(2));
            $('#cart-tax').html(tax.toFixed(2));
            $('#cart-shipping').html(shipping.toFixed(2));
            $('#cart-total').html(total.toFixed(2));
            if (total == 0) {
            $('.checkout').fadeOut(fadeTime);
            } else {
            $('.checkout').fadeIn(fadeTime);
            }
            $('.totals-value').fadeIn(fadeTime);
        });
        }


        function updateQuantity(quantityInput)
        {

        var productRow = $(quantityInput).parent().parent();
        var price = parseFloat(productRow.children('.product-price').text());
        var quantity = $(quantityInput).val();
        var linePrice = price * quantity;

        productRow.children('.product-line-price').each(function () {
            $(this).fadeOut(fadeTime, function () {
            $(this).text(linePrice.toFixed(2));
            recalculateCart();
            $(this).fadeIn(fadeTime);
            });
        });
        }

        function removeItem(removeButton)
        {
        var productRow = $(removeButton).parent().parent();
        productRow.slideUp(fadeTime, function () {
            productRow.remove();
            recalculateCart();
        });
        }
    </script>
    <script>
        $(document).ready(function () {
            $('.remove-product').click(function () {
                var productId = $(this).data('product-id');
                
                $.ajax({
                    type: 'POST',
                    url: 'cart.php',
                    data: { productId: productId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $(this).closest('.product').remove();
                            recalculateCart();
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
            
            function recalculateCart() {
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('search-input').addEventListener('focus', function() {
                var searchDropdown = document.getElementById('search-dropdown');

                fetch('fetchLastSearches.php')
                    .then(response => response.json())
                    .then(data => {
                        searchDropdown.innerHTML = '';
                        data.forEach(function(searchItem) {
                            var dropdownItem = document.createElement('a');
                            dropdownItem.href = 'searchResult.php?search=' + encodeURIComponent(searchItem.item_name);
                            dropdownItem.textContent = searchItem.item_name;
                            searchDropdown.appendChild(dropdownItem);
                        });
                        searchDropdown.style.display = 'block';
                    })
                    .catch(error => console.error('Error fetching last searches:', error));
            });

            document.addEventListener('click', function(event) {
                var searchDropdown = document.getElementById('search-dropdown');
                var searchInput = document.getElementById('search-input');

                if (!searchInput.contains(event.target) && !searchDropdown.contains(event.target)) {
                    searchDropdown.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
