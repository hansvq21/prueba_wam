<?php

    //Conexión con el servidor de la BD
    $host="127.0.0.1";
    $user="root";
    $password="";
    $dbname="prueba_wam_hans";

    $con = new mysqli($host, $user, $password, $dbname)
        or die ('Could not connect to the database server' . mysqli_connect_error());

    //$con->close();


    
    //Función para validar si un cupón ya fue activado o no
    function validatecoupon ($con, $newcoupon){
        $coupons = "SELECT coupon_name FROM prueba_wam_hans.coupon";
        $couponNameBD = "";
        //Se recorre la lista de coupones dados de la consulta en la BD
        if ($stmt = $con->prepare($coupons)) {
            $stmt->execute();
            $stmt->bind_result($couponNameBD);
            while ($stmt->fetch()) {
                //Se verifica si existe alguno igual en la BD
                if($couponNameBD == $newcoupon){
                    return false;
                }
            }
            $stmt->close();
            //En caso de no haber ningún cupón igual, se retorna true
            return true;
        }
    }

    //Función para validar si un cupón ya fue activado o no
    function validateAllCoupons ($con, $newcoupon){
        $coupons = "SELECT coupon_name FROM prueba_wam_hans.coupon";
        $couponNameBD = "";
        //Se recorre la lista de coupones dados de la consulta en la BD
        if ($stmt = $con->prepare($coupons)) {
            $stmt->execute();
            $stmt->bind_result($couponNameBD);
            while ($stmt->fetch()) {
                //Se verifica si existe alguno igual en la BD
                for ($x=0;$x<count($newcoupon); $x++){
                    if($couponNameBD == $newcoupon[$x]){
                        return false;
                    }
                }
            }
            $stmt->close();
            //En caso de no haber ningún cupón igual, se retorna true
            return true;
        }
    }
    

?>
