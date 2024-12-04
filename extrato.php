<?php
session_start();
 
 
function dataHora()
{
   
    $dataAtual = new DateTime();
    $timezone = new DateTimeZone('America/Sao_Paulo');
    $dataAtual->setTimezone($timezone);
    return $dataAtual->format('d/m/Y à\s h:i');
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato - BancoFlacko</title>
    <link rel="stylesheet" href="extrato.css">
</head>
 
<body>
 
    <header>
        <div class="header-content">
            <h1>Extrato Bancário</h1>
            <?php
            if (isset($_SESSION['saldo'])) {
                echo "<p><strong>Saldo da Conta:</strong> R$" . number_format($_SESSION['saldo'], 2, ',', '.') . "</p>";
            } else {
                echo "<p><strong>Saldo da Conta:</strong> R$0,00</p>";
            }
            ?>
        </div>
    </header>
 
    <main>
        <div class="extrato">
            <h2>Extrato de Transações</h2>
            <div class="transacoes">
                <?php
                $arquivo = "meu_arquivo.txt";
                if (file_exists($arquivo)) {
                    $handle = fopen($arquivo, "r");
                    echo "<table class='transacao-table'>";
                    echo "<tr><th>Data</th><th>Histórico</th></tr>";
 
                    while (($line = fgets($handle)) !== false) {
                       
                        list($operacao) = explode('|', $line);
                       
                        echo "<tr>";
                        echo "<td>" . dataHora() . "</td>";
                        echo "<td>" . htmlspecialchars(trim($operacao)) . "</td>";
                        echo "</tr>";
                    }
 
                    echo "</table>";
                    fclose($handle);
                } else {
                    echo "<p><em>Nenhuma transação encontrada.</em></p>";
                }
                ?>
            </div>
        </div>
    </main>
 
    <footer>
        <div class="footer-content">
            <a href="./index.php" class="botao-footer">INICIO</a>
            <p>Data e Hora Atual: <?php echo dataHora(); ?></p>
        </div>
    </footer>
 
</body>
 
</html>
 