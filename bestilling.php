<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>De Glade Kager</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="bestilling-stylesheet.css" rel="stylesheet">

  </head>
  <body>  
    <!-- Header -->
    <?php include('templates/header.php'); ?>
    
    <!-- PHP form validation -->
    <?php 
      $firstName = $lastName = $order = $comment = $email = $phone = '';
      $errors =array('fornavn'=>'', 'efternavn'=>'', 'bestilling' => '', 'kommentar' => '', 'email' => '', 'telefon' => '');
      include('templates/connection.php'); 

      if(isset($_POST['submit']))
      {
        if(empty($_POST['fornavn']))
        {
          $errors['fornavn'] = 'Vi mangler dit navn.';
        } 
        else
        {
          $firstName = $_POST['fornavn'];
          if(!preg_match('/^[A-Z][a-zA-Z\s]+$/', $firstName))
          {
            $errors['fornavn'] = 'Dit navn skal starte med et stort bogstav og må ikke indeholde tal.';
          }
        }
        if(empty($_POST['efternavn']))
        {
          $errors['efternavn'] = 'Vi mangler dit efternavn';
        } 
        else
        {
          $lastName = $_POST['efternavn'];
          if(!preg_match('/^[A-Z][a-zA-Z\s]+$/', $lastName))
          {
            $errors['efternavn'] = 'Dit efternavn skal starte med et stort bogstav og må ikke indeholde tal.';
          }
        }
        if(empty($_POST['bestilling']))
        {
          $errors['bestilling'] = 'Vi mangler din bestilling';
        } 
        else
        {
          $order = $_POST['bestilling'];
        }        
        if(!empty($_POST['kommentar']))
        {
          $comment = $_POST['kommentar'];
        }         
        if(empty($_POST['email']))
        {
          $errors['email'] = 'Vi mangler din email';
        } 
        else
        {
          $email = $_POST['email'];
          if(!preg_match('/^[a-zA-Z0-9\s]+@zbc.dk+$/', $email))
          {
            $errors['email'] = 'Din email skal være en ZBC mail.';
          }
        }
        if(empty($_POST['telefon']))
        {
          $errors['telefon'] = 'Vi mangler dit telefonnummer. (I tilfælde af vi skal kontakte dig hurtigt)';
        } 
        else
        {
          $phone = $_POST['telefon'];
          if(!preg_match('/\d+$/', $phone))
          {
            $errors['telefon'] = 'Dit telefonnummer må kun indeholde tal.';
          }
        }

        // Sending data to database and add one to cake counter (For order control)
        if(array_filter($errors))
        {
            
        } 
        else
        {
                         
          $firstName = mysqli_real_escape_string($connection, $_POST['fornavn']);
          $lastName = mysqli_real_escape_string($connection, $_POST['efternavn']);
          $cakeOrder = mysqli_real_escape_string($connection, $_POST['bestilling']);
          $comment = mysqli_real_escape_string($connection, $_POST['kommentar']);
          $email = mysqli_real_escape_string($connection, $_POST['email']);
          $phone = mysqli_real_escape_string($connection, $_POST['telefon']);
                
          $sqlOrder = "INSERT INTO orders(firstName, lastName, cake_order, comment, email, phone) VALUES('$firstName', '$lastName', '$cakeOrder', '$comment', '$email', '$phone')";
          $sqlCounter = "INSERT INTO cakecounter(cake_Counter) VALUES(1)";
          
          mysqli_query($connection, $sqlCounter);
          if(mysqli_query($connection, $sqlOrder))
          {            
            header('Location: bekræftelse.php');            
          } else
          {
            echo 'query error: '. mysqli_error($connection);
          }         
        }
      }    
    
      //Order control, close for orders if more than 8 is pending
    
      // Grapping the data we want and store it in a variable
      $sqlCount = 'SELECT cake_Counter FROM cakecounter';
        
      // To get the data we need to have the connection and sql statement in a variable we can use when we want to output data
      $result = mysqli_query($connection, $sqlCount);
        
      // This store our data in an array we can use to output it
      $count_array = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
      // Will free the result from memory
      mysqli_free_result($result);
        
      // Closes the connection
      mysqli_close($connection);

      //Checking how many elements there are in the array, if there is more than 8 it wont show the page
      $numberCount = 0;
      
      foreach($count_array as $numbers)
      {
        $numberCount++;
      }  
    ?>

    <!-- The main page that starts with counting the number of items in the count array -->  	  
    <?php if($numberCount < 8){ ?>

      <section class="order-form my-4 mx-4">
        <div class="container pt-4" id="main">          
          <div class="row">
            <div class="col-12">
              <h1>Kage bestilling</h1>
              <span>På denne side kan du bestille kager til på fredag. Husk du maksimalt kan bestille 1 kage. Hvis du har brug for flere så kontakt os.</span>
              <hr class="mt-1">
            </div>
            <div class="col-12">
              <form class="white" action="bestilling.php" method="POST">
                <div class="row mx-4">
                  <div class="col-12 mb-2">
                    <label class="order-form-label">Navn</label>
                  </div>
                  <div class="col-12 col-sm-6">
                    <input class="order-form-input" id="fornavn" name='fornavn' placeholder="Fornavn" value="<?php echo htmlspecialchars($firstName) ?>">
                    <div class="red-text" style="color:red"><?php echo $errors['fornavn']; ?></div>
                  </div>
                  <div class="col-12 col-sm-6 mt-2 mt-sm-0">
                    <input class="order-form-input" id="efternavn" name='efternavn' placeholder="Efternavn" value="<?php echo htmlspecialchars($lastName) ?>">
                    <div class="red-text" style="color:red"><?php echo $errors['efternavn']; ?></div>
                  </div>
                </div>

                <div class="row mt-3 mx-4">
                  <div class="col-12">
                    <label class="order-form-label">Hvilken kage vil du gerne bestille?</label>
                  </div>
                  <div class="col-12">
                    <input class="order-form-input" id="bestilling" name='bestilling' placeholder="Maks 1." value="<?php echo htmlspecialchars($order) ?>">
                    <div class="red-text" style="color:red"><?php echo $errors['bestilling']; ?></div>
                  </div>
                </div>

                <div class="row mt-3 mx-4">
                  <div class="col-12">
                    <label class="order-form-label">Har du kommentare eller ønsker til ordren?</label>
                  </div>
                  <div class="col-12">
                    <input class="order-form-input" id="kommentar" name='kommentar' placeholder="Allergener mm." value="<?php echo htmlspecialchars($comment) ?>">
                    <div class="red-text" style="color:red"><?php echo $errors['kommentar']; ?></div>
                  </div>
                </div>

                <!-- Placeholder Date / Kalender -->
                <!--
                  <div class="row mt-3 mx-4">
                    <div class="col-12">
                      <label class="order-form-label" for="date-picker-example">Date</label>
                    </div>
                    <div class="col-12">
                      <input class="order-form-input datepicker" placeholder="Selected date" type="text"
                        id="date-picker-example">
                    </div>
                  </div>
                -->
          
                <div class="row mt-3 mx-4">
                  <div class="col-12">
                    <label class="order-form-label">Kontaktoplysninger</label>,
                  </div>
                </div>

                <!-- Placeholder Address -->
                <!--
                  <div class="col-12">
                    <input class="order-form-input" placeholder="Street Address">
                  </div>
                  <div class="col-12 col-sm-6 mt-2 pr-sm-2">
                    <input class="order-form-input" placeholder="City">
                  </div>
                  <div class="col-12 col-sm-6 mt-2 pl-sm-0">
                    <input class="order-form-input" placeholder="Region">
                  </div>
                -->	

                <div class="row mx-4">
                  <div class="col-12 col-sm-6 mt-2 pr-sm-2">
                    <input class="order-form-input" id="email" name='email' placeholder="ZBC-Mail" value="<?php echo htmlspecialchars($email) ?>">
                    <div class="red-text" style="color:red"><?php echo $errors['email']; ?></div>
                  </div>
                  <div class="col-12 col-sm-6 mt-2 pl-sm-0">
                    <input class="order-form-input" id="telefon" name='telefon' placeholder="Telefon nummer" value="<?php echo htmlspecialchars($phone) ?>">
                    <div class="red-text" style="color:red"><?php echo $errors['telefon']; ?></div>
                  </div>
                </div>

                <div class="row mt-3 mx-4">
                  <div class="col-12">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" name="validation" id="validation" value="1">
                      <label for="validation" class="form-check-label">Jeg bekræfter at jeg har læst handelsbetingelserne.</label>
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-12">
                    <button type="submit" id="submit" name='submit' value='submit' class="btn btn-dark d-block mx-auto btn-submit">Bestil</button>
                  </div>
                </div>
              </div>  
            </form>
          </div>
        </div>
      </section>
    
      <!-- This bit runs if the counter detects more that 8 instanses in the array -->
    <?php }else{ ?>
      <section class="order-form my-4 mx-4">
        <div class="container pt-4" id="main">          
          <div class="row">
            <div class="col-12">
              <h1>Vi har udsolgt</h1>
              <span>Vi må desværre meddele at vi ikke kan tage flere bestillinger i denne uge.</span>
              <hr class="mt-1">
            </div>
          </div>
        </div>
      </section>
    <?php } ?>

    <div id="container">
      <div id="main"></div>
    </div>

    <!-- Footer -->
    <?php include('templates/footer.php'); ?>	  
 
  </body>

</html>