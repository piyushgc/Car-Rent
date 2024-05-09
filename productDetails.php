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


$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if ($product_id) {

    $sql_product_details = "SELECT * FROM products WHERE id = $product_id";
    $result_product_details = $conn->query($sql_product_details);

    if ($result_product_details->num_rows > 0) {
        $product_details = $result_product_details->fetch_assoc();
    } else {
        echo "Product not found";
        exit;
    }
} else {
    echo "Product ID not provided";
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale-1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rento - Car Detail Page</title> 
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"><div class="pd-wrap">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        #sticky{
            position: fixed;
            top: 0;
            width: 100%;
            height: 11%;
            left: 0;
            z-index: 1000;
        }
        body{
            margin: 0;
            font-family: system-ui;
            /* background-color: gainsboro; */
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
        .containerr{
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
        
       
        .main{
            margin-top: 100px;
            height: max-content;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover; 
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
        .pd-wrap {
            padding: 40px 0;
            font-family: 'Poppins', sans-serif;
        }
        .heading-section {
            text-align: center;
            margin-bottom: 0px;
        }
        .sub-heading {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            display: block;
            font-weight: 600;
            color: #2e9ca1;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .heading-section h2 {
            font-size: 32px;
            font-weight: 500;
            padding-top: 10px;
            padding-bottom: 15px;
            font-family: 'Poppins', sans-serif;
        }
        .user-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: relative;
            min-width: 80px;
            background-size: 100%;
        }
        .carousel-testimonial .item {
            padding: 30px 10px;
        }
        .quote {
            position: absolute;
            top: -23px;
            color: #2e9da1;
            font-size: 27px;
        }
        .name {
            margin-bottom: 0;
            line-height: 14px;
            font-size: 17px;
            font-weight: 500;
        }
        .position {
            color: #adadad;
            font-size: 14px;
        }
        .owl-nav button {
            position: absolute;
            top: 50%;
            transform: translate(0, -50%);
            outline: none;
            height: 25px;
        }
        .owl-nav button svg {
            width: 25px;
            height: 25px;
        }
        .owl-nav button.owl-prev {
            left: 25px;
        }
        .owl-nav button.owl-next {
            right: 25px;
        }
        .owl-nav button span {
            font-size: 45px;
        }
        .product-thumb .item img {
            height: 100px;
        }
        .product-name {
            font-size: 22px;
            font-weight: 500;
            line-height: 22px;
            margin-bottom: 4px;
        }
        .product-price-discount {
            font-size: 22px;
            font-weight: 400;
            padding: 10px 0;
            clear: both;
        }
        .product-price-discount span.line-through {
            text-decoration: line-through;
            margin-left: 10px;
            font-size: 14px;
            vertical-align: middle;
            color: #a5a5a5;
        }
        .display-flex {
            display: flex;
        }
        .align-center {
            align-items: center;
        }
        .product-info {
            margin-top: 50px;
            width: 100%;
        }
        .product-dtl p {
            font-size: 14px;
            line-height: 24px;
            color: #7a7a7a;
        }
        .product-dtl .form-control {
            font-size: 15px;
        }
        .product-dtl label {
            line-height: 16px;
            font-size: 15px;
        }
        .form-control:focus {
            outline: none;
            box-shadow: none;
        }
        .product-count {
            margin-top: 50px; 
        }
        .product-count .qtyminus,
        .product-count .qtyplus {
            width: 34px;
            height: 34px;
            background: #212529;
            text-align: center;
            font-size: 19px;
            line-height: 36px;
            color: #fff;
            cursor: pointer;
        }
        .product-count .qtyminus {
            border-radius: 3px 0 0 3px; 
        }
        .product-count .qtyplus {
            border-radius: 0 3px 3px 0; 
        }
        .product-count .qty {
            width: 60px;
            text-align: center;
        }
        .round-black-btn {
            border-radius: 4px;
            background: #212529;
            color: #fff;
            padding: 7px 45px;
            display: inline-block;
            margin-top: 20px;
            border: solid 2px #212529; 
            transition: all 0.5s ease-in-out 0s;
        }
        .round-black-btn:hover,
        .round-black-btn:focus {
            background: transparent;
            color: #212529;
            text-decoration: none;
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
        <div class="menu" id="sticky">
            <div class="containerr">
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
        <div class="container">
            <div class="heading-section">
                <h2>Car Details</h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div id="slider" class="owl-carousel product-slider">
                        <div class="item">
                            <img src="<?php echo $product_details['image_url']; ?>" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="product-dtl">
                        <div class="product-info">
                            <div class="product-name"><?php echo $product_details['name']; ?></div>
                            <div class="product-price-discount">
                                <span>$<?php echo $product_details['price']; ?></span>
                                <br>
                                <p><?php echo $product_details['description']; ?></p>
                            </div>
                        </div>
                        <div class="product-count">
                            <form method="post" action="">
                                <!-- <label for="size">Quantity</label> -->
                                <!-- <div class="display-flex">
                                    <div class="qtyminus">-</div>
                                    <input type="text" name="quantity" value="1" class="qty">
                                    <div class="qtyplus">+</div>
                                </div> -->
                                <input type="hidden" name="product_id" value="<?php echo $product_details['id']; ?>">
                                <input type="hidden" name="update_quantity" value="1">
                                <a href="?action=add_to_cart&product_id=<?php echo $product_details['id']; ?>" class="round-black-btn">Book Now</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <i>&copy;2024 Rento <a href="#">All Rights Reserved</a></i>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="	sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <script id="rendered-js" >
        $(document).ready(function () {
        var slider = $("#slider");
        var thumb = $("#thumb");
        var slidesPerPage = 4;
        var syncedSecondary = true;
        slider.owlCarousel({
            items: 1,
            slideSpeed: 2000,
            nav: false,
            autoplay: false,
            dots: false,
            loop: true,
            responsiveRefreshRate: 200 }).
        on('changed.owl.carousel', syncPosition);
        thumb.
        on('initialized.owl.carousel', function () {
            thumb.find(".owl-item").eq(0).addClass("current");
        }).
        owlCarousel({
            items: slidesPerPage,
            dots: false,
            nav: true,
            item: 4,
            smartSpeed: 200,
            slideSpeed: 500,
            slideBy: slidesPerPage,
            navText: ['<svg width="18px" height="18px" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="25px" height="25px" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
            responsiveRefreshRate: 100 }).
        on('changed.owl.carousel', syncPosition2);
        function syncPosition(el) {
            var count = el.item.count - 1;
            var current = Math.round(el.item.index - el.item.count / 2 - .5);
            if (current < 0) {
            current = count;
            }
            if (current > count) {
            current = 0;
            }
            thumb.
            find(".owl-item").
            removeClass("current").
            eq(current).
            addClass("current");
            var onscreen = thumb.find('.owl-item.active').length - 1;
            var start = thumb.find('.owl-item.active').first().index();
            var end = thumb.find('.owl-item.active').last().index();
            if (current > end) {
            thumb.data('owl.carousel').to(current, 100, true);
            }
            if (current < start) {
            thumb.data('owl.carousel').to(current - onscreen, 100, true);
            }
        }
        function syncPosition2(el) {
            if (syncedSecondary) {
            var number = el.item.index;
            slider.data('owl.carousel').to(number, 100, true);
            }
        }
        thumb.on("click", ".owl-item", function (e) {
            e.preventDefault();
            var number = $(this).index();
            slider.data('owl.carousel').to(number, 300, true);
        });


        $(".qtyminus").on("click", function () {
            var now = $(".qty").val();
            if ($.isNumeric(now)) {
            if (parseInt(now) - 1 > 0)
            {now--;}
            $(".qty").val(now);
            }
        });
        $(".qtyplus").on("click", function () {
            var now = $(".qty").val();
            if ($.isNumeric(now)) {
            $(".qty").val(parseInt(now) + 1);
            }
        });
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
