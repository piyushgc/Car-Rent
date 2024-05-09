<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale-1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rento - Order Placed</title>
    <!-- <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d6a8ce8d77.js" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
       #card {
  position: relative;
  top: 110px;
  width: 320px;
  display: block;
  margin: auto;
  text-align: center;
  font-family: 'Source Sans Pro', sans-serif;
}

#upper-side {
  padding: 2em;
  background-color: #8BC34A;
  display: block;
  color: #fff;
  border-top-right-radius: 8px;
  border-top-left-radius: 8px;
}

#checkmark {
  font-weight: lighter;
  fill: #fff;
  margin: -3.5em auto auto 20px;
}

#status {
  font-weight: lighter;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: 1em;
  margin-top: -.2em;
  margin-bottom: 0;
}

#lower-side {
  padding: 2em 2em 5em 2em;
  background: #fff;
  display: block;
  border-bottom-right-radius: 8px;
  border-bottom-left-radius: 8px;
}

#message {
  margin-top: -.5em;
  color: #757575;
  letter-spacing: 1px;
}

#contBtn {
  position: relative;
  top: 1.5em;
  text-decoration: none;
  background: #8bc34a;
  color: #fff;
  margin: auto;
  padding: .8em 3em;
  -webkit-box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.21);
  -moz-box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.21);
  box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.21);
  border-radius: 25px;
  -webkit-transition: all .4s ease;
		-moz-transition: all .4s ease;
		-o-transition: all .4s ease;
		transition: all .4s ease;
}

#contBtn:hover {
  -webkit-box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.41);
  -moz-box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.41);
  box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.41);
  -webkit-transition: all .4s ease;
		-moz-transition: all .4s ease;
		-o-transition: all .4s ease;
		transition: all .4s ease;
}

        body{
            margin: 0;
            font-family: system-ui;
            /* background-color: gainsboro; */
             text-align: center;
            padding: 40px 0;
            background: #EBF0F5;
        }
        .h1 {
            color: #88B04B;
            font-family: fantasy;
            font-weight: 900;
            font-size: 40px;
            margin-bottom: 10px;
        }
        .p {
            color: #122949;
            font-family: fantasy;
            font-size:20px;
            margin: 0;
        }
        .i {
            color: #9ABC66;
            font-size: 100px;
            line-height: 200px;
            margin-left:-15px;
        }
        .main{
            height: max-content;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover; 
            z-index: 1;
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
            z-index: 1000;
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
            margin-left:46px;
            margin-top:5px;
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
            box-shadow: 0 0 10px #122949;;
            border: 1px solid #122949;;
        }
        .search-input{
            width: 150px;
            border: none;
            font-size: 16px;
            background: transparent;
            outline: none;
            margin: 1px 0 0 10px;
        }
        /* .checkmark{
            font-size: 120px;
            margin: 0 10px 0 0;
        } */
        button{
            background: transparent;
            border: none;
            outline: none;
        }
        
        footer{
            padding-top: 0;
            margin-top: 15%; 
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
<body>
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
<div id='card' class="animated fadeIn">
  <div id='upper-side'>
    <?xml version="1.0" encoding="utf-8"?>
      <!-- Generator: Adobe Illustrator 17.1.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
      <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
      <svg version="1.1" id="checkmark" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" xml:space="preserve">
        <path d="M131.583,92.152l-0.026-0.041c-0.713-1.118-2.197-1.447-3.316-0.734l-31.782,20.257l-4.74-12.65
	c-0.483-1.29-1.882-1.958-3.124-1.493l-0.045,0.017c-1.242,0.465-1.857,1.888-1.374,3.178l5.763,15.382
	c0.131,0.351,0.334,0.65,0.579,0.898c0.028,0.029,0.06,0.052,0.089,0.08c0.08,0.073,0.159,0.147,0.246,0.209
	c0.071,0.051,0.147,0.091,0.222,0.133c0.058,0.033,0.115,0.069,0.175,0.097c0.081,0.037,0.165,0.063,0.249,0.091
	c0.065,0.022,0.128,0.047,0.195,0.063c0.079,0.019,0.159,0.026,0.239,0.037c0.074,0.01,0.147,0.024,0.221,0.027
	c0.097,0.004,0.194-0.006,0.292-0.014c0.055-0.005,0.109-0.003,0.163-0.012c0.323-0.048,0.641-0.16,0.933-0.346l34.305-21.865
	C131.967,94.755,132.296,93.271,131.583,92.152z" />
        <circle fill="none" stroke="#ffffff" stroke-width="5" stroke-miterlimit="10" cx="109.486" cy="104.353" r="32.53" />
      </svg>
      <h3 id='status'>
      Success
    </h3>
  </div>
  <div id='lower-side'>
    <p id='message'>
      Booking Confirmed, We've sent a details to your e-mail.
    </p>
    <a href="#" id="contBtn">Continue</a>
  </div>
</div>
    <footer>
        <i>&copy;2024 Rento <a href="#">All Rights Reserved</a></i>
    </footer>
</body>
</html>
