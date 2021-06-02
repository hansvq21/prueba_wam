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
            
            if(isset($_POST['activate'])){
                echo 'estoy aqui';
                include 'config/connection.php';

                $identificator = $_POST['identification'];
                $name = $_POST['name'];
                $telephone = $_POST['telephone'];
                $email = $_POST['email'];
                $cupons = $_POST['cupon'];
                $isCustomer = false;

                if ($con->connect_error) {
                    die("Connection failed: " . $con->connect_error);
                }

                $query = "SELECT * FROM b67781_wa_2021.customer";

                if ($stmt = $con->prepare($query)) {
                    $stmt->execute();
                    $stmt->bind_result($identificator_bd, $name_bd, $telephone_bd, $email_bd);
                    while ($stmt->fetch()) {
                        if($identificator == $identificator_bd){
                            $isCustomer = true;
                        }
                    }
                    $stmt->close();
                }

                if(!$isCustomer){
                    $sql_customer = "INSERT INTO customer (id_customer, name, telephone, email) 
                    VALUES ('$identificator', '$name', '$telephone', '$email');";

                    if ($con->query($sql_customer) === TRUE) {
                    } else {
                        echo "Error: " . $sql_customer . "<br>" . $con->error;
                    }
                }


                $isAdded = false;
                for ($x=0;$x<count($cupons); $x++){
                    $sql_cupon = "INSERT INTO cupon (cupon_name, id_customer) 
                    VALUES ('$cupons[$x]', '$identificator');";
                    if ($con->query($sql_cupon) === TRUE) {
                        $isAdded = true;
                    } else {
                        echo '<script>alert("Error al agregar la información a la base de datos")</script>';
                        break;
                    }
                }

                if($isAdded){
                    echo '<script>alert("Cupones agregados con éxito")</script>';
                }

                

                

                echo count($cupons);

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
            <div class="information">1</div>
            <div class="form">
                <h1 class="activation-text">Activation form</h1>
                <h2 class="secondary-activation-text">A short explanation</h2>

                <form method="post">
                    <label class="label-form">Identification number:</label> <br/>
                    <input type="number" id="identification" name="identification" placeholder="X-XXXX-XXXX" required> <br/>

                    <label class="label-form">Your name:</label> <br/>
                    <input type="text" id="name" name="name" placeholder="First and last name" required> <br/>

                    <label class="label-form">Telephone number:</label> <br/>
                    <input type="number" id="telephone" name="telephone" placeholder="First and last name" required> <br/>
                
                    <label class="label-form">Email address:</label> <br/>
                    <input type="email" id="email" name="email" placeholder="example@email.com" required> <br/>

                    <label class="label-form">Cupons:</label> <br/>
                    <input type="text" id="cupon" name="cupon[]" placeholder="abc123" autocomplete="off" required> 
                    
                    <div id="newRow"></div>
                    <button id="addRow" type="button" class="btn btn-info">Add Row</button><br/>

                    <input type="submit" name="activate" value="Activate">
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // add row
        $("#addRow").click(function () {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<input type="text" id="cupon" name="cupon[]" placeholder="abc123" autocomplete="off" required>';
            html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button><br/>';
            html += '</div>';

            $('#newRow').append(html);
        });

        // remove row
        $(document).on('click', '#removeRow', function () {
            $(this).closest('#inputFormRow').remove();
        });
    </script>
</body>

</html>