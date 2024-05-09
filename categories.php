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

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

$categories = array();
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}

$products = array();
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $sql_products = "SELECT * FROM products WHERE category_id = $category_id";
} else {
    $first_category = reset($categories);
    $category_id = $first_category['id'];
    $sql_products = "SELECT * FROM products WHERE category_id = $category_id";
}

$result_products = $conn->query($sql_products);

if ($result_products->num_rows > 0) {
    while ($row = $result_products->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Rento - Category Page</title> 
    <script src="https://kit.fontawesome.com/d6a8ce8d77.js" crossorigin="anonymous"></script>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Francois+One);

        * {
        box-sizing: border-box;
        }

        body{ font-family: system-ui; background-image: url(http://subtlepatterns.subtlepatterns.netdna-cdn.com/patterns/cream_dust.png);
         }

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
            background:#122949;
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
            height:59px;
            padding-bottom:8px;
            /* margin-left:50px; */
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
            /* font-size:normal; */
        }
        .menu-ul li:hover{
            /* background: #5b8c5a; */
            /* transform:scale(0.1) */
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

        .container{
            margin: 0px 50px 50px 5%;
            width: 90%;
            text-align: center;
        }

        .wrap {
            width: 90%;
            margin: 140px 0px 0px 0px;
        }

        .filters {
            float: left;
            width: 25%;
            margin-top: 10px;
        }

        .filters .filter-block {
            width: 95%;
            border: 1px solid #eee;
            margin-bottom: 15px;
        }

        .filters .filter-block:hover {
            border-color: #122949;
        }

        .filters .filter-block .filter {
            background-color: white;
            padding: 5px;
            border-bottom: 1px solid #eee;
        }

        .filters .filter-block:hover .filter {
            /* background-color: hsl(64, 58%, 46%); */
            color: white;
        }

        .filters .filter-block .property {
            padding: 5px;
            cursor: pointer;
        }

        .filters .filter-block .property:hover {
            background-color: #122949;
        }

        .filters .filter-block .property span {
        width: 80%;
        display: inline-block;
        overflow: hidden;
        }

        .filters .filter-block .property input[type='checkbox'] {
        float: right;
        }
        

        .products {
        float: left;
        width: 74%;
        padding-left: 1%;
        border-left: 1px dashed #ddd;
        margin-top: 10px;
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
            float: left;
            padding: 5px;
            min-height: 250px;
            max-height: 250px;
            height: 250px;
            min-width: 200px;
        }

        .product-box .description {
            display: none;
        }

        .product-box img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .show-base {
            width: 100%;
            height: 100%;
            float: left;
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
            border-color:#122949;
        }
        .product-box:hover .title {
            opacity: 0.3;
        }
        }

        @media (max-width: 950px) {
        .filters {
            width: 30%;
        }
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
            float: left;
            margin-right: 10px;
        }
        .product-box .mask p {
            display: inline;
        }
        .product-box .description {
            margin-bottom: 20px;
        }
        }

        @media (max-width: 590px) {
        .filters {
            width: 40%;
        }
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

        .fixed-sidebar {
            position: fixed;
            top: 140px;
            left: 50px;
            width: 65%;
            height: calc(100vh - 140px);
            overflow-y: auto;
            padding-top: 10px;
        }

        .scrollable-products {
            margin-left: 25%;
            padding-top: 10px;
            overflow-y: auto;
            height: calc(100vh - 140px);
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
        <div class="fixed-sidebar">
            <div class="filters">
                <?php
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        echo '<div class="filter-block">';
                        echo '<div class="filter"><a href="?category_id=' . $category['id'] . '" style="text-decoration: none;">' . $category['name'] . '</a></div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No categories found</p>';
                }
                ?>
            </div>
        </div>
        <div class="scrollable-products">
            <div class="products">
                <?php
                    if (!empty($products)) {
                        foreach ($products as $product) {
                            echo '<div class="product-box">';
                            echo '<div class="title">' . $product['name'] . '</div>';
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
  


