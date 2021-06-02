<?php
    $host="163.178.107.10";
    $port=3306;
    $socket="";
    $user="laboratorios";
    $password="KmZpo.2796";
    $dbname="b67781_wa_2021";

    $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die ('Could not connect to the database server' . mysqli_connect_error());

    //$con->close();


    

    function validateCupon ($con, $newCupon){
        $cupons = "SELECT cupon_name FROM b67781_wa_2021.cupon";
        if ($stmt = $con->prepare($cupons)) {
            $stmt->execute();
            $stmt->bind_result($cuponNameBD);
            while ($stmt->fetch()) {
                if($cuponNameBD == $newCupon){
                    return false;
                }
            }
            $stmt->close();

            return true;
        }
    }
    

?>
