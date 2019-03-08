<script>
    import VcDatepicker from './Datepicker';

    export default {
        props: {
            parcelasSelecionada: {},
            pedidoSelecionado: {},
            formaPag: {}
        },

        components: {
            VcDatepicker
        },

        watch: {
            'formaPagamento': {
                handler(formaPagamento) {
                    if (formaPagamento == 'VISTA') {
                        this.$set(this, 'parcelas', {})
                    }
                }
            },
            'pedido.valor_desconto': {
                handler(valor) {
                    var soma = formatForCalc(this.pedido.valor) - formatForCalc(valor);
                    this.$set(this.pedido, 'valor_liquido', soma.formatMoney(2, ',', '.'));
                }
            }
        },

        data() {
            return {
                item: {},
                editando: false,
                totalForm: '0,00',
                itens: [],
                pedidoFaturar: false,
                pedido: {
                    'valor_desconto': '0,00'
                },
                formaPagamento: 'VISTA',
                parcelas: [],
                idPedodoImprimir: {},
                pessoaId: null,
                mostraSelectPessoa: true
            }
        },

        methods: {
            editaObj($event, _item) {
                let self = this;
                self.$set(self, 'editando', true);
                self.$set(self, 'item', JSON.parse(JSON.stringify(_item)));
                self.itens.splice(self.itens.indexOf(_item), 1);
            },

            remove($event, index) {
                const self = this;
                self.itens.splice(index, 1);
                self.atualizaValorForm();
            },

            adicionaItem($event, _item) {
                const self = this;

                if (typeof _item.id == 'undefined') {
                    $('#modal-danger .text').text("Informe um item e todos seus dados antes de continuar");
                    $('#modal-danger').modal();
                    return false;
                }

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')},
                    type: "POST",
                    url: '/pedidos/lists/getToSelect',
                    data: {'item': _item},
                    dataType: "json",
                    complete: function (response) {
                        self.$set(self, 'item', response.responseJSON);

                        if (self.editando) {
                            self.$set(self, 'editando', false);
                            self.itens.push(self.item);
                        } else {
                            if (self.verificaItemCadastrado(self.item)) {
                                self.$set(self.item, 'baixar', true);
                                self.itens.push(self.item);
                            }
                        }
                        self.atualizaValorForm();
                        self.$set(self, 'item', {});
                    }
                });

            },

            enviaProduto(id) {
                const self = this;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')},
                    type: "POST",
                    url: '/produtos/lists/getToSelect',
                    data: {'id': id, 'tipo': 'unico'},
                    dataType: "json",
                    complete: function (response) {
                        self.$set(self, 'item', response.responseJSON);
                        self.$set(self.item, 'valor_total', response.responseJSON.valor_venda);
                        self.$set(self.item, 'quantidade', '1');
                        self.$set(self.item, 'valor_desconto', '0,00');
                    }
                });
            },

            atualizaValorForm() {
                var soma = 0;
                var self = this;
                $.each(self.itens, function (index, item) {
                    soma += formatForCalc(item.valor_total);
                });

                self.$set(self, 'totalForm', soma.formatMoney(2, ',', '.'));
                self.$set(self.pedido, 'valor', soma);
                self.$set(self.pedido, 'valor_liquido', soma.formatMoney(2, ',', '.'));
            },

            verificaItemCadastrado(_item) {
                var self = this;
                var podeAdicionarProduto = true;

                $.each(self.itens, function (index, item) {
                    if (item.id == _item.id) {
                        $('#modal-danger .text').text("Produto já cadastrado!");
                        $('#modal-danger').modal();
                        podeAdicionarProduto = false;
                        return false;
                    }
                });

                return podeAdicionarProduto;
            },

            calcularParcelas() {
                const self = this;

                if (typeof self.pedido.nro_parcelas == 'undefined' || typeof self.pedido.valor_liquido == 'undefined' || typeof self.pedido.primeira_cobranca == 'undefined' || typeof self.pedido.valor_desconto == 'undefined') {
                    $('#modal-danger .text').text("Por favor informe todos os dados antes de prosseguir!");
                    $('#modal-danger').modal();
                } else {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')},
                        type: "POST",
                        url: '/pedidos/calculaParcelas',
                        data: {'pedido': self.pedido},
                        dataType: "json",
                        complete: function (response) {
                            self.$set(self, 'parcelas', response.responseJSON);
                        }
                    });
                }
            },

            validaForm() {
                const self = this;

                if (self.pedidoFaturar) {
                    if (self.pessoaId == null && self.formaPagamento == 'PRAZO') {
                        $('#modal-danger .text').text("Venda a prazo sem informar cliente, favor informar!");
                        $('#modal-danger').modal();
                        return false;
                    }

                    if (formatForCalc(self.totalForm) != (formatForCalc(self.pedido.valor_liquido) + formatForCalc(self.pedido.valor_desconto))) {
                        $('#modal-danger .text').text("Valor total diferente do valor pago, favor informar!");
                        $('#modal-danger').modal();
                        return false;
                    }
                } else {
                    if (self.pessoaId == null) {
                        $('#modal-danger .text').text("Informe o cliente antes de salvar, favor informar!");
                        $('#modal-danger').modal();
                        return false;
                    }
                }

                if (!self.itens.length > 0) {
                    $('#modal-danger .text').text("Venda sem produtos, favor informar!");
                    $('#modal-danger').modal();
                    return false;
                }

                return true;
            },

            salvarPedido() {
                const self = this;
                var _url = '/pedidos/gravar';

                if (self.pedidoSelecionado != null) {
                    _url = '/pedidos/update/' + self.pedidoSelecionado.id;
                }

                if (self.validaForm()) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')},
                        type: "POST",
                        url: _url,
                        data: {
                            'pedido': self.pedido,
                            'itens': self.itens,
                            'pessoaId': self.pessoaId,
                            'parcelas': self.parcelas,
                            'faturado': self.pedidoFaturar,
                            'formaPagamento': self.formaPagamento
                        },
                        dataType: "json",
                        complete: function (response) {
                            var data = response.responseJSON;
                            if (data.erro == 0) {
                                $('#modal-success-pedido .text').text(data.msg)
                                $('#modal-success-pedido').modal()
                                self.$set(self, 'idPedodoImprimir', data.pedido.id);
                            } else {
                                $('#modal-danger .text').text(data.msg)
                                $('#modal-danger').modal()
                            }
                        }
                    });
                }
            },

            getDateAtual() {
                var mes = new Date().getMonth() + 1;
                if (mes < 10) {
                    mes = '0' + mes;
                }
                return new Date().getDate() + '/' + mes + '/' + new Date().getFullYear();
            },

            imprimir(valor) {
                const self = this;
                if (valor == true) {
                    window.location.href = '/pedidos/imprimir/'+self.idPedodoImprimir;
                }
                $('#modal-success-pedido').modal('hide');
                self.$set(self, 'item', {});
                self.$set(self, 'totalForm', '0,00');
                self.$set(self, 'itens', []);
                self.$set(self, 'pedido', {});
                self.$set(self.pedido, 'valor_desconto', '0,00');
                self.$set(self, 'formaPagamento', 'VISTA');
                self.$set(self, 'parcelas', []);
                self.$set(self, 'pessoaId', null);
                self.$set(self, 'mostraSelectPessoa', true);
                self.$set(self, 'pedidoFaturar', false);
                $('select[name=selectPessoa]').val('').trigger('change')
            }
        },

        mounted() {
            const self = this;

            if (typeof self.pedidoSelecionado != 'undefined') {
                self.$set(self, 'mostraSelectPessoa', false);
                self.$set(self, 'pessoaId', self.pedidoSelecionado.pessoa_id);
                self.$set(self, 'pedido', self.pedidoSelecionado);
                self.$set(self.pedido, 'valor', self.pedidoSelecionado.valor_total);
                self.$set(self.pedido, 'desconto', self.pedidoSelecionado.valor_desconto);
                self.$set(self, 'pedidoFaturar', self.pedidoSelecionado.faturado);
                $.each(self.pedidoSelecionado.itens, function (index, item) {
                    self.$set(self.pedidoSelecionado.itens[index], 'nome', item.produto.nome);
                    self.$set(self.pedidoSelecionado.itens[index], 'id', item.produto_id);
                    self.$set(self.pedidoSelecionado.itens[index], 'produto_id', {});
                });
                self.$set(self, 'itens', self.pedidoSelecionado.itens);
                self.$set(self, 'totalForm', self.pedido.valor);
            }

            if (typeof self.formaPag != 'undefined') {
                self.$set(self, 'formaPagamento', self.formaPag);
                self.$set(self, 'parcelas', self.parcelasSelecionada);
                self.$set(self.pedido, 'nro_parcelas', self.pedidoSelecionado.conta.qtd_parcelas);
                self.$set(self.pedido, 'qtd_dias', self.pedidoSelecionado.conta.qtd_dias);
            }

            self.$set(self.pedido, 'primeira_cobranca', self.getDateAtual());

            $('select[name="nomeSelect"]').select2({
                placeholder: "Seleciona uma opção.",
                language: 'pt-br',
                ajax: {
                    headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')},
                    url: '/produtos/lists/getToSelect',
                    dataType: 'JSON',
                    method: 'POST',
                    data: function (parametros) {
                        return {
                            term: parametros.term,
                            ativo: '0'
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
                $('#apelido').val($(this).text());
                self.enviaProduto($(this).val());
                $(this).find('option').remove();
            });

            $('select[name="selectPessoa"]').select2({
                placeholder: "Seleciona uma opção.",
                language: 'pt-br',
                ajax: {
                    headers: {'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')},
                    url: '/pessoas/lists/getToSelect',
                    dataType: 'JSON',
                    method: 'POST',
                    data: function (parametros) {
                        return {
                            term: parametros.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.pessoas
                        };
                    }
                },
                minimumInputLength: 3
            }).on('change', function () {
                self.$set(self, 'pessoaId', $(this).val());
            });

        }
    }
</script>

<template>
    <div>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Produtos</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-md-8">
                                <select name="nomeSelect" class="form-control" id="idSelect"
                                        style="width : 100%"></select>
                            </div>

                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <input class="form-control" type="text" id="apelido" readonly
                                           placeholder="Apelido do produto"/>
                                </div>
                            </div>
                        </div>
                        <br><br>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input class="form-control" type="text" v-model="item.nome" id="nome"
                                                   readonly
                                                   placeholder="Nome do produto"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input class="form-control input-positive" id="quatidade" type="text"
                                                   v-model="item.quantidade" placeholder="Quantidade"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" id="vlrVenda"
                                                   v-model="item.valor_total"
                                                   v-money="item.valor_total" placeholder="Valor (R$)"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" v-model="item.valor_desconto"
                                                   v-money="item.valor_desconto"
                                                   id="vlrDesconto" placeholder="Desconto (R$)"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-effect-ripple btn-success btn-sm"
                                                    @click="adicionaItem($event, item)" title="Incluir"><i
                                                    class="fa fa-check"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table id="lista-produtos"
                               class="table table-dependencia table-striped table-bordered table-responsive table-hover"
                               v-show="itens.length > 0">
                            <thead>
                            <tr>
                                <th width="25%">Produto / Serviço</th>
                                <th width="15%" style="text-align:center">Quantidade</th>
                                <th width="15%" style="text-align:center">Valor (R$)</th>
                                <th width="15%" style="text-align:center">Desconto (R$)</th>
                                <th width="15%" style="text-align:center">Total (R$)</th>
                                <th width="5%">Ações</th>
                            </tr>
                            </thead>
                            <tbody v-for="(item, index) in itens">
                            <tr>
                                <td>{{ item.nome == null ? item.produto.nome : item.nome }}</td>
                                <td align="center">{{ item.quantidade }}</td>
                                <td align="center">{{ item.valor_unitario }}</td>
                                <td align="center">{{ item.valor_desconto }}</td>
                                <td align="center">{{ item.valor_total }}</td>
                                <td>
                                    <a @click="editaObj($event, item)" class="btn btn-success btn-xs btn-effect-ripple"><i
                                            class="fa fa-pencil"></i></a>
                                    <a @click="remove($event, index)" class="btn btn-danger btn-xs btn-effect-ripple"><i
                                            class="fa fa-close"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="row" id="total">
                            <div class="col-lg-12 col-xs-12 margim-bottom-10 text-center">
                                <hr class="hr-middle-pedido">
                                <h4 id="totalProdutos"><b>R$ {{ totalForm }}</b></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Fechamento</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <br>
                <div class="row">
                    <div class="col-xs-12 col-md-6" v-show="mostraSelectPessoa">
                        <select name="selectPessoa" class="form-control" style="width : 100%"></select>
                    </div>

                    <div class="col-xs-12 col-md-6" v-show="!pedido.faturado">
                        <input name="faturar" v-model="pedidoFaturar" type="checkbox" value="0">Faturar pedido
                    </div>
                </div>
                <br>
                <div v-show="pedidoFaturar">
                    <h5 class="box-title">Forma de pagamento</h5>
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <select v-model="formaPagamento" name="formaPagamento" class="form-control"
                                    style="width : 100%">
                                <option value="VISTA">A vista</option>
                                <option value="PRAZO">A prazo</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" type="text" v-model="pedido.valor_liquido" v-money="pedido.valor_liquido"
                                       placeholder="Valor pago(R$)"/>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input class="form-control" type="text" v-money="pedido.valor_desconto"
                                       v-model="pedido.valor_desconto"
                                       placeholder="Desconto (R$)"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div v-show="formaPagamento == 'PRAZO'">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <input class="form-control input-positive" type="text" v-model="pedido.nro_parcelas"
                                           placeholder="Quantidade parcelas"/>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <input class="form-control input-date" type="text"
                                           v-model="pedido.primeira_cobranca"
                                           placeholder="Data base para cobrança"/>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <input class="input-positive form-control" type="text" v-model="pedido.qtd_dias"
                                           placeholder="Dias entre parcelas"/>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <a @click="calcularParcelas()" class="btn btn-effect-ripple btn-success btn-sm">Calcular
                                        parcelas</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="lista-parcelas"
                           class="table table-dependencia table-striped table-bordered table-responsive table-hover"
                           v-show="parcelas.length > 0">
                        <thead>
                        <tr>
                            <th width="33%">Número</th>
                            <th width="33%" style="text-align:center">Data vencimento</th>
                            <th width="33%" style="text-align:center">Valor (R$)</th>
                        </tr>
                        </thead>
                        <tbody v-for="(parcela, index) in parcelas">
                        <tr>
                            <td>{{ parcela.nro_parcela }}</td>
                            <td align="center">
                                <input class="input-data-parcela input-date form-control" type="text"
                                       v-model="parcela.data_vencimento"/>
                            </td>
                            <td align="center">
                                <input class="form-control" v-money="parcela.valor" type="text"
                                       v-model="parcela.valor"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div v-show="!pedidoFaturar">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <textarea class="form-control" v-model="pedido.observacao" placeholder="Observações do pedido" :rows="7" type="text"></textarea>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>

        <div class="box-footer">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group form-actions">
                        <a class="btn-submit-form btn btn-effect-ripple btn-success" @click="salvarPedido()">Salvar
                            pedido</a>
                        <a href="/pedidos/listar" class="btn btn-effect-ripple btn-danger">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal modal-success fade in" id="modal-success-pedido" style="display: none; padding-right: 15px;">
            <input type="hidden" name="idPedido">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Successo!</h4>
            </div>
            <div class="modal-body text">
                <p>One fine body…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left reload" data-dismiss="modal">Não</button>
                <button type="button" class="btn btn-outline" @click="imprimir(true)">Sim</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
    </div>
</template>

<style scoped>
    #total h4 {
        margin-top: 3px;
    }

    #total .hr-middle-pedido {
        margin-top: 3px;
    }

    button.btn.btn-success.btn-sm {
        margin-top: 7px;
    }
</style>
