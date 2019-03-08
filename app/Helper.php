<?php

/**
 * Função responsável por formatar o valor para o banco de dados
 * @Autor Mauricio
 * @Parâmetos $valor
 * @Retorno o valor formatado
 */
function formatValueForMysql($valor) {

    if (strlen($valor) <= 6) {
        return str_replace(',', '.', $valor);
    }

    return str_replace(',', '.', str_replace('.', '', $valor));
}

/**
 * Função responsável por formatar o valor para exibicao
 * @Autor Mauricio
 * @Parâmetos $valor
 * @Retorno o valor formatado
 */
function formatValueForUser($valor) {
    if (empty($valor)) {
        return '0,00';
    }
    return number_format($valor, 2, ',', '.');
}
