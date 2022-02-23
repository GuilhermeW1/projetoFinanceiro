<?php

/*
class movimentController
{

    private function inserir()
    {
        session_start();
        if (
            isset($_POST['id_ativo']) != 0 && isset($_POST['quantidade'])
            && isset($_POST['dt_compra']) && isset($_POST['preco_ativo'])
            && isset($_POST['qnt_ativos'])
        ) {

            $ativo = $_POST['id_ativo'];
            $quantidade = $_POST['quantidade'];
            $dt_compra = $_POST['dt_compra'];
            $preco = $_POST['preco_ativo'];
            $precoTotal = $_POST['qnt_ativos'];
            $tpperacao = $_POST['tpoperacao'];
            $tpcompra = $_POST['tpcompra'];


            $firstMov = $this->verificaPrimeiroRegistro($_SESSION['id_user'], $ativo);


            try {
                $con = require '../database/connection.php';

                $sql = "insert into moviements values(default, ? , ? ,true, true, ? , ?, ?, ?, '2022/01/11'  )";
                $stmt = $con->prepare($sql);
                $result = $stmt->execute([$_SESSION['id_user'], $ativo, $quantidade, $preco, $precoTotal, $dt_compra]);
            } catch (PDOException $e) {
                echo 'erro ' . $e->getMessage();
            }

            if ($result) {
                $id_moviement = $this->maxIdMoviement();


                if (!$firstMov) {
                    //vai pegar o ultimo id inserido no banco que sera o id adicionado a cima

                    //vai descontar um para pegar o valor id do valor antes de ser adicionado o de cima
                    //idmovimentacao old

                    try {
                        $id_old = $this->penultimoInsert($ativo, $_SESSION['id_user']);


                        $sql = "select * from cm where id_moviement = $id_old";

                        $result = $con->query($sql);

                        $row = $result->fetch(PDO::FETCH_ASSOC);

                        $old_total = $row['total'];
                        $old_qntd = $row['qntd'];
                        //aqui e definido os calculos e o que vai para o banco
                        $cm = ($precoTotal + $old_total) / ($quantidade + $old_qntd);
                        $total = $old_total + $precoTotal;
                        $qntd = $old_qntd + $quantidade;

                        $sql = "insert into cm values(default,?, ?, ?, ? )";

                        $stmt = $con->prepare($sql);

                        // $cm = $precoTotal/$quantidade;//se atenta nessa linha

                        $result = $stmt->execute([$id_moviement,  $cm, $total, $qntd]);
                    } catch (PDOException $e) {
                        echo "erro" . $e->getMessage();
                    }

                    if ($result) {
                        $_SESSION['success'] = "cm inserido com sucesso";
                        
                    } else {
                        $_SESSION['erro'] = "erro ao inserir cm";
                        header('Location: ../../src/movements/index.php');
                    }

                    ////////////////////////
                    $stmt = $con->prepare($sql);

                    if ($result = $stmt->execute([$id_old])) {

                        $row = $result->fetch(PDO::FETCH_ASSOC);

                        $oldTotal = $row['total'];
                    }
                    //AQUI TAVA FECHADO COM O *   /
                } else {

                    try {
                        $sql = "insert into cm values(default,?, ?, ?, ? )";

                        $stmt = $con->prepare($sql);

                        $cm = $precoTotal / $quantidade; //se atenta nessa linha

                        $result = $stmt->execute([$id_moviement,  $cm, $precoTotal, $quantidade]);
                    } catch (PDOException $e) {
                        echo "erro " . $e->getMessage();
                    }
                    if ($result) {
                        $_SESSION['success'] = "cm inserido com sucesso";
                        
                    } else {
                        $_SESSION['erro'] = "erro ao inserir cm";
                        
                    }
                }
            } else {
                $_SESSION['erro'] = "erro ao inserir movimentação";
                header('Location: ../../src/movements/index.php');
            }
        }
    }



    //vai retornar o ultimo indixe, vai buscar 2 e o laco vai achar o ultimo
    //no caso ele vai pegar o insert penultimo que sera necessario para calcular o cm

    private function penultimoInsert($idAtivo, $idUser)
    {

        $con = require '../database/connection.php';
        $sql = "select id_moviements
    from moviements 
    where id_ativo = $idAtivo
    and id_user = $idUser
    order by id_moviements desc
    limit 2";

        $result = $con->query($sql);



        $id_moviement;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id_moviement = $row['id_moviements'];
        }


        return $id_moviement;
    }

    ////////////////////////////////////////////////////

    private function maxIdMoviement()
    {
        //AQUI EU SELECIONO O ULTIMO INDICE DE MOVIMENTACOES PARA USAR NA CM
        $con = require '../database/connection.php';
        $sql = "select max(id_moviements) as maximo from moviements where id_user = 2 and id_ativo = 1";

        $result = $con->query($sql);

        $row = $result->fetch(PDO::FETCH_ASSOC);

        $id_moviement = $row['maximo'];
        return $id_moviement;
    }


    ///////////////////////////////////////////////////////////////////////////////////////
    //a funcao ira verificar se ja ha algum registro desse usuario utilizando 
    //algum do astivo para que na primeira vez o cm n seja calculado somando 
    //novamente as coisas
    private function verificaPrimeiroRegistro($idUser, $idAtivo)
    {
        try {
            $con = require '../database/connection.php';

            $sql = "select id_moviements 
        from moviements 
        where id_ativo = $idAtivo 
        and id_user = $idUser";


            $result = $con->query($sql);

            $row = $result->fetch(PDO::FETCH_ASSOC);

            if (isset($row['id_moviements']) && isset($row['id_moviements']) != null) {
                //se existir a movimentacao retornara false 
                return false;
            } else {
                //se nao existir movimentacao retornara true
                return true;
            }
        } catch (PDOException $e) {
            echo 'AQUI' . $e->getMessage();
        }
    }

    function insertCompraHold()
    {
        $iface = $this->inserir();
    }
}

$inserir = new movimentController;
$inserir->insertCompraHold();
*/


