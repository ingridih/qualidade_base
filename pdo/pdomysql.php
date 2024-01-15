<?php 


class ConexaoMYSQL
{
    private static $connection;
    private function __construct()
    {
    }
    public static function getConnection()
    {
        global $dbConfig;
        try {
            if (!isset($connection)) {
                $connection = new PDO('mysql:seuhost.com.br;dbname=suabase', 'usuario', 'senha');
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $connection;
        } catch (PDOException $e) {
            $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
            $mensagem .= "\nErro: " . $e->getMessage();
            throw new Exception($mensagem);
        }
        
    }
}


class ConexaoMYSQL_ticket
{
    private static $connection;
    private function __construct()
    {
    }
    public static function getConnection()
    {
        global $dbConfig;
        try {
            if (!isset($connection)) {
                $connection = new PDO('mysql:seuhost.com.br;dbname=suabase', 'usuario', 'senha');
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $connection;
        } catch (PDOException $e) {
            $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
            $mensagem .= "\nErro: " . $e->getMessage();
            throw new Exception($mensagem);
        }
        
    }
}



?>
