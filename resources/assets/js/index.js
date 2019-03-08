$(document).on('click', '.btn-confirm-operation', function () {
    $('#modal-deletar').modal();
    $('input[name="modal-href"]').val($(this).attr('a-href'));
});

$(document).on('click', '.btn-confirm', function () {
    executaAjax($('input[name="modal-href"]').val());
    $('#modal-deletar').modal('hide');
});

$(document).on('click', '.reload', function () {
    location.href = location.href;
});


var executaAjax = function (_url) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        },
        type: "POST",
        url: _url,
        data: null,
        dataType: "json",
        complete: function (response) {
            if (response.responseJSON.erro == 0) {
                $('#modal-success .text').text(response.responseJSON.mensagem)
                $('#modal-success').modal()
            } else {
                $('#modal-danger .text').text(response.responseJSON.mensagem)
                $('#modal-danger').modal()
            }
        }
    });
};


$('.user.user-menu').click(function () {
    $(this).toggleClass('open')
})
