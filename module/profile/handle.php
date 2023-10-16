<?php 

session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/gerador_senha.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/mail.php';

//alterar usuario
if ($_POST['action'] == 'alterar-colab') {

   
    try {
        $Conexao = ConexaoMYSQL::getConnection();

        $doc = str_replace(array('.','-','/'), "", $_POST['doc']);
        $telefone = str_replace(array('.','-','(',')'), "", $_POST['telefone']);

            $update_str = null;
            if($_POST['novasenha'] != null and $_POST['novasenha'] != ""){
                $senha = crypt($_POST['novasenha'], '$1$N1EHVYY9$qz.BE7oyWWkD85o5uCvO8/');
                $update_str = ", USU_SENHA = '".$senha."', USU_DT_ATUALIZADO = '".date('Y-m-d H:i:s')."'";
            }
            
            if($Conexao->query("UPDATE QUALIDADE_USUARIO_LOGIN SET USU_NOME = '".$_POST['nome']."', 
                USU_DOC = '".$doc."', USU_TELEFONE = '".$telefone."' ".$update_str."  
                WHERE USU_ID = ".$_SESSION['USUID'])){
                    $_SESSION['USUNOME'] = $_POST['nome'];
                echo 1;
            }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}
