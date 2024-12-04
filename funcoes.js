function adicionarNumero(numero) {
    var campoSaque = document.getElementById('saque');
 
    if (campoSaque.value === "0") {
        campoSaque.value = numero;
    } else {
        campoSaque.value += numero;
    }
}
 
// função de limpar campo
function limparCampo() {
    document.getElementById('saque').value = "";
 
}
 
 
 