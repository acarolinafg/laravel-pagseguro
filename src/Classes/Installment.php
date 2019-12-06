<?php

namespace Acarolinafg\PagSeguro\Classes;

/**
 * Classe de parcelamento no cartão
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class Installment extends CommonData
{

  /**
   * Quantidade de parcelas
   * @var int
   */
  protected $quantity;

  /**
   * Valor das parcelas obtidas
   * @var float
   */
  protected $value;

  /**
   * Quantidade de parcelas sem juros oferecida
   * @var int
   */
  protected $noInterestInstallmentQuantity;


  public function __construct(array $data)
  {
    $this->alias = 'installment';
    $this->attributes = ['quantity', 'value', 'noInterestInstallmentQuantity'];
    parent::__construct($data);
  }

  /**
   * Armazena a quantidade de parcelas (1 a 18)
   * @param int $quantity
   */
  public function setQuantity($quantity)
  {
    $this->quantity = pagseguro_clear_number($quantity);
  }

  /**
   * Retorna a quantidade de parcelas
   * @return int
   */
  public function getQuantity()
  {
    return $this->quantity;
  }

  /**
   * Armazena o valor das parcelas
   *  @param float $value
   */
  public function setValue($value)
  {
    $this->value = pagseguro_format_money($value);
  }

  /**
   * Retorna o valor da parcela
   * @return float
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * Armazena a quantidade de parcelas sem juros
   * @param int $noInterestInstallmentQuantity
   */
  public function setNoInterestInstallmentQuantity($noInterestInstallmentQuantity)
  {
    $this->noInterestInstallmentQuantity = pagseguro_clear_number($noInterestInstallmentQuantity);
  }

  /**
   * Retorna a quantidade de parcelas sem juros
   * @return int
   */
  public function getNoInterestInstallmentQuantity()
  {
    return $this->noInterestInstallmentQuantity;
  }

  public function rules(): array
  {
    return [
      'quantity'                        => 'required|integer|between:1,18',
      'value'                           => 'required|numeric|between:0.00,9999999.00',
      'noInterestInstallmentQuantity'   => 'nullable|integer|between:1,999'
    ];
  }

  public function toArray(bool $useAlias = false): array
  {
    $array = parent::toArray($useAlias);

    if ($useAlias) {
      unset($array['installmentNoInterestInstallmentQuantity']);
      $array['noInterestInstallmentQuantity'] = $this->getNoInterestInstallmentQuantity();
    }
    return $array;
  }
}
