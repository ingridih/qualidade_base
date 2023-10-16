<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/menu/session.php';
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
                    registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 8, 'Novo Laboratório Cadastrado');
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
        
        $query2 = $Conexao->query("SELECT E_NOME, E_ID FROM QUALIDADE_LABORATORIO WHERE E_ATIVO = 'A'");
        while ($row = $query2->fetch()) {
            $resultado .= '<option value="'.$row['E_ID'].'">'.$row['E_NOME'].'</option>';
        }
        echo json_encode($resultado);
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

// buscar lab
if ($_POST['action'] == 'select-lab-analysis') {
    try {
        $resultado = '<option value="">Selecione um laboratório...</option>';
        $Conexao = ConexaoMYSQL::getConnection();
        
        $query2 = $Conexao->query("SELECT E_NOME, E_ID 
            FROM QUALIDADE_LABORATORIO WHERE E_ATIVO = 'A'");
        while ($row = $query2->fetch()) {
            $resultado .= '<option value="'.$row['E_ID'].'">'.$row['E_NOME'].'</option>';
        }
        echo json_encode($resultado);
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

// cadastrar elemento 
if ($_POST['action'] == 'cadastrar-elemento') {

    $Conexao = ConexaoMYSQL::getConnection();
    try {
        if($Conexao->query("INSERT INTO QUALIDADE_ELEMENTOS_QUIMICOS (EQ_NOME, EQ_SIGLA, EQ_USUARIO, EQ_TIPO, EQ_VMA, EQ_DATA) 
            VALUES ('".$_POST['elemento']."', '".$_POST['sigla']."', '".$_SESSION['USUID']."', '".$_POST['tipo']."', '".$_POST['vma']."', '".date("Y-m-d H:i:s")."')")){
            
            registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 10, 'Novo Elemento Cadastrado');
            $selectEl = '<option value="">Selecione ou crie o elemento</option>';
            $query2 = $Conexao->query("SELECT EQ_ID, EQ_NOME, EQ_SIGLA FROM QUALIDADE_ELEMENTOS_QUIMICOS ORDER BY EQ_ID DESC");
            while ($row2 = $query2->fetch()) {
                $selectEl .= '<option value="'.$row2['EQ_ID'].'">'.$row2['EQ_SIGLA'].' - '.$row2['EQ_NOME'].'</option>';
            }
            echo json_encode($selectEl);
        }else{
            echo json_encode(2);
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

// ENVIAR ANALISE
if ($_POST['action'] == 'enviar-analise') {
    $return = null;
    try {
        $Conexao = ConexaoMYSQL::getConnection();
        
            if($_POST['checkcontra'] == 1){
                $contra = 'S';
            }else{
                $contra = NULL;
            }

            if($Conexao->query("INSERT INTO QUALIDADE_CARTA (CA_STATUS, CA_SOLICITANTE, CA_IDENTIFICACAO_QUALI, CA_LABORATORIO, 
                CA_URGENCIA, CA_OBS_QUALI, CA_CONTRA) 
                VALUES 
                ('A', '".$_SESSION['USUID']."', '".$_POST['ident_quali']."', '".$_POST['selectlab']."', 
                '".$_POST['check']."', '".$_POST['obs_quali']."', '".$contra."')")){
                
                $idct = $Conexao->lastInsertId();
                    
                foreach($_POST['op'] as $op){
                    $o = explode('|', $op);
                    if($o[1] != ""){  
                        $datafab = convertDateFormat($o[8]);
                        $datavalidade = convertDateFormat($o[12]);
                        
                        if($Conexao->query("INSERT INTO QUALIDADE_PRODUTO (PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_CLIENTE, PO_LOTE, PO_NOTA, 
                            PO_QTD, PO_DATA_FAB, PO_REG_MAPA, PO_DATA_VALIDADE, PO_OBS_QUALI, PO_METAIS_PESADOS, PO_FORNECEDOR, PO_TIPO) 
                            VALUES ('".$idct."', '".$o[1]."', '".$o[2]."', '".$o[3]."', '".$o[4]."', '".$o[5]."', '".$o[6]."',
                            '".$o[7]."', '".$datafab."', '".$o[9]."', '".$datavalidade."', '".$o[10]."',  '".$o[11]."', '".$o[13]."', '".$o[14]."')")){
                            $id_prod = $Conexao->lastInsertId();
                            $return = 1;
                            // INSERT GARANTIA
                            foreach($_POST['garantia'] as $el){
                                $e = explode('|', $el);
                                
                                if($o[0] == $e[0]){
                                    
                                    if(!empty($e[3])){
                                        if($Conexao->query("INSERT INTO QUALIDADE_ANALISE 
                                        (AE_PRODUTO_ID, AE_ELEMENTO_ID, AE_ELEMENTO, AE_GARANTIA, AE_QUALI) 
                                        VALUES ('".$id_prod."', '".$e[1]."', '".$e[2]."', '".$e[3]."', '1')")){
                                            $return = 1;
                                        }
                                    }
                                }

                            }
                        }
                    }
                }

                $email = array();
                $existe = 0;
                $query_email = $Conexao->query("SELECT USU_EMAIL FROM QUALIDADE_USUARIO_LOGIN WHERE USU_EMPRESAID = '".$_POST['selectlab']."' AND USU_TIPO = 'LABORATORIO'");
                while($row_email = $query_email->fetch()){
                    $email[] = $row_email['USU_EMAIL'];
                    $existe = 1;
                }    
                $assunto = "Análise de Amostra- Diges";
                $mensagem = 'Nova Analise requisitada. ID '.$idct;

                if($existe == 1){ 
                    if(Envia_Email($assunto, $mensagem, $email, false) === false){
                        $return = 'Analise salva, mas não foi possivel enviar email.';
                    }
                }
                registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 1, 'Nova Análise Cadastrado ID: '.$idct);
           
            }else{
                 $return = 2;
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
    $tipo = null;
    $Conexao = ConexaoMYSQL::getConnection();
    $query = $Conexao->query("SELECT CA_ID, CA_STATUS, CA_SOLICITANTE, CA_TIPO, CA_IDENTIFICACAO_QUALI, CA_IDENTIFICACAO_LAB,
        CA_LABORATORIO, CA_URGENCIA, CA_OBS_QUALI, CA_OBS_LAB, CA_DATA, USU_NOME, USU_EMPRESA_NOME, USU_EMAIL, 
        PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_NOTA, DATE_FORMAT(PO_DATA_FAB,'%d/%m/%Y') PO_DATA_FAB, CA_TIPO, PO_TIPO, 
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
        if($row['CA_TIPO'] == 'MP'){
            $tipo = 'Matéria Prima';    
        }else if($row['CA_TIPO'] == 'MICRO'){
            $tipo = 'Micronutrientes';    
        }else if($row['CA_TIPO'] == 'PA'){
            $tipo = 'Produto Acabado';
        }

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

// cancelar ct
if ($_POST['action'] == 'cancelarct') {

    $Conexao = ConexaoMYSQL::getConnection();
    try {
        if($Conexao->query("UPDATE QUALIDADE_CARTA SET CA_STATUS = 'C' WHERE CA_ID = ".$_POST['id'])){
            registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 11, 'Análise Cancelada ID: '.$_POST['id']);
            echo 1;
        }
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

// Busca dados para analise. 
if ($_POST['action'] == 'busca-resultado') {
    $html = "";
    $Conexao = ConexaoMYSQL::getConnection();
    try {
        $query2 = $Conexao->query("SELECT CA_ID, CA_TIPO, CA_IDENTIFICACAO_QUALI, CA_IDENTIFICACAO_LAB, CA_OBS_QUALI, CA_OBS_LAB 
        FROM QUALIDADE_CARTA WHERE CA_ID = ".$_POST['id']);
        $row2 = $query2->fetch();
        if(!empty($row2)){
            $tipo = (($row2['CA_TIPO'] == 'PA') ? 'Produto Acabado' : 'Matéria Prima');
            $html .= '<h3>'.$row2['CA_ID'].' - '.$tipo.'</h3>
            <input type="hidden" id="cartaid" value="'.$row2['CA_ID'].'" />
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Identificação Qualidade</th>
                            <td>'.$row2['CA_IDENTIFICACAO_QUALI'].'</td>
                            <th>Identificação Laboratório</th>
                            <td>'.$row2['CA_IDENTIFICACAO_LAB'].'</td>
                        </tr>
                        <tr>
                            <th>Observação Qualidade</th>
                            <td>'.$row2['CA_OBS_QUALI'].'</td>
                            <th>Observação Laboratório</th>
                            <td>'.$row2['CA_OBS_LAB'].'</td>
                        </tr>
                    </tbody>
                </table>
            </div>';

            // ESTUDO DE CAUSA - estudo 
            $estudo = "";
            $querye = $Conexao->query("SELECT EE_ID, EE_ESTUDO
            FROM ESTUDO_CAUSA_ESTUDO");
            while($rowe = $querye->fetch()){ 
                $estudo .= '<option value="'.$rowe['EE_ESTUDO'].'">'.$rowe['EE_ESTUDO'].'</option>';
            }
            // ESTUDO DE CAUSA - causa  
            $causa = "";
            $queryc = $Conexao->query("SELECT EC_ID, EC_CAUSA
            FROM ESTUDO_CAUSA_CAUSA");
            while($rowc = $queryc->fetch()){ 
                $causa .= '<option value="'.$rowc['EC_CAUSA'].'">'.$rowc['EC_CAUSA'].'</option>';
            }
            
        
            $html .= '<table class="table" style="margin: 0px">
                <tbody><tr>
                <td style="width: 50%;"><select type="text" class="form-control mb-2 mb-md-0 select_estudo" data-kt-repeater="select2" data-control="select2" name="" id="estudo" data-dropdown-parent="#kt_avaliar">
                    <option value="">Selecione o Estudo</option>
                    '.$estudo.'
                </select></td>
                <td style="width: 50%;"><select type="text" class="form-control mb-2 mb-md-0 select_causa" data-kt-repeater="select2" data-control="select2" name="" id="causa" data-dropdown-parent="#kt_avaliar">
                    <option value="">Selecione a Causa</option>
                    '.$causa.'
                </select></td>
                </tr></tbody></table>
            ';

            $query3 = $Conexao->query("SELECT PO_ID, PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_NOTA 
            FROM QUALIDADE_PRODUTO WHERE PO_ID_CARTA = ".$_POST['id']);
            while($row3 = $query3->fetch()){
                $html .= '
                <div class="table-responsive">
                    <table class="table table-bordered" >
                        <tbody>
                            <tr style="background-color:#009ef738">
                                <th style="width: 10%;">Amostra</th>
                                <td style="width: 13%;">'.$row3['PO_NUMERO'].'</td>
                                <th style="width: 10%;">Produto</th>
                                <td>'.$row3['PO_PRODUTO'].' - '.$row3['PO_PROD_DESC'].'</td>
                                <th style="width: 5%;">Lote</th>
                                <td style="width: 10%;">'.$row3['PO_LOTE'].'</td>
                                <th style="width: 5%;">Nota</th>
                                <td style="width: 12%;">'.$row3['PO_NOTA'].'</td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <div class="d-flex" style="float: right;">
                                        <div class="form-check form-check-custom form-check-solid" style="margin-right: 10px;">
                                            <input class="form-check-input checkaprov" type="radio" value="A" id="checkaprovA'.$row3['PO_ID'].'" name="checkaprov'.$row3['PO_ID'].'" data-id="'.$row3['PO_ID'].'"/>
                                            <label class="form-check-label" for="checkaprovA'.$row3['PO_ID'].'">
                                                Aprovar
                                            </label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-danger form-check-solid">
                                            <input class="form-check-input checkaprov" type="radio" value="R" id="checkaprovR'.$row3['PO_ID'].'" name="checkaprov'.$row3['PO_ID'].'" data-id="'.$row3['PO_ID'].'"/>
                                            <label class="form-check-label" for="checkaprovR'.$row3['PO_ID'].'">
                                                Reprovar
                                            </label>
                                        </div>
                                        <div class="form-check" style="margin-left: 20px;">
                                            <input class="form-check-input checkct" type="checkbox" value="'.$row3['PO_ID'].'" id="checkct'.$row3['PO_ID'].'"/>
                                            <label class="form-check-label" for="checkct'.$row3['PO_ID'].'">
                                                Gerar Contra Prova
                                            </label>
                                        </div>
                                        
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="padding:0px">
                                
                                    <table class="table  table-bordered" style="font-size:11px">
                                        <tr class="fw-bold fs-8 text-gray-800">
                                            <th>Elemento</th>
                                            <th>Garantia</th>
                                            <th>Resultado</th>
                                        </tr>';
                                    $query4 = $Conexao->query("SELECT AE_ID, AE_ELEMENTO, AE_GARANTIA, AE_VMA, AE_RESULTADO, AE_METODO
                                    FROM QUALIDADE_ANALISE WHERE AE_PRODUTO_ID = ".$row3['PO_ID']);
                                    while($row4 = $query4->fetch()){

                                    // verificar ainda
                                    $garantia = is_numeric($row4['AE_GARANTIA']) ? $row4['AE_GARANTIA'] : 0;
                                    $resultado = is_numeric($row4['AE_RESULTADO']) ? $row4['AE_RESULTADO'] : 0;
                                    $str = 0;
                                    if($resultado < $garantia){
                                        switch (true) {
                                            case $garantia < 0.1:
                                                $v_tolerado = $garantia - ($garantia * 0.30);
                                                break;
                                            case $garantia >= 0.1 && $garantia < 1.0:
                                                $v_tolerado = $garantia - ($garantia * 0.25);
                                                break;
                                            case $garantia >= 1.0 && $garantia < 5.0:
                                                $v_tolerado = $garantia - ((0.1875 * $garantia) + 0.0625);
                                                break;
                                            case $garantia >= 5.0 && $garantia < 10.0:
                                                $v_tolerado = $garantia - ((0.05 * $garantia) + 0.75);
                                                break;
                                            case $garantia >= 10.0 && $garantia < 40.0:
                                                $v_tolerado = $garantia - ((0.0417 * $garantia) + 0.8333);
                                                break;
                                            default:
                                                $v_tolerado = $garantia - 2.5;
                                                break;
                                        }
                                        $diferenca = $garantia - $v_tolerado;
                                        $apurado = ($diferenca != 0) ? ($garantia - $resultado) / $diferenca : 0;
                                        $str = ($resultado < $v_tolerado && $apurado <= 3) ? 1 : 0;
                                        $string = ($str == 1) ? 'style="font-weight: 500;color:#f1416c"' : '';
                                    }
                                    $html .= '                         
                                        <tr>
                                            <td>'.$row4['AE_ELEMENTO'].'</td>
                                            <td>'.(is_numeric($row4['AE_GARANTIA']) ? number_format($row4['AE_GARANTIA'], 2, ',', '.') : $row4['AE_GARANTIA']).'</td>
                                            <td><span '.$string.'>'.(is_numeric($row4['AE_RESULTADO']) ? number_format($row4['AE_RESULTADO'], 2, ',', '.') : $row4['AE_RESULTADO']).'</span></td>
                                        </tr>                                
                                    ';
                                }
                        $html .= '</table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>';
            }

        }
        echo json_encode($html);
       
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

// avaliar os resultados ds CT
if ($_POST['action'] == 'avaliar-ct') {

    $Conexao = ConexaoMYSQL::getConnection();
    try {
        $return = 0;
        $existecp = 0;

        $causa = $_POST['causa'];
        $estudo = $_POST['estudo'];

        $i = 0;
        foreach($_POST['radio'] as $radio){
            $contra = 0;

            if(isset($_POST['check'])){
                if(in_array($radio['dataid'], $_POST['check'])){
                    $contra = 1;
                    $existecp = 1;
                }
            }   

            $sql_update = "UPDATE QUALIDADE_PRODUTO SET PO_APROVADO = '".$radio['valor']."', PO_CONTRA = '".$contra."' WHERE PO_ID = '".$radio['dataid']."'";
            $stmt_update = $Conexao->prepare($sql_update);

            // apaga se já existir (não é pra existir)
            $sql = "DELETE FROM ESTUDO_CAUSA_PRODUTO WHERE EP_PROD_ID = '".$radio['dataid']."'";
            $stmt = $Conexao->prepare($sql);
        
            // insere estudo e causa
            $sql_inserecausa = "INSERT IGNORE INTO ESTUDO_CAUSA_CAUSA (EC_CAUSA, EC_USUID) VALUES ('".str_replace("'", "''", $causa[$i])."', '".$_SESSION['USUID']."')";
            $sql_insereestudo = "INSERT IGNORE INTO ESTUDO_CAUSA_ESTUDO (EE_ESTUDO, EE_USUID) VALUES ('".str_replace("'", "''", $estudo[$i])."', '".$_SESSION['USUID']."')";

            // relaciona com o produto.
            $sql_produto_estudo_causa = "INSERT INTO ESTUDO_CAUSA_PRODUTO (EP_PROD_ID, EP_ESTUDO, EP_CAUSA, EP_USUID) 
                VALUES ('".$radio['dataid']."', '".str_replace("'", "''", $causa[$i])."', '".str_replace("'", "''", $estudo[$i])."', '".$_SESSION['USUID']."')";

            $stmt_inserecausa = $Conexao->prepare($sql_inserecausa);
            $stmt_insereestudo = $Conexao->prepare($sql_insereestudo);
            $stmt_produto_estudo_causa = $Conexao->prepare($sql_produto_estudo_causa);

            if($stmt_update->execute()){
                $return = 1;
                if($stmt->execute()){   

                    $stmt_inserecausa->execute();
                    $stmt_insereestudo->execute();
                    $stmt_produto_estudo_causa->execute();

                    $return = 1;
                }
            }
            $i++;
        }// Atualiar o status para finalizado.
        if($return == 1){
            if($Conexao->query("UPDATE QUALIDADE_CARTA SET CA_STATUS = 'F' WHERE CA_ID = '".$_POST['id']."'")){
                registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 3, 'Análise Finalizada ID: '.$_POST['id']);
                $return = 1;
            }
        }

        // -------------------------- GERAR CONTRAPROVA -----------------------------
        if($existecp == 1){

            $query = $Conexao->query("SELECT * 
            FROM QUALIDADE_CARTA WHERE CA_ID = ".$_POST['id']);
            $row = $query->fetch();
            if(!empty($row)){
                
                $Conexao->query("INSERT INTO QUALIDADE_CARTA 
                (CA_STATUS, CA_SOLICITANTE, CA_TIPO, CA_IDENTIFICACAO_QUALI, CA_URGENCIA, CA_OBS_QUALI, CA_CONTRA) 
                VALUES 
                ('E', '".$_SESSION['USUID']."', '".$row['CA_TIPO']."', '".$row['CA_IDENTIFICACAO_QUALI']."', '".$row['CA_URGENCIA']."', '".$row['CA_OBS_QUALI']."', '".$_POST['id']."')");
                
                $idct = $Conexao->lastInsertId();    // ---------- ID DA NOVA CARTA ----------

                $query2 = $Conexao->query("SELECT * FROM QUALIDADE_PRODUTO WHERE PO_ID_CARTA = ".$_POST['id']." AND PO_CONTRA = '1'");
                while($row2 = $query2->fetch()){
                    // ----------- inserir produtos com a flag de contra prova --------------
                    $Conexao->query("INSERT INTO QUALIDADE_PRODUTO (PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_QTD, PO_DATA_FAB, PO_REG_MAPA, PO_DATA_VALIDADE, PO_TIPO, PO_NOTA,
                        PO_OBS_QUALI, PO_ID_SOLICITANTE, PO_DATA_CRIADO, PO_CLIENTE, PO_METAIS_PESADOS) 
                    VALUES 
                    ('".$idct."', '".$row2['PO_NUMERO']."', '".$row2['PO_PRODUTO']."', '".$row2['PO_PROD_DESC']."', '".$row2['PO_LOTE']."', '".$row2['PO_QTD']."', '".$row2['PO_DATA_FAB']."', '".$row2['PO_REG_MAPA']."', 
                    '".$row2['PO_DATA_VALIDADE']."', '".$row2['PO_TIPO']."', '".$row2['PO_NOTA']."', '".$row2['PO_OBS_QUALI']."', '".$_SESSION['USUID']."', '".$row2['PO_DATA_CRIADO']."', '".$row2['PO_CLIENTE']."', '".$row2['PO_METAIS_PESADOS']."')");
                    $id_prod = $Conexao->lastInsertId(); 
                    
                    $query3 = $Conexao->query("SELECT * FROM QUALIDADE_ANALISE WHERE AE_PRODUTO_ID = '".$row2['PO_ID']."'");
                    while($row3 = $query3->fetch()){
                        $Conexao->query("INSERT INTO QUALIDADE_ANALISE (AE_PRODUTO_ID, AE_ELEMENTO_ID, AE_ELEMENTO, AE_GARANTIA, AE_VMA)
                        VALUES ('".$id_prod."', '".$row3['AE_ELEMENTO_ID']."', '".$row3['AE_ELEMENTO']."', '".$row3['AE_GARANTIA']."', '".$row3['AE_VMA']."')");
                    }

                }
            }
        }
        echo $return;

    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

}

// ENVIAR ANALISE - APÓS EDITADO E REVISADO
if ($_POST['action'] == 'editar-analise') {
    $return = 'null';
    try {
        $Conexao = ConexaoMYSQL::getConnection();

        $query_sts = $Conexao->query("SELECT CA_STATUS FROM QUALIDADE_CARTA WHERE CA_ID = '".$_POST['idcarta']."'");
        $row_sts = $query_sts->fetch();
        if(!empty($row_sts)){
       
            if($_POST['checkcontra'] == 1){
                $contra = 'S';
            }else{
                $contra = NULL;
            }
            if($row_sts['CA_STATUS'] == 'A' || $row_sts['CA_STATUS'] == 'E'){
                // verifica se esta aguardando ou em revisão para poder alterar, caso contrário ja foi analisado. 
                if($Conexao->query("UPDATE QUALIDADE_CARTA SET CA_STATUS = 'A', CA_SOLICITANTE = '".$_SESSION['USUID']."', CA_IDENTIFICACAO_QUALI = '".$_POST['ident_quali']."', 
                    CA_LABORATORIO = '".$_POST['selectlab']."', CA_URGENCIA = '".$_POST['check']."', CA_OBS_QUALI = '".$_POST['obs_quali']."', CA_CONTRA = '".$contra."' 
                    WHERE CA_ID = '".$_POST['idcarta']."'")){

                

                    if($Conexao->query("DELETE FROM QUALIDADE_PRODUTO WHERE PO_ID_CARTA = '".$_POST['idcarta']."'")){ 

                        foreach($_POST['op'] as $op){
                            $o = explode('|', $op);

                            $Conexao->query("DELETE FROM QUALIDADE_ANALISE WHERE AE_PRODUTO_ID = '".$o[13]."'");

                            $datafab = convertDateFormat($o[8]);
                            $datavalidade = convertDateFormat($o[12]);
                            
                            if($Conexao->query("INSERT INTO QUALIDADE_PRODUTO (PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_CLIENTE, PO_LOTE, PO_NOTA, 
                                PO_QTD, PO_DATA_FAB, PO_REG_MAPA, PO_DATA_VALIDADE, PO_OBS_QUALI, PO_METAIS_PESADOS, PO_FORNECEDOR, PO_TIPO) 
                                VALUES ('".$_POST['idcarta']."', '".$o[1]."', '".$o[2]."', '".$o[3]."', '".$o[4]."', '".$o[5]."', '".$o[6]."',
                                '".$o[7]."', '".$datafab."', '".$o[9]."', '".$datavalidade."', '".$o[10]."',  '".$o[11]."', '".$o[14]."', '".$o[15]."')")){
                                $id_prod = $Conexao->lastInsertId();
                                $return = 1;
                                // INSERT GARANTIA
                                foreach($_POST['garantia'] as $el){
                                    $e = explode('|', $el);
                                    
                                    if($o[0] == $e[0]){
                                        
                                        if($Conexao->query("INSERT INTO QUALIDADE_ANALISE 
                                        (AE_PRODUTO_ID, AE_ELEMENTO_ID, AE_ELEMENTO, AE_GARANTIA, AE_QUALI) 
                                        VALUES ('".$id_prod."', '".$e[1]."', '".$e[2]."', '".$e[3]."', '1')")){
                                            $return = 1;
                                        }
                                    }
                                }
                            
                            }
                        }
                    }
       
                    registrar_historico($_SESSION['USUID'], $_SESSION['USUNOME'], 12, 'Análise Editada ID: '.$_POST['idcarta']);
                    // enviar email avisando da solicitação
                    $email = array();
                    $query_email = $Conexao->query("SELECT USU_EMAIL FROM QUALIDADE_USUARIO_LOGIN WHERE USU_EMPRESAID = '".$_POST['selectlab']."' AND USU_TIPO = 'LABORATORIO'");
                    while($row_email = $query_email->fetch()){
                        $email[] = $row_email['USU_EMAIL'];
                    }    
                    $assunto = "Análise de Amostra - Diges";
                    $mensagem = 'Analise Editada requisitada. ID '.$_POST['idcarta'];

                    
                    if(Envia_Email($assunto, $mensagem, $email, false) === false){
                        $return = 'Analise salva, mas não foi possivel enviar email.';
                    }
                }else{
                    $return = 2;
                }
            }else{
                $return = 3;
            }
            echo $return;
        }  
    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    

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
            PO_OBS_QUALI, PO_ID_LAB, PO_ID_SOLICITANTE, PO_DATA_CRIADO, PO_OBS_LAUDO, PO_CLIENTE, PO_METAIS_PESADOS, PO_DATA, PO_APROVADO, PO_CONTRA
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

// mudar o formato da data
function convertDateFormat($inputDate) {
    $date = str_replace('/', '-', $inputDate);
    return date('Y-m-d', strtotime($date));
}

// cadastro de historicos 
function registrar_historico($usuid, $usunome, $tipo, $descricao) {
    $Conexao = ConexaoMYSQL::getConnection();
    $Conexao->query("INSERT INTO HISTORICO (H_USUID, H_USUNOME, H_TIPO, H_DESCRICAO) VALUES ('".$usuid."', '".$usunome."', '".$tipo."', '".$descricao."')");
    
}

