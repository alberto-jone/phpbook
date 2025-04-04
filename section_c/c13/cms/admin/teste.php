<?php
$uploads = __DIR__ . '/uploads/';
$file = $uploads . 'test.txt';

if (file_put_contents($file, 'Teste de escrita') !== false) {
    echo 'Sucesso: O PHP conseguiu escrever na pasta uploads.';
} else {
    echo 'Erro: O PHP NÃO conseguiu escrever na pasta uploads.';
}
?>
