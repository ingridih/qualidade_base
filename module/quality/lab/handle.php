<?php 

session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/gerador_senha.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/mail.php';

// cadastrar linha de metodo e elemento
if ($_POST['action'] == 'adicionar-linha') {

    $id = $_POST['id'];
    $elemento = $_POST['elemento'];
    $sigla = $_POST['sigla'];
    $resultado = $_POST['resultado'];
    $metodo = $_POST['metodo'];
   $retorno = '<div class="form-group row mb-5">
        <div class="col-md-2">
            <input type="hidden" name="inputhidden" value="'.$id.'" />
            <input type="text" class="form-control elemento" id="" name="elemento" placeholder="" value="'.$sigla.' - '.$elemento.'" disabled>
        </div>
        <div class="col-md-2 fv-row">
            <input type="number" class="form-control result" min="0" name="result" placeholder="Insira o resultado" id="'.$id.'|'.'" value="'.$resultado.'" disabled>
        </div>
        <div class="col-md-4 fv-row">
            <select type="text" class="form-control metodo" name="metodo" disabled>
                <option value="'.$metodo.'" selected>'.$metodo.'</option>
            </select>
        </div>
        <div class="col-md-4">
            <button id="'.$id.'" type="button" class="btn btn-danger btn-remover" name="btn-remover">
                <span class="indicator-label">
                    Apagar Linha
                </span>
            </button>
        </div>
    </div>';

    echo json_encode($retorno);
}

