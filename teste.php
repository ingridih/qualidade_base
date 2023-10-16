<?php



$url = 'http://localhost/api/consulta-amostra?acao=produto&datade=2023-01-01&dataate=2023-09-01';

$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3QiLCJ1c2VybmFtZSI6IjEwMjEiLCJlbWFpbCI6ImNvbnRhdG9AZGlnZXMuY29tLmJyIiwiZXhwIjoiMjAyMy0xMC0wMSJ9.X7Az9byswDlJ71/GQCbZttS1CsoBYmw1AjbZJ5I1zqA=';
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
];

// Inicializa o cURL
$ch = curl_init($url);

// Configura as opções do cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna a resposta como uma string em vez de imprimi-la
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora a verificação SSL (não recomendado em produção)
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTPGET, true);

// Executa a requisição cURL e armazena a resposta na variável $resposta
$resposta = curl_exec($ch);


// Verifica se houve algum erro na requisição
if (curl_errno($ch)) {
    echo 'Erro na requisição cURL: ' . curl_error($ch);

}
// Obtém o código de resposta HTTP
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Fecha a sessão cURL
curl_close($ch);

// Verifica o código de resposta HTTP
if ($httpCode == 200) {
    echo $resposta;
    // Você pode processar a resposta aqui
} else {
    echo 'Erro na requisição (código ' . $httpCode . ')';
    // Lida com o erro de acordo com o código de resposta
}

?>