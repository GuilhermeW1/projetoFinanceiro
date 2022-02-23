<?php

session_start();

if (isset($_SESSION['erro']) && $_SESSION['erro'] != "") {
    echo "<script>alert('{$_SESSION["erro"]}')</script>";
    unset($_SESSION['erro']);
}


if (isset($_SESSION['user'])) {
    header('Location: ../../src/home/index.php');
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include('../../style/style.php') ?>
    <link rel="stylesheet" href="../../style/styleLogin.css">

</head>

<body>


    <div id="login">



        <h3 class="text-center text-white pt-5">Login form</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">

                        <form id="login-form" class="form" action="../../controllers/LoginController.php" method="post">
                            <h3 class="text-center text-info">Login</h3>

                            <div class="form-group">
                                <label for="user" class="text-info">Usuário:</label><br>
                                <input type="text" name="user" id="user" class="form-control" placeholder="Usuário" required>
                            </div>

                            <div class="form-group">
                                <label for="password" class="text-info">Senha:</label><br>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Senha" required>
                            </div>

                            <div class="form-group">
                                <!-- <input type="checkbox" name="teste" value="Checkbox"> -->
                                <label for="remember-me" class="text-info"><span>Lembre me</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Entrar">
                            </div>
                            <div id="register-link" class="form-group text-right">
                                <a href="create.php" rel="next" target="_self" class="text-info">Criar conta</a><br>


                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>