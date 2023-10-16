<?php 
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/gerador_senha.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/mail.php';

// cadastrar elemento
if ($_POST['action'] == 'cadastrar-elemento') {

    try {
        $usutipo = null;
        $Conexao = ConexaoMYSQL::getConnection();
        if($Conexao->query("INSERT INTO QUALIDADE_ELEMENTOS_QUIMICOS (EQ_NOME, EQ_SIGLA, EQ_TIPO, EQ_VMA, EQ_USUARIO, EQ_DATA) 
            VALUES ('".$_POST['nome']."', '".$_POST['sigla']."', '".$_POST['tipo']."', '".$_POST['vma']."', '".$_SESSION['USUID']."', '".date('Y-m-d H:i:s')."')")){
            echo 1;
        }else{
            echo 2;
        }
     
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

// apagar elemento
if ($_POST['action'] == 'apagar-elemento') {

    try {
        $Conexao = ConexaoMYSQL::getConnection();
        if($_POST['id'] != ""){ 
            if($Conexao->query("DELETE FROM QUALIDADE_ELEMENTOS_QUIMICOS WHERE EQ_ID = '".$_POST['id']."'")){
               
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

// editar elemento
if ($_POST['action'] == 'editar-elemento') {

    try {
        $Conexao = ConexaoMYSQL::getConnection();
        if($Conexao->query("UPDATE QUALIDADE_ELEMENTOS_QUIMICOS SET EQ_NOME = '".$_POST['nome']."', EQ_SIGLA = '".$_POST['sigla']."', EQ_TIPO = '".$_POST['tipo']."', EQ_VMA = '".$_POST['vma']."', EQ_USUARIO = '".$_SESSION['USUID']."',
            EQ_DATA = '".date('Y-m-d H:i:s')."'  
            WHERE EQ_ID = '".$_POST['id']."'")){
            echo 1;
        }else{
            echo 2;
        }
      
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}


// editar elemento
if ($_POST['action'] == 'busca-elemento') {

    try {
        $Conexao = ConexaoMYSQL::getConnection();
        $query = $Conexao->query("SELECT * FROM QUALIDADE_ELEMENTOS_QUIMICOS WHERE EQ_ID = '".$_POST['id']."'");
        $row = $query->fetch();
        if(!empty($row)){ 
            echo json_encode(array('elemento' => $row['EQ_NOME'], 'sigla' => $row['EQ_SIGLA'], 'tipo' => $row['EQ_TIPO'], 'vma' => $row['EQ_VMA']));
        }else{
            echo 2;
        }
      
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}