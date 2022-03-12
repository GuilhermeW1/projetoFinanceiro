<?php


session_start();
require_once '../models/Movimentacoes.php';


class movimentsController 
{

    /*
        da pra fazer assim 
        
        private $variavel

        e chamra dentro do metodo assim $this->variavel  *sem o sifrao
    */
    private $varr;

    function insert()
    {
        if ($this->isSetAllPostItems() && $this->validatePostItems()) {

            $objMoviements = new Movimentacoes;
            $objMoviements = $this->guardarDados($objMoviements);

            $this->rediretcTpOperation($objMoviements);
        }
    }

    ///VALIDACOES


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

    private function validatePostItems()
    {
        $arrayNambersOfPost = [$_POST['quantidade'], $_POST['preco_ativo'], $_POST['qnt_ativos']];
        if (!$this->isNumberDecimal($arrayNambersOfPost)) {
            return false;
        }

        if (!$this->validateOperation()) {
            return false;
        }

        return true;
    }

    private function isNumberDecimal($arrayNambersOfPost)
    {
        $aa = count($arrayNambersOfPost) - 1;
        for ($i = 0; $i <= $aa; $i++) {

            if (!is_numeric($arrayNambersOfPost[$i])) {
                return false;
                $_SESSION['erro'] = 'Erro ao validar numero de opercao' + $arrayNambersOfPost;
                break;
            }
        }
        return true;
    }


    private function validateOperation()
    {
        $tpOperation = $_POST['tpoperacao'];

        if ($tpOperation === 'compra' || $tpOperation === 'trade' || $tpOperation === 'venda') {
            return true;
        } else {
            $_SESSION['erro'] = 'Erro ao validar operacao swing, ou trade';
            return false;
        }
    }


    private function guardarDados($objMoviments)
    {
        $objMoviments->setTpOperation($this->defineTpOperation());

        $objMoviments->setIdUser($_SESSION['idUser']);
        $objMoviments->setIdAtivo($_POST['id_ativo']);
        $objMoviments->setQntAtivo($_POST['quantidade']);
        $objMoviments->setVlrAtivo($_POST['preco_ativo']);
        $objMoviments->setVlrTotal($_POST['qnt_ativos']);
        $objMoviments->setDtaMoviment($_POST['dt_compra']);

        $diaAtual = date('Y-m-d');
        $objMoviments->setDtaRecord($diaAtual);

        return $objMoviments;
    }

    private function defineTpOperation()
    {
        $tpOperation = $_POST['tpoperacao'];

        if ($tpOperation === 'compra') {
            return 1;
        }
        if ($tpOperation === 'venda') {
            return 2;
        }
        if ($tpOperation === 'trade') {
            return 3;
        }
    }

    private function rediretcTpOperation($objetoMoviements)
    {   
        if ($objetoMoviements->getTpOperation() === 1) {//compra

            $this->insertSwingCompra($objetoMoviements);

        }

        if ($objetoMoviements->getTpOperation() === 2) {//venda

           $this->insertSwingSell($objetoMoviements);

        }
        if ($objetoMoviements->getTpOperation() === 1) {//trade

            //$this->insertSwingCompra($objetoMoviements);

        }
    }

    private function insertSwingCompra($objetoMoviements)
    {

        if ($this->isTheFirstInsertOfTheUserWithThisAsset($objetoMoviements)) {
            $this->insertFirstMovSwingBuy($objetoMoviements);
        } else {
            $this->insertOneMoreSwingBuy($objetoMoviements);
        }
    }

    private function isTheFirstInsertOfTheUserWithThisAsset($objetoMoviements)
    {
        try {

            $idUser = $objetoMoviements->getIdUser();
            $idAtivo = $objetoMoviements->getIdAtivo();

            $con = require '../database/connection.php';

            $sql = "select idMoviement 
            from moviements 
            where idAtivo = $idAtivo 
            and idUser = $idUser";


            $result = $con->query($sql);

            $row = $result->fetch(PDO::FETCH_ASSOC);

            if (isset($row['idMoviement']) && isset($row['idMoviement']) != null) {
                //IF IS THE USER HAVS ANY ASSET WITH RETURN FALSE

                return false;
            } else {
                //IF THE USER HAVS NOT THIS ASSET RETURN TRUE; SAYING THIS IS HER USER FIRST 
                //INSERT USING THE ASSET

                return true;
            }
        } catch (PDOException $e) {
            echo 'AQUI' . $e->getMessage();
        }
    }

    private function insertFirstMovSwingBuy($obj)
    {
        $con = require '../database/connection.php';

        $user = $obj->getIdUser();
        $ativo = $obj->getIdAtivo();
        $quantidade = $obj->getQntAtivo();
        $dt_compra = $obj->getDtaMoviment();
        $preco = $obj->getVlrAtivo();
        $precoTotal = $obj->getVlrTotal();

        $tpperacao = $obj->getTpOperation();
        
        $dtRegistro = $obj->getDtaRecord();

        $sql = "insert into moviements values(default,?,?,?,?,?,?,?,?)";
        $stmt = $con->prepare($sql);
        $result = $stmt->execute([$user, $ativo, $quantidade, $preco, $precoTotal, $dt_compra, $dtRegistro, $tpperacao]);

        if ($result) {
            $this->insertFirstCM($obj);
        }
    }

