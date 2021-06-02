<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                $cupons = $_POST['cupon'];
                $isCustomer = false;

                //Verificar algún error en la conexión
                if ($con->connect_error) {
                    die("Connection failed: " . $con->connect_error);
                }

                $query = "SELECT * FROM b67781_wa_2021.customer";

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


                //Se recorre la lista de cupones y se va agregando uno a uno a la BD
                //En caso de que haya un cupón repetido, este no se agrega y se envía un mensaje de error
                $isAdded = false;
                for ($x=0;$x<count($cupons); $x++){
                    $sql_cupon = "INSERT INTO cupon (cupon_name, id_customer) 
                    VALUES ('$cupons[$x]', '$identificator');";
                    if(validateCupon($con, $cupons[$x])){
                        if ($con->query($sql_cupon) === TRUE) {
                            $isAdded = true;
                        } else {
                            $isAdded = false;
                            echo '<script>alert("Error al agregar la información a la base de datos")</script>';
                            break;
                        }
                    } else {
                        echo '<script>alert("El cupón '.$cupons[$x].' no es válido")</script>';
                    }
                }

                if($isAdded){
                    echo '<script>alert("Cupones agregados con éxito")</script>';
                }

                //Cierre de conexión con la BD
                $con->close();

            }

        ?>
    </div>

    <div class="main">
        <div class="container">
            <div class="navbar">
                <img src="img/navbar-img.png" class="logo" alt="Main logo">

                <ul>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Services</a></li>
                </ul>
            </div>
        </div>

        <header>
            <div class="container">
                <h1 class="tittle">Header text</h1>
                <h2 class="secondary-tittle">Secondary header text</h2>
            </div>
        </header>

        <div class="grid-container">
            <div class="information">
                <img src="img/perrito.jpg" class="img-information" alt="Main logo">
            </div>
            
            <div class="form">  
                <h1 class="activation-text">Activation form</h1><br/>
                <p class="secondary-activation-text">A short explanation</p><br/>

                <form method="post">
                    <label class="label-form">Identification number:</label> <br/>
                    <input type="number" id="identification" name="identification" placeholder="X-XXXX-XXXX" required maxlength="9"> <br/>

                    <label class="label-form">Your name:</label> <br/>
                    <input type="text" id="name" name="name" placeholder="First and last name" required> <br/>

                    <label class="label-form">Telephone number:</label> <br/>
                    <input type="number" id="telephone" name="telephone" placeholder="####-####" required min="1" maxlength="8"> <br/>
                
                    <label class="label-form">Email address:</label> <br/>
                    <input type="email" id="email" name="email" placeholder="example@email.com" required> <br/>

                    <label class="label-form">Cupons:</label> <br/>
                    <input type="text" id="cupon" name="cupon[]" placeholder="abc123" autocomplete="off" required maxlength="6"> 
                    
                    <div id="newRow"></div>
                    <button id="addRow" type="button" class="addRow">Add Cupon</button><br/>

                    <input type="submit" name="activate" value="Activate">
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Añadir nuevo campo de texto para el cupón
        // Se añade también un botón de borrar para eliminar el campo de texto
        $("#addRow").click(function () {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<input type="text" id="cupon" name="cupon[]" placeholder="abc123" autocomplete="off" required maxlength="6">';
            html += '<button id="removeRow" type="button" class="removeRow">Remove Cupon</button><br/>';
            html += '</div>';

            $('#newRow').append(html);
        });

        // rFuncionamiento del botón de borrado del campo de texto
        $(document).on('click', '#removeRow', function () {
            $(this).closest('#inputFormRow').remove();
        });
    </script>
</body>

</html>