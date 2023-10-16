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


// gerar relatorio
if($_POST['action'] == 'gerar_relatorio'){ 

    $d = explode(' até ', $_POST['data']);

    $d1 = substr($d[0], 0, 2);
    $m1 = substr($d[0], 3, 2);
    $a1 = substr($d[0], 6, 4);
    $dt1 = $a1.'-'.$m1.'-'.$d1;

    $d2 = substr($d[1], 0, 2);
    $m2 = substr($d[1], 3, 2);
    $a2 = substr($d[1], 6, 4);
    $dt2 = $a2.'-'.$m2.'-'.$d2;

    $array_carta_pa = array();
    $array_carta_mp = array();
    $array_analise = array();
    $chaves = array();
    $elementos = array();

    $existe = 0;
    
    $Conexao = ConexaoMYSQL::getConnection();

    $query = $Conexao->query("SELECT CA_ID, CA_STATUS, CA_SOLICITANTE, CA_TIPO, CA_IDENTIFICACAO_QUALI, CA_IDENTIFICACAO_LAB,
    CA_LABORATORIO, CA_URGENCIA, CA_OBS_QUALI, CA_OBS_LAB, DATE_FORMAT(CA_DATA,'%d/%m/%Y') CA_DATA, CA_CONTRA, CA_LAUDO, DATE_FORMAT(CA_ANALISE,'%d/%m/%Y') CA_ANALISE, E_NOME, 
    PO_ID, PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_QTD, DATE_FORMAT(PO_DATA_FAB,'%d/%m/%Y') PO_DATA_FAB, PO_REG_MAPA, 
    DATE_FORMAT(PO_DATA_VALIDADE,'%d/%m/%Y') PO_DATA_VALIDADE, PO_TIPO, PO_NOTA, 
    PO_OBS_LAB, PO_OBS_QUALI, PO_ID_LAB, PO_ID_SOLICITANTE, DATE_FORMAT(PO_DATA_CRIADO,'%d/%m/%Y') PO_DATA_CRIADO, PO_OBS_LAUDO, PO_CLIENTE, 
    PO_METAIS_PESADOS, PO_DATA, PO_APROVADO, PO_CONTRA
    FROM QUALIDADE_CARTA  
    INNER JOIN QUALIDADE_PRODUTO ON CA_ID = PO_ID_CARTA 
    LEFT JOIN QUALIDADE_LABORATORIO ON E_ID = CA_LABORATORIO
    WHERE CA_DATA >= '".$dt1."' AND CA_DATA <= '".$dt2."' ");
    while($row = $query->fetch()){ 
        $existe++;
        $array_carta_pa[$row['PO_ID']] = $row;
        $chaves[] = $row['PO_ID'];
    }
    if($existe > 0) {   
        
        $chaves_string = implode(",", $chaves);
        $query2 = $Conexao->query("SELECT AE_ID, AE_PRODUTO_ID, AE_ELEMENTO_ID, AE_ELEMENTO, AE_GARANTIA, AE_VMA, AE_RESULTADO, AE_METODO, AE_QUALI
        FROM QUALIDADE_ANALISE
        WHERE AE_PRODUTO_ID IN (".$chaves_string.") ");
        while($row2 = $query2->fetch()){ 
            $array_analise[$row2['AE_PRODUTO_ID']][$row2['AE_ELEMENTO']] = $row2;
            $elementos[] = $row2['AE_ELEMENTO'];
        }

        $elementos = array_unique($elementos);

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
        $style_garantia = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'B7DEE8', // Código ARGB para azul
                ],
            ],
            'font' => [
                'bold' => true, 
                'color' => [
                    'argb' => Color::COLOR_BLACK, // Código ARGB para branco
                ],
            ],
        ];
        $style_resultado = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FABF8F', // Código ARGB para laranja
                ],
            ],
            'font' => [
                'bold' => true, 
                'color' => [
                    'argb' => Color::COLOR_BLACK, // Código ARGB para branco
                ],
            ],
        ];
        $style_reprovado = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FABF8F', // Código ARGB para laranja
                ],
            ],
            'font' => [
                'bold' => true, 
                'color' => [
                    'argb' => Color::COLOR_RED, // Código ARGB para branco
                ],
            ],
        ];
        $style_reprovado = [
            'font' => [
                'bold' => true, 
                'color' => [
                    'argb' => Color::COLOR_RED, // Código ARGB para VERMELHO
                ],
            ],
        ];

        $filename = date('YmdHis');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Laboratório');
        $sheet->setCellValue('B1', 'Produto');
        $sheet->setCellValue('C1', 'Quantidade');
        $sheet->setCellValue('D1', 'Identificação');
        $sheet->setCellValue('E1', 'Data Fabricação');
        $sheet->setCellValue('F1', 'Data Envio');
        $sheet->setCellValue('G1', 'Data Analise');
        $sheet->setCellValue('H1', 'Laboratorio Ident.');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'CP');
        $sheet->setCellValue('K1', 'Tipo');
        $sheet->getStyle('A1:K'. $sheet->getHighestRow())->applyFromArray($style);

        $startColumn = 'A'; // Coluna inicial (A)
        $endColumn = 'K';   // Coluna final (K)
        for ($column = $startColumn; $column <= $endColumn; $column++) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        // colocar os elementos na coluna. 
        $coluna = 12;
        foreach($elementos as $el){
            $sheet->setCellValueByColumnAndRow($coluna, 1, $el);
            $sheet->getStyleByColumnAndRow($coluna, 1)->applyFromArray($style_garantia);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, 1, $el);
            $sheet->getStyleByColumnAndRow($coluna, 1)->applyFromArray($style_resultado);
            $coluna++;
        }

        // correr os produtos existentes e as amostragens.
        $linha = 2;
        foreach($array_carta_pa as $key => $pa){
            $coluna = 1;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['E_NOME']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_PRODUTO']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_QTD']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['CA_IDENTIFICACAO_QUALI']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_DATA_FAB']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['CA_DATA']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['CA_ANALISE']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['CA_IDENTIFICACAO_LAB']);
            $coluna++;
            if($pa['PO_APROVADO'] == 'R'){
                $aprovado = 'Reprovado';
            }else if($pa['PO_APROVADO'] == 'A'){ 
                $aprovado = 'Aprovado';
            }else{
                $aprovado = '';
            }
            ($pa['PO_CONTRA'] != "") ? $contra = 'Sim' : $contra = 'Não';
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $aprovado);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $contra);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_TIPO']);
            $coluna++;

            foreach($elementos as $el){
                $garantia = (isset($array_analise[$key][$el]['AE_GARANTIA'])) ? $array_analise[$key][$el]['AE_GARANTIA'] : '';
                $analise_resultado = (isset($array_analise[$key][$el]['AE_RESULTADO'])) ? $array_analise[$key][$el]['AE_RESULTADO'] : '';
                $sheet->setCellValueByColumnAndRow($coluna, $linha, $garantia);
                $sheet->getStyleByColumnAndRow($coluna, $linha)->applyFromArray($style_garantia);
                $coluna++;
                $sheet->setCellValueByColumnAndRow($coluna, $linha, $analise_resultado);
                $sheet->getStyleByColumnAndRow($coluna, $linha)->applyFromArray($style_resultado);

                // REGRA DE TOLERANCIA -- TESTAR
                
                $garantia = (isset($array_analise[$key][$el]['AE_GARANTIA'])) ? $array_analise[$key][$el]['AE_GARANTIA'] : 0;
                $resultado = (isset($array_analise[$key][$el]['AE_RESULTADO'])) ? $array_analise[$key][$el]['AE_RESULTADO'] : 0;
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
                    if($str == 1){
                        $sheet->getStyleByColumnAndRow($coluna, $linha)->applyFromArray($style_reprovado);
                    }
                }
                $coluna++;
            }

            $linha++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename.'.xlsx');

        echo $filename.'.xlsx';
    }else{
        echo 2;
    }
}


