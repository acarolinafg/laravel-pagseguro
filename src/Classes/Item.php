<?php

namespace Acarolinafg\PagSeguro\Classes;

/**
 * Modelagem dos itens da transação
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class Item
{
  /**
   * Identificador do item
   * @var string
   */
  private $id;

  /**
   * Descrição do item
   * @var string
   */
  private $description;

  /**
   * Valor do item
   * @var mixed
   */
  private $amount;

  /**
   * Quantidade
   * @var mixed
   */
  private $quantity;

  public function __construct($id, $description, $amount, $quantity)
  {
    $this->id = pagseguro_clear_value($id);
    $this->description = pagseguro_clear_value($description);
    $this->amount = pagseguro_format_money($amount);
    $this->quantity = pagseguro_clear_number($quantity);
  }
}
