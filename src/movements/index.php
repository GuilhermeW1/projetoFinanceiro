<?php
//session_start();



include('../../controllers/VerificaSessao.php');


$con = require '../../database/Connection.php';

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentações</title>
    <?php include('../../style/style.php') ?>
</head>

<body>
    <?php include('../layout/index.php') ;
    
    if(isset($_SESSION['erro']) && $_SESSION['erro'] != "") {
        echo "<script>alert('{$_SESSION["erro"]}')</script>";
        unset($_SESSION['erro']);
    }

    if(isset($_SESSION['success']) && $_SESSION['success'] != "") {
        echo "<script>alert('{$_SESSION["success"]}')</script>";
        unset($_SESSION['success']);
    }

    
    ?>

    <div class="container">

        <form action="../../controllers/MovementsController.php" method="POST">
            <div class="row mt-5">
                <div class="col-sm-4">
                    
                <div class="form-group">
                        <label for="id_ativo"> Ativo </label>
                        <select name="id_ativo" id="id_ativo" class="form-control">
                            <option value=0>Ativo</option>

                            <?php  
                                
                                $sql = "select id_ativo,cd_ativo from ativo order by cd_ativo";
                                $stmt =  $con->prepare($sql);
                                $stmt->execute();
                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    echo ("<option value={$row['id_ativo']}>{$row['cd_ativo']}</option>") ;
                                }

                                /*
                                $ar = require '../../controllers/AtivoController.php';
                                 
                                //$arr = $ativo->a();

                                $cont=0;

                                for($i = 0; ;$i++){
                                    if(isset($ar[$i])){
                                    $at = $ar[$i];
                                    echo ("<option value={$at['id_ativo']}>{$at['cd_ativo']}</option>") ;
                                    }else{
                                        break;
                                    }

                                }
                                */

                            ?>

                        </select>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="quantidade" class="form-lable mb-2"> Quantidade </label>
                        <input type="number" name="quantidade" id="quantidade" class="form-control" required placeholder="123">
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="dt_compra" class="form-lable mb-2"> Data de Compra </label>
                        <input type="date" name="dt_compra" id="dt_compra" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row mt-4">

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="preco_ativo" class="form-lable mb-2"> Preco por ação </label>

                        <input type="number" name="preco_ativo" id="preco_ativo" class="form-control" required placeholder="R$ 123,00" step=".01">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="qnt_ativos" class="form-lable mb-2"> Valor total </label>

                        <input type="number" name="qnt_ativos" id="qnt_ativos" class="form-control" required placeholder="123" step=".01">
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-sm-4 mt-3">

                    <label for="" class="mb-2">Compra / Venda</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tpcompra" value="compra" id="flexRadioDefault1" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Compra
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tpcompra" value="venda" id="flexRadioDefault2" >
                        <label class="form-check-label" for="flexRadioDefault2">
                            Venda
                        </label>

                    </div>
                </div>
                
                <div class="col-sm-4 mt-3">
                    <label for="" class="mb-2">TP operacao</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tpoperacao" value="swing" id="flexRadioDefault3" checked>
                        <label class="form-check-label" for="flexRadioDefault3">
                            Swing Trade
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tpoperacao" value="trade" id="flexRadioDefault4" >
                        <label class="form-check-label" for="flexRadioDefault4">
                            Day Trade
                        </label>

                    </div>
                </div>
            </div>

            <div class="row mt-5 mt-5">
                <div class="col-sm-4">
                    <div class="form-group">

                        <input type="submit" class="btn btn-info btn-md" name="" id="" value="Adicionar">
                    </div>
                </div>
            </div>



    

    </form>

    </div>



</body>

</html>