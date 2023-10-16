<?php
require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';


include $_SERVER['DOCUMENT_ROOT'].'/script/class-list-util.php';

class DataTableApi
{
    public $columnsDefault = [
        'id'         => true,
        "idenficacao" => true,
        "idenficacao_lab" => true,
        "tipo" => true,
        "urgencia" => true,
        "solicitante" => true,
        "status" => true,
        "data" => true,
        "usu" => true,
        "contra" => true,
        "produtos" => true,
    ];

    public function __construct()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');
    }

    public function init()
    {
        if (isset($_REQUEST['columnsDef']) && is_array($_REQUEST['columnsDef'])) {
            foreach ($_REQUEST['columnsDef'] as $field) {
                $columnsDefault[$field] = true;
            }
        }

        // get all raw data
        $alldata = $this->getJsonDecode();

        $data = [];
        // internal use; filter selected columns only from raw data
        foreach ($alldata as $d) {
            $data[] = $this->filterArray($d, $this->columnsDefault);
        }

        // filter by general search keyword
        if (isset($_REQUEST['search']['value']) && $_REQUEST['search']['value']) {
            $data = $this->arraySearch($data, $_REQUEST['search']['value']);
        }

        // count data
        $totalRecords = $totalDisplay = count($data);

        // sort
        if (isset($_REQUEST['order'][0]['column']) && $_REQUEST['order'][0]['dir']) {
            $column = $_REQUEST['order'][0]['column'];
            $dir    = $_REQUEST['order'][0]['dir'];
            usort($data, function ($a, $b) use ($column, $dir) {
                $a = array_slice($a, $column, 1);
                $b = array_slice($b, $column, 1);
                $a = array_pop($a);
                $b = array_pop($b);

                if ($dir === 'asc') {
                    return $a > $b ? 1 : -1;
                }

                return $a < $b ? 1 : -1;
            });
        }

        // pagination length
        if (isset($_REQUEST['length'])) {
            $data = array_splice($data, $_REQUEST['start'], $_REQUEST['length']);
        }

        $result = [
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalDisplay,
            'data'            => $data,
        ];

        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function filterArray($array, $allowed = [])
    {
        return array_filter(
            $array,
            function ($val, $key) use ($allowed) { // N.b. $val, $key not $key, $val
                return isset($allowed[$key]) && ($allowed[$key] === true || $allowed[$key] === $val);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    public function filterKeyword($data, $search, $field = '')
    {
        $filter = '';
        if (isset($search['value'])) {
            $filter = $search['value'];
        }
        if (!empty($filter)) {
            if (!empty($field)) {
                if (strpos(strtolower($field), 'date') !== false) {
                    // filter by date range
                    $data = $this->filterByDateRange($data, $filter, $field);
                } else {
                    // filter by column
                    $data = array_filter($data, function ($a) use ($field, $filter) {
                        return (boolean) preg_match("/$filter/i", $a[$field]);
                    });
                }

            } else {
                // general filter
                $data = array_filter($data, function ($a) use ($filter) {
                    return (boolean) preg_grep("/$filter/i", (array) $a);
                });
            }
        }

        return $data;
    }

    public function filterByDateRange($data, $filter, $field)
    {
        // filter by range
        if (!empty($range = array_filter(explode('|', $filter)))) {
            $filter = $range;
        }

        if (is_array($filter)) {
            foreach ($filter as &$date) {
                // hardcoded date format
                $date = date_create_from_format('m/d/Y', stripcslashes($date));
            }
            // filter by date range
            $data = array_filter($data, function ($a) use ($field, $filter) {
                // hardcoded date format
                $current = date_create_from_format('m/d/Y', $a[$field]);
                $from    = $filter[0];
                $to      = $filter[1];
                if ($from <= $current && $to >= $current) {
                    return true;
                }

                return false;
            });
        }

        return $data;
    }

    
    public function getJsonDecode(): mixed
    {
        $data = $_GET['data'];
        $d = explode(' ', $data);

        $d1 = substr($d[0], 0, 2);
        $m1 = substr($d[0], 3, 2);
        $a1 = substr($d[0], 6, 4);
        $dt1 = $a1.'-'.$m1.'-'.$d1;

        $d1 = substr($d[2], 0, 2);
        $m1 = substr($d[2], 3, 2);
        $a1 = substr($d[2], 6, 4);
        $dt2 = $a1.'-'.$m1.'-'.$d1;
        $dados = array();
        try {
            $Conexao = ConexaoMYSQL::getConnection();
            $query = $Conexao->query("SELECT 
                CA_ID,
                CA_STATUS,
                CA_SOLICITANTE,
                CA_TIPO,
                CA_IDENTIFICACAO_QUALI,
                CA_IDENTIFICACAO_LAB,
                CA_LABORATORIO,
                CA_URGENCIA, 
                CA_CONTRA,
                USU_NOME,
                DATE_FORMAT(CA_DATA,'%d/%m/%Y') CA_DATA
            FROM QUALIDADE_CARTA 
            LEFT JOIN QUALIDADE_USUARIO_LOGIN ON USU_ID = CA_SOLICITANTE WHERE CA_LABORATORIO = ".$_SESSION['USUEMPID']." AND CA_STATUS IN ('A', 'B', 'F') AND CA_DATA BETWEEN '".$dt1."' AND '".$dt2."'");
            while ($row = $query->fetch()) {
                $prod = NULL;
                $tipo_ar = [];
                $query2 = $Conexao->query("SELECT PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_NOTA, PO_TIPO  
                FROM QUALIDADE_PRODUTO 
                WHERE PO_ID_CARTA = ".$row['CA_ID']);
                while ($row2 = $query2->fetch()) {
                    $prod .= $row2['PO_NUMERO'].'|'.$row2['PO_PROD_DESC'].'|'.$row2['PO_LOTE'].'|'.$row2['PO_NOTA'];
                    if(!empty($row2['PO_TIPO'])){
                        $tipo_ar[] = $row2['PO_TIPO'];
                    }
                }
                $valoresUnicos = array_unique($tipo_ar);
                $tipo = implode('|', $valoresUnicos);

                $dados[] = array(
                    "id" => $row['CA_ID'],
                    "idenficacao" => $row['CA_IDENTIFICACAO_QUALI'],
                    "idenficacao_lab" => $row['CA_IDENTIFICACAO_LAB'],
                    "tipo" => $tipo,
                    "urgencia" => $row['CA_URGENCIA'],
                    "solicitante" => $row['USU_NOME'],
                    "status" => $row['CA_STATUS'],
                    "data" => $row['CA_DATA'],
                    "usu" => $row['CA_SOLICITANTE'],
                    "contra" => $row['CA_CONTRA'],
                    "produtos" => $prod
                );
            }

            return $dados;

        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }

       
    }

    /**
     * @param  array  $data
     *
     * @return array
     */

    public function arraySearch($array, $keyword)
    {
        $escapedKeyword = preg_quote($keyword, '/');
        return array_filter($array, function ($a) use ($escapedKeyword) {
            return (boolean) preg_grep("/$escapedKeyword/i", (array) $a);
        });
    }
   

}

$api = new DataTableApi;
$api->init();