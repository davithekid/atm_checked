<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimo - BancoFlacko</title>
    <script src="funcoes.js"></script>
    <link rel="stylesheet" href="emprestimo.css">

</head>

<body>
    <div class="emprestimo">
        <?php

        if (!isset($_SESSION['limiteEmprestimo'])) {
            $_SESSION['limiteEmprestimo'] = 1500;
            echo "<p class='limite' style='color: white;'Limite de Limite de Empréstimo:" . $_SESSION['limiteEmprestimo'];

        }

        if (isset($_SESSION['limiteEmprestimo'])) {
            echo "<p  class='limite' style='color: white;'><br>Limite de Empréstimo: R$" . $_SESSION['limiteEmprestimo'] . ",00  <br><br>";
        }

        ?>
        <script>
            // função para botões de selecionar data
            function validarBotao() {
                if (!document.querySelector('input[name="dias"]:checked')) {
                    alert("Por favor, escolha um dos períodos (30 dias, 60 dias ou 90 dias).");
                    return false;
                }
                return true;
            }
        </script>
    </div>
    <main>

        <!-- Formulário para pegar o valor de empréstimo -->

        <div class="frases">
            <h1>Valor Mínimo: 10R$ <br></h1>
            <h1>Pagamento em até 90 dias! <br></h1>

        </div>

        <div class="container">
            <section class="deposito-container">
                <form action="emprestimo.php" method="post" onsubmit="return validarBotao()">
                    <div class="teclado">
                        <input type="text" name="emprestimo" id="saque" required> <br>
                        <!-- Botões -->
                        <button class="botoes" type="button" onclick="adicionarNumero('1')">1</button>
                        <button class="botoes" type="button" onclick="adicionarNumero('2')">2</button>
                        <button class="botoes" type="button" onclick="adicionarNumero('3')">3</button><br>
                        <button class="botoes" type="button" onclick="adicionarNumero('4')">4</button>
                        <button class="botoes" type="button" onclick="adicionarNumero('5')">5</button>
                        <button class="botoes" type="button" onclick="adicionarNumero('6')">6</button><br>
                        <button class="botoes" type="button" onclick="adicionarNumero('7')">7</button>
                        <button class="botoes" type="button" onclick="adicionarNumero('8')">8</button>
                        <button class="botoes" type="button" onclick="adicionarNumero('9')">9</button><br>
                        <input type="submit" value="Enviar">
                        <button class="botoes" type="button" onclick="adicionarNumero('0')">0</button>
                        <button class="botoes" type="button" onclick="limparCampo()">Limpar</button><br>
                    </div>
                </form>
            </section>
            <section class="data">
                <h1 class="parcela1"><br>Data da primeira parcela: </h1>
                <div class="dias">
                    <br>Data da primeira parcela:
                    <br><input type="radio" id="dias30" name="dias" value="30" required>
                    <label for="dias30">30 dias</label>
                    <br><input type="radio" id="dias60" name="dias" value="60">
                    <label for="dias60">60 dias</label>
                    <br><input type="radio" id="dias90" name="dias" value="90" required>
                    <label for="dias90">90 dias</label>
                    <br><br>
                    <br><br>
                </div>
            </section>
        </div>

        </>
        </form>

    </main>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $emprestimo = $_POST['emprestimo']; // recebendo valor de emprestimo
    
        // cálciulo de parcelas/juros/valor final
    
        // se parcela for maior que 1.000 é possivel parcelar até 12x 12% de taxa
        // se parcela for maior que 500 é possivel para até 6x 6% de taxa
        // se parcela for maior que 30 é possivel parcelar até 3x , 2% de taxa
        // se for menor, voce nao pode parcelar, apenas devera pagar na data selecionada
    
        //parcelas de 12 meses
        $juros12 = 0.12;
        $parcela12 = 12;
        $valorDeParcela12 = ($emprestimo * (1 + $juros12)) / $parcela12;
        $valorFinal12 = $emprestimo * (1 + $juros12);


        // parcelas de 6 meses
        $juros6 = 0.06;
        $parcela6 = 6;
        $valorDeParcela6 = ($emprestimo * (1 + $juros6)) / $parcela6;
        $valorFinal6 = $emprestimo * (1 + $juros6);

        // parcelas de 3 meses
        $juros3 = 0.03;
        $parcela3 = 3;
        $valorDeParcela3 = ($emprestimo * (1 + $juros3)) / $parcela3;
        $valorFinal3 = $emprestimo * (1 + $juros3);


        // parcelas de 1 mes
        $juros1 = 0.02;
        $parcela1 = 1;
        $valorDeParcela1 = ($emprestimo * (1 + $juros1)) / $parcela1;
        $valorFinal1 = $emprestimo * (1 + $juros1);

        ?>
        <?php
        //  verificações
        if ($_SESSION['limiteEmprestimo'] < $emprestimo) {
            echo "<p style='color: red;'>Limite de empréstimo inválido.</p>";
        } else {

            // empréstimo não pode ser maior que 1500 e nem menor que 10
            if ($emprestimo > 1500 || $emprestimo < 10) {
                echo "<p style='color: red;'>Valor inválido.</p>";
            } else {


                '<section class="parcelas">';
                '<div class="parc-container">';
                // botões de parcelas 
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if ($emprestimo >= 1000) {
                        echo '<form class="teste"  action="emprestimo.php" method="post">';

                        // Botão de 12 parcelas
                        echo '<div class="parcela-container">
                                        <button class="botao-parc" type="submit" name="botao12">
                                            <span class="parcela-titulo">12 Parcelas</span><br>
                                            <span class="parcela-valor">R$ ' . number_format($valorDeParcela12, 2, ',', '.') . '</span>
                                        </button>
                                      </div>';

                        // Botão de 6 parcelas
                        echo '<div class="parcela-container">
                                        <button class="botao-parc" type="submit" name="botao6">
                                            <span class="parcela-titulo">6 Parcelas</span><br>
                                            <span class="parcela-valor">R$ ' . number_format($valorDeParcela6, 2, ',', '.') . '</span>
                                        </button>
                                      </div>';

                        // Botão de 3 parcelas
                        echo '<div class="parcela-container">
                                        <button class="botao-parc" type="submit" name="botao3">
                                            <span class="parcela-titulo">3 Parcelas</span><br>
                                            <span class="parcela-valor">R$ ' . number_format($valorDeParcela3, 2, ',', '.') . '</span>
                                        </button>
                                      </div>';

                        // Botão de 1 parcela
                        echo '<div class="parcela-container">
                                        <button class="botao-parc" type="submit" name="botao1">
                                            <span class="parcela-titulo">1 Parcela</span><br>
                                            <span class="parcela-valor">R$ ' . number_format($valorDeParcela1, 2, ',', '.') . '</span>
                                        </button>
                                      </div>';

                       
                        echo '<input type="hidden" name="emprestimo" value="' . $emprestimo . '"/>';
                        echo '</form>';

                    } else if ($emprestimo >= 500) {
                        echo '<form class="teste" action="emprestimo.php" method="post">';
                        // Botão de 6 parcelas
                        echo '<div class="parcela-container">
                                  <button class="botao-parc" type="submit" name="botao6">
                                      <span class="parcela-titulo">6 Parcelas</span><br>
                                      <span class="parcela-valor">R$ ' . number_format($valorDeParcela6, 2, ',', '.') . '</span>
                                  </button>
                                </div>';

                        // Botão de 3 parcelas
                        echo '<div class="parcela-container">
                                  <button class="botao-parc" type="submit" name="botao3">
                                      <span class="parcela-titulo">3 Parcelas</span><br>
                                      <span class="parcela-valor">R$ ' . number_format($valorDeParcela3, 2, ',', '.') . '</span>
                                  </button>
                                </div>';

                        // Botão de 1 parcela
                        echo '<div class="parcela-container">
                                  <button class="botao-parc" type="submit" name="botao1">
                                      <span class="parcela-titulo">1 Parcela</span><br>
                                      <span class="parcela-valor">R$ ' . number_format($valorDeParcela1, 2, ',', '.') . '</span>
                                  </button>
                                </div>';
                        
                        echo '<input type="hidden" name="emprestimo" value="' . $emprestimo . '"/>';
                        echo '</form>';
                    } else if ($emprestimo >= 10) {
                        echo '<form class="teste"  action="emprestimo.php" method="post">';
                        // Botão de 3 parcelas
                        echo '<div class="parcela-container">
                          <button class="botao-parc" type="submit" name="botao3">
                              <span class="parcela-titulo">3 Parcelas</span><br>
                              <span class="parcela-valor">R$ ' . number_format($valorDeParcela3, 2, ',', '.') . '</span>
                          </button>
                        </div>';

                        // Botão de 1 parcela
                        echo '<div class="parcela-container">
                          <button class="botao-parc" type="submit" name="botao1">
                              <span class="parcela-titulo">1 Parcela</span><br>
                              <span class="parcela-valor">R$ ' . number_format($valorDeParcela1, 2, ',', '.') . '</span>
                          </button>
                        </div>';
                      
                        echo '<input type="hidden" name="emprestimo" value="' . $emprestimo . '"/>';
                        echo '</form>';
                    }
                    '</section>';

                    '<section class="deposito-container">';
                    // casos de input de cada botão (selecionando parcela 12)
                    if (isset($_POST['botao12'])) {
                        echo '<div class="parc">';
                        echo "<h2 class='escritas-botao12'>Resultado de Simulação: <br> ";
                        echo "$parcela12 parcelas de: R$" . number_format($valorDeParcela12, 2, ',', '.') . '<br>';
                        echo "Valor entregue R$: " . number_format($emprestimo, 2, ',', '.') . '<br>';
                        echo "Taxa: " . number_format($juros12, 2, ',', '.') . "%" . '<br>';
                        echo "Valor final R$: " . number_format($valorFinal12, 2, ',', '.') . '<br>';
                        echo "Confirma transação? </h2>";

                        //formulário para confirmação de operação
                        echo '<form action="emprestimo.php" method="post">';
                        echo "<button class='resposta' type='submit' name='botaosim'>Sim</button>";
                        echo "<button class='resposta' type='submit' name='nao'>Não</button>";
                        echo '</div>';
                        echo "<input type='hidden' name='emprestimo' value='$emprestimo'/>";
                        echo '</form>';

                    }
                    if (isset($_POST['botao6'])) {
                        echo '<div class="parc">';
                        echo "<h2 class='escritas-botao6'>Resultado de Simulação: <br>";
                        echo "$parcela6 parcelas de: R$" . number_format($valorDeParcela6, 2, ',', '.') . '<br>';
                        echo "Valor entregue R$: " . number_format($emprestimo, 2, ',', '.') . '<br>';
                        echo "Taxa: " . number_format($juros6, 2, ',', '.') . "%" . '<br>';
                        echo "Valor final R$: " . number_format($valorFinal6, 2, ',', '.') . '<br>';
                        echo "Confirma transação? </h2>";

                        //formulário para confirmação de operação
                        echo '<form  action="emprestimo.php" method="post">';
                        echo "<button class='resposta' type='submit' name='botaosim'>Sim</button>";
                        echo "<button class='resposta' type='submit' name='nao'>Não</button>";
                        echo '</div>';
                        echo "<input type='hidden' name='emprestimo' value='$emprestimo'/>";
                        echo '</form>';

                    }
                }
                if (isset($_POST['botao3'])) {
                    echo '<div class="parc">';
                    echo "<h2 class='escritas-botao3'>Resultado de Simulação: <br> ";
                    echo "$parcela3 parcelas de: R$" . number_format($valorDeParcela3, 2, ',', '.') . '<br>';
                    echo "Valor entregue R$: " . number_format($emprestimo, 2, ',', '.') . '<br>';

                    echo "Taxa: " . number_format($juros3, 2, ',', '.') . "%" . '<br>';
                    echo "Valor final R$: " . number_format($valorFinal3, 2, ',', '.') . '<br>';
                    echo "Confirma transação? </h2>";

                    //formulário para confirmação de operação
                    echo '<form action="emprestimo.php" method="post">';
                    echo "<button class='resposta' type='submit' name='botaosim'>Sim</button>";
                    echo "<button class='resposta' type='submit' name='nao'>Não</button>";
                    echo '</div>';
                    echo "<input type='hidden' name='emprestimo' value='$emprestimo'/>";
                    echo '</form>';

                }
                if (isset($_POST['botao1'])) {
                    echo '<div class="parc">';
                    echo "<h2 class='escritas-botao1'>Resultado de Simulação: <br>";
                    echo "$parcela1 parcelas de: R$ " . number_format($valorDeParcela1, 2, ',', '.') . '<br>';
                    echo "Valor entregue R$: " . number_format($emprestimo, 2, ',', '.') . '<br>';
                    echo "Taxa: " . number_format($juros1, 2, ',', '.') . "%" . '<br>';
                    echo "Valor final R$: " . number_format($valorFinal1, 2, ',', '.') . '<br>';
                    echo "Confirma transação? </h2>";

                    //formulário para confirmação de operação
                    echo '<form  action="emprestimo.php" method="post">';
                    echo "<button class='resposta' type='submit' name='botaosim'>Sim</button>";
                    echo "<button class='resposta' type='submit' name='nao'>Não</button>";
                    echo '</div>';
                    echo "<input type='hidden' name='emprestimo' value='$emprestimo'/>";
                    echo '</form>';

                }
                '</div>';
                '</section>';
                // botão de confirmção
                if (isset($_POST['botaosim'])) {

                    $_SESSION['limiteEmprestimo'] -= $emprestimo;
                    echo "<p style='color: lime;'>Empréstimo de R$$emprestimo efetuado com sucesso!</p>";

                    $_SESSION['saldo'] = +$emprestimo;

                    $arquivo = "meu_arquivo.txt";

                    $handle = fopen($arquivo, "a");
                    fwrite($handle, "Empréstimo - R$" . number_format($emprestimo, 2, ',', '.') . "\n");
                    fclose($handle);

                }
            }

        }
    }
    ?>



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