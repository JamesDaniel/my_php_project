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
    <link href='nav.css'       rel='stylesheet'    type='text/css'>
    <link href='content.css'       rel='stylesheet'    type='text/css'>
    <link href='responsive.css' rel='stylesheet'    type='text/css'>
    <script type="text/javascript" src="cookieMonster.js"></script>
                                                                                                 <!--Creating new html tag for older brouser-->
                                                                                                 <!--Find out if new tags need to be created for old brousers-->
    <!--[if lt IE 9]> 
        <script type="text/javascript"> document.createElement("stack"); </script>               
    <![endif]-->  
  <style type="text/css">
  
  </style>
  <script type="text/javascript"> 
      function checkLogin()
      {   
          if (checkCookie() == 1)
          {
              document.getElementById('welcomeMessage').innerHTML = 'Welcome ' + getCookie('usrEmail');
              document.getElementById('useremail2').value = getCookie('usrEmail');
              document.getElementById('useremail').value = getCookie('usrEmail');
              document.forms['firstForm'].submit();
          }
      }
      
  </script>
  <?php
  ?>
  
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
          <p id="welcomeMessage"></p>
          <?php
              if (isset($_POST['usrEmail']))
              {
              $databaseConnection = mysqli_connect("localhost", "root", "", "booksys");
              if (mysqli_connect_errno($databaseConnection)) { echo "Failed to connect to database.".mysqli_connect_error();}
              
                  $email = $_POST['usrEmail'];
                  /*    echo "<script>alert('$email');</script>";    */
                  $MemID='';
                  $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                                 FROM members
                                                                 WHERE Email = '$email'");
                  while ($memberInfo = mysqli_fetch_array($resultSet))
                  {
                      $MemID = $memberInfo['MemID']; 
                  }
              
              $searchText = '';
              if (isset($_POST['searchText']))
              {
                  $searchText = mysqli_real_escape_string($databaseConnection, $_POST['searchText']);
              }
              if ($searchText == '' || $searchText == null)
              {
                  $sqlQuery = "SELECT * 
                               FROM books, basket
                               WHERE books.bookID = basket.bookID  AND
                                     basket.MemID = '$MemID'";
              }
              else      
              {
                  $sqlQuery = "SELECT * 
                               FROM books
                               RIGHT JOIN basket
                               ON basket.bookID = books.bookID 
                               WHERE (bookName LIKE '%$searchText%' OR
                                      author   LIKE '%$searchText%') AND
                                      MemID = '$MemID'";
              }
              $resultSet = mysqli_query($databaseConnection,$sqlQuery); 
              
                  if (!$resultSet) 
                  {
                      echo "Error: \n". mysqli_error($databaseConnection);   /* this was usefull */
                      exit();
                  }                                                            
              while ($bookInfo = mysqli_fetch_array($resultSet))
              {
                  $bookName = stripslashes($bookInfo['bookName']);
                  $author   = stripslashes($bookInfo['author']);
                  $picture  = stripslashes($bookInfo['picture']);
                  
                  $useremail = "'usrEmail'";
                  echo "  <div class='bookContainer'>             <!-- I did this instead of a here doc   -->
                              <img class='book' src='bookImages/$picture'>
                              <h1>$bookName</h1>
                              <p class='bookAuthor'>by $author</p>
                              <p class='bookFormat'>Format:  Paperback</p>
                              <p class='bookStock'>Stock Level: 50</p>
                              <div class='bookPrice'>
                                  <p class='bookPrice'>&euro;16.49</p>
                                  <form action='basket.php' method='post'>
                                      <input type='hidden' name='bookName' value='$bookName'>
                                      <input type='hidden' id='usrEmail$bookName' name='usrEmail' value=''>
                                      <input type='submit' name='deleteFromBasket' value='Delete Item'>
                                  </form>
                              </div>
                          </div>
                          <script type='text/javascript'>document.getElementById('usrEmail$bookName').value = getCookie('usrEmail');</script>  <!-- I needed to add this script here otherwise the value would only be applied to one id  -->
                       ";
                   
              }
              if (isset($_POST['deleteFromBasket']))
              {
                  $bookName = $_POST['bookName'];
                  $email = $_POST['usrEmail'];
                  
                  
                  $bookID='';
                  $resultSet = mysqli_query($databaseConnection,"SELECT *
                                                                 FROM books
                                                                 WHERE bookName = '$bookName'");
                  if (!$resultSet) 
                  {
                      echo "Error: \n". mysqli_error($databaseConnection);   /* this was usefull */
                      exit();
                  }
                  while ($bookInfo = mysqli_fetch_array($resultSet))
                  {
                      $bookID = $bookInfo['bookID']; 
                  }
                                                        
                  /*echo "<script>alert('$bookID' + '$MemID');</script>"; */
                  mysqli_query($databaseConnection,"DELETE FROM basket
                                                    WHERE bookID = $bookID AND
                                                          MemID  = $MemID");
              }
                  mysqli_close($databaseConnection);
              }
              else
              {
                  echo "<form action='basket.php' name='firstForm' method='post'>
                            <input type='hidden' id='useremail' name='usrEmail' value=''>
                          <!--  <input type='submit' value='Click Here To Show Basket'>   -->
                        </form>";
              }           
          ?>
                  
        </div>  
                                                             
      </contentArea>
      <footer>
          <div class="foot">  
              <form name="searchBar" class="foo" action="basket.php" method="post">                 
                  <input name="searchText" class="text" type="text">    
                  <input type='hidden' id='useremail2' name='usrEmail' value=''>                            
                  <input class="search" type="image" src="search.png" alt="search_icon">  
              </form>     
              <a class="f" href="checkout.php"><img class="cart" src="cart.png" alt="shopping_cart"><p class="check">Checkout</p> </a>
              <div class="ba"><a class="f" href="basket.php"><p class="bas">Basket</p><img class="basket" src="basket.png" alt="basket_icon"></a></div>
          </div>
      </footer>
  
  </body>
</html>