<?php

namespace Acarolinafg\PagSeguro\Classes;

/**
 * Classe Item
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class Item implements DataInterface
{
  /**
   * Identificadores dos itens
   * @var string
   */
  private $id;

  /**
   * Descrições dos itens
   * @var string
   */
  private $description;

  /**
   * Valores unitários dos itens
   * @var float
   */
  private $amount;

  /**
   * Quantidades dos itens
   * @var int
   */
  private $quantity;

  /**
   * Obter o identificador
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Armazena o valor do identificador
   * @param string $id
   */
  public function setId($id)
  {
    $this->id = pagseguro_clear_value($id);
  }

  /**
   * Obter a descrição
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Armazena o valor da descrição
   * @param string $description
   */
  public function setDescription($description)
  {
    $this->description = pagseguro_clear_value($description);
  }

  /**
   * Obter o valor unitário do item
   * @return string
   */
  public function getAmount()
  {
    return $this->amount;
  }

  /**
   * Armazena o valor do valor unitário do item
   * @param string $amount
   */
  public function setAmount($amount)
  {
    $this->amount = pagseguro_format_money($amount);
  }

  /**
   * Obter a quantidade
   * @return int
   */
  public function getQuantity()
  {
    return $this->quantity;
  }

  /**
   * Armazena o valor da quantidade
   * @param string $quantity
   */
  public function setQuantity($quantity)
  {
    $this->quantity = pagseguro_clear_number($quantity);
  }

  /**
   * Retorna o objeto como array
   * @return array
   */
  public function toArray()
  {
    return [
      'id'            => $this->id,
      'description'   => $this->description,
      'amount'        => $this->amount,
      'quantity'      => $this->quantity
    ];
  }

  /**
   * Regras de validação do item
   * @array
   */
  public function rules()
  {
    return [
      'id'            => 'required|max:100',
      'description'   => 'required|max:100',
      'amount'        => 'required|numeric|between:0.00,9999999.00',
      'quantity'      => 'required|integer|between:1,999'
    ];
  }
}
