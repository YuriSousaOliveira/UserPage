<?php

    $conn = new mysqli($host, $user, $pass, $db);
    mysqli_set_charset($conn, "utf8");

    //date_default_timezone_set('America/Sao_Paulo');

    $mesAtual = date('m');
    $mesAnterior = date('m', strtotime(' - 1 month'));

    if ($mesAnterior == $mesAtual){
        $mesAnterior_1 = date('m', strtotime(' - 2 month'));

        if($mesAnterior_1 == -1){
            $mes_nome_1 = "Jan";
        }elseif($mesAnterior_1 == 0){
            $mes_nome_1 = "Fev";
        }elseif($mesAnterior_1 == 1){
            $mes_nome_1 = "Mar";
        }elseif($mesAnterior_1 == 2){
            $mes_nome_1 = "Abr";
        }elseif($mesAnterior_1 == 3){
            $mes_nome_1 = "Mai";
        }elseif($mesAnterior_1 == 5){
            $mes_nome_1 = "Jun";
        }elseif($mesAnterior_1 == 6){
            $mes_nome_1 = "Jul";
        }elseif($mesAnterior_1 == 7){
            $mes_nome_1 = "Ago";
        }elseif($mesAnterior_1 == 8){
            $mes_nome_1 = "Set";
        }elseif($mesAnterior_1 == 9){
            $mes_nome_1 = "Out";
        }elseif($mesAnterior_1 == 10){
            $mes_nome_1 = "Nov";
        }else{
            $mes_nome_1 = "Dez";
        }
        
    }else{
        $mesAnterior_1 = date('m', strtotime(' - 1 month'));

        if($mesAnterior_1 == 1){
            $mes_nome_1 = "Jan";
        }elseif($mesAnterior_1 == 2){
            $mes_nome_1 = "Fev";
        }elseif($mesAnterior_1 == 3){
            $mes_nome_1 = "Mar";
        }elseif($mesAnterior_1 == 4){
            $mes_nome_1 = "Abr";
        }elseif($mesAnterior_1 == 5){
            $mes_nome_1 = "Mai";
        }elseif($mesAnterior_1 == 6){
            $mes_nome_1 = "Jun";
        }elseif($mesAnterior_1 == 7){
            $mes_nome_1 = "Jul";
        }elseif($mesAnterior_1 == 8){
            $mes_nome_1 = "Ago";
        }elseif($mesAnterior_1 == 9){
            $mes_nome_1 = "Set";
        }elseif($mesAnterior_1 == 10){
            $mes_nome_1 = "Out";
        }elseif($mesAnterior_1 == 11){
            $mes_nome_1 = "Nov";
        }else{
            $mes_nome_1 = "Dez";
        }

    }

    if (!$conn) {
        die("<br/><br/> Não há conexão ou há algum bloqueio na conexão com o banco de dados. <br/><br/>");
    } 
    $sql = "Select distinct A.name,
            A.created_at,
            A.slug,
            A.id,
            B.Usuarios, 
            C.NoticiasTotal,
            D.NoticiasAtivas,
            E.VisualizacaoTotal,
            F.ComentariosTotal,
            G.CurtidasTotal,
            H.UsuariosACT,
            I.NoticiasMes,
            J.NoticiasMesAnt
            From company AS A 
            left JOIN (
                select distinct company_id, count(id) AS Usuarios from user where status = 1 group by company_id ) AS B 
            on A.id = B.company_id
            left JOIN (
                select distinct company_id, count(id) AS NoticiasTotal from news group by company_id) AS C
            on A.id = C.company_id 
            left JOIN (
                select distinct company_id, count(id) AS NoticiasAtivas from news where status = 1 group by company_id) AS D
            on A.id = D.company_id
            left JOIN (
                select distinct company_id, count(id) AS VisualizacaoTotal from view group by company_id) AS E
            on A.id = E.company_id
            left JOIN (
                select distinct company_id, count(id) AS ComentariosTotal from comment group by company_id) AS F
            on A.id = F.company_id
            left JOIN ( 
                select distinct company_id, count(id) AS CurtidasTotal from `like` group by company_id) AS G
            on A.id = G.company_id
            left JOIN (
                select distinct company_id, count(id) AS UsuariosACT from user WHERE status = 1 and updated_at IS NOT NULL group by company_id) AS H
            on A.id = H.company_id
            left JOIN ( 
                select distinct company_id, count(id) AS NoticiasMes from news WHERE MONTH(publish_date) = MONTH(NOW()) and YEAR(publish_date) = YEAR(NOW()) group by company_id) AS I
            on A.id = I.company_id
            left JOIN ( 
                select distinct company_id, count(id) AS NoticiasMesAnt from news WHERE MONTH(publish_date) = ".$mesAnterior_1." and YEAR(publish_date) = YEAR(NOW()) group by company_id) AS J
            on A.id = J.company_id
            Where A.status = 1
            GROUP BY A.id;";
    $result = mysqli_query($conn, $sql);
    
    $sql_01 = "select 
                    A.ContasTotalAtiva, 
                    B.UsuariosACT,
                    C.UsuariosTotals,
                    D.NoticiasAtivass,
                    E.NoticiasTotals,
                    F.NoticiasTotalMes,
                    G.NoticiasTotalMesAnt,
                    H.ViewTotals,
                    I.CurtidasTotals,
                    J.CometarioTotals
                from 
                    (select count(id) AS ContasTotalAtiva from company WHERE status = 1) as A, 
                    (select count(id) AS UsuariosACT from user WHERE status = 1 and updated_at IS NOT NULL) as B,
                    (select count(id) AS UsuariosTotals from user where status = 1) as C,
                    (select count(id) AS NoticiasAtivass from news where status = 1) as D,
                    (select count(id) AS NoticiasTotals from news) as E,
                    (select count(id) AS NoticiasTotalMes from news WHERE MONTH(publish_date) = MONTH(NOW()) and YEAR(publish_date) = YEAR(NOW()) ) as F,
                    (select count(id) AS NoticiasTotalMesAnt from news WHERE MONTH(publish_date) = ".$mesAnterior_1." and YEAR(publish_date) = YEAR(NOW())  ) as G,
                    (select count(id) AS ViewTotals from view ) as H,
                    (select count(id) AS CurtidasTotals from `like`) as I,
                    (select count(id) AS CometarioTotals from comment) as J";

    $result_01 = mysqli_query($conn, $sql_01);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="Work.ico">
    <title>Paginas X Usuários</title>

    <style>
       h1 {
            display:            block;
            font:               600 1.5em/1 \'Open Sans\', sans-serif;
            text-align:         center;
            letter-spacing:     .2em;
            line-height:        1.6;
            top:                15px;
            margin:             auto;
            width:              50%;
            padding:            10px;
            background-color:   #0d5b5d;
            color:              white;
        }
        span {
            font-family:        "Open Sans";
            font-size:          14px;
            z-index:            10;
        }
        span.mySpan {
            padding:            10px;
            background-color:   #0d5b5d;
            display:            block;
            margin:             auto;
            width:              50%;
            height:             auto;
            bottom:             15;
            word-wrap:          break-word;
            min-height:         160px;
            color:              white;
        }
        .allContent {
            width:              98%;
            margin:             auto;
            margin-top:         20px;
            overflow:           scroll;
           
            overflow:           auto;
            background:         rgb(252, 252, 252);
            border:             3px solid rgb(245, 245, 245, 0.5);
            border-radius:      5px;
        }
        table {
            font-family:        "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse:    collapse;
            width:              100%;
            margin:             auto;
            padding:            15px;
        }
        .allContent::-webkit-scrollbar {
            width: 15px;
        }
        .allContent::-webkit-scrollbar-track {
            box-shadow:     inset 0 0 5px grey; 
            border-radius:  10px;
        }
        .allContent::-webkit-scrollbar-thumb {
            background:     #cccccc; 
            border-radius:  10px;
        }
        .allContent::-webkit-scrollbar-thumb:hover {
            background:     #dddddd; 
        }
        td, th {
            border:     1px solid #ddd;
            padding:    8px;
            font-size:  14px;
        }
        tr:nth-child(even){background-color: #f2f2f2;}
        tr:hover {background-color: #ddd;}
        th {
            padding-top:        12px;
            padding-bottom:     12px;
            text-align:         left;
            background-color:   #2fb8e9;
            color:              white;
        }
        th:hover {
            cursor:             pointer;
            background-color:   #002b6a;
            color:              white;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:before {

    </style>
</head>
<body>

    <div style="
            background-image:   url(images/patternfail.jpg);
            background-repeat:  repeat;
            width:              100%;
            height:             100%;
            position:           absolute;
            top:                0; 
            left:               0;
            z-index:            -10;
            opacity:            0.1;">
        </div>
        <div class="form-group input-group" style="width: 98%; margin:auto; margin-top: 50px;">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-search"></i>
            </span>
            <input name="consulta" id="txt_consulta" placeholder="Consultar" type="text" class="form-control">
        </div>
        <div class="allContent">
            <div class="image">
                <center><a href="https://docs.google.com/spreadsheets/d/1otoa2qt3V4FcNeeiJywSxMqeeqinoxBwX0MY37MptNM/edit?ts=5d41d69f#gid=0" target="_blank">
                    <img src="planilha.png" width="30" height="30">
                    TRIAL LEADS sheets
                </a></center>
            </div>
            <table>
                
            </table>
            <table id="minhasCurtidas" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                    <tr style="pointer-events: none;">
                        <th colspan="2" style="background-color: #002b6a;"><center>About Company</center></th>
                        <th colspan="2" style="background-color: #002b6a;"><center>Users</center></th>
                        <th colspan="4" style="background-color: #002b6a;"><center>News</center></th>
                        <th colspan="3" style="background-color: #002b6a;"><center>Interactions</center></th>
                        <th colspan="2" style="background-color: #002b6a;"><center>Date</center></th>
                    </tr>
                    <tr height="30px">
                        <th onclick="sortTable(0)"></th>
                        <th onclick="sortTable(2)">Name</th>
                        <th onclick="sortTable(4)">ACT</th>
                        <th onclick="sortTable(5)">ALL</th> 
                        <th onclick="sortTable(6)">ACT</th>
                        <th onclick="sortTable(7)">ALL</th>  
                        <th onclick="sortTable(8)">
                            <?php
                                $mesAtual_01 = date('m');

                                if($mesAtual_01 == 1){
                                    $mes_nome = "Jan";
                                }elseif($mesAtual_01 == 2){
                                    $mes_nome = "Fev";
                                }elseif($mesAtual_01 == 3){
                                    $mes_nome = "Mar";
                                }elseif($mesAtual_01 == 4){
                                    $mes_nome = "Abr";
                                }elseif($mesAtual_01 == 5){
                                    $mes_nome = "Mai";
                                }elseif($mesAtual_01 == 6){
                                    $mes_nome = "Jun";
                                }elseif($mesAtual_01 == 7){
                                    $mes_nome = "Jul";
                                }elseif($mesAtual_01 == 8){
                                    $mes_nome = "Ago";
                                }elseif($mesAtual_01 == 9){
                                    $mes_nome = "Set";
                                }elseif($mesAtual_01 == 10){
                                    $mes_nome = "Out";
                                }elseif($mesAtual_01 == 11){
                                    $mes_nome = "Nov";
                                }else{
                                    $mes_nome = "Dez";
                                }

                                echo $mes_nome;
                            ?>
                        </th>
                        <th onclick="sortTable(9)">
                            <?php
                                echo $mes_nome_1;
                            ?>
                        </th>
                        <th onclick="sortTable(10)"><center><img src='viewsbranco.png' height='20' width='20'></center></th>
                        <th onclick="sortTable(11)"><center><img src='likebranco.png' height='20' width='20'></center></th>
                        <th onclick="sortTable(12)"><center><img src='commentbranco.png' height='20' width='20'></center></th>
                        <th onclick="sortTable(13)"><center>Deadline</center></th>
                        <th onclick="sortTable(14)"><center>Creation</center></th>
                    </tr>
                    <?php

                        if ($result_01){

                            $row_01 = $result_01->fetch_assoc();

                            echo "<tr height='30px' style='pointer-events: none;'>
                                    <th onclick='sortTable(0)' style='background-color: #002b6a;'>SUM</th>
                                    <th onclick='sortTable(1)' style='background-color: #002b6a;'>".$row_01["ContasTotalAtiva"]." Contas Ativas</th>
                                    <th onclick='sortTable(2)' style='background-color: #002b6a;'>".$row_01["UsuariosACT"]."</th>
                                    <th onclick='sortTable(4)' style='background-color: #002b6a;'>".$row_01["UsuariosTotals"]."</th>
                                    <th onclick='sortTable(5)' style='background-color: #002b6a;'>".$row_01["NoticiasAtivass"]."</th> 
                                    <th onclick='sortTable(6)' style='background-color: #002b6a;'>".$row_01["NoticiasTotals"]."</th>
                                    <th onclick='sortTable(7)' style='background-color: #002b6a;'>".$row_01["NoticiasTotalMes"]."</th>  
                                    <th onclick='sortTable(8)' style='background-color: #002b6a;'>".$row_01["NoticiasTotalMesAnt"]."</th>
                                    <th onclick='sortTable(9)' style='background-color: #002b6a;'>".$row_01["ViewTotals"]."</th>
                                    <th onclick='sortTable(10)' style='background-color: #002b6a;'>".$row_01["CurtidasTotals"]."</th>
                                    <th onclick='sortTable(11)' style='background-color: #002b6a;'>".$row_01["CometarioTotals"]."</th>
                                    <th onclick='sortTable(12)' colspan='2' style='background-color: #002b6a;'></th>
                                </tr>";
                        }
                    ?>
                </thead>
                <tbody>
    <?php

    if ($result) {
        
        if ($result->num_rows > 0) {
                
            while ($row = $result->fetch_assoc()) {

                //Serve para pegar a data e cortar para exibir sem a Hora e comparar com a data do começo do Trail de 30 Dias.
                $data_2 = $row["created_at"];
                $dataNova_2 = date('Y/m/d', strtotime($data_2));
                $dataTrial = date('2019/07/30');

                //Serve para calcular o periodo de teste que os usuarios Trial 30 Dias ainda possuem na plataforma.
                $data_1 = $row["created_at"];
                $dataNova_1 = date('Y/m/d', strtotime($data_1. ' + 40 days'));
                $dataAgora_1 = date('Y/m/d');

                $diferenca_1 = strtotime($dataNova_1) - strtotime($dataAgora_1);
                $dias_1 = floor($diferenca_1 / (60 * 60 * 24));

                //Serve para calcular o periodo de teste que os usuarios Trial 90 Dias ainda possuem na plataforma.
                $data = $row["created_at"];
                $dataNova = date('Y/m/d', strtotime($data. ' + 100 days'));
                $dataAgora = date('Y/m/d');

                $diferenca = strtotime($dataNova) - strtotime($dataAgora);
                $dias = floor($diferenca / (60 * 60 * 24));

                echo "<tr>";  

                if ($row["Usuarios"] > 7) {
                    echo "<td><img src='estrela_dourada.jpg' height='8' width='8'> ";
                }else {
                    echo "<td><img src='estrela_negra.png' height='8' width='8'> ";
                }

                if ($row["NoticiasTotal"] > 6) {
                    echo "<img src='estrela_dourada.jpg' height='8' width='8'></td> ";
                }else {
                    echo "<img src='estrela_negra.png' height='8' width='8'></td> ";
                }

                    echo   "<td><b>".$row["name"]."</b></td>
                            <td>".$row["UsuariosACT"]."</td>
                            <td>".$row["Usuarios"]."</td>
                            <td>".$row["NoticiasAtivas"]."</td>
                            <td>".$row["NoticiasTotal"]."</td>";

                            if ($row["NoticiasMes"]){
                                echo "<td>".$row["NoticiasMes"]."</td>";
                            }else{
                                echo "<td><b> 0 </b></td>";
                            }

                            if ($row["NoticiasMesAnt"]){
                                echo "<td>".$row["NoticiasMesAnt"]."</td>";
                            }else{
                                echo "<td><b> 0 </b></td>";
                            }                          

                            if ($row["VisualizacaoTotal"]){
                                echo "<td>".$row["VisualizacaoTotal"]."</td>";
                            }else{
                                echo "<td><b> 0 </b></td>";
                            }

                            if ($row["CurtidasTotal"]){
                                echo "<td>".$row["CurtidasTotal"]."</td>";
                            }else{
                                echo "<td><b> 0 </b></td>";
                            }

                            if ($row["ComentariosTotal"]){
                                echo "<td>".$row["ComentariosTotal"]."</td>";
                            }else{
                                echo "<td><b> 0 </b></td>";
                            }

                $nomeEmpresa = $row["name"];
                $nomeEmpresa = explode(" ", $nomeEmpresa);

                if ($nomeEmpresa[0] == "Trial") {

                    if ($dataTrial < $dataNova_2){
                        echo "<td>".$dias_1." Daysleft"."</td>";
                    }else{
                        echo "<td>".$dias." Daysleft"."</td>";
                    }

                }else{
                    echo "<td>Reg. Company</td>";
                }

                echo "<td>".$row["created_at"]."</td>";
                echo "</tr>";
            }
        }
    }

    ?>
                </tbody>
            </table>
            </div>
            <div class="myBottom"></div>
            <script>
                
                $("input#txt_consulta").quicksearch("table#minhasCurtidas tbody tr");
                
                $("th").click(function(){
                var table = $(this).parents("table").eq(0)
                var rows = table.find("tr:gt(2)").toArray().sort(comparer($(this).index()))
                this.asc = !this.asc
                
                if (!this.asc){rows = rows.reverse()}
                    for (var i = 0; i < rows.length; i++){
                        table.append(rows[i])}
                })
                
                function comparer(index) {
                    return function(a, b) {
                        var valA = getCellValue(a, index),
                        valB = getCellValue(b, index)
                        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
                    }
                }
                
                function getCellValue(row, index){
                    return $(row).children("td").eq(index).text()
                }

            </script>
</body>
</html>
<?php
mysqli_free_result($result);
mysqli_free_result($result_01);
?>