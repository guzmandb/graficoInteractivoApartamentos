
<?php

//database_connection.php
try{

    $connect = new \PDO(
        'mysql:host=142.44.210.74;port:3306;dbname=contratos;charset=utf8mb4',
        //'mysql:host=localhost;dbname=canvasjs_db;charset=utf8mb4',
        'dacero',
        //'root',
        '4DnSGRxfRA',
        //'',
        array(
                \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ));

}
catch(PDOException $pe){
    echo $pe->getMessage();
} 

?>


