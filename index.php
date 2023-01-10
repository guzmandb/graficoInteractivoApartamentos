<?php  

//index.php

include("database_connection.php");

$query = "SELECT idtblProject as id,
strName as nombre
FROM contratos.tblProject";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

?>  



<!DOCTYPE html>  
<html lang="es">
<html>  
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gráfico de barras dinámico con cifras por proyecto</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
    </head>  
    <body> 
        <br /><br />
        <div class="container">  
            <h3 align="center">Gráfico de barras dinámico con cifras por proyecto</h3>  
            <br />  
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-9">
                            <h3 class="panel-title">Seleccione un proyecto</h3>
                        </div>
                        <div class="col-md-3">
                            <select name="idProyecto" class="form-control" id="idProyecto">
                                <option value="">Seleccionar Proyecto</option>
                                <option value="0">Todos los proyectos</option>
                            <?php
                            foreach($result as $row)
                            {
                                echo '<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="chart_area" style="width: 1000px; height: 620px;"></div>
                </div>
                <div class="panel-body">
                    <div id="chart_area2" style="width: 1000px; height: 620px;"></div>
                </div>
            </div>
        </div>  
    </body>  
</html>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback();




function cargar_datos_proyecto(idProyecto, title)
{
    var temp_title = 'Datos discriminados por Proyecto:' + ' '+title+'';
    $.ajax({
        url:"fetch.php",
        method:"POST",
        data:{idProyecto:idProyecto},
        dataType:"JSON",
        success:function(data)
        {
            dibujarGraficoProyecto(data, temp_title);
        }
    });
}

function dibujarGraficoProyecto(chart_data, chart_main_title)
{
    var jsonData = chart_data;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'estado');
    data.addColumn('number', 'cifras');
    $.each(jsonData, function(i, jsonData){
        var estado = jsonData.estado;
        var cifras = parseFloat($.trim(jsonData.cifras));
        data.addRows([[estado, cifras]]);
    });
    var options = {
        title:chart_main_title,
        legend:'none',
        hAxis: {
            title: "Estado por proyecto",
            minValue: 0
        },
        vAxis: {
            title: 'Cifras ($ en pesos colombianos)'
        },
        width: '100%',
        height: '100%',
    };

    var jsonData = chart_data;
    var dataPie = new google.visualization.DataTable();
    dataPie.addColumn('string', 'estado');
    dataPie.addColumn('number', 'conteo');
    $.each(jsonData, function(i, jsonData){
        var estado = jsonData.estado;
        var conteo = parseFloat($.trim(jsonData.conteo));
        dataPie.addRows([[estado, conteo]]);
    });

    var optionsPie = {
        title:chart_main_title,
        legend:'top',
        width: '100%',
        height: '100%',
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_area'));
    
    chart.draw(data, options);

    var chart = new google.visualization.PieChart(document.getElementById('chart_area2'));

    chart.draw(dataPie, optionsPie);
}

</script>

<script>
    
$(document).ready(function(){

    $('#idProyecto').change(function(){
        var idProyecto = $(this).val();
        var titulo = $("#idProyecto option:selected").text();
        if(idProyecto != '')
        {
            cargar_datos_proyecto(idProyecto, titulo);
        }
    });

});

</script>