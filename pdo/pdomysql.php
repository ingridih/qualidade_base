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
                $connection = new PDO('mysql:host=br14.hostgator.com.br;dbname=digesc43_base_qualidade', 'digesc43_admin', 'hJ.&d)K5#$Sn');
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
                $connection = new PDO('mysql:host=br14.hostgator.com.br;dbname=digesc43_ticket', 'digesc43_user_ticket', 'P!mIB^p7@6I86^GC');
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