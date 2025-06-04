$(document).ready(function () {
    // Máscaras fixas
    $('#telefone').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');

    const cpfCnpjField = $('#cpf_cnpj');

    // Enquanto digita: mantém em caixa alta e limita a 14 caracteres
    cpfCnpjField.on('input', function () {
        let val = $(this).val().toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (val.length > 14) val = val.substring(0, 14);
        $(this).val(val);
    });

    // Função para aplicar a máscara correta
    function aplicarMascara() {
        let val = cpfCnpjField.val().replace(/[^A-Z0-9]/g, '');
        
        cpfCnpjField.unmask();

        if (val.length === 0) return;

        if (/^[0-9]+$/.test(val)) {
            // Apenas números
            if (val.length <= 11) {
                cpfCnpjField.mask('000.000.000-00');
            } else {
                cpfCnpjField.mask('00.000.000/0000-00');
            }
        } else {
            // Contém letras
            cpfCnpjField.mask('AA.AAA.AAA/AAAA-AA', {
                translation: {
                    'A': { pattern: /[A-Z0-9]/ }
                },
                onKeyPress: function(value, e, field, options) {
                    field.val(field.val().toUpperCase());
                }
            });
        }
        
        // Força a atualização do valor com a máscara
        cpfCnpjField.val(val).trigger('input');
    }

    // Aplica máscara ao sair do campo
    cpfCnpjField.on('blur', aplicarMascara);

    // Remove máscara ao focar para facilitar edição
    cpfCnpjField.on('focus', function() {
        let val = $(this).val().replace(/[^A-Z0-9]/g, '');
        $(this).unmask().val(val);
    });

    // Aplica máscara inicial se houver valor
    if (cpfCnpjField.val().length > 0) {
        aplicarMascara();
    }
});