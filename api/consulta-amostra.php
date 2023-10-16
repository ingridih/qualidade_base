<?php

// TO DO, TESTAR A URL SE ENCONTRA

require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
$Conexao = ConexaoMYSQL::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];

        if (strpos($authorizationHeader, 'Bearer ') === 0) {
            $token = substr($authorizationHeader, 7);
            
            $query = $Conexao->query("SELECT T_DATA, T_TOKEN, USU_TIPO, USU_EMPRESAID 
                FROM TOKEN INNER JOIN QUALIDADE_USUARIO_LOGIN ON USU_ID = T_USUID WHERE T_TOKEN = '".$token."'");
            $row = $query->fetch();

            if(!empty($row)){
                if($row['T_DATA'] < date('Y-m-d')){
                    enviarRespostaErro('Seu token está vencido, gere um novo.', 400);
                }else{
                    if($_GET['acao'] == 'produto'){ 
                        if(isset($_GET['datade']) and isset($_GET['datade'])){
                            (isset($_GET['limit']) ? $limite = "LIMIT ".$_GET['datade'] : $limite = '');
                            ($row['USU_TIPO'] == 'LABORATORIO') ? $laboratorio = " AND CA_LABORATORIO = '".$row['USU_EMPRESAID']."'" : $laboratorio = '';
                            $retorno = retorna_produto($_GET['datade'], $_GET['datade'], $limite, $laboratorio);
                            enviarRespostaErro($retorno, 200);
                        }
                    }
                }
            }else {
                enviarRespostaErro('Chave de autenticação invalida.', 400);
            }
        } else {
            enviarRespostaErro('Formato inválido do cabeçalho Authorization. Use "Bearer [token]".', 400);
        }
    } else {
        enviarRespostaErro('Cabeçalho Authorization ausente na requisição.', 401);
    }
} else {
    enviarRespostaErro('Método de requisição não suportado. Use o método GET.', 405);
}

function enviarRespostaErro($mensagem, $statusCode) {
    $response = [
        'return' => $mensagem
    ];
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($response);
}


function retorna_produto($datade, $dataate, $limite, $laboratorio){

    $Conexao = ConexaoMYSQL::getConnection();

    $produto = array();

    $query = $Conexao->query("SELECT PO_ID, CA_ID, CA_STATUS, CA_DATA, CA_IDENTIFICACAO_QUALI, CA_IDENTIFICACAO_LAB, 
    PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_QTD, PO_DATA_FAB, PO_REG_MAPA, PO_DATA_VALIDADE, 
    PO_TIPO, PO_NOTA, PO_APROVADO 
    FROM QUALIDADE_PRODUTO 
    WHERE CA_DATA >= '".$datade."' AND CA_DATA <= '".$dataate."'   ".$laboratorio. $limite);
    $row = $query->fetch();
    if(!empty($row)){
        $query2 = $Conexao->query("SELECT AE_ELEMENTO, AE_GARANTIA, AE_RESULTADO, AE_VMA
        FROM QUALIDADE_ANALISE 
        WHERE AE_PRODUTO_ID = '".$row['PO_ID']."'");
        while($row2 = $query2->fetch()){
            $produto[] = [
                'elemento' => "'".$row2['AE_ELEMENTO']."'",
                'garantia' => "'".$row2['AE_GARANTIA']."'",
                'vma' => "'".$row2['AE_VMA']."'",
                'resultado' => "'".$row2['AE_RESULTADO']."'",
                'metodo' => "'".$row2['AE_METODO']."'"
            ];
        }

        $return[] = [
            'id' => "'".$row['CA_ID']."'",
            'status' => "'".$row['CA_STATUS']."'",
            'data' => "'".$row['CA_DATA']."'",
            'identificacao_qualidade' => "'".$row['CA_IDENTIFICACAO_QUALI']."'",
            'identificacao_laboratorio' => "'".$row['CA_IDENTIFICACAO_LAB']."'",
            'numero' => "'".$row['PO_NUMERO']."'",
            'produto' => "'".$row['PO_PRODUTO']."'",
            'produto_descricao' => "'".$row['PO_PROD_DESC']."'",
            'lote' => "'".$row['PO_LOTE']."'",
            'quantidade' => "'".$row['PO_QTD']."'",
            'data_fabricacao' => "'".$row['PO_DATA_FAB']."'",
            'reg_mapa' => "'".$row['PO_REG_MAPA']."'",
            'validade' => "'".$row['PO_DATA_VALIDADE']."'",
            'tipo' => "'".$row['PO_TIPO']."'",
            'nota' => "'".$row['PO_NOTA']."'",
            'aprovado' => "'".$row['PO_APROVADO']."'",
            'resultados' => [
                
            ]
        ];
    }else{
        $return = 'Nenhum resultado encontrado.';
    }

    echo $return;
    
}

?>
