<?php
session_start();

include('config.php');

require 'vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

$mail->isSMTP();

if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $mobile_number = $_POST['mobile_number'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $address_state = $_POST['address_state'];
    $street_address = $_POST['street_address'];

    if(isset($_SESSION['order_id'])) {
        $order_id = $_SESSION['order_id'];

        $insert_query = "INSERT INTO delivery_details (order_id, firstname, lastname, mobile_number, email, city, address_state, street_address) VALUES ('$order_id', '$firstname', '$lastname', '$mobile_number', '$email', '$city', '$address_state', '$street_address')";
        mysqli_query($conn, $insert_query);

        $empty_cart_sql = "TRUNCATE TABLE cart";
        mysqli_query($conn, $empty_cart_sql);

        unset($_SESSION['cart']);

        $orderDetails = "Order ID: $order_id<br>";
        $orderDetails .= "Name: $firstname $lastname<br>";
        $orderDetails .= "Email: $email<br>";
        $orderDetails .= "Mobile Number: $mobile_number<br>";
        $orderDetails .= "City: $city<br>";
        $orderDetails .= "State: $address_state<br>";
        $orderDetails .= "Street Address: $street_address<br>";

        try {
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'example@example.com';
            $mail->Password = 'password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
        
            $mail->setFrom('homego@info.com', 'HomeGo');
            $mail->addAddress($email, $firstname);
            $mail->Subject = 'Order Confirmation';
            $mail->isHTML(true);
            $mail->Body = 'Dear $firstname'.$firstname.',<br><br>Your order has been successfully placed. Your order ID is '.$order_id.'.<br><br>Thank you for shopping with us!';
        
            echo "Email sent successfully to: " . $email . "<br>";
            echo "Order Details:<br>";
            echo $orderDetails;
            
            $mail->send();
            echo "Email sent successfully to: " . $email;
        
            header("Location: orderConfirmation.php");
            exit();
        } catch (Exception $e) {
            echo "Failed to send email. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: 'order_id' is not provided in the session.";
    }
}
?>


<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale-1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rento- Booking Details</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d6a8ce8d77.js" crossorigin="anonymous"></script>
    <style>
        @import url("https://rsms.me/inter/inter.css");

        :root {
            --color-gray: #737888;
            --color-lighter-gray: #e3e5ed;
            --color-light-gray: #f7f7fa;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        html {
            font-family: "Inter", sans-serif;
            font-size: 14px;
            box-sizing: border-box;
        }

        @supports (font-variation-settings: normal) {
        html {
            font-family: "Inter var", sans-serif;
        }
        }

        body {
            margin: 0;
        }

        h1 {
            margin-bottom: 1rem;
        }

        p {
            color: var(--color-gray);
        }

        hr {
            height: 1px;
            width: 100%;
            background-color: #2b2b2d;
            border: 0;
            margin: 2rem 0;
        }

        .main{
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
        #sticky{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 11%;
        }
        .menu{
            background:  #122949;
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
            height:70px;
            padding-bottom:8px;
            margin-left: 50px;
    /* margin-top: 0px; */
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

        .container {
            max-width: 45rem;
            padding: 44px 2rem 0;
            margin: 0 auto;
            height: 115vh;
            background-color:#f6f6f6;
            margin-top:8%;
            border-radius:1rem;

        }

        .form {
            display: grid;
            grid-gap: 1rem;
        }

        .field {
            width: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #122949;
            padding: .5rem;
            border-radius: .25rem;
        }

        .field__label {
            color: #4b4b4c;;
            font-size: 0.6rem;
            font-weight: 300;
            text-transform: uppercase;
            margin-bottom: 0.25rem
        }

        .field__input {
            padding: 0;
            margin: 0;
            border: 0;
            outline: 0;
            font-weight: bold;
            font-size: 1rem;
            width: 100%;
            -webkit-appearance: none;
            appearance: none;
            background-color: transparent;
        }
        .field:focus-within {
            border-color: #000;
        }

        .fields {
            display: grid;
            grid-gap: 1rem;
        }
        .fields--2 {
            grid-template-columns: 1fr 1fr;
        }
        .fields--3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .button {
            background-color: #122949;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 600;
            display: block;
            color: #fff;
            width: 100%;
            padding: 1rem;
            border-radius: 0.25rem;
            border: 0;
            cursor: pointer;
            outline: 0;
        }
        .button:focus-visible {
            background-color: #333;
        }
        footer{
            padding-top: 0;
            margin-top: 8%;
            /* font-family: cursive; */
            text-align: center;
            background-size: contain;
        }
        footer a{
            text-decoration: none;
            color:black;
        }

        h1{
            color:#122949;
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
            </div>
        </div>
        <div class="container">
            <h1>Personal Details</h1>
            <!-- <p>Please enter your details.</p> -->
            <!-- <hr> -->
            <form method="POST" action="deliveryDetails.php">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <div class="form">
                    <div class="fields fields--2">
                        <label class="field">
                            <span class="field__label" for="firstname">First name</span>
                            <input class="field__input" type="text" id="firstname" name="firstname" />
                        </label>
                        <label class="field">
                            <span class="field__label" for="lastname">Last name</span>
                            <input class="field__input" type="text" id="lastname" name="lastname" />
                        </label>
                    </div>
                    <div class= 'fields fields--2'>
                    <label class="field">
                        <span class="field__label" for="mobile_number">Mobile Number</span>
                        <input class="field__input" type="text" id="mobile_number" name="mobile_number" />
                    </label>
                    <label class="field">
                            <span class="field__label" for="email">Email</span>
                            <input class="field__input" type="email" id="email" name="email" required />
                        </label>
                    </div>
                    
                    <!-- <hr> -->
                    <h1>Booking Details</h1>
                    
                    <div class="fields fields--2">
                        <label class="field">
                            <span class="field__label" for="city">City/Suburb</span>
                            <input class="field__input" type="text" id="city" name="city" />
                        </label>
                        <label class="field">
                            <span class="field__label" for="address_state">State</span>
                            <select class="field__input" id="address_state" name="address_state">
                                <option value="NSW">New South Wales</option>
                                <option value="VIC">Victoria</option>
                                <option value="QLD">Queensland</option>
                                <option value="WA">Western Australia</option>
                                <option value="SA">South Australia</option>
                                <option value="TAS">Tasmania</option>
                                <option value="ACT">Australian Capital Territory</option>
                                <option value="NT">Northern Territory</option>
                                <option value="Others">Others</option>
                            </select>
                        </label>
                    </div>
                    
                    <label class="field">
                        <span class="field__label" for="street_address">Address</span>
                        <input class="field__input" type="text" id="street_address" name="street_address" />
                    </label>
                    <div class="fields fields--2">
                        <label class="field">
                            <span class="field__label" for="start_date">Start Date</span>
                            <input type="date"  id="start_date" name="start_date" required>
                        </label>
                        <label class="field">
                            <span class="field__label" for="end_date">End Date</span>
                            <input type="date"  id="end_date" name="end_date" required>
                        </label>
                    </div>
                    <div class="fields fields--2">
                    <label class="field">
                            <span class="field__label" for="id_type">ID Type</span>
                            <select class="field__input" id="id_type" name="id_type">
                                <option value="NSW">Passport</option>
                                <option value="VIC">Govt. Id</option>
                                <option value="QLD">Voter Id</option>
                                <option value="QLD">Driving Lic.</option>
                            </select>
                        </label>

                        <label class="field">
                        <span class="field__label" for="id_number">ID Number</span>
                        <input class="field__input" type="text" id="id_number" name="id_number" />
                    </label>
                    
                    </div>

                </div>
                <hr>
                <button type="submit" class="button" name="submit">Continue for Payment</button>
            </form>
        </div>
    </div>

    <footer>
        <i>&copy;2024 Rento <a href="#">All Rights Reserved</a></i>
    </footer>
    <script>
        document.getElementById('email').addEventListener('input', function() {
            var emailInput = document.getElementById('email');
            var emailError = document.getElementById('email-error');
            var email = emailInput.value;

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                emailError.textContent = 'Please enter a valid email address.';
                emailInput.classList.add('error');
            } else {
                emailError.textContent = '';
                emailInput.classList.remove('error');
            }
        });
    </script>
</body>
</html>
