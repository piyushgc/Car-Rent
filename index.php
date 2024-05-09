<?php
include('config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
            background-image: url(https://www.luzholidays.com/wp-content/uploads/2014/03/holiday-car-rental.jpg);
            height: max-content;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover; 
        }
        .page-title{
            padding: 50px;
            font-size: 100px;
            /* font-weight: bold; */
            letter-spacing: 10px;
            color: white;
            background: rgba(0,0, 0, 0.5);
            text-align:center;
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
            height:53px;
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
        .categories,.items{
            width: max-content;
            margin: 25px;
            display: inline-block;
            border: 1px solid #122949;
            border-radius: 17px;
            background-color:#ebebeb;
        }
        .categories:hover,.items:hover{
            box-shadow: 0 0 2px 2px #122949;
        }
        .item-image{
            height: 200px;
            width: 200px;
            border-radius: 18px;
            padding: 10px;
        }
        .image-title{
            text-align: center;
            padding: 10px;
            font-weight: bold;
            color: black;
        }
        a{
            text-decoration: none;
        }
        .title{
            padding: 50px;
            font-size: 50px;
            /* font-weight: bold; */
            letter-spacing: 10px;
            color: white;
            background: rgba(0,0, 0, 0.5);
        }
        .deal{
            padding: 50px;
            font-size: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 20px;
            max-width: 20%;
            background: linear-gradient(120deg, #62737f 20%,#688896 50%,#2f4544 80%);
        }
        .coupon-btn{
            border: 1px solid white;
            color: white;
            margin: 10px;
            padding: 10px;
            border-radius: 20px;
        }
        .coupon-btn:hover{
            box-shadow: 0 0 10px 5px white inset;
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
        .description{
            margin: 20px;
            min-height: 100px;
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
            border: 1px solid #122949;
            box-shadow: 0 0 10px 5px #122949 inset;
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
    <div class="main">
    <div class="page-title">BRANDS</div>
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
        <div class="container">
            <a href="categories.php?category_id=1">
                <div class="categories">
                    <img src="https://indiantreasurehouse.com/static/media/Swift.1bf5c113b83f48c58238.webp" alt="" class="item-image">
                    <div class="image-title">Suzuki</div>
                </div>
            </a>
            <a href="categories.php?category_id=2">
                <div class="categories">
                    <img src="https://indiantreasurehouse.com/static/media/Ethos.5e0f0d151044865d7e1e.webp" class="item-image">
                    <div class="image-title">Honda</div>
                </div>
            </a>
            <a href="categories.php?category_id=3">
                <div class="categories">
                    <img src="https://indiantreasurehouse.com/static/media/Innova.dbdd69205e70c4940514.webp" alt="" class="item-image">
                    <div class="image-title">Toyota </div>
                </div>
            </a>
            <a href="categories.php?category_id=4">
                <div class="categories">
                    <img src="https://www.team-bhp.com/forum/attachments/indian-car-scene/1468356d1454496306-hyundai-auto-expo-2016-27a.jpg" alt="" class="item-image">
                    <div class="image-title">Hyundai</div>
                </div>
            </a>
            <a href="categories.php?category_id=3">
                <div class="categories">
                    <img src="https://indiantreasurehouse.com/static/media/Innova.dbdd69205e70c4940514.webp" alt="" class="item-image">
                    <div class="image-title">Kia </div>
                </div>
            </a>
            <a href="categories.php?category_id=5">
                <div class="categories">
                    <img src="https://indiantreasurehouse.com/static/media/Bigbus.3b5209a179b4f9cd738c.webp" alt="" class="item-image">
                    <div class="image-title">Luxury Bus</div>
                </div>
            </a>
        </div>
        
        <div class="deals-container" id="vegetables">
            <div class="main">
                <div class="title">MOST ORDERED</div>
            </div>
            <?php
            if ($result_categories->num_rows > 0) {
                while ($row = $result_categories->fetch_assoc()) {
                    $category_id = $row['id'];
                    $sql_products = "SELECT * FROM products WHERE category_id = $category_id LIMIT 1";
                    $result_products = $conn->query($sql_products);
                    if ($result_products->num_rows > 0) {
                        $product = $result_products->fetch_assoc();
                        echo '<div class="items">';
                        echo '<div class="images">';
                        echo '<img src="' . $product['image_url'] . '" alt="' . $product['name'] . '" class="item-image-size">';
                        echo '</div>';
                        echo '<div class="description">';
                        echo '<b>' . $product['name'] . '</b><br>';
                        echo '<div class="item-select">';
                        echo 'Price : $' . $product['price'] . '/Day';
                        echo '</div>';
                        // echo '<label>Qty:</label>';
                        // echo '<select name="" id="" class="item-select">';
                        // echo '<option value="">1 Pack</option>';
                        // echo '<option value="">2 Pack</option>';
                        // echo '<option value="">3 Pack</option>';
                        echo '</select><br>';
                        echo '<a href="?action=add_to_cart&product_id=' . $product['id'] . '" class="buynow-btn">Book Now</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
            }
            ?>
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