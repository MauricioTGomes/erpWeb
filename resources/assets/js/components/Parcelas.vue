<script>

    export default {
        props: {
            parcelasPagar: {},
            parcelasEstornar: {}
        },

        watch: {
            'parcelasPagar': {
                handler() {
                    if (this.parcelasPagar.length >= 0) {
                        this.$set(this, 'parcelas', this.parcelasPagar);
                        this.$set(this, 'modo', 'pagar');
                    }
                }
            },
            'parcelasEstornar': {
                handler() {
                    if (this.parcelasEstornar.length >= 0) {
                        this.$set(this, 'parcelas', this.parcelasEstornar);
                        this.$set(this, 'modo', 'estornar');
                    }
                }
            }
        },

        data() {
            return {
                parcelas: {},
                modo: {
                    type: String,
                    default: 'pagar'
                }
            }
        },

        methods: {
            estornar(parcela) {
                const self = this;
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: '/contas/parcelas/estornar/' + parcela.id,
                    data: parcela,
                    dataType: "json",
                    complete: function (response) {
                        var data = response.responseJSON;
                        if (data.erro == 0) {
                            self.parcelas.splice(self.parcelas.indexOf(parcela), 1);
                            $('#modal-success-sem-reload .text').text(data.msg)
                            $('#modal-success-sem-reload').modal()
                        } else {
                            $('#modal-danger .text').text(data.msg)
                            $('#modal-danger').modal()
                        }
                        self.$emit('atualizaConta')
                    }
                });
            },

            baixarParcela(parcela) {
                const self = this;
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: "POST",
                    url: '/contas/parcelas/baixar',
                    data: parcela,
                    dataType: "json",
                    complete: function (response) {
                        var data = response.responseJSON;
                        if (data.erro == 0) {
                            self.parcelas.splice(self.parcelas.indexOf(parcela), 1);
                            $('#modal-success-sem-reload .text').text(data.msg)
                            $('#modal-success-sem-reload').modal()
                        } else {
                            $('#modal-danger .text').text(data.msg)
                            $('#modal-danger').modal()
                        }
                        self.$emit('atualizaConta')
                    }
                });
            }
        },

        mounted() {
            const self = this;

            if (typeof self.conta != 'undefined') {
                self.$set(self, 'parcelas', self.conta.parcelas);
            }


        }
    }
</script>

<template>
    <div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Parcelas</h3>
            </div>

            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12">
                            <table v-if="modo == 'pagar'" id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                                <thead>
                                <tr>
                                    <th width="5%">Número parcela</th>
                                    <th width="20%">Data vencimento</th>
                                    <th width="20%" class="text-center">Valor (R$)</th>
                                    <th width="20%" class="text-center">Desconto da parcela</th>
                                    <th width="8%" class="text-center">Baixar parcela</th>
                                </tr>
                                </thead>
                                <tbody v-for="(parcela, index) in parcelas">
                                <tr class="contrato">
                                    <td>{{ parcela.nro_parcela }}</td>
                                    <td>{{ parcela.data_vencimento }}</td>
                                    <td class="text-center">{{ parcela.valor }}</td>
                                    <td class="text-center">
                                        <input class="form-control" v-money="parcela.valor_desconto" type="text"
                                               v-model="parcela.valor_desconto"/>
                                    </td>
                                    <td class="text-center">
                                        <a @click="baixarParcela(parcela)" class="btn btn-effect-ripple btn-success"><i
                                                class="fa fa-plus"></i> Baixar
                                        </a>
                                    </td>
                                </tr>
                                <tr v-if="parcelas == undefined || parcelas.length <= 0">
                                    <td colspan="6" class="text-center margim-bottom-10"><h5 style="font-weight: normal">Nenhum registro encontrado</h5></td>
                                </tr>
                                </tbody>
                            </table>

                            <table v-if="modo == 'estornar'" id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                                <thead>
                                <tr>
                                    <th width="5%">Número parcela</th>
                                    <th width="20%">Data vencimento</th>
                                    <th width="20%">Data da baixa</th>
                                    <th width="20%" class="text-center">Valor (R$)</th>
                                    <th width="10%" class="text-center">Valor desconto(R$)</th>
                                    <th width="10%" class="text-center">Valor pago(R$)</th>
                                    <th width="8%" class="text-center">Estornar parcela</th>
                                </tr>
                                <tr v-if="parcelas == undefined || parcelas.length <= 0">
                                    <td colspan="6" class="text-center margim-bottom-10"><h5 style="font-weight: normal">Nenhum registro encontrado</h5></td>
                                </tr>
                                </thead>

                                <tbody v-for="(parcela, index) in parcelas">
                                <tr class="contrato">
                                    <td>{{ parcela.nro_parcela }}</td>
                                    <td>{{ parcela.data_vencimento }}</td>
                                    <td class="text-center">{{ parcela.data_recebimento }}</td>
                                    <td class="text-center">{{ parcela.valor }}</td>
                                    <td class="text-center">{{ parcela.valor_desconto }}</td>
                                    <td class="text-center">{{ parcela.valor_pago }}</td>
                                    <td class="text-center">
                                        <a @click="estornar(parcela)" class="btn btn-effect-ripple btn-success"><i
                                                class="fa fa-plus"></i> Estornar
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>