// ENVIAR RESULTADO DA ANALISE
if ($_POST['action'] == 'cadastrar-analise') {
    $return = null;
    try {
        $Conexao = ConexaoMYSQL::getConnection();

        foreach($_POST['op'] as $o){
            $op = explode('|', $o);
            
            
            if($Conexao->query("UPDATE QUALIDADE_PRODUTO 
                SET PO_OBS_LAB = '".$op[1]."', PO_OBS_LAUDO = '".$op[2]."', PO_ID_LAB = '".$_SESSION['USUEMPID']."' 
                WHERE PO_ID = ".$op[0])){

                foreach($_POST['elementos'] as $el){
                    $e = explode('|', $el);
                    if($e[1] != ""){
                        $Conexao->query("UPDATE QUALIDADE_ANALISE 
                        SET AE_RESULTADO = '".$e[3]."', AE_METODO = '".$e[4]."' 
                        WHERE AE_ID = ".$e[1]);
                    }else{ 
                        $Conexao->query("INSERT INTO QUALIDADE_ANALISE 
                        (AE_PRODUTO_ID, AE_ELEMENTO, AE_RESULTADO, AE_METODO) 
                        VALUES ('".$e[0]."', '".$e[2]."', '".$e[3]."', '".$e[4]."')");
                        
                    }
                }
                if($Conexao->query("UPDATE QUALIDADE_CARTA 
                SET CA_IDENTIFICACAO_LAB = '".$_POST['identlab']."', CA_OBS_LAB = '".$_POST['obslab']."', CA_STATUS = 'B', CA_ANALISE = '".date('Y-m-d H:i:s')."' 
                WHERE CA_ID = ".$_POST['idct'])){
                    $return = 1;
                    registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 2, 'Análise Analisada ID: '.$_POST['idct']);
                }
                // status B =  analisado
            }
        }
        $query_email = $Conexao->query("SELECT USU_EMAIL, USU_NOME 
            FROM QUALIDADE_CARTA 
            LEFT JOIN QUALIDADE_USUARIO_LOGIN ON CA_SOLICITANTE = USU_ID 
            WHERE CA_ID = '" . $_POST['idct'] . "'");
        if ($row_email = $query_email->fetch()) {
            $assunto = "Análise Realizada ".$_POST['idct'];
            $mensagem = 'A análise foi realizada por  '.$_SESSION['USUNOME'];
            $email = $row_email['USU_EMAIL'];
    
            Envia_Email($assunto, $mensagem, $email, false);
        }

        

       
    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    echo $return;

}

// GERAR CT E FAZER DOWNLOAD
if ($_POST['action'] == 'gerarct') {
    
    $arrayAmostra = array();
    $arrayElementos = array();
    $arrayEL = array();
    
    $solicitante = null;
    $emailsol = null;
    $empresa = null;
    $idcarta = null;

    $Conexao = ConexaoMYSQL::getConnection();
    $query = $Conexao->query("SELECT CA_ID, CA_STATUS, CA_SOLICITANTE, CA_TIPO, CA_IDENTIFICACAO_QUALI, CA_IDENTIFICACAO_LAB,
            CA_LABORATORIO, CA_URGENCIA, CA_OBS_QUALI, CA_OBS_LAB, CA_DATA, USU_NOME, USU_EMPRESA_NOME, USU_EMAIL, 
            PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_NOTA, DATE_FORMAT(PO_DATA_FAB,'%d/%m/%Y') PO_DATA_FAB, PO_TIPO, 
            DATE_FORMAT(PO_DATA_VALIDADE,'%d/%m/%Y') PO_DATA_VALIDADE, CA_OBS_QUALI, CA_URGENCIA, PO_ID, PO_REG_MAPA, PO_OBS_QUALI 
        FROM QUALIDADE_CARTA 
        INNER JOIN QUALIDADE_PRODUTO ON PO_ID_CARTA = CA_ID
        LEFT JOIN QUALIDADE_USUARIO_LOGIN ON USU_ID = CA_SOLICITANTE 
        WHERE CA_ID = '".$_POST['id_ct']."' ORDER BY PO_ID");
    while ($row = $query->fetch()) {
        $arrayAmostra[] = $row;
        $solicitante = $row['USU_NOME'];
        $emailsol = $row['USU_EMAIL'];
        $empresa = $row['USU_EMPRESA_NOME'];
        $idcarta = $row['CA_ID'].' - '.$row['CA_IDENTIFICACAO_QUALI'];

        $query2 = $Conexao->query("SELECT AE_ID, AE_PRODUTO_ID, AE_ELEMENTO_ID, AE_ELEMENTO, AE_GARANTIA
        FROM QUALIDADE_ANALISE 
        WHERE AE_ELEMENTO_ID IS NOT NULL AND AE_PRODUTO_ID = '".$row['PO_ID']."' ORDER BY AE_PRODUTO_ID");
        while ($row2 = $query2->fetch()) {
            $arrayElementos[$row2['AE_PRODUTO_ID']][] = $row2;
            $arrayEL[] = $row2['AE_ELEMENTO'];
            $arrayPO[] = $row2['AE_PRODUTO_ID'];
        }
    }
    
    // GERAR PDF ***************************************************
    
    require_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
    require $_SERVER['DOCUMENT_ROOT']."/vendor/mpdf/mpdf/src/Mpdf.php";

    // Create an instance of the class:
    $mpdf = new \Mpdf\Mpdf();


    $html = null;

    $html = '
        <link href="'.$_SESSION['URL'].'/vendor/mpdf/mpdf/mpdf.css" type="text/css" rel="stylesheet" media="mpdf" />
        <h3> Solicitação de Análise.</h3>
        
        <table class="table table-bordered font-san">
            <tbody style="width: 100%">
                <tr>
                    <td><p class="phon">Carta Identificação: </p>'.$idcarta.'</td>
                </tr>
                <tr>
                    <td><p class="phon">Empresa/Data: </p>'.$empresa.' - '.date('d/m/Y').'</td>
                </tr>
                <tr>
                    <td><p class="phon">Solicitante:</p>'. $solicitante .' - '. $emailsol.'</td>
                </tr>
            </tbody>
        </table>
        

        <table class="table table-bordered font-san">
        <tbody style="width: 100%">
            <tr>
                <td class="font-td"><b>ANÁLISES / PRODUTO (AM)</b></td>';
                
                    $amostra = 1;
                    foreach($arrayAmostra as $a){
                        $html .= '<td style="text-align: center;"><b>AM: '.$amostra.'</b></td>';
                        $amostra++;
                    }
            $html .= '</tr>';
                    
            foreach (array_unique($arrayEL) as $el) {
                $html .= '<tr>';
                $html .= '<td><p class="font-td"><b>' . $el . '</b></p></td>';
            
                foreach (array_unique($arrayPO) as $po) {
                    $elementoPresente = false;
            
                    foreach ($arrayElementos[$po] as $analise) {
                        if ($analise['AE_ELEMENTO'] == $el) {
                            $html .= '<td style="text-align: center;"><p class="font-td">'. $analise['AE_GARANTIA'] . '</p></td>';
                            $elementoPresente = true;
                            break; // Uma vez que encontramos o elemento, não precisamos continuar o loop de análises
                        }
                    }
            
                    if (!$elementoPresente) {
                        $html .= '<td style="text-align: center;"></td>';
                    }
                }
            
                $html .= '</tr>';
            }
        $html .= '</tbody>
        </table>
        
        <table class="table table-bordered font-san">
            <tbody style="width: 100%">
                <tr>
                    <td class="font-td">AM Nº</td>
                    <td class="font-td">Produto</td>
                    <td class="font-td">Lote</td>
                    <td class="font-td">Nota</td>
                    <td class="font-td">Data Fab</td>
                    <td class="font-td">Validade</td>
                    <td class="font-td">Registro</td>
                    <td class="font-td">Urgente</td>
                    <td class="font-td">Tipo</td>
                </tr>
            ';
                $amostra = 1;
                foreach($arrayAmostra as $a){
                    $tipo = null;
                    if($a['PO_TIPO'] == 'MP'){
                        $tipo = 'Matéria Prima';
                    } else if($a['PO_TIPO'] == 'PA'){
                        $tipo = 'Produto Acabado';
                    }else if($a['PO_TIPO'] == 'MICRO'){
                        $tipo = 'Micronutriente';
                    }
                    $html .= '<tr>
                        <td class="font-td"><p class="font-td">'.$amostra.'</p></td>
                        <td class="font-td"><p class="font-td">'.$a['PO_PRODUTO'].'</p></td>
                        <td class="font-td"><p class="font-td">'.$a['PO_LOTE'].'</p></td>
                        <td class="font-td"><p class="font-td">'.$a['PO_NOTA'].'</p></td>
                        <td class="font-td"><p class="font-td">'.$a['PO_DATA_FAB'].'</p></td>
                        <td class="font-td"><p class="font-td">'.$a['PO_DATA_VALIDADE'].'</p></td>
                        <td class="font-td"><p class="font-td">'.$a['PO_REG_MAPA'].'</p></td>
                        <td class="font-td"><p class="font-td">'.(($a['CA_URGENCIA'] == 1) ? '<b>sim</b>' : 'não').'</p></td>
                        <td class="font-td"><p class="font-td">'.$tipo.'</p></td>
                    </tr>';
                    $amostra++;
                }
                    
        $html .= '</tbody>
    </table>';
    $mpdf->CSSselectMedia = 'mpdf';
    $mpdf->WriteHTML($html);

    $file_name = 'ct-'.date('Y-m-d_His').'.pdf';
    // Output a PDF file directly to the browser

    $mpdf->Output($_SERVER['DOCUMENT_ROOT'].'/file/ct/'.$file_name,'F');

    ob_clean();  

    $return = $file_name;
    echo $return;
    
}

// EDITAR DADOS DA ANALISE
if ($_POST['action'] == 'editar-analise') {
    $return = null;
    try {
        $Conexao = ConexaoMYSQL::getConnection();
        
        $query2 = $Conexao->query("SELECT CA_ID, CA_STATUS 
        FROM QUALIDADE_CARTA 
        WHERE CA_ID = ".$_POST['idct']);
        $row = $query2->fetch();
        if(!empty($row)){
            if($row['CA_STATUS'] == 'B'){ // só pode alterar se não foi avaliado ainda. 
                foreach($_POST['op'] as $o){
                    $op = explode('|', $o);
                    
                    if($Conexao->query("UPDATE QUALIDADE_PRODUTO 
                        SET PO_OBS_LAB = '".$op[1]."', PO_OBS_LAUDO = '".$op[2]."', PO_ID_LAB = '".$_SESSION['USUEMPID']."' 
                        WHERE PO_ID = ".$op[0])){

                        if($Conexao->query("DELETE FROM QUALIDADE_ANALISE WHERE AE_PRODUTO_ID = '".$op[0]."' AND AE_ELEMENTO_ID IS NULL")){
                            foreach($_POST['elementos'] as $el){
                                $e = explode('|', $el);
                                if($e[0] == $op[0]){
                                    if($e[1] != ""){
                                        $Conexao->query("UPDATE QUALIDADE_ANALISE 
                                        SET AE_RESULTADO = '".$e[3]."', AE_METODO = '".$e[4]."' 
                                        WHERE AE_ID = ".$e[1]);
                                    }else{ 
                                        $Conexao->query("INSERT INTO QUALIDADE_ANALISE 
                                        (AE_PRODUTO_ID, AE_ELEMENTO, AE_RESULTADO, AE_METODO) 
                                        VALUES ('".$e[0]."', '".$e[2]."', '".$e[3]."', '".$e[4]."')");
                                        
                                    }
                                }
                            }
                        }
                        
                    }
                }
                if($Conexao->query("UPDATE QUALIDADE_CARTA 
                    SET CA_IDENTIFICACAO_LAB = '".$_POST['identlab']."', CA_OBS_LAB = '".$_POST['obslab']."', CA_STATUS = 'B' 
                    WHERE CA_ID = ".$_POST['idct'])){
                    $return = 1;
                    registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 13, 'Resultado de análise alterada ID: '.$_POST['idct']);
                }// status B =  analisado

                    
                $query_email = $Conexao->query("SELECT USU_EMAIL, USU_NOME 
                    FROM QUALIDADE_CARTA 
                    LEFT JOIN QUALIDADE_USUARIO_LOGIN ON CA_SOLICITANTE = USU_ID 
                    WHERE CA_ID = '" . $_POST['idct'] . "'");
                if ($row_email = $query_email->fetch()) {
                    $assunto = "Análise Alterada ".$_POST['idct'];
                    $mensagem = 'A análise foi realizada por  '.$_SESSION['USUNOME'];
                    $email = $row_email['USU_EMAIL'];
            
                    Envia_Email($assunto, $mensagem, $email, false);
                }
            }else{
                $return = 2;
            }
        }

       
    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    echo $return;

}

// BUSCAR PRODUTOS DESSA ANALISE
if ($_POST['action'] == 'busca_produtos') {
    $return = null;
    try {
        $Conexao = ConexaoMYSQL::getConnection();

        $html = '<table class="table table-bordered">
        </thead>
            <tr class="fw-bold fs-6 text-gray-800">
                <th>Tipo</th>    
                <th>Número</th>
                <th>Produto</th>
                <th>Descrição</th>
                <th>Lote</th>
                <th>Qtd</th>
                <th>Nota</th>
            <tr>
        </thead>
        <tbody>';

        $query_sts = $Conexao->query("SELECT PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_QTD, PO_DATA_FAB, PO_REG_MAPA, PO_DATA_VALIDADE, PO_TIPO, PO_NOTA, PO_OBS_LAB, 
            PO_OBS_QUALI, PO_ID_LAB, PO_ID_SOLICITANTE, PO_DATA_CRIADO, PO_OBS_LAUDO, PO_CLIENTE, PO_METAIS_PESADOS, PO_DATA, PO_APROVADO, PO_CONTRA , PO_TIPO
            FROM QUALIDADE_PRODUTO WHERE PO_ID_CARTA = '".$_POST['idprod']."'");
        while($row = $query_sts->fetch()){ 
            if($row['PO_TIPO'] == 'MP'){
                $tipo = '<span class="badge badge-light-danger">Matéria Prima</span>';
            }else if($row['PO_TIPO'] == 'PA'){
                $tipo = '<span class="badge badge-light-primary">Produto Acabado</span>';
            }else if($row['PO_TIPO'] == 'MICRO'){
                $tipo = '<span class="badge badge-light-success">Micronutriente</span>';
            }else {
                $tipo = '';
            }
            $html .= '<tr>
                <td>'.$tipo.'</td>
                <td>'.$row['PO_NUMERO'].'</td>
                <td>'.$row['PO_PRODUTO'].'</td>
                <td>'.$row['PO_PROD_DESC'].'</td>
                <td>'.$row['PO_LOTE'].'</td>
                <td>'.$row['PO_QTD'].'</td>
                <td>'.$row['PO_NOTA'].'</td>
            </tr>';
            
        }  
        $html .= '</tbody></table>';
        $return = json_encode($html);
    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    echo $return;

}

// CONFIGURAR LAUDO 
if ($_POST['action'] == 'config_laudo') {
    $return = 0;
    try {
        $Conexao = ConexaoMYSQL::getConnection();

        $newfile = null;
        $existe_arquivo = 0;
        if (isset($_FILES['file']['name'])) {
            $path = $_FILES['file']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $newfile = 'logo'.date('dmYHis').'.'.$ext;
            if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/file/config/' . $_FILES['file']['name'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/file/config/' . $newfile);
                $existe_arquivo = 1;
            }
        }
        $newfile2 = null;
        $existe_arquivo2 = 0;
        if (isset($_FILES['file2']['name'])) {
            $path = $_FILES['file2']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $newfile2 = 'signature'.date('dmYHis').'.'.$ext;
            if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/file/config/' . $_FILES['file2']['name'])) {
                move_uploaded_file($_FILES['file2']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/file/config/' . $newfile2);
                $existe_arquivo2 = 1;
            }
        }


        $query = $Conexao->query("SELECT LC_ID, LC_LOGO_ARQ, LC_LOGO_TEXTO, LC_TOPO, LC_BOTTOM, LC_ASSINATURA, LC_USU
        FROM LAUDO_CONFIGURACAO");
        $row = $query->fetch();
        if(!empty($row)) {
            if($existe_arquivo == 1){
                $arquivo = $newfile;
                unlink($_SERVER['DOCUMENT_ROOT'].'/file/config/'.$row['LC_LOGO_ARQ']);
            }else{
                $arquivo = $row['LC_LOGO_ARQ'];
            }

            if($existe_arquivo2 == 1){
                $arquivo2 = $newfile2;
                unlink($_SERVER['DOCUMENT_ROOT'].'/file/config/'.$row['LC_ASSINATURA']);
            }else{
                $arquivo2 = $row['LC_ASSINATURA'];
            }

            if($Conexao->query("UPDATE LAUDO_CONFIGURACAO SET LC_LOGO_ARQ = '".$arquivo."',  LC_LOGO_TEXTO =  '".str_replace("'", "''", $_POST['nome'])."', 
                LC_TOPO = '".str_replace("'", "''", $_POST['topo'])."', LC_BOTTOM =  '".str_replace("'", "''", $_POST['bottom'])."', LC_ASSINATURA = '".$arquivo2."', LC_USU = '".$_SESSION['USUID']."' 
                WHERE LC_ID = '".$row['LC_ID']."'")){
                $return = 1;
                registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 4, 'Configuração de laudo editado');
            }
        }else{
            if($Conexao->query("INSERT INTO LAUDO_CONFIGURACAO 
                (LC_LOGO_ARQ, LC_LOGO_TEXTO, LC_TOPO, LC_BOTTOM, LC_ASSINATURA, LC_USU) 
                VALUES ('".$newfile."', '".str_replace("'", "''", $_POST['nome'])."', '".str_replace("'", "''", $_POST['topo'])."', '".str_replace("'", "''", $_POST['bottom'])."', '".$newfile2."', '".$_SESSION['USUID']."')")){
                $return = 1;
                registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 14, 'Configuração de laudo criada');
            }
        }

       

    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    echo $return;

}

// configurar se vai subir arquivo ou gerar.
if ($_POST['action'] == 'configura_laudo'){

    $laudo = null;
    $arquivo = null;
    $arquivos_mais = null;
    $existe = 0;
    $Conexao = ConexaoMYSQL::getConnection();
    $query = $Conexao->query("SELECT CA_LAUDO
            FROM QUALIDADE_CARTA WHERE CA_ID = '".$_POST['idconfig']."'");
    $row = $query->fetch();
    if(!empty($row)){
        $laudo = $row['CA_LAUDO'];

        if($laudo == 'anexo'){
            $existe = 0;
            $arquivos_mais = '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Arquivo</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>';
            $query2 = $Conexao->query("SELECT AL_ID, AL_CARTA_ID, AL_ARQUIVO, DATE_FORMAT(AL_DATA,'%d/%m/%Y %H:%i') AL_DATA, AL_NOME
            FROM ANEXO_LAUDO WHERE AL_CARTA_ID = '".$_POST['idconfig']."'");
            while($row2 = $query2->fetch()){
                $existe++;
                $arquivos_mais .= '
                <tr><td>'.$row2['AL_NOME'].'</td>
                <td>'.$row2['AL_ARQUIVO'].'</td>
                <td>'.$row2['AL_DATA'].'</td>
                <td><button type="button" class="btn btn-sm btn-info btn-icon btn-download-laudo" style="height: calc(1.5em + 0.55rem + 1px); !important" id="'.$row2['AL_ID'].'" data-id="'.$row2['AL_ARQUIVO'].'"><i class="fas fa-download"></i></button>
                <button type="button" class="btn btn-sm btn-danger btn-icon btn-delete-laudo" style="height: calc(1.5em + 0.55rem + 1px); !important" id="'.$row2['AL_ID'].'" data-id="'.$row2['AL_CARTA_ID'].'"><i class="fas fa-trash-alt"></i></button></td></tr>';
            }
            if($existe > 0){
                $arquivos_mais .= '</tbody></table>';
            }else{
                $arquivos_mais .= '<tr><td colspan="4"></td>Nenhum Registro encontrado.</tr></tbody></table>';
            }
        
        }
    }
    if($existe == 0){
        $arquivos_mais = null;
    }
    $html = '<div class="rounded border p-10">
        <input type="hidden" value="'.$_POST['idconfig'].'" id="id_laudo_ct" />
        <div class="mb-10">
            <div class="form-check">
                <input class="form-check-input radio_laudo" type="radio" value="gera" id="check" name="radio2" '.(( $laudo == 'gera') ? 'checked' : '').'>
                <label class="form-check-label" for="check">
                    Gerar automaticamente pelo sistema
                </label>
            </div>
        </div>

        <div class="mb-10">
            <div class="form-check">
                <input class="form-check-input radio_laudo" type="radio" value="anexo" id="check1" name="radio2" '.(( $laudo == 'anexo') ? 'checked' : '').'>
                <label class="form-check-label" for="check1">
                    Anexar Laudo
                </label>
            </div>
        </div>';

       

        $html .= '
        <div id="div_anexolaudo">
        '.$arquivos_mais.'
        </div>
        
        <div id="div_laudo_anexo" style="display:none">
            <div class="row">
                <div class="col-md-4">
                    <label for="formFile" class="form-label">Identificação do Arquivo</label>
                    <input class="form-control" type="text" id="anexonome" name="anexonome" placeholder="Insira um nome">
                </div>
                <div class="col-md-6">
                    <label for="formFile" class="form-label">Anexo do Laudo</label>
                    <input class="form-control" type="file" id="anexo_arquivo_laudo" name="anexo_arquivo_laudo">
                </div>
                <div class="col-md-2">
                    <button style="margin-top: 26px;" type="button" class="btn btn-success" id="btn-anexo-laudo" name="btn-anexo-laudo">
                        <span class="indicator-label">
                            Adicionar
                        </span>
                        <span class="indicator-progress">
                            ... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

    </div>';

    echo json_encode($html);

}

// anexar arquivo
if ($_POST['action'] == 'anexar_laudo'){

    $laudo = null;
    $arquivo = null;
    $arquivos_mais = null;
    $return = 0;
    $newfile = null;


    $Conexao = ConexaoMYSQL::getConnection();

    $query = $Conexao->query("SELECT CA_LAUDO FROM QUALIDADE_CARTA  WHERE CA_ID = '".$_POST['id']."'");
    $row = $query->fetch();
    if(!empty($row)){
        if($row['CA_LAUDO'] == null or $row['CA_LAUDO'] == ""){
            if(isset($_POST['radio']) and $_POST['radio'] != ""){
                $Conexao->query("UPDATE QUALIDADE_CARTA SET CA_LAUDO = '".$_POST['radio']."'");
            }
        }
    }

    try{
        if (isset($_FILES['file']['name'])) {
            $path = $_FILES['file']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $newfile = date('dmYHis').'.'.$ext;
            if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/file/laudoAnexo/' . $_FILES['file']['name'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/file/laudoAnexo/' . $newfile);
                if($Conexao->query("INSERT INTO ANEXO_LAUDO (AL_CARTA_ID, AL_ARQUIVO, AL_NOME) VALUES ('".$_POST['id']."', '".$newfile."', '".$_POST['anexonome']."')")){
                    $return = 1;
                    registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 15, 'Laudo Anexado');
                }
            }
        }else{
            $return = 0;
        }
    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    
    echo $return;

}

// carrega os arquivos que possuem no laudo
if ($_POST['action'] == 'carrega_anexo_laudo'){

    $html = '<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">Nome</th>
            <th scope="col">Arquivo</th>
            <th scope="col">Data</th>
            <th scope="col">Ação</th>
        </tr>
    </thead>
    <tbody>';
    $laudo = null;
    $existe = 0;
    $Conexao = ConexaoMYSQL::getConnection();

        $query_0 = $Conexao->query("SELECT CA_LAUDO 
        FROM QUALIDADE_CARTA WHERE CA_ID = '".$_POST['id']."'");
        $row_0 = $query_0->fetch();
        if(!empty($row_0)){
            $laudo = $row_0['CA_LAUDO'];
        }

        $query = $Conexao->query("SELECT AL_ID, AL_CARTA_ID, AL_ARQUIVO, DATE_FORMAT(AL_DATA,'%d/%m/%Y %H:%i') AL_DATA, AL_NOME 
        FROM ANEXO_LAUDO WHERE AL_CARTA_ID = '".$_POST['id']."'");
        while($row = $query->fetch()){
            
            $existe++;
            $html .= '<tr>
            <td>'.$row['AL_NOME'].'</td>
            <td>'.$row['AL_ARQUIVO'].'</td>
            <td>'.$row['AL_DATA'].'</td>
            <td><button type="button" class="btn btn-sm btn-info btn-icon btn-download-laudo" style="height: calc(1.5em + 0.55rem + 1px); !important" id="'.$row['AL_ID'].'" data-id="'.$row['AL_ARQUIVO'].'"><i class="fas fa-download"></i></button>
            <button type="button" class="btn btn-sm btn-danger btn-icon btn-delete-laudo" style="height: calc(1.5em + 0.55rem + 1px); !important" id="'.$row['AL_ID'].'" data-id="'.$row['AL_CARTA_ID'].'"><i class="fas fa-trash-alt"></i></button></td>
            </tr>';
        }
    if($laudo == 'anexo'){ 
        if($existe > 0){
            $html .= '</tbody></table>';
        }else{
            $html .= '<tr><td colspan="4"></td>Nenhum Registro encontrado.</tr></tbody></table>';
        }
    }else{
        $html = null;
    }

    echo json_encode($html);

}

// salvar radio modal laudo config
if ($_POST['action'] == 'salvar_config_laudo'){

    $Conexao = ConexaoMYSQL::getConnection();
    $return = 0;
    
    if(isset($_POST['radio']) and $_POST['radio'] != ""){
        if($Conexao->query("UPDATE QUALIDADE_CARTA SET CA_LAUDO = '".$_POST['radio']."'")){
            $return = 1;
        }else{
            $return = 'Não foi possivel salvar registro.';
        }
    }else{
        $return = 'Selecione uma opção para salvar';
    }
    
    echo $return;
}

// APAGAR LAUDO
if ($_POST['action'] == 'apagar_arquivo_laudo'){

    $Conexao = ConexaoMYSQL::getConnection();
    $return = 0;

    $query = $Conexao->query("SELECT AL_ID, AL_CARTA_ID, AL_ARQUIVO, AL_NOME FROM ANEXO_LAUDO WHERE AL_ID = '".$_POST['id']."'");
    $row = $query->fetch();
    if(!empty($row)){
        if($Conexao->query("DELETE FROM ANEXO_LAUDO WHERE AL_ID = '".$_POST['id']."'")){
            unlink($_SERVER['DOCUMENT_ROOT'].'/file/laudoAnexo/'.$row['AL_ARQUIVO']);
            registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 16, 'Laudo Anexo apagado');
            $return = 1;
        }else{
            $return = 'Não foi possivel salvar registro.';
        }
    }
       
    echo $return;

}

// CARREGAR MODAL COM ARQUIVOS DE LAUDO ANEXADO OU BOTÃO PARA GERAR AUTOMATICAMENTE O LAUDO PELO MPDF
if ($_POST['action'] == 'carrega_baixar_laudo'){

    $Conexao = ConexaoMYSQL::getConnection();

    $query_0 = $Conexao->query("SELECT CA_LAUDO 
    FROM QUALIDADE_CARTA WHERE CA_ID = '".$_POST['id']."'");
    $row_0 = $query_0->fetch();
    if(!empty($row_0)){
        if($row_0['CA_LAUDO'] == 'anexo'){  /// criar a tabela com os arquivos anexados. 
            $html = '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Arquivo</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>';
            $existe = 0;
            $query = $Conexao->query("SELECT AL_ID, AL_CARTA_ID, AL_ARQUIVO, DATE_FORMAT(AL_DATA,'%d/%m/%Y %H:%i') AL_DATA, AL_NOME 
            FROM ANEXO_LAUDO WHERE AL_CARTA_ID = '".$_POST['id']."'");
            while($row = $query->fetch()){
                
                $existe++;
                $html .= '<tr>
                <td>'.$row['AL_NOME'].'</td>
                <td>'.$row['AL_ARQUIVO'].'</td>
                <td>'.$row['AL_DATA'].'</td>
                <td><button type="button" class="btn btn-sm btn-info btn-icon btn-download-laudo" style="height: calc(1.5em + 0.55rem + 1px); !important" id="'.$row['AL_ID'].'" data-id="'.$row['AL_ARQUIVO'].'">
                    <i class="fas fa-download"></i></button>
                </td>
                </tr>';
            }
            if($existe > 0){
                $html .= '</tbody></table>';
            }else{
                $html .= '<tr><td colspan="4" style="text-align:center">Nenhum Registro encontrado.</td></tr></tbody></table>';
            }
        }else if($row_0['CA_LAUDO'] == 'gera'){
            $html = '<div style="text-align:center">
            <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-elevate-up gerar_laudo_pdf" id="'.$_POST['id'].'" data-id="A"> 
                <i class="fa-solid fa-download fs-2x"></i>
                <span class="indicator-label">
                    Gerar Laudo
                </span>
                <span class="indicator-progress">
                    Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </a>
            </div>';
        }else{
            $html = '<div style="">
                <span style="font-weight:600; font-size:14px">Configure os dados de como será emitido o laudo. </span><br>
                <span style="font-weight:500">Configuração automatica:</span> <a href="laudo" style="font-weight:600">AQUI</a><br>
                <span style="font-weight:500">Configuração para anexo:</span> Clique no botão "configurar Laudo" e selecione "anexar Laudo".
            </div>';
        }
    }

    echo json_encode($html);

}

// GERAR PDF DO LAUDO ***********************************************************************************
if ($_POST['action'] == 'gerarlaudo') {
    
    $array_produto = array();
    $array_analise = array();
    $array_analiseVMA = array();
    
    $solicitante = null;
    $emailsol = null;
    
    $id =  $_POST['id'];
    $status =  $_POST['status'];
    
    $id_lab = null;
    $solicitante = null;
    $emailsol = null;
    $lab_ref = null;
    $envio = null;
    $retorno = null;

    $Conexao = ConexaoMYSQL::getConnection();
    
    if($status == 'A' or $status == '' or $status == null){
        $where = " and (PO_APROVADO is null or PO_APROVADO = 'A') ";
    }else{ 
        $where = " and PO_APROVADO = 'R' ";
    }

    $query = $Conexao->query("SELECT PO_ID, PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_QTD, DATE_FORMAT(PO_DATA_FAB,'%d/%m/%Y') PO_DATA_FAB, 
            PO_REG_MAPA,DATE_FORMAT(PO_DATA_VALIDADE,'%d/%m/%Y') PO_DATA_VALIDADE, PO_TIPO, PO_NOTA, 
            PO_OBS_LAB, PO_OBS_QUALI, PO_ID_LAB, PO_ID_SOLICITANTE, PO_DATA_CRIADO, PO_OBS_LAUDO, PO_CLIENTE, PO_METAIS_PESADOS, 
            DATE_FORMAT(PO_DATA_FAB,'%d/%m/%Y') PO_DATA_FAB, PO_APROVADO, PO_CONTRA, CA_LABORATORIO, 
            CA_SOLICITANTE, USU_NOME, USU_EMAIL, CA_IDENTIFICACAO_LAB, DATE_FORMAT(CA_DATA,'%d/%m/%Y') CA_DATA, DATE_FORMAT(CA_ANALISE,'%d/%m/%Y') CA_ANALISE 
            FROM QUALIDADE_PRODUTO INNER JOIN QUALIDADE_CARTA ON CA_ID = PO_ID_CARTA LEFT JOIN QUALIDADE_USUARIO_LOGIN ON USU_ID = CA_SOLICITANTE 
            WHERE PO_ID_CARTA = '".$id."'  ".$where." ");
    while ($row = $query->fetch()) {
        $array_produto[] = $row;
        $id_lab = $row['CA_LABORATORIO'];
        $solicitante = $row['USU_NOME'];
        $emailsol = $row['USU_EMAIL'];
        $lab_ref = $row['CA_IDENTIFICACAO_LAB'];
        $envio = $row['CA_DATA'];
        $retorno = $row['CA_ANALISE'];

        $query2 = $Conexao->query("SELECT AE_ELEMENTO, AE_PRODUTO_ID, AE_GARANTIA, AE_VMA, AE_RESULTADO, AE_METODO
        FROM QUALIDADE_ANALISE 
        WHERE AE_PRODUTO_ID = '". $row['PO_ID']."' ORDER BY AE_ELEMENTO");
        while ($row2 = $query2->fetch()) {
            if($row2['AE_VMA'] == "" and $row2['AE_VMA'] == null){
                $array_analise[$row2['AE_PRODUTO_ID']][] = $row2;
            }else{
                $array_analiseVMA[$row2['AE_PRODUTO_ID']][] = $row2;
            }
        }
    }
    
    $logo = null;
    $assinatura = null;
    $queryl = $Conexao->query("SELECT LC_ID, LC_LOGO_ARQ, LC_LOGO_TEXTO, LC_TOPO, LC_BOTTOM, LC_ASSINATURA, LC_USU
        FROM LAUDO_CONFIGURACAO");
    $rowl = $queryl->fetch();
    if(!empty($rowl)){
        $logoarq = $rowl['LC_LOGO_ARQ'];
        $logotexto = $rowl['LC_LOGO_TEXTO'];
        $topo = $rowl['LC_TOPO'];
        $bottom = $rowl['LC_BOTTOM'];
        $assitatura = $rowl['LC_ASSINATURA'];
    }
    
  
    // GERAR PDF ***************************************************
        
    require_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
    require $_SERVER['DOCUMENT_ROOT']."/vendor/mpdf/mpdf/src/Mpdf.php";

    // Create an instance of the class:
    $mpdf = new \Mpdf\Mpdf();

    $html = null;
    $i = 0;


    foreach($array_produto as $p){

        $i++;

        $html .= ' 
            <link href="'.$_SESSION['URL'].'/vendor/mpdf/mpdf/mpdf.css" type="text/css" rel="stylesheet" media="mpdf" />';

            if($logoarq != ""and $logoarq != null){
                $html .= '<div style="text-align: center;">
                    <img style="width: 170px;" src="'.$_SERVER['DOCUMENT_ROOT'].'/file/config/'.$logoarq.'" />
                </div>';
            }else{
                $html .= $logotexto;
            }

            $html .= '
            '.$topo.'
                <br />
                <table class="table table-bordered font-ti">
                    <tbody style="width: 100%">
                        <tr>
                            <td style="text-align:center"><p>solicitação / nº amostra </p></td>
                            <td style="text-align:center"><p>Entrada</p></td>
                            <td style="text-align:center"><p>Saida</p></td>
                        </tr>
                        <tr>
                            <td style="text-align:center">'. $lab_ref .' / '.$p['PO_NUMERO']. '</td>
                            <td style="text-align:center">'. $envio .'</td>
                            <td style="text-align:center">'. $retorno .'</td>
                        </tr>
                        <tr>
                            <td>Solicitado por</td>
                            <td colspan="2">Nome do Solicitante - teste@teste.com.br</td>
                        </tr>

                        <tr>
                            <td>Cliente</td>
                            <td colspan="2">'.$p['PO_CLIENTE'] .'</td>
                        </tr>';
                        
                        $html .= '<tr>
                            <td>Nota</td>
                            <td colspan="2">'.$p['PO_NOTA'].'</td>
                        </tr>';
                    $html .= '</tbody>
                </table>

                <table class="table table-bordered font-ti">
                    <tbody style="width: 100%">
                        <tr>
                            <td>Produto</td>
                            <td>Lote</td>
                            <td>FAB/COLETA</td>
                            <td>VALIDADE</td>
                            <td>REG. MAPA</td>
                        </tr>
                        <tr>
                            <td>'. $p['PO_PRODUTO'].', '. $p['PO_PROD_DESC'].'</td>
                            <td>'. $p['PO_LOTE'] .'</td>
                            <td>'. $p['PO_DATA_FAB'] .'</td>
                            <td>'. $p['PO_DATA_VALIDADE'] .'</td>
                            <td>'. $p['PO_REG_MAPA'] .'</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered font-ti">
                    <tbody style="width: 100%">
                        <tr>
                            <td style="width:25%">PARAMETRO ANALISADO</td>
                            <td style="width:15%;text-align:center">Garantias</td>
                            <td style="width:15%;text-align:center">Resultado</td>
                            <td style="width:35%;text-align:left">Método</td>
                        </tr>';

                        foreach($array_analise[$p['PO_ID']] as $el){

                            $html .= '<tr>
                                <td style="text-align:left">'. $el['AE_ELEMENTO'] .'</td>
                                <td style="text-align:center">'. $el['AE_GARANTIA'] .'</td>
                                <td style="text-align:center">'. $el['AE_RESULTADO'] .'</td>
                                <td>'. $el['AE_METODO'].'</td>
                            </tr>';
                        }
                        
                     
                 $html .= '</tbody>
                </table>';
               
                if(isset($array_analiseVMA[$p['PO_ID']])){
                $html .= '<table class="table table-bordered font-ti">
                    <tbody style="width: 100%">
                        <tr>
                            <td style="width:25%">PARAMETRO ANALISADO - MP</td>
                            <td style="width:15%;text-align:center">VMA</td>
                            <td style="width:15%;text-align:center">RESULTADOS</td>
                            <td style="width:35%;text-align:left">Métodos</td>
                        </tr>';
                        $metal = 0;
                        foreach($array_analiseVMA[$p['PO_ID']] as $elp){
                            $metal = 1;

                            $html .= '<tr>
                                <td style="text-align:center">'. $elp['AE_ELEMENTO'] .'</td>
                                <td style="text-align:center">'. $elp['AE_VMA'] .'</td>
                                <td style="text-align:center">'. $elp['AE_RESULTADO'] .'</td>
                                <td style="text-align:left">'. $elp['AE_METODO'].'</td>
                            </tr>';
                        }
                 $html .= '</tbody>
                </table>';
                    if($metal == 1){
                        $html .= '<p style="font-size:10px">*VMA = VALOR MAXIMO ADMITIDO EM mg POR kg DE FERTILIZANTE CONFORME INST NORMATIVA 27-2.006 alterada pela IN 07 2016.</p><hr>';
                    }
                }

           $html .= '<p class="font2">Observação: '.$p['PO_OBS_LAUDO'].'</p>  

           <p style="font-size:11px">RESULTADOS EXPRESSOS SOBRE AMOSTRA TAL COMO RECEBIDA. (p/p)<br>
           Estes resultados têm significação restrita e aplica-se a amostra recebida.<br>
           Não é permitida reprodução parcial deste relatório.</p>';
        
       
            $html .= '
            <img style="width:250px" src="'.$_SERVER['DOCUMENT_ROOT'].'/file/config/'.$assitatura.'" />'; 
    
           
        //  $html .= '<p style="font-size:10px">METODOLOGIA: MANUAL DE MÉTODOS ANALÝTICOS OFICIAIS PARA FERTILIZANTES E CORRETIVOS 2017- ISBN (IN 037 2017 DO MAPA).
        //      <br>Métodos reconhecidos pelo Ministério da Agricultura para análise de contaminantes. U.S. EPA 3050B; 7000 A; 7470 A; 7471 A; 7061 A; 7741A.
        //      </p>';

        $html .= trim($bottom);

        //$mpdf->SetHTMLFooter('');
        $mpdf->CSSselectMedia = 'mpdf';
        $mpdf->WriteHTML($html);
        
        $html = '';
       
        if($i != sizeof($array_produto)){
            $mpdf->AddPage(); 
        }
        
    }

    
    $file_name = 'laudo-'.date('Y-m-d_His').'.pdf';
    // Output a PDF file directly to the browser

    $mpdf->Output($_SERVER['DOCUMENT_ROOT'].'/file/laudoGerado/'.$file_name,'F');

    ob_clean();  

    $return = $file_name;
    echo $return;
}

// cadastro de historicos 
function registrar_historico($usuid, $usunome, $tipo, $descricao) {
    $Conexao = ConexaoMYSQL::getConnection();
    $Conexao->query("INSERT INTO HISTORICO (H_USUID, H_USUNOME, H_TIPO, H_DESCRICAO) VALUES ('".$usuid."', '".$usunome."', '".$tipo."', '".$descricao."')");
}