// gerar relatorio COMPLETO
if($_POST['action'] == 'gerar_relatorio_completo'){ 

    $d = explode(' até ', $_POST['data']);

    $d1 = substr($d[0], 0, 2);
    $m1 = substr($d[0], 3, 2);
    $a1 = substr($d[0], 6, 4);
    $dt1 = $a1.'-'.$m1.'-'.$d1;

    $d2 = substr($d[1], 0, 2);
    $m2 = substr($d[1], 3, 2);
    $a2 = substr($d[1], 6, 4);
    $dt2 = $a2.'-'.$m2.'-'.$d2;

    $array_carta_pa = array();
    $array_carta_mp = array();
    $array_analise = array();
    $chaves = array();
    $elementos = array();
    $elementos_existe = array();

    $meses = array(
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro'
    );

    $nutrientes = array('B','Ca','Pb','Cl','Co','Cu','Cr','S','Fe','P','Mg','Mn','Mo','Ni','N','K','Se','Zn');


    $existe = 0;
    
    $Conexao = ConexaoMYSQL::getConnection();

    $query = $Conexao->query("SELECT CA_ID, CA_STATUS, CA_SOLICITANTE, CA_TIPO, CA_IDENTIFICACAO_QUALI, CA_IDENTIFICACAO_LAB,
    CA_LABORATORIO, CA_URGENCIA, CA_OBS_QUALI, CA_OBS_LAB, DATE_FORMAT(CA_DATA,'%d/%m/%Y') CA_DATA, CA_CONTRA, CA_LAUDO, DATE_FORMAT(CA_ANALISE,'%d/%m/%Y') CA_ANALISE, E_NOME, 
    PO_ID, PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_QTD, DATE_FORMAT(PO_DATA_FAB,'%d/%m/%Y') PO_DATA_FAB, PO_REG_MAPA, 
    DATE_FORMAT(PO_DATA_VALIDADE,'%d/%m/%Y') PO_DATA_VALIDADE, PO_TIPO, PO_NOTA, 
    PO_OBS_LAB, PO_OBS_QUALI, PO_ID_LAB, PO_ID_SOLICITANTE, DATE_FORMAT(PO_DATA_CRIADO,'%d/%m/%Y') PO_DATA_CRIADO, PO_OBS_LAUDO, PO_CLIENTE, 
    PO_METAIS_PESADOS, PO_DATA, PO_APROVADO, PO_CONTRA, EP_ESTUDO, EP_CAUSA, DATE_FORMAT(CA_DATA,'%m') MES, PO_FORNECEDOR 
    FROM QUALIDADE_CARTA  
    INNER JOIN QUALIDADE_PRODUTO ON CA_ID = PO_ID_CARTA 
    LEFT JOIN QUALIDADE_LABORATORIO ON E_ID = CA_LABORATORIO
    LEFT JOIN ESTUDO_CAUSA_PRODUTO ON PO_ID = EP_PROD_ID
    WHERE CA_DATA >= '".$dt1."' AND CA_DATA <= '".$dt2."' ");
    while($row = $query->fetch()){ 
        $existe++;
        $array_carta_pa[$row['PO_ID']] = $row;
        $chaves[] = $row['PO_ID'];
    }
    if($existe > 0) {   
        
        $chaves_string = implode(",", $chaves);
        $query2 = $Conexao->query("SELECT AE_ID, AE_PRODUTO_ID, AE_ELEMENTO_ID, AE_ELEMENTO, AE_GARANTIA, AE_VMA, AE_RESULTADO, AE_METODO, AE_QUALI
        FROM QUALIDADE_ANALISE
        WHERE AE_PRODUTO_ID IN (".$chaves_string.") ");
        while($row2 = $query2->fetch()){ 
            $array_analise[$row2['AE_PRODUTO_ID']][$row2['AE_ELEMENTO']] = $row2;
            $elementos[] = $row2['AE_ELEMENTO'];
            $elemen = explode('-',$row2['AE_ELEMENTO']);
            $elementos_existe[$row2['AE_PRODUTO_ID']][] = array('sigla' => trim($elemen[0]), 'nome' => trim($elemen[1]));
        }

        $elementos = array_unique($elementos);

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
        $style_garantia = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'B7DEE8', // Código ARGB para azul
                ],
            ],
            'font' => [
                'bold' => true, 
                'size' => 9,
                'color' => [
                    'argb' => Color::COLOR_BLACK, // Código ARGB para branco
                ],
            ],
        ];
        $style_resultado = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FABF8F', // Código ARGB para laranja
                ],
            ],
            'font' => [
                'bold' => true, 
                'size' => 9,
                'color' => [
                    'argb' => Color::COLOR_BLACK, // Código ARGB para branco
                ],
            ],
        ];
        $style_reprovado = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FABF8F', // Código ARGB para laranja
                ],
            ],
            'font' => [
                'bold' => true, 
                'color' => [
                    'argb' => Color::COLOR_RED, // Código ARGB para branco
                ],
            ],
        ];
        $style_reprovado = [
            'font' => [
                'bold' => true, 
                'color' => [
                    'argb' => Color::COLOR_RED, // Código ARGB para VERMELHO
                ],
            ],
        ];

        $filename = date('YmdHis');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Laboratório');
        $sheet->setCellValue('B1', 'Carta');
        $sheet->setCellValue('C1', 'Produto');
        $sheet->setCellValue('D1', 'Cliente');
        $sheet->setCellValue('E1', 'Fornecedor');
        $sheet->setCellValue('F1', 'Lote');
        $sheet->setCellValue('G1', 'Estudo de Causa');
        $sheet->setCellValue('H1', 'Mês');
        $sheet->setCellValue('I1', 'Data Fabricação');
        $sheet->setCellValue('J1', 'Data Solicitação');
        $sheet->setCellValue('K1', 'Data Resposta');
        $sheet->setCellValue('L1', 'Status');
        $sheet->setCellValue('M1', 'Nutriente 0');
        $sheet->setCellValue('N1', 'Nutriente 1');
        $sheet->setCellValue('O1', 'Nutriente 2');
        $sheet->setCellValue('P1', 'Nutriente 3');
        $sheet->setCellValue('Q1', 'Classificação 1');
        $sheet->setCellValue('R1', 'Classificação 2');
        $sheet->setCellValue('S1', 'Classificação 3');
        $sheet->getStyle('A1:S'. $sheet->getHighestRow())->applyFromArray($style);

        $startColumn = 'A'; // Coluna inicial (A)
        $endColumn = 'S';   // Coluna final (V)
        for ($column = $startColumn; $column <= $endColumn; $column++) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        // colocar os elementos na coluna. 
        $coluna = 17;
        foreach($elementos as $el){
            $sheet->setCellValueByColumnAndRow($coluna, 1, $el);
            $sheet->getStyleByColumnAndRow($coluna, 1)->applyFromArray($style_garantia);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, 1, $el);
            $sheet->getStyleByColumnAndRow($coluna, 1)->applyFromArray($style_resultado);
            $coluna++;
        }

        // correr os produtos existentes e as amostragens.
        $linha = 2;
        foreach($array_carta_pa as $key => $pa){
            $coluna = 1;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['E_NOME']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['CA_TIPO'].': '.$pa['CA_IDENTIFICACAO_QUALI']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_PRODUTO'].': '.$pa['PO_PROD_DESC']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_CLIENTE']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_FORNECEDOR']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_LOTE']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['EP_ESTUDO'].' - '.$pa['EP_CAUSA']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $meses[$pa['MES']]);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['PO_DATA_FAB']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['CA_DATA']);
            $coluna++;
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $pa['CA_ANALISE']);
            $coluna++;
            if($pa['PO_APROVADO'] == 'R'){
                $aprovado = 'Reprovado';
            }else if($pa['PO_APROVADO'] == 'A'){ 
                $aprovado = 'Aprovado';
            }else{
                $aprovado = '';
            }
            ($pa['PO_CONTRA'] != "") ? $contra = 'Sim' : $contra = 'Não';
            $sheet->setCellValueByColumnAndRow($coluna, $linha, $aprovado);
            $coluna++;

            // nutrientes existentes    
            $colunanutri = 14;
            $nutriexistente = null;
            $contador_nutriente = 1;
            foreach($elementos_existe[$key] as $nutri){
                if (in_array(trim($nutri['sigla']), $nutrientes)) {
                    if($contador_nutriente < 4){
                        $sheet->setCellValueByColumnAndRow($colunanutri, $linha, $nutri['nome']);
                        $nutriexistente .= $nutri['nome'].' ';
                        $contador_nutriente++;
                        $colunanutri++;
                    }
                }
            }
            $sheet->setCellValueByColumnAndRow(13, $linha, $nutriexistente);

            $coluna = 17;
            foreach($elementos as $el){
                $garantia = (isset($array_analise[$key][$el]['AE_GARANTIA'])) ? $array_analise[$key][$el]['AE_GARANTIA'] : '';
            }

            foreach($elementos as $el){
                $garantia = (isset($array_analise[$key][$el]['AE_GARANTIA'])) ? $array_analise[$key][$el]['AE_GARANTIA'] : '';
                $analise_resultado = (isset($array_analise[$key][$el]['AE_RESULTADO'])) ? $array_analise[$key][$el]['AE_RESULTADO'] : '';
                $sheet->setCellValueByColumnAndRow($coluna, $linha, $garantia);
                $sheet->getStyleByColumnAndRow($coluna, $linha)->applyFromArray($style_garantia);
                $coluna++;
                $sheet->setCellValueByColumnAndRow($coluna, $linha, $analise_resultado);
                $sheet->getStyleByColumnAndRow($coluna, $linha)->applyFromArray($style_resultado);

                // REGRA DE TOLERANCIA -- TESTAR
                
                $garantia = (isset($array_analise[$key][$el]['AE_GARANTIA'])) ? $array_analise[$key][$el]['AE_GARANTIA'] : 0;
                $resultado = (isset($array_analise[$key][$el]['AE_RESULTADO'])) ? $array_analise[$key][$el]['AE_RESULTADO'] : 0;
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
                    if($str == 1){
                        $sheet->getStyleByColumnAndRow($coluna, $linha)->applyFromArray($style_reprovado);
                    }
                }
                $coluna++;
            }

            $linha++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename.'.xlsx');

        echo $filename.'.xlsx';
    }else{
        echo 2;
    }
}
// gerar relatorio
if($_POST['action'] == 'apagar_relatorio'){ 
    
    //unlink($_POST['arquivo']);
}