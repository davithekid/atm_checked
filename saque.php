<?php
session_start();

$saque = 0; // Definindo uma variável inicial para o saque
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saque - BancoFlacko</title>
    <script src="funcoes.js"></script>
    <link rel="stylesheet" href="sacar.css">

</head>

<body>
    <main>
        <div class="saldo-container">

            <?php
            if (!isset($_SESSION['limite'])) {
                $_SESSION['limite'] = 0; // limite inicial de saque
            }

            echo "<h2 class='saldo'>Notas disponíveis: R$10,00, R$20,00, R$50,00, R$100. <br></h2>";
            echo "<h2 class='saldo'><br> Limite Diário: R$2000,00. <br></h2>";

            if (isset($_SESSION['saldo'])) {
                echo "<h2 class='saldo'><br>Saldo da conta: R$" . $_SESSION['saldo'] . ",00  <br><br>";
            } else {
                echo "<h2 class='saldo'>Saldo da conta: R$0,00 <br><br></h2>";
            }
            ?>
        </div>
        <section class="deposito-container">
            <h1 class="titulo">SAQUE</h1>
            <form action="saque.php" method="post" id="formSaque">
                <div class="teclado">
                    <input type="text" id="saque" name="enviar" value="" id="inputSaque"> <br> <br>
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

            // verificação se formulário foi enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {


            // verificações
            
            if (!isset($_SESSION["saldo"])) {
                echo "<p style='color: red;'>Saldo Insuficiente.</p>";
            } else {

                if (isset($_POST['enviar'])) {

                    // validação número inteiro
                    $saque = filter_input(INPUT_POST, 'enviar', FILTER_VALIDATE_INT);
                    if ($saque === false || $saque < 10) {
                        echo "<p style='color: red;'>Valor de saque inválido. O valor mínimo de saque é R$10,00.</p>";
                    } elseif ($saque % 10 != 0) {

                        // caso o valor não seja múltiplo de 10, pede para arredondar
                        $saqueArredondado = floor($saque / 10) * 10;
                        echo "<h2 class='nota'>Infelizmente não temos notas para imprimir esse valor, deseja arredondar para um valor mais baixo? O valor arredondado seria: R$$saqueArredondado,00</h2>";
                        echo '<form action="saque.php" method="post">';
                        echo "<button type='submit' name='sim'>Sim</button>";
                        echo "<button type='submit' name='nao'>Não</button>";
                        echo "<input type='hidden' name='saque' value='$saque'/>";
                        echo '</form>';
                    } else {

                        if ($saque <= $_SESSION['saldo'] && $_SESSION['limite'] + $saque <= 2000) {
                            $_SESSION['saldo'] -= $saque;
                            $_SESSION['limite'] += $saque;
                            echo "<p class='sucesso' style='color: orange;'>Limite total após saque: R$" . $_SESSION['limite'] . ",00</p>";
                            echo "<p class='sucesso' style='color: lime;'>Saque de R$$saque efetuado com sucesso!</p>";

                            // criando arquivo txt para armazenar operação
                            $arquivo = "meu_arquivo.txt";

                            $handle = fopen($arquivo, "a");
                            fwrite($handle, "Saque - R$" . number_format($saque, 2, ',', '.') . "\n");
                            fclose($handle);


                        } else {
                            echo "<p class='invalido' style='color: red;'>Valor superior ao saldo disponível ou limite diário atingido.</p>";
                        }
                    }
                }

                // se o usuário selecionar o botão sim
                if (isset($_POST['sim'])) {
                    $saque = $_POST['saque'];


                    $saqueArredondado = floor($saque / 10) * 10; // arredonda para o múltiplo de 10 mais próximo
        
                    if ($saqueArredondado <= $_SESSION['saldo'] && $_SESSION['limite'] + $saqueArredondado <= 2000) {
                        $_SESSION['saldo'] -= $saqueArredondado;
                        $_SESSION['limite'] += $saqueArredondado;
                        echo "<p style='color: lime;'>Saque de R$$saqueArredondado efetuado com sucesso!</p>";
                        echo "<p>Saldo Atual: R$" . $_SESSION['saldo'] . ",00</p>";
                        echo "<p>Limite total após saque: R$" . $_SESSION['limite'] . ",00 <br> </p>";
                        $saldoAtual = $_SESSION['saldo'];

                        // criando arquivo txt para armazenar operação
        
                        $arquivo = "meu_arquivo.txt";

                        $handle = fopen($arquivo, "a");
                        fwrite($handle, "Saque - R$" . number_format($saqueArredondado, 2, ',', '.') . "\n");
                        fclose($handle);
                    } else {
                        echo "<p class='erro' style='color: red;'>Não foi possível realizar o saque, saldo ou limite insuficientes.</p>";
                    }
                }

                if (isset($_POST['nao'])) {
                    echo "<p>Operação cancelada. Nenhum valor foi arredondado.</p>";
                }
            }
        }
        ?>

    </main>
    <footer>
        <button class="botao-footer"><a href="./index.php">Inicio</a></button>
        <div class="horario">
            <?php
            // data atual
            $dataAtual = new DateTime();
            $timezone = new DateTimeZone('America/Sao_Paulo');

            $dataAtual->setTimezone($timezone);
            echo $dataAtual->format('d/F /Y à\s h:i');
            ?>
        </div>
    </footer>

</body>

</html>