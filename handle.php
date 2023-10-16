<?php 

// INGRID CENTIOLI
// 23/04/2023
// login 
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/gerador_senha.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/mail.php';

/* define o prazo do cache em 30 minutos */
session_cache_expire(30);
session_start();


// efetuar Login na página
if ($_POST['action'] == 'login') {
    $usu = $_POST['userlogin'];
    $pass = $_POST['userpass'];

    $return = 0;

    $_SESSION['USUPRODUTOS'] = null;
    $_SESSION['USUID'] = null;
    $_SESSION['USUATIVO'] = null;
    $_SESSION['USUADMIN'] = null;
    $_SESSION['USUNOME'] = null;
    $_SESSION['USUEMAIL'] = null;
    $_SESSION['USUEMP'] = null;
    $_SESSION['URL'] = null;
    $_SESSION['USUTIPO'] = NULL;

    try {
        $Conexao = ConexaoMYSQL::getConnection();
        $query = $Conexao->query("SELECT USU_ID, USU_NOME, USU_EMAIL, USU_SENHA, USU_ATIVO, USU_DOC, USU_ADMIN, USU_EMPRESA_NOME, USU_EMPRESAID, USU_TIPO  
            FROM QUALIDADE_USUARIO_LOGIN WHERE USU_EMAIL = '" . $usu . "'");
        if ($row = $query->fetch()) {

            if ((crypt($pass, $row['USU_SENHA'])) == $row['USU_SENHA']) {
                if ($row['USU_ATIVO'] == 'A') {

                    $_SESSION['USUID'] = $row['USU_ID'];
                    $_SESSION['USUATIVO'] = $row['USU_ATIVO'];
                    $_SESSION['USUADMIN'] = $row['USU_ADMIN'];
                    $_SESSION['USUNOME'] = $row['USU_NOME'];
                    $_SESSION['USUEMAIL'] = $row['USU_EMAIL'];
                    $_SESSION['USUEMP'] = $row['USU_EMPRESA_NOME'];
                    $_SESSION['USUTIPO'] = $row['USU_TIPO'];
                    $_SESSION['USUEMPID'] = (($row['USU_ADMIN'] == '1') ? $row['USU_ID'] : $row['USU_EMPRESAID']);
                    $_SESSION['URL'] = 'http://localhost';

                    $return = 1;
                }else if($row['USU_ATIVO'] == 'I'){
                    $return = 2;
                }
            }else{
                $return = 0;
            }
        }
        echo $return;
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}


// EFETUAR LOGOUT E REDIRECIONAR PARA A PÝGINA DE LOGIN.
if ($_POST['action'] == 'logout') {

    session_destroy();
    session_commit();
    echo 1;

}

// esqueci senha
if ($_POST['action'] == 'resetpassword') {
    $Conexao = ConexaoMYSQL::getConnection();
    $randomPassword = gerar_senha(8, true, true, true, true);
    
    $hash = password_hash($randomPassword, PASSWORD_DEFAULT);

    $pass = crypt($randomPassword, $hash);
    try{ 
        if($Conexao->query("UPDATE QUALIDADE_USUARIO_LOGIN SET USU_SENHA = '".$pass."' WHERE USU_EMAIL = '" . $_POST['email'] . "'")){
            $assunto = 'Esqueci a senha.';
            $mensagem = 'Sua nova senha de acesso: <strong>'.$randomPassword.'</strong><br> Lembre-se de trocar a senha o mais rápido possivel.';
            $email =  $_POST['email'];
            Envia_Email($assunto, $mensagem, $email, false);
            echo 1;
        }else{
            echo 0;
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
    
}



if ($_POST['action'] == 'gerar-token') {

    $key = generateRandomKey(32);

    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    $header = json_encode($header);
    $header = base64_encode($header);

    $payload = [
        'iss' => $_SESSION['URL'],
        'username' => $_SESSION['USUID'],
        'email' => $_SESSION['USUEMAIL'],
        'exp' =>  date('Y-m-d', strtotime('+90 days'))
    ];
    $payload = json_encode($payload);
    $payload = base64_encode($payload);

    $signature = hash_hmac('sha256', "{$header}.{$payload}", $key, true);
    $signature = base64_encode($signature);

    $token = "{$header}.{$payload}.{$signature}";

    $Conexao = ConexaoMYSQL::getConnection();
    $query = $Conexao->query("SELECT * FROM TOKEN WHERE T_USUID = '" . $_SESSION['USUID'] . "'");
    $row = $query->fetch();
    if(!empty($row)){
        $Conexao->query("UPDATE TOKEN SET T_USUID = '" . $_SESSION['USUID'] . "', T_EMAIL = '".$_SESSION['USUEMAIL']."', T_URL = '".$_SESSION['URL']."', T_DATA = '".date('Ymd', strtotime('+90 days'))."', 
        T_TOKEN = '".$token."', T_KEY = '".$key."'
        WHERE T_USUID = '" . $_SESSION['USUID'] . "'");
    }else{
        $Conexao->query("INSERT INTO TOKEN (T_USUID, T_EMAIL, T_URL, T_DATA, T_TOKEN, T_KEY) 
        VALUES ('" . $_SESSION['USUID'] . "', '".$_SESSION['USUEMAIL']."', '".$_SESSION['URL']."', '".date('Ymd', strtotime('+2 days'))."', '".$token."', '".$key."')");
    }

    echo $token;

}

if ($_POST['action'] == 'buscatoken') {

    $Conexao = ConexaoMYSQL::getConnection();
    $query = $Conexao->query("SELECT * FROM TOKEN WHERE T_USUID = '" . $_SESSION['USUID'] . "' AND T_EMAIL = '".$_SESSION['USUEMAIL']."' AND T_URL = '".$_SESSION['URL']."'");
    $row = $query->fetch();
    if(!empty($row)){
        if(date('Y-m-d') > $row['T_DATA']){
            echo 'Token vencido, gere um novo.';
        }else{
            echo $row['T_TOKEN'];
        }
    }else{
        echo 'Gere um token.';
    }
}

function generateRandomKey($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_-+=<>?';
    $randomKey = '';
    
    $characterCount = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomKey .= $characters[rand(0, $characterCount - 1)];
    }
    
    return $randomKey;
}


// Busca dados do histórico
if ($_POST['action'] == 'carrega_historico') {
    $html = "";
    $array_status = array(
        '1' => 'Análise Criado',
        '2' => 'Análise Analisada',
        '3' => 'Análise Finalizada',
        '11' => 'Análise Cancelada',
        '12' => 'Análise Editada',
        '13' => 'Analise. Resultado editado',
        '4' => 'Laudo Alterado',
        '14' => 'Laudo configurado',
        '5' => 'Usuário Criado',
        '6' => 'Usuário Editado',
        '7' => 'Usuário Apagado',
        '8' => 'Laboratório Cadastrado',
        '9' => 'Laboratório Apagado',
        '10' => 'Elemento Cadastrado',
        '15' => 'Laudo anexado',
        '16' => 'Laudo apagado'
    );

    $Conexao = ConexaoMYSQL::getConnection();
    try {
        $query2 = $Conexao->query("SELECT H_ID, H_USUID, H_USUNOME, H_TIPO, H_DESCRICAO, DATE_FORMAT(H_DATA,'%d/%m/%Y') H_DATA
        FROM HISTORICO order by H_DATA desc limit 20");
        while($row2 = $query2->fetch()){ 
        
            $html .= '
            <div class="timeline-item">
                <div class="timeline-line w-40px"></div>
                <div class="timeline-icon symbol symbol-circle symbol-40px me-4">
                    <div class="symbol-label bg-light">
                        <i class="ki-duotone ki-message-text-2 fs-2 text-gray-500">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                        </i>
                    </div>
                </div>
                <div class="timeline-content mb-10 mt-n1">
                    <div class="pe-3 mb-5">
                        <div class="fs-5 fw-semibold mb-2">'.$array_status[$row2['H_TIPO']].'</div>
                        <div class="fs-6 text-gray-600">'.$row2['H_DESCRICAO'].'</div>
                        <div class="d-flex align-items-center mt-1 fs-6">
                            <div class="text-muted me-2 fs-7">'.$row2['H_USUNOME'].' - '.$row2['H_DATA'].'</div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        echo json_encode($html);
       
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

?>