session_start();
require_once '../models/Movimentacoes.php';


class movimentsController
{
    
    private function isSetAllPostItems()
    {
        if (
            isset($_POST['id_ativo']) != 0 && isset($_POST['quantidade'])
            && isset($_POST['dt_compra']) && isset($_POST['preco_ativo'])
            && isset($_POST['qnt_ativos'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    private function guardarDados($objMoviments)
    {

        if ($this->isSetAllPostItems()) {



            //$objMoviments->setId_moviements($_POST['id_ativo']);  é inutil
            $objMoviments->setId_user($_SESSION['id_user']);
            $objMoviments->setId_ativo($_POST['id_ativo']);
            $objMoviments->setTp_operation($_POST['tpoperacao']);
            $objMoviments->setQnt_ativo($_POST['quantidade']);
            $objMoviments->setCompra_venda($_POST['tpcompra']);
            $objMoviments->setVlr_ativo($_POST['preco_ativo']);
            $objMoviments->setVlr_total($_POST['qnt_ativos']);
            $objMoviments->setDt_moviment($_POST['dt_compra']);
            $diaAtual = date('Y-m-d');
            $objMoviments->setDt_registro($diaAtual);

            return $objMoviments;
        }
    }

    private function validateOperation($TpOperation)
    {
        if ($TpOperation === 'swing' || $TpOperation === 'trade') {
            return true;
        } else {
            $_SESSION['erro'] = 'Erro ao validar operacao swing, ou trade';
            return false;
        }
    }

    private function validateBuyOrSale($byuOrSale)
    {

        if ($byuOrSale === 'compra' || $byuOrSale === 'venda') {
            return true;
        } else {
            $_SESSION['erro'] = 'Erro ao validar operacao de compra ou venda';
            return false;
        }
    }

    private function isNumberDecimal($arg)
    {
        $aa = count($arg) - 1;
        for ($i = 0; $i <= $aa; $i++) {

            if (!is_numeric($arg[$i])) {
                return false;
                $_SESSION['erro'] = 'Erro ao validar numero de opercao' + $arg;
                break;
            }
        }
        return true;
    }

    private function validatePostItems($ObjMoviment)
    {

        $bayOrSale = $ObjMoviment->getCompra_venda();
        if (!$this->validateBuyOrSale($bayOrSale)) {
            return false;
        }

        $arrayNumericOfValues = [$ObjMoviment->getVlr_ativo(), $ObjMoviment->getVlr_total()];
        if (!$this->isNumberDecimal($arrayNumericOfValues)) {
            return false;
        }

        $tpOperation = $ObjMoviment->getTp_operation();
        if (!$this->validateOperation($tpOperation)) {
            return false;
        }

        return true;
    }



    private function insertSwingBuy($objetoMoviements)
    {

        if ($this->hasAnyMoviement($objetoMoviements)) {

            $this->insertOneMore();
        } else {

            $this->insertFirstMovSwingBuy($objetoMoviements);
        }
    }

    private function insertSwingSel()
    {
    }

    private function insertTradeBuy()
    {
    }


    private function insertFirstMovSwingBuy($obj)
    {
        $con = require '../database/connection.php';
        $user = $obj->getId_user();
        $ativo = $obj->getId_ativo();
        $quantidade = $obj->getQnt_ativo();
        $dt_compra = $obj->getDt_moviment();
        $preco = $obj->getVlr_ativo();
        $precoTotal = $obj->getVlr_total();
        $tpperacao = true;
        $tpcompra = true;
        $dtRegistro = $obj->getDt_registro();


        $sql = "insert into moviements values(default,?,?,?,?,?,?,?,?,?)";
        $stmt = $con->prepare($sql);
        $result = $stmt->execute([$user , $ativo ,$tpperacao, $tpcompra, $quantidade , $preco, $precoTotal, $dt_compra, $dtRegistro ]);

        if ($result) {
            
            $this->insertFirstCM($obj);
        }

        
    }

    private function calculateFirstCM($precoTotal, $qntd){
        $cm = $precoTotal / $qntd;
        return $cm;
    }

    private function insertFirstCM($obj)
    {   
        $con = require '../database/connection.php';
        $sql = "insert into cm values(default,?, ?, ?, ? )";

        $stmt = $con->prepare($sql);
        $precoTotal = $obj->getVlr_total();
        $quantidade = $obj->getQnt_ativo();
        $cm = $this->calculateFirstCM($precoTotal, $quantidade);
        $id_moviement = $this->lastIdMoviment($obj);//vai pegar o id inserido anteriormente

        $result = $stmt->execute([$id_moviement,  $cm, $precoTotal, $quantidade]);
        

        if($result){
            $_SESSION['success'] = 'movimentacao adicionada com sucesso'; 
            header('Location: ../../src/movements/index.php');
            // return true; acho q n precisa
        }else{
            $_SESSION['erro'] = 'erro ao adicionar movimentacao';
            header('Location: ../../src/movements/index.php');
            //return false;
        }
    }

    private function insertOneMore()
    {
    }






    function insert()
    {
        $objetoMoviements = new Movimentacoes;
        $objetoMoviements = $this->guardarDados($objetoMoviements);

        if ($this->validatePostItems($objetoMoviements)) {

            if ($objetoMoviements->getCompra_venda() === 'compra') {

                if ($objetoMoviements->getTp_operation() === 'swing') {

                    $this->insertSwingBuy($objetoMoviements);
                } 

            } else if ($objetoMoviements->getCompra_venda() === 'venda') {

                if ($objetoMoviements->getTp_operation() === 'swing') {
                } else if ($objetoMoviements->getTp_operation() === 'trade') {
                }
            }
        }
    }





    private function hasAnyMoviement($objetoMoviements)
    {
        try {

            $idUser = $objetoMoviements->getId_user();
            $idAtivo = $objetoMoviements->getId_ativo();

            $con = require '../database/connection.php';

            $sql = "select id_moviements 
            from moviements 
            where id_ativo = $idAtivo 
            and id_user = $idUser";


            $result = $con->query($sql);

            $row = $result->fetch(PDO::FETCH_ASSOC);

            if (isset($row['id_moviements']) && isset($row['id_moviements']) != null) {
                //se exitir alguma movimentacao ira retornar true
                
                return true;
            } else {
                //se nao existir ira retornar false
                
                return false;
            }
        } catch (PDOException $e) {
            echo 'AQUI' . $e->getMessage();
        }
    }

    private function lastIdMoviment($obj)
    {
        //seleciono o id da ultima movimentacao feita para vincular a movimentacao com o cm usando esse id
        $id_user = $obj->getId_user();
        $id_ativo = $obj->getId_ativo();

        $con = require '../database/connection.php';
        $sql = "select max(id_moviements) as maximo from moviements where id_user = $id_user and id_ativo = $id_ativo";

        $result = $con->query($sql);

        $row = $result->fetch(PDO::FETCH_ASSOC);

        $id_moviement = $row['maximo'];
        return $id_moviement;
    }

}



    //tem q ver certo isso aqui
    /*
        $this->guardarDados();

        if($a->getTp_operation() == 'swing'){

            if($a->getCompra_venda() == 'compra'){

            }
            else if($a->getCompra_venda() == 'venda'){
                
            }

        }
        else if($a->getTp_operation() == 'trade'){

        }
        
        */

/*
private $qnt_ativo;
    private $vlr_ativo;
    private $vlr_total;
    private $dt_moviment;
    private $dt_registro;


$a = new movimentsController;
$objeto = $a->guardarDados();
print_r($objeto);
 */

$a = new movimentsController;
$a->insert();
