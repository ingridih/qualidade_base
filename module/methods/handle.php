<?php 

session_start();
if (!isset($_SESSION['USUID'])) {
    header('Location: /login'); die;
}
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';

// cadastrar metodo. 
if ($_POST['action'] == 'cadastrar-metodo') {
    try {
        $Conexao = ConexaoMYSQL::getConnection();

            $query2 = $Conexao->query("SELECT * FROM METODO_ANALISE WHERE MA_METODO = '".$_POST['metodo']."'");
            $row = $query2->fetch();
            if(empty($row)){
            
            if($Conexao->query("INSERT INTO METODO_ANALISE (MA_METODO, MA_USUARIO) VALUES ('".$_POST['metodo']."', '".$_SESSION['USUID']."')")){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

// cadastrar usuario. 
if ($_POST['action'] == 'apagar-metodo') {
    try {
        $Conexao = ConexaoMYSQL::getConnection();

        if($Conexao->query("DELETE FROM METODO_ANALISE WHERE MA_ID = '".$_POST['id']."'")){
            echo 1;
        }else{
            echo 2;
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

