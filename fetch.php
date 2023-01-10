<?php

//fetch.php

include("database_connection.php");


if(isset($_POST["idProyecto"]))
{
    $where = '';
    if($_POST["idProyecto"]!=0){
        $where = 'WHERE pr.idtblProject = '.$_POST["idProyecto"];
    }
    $query = "SELECT CASE 
    WHEN (ap.bitSelled=0) THEN 'Disponible'
    WHEN (ap.bitSelled=1) THEN 'Vendido'
    END estado,ap.bitSelled bitEstado, COUNT(ap.bitSelled) conteo, sum(ap.dblPrice) cifras, pr.idtblProject idProyecto, pr.strName nombreProyecto
    FROM contratos.tblApartment as ap
    JOIN contratos.tblBuilding as b ON (ap.tblBuilding_idBuilding=b.idtblBuilding)
    JOIN contratos.tblPhase as p ON (b.tblPhase_idPhase=p.idtblPhase)
    JOIN contratos.tblProject as pr ON (p.tblProject_idProject=pr.idtblProject)
    ".$where."
    GROUP BY estado
    ORDER BY idProyecto";


    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)

    {
        $output[] = array(
            'estado'   => $row["estado"],
            'cifras'  => intval($row["cifras"]),
            'conteo' => intval($row["conteo"]),

        );
    }
    echo json_encode($output);
}

?>