<?php

session_start();
if(isset($_POST['id_ativo']) != 0 && isset($_POST['quantidade']) 
&& isset($_POST['dt_compra']) && isset($_POST['preco_ativo']) 
&& isset($_POST['qnt_ativos'])){
    
    $ativo = $_POST['id_ativo'];
    $quantidade = $_POST['quantidade'];
    $dt_compra = $_POST['dt_compra'];
    $preco = $_POST['preco_ativo'];
    $precoTotal = $_POST['qnt_ativos'];
    
    $firstMov = verificaPrimeiroRegistro($_SESSION['id_user'], $ativo);

    
        try{
        $con =require '../database/connection.php';

        $sql = "insert into moviements values(default, ? , ? ,true, true, ? , ?, ?, ?, '2022/01/11'  )";
        $stmt = $con->prepare($sql);
        $result = $stmt->execute([$_SESSION['id_user'], $ativo, $quantidade, $preco, $precoTotal, $dt_compra]);
        }catch(PDOException $e){
            echo 'erro '. $e->getMessage();
        }

    if($result){
        $id_moviement = maxIdMoviement();

        
        if(!$firstMov){
            //vai pegar o ultimo id inserido no banco que sera o id adicionado a cima
            
            //vai descontar um para pegar o valor id do valor antes de ser adicionado o de cima
            //idmovimentacao old
            
            try{
            $id_old = penultimoInsert($ativo ,$_SESSION['id_user']);

            
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

            $result = $stmt->execute([$id_moviement,  $cm, $total, $qntd ]);
            }catch(PDOException $e){
                echo "erro" .$e->getMessage();
            }
            if($result){
                $_SESSION['success'] = "cm inserido com sucesso";
                header('Location: ../../src/movements/index.php');

            }else{
                $_SESSION['erro'] = "erro ao inserir cm";
                header('Location: ../../src/movements/index.php');
                
            }

            /*
            $stmt = $con->prepare($sql);
            
            if($result = $stmt->execute([$id_old])){

                $row = $result->fetch(PDO::FETCH_ASSOC);
                
                $oldTotal = $row['total'];


            }
            */

        }else{

            try{
            $sql = "insert into cm values(default,?, ?, ?, ? )";

            $stmt = $con->prepare($sql);
            
            $cm = $precoTotal/$quantidade;//se atenta nessa linha

            $result = $stmt->execute([$id_moviement,  $cm, $precoTotal, $quantidade ]);
            }catch(PDOException $e){
                echo "erro " . $e->getMessage();
            }
            if($result){
                $_SESSION['success'] = "cm inserido com sucesso";
                header('Location: ../../src/movements/index.php');

            }else{
                $_SESSION['erro'] = "erro ao inserir cm";
                header('Location: ../../src/movements/index.php');
                
            }




        }


        
    }else{
        $_SESSION['erro'] = "erro ao inserir movimentação";
        header('Location: ../../src/movements/index.php');
        
        
    }

    
}


//a funcao ira verificar se ja ha algum registro desse usuario utilizando 
//algum do astivo para que na primeira vez o cm n seja calculado somando 
//novamente as coisas
function verificaPrimeiroRegistro($idUser, $idAtivo){
    try{
        $con =require '../database/connection.php';

        $sql = "select id_moviements 
        from moviements 
        where id_ativo = $idAtivo 
        and id_user = $idUser";

        
        $result = $con->query($sql);

        $row = $result->fetch(PDO::FETCH_ASSOC);

        if(isset($row['id_moviements']) && isset($row['id_moviements']) != null ){
            //se existir a movimentacao retornara false 
            return false;
        }else{
            //se nao existir movimentacao retornara true
            return true;
        }



    }catch(PDOException $e){
        ECHO 'AQUI'. $e->getMessage();
    }
}

function maxIdMoviement(){
    //AQUI EU SELECIONO O ULTIMO INDICE DE MOVIMENTACOES PARA USAR NA CM
    $con =require '../database/connection.php';
    $sql = "select max(id_moviements) as maximo from moviements";

    $result = $con->query($sql);

    $row = $result->fetch(PDO::FETCH_ASSOC);

    $id_moviement = $row['maximo'];
    return $id_moviement;

}


//vai retornar o ultimo indixe, vai buscar 2 e o laco vai achar o ultimo
//no caso ele vai pegar o insert penultimo que sera necessario para calcular o cm
function penultimoInsert($idAtivo, $idUser){
    
    $con =require '../database/connection.php';
    $sql = "select id_moviements
    from moviements 
    where id_ativo = $idAtivo
    and id_user = $idUser
    order by id_moviements desc
    limit 2";

    $result = $con->query($sql);

    

    $id_moviement;
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $id_moviement = $row['id_moviements'];
    }


    return $id_moviement;

}







