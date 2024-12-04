<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposito - BancoFlacko</title>
    <script src="funcoes.js"></script>
    <link rel="stylesheet" href="dep.css">
</head>

<body>
    <div class="animated-gradient"></div>
    <main>
        <div class="saldo-container">

            <?php

            function dataHora()
            {
                $dataAtual = new DateTime();
                $timezone = new DateTimeZone('America/Sao_Paulo');

                $dataAtual->setTimezone($timezone);
                echo $dataAtual->format('d/F /Y à\s h:i');
            }

            session_start();

            // exibindo saldo atual
            if (isset($_SESSION['saldo'])) {
                echo '<h1 class="saldo">SALDO ATUAL: R$ ' . number_format($_SESSION['saldo'], 2, ',', '.') . '</h1>';
            } else {
                echo "<h2 class='saldo'> Saldo da conta: R$0,00 </h2>";
            }
            ?>
        </div>

        <section class="deposito-container">
            <h1 class="titulo">DEPÓSITO</h1>
            <form action="dep.php" method="post">
                <h2 class="minimo">Valor mínimo de depósito: R$10,00.</h2>
                <h2 class="maximo">O valor máximo permitido para depósitos é de R$ 5.000,00 por dia.</h2>
                <div class="teclado">
                    <input type="text" id="saque" name="enviar"> <br><br>
                    <!-- botões -->
                    <button class="botoes" type="button" onclick="adicionarNumero('1')">1</button>
                    <button class="botoes" type="button" onclick="adicionarNumero('2')">2</button>
                    <button class="botoes" type="button" onclick="adicionarNumero('3')">3</button><br>
                    <button class="botoes" type="button" onclick="adicionarNumero('4')">4</button>
                    <button class="botoes" type="button" onclick="adicionarNumero('5')">5</button>
                    <button class="botoes" type="button" onclick="adicionarNumero('6')">6</button><br>
                    <button class="botoes" type="button" onclick="adicionarNumero('7')">7</button>
                    <button class="botoes" type="button" onclick="adicionarNumero('8')">8</button>
                    <button class="botoes" type="button" onclick="adicionarNumero('9')">9</button><br>
                    <button class="botoes" type="submit">Enviar</button>
                    <button class="botoes" type="button" onclick="adicionarNumero('0')">0</button>
                    <button class="botoes" type="button" onclick="limparCampo()">Limpar</button><br>
                </div>
            </form>
        </section>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //validação número inteiro
            $valorDeposito = filter_input(INPUT_POST, 'enviar', FILTER_VALIDATE_INT);
            if ($valorDeposito === false) {
                $valorDeposito = 0;
            }

            // definindo limite
            if (isset($_SESSION['saldo']) && ($_SESSION['saldo'] + $valorDeposito) > 5000) {
                echo "<p class='erro' style= 'color: red;'> Limite máximo de depósito atingido. </p>";
            } else {
                if ($valorDeposito < 10 || $valorDeposito > 5000) {
                    echo "<p class='invalido' style='color: red;'>Valor inválido para depositar! </p>";
                } else {

                    // saldo por padrão 0
                    if (!isset($_SESSION['saldo'])) {
                        $_SESSION['saldo'] = 0;
                    }
                    $_SESSION['saldo'] += $valorDeposito;

                    // criando arquivo txt para armazenar operação e valor

                    $arquivo = "meu_arquivo.txt";

                    $handle = fopen($arquivo, "a");
                    fwrite($handle, "Depósito - R$" . number_format($valorDeposito, 2, ',', '.') . "\n");
                    fclose($handle);


                    echo "<p class='sucesso' style='color: lime;'> Depósito de R$" . number_format($valorDeposito, 2, ',', '.') . " realizado com sucesso!<br></p>";
                }
            }
        }
        ?>
    </main>

    <footer>
        <button class="botao-footer"><a href="./index.php">INICIO</a></button>

        <?php
        // função para aparecer horário             
            
        echo dataHora();

        ?>
    </footer>

</body>

</html>