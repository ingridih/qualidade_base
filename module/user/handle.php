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

// buscar lab
if ($_POST['action'] == 'busca-lab') {
    try {
        $resultado = '<option value="">Selecione um laboratório ou crie...</option>';
        $Conexao = ConexaoMYSQL::getConnection();
        
        $query2 = $Conexao->query("SELECT E_NOME, E_ID FROM QUALIDADE_LABORATORIO");
        while ($row = $query2->fetch()) {
            $resultado .= '<option value="'.$row['E_ID'].'">'.$row['E_NOME'].'</option>';
        }
        echo json_encode($resultado);
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

// cadastrar usuario. 
if ($_POST['action'] == 'cadastrar-colab') {

   
    try {
        $Conexao = ConexaoMYSQL::getConnection();

        $doc = str_replace(array('.','-','/'), "", $_POST['doc']);
        $telefone = str_replace(array('.','-','(',')'), "", $_POST['telefone']);
        if($_POST['usuarioid'] == null){ 
            $senha_gerada = gerar_senha(8, true, true, true, true);
            
            $senha = crypt($senha_gerada, '$1$N1EHVYY9$qz.BE7oyWWkD85o5uCvO8/');

                $query2 = $Conexao->query("SELECT * FROM QUALIDADE_USUARIO_LOGIN WHERE USU_EMAIL = '".$_POST['email']."'");
                $row = $query2->fetch();
                if(empty($row)){
                

                    if($_POST['tipo'] == 'LABORATORIO'){
                        $empresa_pertence =  $_POST['selectlab'];
                        $textempresa = $_POST['selectlab_text'];
                    }else{
                        $empresa_pertence = $_SESSION['USUID'];
                        $textempresa = $_SESSION['USUEMP'];
                    }

                
                if($Conexao->query("INSERT INTO QUALIDADE_USUARIO_LOGIN (USU_NOME, USU_EMAIL, USU_SENHA, USU_DOC, 
                    USU_TELEFONE, USU_ATIVO, USU_EMPRESAID, USU_EMPRESA_NOME, USU_TIPO, USU_DT_ATUALIZADO) 
                    VALUES 
                    ('".$_POST['nome']."', '".$_POST['email']."', '".$senha."', '".$doc."',
                    '".$telefone."', 'A', '".$empresa_pertence."', '".$textempresa."', '".$_POST['tipo']."', '".date('Y-m-d H:i:s')."')")){
                    
                    $idusuario = $Conexao->lastInsertId();

                    echo 1;

                    $assunto = "Novo Acesso - Diges";
                    $mensagem = 'Login: '.$_POST['email'].' <br> Senha: '.$senha_gerada.' <br>
                        Após realizar o primeiro acesso, troque sua senha. ';
                    $email = array($_SESSION['USUEMAIL'], $_POST['email']);

                    Envia_Email($assunto, $mensagem, $email, false);
                    
                }else{
                    echo 2;
                }
            }else{
                echo 3;
            }
        }else{
            if($_POST['tipo'] == 'LABORATORIO'){
                $empresa_pertence =  $_POST['selectlab'];
                $textempresa = $_POST['selectlab_text'];
            }else{
                $empresa_pertence = $_SESSION['USUID'];
                $textempresa = $_SESSION['USUEMP'];
            }
            
            if($Conexao->query("UPDATE QUALIDADE_USUARIO_LOGIN SET USU_NOME = '".$_POST['nome']."', 
                USU_DOC = '".$doc."', USU_TELEFONE = '".$telefone."', USU_ATIVO = 'A', 
                USU_EMPRESAID = '".$empresa_pertence."', USU_EMPRESA_NOME = '".$textempresa."', USU_TIPO = '".$_POST['tipo']."'  
                WHERE USU_ID = ".$_POST['usuarioid'])){
                echo 4;
            }else{
                echo 2;
            }
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

// apagar usuario
if ($_POST['action'] == 'apagar-usuario') {
    try {
        $Conexao = ConexaoMYSQL::getConnection();
        if($Conexao->query("DELETE FROM QUALIDADE_USUARIO_LOGIN WHERE USU_ID = ".$_POST['id'])){ 
            echo 1;
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}


// gerar nova senha e enviar por email 
if ($_POST['action'] == 'gerar_senha') {
    $Conexao = ConexaoMYSQL::getConnection();
    $randomPassword = gerar_senha(8, true, true, true, true);
    
    $hash = password_hash($randomPassword, PASSWORD_DEFAULT);

    $pass = crypt($randomPassword, $hash);
    $query2 = $Conexao->query("SELECT * FROM QUALIDADE_USUARIO_LOGIN WHERE USU_ID = '" . $_POST['id'] . "'");
    $row = $query2->fetch();
    if(!empty($row)){
     
        if($Conexao->query("UPDATE QUALIDADE_USUARIO_LOGIN SET USU_SENHA = '".$pass."' WHERE USU_ID = '" . $_POST['id'] . "'")){
            $assunto = 'Nova senha.';
            $mensagem = 'Sua nova senha de acesso: <strong>'.$randomPassword.'</strong><br> Lembre-se de trocar a senha o mais rápido possivel.';
            $email =  $row['USU_EMAIL'];
            Envia_Email($assunto, $mensagem, $email, false);
            echo 1;
        }else{
            echo 0;
        }
    }else{
        echo 0;
    }
        
}


