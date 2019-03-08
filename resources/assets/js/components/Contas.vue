<script>

    import VcParcelas from './Parcelas.vue';

    export default {
        props: {
            contasListar: {},
            tipo: {
                type: String,
                default: 'R'
            },
        },

        components: {
            VcParcelas
        },
        data() {
            return {
                contas: {},
                conta: {},
                parcelas: {},
                mostrarParcelas: false,
                parcelasPagar: {},
                parcelasEstornar: {}
            }
        },

        methods: {
            eliminar() {
                const self = this;

                $('#modal-deletar-conta').modal('hide');

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')},
                    type: "POST",
                    url: '/contas/deletar/' + self.conta.id,
                    data: {},
                    dataType: "json",
                    complete: function (response) {
                        var data = response.responseJSON;
                        if (data.erro == 0) {
                            self.contas.splice(self.conta.original_index, 1);
                            self.$set(self, 'conta', {});
                            $('#modal-success-sem-reload .text').text(data.msg)
                            $('#modal-success-sem-reload').modal()
                        } else {
                            $('#modal-danger .text').text(data.msg)
                            $('#modal-danger').modal()
                        }
                    }

                });

            },

            alterar(conta) {
                if (this.tipo == 'R') {
                    window.location.href = '/contas/receber/alterar/' + conta.id;
                } else {
                    window.location.href = '/contas/pagar/alterar/' + conta.id;
                }
            },

            abreModal(contaEliminar) {
                const self = this;
                self.$set(self, 'conta', contaEliminar);
                self.$set(self, 'mostrarParcelas', false);
                self.conta.original_index = self.contas.indexOf(contaEliminar);
                $('#modal-deletar-conta').modal();
            },

            atualizaContas() {
                const self = this;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: '/contas/buscaContas',
                    data: {'tipo': self.tipo},
                    dataType: "json",
                    complete: function (response) {
                        var data = response.responseJSON;
                        self.$set(self, 'contas', data);
                    }
                });
            },

            estornarParcelas(contaSelecionada) {
                const self = this;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: '/contas/parcelas/buscaParcelas',
                    data: {'baixada': '1', 'conta_id': contaSelecionada.id},
                    dataType: "json",
                    complete: function (response) {
                        var data = response.responseJSON;
                        self.$set(self, 'parcelasEstornar', data);
                        self.$set(self, 'parcelasPagar', {});
                    }
                });

                self.$set(self, 'conta', contaSelecionada);
                self.$set(self, 'mostrarParcelas', true);
            },

            buscaParcelas(contaSelecionada) {
                const self = this;

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: '/contas/parcelas/buscaParcelas',
                    data: {'baixada': '0', 'conta_id': contaSelecionada.id},
                    dataType: "json",
                    complete: function (response) {
                        var data = response.responseJSON;
                        self.$set(self, 'parcelasPagar', data);
                        self.$set(self, 'parcelasEstornar', {});
                    }
                });

                self.$set(self, 'conta', contaSelecionada);
                self.$set(self, 'mostrarParcelas', true);
            }

        },

        mounted() {
            const self = this;

            if (typeof self.contasListar != 'undefined') {
                self.$set(self, 'contas', self.contasListar);
            }
        }
    }
</script>

<template>
    <div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <a v-if="tipo == 'R'" href="/contas/receber/incluir" class="btn btn-effect-ripple btn-success">
                        <i class="fa fa-plus"></i> Adicionar conta
                    </a>
                    <a v-if="tipo == 'P'" href="/contas/pagar/incluir" class="btn btn-effect-ripple btn-success">
                        <i class="fa fa-plus"></i> Adicionar conta
                    </a>
                </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                                <thead>
                                <tr>
                                    <th width="10%">Título</th>
                                    <th width="10%">Data emissão</th>
                                    <th width="20%">Nome</th>
                                    <th width="15%">Valor total(R$)</th>
                                    <th width="15%">Valor restante(R$)</th>
                                    <th width="15%">Ações</th>
                                </tr>
                                </thead>
                                <tbody v-for="(conta, index) in contas">
                                <tr>
                                    <td>{{ conta.titulo }}</td>
                                    <td align="center">{{ conta.data_emissao }}</td>
                                    <td align="center">{{ conta.pessoa.nome != null ? conta.pessoa.nome : conta.pessoa.fantasia }}</td>
                                    <td align="center">{{ conta.vlr_total }}</td>
                                    <td align="center">{{ conta.vlr_restante }}</td>
                                    <td>
                                        <a @click="estornarParcelas(conta)" title="Estornar parcelas"
                                           class="btn btn-effect-ripple btn-xs btn-success"
                                           data-original-title="Estornar parcelas"><i class="fa fa-undo"></i></a>
                                        <a @click="buscaParcelas(conta)" title="Baixar parcelas"
                                           class="btn btn-effect-ripple btn-xs btn-success"
                                           data-original-title="Baixar parcelas"><i class="fa fa-money"></i></a>
                                        <a class="btn btn-danger btn-xs btn-effect-ripple btnEliminarConta" @click="abreModal(conta)"><i
                                                class="fa fa-close"></i></a>
                                        <a @click="alterar(conta)" title="Alterar"
                                           class="btn btn-effect-ripple btn-xs btn-success"
                                           data-original-title="Alterar"><i class="fa fa-pencil"></i></a>
                                    </td>
                                </tr>
                                <tr v-if="contasListar == undefined || contasListar.length <= 0">
                                    <td colspan="6" class="text-center margim-bottom-10"><h5 style="font-weight: normal">Nenhum registro encontrado</h5></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="mostrarParcelas">
        <vc-parcelas
            :parcelas-estornar="parcelasEstornar"
            :parcelas-pagar="parcelasPagar"
            :conta="conta"
            @atualizaConta="atualizaContas">
        </vc-parcelas>
        </div>

        <div class="modal modal-info fade in" id="modal-deletar-conta" style="display: none; padding-right: 15px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <input type="hidden" name="modal-id">
                    <div class="modal-header text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Atenção!</h4>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja realizar esta operação? <br> Não será possível reverter</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Não</button>
                        <button type="button" class="btn btn-outline" @click="eliminar()">Sim</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>