<?php

//session_start();
include('../../controllers/verificaSessao.php');
$con = require('../../database/connection.php');

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatisticas</title>
    <?php include('../../style/style.php'); ?>
</head>



<body>
    <header>
        <?php include('../layout/index.php'); ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            
        </nav>

        <h1 style="text-align: center; margin-top: 10px;">Aqui tera as Estatisticas
        </h1>

    </header>

    
    <div class="container">
        <table class="table mt-5">
            <thead>
                <th scope="col">Ativo</th>
                <th scope="col">Data de Compra/Venda</th>
                <th scope="col">Qntd</th>
                <th scope="col">Preco</th>
                <th scope="col">Valor compra</th>
                <th scope="col">CM</th>
                <th scope="col">Total de ativos</th>
                <th scope="col">Valor total</th>
            </thead>

            <tbody>
                <?php
                $sql = "select a.cd_ativo as ativo, date_format(m.dt_moviement, '%d-%m-%Y') as data, 
                    m.qnt_ativo as qntd, m.vlr_ativo as valor, m.vlr_total as total, 
                    c.cm as cmm, c.total as totalCm, c.qntd as qntdTotal
                    from moviements m, cm c, users u, ativo a
                    where m.id_moviements = c.id_moviement
                    and m.id_user = u.id_user
                    and m.id_ativo = a.id_ativo
                    and u.id_user = ?
                    order by  m.id_moviements
                    ";

                $stmt = $con->prepare($sql);
                $stmt->execute([$_SESSION['id_user']]);




                /* 
                    esse aqui cira um array com os index  quero usar
                    para separar as camadas da aplicacao 

                    essa aqui e fazendo direto

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        echo  ("<tr>

                                    <td>{$row['ativo']}</td>
                                    <td>{$row['data']}</td>
                                    <td>{$row['qntd']}</td>
                                    <td>{$row['valor']}</td>
                                    <td>{$row['total']}</td>
                                    <td>{$row['cmm']}</td>
                                    <td>{$row['qntdTotal']}</td>
                                    <td>{$row['totalCm']}</td>
                                </tr>
                        
                        ");

                    }

                    */
                $teste;
                $contador = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $teste[$contador] = $row;
                    $contador++;
                }

                //print_r($teste);

                $a;
                for ($i = 0; $i <= $teste; $i++) {


                    if (isset($teste[$i])) {
                        $a = $teste[$i];

                        echo ("<tr>

                                    <td>{$a['ativo']}</td>
                                    <td>{$a['data']}</td>
                                    <td>{$a['qntd']}</td>
                                    <td>{$a['valor']}</td>
                                    <td>{$a['total']}</td>
                                    <td>{$a['cmm']}</td>
                                    <td>{$a['qntdTotal']}</td>
                                    <td>{$a['totalCm']}</td>
                                </tr>
                        
                        ");
                    } else {
                        break;
                    }
                }




                ?>

            </tbody>

        </table>
    </div>


</body>

</html>