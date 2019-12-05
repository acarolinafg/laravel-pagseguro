<?php

namespace Acarolinafg\PagSeguro\Classes;

use Acarolinafg\PagSeguro\Rules\DocumentRule;

/**
 * Classe CreditCardHolder (Cartão de Crédito e Titular)
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class CreditCardHolder extends CommonData
{

  /**
   * Token gerado pelo PagSeguro
   * @var string
   */
  protected $token;

  /**
   * Nome impresso no cartão
   * @var string
   */
  protected $name;

  /**
   * Dados do telefone do titular do cartão
   * @var array
   */
  protected $phone = ['areaCode' => '', 'number' => ''];

  /**
   * CPF do titular do cartão
   * @var array
   */
  protected $CPF;

  /**
   * Data de nascimento do titular do cartão
   * @var string
   */
  protected $birthDate;

  public function __construct(array $data)
  {
    $this->alias = 'creditCard';
    $this->attributes = ['token', 'name', 'phone', 'CPF', 'birthDate'];
    parent::__construct($data);
  }

  /**
   * Retorna o token
   * @return string
   */
  public function getToken()
  {
    return $this->token;
  }

  /**
   * Armazena a chave do comprador
   * @param string $token
   */
  public function setToken($token)
  {
    $this->token = $token;
  }

  /**
   * Retorna o nome do comprador
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Armazena o nome do comprador
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * Retorna o telefone
   * @var string
   */
  public function getPhone()
  {
    return $this->phone['areaCode'] . $this->phone['number'];
  }

  /**
   * Armazena o telefone
   * @param string $phone
   */
  public function setPhone($phone)
  {
    $value = pagseguro_clear_number($phone);
    $this->phone['areaCode'] = substr($value, 0, 2);
    $this->phone['number'] = substr($value, 2);
  }

  /**
   * Retorna o CPF
   * @var string
   */
  public function getCPF()
  {
    return $this->CPF;
  }

  /**
   * Armazena CPF
   * @param string $CPF
   */
  public function setCPF($CPF)
  {
    $this->CPF = pagseguro_clear_number($CPF);
  }

  public function getBirthDate()
  {
    return $this->birthDate;
  }

  public function setBirthDate($birthDate)
  {
    $this->birthDate = pagseguro_dateBR($birthDate);
  }

  public function rules(): array
  {
    return [
      'token'     => 'required',
      'name'      => 'required|min:1|max:50',
      'phone'     => 'required|digits_between:10,11',
      'CPF'       => ['required', new DocumentRule($this->getCPF())],
      'birthDate' => 'required|date_format:d/m/Y'
    ];
  }

  public function toArray(bool $useAlias = false): array
  {
    if ($useAlias) {
      $array['creditCardToken'] = $this->getToken();
      $array['creditCardHolderName'] = $this->getName();
      $array['creditCardHolderCPF'] = $this->getCPF();
      $array['creditCardHolderBirthDate'] = $this->getBirthDate();
      $array['creditCardHolderAreaCode'] = $this->phone['areaCode'];
      $array['creditCardHolderPhone'] = $this->phone['number'];
    } else {
      $array = parent::toArray();
      $array['phone'] = $this->getPhone();
    }
    return $array;
  }
}
