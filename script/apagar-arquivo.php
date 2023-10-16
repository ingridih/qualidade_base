    <?php
    function deletarArquivosPasta($caminhosPastas) {
        foreach ($caminhosPastas as $caminhoPasta) {
            
            if (!is_dir($caminhoPasta)) {
                throw new Exception("O caminho fornecido não é uma pasta.");
            }
        
            $arquivos = scandir($caminhoPasta);
        
            foreach ($arquivos as $arquivo) {
                if ($arquivo !== '.' && $arquivo !== '..') {
                    $caminhoCompleto = $caminhoPasta . DIRECTORY_SEPARATOR . $arquivo;
        
                    if (is_file($caminhoCompleto)) {
                        unlink($caminhoCompleto);
                        echo "Arquivo deletado: $arquivo <br>";
                    }
                }
            }
        }
    }

    // Exemplo de uso: deletar arquivos na pasta "exemplo"
    $caminhoPastaExemplo[] = $_SERVER['DOCUMENT_ROOT']."/file/ct";
    $caminhoPastaExemplo[] = $_SERVER['DOCUMENT_ROOT']."/file/laudoGerado";
    deletarArquivosPasta($caminhoPastaExemplo);
?>

