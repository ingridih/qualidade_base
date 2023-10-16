<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/menu/session.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/gerador_senha.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/script/mail.php';
include($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;


// buscar config granu
if ($_POST['action'] == 'config_gran') {

    $html = null;
    try {
        $Conexao = ConexaoMYSQL::getConnection();
        
        $html .= '<div id="kt_docs_repeater_basic">
                    <div class="form-group">
                        <div data-repeater-list="kt_docs_repeater_basic">';

        $query2 = $Conexao->query("SELECT CP_ESPECIFICACAO, CP_ABERTURA, CP_PESO, CP_VALID, CP_VALOR FROM CONFIG_PENEIRA");
        while ($row = $query2->fetch()) {
            $html .= 
            '<div data-repeater-item>
                <div class="form-group row mb-5 mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Especificação das Partículas Passantes</label>
                        <input type="text" class="form-control mb-2 mb-md-0 espec" placeholder="Entre com a descrição" value="'.$row['CP_ESPECIFICACAO'].'">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Abertura de Peneira (mm)</label>
                        <input type="text" class="form-control mb-2 mb-md-0 abertura" placeholder="Abertura de Peneira" value="'.$row['CP_ABERTURA'].'">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Peso peneira (g)</label>
                        <input type="number" class="form-control mb-2 mb-md-0 peso" placeholder="Peso da peneira" value="'.$row['CP_PESO'].'">
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:;" data-repeater-delete="" class="btn btn-sm btn-flex flex-center btn-light-danger mt-3 mt-md-9">
                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Apagar                                        
                        </a>
                    </div>
                </div>
                <div class="form-group row mb-5 mb-3">
                    <div class="col-md-2">
                        <label class="form-label">Regra de validação</label>
                        <select class="form-control mb-2 mb-md-0 regra" placeholder="Selecione uma regra...">
                            <option value="maior" '.(($row['CP_VALID'] == 'maior') ? 'selected' : '').'>Maior que</option>
                            <option value="menor" '.(($row['CP_VALID'] == 'menor') ? 'selected' : '').'>Menor que</option>
                            <option value="entre" '.(($row['CP_VALID'] == 'entre') ? 'selected' : '').'>Entre valores</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Valor(es)</label>
                        <input type="text" class="form-control mb-2 mb-md-0 valor_regra" placeholder="valor da regra ex: 95" value="'.$row['CP_VALOR'].'">
                    </div>
                    
                    <label for="floatingInput">Se escolher "Entre valores" coloque os valores separados por virgula. (não coloque o simbolo de %)</label>
                </div>
                <hr>
            </div>';
        }
        $html .= '</div>
            </div>  
            <div class="form-group mt-5">
                <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                    <i class="ki-duotone ki-plus fs-3"></i>
                    Adicionar
                </a>
            </div>
        </div>';
        

        echo json_encode($html);
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}



// salvar config granu
if ($_POST['action'] == 'salvar-config') {

    $return = 0;
    try {
        $Conexao = ConexaoMYSQL::getConnection();
        $sql = "DELETE FROM CONFIG_PENEIRA";
        $stmt = $Conexao->prepare($sql);
        if($stmt->execute()){
            
            foreach($_POST['dados'] as $dados){

                $sql2 = "INSERT INTO CONFIG_PENEIRA (CP_ESPECIFICACAO, CP_ABERTURA, CP_PESO, CP_VALID, CP_VALOR) VALUES (:espec, :abertura, :peso, :regra, :valor)";
                // Prepara a consulta
                $stmt2 = $Conexao->prepare($sql2);
                // Binda os parâmetros
                $stmt2->bindParam(':espec', $dados['espec']);
                $stmt2->bindParam(':abertura', str_replace(',','.',$dados['abertura']));
                $stmt2->bindParam(':peso', str_replace(',','.',$dados['peso']));
                $stmt2->bindParam(':regra', $dados['regra']);
                $stmt2->bindParam(':valor', $dados['valor_regra']);
                // Executa a consulta
                if ($stmt2->execute()) {
                    $return = 1;
                } else {
                    $return = "Erro ao inserir o registro: " . $stmt2->errorInfo()[2];
                }
            }
        }
    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    echo $return;

}


// criar dadoa granulometria
if ($_POST['action'] == 'granulometria-criar') {
    $array_gran = array();
    $html = null;
    try {
        $Conexao = ConexaoMYSQL::getConnection();
        $query2 = $Conexao->query("SELECT CP_ESPECIFICACAO, CP_ABERTURA, CP_PESO, CP_VALID, CP_VALOR FROM CONFIG_PENEIRA");
        while ($row = $query2->fetch()) {
            $array_gran[] = $row;
        }

        $select = '';
        $query1 = $Conexao->query("SELECT PO_ID_CARTA, PO_ID, PO_NUMERO, PO_PRODUTO, PO_NOTA, PO_PROD_DESC  
            FROM QUALIDADE_PRODUTO LEFT JOIN GRANULOMETRIA ON G_PROD_ID = PO_ID");
        while ($row1 = $query1->fetch()) {
            $select .= '<option value="'.$row1['PO_ID'].'">'.'Carta: '.$row1['PO_ID_CARTA'].' Produto: '.$row1['PO_PRODUTO'].' Descrição: '.$row1['PO_PROD_DESC'].'</option>';
        }

        
        $html .= '
            <table class="table table-bordered table-hover" style="font-size:12px">
                <thead>
                    <tr>
                        <th colspan="3">RELACIONAMENTO COM A ORDEM DE PRODUÇÃO <span style="color:red">*</span>
                            <select class="form-control gran_op" data-control="select2" data-placeholder="Selecione..." data-dropdown-parent="#kt_modal_granu" data-allow-clear="true" id="prod_id">
                                <option></option>
                                '. $select.'
                            </selecet>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">IDENTIFICAÇÃO <span style="color:red">*</span> <input type="text" class="form-control gran_desc" placeholder="Entre com a Descrição" id="gran_desc" value="" autocomplete="off"></th>
                    </tr>
                    <tr>
                        <th>ESPECIFICAÇÃO DAS PARTÍCULAS PASSANTES</th>
                        <th>PASSANTE (%)</th>
                        <th>RETIDO (%)</th>
                    </tr>
                </thead>
                <tbody>';
                    $i = 0;
                    foreach($array_gran as $for){
                        if($for['CP_ESPECIFICACAO'] != ""){
                            $html .= '<tr>
                                <td><p id="te'.$i.'" class="cole_topo">'.$for['CP_ESPECIFICACAO'].'</p></td>
                                <td><p id="tk'.$i.'" class="colk_topo"></p></td>
                                <td><p id="tl'.$i.'" class="coll_topo"></p></td>
                            </tr>
                            <input type="hidden" id="regra_peneira'.$i.'" class="regra_peneira" value="'.$for['CP_VALID'].'" />
                            <input type="hidden" id="regra_valor'.$i.'" class="regra_valor" value="'.$for['CP_VALOR'].'" />
                            ';
                            $i++;
                        }
                    }
                    $html .=' </tbody>
                </table>
                <table class="table table-bordered table-hover" style="font-size:12px">
                    <thead>
                        <tr>
                            <th>Abertura Peneira (mm)</th>
                            <th>Peso da Peneira (g)</th>
                            <th>Peso da Peneira + Produto (g)</th>
                            <th>Produto Retido (g)</th>
                            <th>%</th>
                            <th>Cumulativo Retido</th>
                            <th>Peso Passante</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">';
                    $j = 0;
                    foreach($array_gran as $for){
                        ($for['CP_ESPECIFICACAO'] == "") ? $class = 'fundo' : $class = '';
                        $html .= '
                        <tr>
                            <td><p class="col_abertura">'.$for['CP_ABERTURA'].'</p></td>
                            <td><input type="hidden" class="peso" value="'.number_format($for['CP_PESO'],0).'" /> '.number_format($for['CP_PESO'],0).'</td>
                            <td><input type="number" class="input_valor" placeholder="" id="valor_'.$j.'"></td>
                            <td><p id="i'.$j.'" class="piv"></p></td>
                            <td><p id="k'.$j.'" class="pkv"></p></td>
                            <td><p id="l'.$j.'" class="plv '.$class.'"></p></td>
                            <td><p id="n'.$j.'" class="pnv '.$class.'"></p></td>
                        </tr>';
                        $j++;
                    }
                $html .= '<tr>
                    <td><p class="col_abertura">Total (peso)</p></td>
                    <td>*</td>
                    <td>*</td>
                    <td><p id="v6"></p></td>
                    <td><p id="v7"></p></td>
                    <td>*</td>
                    <td>*</td>
                </tr>
            </td>
        </tr>
    </tbody>
 </table>';

        echo json_encode($html);
    }catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

// salvar dados da granu
if ($_POST['action'] == 'salvar_granu') {

    $return = 0;
    try {
        $Conexao = ConexaoMYSQL::getConnection();

        $sql2 = "INSERT INTO GRANULOMETRIA (G_PROD_ID, G_IDENTIFICA, G_TOPO, G_PASSANTE, G_RETIDO, G_ABERTURA, G_PESO, G_PESO_PENEIRA_PROD, G_PROD_RETIDO, G_PORCENT, 
        G_PESO_RETIDO, G_PESO_PASSANTE, G_DATA) 
        VALUES (:prod_id, :identifica, :topo, :passante, :retido, :abertura, :peso, :peso_peneira_prod, :prod_retido, :percentual, :peso_retido, :peso_passante, :data)";
        // Prepara a consulta
        $stmt2 = $Conexao->prepare($sql2);
        // Binda os parâmetros
        $stmt2->bindParam(':prod_id', $_POST['prod_id']);
        $stmt2->bindParam(':identifica', $_POST['gran_desc']);
        $stmt2->bindParam(':topo', implode('|', $_POST['topo']));
        $stmt2->bindParam(':passante', implode('|', $_POST['passante_topo']));
        $stmt2->bindParam(':retido', implode('|', $_POST['retido_topo']));
        $stmt2->bindParam(':abertura', implode('|', $_POST['abertura']));
        $stmt2->bindParam(':peso', implode('|', $_POST['peso']));
        $stmt2->bindParam(':peso_peneira_prod', implode('|', $_POST['input']));
        $stmt2->bindParam(':prod_retido', implode('|', $_POST['prod_retido']));
        $stmt2->bindParam(':percentual', implode('|', $_POST['percentual']));
        $stmt2->bindParam(':peso_retido', implode('|', $_POST['cumu_retido']));
        $stmt2->bindParam(':peso_passante', implode('|', $_POST['peso_passante']));
        $stmt2->bindParam(':data', date('Y-m-d'));
        // Executa a consulta
        if ($stmt2->execute()) {
            $return = 1;
        } else {
            $return = "Erro ao inserir o registro: " . $stmt2->errorInfo()[2];
        }

    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    echo $return;

}


// gerar excel 
if ($_POST['action'] == 'gerar_xls') {

    $identificacao = null;
    $produto = null;
    $identificacao = null;
    $topo = null;
    $passante = null;
    $retido = null;
    $abertura = null;
    $peso = null;
    $peso_peneira = null;
    $prod_retido = null;   
    $percentual = null;
    $peso_retido = null;
    $peso_passante = null;

    $Conexao = ConexaoMYSQL::getConnection();
    $sql_select = "SELECT G_ID, G_PROD_ID, PO_ID_CARTA, PO_ID, PO_NUMERO, PO_PRODUTO, PO_NOTA, PO_LOTE, PO_PROD_DESC, G_IDENTIFICA, DATE_FORMAT(G_DATA,'%d/%m/%Y') G_DATA, 
        G_IDENTIFICA, G_TOPO, G_PASSANTE, G_RETIDO, G_ABERTURA, G_PESO, G_PESO_PENEIRA_PROD, G_PROD_RETIDO, G_PORCENT, G_PESO_RETIDO, G_PESO_PASSANTE
        FROM GRANULOMETRIA 
        INNER JOIN QUALIDADE_PRODUTO ON G_PROD_ID = PO_ID WHERE G_ID = '".$_POST['id']."'";
    // Prepara a consulta
    
    $stmt_select = $Conexao->prepare($sql_select);
    // Executa a consulta
    $stmt_select->execute();
    // Obtém um único registro como um array associativo
    $row = $stmt_select->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $identificacao = $row['G_IDENTIFICA'];
        $produto = $row['PO_NUMERO'].' '.$row['PO_PROD_DESC'].' Lote: '.$row['PO_LOTE'];
        $identificacao = $row['G_IDENTIFICA'];
        $topo = $row['G_TOPO'];
        $passante = $row['G_PASSANTE'];
        $retido = $row['G_RETIDO'];
        $abertura = $row['G_ABERTURA'];
        $peso = $row['G_PESO'];
        $peso_peneira = $row['G_PESO_PENEIRA_PROD'];
        $prod_retido = $row['G_PROD_RETIDO'];  
        $percentual = $row['G_PORCENT'];
        $peso_retido = $row['G_PESO_RETIDO'];
        $peso_passante = $row['G_PESO_PASSANTE'];
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $style = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '0B5281', // Código ARGB para azul
            ],
        ],
        'font' => [
            'bold' => true, 
            'color' => [
                'argb' => Color::COLOR_WHITE, // Código ARGB para branco
            ],
        ],
    ];
    $style2 = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'FFE28E', // Código ARGB para laranja
            ],
        ],
        'font' => [
            'bold' => true, 
            'color' => [
                'argb' => Color::COLOR_BLACK, // Código ARGB para branco
            ],
        ],
    ];

    $filename = 'Granulometria'.date('YmdHis');
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('D1', 'ANÁLISE GRANULOMÉTRICA');
    $sheet->setCellValue('D2', 'ESPECIFICAÇÃO DAS PARTÍCULAS PASSANTES');
    $sheet->setCellValue('K2', 'PASSANTE(%)');
    $sheet->setCellValue('L2', 'RETIDO(%)');

    $sheet->getStyle('D1:L1')->applyFromArray($style);
    $sheet->getStyle('D2:L2')->applyFromArray($style2);

    $sheet->mergeCells('D1:L1');
    $sheet->mergeCells('D2:J2');

    
    // topo
    $topo_explode = explode('|', $topo);
    $passante_explode = explode('|', $passante);
    $retido_explode = explode('|', $retido);
    
    $linha = 3;
    foreach($topo_explode as $key => $t){
        $coluna = 4;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, $t);
        $coluna = 11;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, $passante_explode[$key]);
        $coluna++;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, $retido_explode[$key]);
        $sheet->mergeCells('D'.$linha.':J'.$linha);
        $linha++;
    }

    $linha++;
    $linha++;
    $sheet->setCellValue('C'.$linha, 'Identificação da Amostra');
    $sheet->setCellValue('D'.$linha, 'Abertura de Peneira (mm)');
    $sheet->setCellValue('G'.$linha, 'Peso peneira (g)');
    $sheet->setCellValue('H'.$linha, 'Peso da Peneira + Produto (g)');
    $sheet->setCellValue('I'.$linha, 'Produto Retido (g)');
    $sheet->setCellValue('J'.$linha, '%');
    $sheet->setCellValue('K'.$linha, 'Cumulativo Retido');
    $sheet->setCellValue('L'.$linha, 'Peso Passante ');
    $sheet->mergeCells('D'.$linha.':F'.$linha);
    $sheet->getStyle('C'.$linha.':L'.$linha)->applyFromArray($style);
    
    $linha++;
    $sheet->setCellValueByColumnAndRow(3, $linha, $produto);
    $sheet->getStyle('C'.$linha)->getAlignment()->setWrapText(true);
    $merge_ident = $linha;
    // corpo
    $abertura_explode = explode('|', $abertura);
    $peso_explode = explode('|', $peso);
    $peso_peneira_explode = explode('|', $peso_peneira);
    $prod_retido_explode = explode('|', $prod_retido);
    $percentual_explode = explode('|', $percentual);
    $peso_retido_explode = explode('|', $peso_retido);
    $peso_passante_explode = explode('|', $peso_passante);

    foreach($abertura_explode as $key => $t){
        $coluna = 4;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, $t);
        $sheet->mergeCells('D'.$linha.':F'.$linha);
        $coluna = 7;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, (isset($peso_explode[$key]) ? $peso_explode[$key] : '*'));
        $coluna++;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, (isset($peso_peneira_explode[$key]) ? $peso_peneira_explode[$key] : '*'));
        $coluna++;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, (isset($prod_retido_explode[$key]) ? $prod_retido_explode[$key] : '*'));
        $coluna++;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, (isset($percentual_explode[$key]) ? $percentual_explode[$key] : '*'));
        $coluna++;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, (isset($peso_retido_explode[$key]) ? $peso_retido_explode[$key] : '*'));
        $coluna++;
        $sheet->setCellValueByColumnAndRow($coluna, $linha, (isset($peso_passante_explode[$key]) ? $peso_passante_explode[$key] : '*'));
        $linha++;
    }
    $sheet->mergeCells('C'.$merge_ident.':C'.$linha-1);
    $startColumn = 'D';
    $endColumn = 'L';  
    for ($column = $startColumn; $column <= $endColumn; $column++) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    $writer = new Xlsx($spreadsheet);
    $writer->save($_SERVER['DOCUMENT_ROOT'].'/file/ct/'.$filename.'.xlsx');

    echo $filename.'.xlsx';
}

// apagar dados da granulometria
if ($_POST['action'] == 'apagar-granulometria') {

    $return = 0;
    try {
        $Conexao = ConexaoMYSQL::getConnection();
        $sql = "DELETE FROM GRANULOMETRIA WHERE G_ID = '".$_POST['id']."'";
        $stmt = $Conexao->prepare($sql);
        if($stmt->execute()){
            $return = 1;
        }else{
            $return = 0;
        }
    }catch (Exception $e) {
        $return = $e->getMessage();
        exit;
    }
    echo $return;

}

