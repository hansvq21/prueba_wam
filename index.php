<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/styles.css">
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
                $cupon = $_POST['cupon'];
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

                $sql_cupon = "INSERT INTO cupon (cupon_name, id_customer) 
                VALUES ('$cupon', '$identificator');";

                if ($con->query($sql_cupon) === TRUE) {
                    echo '<script>alert("Cupon activated successfully")</script>';
                } else {
                    echo "Error: " . $sql_cupon . "<br>" . $con->error;
                }

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
                    <input type="text" id="cupon" name="cupon" placeholder="abc123" required> <br/>

                    <input type="submit" name="activate" value="Activate">
                </form>
            </div>
        </div>
    </div>
</body>

</html>