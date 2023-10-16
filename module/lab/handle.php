<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/gerador_senha.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/mail.php';

// cadastrar lab
if ($_POST['action'] == 'cadastrar-lab') {

    try {
        $usutipo = null;
        $Conexao = ConexaoMYSQL::getConnection();
        if($_POST['lab'] != ""){ 
            if($Conexao->query("INSERT INTO QUALIDADE_LABORATORIO (E_NOME, E_CRIADOPOR, E_ATIVO) 
                VALUES ('".$_POST['lab']."', '".$_SESSION['USUID']."', 'A')")){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 2;
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

// apagar lab
if ($_POST['action'] == 'apagar-lab') {

    try {
        $Conexao = ConexaoMYSQL::getConnection();
        if($_POST['id'] != ""){ 
            if($Conexao->query("DELETE FROM QUALIDADE_LABORATORIO WHERE E_ID = '".$_POST['id']."'")){
                $Conexao->query("DELETE FROM QUALIDADE_USUARIO_LOGIN WHERE USU_EMPRESAID = '".$_POST['id']."' AND USU_TIPO = 'LABORATORIO'");
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 2;
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

// editar nome do lab
if ($_POST['action'] == 'editar-lab') {

    try {
        $Conexao = ConexaoMYSQL::getConnection();
        if($Conexao->query("UPDATE QUALIDADE_LABORATORIO SET E_NOME = '".$_POST['nome']."', E_ATIVO = '".$_POST['status']."' WHERE E_ID = '".$_POST['id']."'")){
            echo 1;
        }else{
            echo 2;
        }
      
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}