<?php

namespace Acarolinafg\PagSeguro\Classes;

/**
 * Inteface com métodos obrigatórios para trasmissão de dados
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
interface DataInterface
{
  /**
   * Retorna o objeto como array
   * @return array
   */
  public function toArray();

  /**
   * Array com as regras de validação
   * @return array
   */
  public function rules();
}
