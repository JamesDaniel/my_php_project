<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>Graphic Novels</title>
    <meta charset='utf-8'>
    <meta name='description' content='Read and write some of the most creative stories on the web.'>
    <meta name='keywords' content='comic, comics, graphic novel, novel, online'>
    <meta name='author' content='James McGarr'>                                                  <!--Create a favicon for this-->
    <link href='/favicon.png'   rel='shortcut icon' type='image/png'> 
    <link href='newTags.css'    rel='stylesheet'    type='text/css'>                             <!--Normalising css style attributes       -->
    <link href='main.css'       rel='stylesheet'    type='text/css'>                  
    <link href='nav.css'        rel='stylesheet'    type='text/css'>
    <link href='content.css'    rel='stylesheet'    type='text/css'>
    <link href='responsive.css' rel='stylesheet'    type='text/css'>
    <script type="text/javascript" src="cookieMonster.js"></script>
                                                                                                 <!--Creating new html tag for older brouser-->
                                                                                                 <!--Find out if new tags need to be created for old brousers-->
    <!--[if lt IE 9]> 
        <script type="text/javascript"> document.createElement("stack"); </script>               
    <![endif]-->
    
  <?php
  
  
  
  $databaseConnection = mysqli_connect("localhost", "root", "", "booksys");
  if (mysqli_connect_errno($databaseConnection)) { echo "Failed to connect to database.".mysqli_connect_error();}
  
  
  
  if (isset($_POST['submitlogin']))
  {      
      $memberEmail = $_POST['usrName']; $memberPass = $_POST['usrPass'];
      
      
      $sqlQuery    = "SELECT * 
                      FROM members
                      WHERE '$memberEmail' = eMail AND
                            '$memberPass'  = password";     /* I'd have to do it a different way to strip slashes but this works for now */
      $resultSet   = mysqli_query($databaseConnection,$sqlQuery);
      if (!$resultSet)
      {
          echo('Query failed ' . $sqlQuery . ' Error: ' . mysqli_error());
          exit();
      }
      else
      {
          if (mysqli_num_rows($resultSet) < 1)
          {
              echo "<script>alert('Invalid username password combination.');</script>";
          }
          else
          {                             
              echo "<script>setCookie('usrEmail', '$memberEmail', 1);</script>";
          }
      }
                                           
      
      
  }
  else if (isset($_POST['submitcreateaccount']))
  {
      
      $fName       = $_POST['fName'   ]; $lName    = $_POST['lName'   ];
      $email       = $_POST['email'   ]; $pass     = $_POST['pass1'   ];
      $address1    = $_POST['address1']; $address2 = $_POST['address2'];
      $city        = $_POST['city'    ]; $county   = $_POST['county'  ];
      $phonenumber = $_POST['phone'   ];
       
      $fName       = mysqli_real_escape_string($databaseConnection, $fName);
      $lName       = mysqli_real_escape_string($databaseConnection, $lName);
      $email       = mysqli_real_escape_string($databaseConnection, $email);
      $pass        = mysqli_real_escape_string($databaseConnection, $pass );
      $address1    = mysqli_real_escape_string($databaseConnection, $address1);
      $address2    = mysqli_real_escape_string($databaseConnection, $address2);
      $city        = mysqli_real_escape_string($databaseConnection, $city);
      $county      = mysqli_real_escape_string($databaseConnection, $county);
      $phonenumber = mysqli_real_escape_string($databaseConnection, $phonenumber);
      
      $sqlQuery = "INSERT INTO members(fName,lName,eMail,password,address1,address2,city,county,phoneNum,credits)
                   VALUES ('$fName','$lName','$email','$pass','$address1','$address2','$city','$county','$phonenumber',0.0)";
      $resultSet = mysqli_query($databaseConnection, $sqlQuery);
      if ($resultSet == 0)
      {
          echo "Error in sql query ". mysqli_error($databaseConnection);
      }              
      else
      {
          echo "<script type='text/javascript'> alert('Account Creation Successful!');</script>";
      }               
  }
  else if (isset($_POST['deleteAccount']))
  {
      $email = $_POST['usrEmail'];
      
      
      
      $MemID='';
      $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                     FROM members
                                                     WHERE Email = '$email'");
      while ($memberInfo = mysqli_fetch_array($resultSet))
      {
          $MemID = $memberInfo['MemID']; 
      }
      $orderID='';
      $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                     FROM orders
                                                     WHERE MemID = '$MemID'");
      if (!$resultSet)
      {
          echo('Query failed , Error: ' . mysqli_error($databaseConnection));
          exit();
      }                                                     
      while ($orderInfo = mysqli_fetch_array($resultSet))
      {
          $orderID = $orderInfo['orderID']; 
      }
      
                  echo "<script>alert('hi');</script>";
      
      mysqli_query($databaseConnection,"DELETE FROM basket
                                        WHERE MemID = $MemID");
      mysqli_query($databaseConnection,"DELETE FROM orderitems
                                        WHERE orderID = $orderID");
      mysqli_query($databaseConnection,"DELETE FROM orders
                                        WHERE MemID   = $MemID");
      mysqli_query($databaseConnection,"DELETE FROM members
                                        WHERE MemID   = $MemID");
                                        
  }
  else if (isset($_POST['submitChangeAccountDetails']))
  {
      $theEmail = $_POST['usrEmail'];
      
      $fName       = $_POST['fName'   ]; $lName    = $_POST['lName'   ];
      $email       = $_POST['email'   ]; $pass     = $_POST['pass1'   ];
      $address1    = $_POST['address1']; $address2 = $_POST['address2'];
      $city        = $_POST['city'    ]; $county   = $_POST['county'  ];
      $phonenumber = $_POST['phone'   ];
       
      $fName       = mysqli_real_escape_string($databaseConnection, $fName);
      $lName       = mysqli_real_escape_string($databaseConnection, $lName);
      $email       = mysqli_real_escape_string($databaseConnection, $email);
      $pass        = mysqli_real_escape_string($databaseConnection, $pass );
      $address1    = mysqli_real_escape_string($databaseConnection, $address1);
      $address2    = mysqli_real_escape_string($databaseConnection, $address2);
      $city        = mysqli_real_escape_string($databaseConnection, $city);
      $county      = mysqli_real_escape_string($databaseConnection, $county);
      $phonenumber = mysqli_real_escape_string($databaseConnection, $phonenumber);
      
      $sqlQuery = "UPDATE members 
                   SET fName    = '$fName',
                       lName    = '$lName',
                       eMail    = '$email',
                       password = '$pass',
                       address1 = '$address1',
                       address2 = '$address2',
                       city     = '$city',
                       county   = '$county',
                       phoneNum = '$phonenumber'
                   WHERE eMail  = '$theEmail'";
                   
      $resultSet = mysqli_query($databaseConnection, $sqlQuery);
      if ($resultSet == 0)
      {
          echo "Error in sql query ". mysqli_error($databaseConnection);
      }              
      else
      {
          echo "<script type='text/javascript'> 
                    alert('Account Details Changed');
                    setCookie('usrEmail', '$email', 1);
                    document.getElementById('welcomeMessage').innerHTML = 'Welcome ' + getCookie('usrEmail');
                </script>";
      }    
  }
  
  ?>
  
  
  
  
  
  <style type="text/css">
  form
  {
  margin:20px;
  }
  div.input
  {
  width:150px;
  display:inline-block;
  }
  p#welcomeMessage
  {
  margin:20px;
  }
  </style>
  <script type="text/javascript">
  function checkLogin()
  {
      if (checkCookie() == 1)
      {
          document.getElementById('welcomeMessage').innerHTML = 'Welcome ' + getCookie('usrEmail');   
          document.getElementById('useremail2').value      = getCookie('usrEmail');
          document.getElementById('useremail').value      = getCookie('usrEmail');
          document.forms['firstForm'].submit();
      }
  }
  
  </script>
  
  </head>
  <body onload="checkLogin()">
      <nav>
          <ul class="headerMenu">
              <a class="headerMenu" href="index.php"><li class="headerMenu">Home - All Books</li></a>
              <a class="headerMenu" href="basket.php"><li class="headerMenu">Basket</li></a>
              <a class="headerMenu" href="checkout.php"><li class="headerMenu">Checkout</li></a>
              <a class="headerMenu" href="myaccount.php"><li class="headerMenu">My Account</li></a>  
          </ul>
      </nav>   
      <contentArea>   
        <div class="spacer">
          <div style="border:3px solid #666666; border-radius:20px; background-color:#CFCFCF;">
          <p id="welcomeMessage"></p>
                 <hr>   
          <?php
                    
              if (isset($_POST['usrEmail']))
              {                          
              $email = $_POST['usrEmail'];             
              
              $MemID = '';
              $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                             FROM members
                                                             WHERE Email = '$email'");
              while ($memberInfo = mysqli_fetch_array($resultSet))
              {
                  $MemID = $memberInfo['MemID']; 
              }
              $resultSet = mysqli_query($databaseConnection, "SELECT * 
                                                              FROM books
                                                              RIGHT JOIN basket
                                                              ON basket.bookID = books.bookID 
                                                              WHERE MemID = '$MemID'");
              echo "<table style='margin:20px;' border='1'>
                     <tr>
                      <th> Book Title </th>
                      <th> Author     </th>
                      <th> Cost    </th>
                      <th> Total Cost </th>
                     </tr>";
              $totalCost=0;                                                                
              while ($bookInfo = mysqli_fetch_array($resultSet))
              {
                  $bookName  = stripslashes($bookInfo['bookName']);
                  $author    = stripslashes($bookInfo['author']);
                  $cost      = stripslashes($bookInfo['cost']);
                  $totalCost += (double)$cost;
                  $quantity  = $bookInfo['quantity'];
                  $newQuantity = $quantity-1;
                  
                  mysqli_query($databaseConnection,"UPDATE books
                                                    SET quantity = $newQuantity");  
                  echo "<tr>
                         <td>$bookName</td>
                         <td>$author</td>
                         <td>$cost</td>
                         <td></td>
                        </tr>";
              }
              
              echo "<tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>$totalCost</td></table>";
                      
                      
              if (isset($_POST['buyBooks']))                 /*                   I need to create this form      */
              {   
                  $email = $_POST['usrEmail'];             
                  $MemID = '';
                  $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                                 FROM members
                                                                 WHERE Email = '$email'");
                  while ($memberInfo = mysqli_fetch_array($resultSet))
                  {
                      $MemID = $memberInfo['MemID']; 
                  }
              
              
              
                  
                  $today = getdate();
                  $day = $today['mday'];
                  if ($day <= 9)
                  { $day = '0' . $day;}
                  $month = $today['mon'];
                  if ($month <= 9)
                  { $month = '0' . $month; }
                  $year = $today['year'];
                  
                  $date = $day . '/' . $month . '/' . $year;
                   
                  echo "<script>alert('$date');</script>";
                  mysqli_query($databaseConnection,"INSERT INTO orders (MemID,orderDate)
                                                    VALUES ($MemID,'$date'");
                                                    
                                                    
                  
                  $orderID='';
                  $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                                 FROM orders
                                                                 WHERE MemID = '$MemID' AND
                                                                 orderDate   = '$date'");
                  while ($orderInfo = mysqli_fetch_array($resultSet))
                  {
                      $orderID = $memberInfo['orderID']; 
                  }                                                     
                                                                        
                  $resultSet = mysqli_query($databaseConnection, "SELECT * 
                                                                  FROM books
                                                                  RIGHT JOIN basket
                                                                  ON basket.bookID = books.bookID 
                                                                  WHERE MemID = $MemID");
                  while ($bookInfo = mysqli_fetch_array($resultSet))
                  {
                      $bookID    = $bookInfo['bookID'];
                      $bookName  = stripslashes($bookInfo['bookName']);
                      $author    = stripslashes($bookInfo['author']);
                      $cost      = stripslashes($bookInfo['cost']);
                      $totalCost += (double)$cost;
                      $quantity  = $bookInfo['quantity'];
                      $newQuantity = $quantity-1;
                  
                      mysqli_query($databaseConnection,"UPDATE books
                                                        SET quantity = $newQuantity
                                                        WHERE bookName = '$bookName'");
                      mysqli_query($databaseConnection,"INSERT INTO orderitems (bookID,orderID)
                                                        VALUES ($bookID,$orderID");
                      
                  
                  }
                  mysqli_query($databaseConnection,"UPDATE orders 
                                                    SET totalPrice = $totalCost
                                                    WHERE MemID = $MemID AND 
                                                          orderID = $orderID");                     
              }
              } 
              else
              {
                  echo "<form action='checkout.php' name='firstForm' method='post'>
                        <input type='hidden' id='useremail' name='usrEmail' value=''>
                  <!--  <input type='submit' value='Click Here To Show Basket'>   -->
                        </form>";
              }
              
              
                  
  
              mysqli_close($databaseConnection);
  
          ?>
           <form method="post" action="checkout.php">
               <input type='hidden' id='useremail2' name='usrEmail' value=''>
               <input type="submit" onclick="alert('hello');" name="buyBooks" value="Buy Books">
           </form> 
          </div>  
         
        </div>  
           
                                                             
      </contentArea>
      <footer>
          <div class="foot">  
              <form name="searchBar" class="foo" action="javascript:void(0);">  <!-- javascript code for action from    http://stackoverflow.com/questions/1818249/form-with-no-action-and-where-enter-does-not-reload-page -->                
                  <input name="searchText" class="text" type="text">                             
                  <input class="search" type="image" onclick="find(document.searchBar.searchText.value);" src="search.png" alt="search_icon">  
              </form>     
              <a class="f" href="checkout.php"><img class="cart" src="cart.png" alt="shopping_cart"><p class="check">Checkout</p> </a>
              <div class="ba"><a class="f" href="basket.php"><p class="bas">Basket</p><img class="basket" src="basket.png" alt="basket_icon"></a></div>
          </div>
      </footer>
  
  </body>
</html>