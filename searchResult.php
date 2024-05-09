<?php
include('config.php');

$search = $_GET['search'];

function insertSearchedItem($conn, $itemName) {
    $itemName = mysqli_real_escape_string($conn, $itemName);
    $sql = "INSERT INTO searched_items (item_name) VALUES ('$itemName')";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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


$sql_products = "SELECT * FROM products WHERE name LIKE '%$search%' OR category_id IN (SELECT id FROM categories WHERE name LIKE '%$search%')";

$result_products = $conn->query($sql_products);

insertSearchedItem($conn, $search);

?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale-1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rento</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d6a8ce8d77.js" crossorigin="anonymous"></script>
    <style>
        body{
            margin: 0;
            font-family: system-ui;
            /* background-color: gainsboro; */
        }
        .main{
            /* background-image: url(https://images.pexels.com/photos/19923012/pexels-photo-19923012/free-photo-of-dacia-duster-on-dirt-road.jpeg?auto=compress&cs=tinysrgb&w=600); */
            height: max-content;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover; 
            margin-top:10%;
        }
        .page-title{
            background: rgba(0,0, 0, 0.5);
            text-align: center;
            padding: 50px;
            color: white;
            font-family: fantasy;
            font-size: 100px;
            letter-spacing: 20px;
            font-variant: small-caps;
        }
        #sticky{
            position: fixed;
            top: 0;
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
            box-shadow: 0 0 10px #5b8c5a;
            border: 1px solid #5b8c5a;
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
            margin: 0px 50px 0px 5%;
            width: 90%;
            text-align: center;
        }
        .categories,.items{
            width: max-content;
            height: 315px;
            margin: 25px;
            display: inline-block;
            border: 1px solid #184d47;
            border-radius: 17px;
            background-color:#ebebeb;
        }
        .categories:hover,.items:hover{
            box-shadow: 0 0 5px 5px #122949;
        }
        .item-image{
            height: 200px;
            width: 200px;
            border-radius: 10px;
            padding: 10px;
        }
        .image-title{
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }
        a{
            text-decoration: none;
        }
        .images{
            display: inline-block;
        }
        .item-image-size{
            height: 200px;
            width: 300px;
            padding: 5px;
            border-radius:17px
        }
        .item-select{
            margin: 10px 0 0 0;
        }
        .buynow-btn{
            border: 1px solid #122949;
            border-radius: 20px;
            background: #122949;
            color: white;
            font-weight: bold;
            padding: 5px 15px;
            margin-top: 10px;
        }
        .buynow-btn:hover{
            border: 1px solid #122942;
            box-shadow: 0 0 10px 5px #122949 inset;
        }
        footer{
            
            padding-top: 0;
            margin-top: 6%;
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
    <div class="main">
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
    </div>

        <div class="deals-container" id="search-results">
            <!-- <div class="page-title">Search Results</div> -->
            <div class="main">
                <?php
                if ($result_products->num_rows > 0) {
                    while ($product = $result_products->fetch_assoc()) {
                        echo '<div class="items">';
                        echo '<div class="images">';
                        echo '<img src="' . $product['image_url'] . '" alt="' . $product['name'] . '" class="item-image-size">';
                        echo '</div>';
                        echo '<div class="description">';
                        echo '<b>' . $product['name'] . '</b><br>';
                        // echo '<div class="item-select">';
                        echo 'Price: $' . $product['price']. '/Day';
                        // echo '</div>';
                        // echo '<label>Qty:</label>';
                        // echo '<select name="" id="" class="item-select">';
                        // echo '<option value="">1 Pack</option>';
                        // echo '<option value="">2 Pack</option>';
                        // echo '<option value="">3 Pack</option>';
                        echo '</select><br>';
                        echo '<br>';
                        echo '<a href="?action=add_to_cart&product_id=' . $product['id'] . '" class="buynow-btn">Book Now</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No results found.</p>';
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