    private function insertFirstCM($obj)
    {
        $con = require '../database/connection.php';
        $sql = "insert into cm values(default,?, ?, ?, ? )";

        $stmt = $con->prepare($sql);
        $precoTotal = $obj->getVlrTotal();
        $quantidade = $obj->getQntAtivo();
        $cm = $this->calculateFirstCM($precoTotal, $quantidade);
        $id_moviement = $this->lastIdMoviment($obj); //vai pegar o id inserido anteriormente

        $result = $stmt->execute([$id_moviement,  $cm, $precoTotal, $quantidade]);


        if ($result) {
            $_SESSION['success'] = 'movimentacao adicionada com sucesso';
            header('Location: ../../src/movements/index.php');
            // return true; acho q n precisa
        } else {
            $_SESSION['erro'] = 'erro ao adicionar movimentacao';
            header('Location: ../../src/movements/index.php');
            //return false;
        }
    }

    private function calculateFirstCM($precoTotal, $qntd)
    {
        $cm = $precoTotal / $qntd;
        return $cm;
    }

    //INSERT SWING BUY WHERE THE USER HAS MANY INSERTS
    private function insertOneMoreSwingBuy($obj)
    {
        $argsLastInsert = $this->lastInsert($obj);

        $con = require '../database/connection.php';
        $user = $obj->getIdUser();
        $ativo = $obj->getIdAtivo();
        $quantidade = $obj->getQntAtivo();
        $dt_compra = $obj->getDtaMoviment();
        $preco = $obj->getVlrAtivo();
        $precoTotal = $obj->getVlrTotal();
        $tpperacao = $obj->getTpOperation();
        
        $dtRegistro = $obj->getDtaRecord();

        $sql = "insert into moviements values(default,?,?,?,?,?,?,?,?)";
        $stmt = $con->prepare($sql);
        $result = $stmt->execute([$user, $ativo, $quantidade, $preco, $precoTotal, $dt_compra, $dtRegistro, $tpperacao]);

        if ($result) {
            $this->insertCM($obj, $argsLastInsert);
        }
    }

    private function insertCM($obj, $arg)
    {
        
    
            $vlrTotalAnterior = $arg['vlrTotal'];
            $qntTotalAnterior = $arg['qntdAtivos'];
    
        

        $con = require '../database/connection.php';
        $sql = "insert into cm values(default,?, ?, ?, ? )";

        $stmt = $con->prepare($sql);

        $precoTotal = $obj->getVlrTotal() + $vlrTotalAnterior;
        $quantidade = $obj->getQntAtivo() + $qntTotalAnterior;

        $cm = $this->calculateCM($precoTotal, $quantidade, $arg);



        $id_moviement = $this->lastIdMoviment($obj); //vai pegar o id inserido anteriormente

        $result = $stmt->execute([$id_moviement,  $cm, $precoTotal, $quantidade]);


        if ($result) {
            $_SESSION['success'] = 'movimentacao adicionada com sucesso';
            header('Location: ../../src/movements/index.php');
            // return true; acho q n precisa
        } else {
            $_SESSION['erro'] = 'erro ao adicionar movimentacao';
            header('Location: ../../src/movements/index.php');
            //return false;
        }
    }

    private function calculateCM($precoTotal, $quantidade, $arg)
    {
        
            $vlrTotalAnterior = $arg['vlrTotal'];
            $qntTotalAnterior = $arg['qntdAtivos'];
        

        $cm = ($vlrTotalAnterior + $precoTotal) / ($qntTotalAnterior + $quantidade);
        return $cm;
    }


    private function lastInsert($obj)
    {
        $idAtivo = $obj->getIdAtivo();
        $idUser = $obj->getIdUser();

        $con = require '../database/connection.php';

        $sql = "select vlrTotal, qntdAtivos
        from moviements 
        where idAtivo = '$idAtivo'
        and idUser = '$idUser'
        order by idMoviement desc
        limit 1";

        $result = $con->query($sql);



        $id_moviement;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id_moviement = $row;
        }


        return $id_moviement;
    }




    private function lastIdMoviment($obj)
    {
        //seleciono o id da ultima movimentacao feita para vincular a movimentacao com o cm usando esse id
        $id_user = $obj->getIdUser();
        $id_ativo = $obj->getIdAtivo();

        $con = require '../database/connection.php';
        $sql = "select max(idMoviement) as maximo from moviements where idUser = $id_user and idAtivo = $id_ativo";

        $result = $con->query($sql);

        $row = $result->fetch(PDO::FETCH_ASSOC);

        $id_moviement = $row['maximo'];
        return $id_moviement;
    }


    private function insertSwingSell($obj){

        if($this->isTheFirstInsertOfTheUserWithThisAsset($obj)){
            $_SESSION['erro'] = "Voce nao possui nenhuma movimentacao com esse ativo";
        }else{
            
            $argsLastInsert = $this->lastInsert($obj);

            $con = require '../database/connection.php';
            $user = $obj->getIdUser();
            $ativo = $obj->getIdAtivo();
            $quantidade = $obj->getQntAtivo();
            $dt_compra = $obj->getDtaMoviment();
            $preco = $obj->getVlrAtivo();
            $precoTotal = $obj->getVlrTotal();
            $tpperacao = $obj->getTpOperation();
            
            $dtRegistro = $obj->getDtaRecord();
    
            $sql = "insert into moviements values(default,?,?,?,?,?,?,?,?)";
            $stmt = $con->prepare($sql);
            $result = $stmt->execute([$user, $ativo, $quantidade, $preco, $precoTotal, $dt_compra, $dtRegistro, $tpperacao]);
    
            if ($result) {
                $this->insertCM($obj, $argsLastInsert);
            }

        }
    }
}


$a = new movimentsController;
$a->insert();
