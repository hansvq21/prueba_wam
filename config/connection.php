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


$query = "SELECT * FROM b67781_wa_2021.customer";


if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($field1, $field2, $field3, $field4);
    while ($stmt->fetch()) {
        printf("%s, %s\n", $field1, $field2, $field3, $field4);
    }
    $stmt->close();
}

?>
