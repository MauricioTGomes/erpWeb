require('./bootstrap');
require('./select2');
require('./Funcoes');
require('../sass/select2.css');
require('./mascaraCampos');
require('../../../public/layout/bower_components/datatables.net/js/jquery.dataTables');
require('../../../public/layout/bower_components/datatables.net/js/jquery.dataTables.min');

window.Vue = require('vue');

// Vue.component('form-pedido', require('./components/FormPedido.vue'));

import VcFormPedido from './components/FormPedido.vue';
import money from 'v-money';
import VcParcelas from './components/Parcelas.vue';
import VcContas from './components/Contas.vue';

Vue.use(money, {decimal: ',', thousands: '.', prefix: '', suffix: '', precision: 2});

if (location.href.indexOf('pedidos/incluir') > 0 || location.href.indexOf('contas/receber/listar') > 0 ||
    location.href.indexOf('contas/pagar/listar') > 0 || location.href.indexOf('pedidos/alterar') > 0) {
    const app = new Vue({
        el: '#app',
        components: {
            VcFormPedido,
            VcParcelas,
            VcContas
        }
    });
}
