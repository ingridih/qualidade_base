<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/menu/session.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/gerador_senha.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/mail.php';

// criar ticket
if ($_POST['action'] == 'gravar') {

    try {
        $usutipo = null;
        $Conexao = ConexaoMYSQL_ticket::getConnection();

            $sql = "INSERT INTO TICKET (T_TIPO, T_URGENCIA, T_ASSUNTO, T_DESCRICAO, T_USUARIO, T_STATUS, T_EMAIL, T_EMAIL_SOLICITANTE, T_SOLICITANTE, T_URL) 
            VALUES (:tipo, :prioridade, :assunto, :descricao, :usuario, 'A', :email, :email_solicitante, :solicitante, :url)";
            
            $stmt = $Conexao->prepare($sql);

            // Substitua os $_POST pelos valores reais ou as variáveis que deseja usar
            $stmt->bindParam(':tipo', $_POST['tipo']);
            $stmt->bindParam(':prioridade', $_POST['prioridade']);
            $stmt->bindParam(':assunto', $_POST['assunto']);
            $stmt->bindParam(':descricao', $_POST['descricao']);
            $stmt->bindParam(':usuario', $_SESSION['USUID']);
            $stmt->bindParam(':email', $_POST['check']);
            $stmt->bindParam(':email_solicitante', $_SESSION['USUEMAIL']); // ou o valor desejado
            $stmt->bindParam(':solicitante', $_SESSION['USUNOME']); // ou o valor desejado
            $stmt->bindParam(':url', $_SESSION['URL']); // ou o valor desejado


            if($stmt->execute()){
                $id = $Conexao->lastInsertId();
                if (!empty($_FILES['files'])) {

                    $uploadedFiles = $_FILES['files'];
                    $count = 0;
                    foreach ($uploadedFiles['tmp_name'] as $index => $tmpName) {
                        $count++;
                        $path = $uploadedFiles['name'][$index];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $fileName = 'ticket'.$count .date('dmYHis').'.'.$ext;
                        $fileSize = $uploadedFiles['size'][$index];
                        $fileType = $uploadedFiles['type'][$index];

                        // Fazer algo com o arquivo, como movê-lo para um diretório de upload
                        $uploadPath = $_SERVER['DOCUMENT_ROOT'].'/file/ticket/' . $fileName;
                        move_uploaded_file($tmpName, $uploadPath);
                        
                        $sqlAnexo = "INSERT INTO TICKET_ANEXO (TA_TID, TA_ANEXO) VALUES (:id, :fileName)";
                        $stmtAnexo = $Conexao->prepare($sqlAnexo);
                        $stmtAnexo->bindParam(':id', $id);
                        $stmtAnexo->bindParam(':fileName', $fileName);
                        $stmtAnexo->execute();
                    }
                }
                $assunto = 'Chamado criado '.$id. ' - Empresa: '.$_SESSION['USUEMP'];
                $mensagem = 'Esse email está sendo enviado para notificar que o chamado foi criado. <br>'.$_SESSION['URL'];
                $email =  array($_SESSION['USUEMAIL'], 'ticket@diges.com.br');
                Envia_Email($assunto, $mensagem, $email, false);
            echo 1;
        }else{
            echo 'Não foi possivel criar o ticket.';
        }
     
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

//adicionar favorito ou remover.
if ($_POST['action'] == 'favorito') {
    $Conexao = ConexaoMYSQL_ticket::getConnection();

    $sqlUpdate = "UPDATE TICKET SET T_FAVORITO = :favorito WHERE T_ID = :id";
    $stmtUpdate = $Conexao->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':favorito', $_POST['favorito']);
    $stmtUpdate->bindParam(':id', $_POST['id']);
    if($stmtUpdate->execute()){
        echo 1;
    }else{
        echo 0;
    }
}


if ($_POST['action'] == 'responder') {

    try {
        $anexos = array();
        $usutipo = null;
        $Conexao = ConexaoMYSQL_ticket::getConnection();

        $sqlInsercao = "INSERT INTO TICKET (T_RESPOSTA, T_DESCRICAO, T_RESPOND_USU, T_STATUS, T_RESPONDEU) VALUES (:id, :descricao, :usuario, :tipo, :respondeu)";
        $stmtInsercao = $Conexao->prepare($sqlInsercao);
        $stmtInsercao->bindParam(':id', $_POST['id']);
        $stmtInsercao->bindParam(':descricao', $_POST['desc']);
        $stmtInsercao->bindParam(':usuario', $_SESSION['USUID']);
        $stmtInsercao->bindParam(':respondeu', $_SESSION['USUNOME']);
        $stmtInsercao->bindParam(':tipo', $_POST['tipo']);
        if($stmtInsercao->execute()){

            $id = $Conexao->lastInsertId();

            $sqlUpdate = "UPDATE TICKET SET T_STATUS = :tipo WHERE T_ID = :id";
            $stmtUpdate = $Conexao->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':tipo', $_POST['tipo']);
            $stmtUpdate->bindParam(':id', $_POST['id']);
            $stmtUpdate->execute();

            if (isset($_FILES['file']['name'])) {
                
                foreach ($_FILES['file']['name'] as $i => $filename) {
                    $path = $filename;
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $newfile = 'ticketr' . date('dmYHis') . '_' . $i . '.' . $ext;
            
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/file/ticket/' . $newfile)) {
                        move_uploaded_file($_FILES['file']['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'] . '/file/ticket/' . $newfile);
                        
                        $sqlAnexoInsercao = "INSERT INTO TICKET_ANEXO (TA_TID, TA_ANEXO) VALUES (:id, :anexo)";
                        $stmtAnexoInsercao = $Conexao->prepare($sqlAnexoInsercao);
                        $stmtAnexoInsercao->bindParam(':id', $id);
                        $stmtAnexoInsercao->bindParam(':anexo', $newfile);
                        $stmtAnexoInsercao->execute();
                    }
                    
                }
            }
            $assunto = 'Chamado '.$_POST['id'].' teve uma resposta adicionada.';
            $mensagem = 'Esse email está sendo enviado para notificar que o chamado houve uma alteração. Entre no sistema para verificar. <br>'.$_SESSION['URL'];
            $email =  array($_SESSION['USUEMAIL'], 'ticket@diges.com.br');
            Envia_Email($assunto, $mensagem, $email, false);

            echo 1;
        }else{
            echo 'Não foi possivel responder.';
        }
     
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

?>
