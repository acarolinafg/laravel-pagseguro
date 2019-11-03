<?php

namespace Acarolinafg\PagSeguro\Classes;

/**
 * Dados da compra
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
trait Shopping
{
  /**
   * Comprador
   * @var Person
   */
  private $sender;

  /**
   * Itens da compra.
   *
   * @var array
   */
  private $items = [];

  /**
   * Adiciona um item a compra
   */
  public function addItem($id, $description, $amount, $quantity)
  {
    $this->items[] = new Item($id, $description, $amount, $quantity);
  }
}
