<?php

include('config.php');

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

if(isset($_GET['action']) && $_GET['action'] == 'add_to_cart' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    
    $quantity = 1;

    $sql_insert_cart = "INSERT INTO cart (product_id, quantity) VALUES ($product_id, $quantity)";
    if ($conn->query($sql_insert_cart) === TRUE) {
        header('Location: cart.php');
        exit(); 
    } else {
        echo "Error: " . $sql_insert_cart . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = array();
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    $products = array();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale-1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rento - Products Page</title> 
    <link rel="stylesheet" href="style.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.6/angular.min.js"></script>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Francois+One);

        * {
        box-sizing: border-box;
        }

        body{ font-family: system-ui; background-image: url(http://subtlepatterns.subtlepatterns.netdna-cdn.com/patterns/cream_dust.png) }

        img{ max-width: 100%; height: auto; }
        h1, h2, h3, h4, h5, h6 { letter-spacing: 0.05em; font-weight: 400; }
        h1{text-align:center;}

        #sticky{
            position: fixed;
            top: 0;
            width: 100%;
            height: 11%;
            left: 0;
            z-index: 1000;
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
        }
        .logo{
            width: 9%;
            height:61px;
            padding-bottom:8px;
            mar
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
        .container,.deals-container{
            margin: 0px 50px 50px 5%;
            width: 90%;
            text-align: center;
        }

        .wrap {
            width: 100%;
            margin: 140px 0px 0px 0px;
        }

        .center-container {
            display: flex;
            justify-content: center;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            max-width: 1200px;
            width: 100%;
            gap: 30px;
        }

        .more:link, .more:visited {
            -webkit-transition-duration: 0.2s;    
            transition-duration: 0.2s;    
            -webkit-transition-timing-function: ease-out;
            transition-timing-function: ease-out;
        }

        .show-base a.more,
        .show-base a.tocart {
            display: inline-block;
            text-decoration: none;
            padding: 7px 14px; 
            background: #000;
            color: #fff;
            text-transform: uppercase;
            border-radius: 5px;
            box-shadow: 0 0 1px #000;
            position: relative;
            margin-left:3px;
        }

        .show-base a.tocart {
            background-color: #070;
        }

        .show-base a.more:hover,
        .show-base a.tocart:hover {
            box-shadow: 0 0 5px #fff;
            background-color: #222;
        }

        .show-base a.tocart:hover {
            background-color: #292;
        }

        .product-box {
            margin-bottom: 20px;
            width: calc(33.33% - 20px);
            margin-right: 0px;
            float: center;
        }

        .product-box .mask .old {
            font-size: 0.8em;
            padding: 0;
            margin: 0;
            line-height: 0.8em;
            color: red;
            text-decoration: line-through;
        }
        .product-box .mask .old + p {
            line-height: 0.5em;
        }

        @media (min-width: 950px) {
        .product-box {
            padding: 5px;
            min-height: 250px;
            max-height: 250px;
            height: 250px;
            min-width: 200px;
        }

        .product-box .description {
            display: none;
        }

        .show-base {
            width: 100%;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
            position: relative;
            text-align: center;
            cursor: default;
            background: #fff;
            display: block;
            border-radius: 4px;
        }
        
        .show-base .mask {
            width: 100%;
            height: 100%;
            position: absolute;
            overflow: hidden;
            top: 0;
            left: 0
        }
        
        .show-base p{
            font-size: 28px; 
        }
        
        .show-base .mask {
            opacity: 0;
            background-image: url(https://subtlepatterns.com/patterns/gplaypattern.png);
            background-color: rgba(255,255,255, 0.5);
            -webkit-transition: all 0.4s ease-in-out;
            transition: all 0.4s ease-in-out;
        }
        
        .show-base h2 {
            color: #000;
            margin-top: 5%;
            padding: 0 5px;
            opacity: 0;
            -webkit-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }
        
        .show-base p {
            opacity: 0;
            -webkit-transition: all 0.2s linear;
            transition: all 0.2s linear;
        }
        
        .show-base a.info{
            opacity: 0;
            -webkit-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }
        
        .show-base img {
            -webkit-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }
        
        .show-base:hover img {
            filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
            filter: gray;
            -webkit-filter: grayscale(100%);
        }
        
        .show-base:hover .mask {
            opacity: 1;
            
        }
        .show-base:hover h2,
        .show-base:hover p,
        .show-base:hover a.info {
            opacity: 1;
        }
        .show-base:hover p {
            -webkit-transition-delay: 0.1s;
            transition-delay: 0.1s;
        }
        .show-base:hover a.info {
            -webkit-transition-delay: 0.2s;
            transition-delay: 0.2s;
        }
        
        .product-box:hover .show-base {
            border-color: #122949;
        }
        .product-box:hover .title {
            opacity: 0.3;
        }
        }

        @media (max-width: 950px) {
        .products {
            width: 70%;
        }
        .product-box {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .product-box .title {
            display: none;
        }
        .product-box img {
            width: auto;
            height: auto;
            object-fit: cover;
        }
        .product-box .mask p {
            display: inline;
        }
        .product-box .description {
            margin-bottom: 20px;
        }
        }

        @media (max-width: 590px) {
        .products {
            width: 60%;
        }
        .product-box {
            text-align: center;
        }
        .product-box .title {
            display: none;
        }
        .product-box img {
            margin-right: 10px;
            float: none;
        }
        .product-box .mask p {
            display: inline;
        }
        }
        footer{
            
            padding-top: 0;
            margin-top: 11%;
            /* font-family: cursive; */
            text-align: center;
            background-size: contain;
        }
        footer a{
            text-decoration: none;
            color:black;
        }
        .category-page h1{
            margin-top:-60px;
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
    
    <div class="wrap category-page">
        <h1>Available Cars/Buses</h1>
        <div class="center-container">
            <div class="products">
            <?php
                if (!empty($products)) {
                    foreach ($products as $product) {
                        echo '<div class="product-box">';
                        echo '<div class="show-base">';
                        echo '<img src="' . $product['image_url'] . '" alt="' . $product['name'] . '" />';
                        echo '<div class="mask">';
                        echo '<h2>' . $product['name'] . '</h2>';
                        echo '<p>' . $product['price'] . '</p>';
                        echo '<div>';
                        echo '<a href="productDetails.php?product_id=' . $product['id'] . '" class="more">Details</a>';
                        echo '<a href="?action=add_to_cart&product_id=' . $product['id'] . '" class="tocart">Book Now</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="title" style="text-align: center; padding-top: 10pxF">' . $product['name'] . '</div>';
                        echo '</div>';
                    }
                    } else {
                        echo '<p>No products found</p>';
                    }
                ?>
            </div>
        </div>
    </div>

    <footer>
        <i>&copy;2024 Rento <a href="#">All Rights Reserved</a></i>
    </footer>
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
  


