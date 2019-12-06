<?php

namespace Acarolinafg\PagSeguro\Classes;

/**
 * Classe BillingAddress - Endereço de cobrança
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class BillingAddress extends CommonData
{
  /**
   * Nome da rua do endereço
   * @var string
   */
  protected $street;

  /**
   * Número do endereço
   * @var string
   */
  protected $number;

  /**
   * Bairro do endereço
   * @var string
   */
  protected $district;

  /**
   * Cidade do endereço
   * @var string
   */
  protected $city;

  /**
   * Estado do endereço
   * @var string
   */
  protected $state;

  /**
   * País do endereço
   * @var string
   */
  protected $country = 'BRA';

  /**
   * CEP do endereço
   * @var string
   */
  protected $postalCode;

  /**
   * Complemento do endereço
   * @var string
   */
  protected $complement;

  public function __construct(array $data)
  {
    $this->alias = 'billingAddress';
    $this->attributes = ['street', 'number', 'district', 'city', 'state', 'postalCode', 'complement'];
    parent::__construct($data);
  }

  public function getStreet()
  {
    return $this->street;
  }

  public function setStreet($street)
  {
    $this->street = pagseguro_clear_value($street);
  }

  public function getNumber()
  {
    return $this->number;
  }

  public function setNumber($number)
  {
    $this->number = pagseguro_clear_value($number);
  }

  public function getDistrict()
  {
    return $this->district;
  }

  public function setDistrict($district)
  {
    $this->district = pagseguro_clear_value($district);
  }

  public function getCity()
  {
    return $this->city;
  }

  public function setCity($city)
  {
    $this->city = pagseguro_clear_value($city);
  }

  public function getCountry()
  {
    return $this->country;
  }

  public function getState()
  {
    return $this->state;
  }

  public function setState($state)
  {
    $this->state = pagseguro_clear_value($state);
  }

  public function getPostalCode()
  {
    return $this->postalCode;
  }

  public function setPostalCode($postalCode)
  {
    $this->postalCode = pagseguro_clear_number($postalCode);
  }

  public function getComplement()
  {
    return $this->complement;
  }

  public function setComplement($complement)
  {
    $this->complement = pagseguro_clear_value($complement);
  }

  public function rules(): array
  {
    return [
      'street'     => 'required|max:80',
      'number'     => 'required|max:20',
      'district'   => 'required|max:60',
      'city'       => 'required|min:2|max:60',
      'state'      => 'required|min:2|max:2',
      'postalCode' => 'required|digits:8',
      'complement' => 'required|max:40'
    ];
  }

  public function toArray(bool $useAlias = false): array
  {
    $array = parent::toArray($useAlias);
    if ($useAlias) {
      $array['billingAddressCountry'] = $this->getCountry();
    }
    return $array;
  }
}
