<?php

    //Conexión con el servidor de la BD
    $host="163.178.107.10";
    $port=3306;
    $socket="";
    $user="laboratorios";
    $password="KmZpo.2796";
    $dbname="b67781_wa_2021";

    $con = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die ('Could not connect to the database server' . mysqli_connect_error());

    //$con->close();


    
    //Función para validar si un cupón ya fue activado o no
    function validateCupon ($con, $newCupon){
        $cupons = "SELECT cupon_name FROM b67781_wa_2021.cupon";
        //Se recorre la lista de cupones dados de la consulta en la BD
        if ($stmt = $con->prepare($cupons)) {
            $stmt->execute();
            $stmt->bind_result($cuponNameBD);
            while ($stmt->fetch()) {
                //Se verifica si existe alguno igual en la BD
                if($cuponNameBD == $newCupon){
                    return false;
                }
            }
            $stmt->close();
            //En caso de no haber ningún cupón igual, se retorna true
            return true;
        }
    }
    

?>
