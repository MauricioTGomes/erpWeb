const self = this;

var insereHtml = function (produto) {
    var tbody = $('.produtos-cadastrados');
    tbody.html('');

    $.each(retorno, function (key, parcela) {
        tbody.append(
            "<tr>" +
            "<td>" +
            "<input type='text' class='input-index-parcela form-control' readonly name='array_parcela[" + key + "][nro_parcela]' value='" + parcela.nro_parcela + "'/>" +
            "</td>" +
            "<td>" +
            "<div class='form-group'>" +
            "<input class='input-data-parcela dtpick-par form-control' type='text' name='array_parcela[" + key + "][data_vencimento]' value='" + parcela.data_vencimento + "'/>" +
            "</div>" +
            "</td>" +
            "<td>" +
            "<div class='form-group'>" +
            "<input type='text' class='input-valor-parcela input-money form-control' name='array_parcela[" + key + "][valor]' value='" + parcela.valor + "'/>" +
            "</div>" +
            "</td>"
        );
    });
};

var adicionaItem = function () {
    console.log(this.item)
};

var enviaProduto = function (id) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')},
        type: "POST",
        url: '/produtos/lists/getToSelect',
        data: {'id': id, 'tipo': 'unico'},
        dataType: "json",
        complete: function (response) {
            var produto = response.responseJSON;
            $('#quantidade').val('1,00');
            $('#vlrVenda').val(produto.valor_venda);
            $('#nome').val(produto.nome);
            $('#vlrDesconto').val('1,00');
            $('#produtoId').val(produto.id);
        }
    });
};

$(document).ready(function () {
    $('.adiciona-produto').click(function () {
        console.log()
    });


    $('select[name="nomeSelect"]').select2({
        placeholder: "Seleciona uma opção.",
        language: 'pt',
        ajax: {
            headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')},
            url: '/produtos/lists/getToSelect',
            dataType: 'JSON',
            method: 'POST',
            data: function (parametros) {
                return {
                    term: parametros.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.produtos
                };
            }
        },
        minimumInputLength: 3
    }).on('change', function () {
        self.enviaProduto($(this).val());
        $(this).trigger('change.select2');
        $(this).select2('close');
    });
});