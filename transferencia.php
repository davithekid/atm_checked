<?php
session_start();
 
 
if (isset($_SESSION['saldo'])) {
    echo "<h1 class='saldo1'><br>Saldo da conta: R$" . number_format($_SESSION['saldo'], 2, ',', '.') . " <br></h1>";
} else {
    echo "<h1 class='saldo'>Saldo da conta: R$0,00 <br></h1>";
}
 
if (!isset($_SESSION['limiteTransferencia'])) {
    $_SESSION['limiteTransferencia'] = 1500;
    echo "<h1 class='saldo2'> Limite de transferência diário da conta: R$" . number_format($_SESSION['limiteTransferencia'], 2, ',', '.') . "<br></h1>";
}
 
if (isset($_SESSION['limiteTransferencia'])) {
    echo "<h1 class='saldo2'> Limite de transferência diário da conta: R$" . number_format($_SESSION['limiteTransferencia'], 2, ',', '.') . "<br><br></h1>";
}
 
 
$arrayNomes = [
    'nome1' => 'Maria Clara Alves da Silva',
    'nome2' => 'João Pedro Souza Lima',
    'nome3' => 'Ana Beatriz Costa Silva',
    'nome4' => 'Carlos Eduardo Pereira',
    'nome5' => 'Gabriela Ferreira dos Santos',
    'nome6' => 'Lucas Henrique Gomes',
    'nome7' => 'Larissa Souza Rocha',
    'nome8' => 'Felipe Augusto Silva Costa',
    'nome9' => 'Mariana Oliveira Pinto',
    'nome10' => 'Rafael Almeida Rodrigues',
    'nome11' => 'Ana Carollini Rossi'
];
?>
 
<!DOCTYPE html>
<html lang="pt-br">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferência - BancoFlacko</title>
    <link rel="stylesheet" href="transferencia.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
</head>
 
<body>
 
    <h1 class="transferencia">Transferência Bancária</h1>
 
    <form class="original" action="transferencia.php" method="post">
        <label for="banco">Banco</label>
        <select name="banco" id="banco">
            <option value="104">104</option>
            <option value="260">260</option>
            <option value="001">001</option>
            <option value="341">341</option>
            <option value="237">237</option>
            <option value="033">033</option>
        </select>
 
        <label for="conta">Conta:</label>
        <input type="text" name="conta" id="conta" minlength="12" maxlength="12" required>
 
        <label for="digito">Dígito:</label>
        <input type="text" name="digito" id="digito" min="1" maxlength="1" required>
 
        <input type="submit" value="Enviar" name="formulario">
    </form>
 
    <?php
 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
 
        if (!isset($_SESSION['saldo']) || $_SESSION['saldo'] <= 0) {
            echo "<p style='color: red; text-align: center;'>Saldo Insuficiente.</p>";
        } else {
 
            if (isset($_POST['formulario'])) {
                $_SESSION['array'] = [
                    "Banco" => $_POST["banco"],
                    "Conta" => $_POST["conta"],
                    "Digito" => $_POST["digito"]
                ];
 
                echo "<form class='original2' action='transferencia.php' method='post' class='result'>";
                echo "Valor de transferência: <input type='number' name='transferencia' min='1' required>";
                echo "<input type='submit' value='Enviar'>";
                echo "</form>";
            } elseif (isset($_POST['transferencia'])) {
                $transferencia = filter_input(INPUT_POST, 'transferencia', FILTER_VALIDATE_INT);
 
                if ($transferencia === false || $transferencia <= 0) {
                    echo "<p style='color: red; text-align: center;'>Valor de transferência inválido.</p>";
                } elseif ($transferencia > $_SESSION['saldo']) {
                    echo "<p style='color: red; text-align: center;'>Saldo insuficiente.</p>";
                } elseif ($transferencia > $_SESSION['limiteTransferencia']) {
                    echo "<p style='color: red; text-align: center;'>Limite de transferência excedido.</p>";
                } else {
                    echo "<div class='result'>";
                    echo "Conta de Origem: ";
                    $rand_keys = array_rand($arrayNomes, 2);
                    echo $arrayNomes[$rand_keys[1]] . "<br>";
 
                    foreach ($_SESSION['array'] as $key => $value) {
                        echo "<br>" . $key . ": " . $value . "<br>";
                    }
 
                    echo "<br> Valor de transferência: R$" . number_format($transferencia, 2, ',', '.') . "<br>";
                    echo "Confirma transação? ";
 
                    echo '<form class="transf" action="transferencia.php" method="post">';
                   
                    echo "<button class='sim' type='submit' name='botaosim'>Sim</button>";
                    echo "<button class='sim' type='submit' name='nao'>Não</button>";
                   
                    echo "<input type='hidden' name='confirmarTransferencia' value='$transferencia' />";
                    echo '</form>';
                    echo "</div>";
                }
            } elseif (isset($_POST['botaosim'])) {
                $transferencia = $_POST['confirmarTransferencia'];
 
                if ($transferencia > $_SESSION['saldo']) {
                    echo "<p style='color: red; text-align: center;'>Saldo insuficiente</p>";
                } else {
                    $_SESSION['limiteTransferencia'] -= $transferencia;
                    $_SESSION['saldo'] -= $transferencia;
 
                    echo "<p style='color: lime; text-align: center;'>Transferência de R$" . number_format($transferencia, 2, ',', '.') . " efetuada com sucesso!</p>";
 
 
                    $arquivo = "meu_arquivo.txt";
                    $handle = fopen($arquivo, mode: "a");
                    fwrite($handle, "Transferência - R$" . number_format($transferencia, 2, ',', '.') . "\n");
                    fclose($handle);
                }
            } elseif (isset($_POST['nao'])) {
                echo "<p style='text-align: center;'>Operação cancelada. Nenhum valor foi transferido.</p>";
            }
        }
    }
    ?>
 
<footer>
 
<footer>
            <div class="footer-container">
            <button class="botao-footer"><a href="./index.php">Inicio</a></button>
            <div class="data">
            <?php
        // data atual
        $dataAtual = new DateTime();
        $timezone = new DateTimeZone('America/Sao_Paulo');
 
        $dataAtual->setTimezone($timezone);
        echo $dataAtual->format('d/F /Y à\s h:i');
 
 
        ?>
            </div>
            </div>
        </footer>
</body>
 
</html>
 