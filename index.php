<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupons Activator</title>
    <link rel="stylesheet" href="css/styles.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <div>
        <?php

            //verificar que se de clicl en el botón para enviar el formulario
            if(isset($_POST['activate'])){
                //importar el archivo de concexión con la bd
                include 'config/connection.php';

                //Variables del formulario
                $identificator = $_POST['identification'];
                $name = $_POST['name'];
                $telephone = $_POST['telephone'];
                $email = $_POST['email'];
                $coupons = $_POST['coupon'];
                $isCustomer = false;

                //Verificar algún error en la conexión
                if ($con->connect_error) {
                    die("Connection failed: " . $con->connect_error);
                }

                $query = "SELECT * FROM prueba_wam_hans.customer";

                //Recorrer la lista de clientes para verificar si ya están en la BD o no
                if ($stmt = $con->prepare($query)) {
                    $stmt->execute();
                    $stmt->bind_result($identificator_bd, $name_bd, $telephone_bd, $email_bd);
                    while ($stmt->fetch()) {
                        //Revisar si el cliente existe
                        if($identificator == $identificator_bd){
                            $isCustomer = true;
                        }
                    }
                    $stmt->close();
                }

                //En caso de que la persona aún no esté en la BD, se agrega
                if(!$isCustomer){
                    $sql_customer = "INSERT INTO customer (id_customer, name, telephone, email) 
                    VALUES ('$identificator', '$name', '$telephone', '$email');";

                    //Revisar cualquier error que pueda ocurrir
                    if ($con->query($sql_customer) === TRUE) {
                    } else {
                        echo "Error: " . $sql_customer . "<br>" . $con->error;
                    }
                }


                //Se recorre la lista de coupones y se va agregando uno a uno a la BD
                //En caso de que haya un cupón repetido, este no se agrega y se envía un mensaje de error
                $isAdded = false;
                for ($x=0;$x<count($coupons); $x++){
                    $sql_coupon = "INSERT INTO coupon (coupon_name, id_customer) 
                    VALUES ('$coupons[$x]', '$identificator');";
                    if(validatecoupon($con, $coupons[$x])){
                        if ($con->query($sql_coupon) === TRUE) {
                            $isAdded = true;
                        } else {
                            $isAdded = false;
                            echo '<script>alert("Error adding the information to the database")</script>';
                            break;
                        }
                    } else {
                        echo '<script>alert("The coupon '.$coupons[$x].' is not valid")</script>';
                    }
                }

                if($isAdded){
                    echo '<script>alert("Coupons added successfully")</script>';
                }

                //Cierre de conexión con la BD
                $con->close();

            }

        ?>
    </div>

    <div class="main">
        <div class="container">
            <div class="navbar">
                <img src="img/coupon-navbar.png" class="logo" alt="Main logo">

                <ul>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Services</a></li>
                </ul>
            </div>
        </div>

        <header>
            <div class="container">
                <h1 class="tittle">Activate your coupons here</h1>
                <h2 class="secondary-tittle">The most reliable site to activate your coupons </h2>
            </div>
        </header>

        <div class="grid-container">
            <div class="information">
                <div>
                <img src="img/logo.png" class="img-information" alt="Main logo">
                <p><strong>We offer the service for you to activate your coupons in a reliable and safe way, 
                   where your data will be safe and you will be able to obtain the benefits of 
                   each coupon without any problem</p></strong></br>
                <p><strong>These are the steps so you can do it:</strong></p>
                </div>

                <div class="grid-container-info">
                    <div><h1>Step 1</h1></div>
                    <div>
                        <h2>Enter your personal data in the form on the right side</h2>
                    </div>

                    <div><h1>Step 2</h1></div>
                    <div>
                        <h2>Add the coupon code you want to activate in the coupon field 
                            (you can activate up to 5 coupons at the same time)</h2>
                    </div>

                    <div><h1>Step 3</h1></div>
                    <div>
                        <h2>Press the activate button at the bottom of the form to complete the process </h2>
                    </div>
                </div>
            </div>

            
            
            <div class="form">  
                <h1 class="activation-text">Activation form</h1><br/>
                <p class="secondary-activation-text">The following information is requested in order to offer 
                    you a better service, more specialized and focused on your tastes and preferences. 
                    In addition to having the possibility of acquiring more and better benefits by 
                    activating the coupons on this website. </p><br/>

                <form method="post">
                    <label class="label-form">Identification number:</label> <br/>
                    <input type="number" id="identification" name="identification" placeholder="X-XXXX-XXXX" required maxlength="9"> <br/>

                    <label class="label-form">Your name:</label> <br/>
                    <input type="text" id="name" name="name" placeholder="First and last name" required> <br/>

                    <label class="label-form">Telephone number:</label> <br/>
                    <input type="number" id="telephone" name="telephone" placeholder="####-####" required min="1" maxlength="8"> <br/>
                
                    <label class="label-form">Email address:</label> <br/>
                    <input type="email" id="email" name="email" placeholder="example@email.com" required> <br/>

                    <label class="label-form">Coupons:</label> <br/>
                    <input type="text" id="coupon" name="coupon[]" placeholder="abc123" autocomplete="off" required maxlength="6"> 
                    
                    <div id="newRow"></div>
                    <button id="addRow" type="button" class="addRow">Add coupon</button><br/>

                    <input type="submit" name="activate" value="Activate" href="index.php">
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Añadir nuevo campo de texto para el cupón
        // Se añade también un botón de borrar para eliminar el campo de texto
        var maxField = 5;
        var x = 1;
        $("#addRow").click(function () {
            
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<input type="text" id="coupon" name="coupon[]" placeholder="abc123" autocomplete="off" required maxlength="6">';
            html += '<button id="removeRow" type="button" class="removeRow">Remove coupon</button><br/>';
            html += '</div>';

            if(x < maxField){ 
                x++; //Incrementar el contador de campos
                $('#newRow').append(html);
                if(x == maxField){
                    $('#addRow').hide();
                }
            }
             
            
        });

        // rFuncionamiento del botón de borrado del campo de texto
        $(document).on('click', '#removeRow', function () {
            x--;
            $('#addRow').show();
            $(this).closest('#inputFormRow').remove();
            $('#newRow').append(html);
        });
    </script>
</body>

</html>