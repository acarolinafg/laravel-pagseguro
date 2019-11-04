<?php

namespace Acarolinafg\PagSeguro\Classes;

use Acarolinafg\PagSeguro\Rules\DocumentRule;

/**
 * Classe Sender (Comprador)
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class Sender
{
  /**
   * Identificador do comprador gerado pelo Java Script do PagSeguro
   * @var string
   */
  private $hash;

  /**
   * Nome completo do comprador
   * @var string
   */
  private $name;

  /**
   * Especifica o e-mail do comprador que está realizando o pagamento
   * @var string
   */
  private $email;

  /**
   * Dados do telefone do comprador
   * @var array
   */
  private $phone = ['areaCode' => '', 'number' => ''];

  /**
   * Dados de documentos do comprador
   * @var array
   */
  private $document = ['type' => '', 'value' => ''];

  /**
   * Retorna a chave do comprador
   * @return string
   */
  public function getHash()
  {
    return $this->hash;
  }

  /**
   * Armazena a chave do comprador
   * @param string $hash
   */
  public function setHash($hash)
  {
    $this->hash = $hash;
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
   * Retorna o email do comprador
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Armazena o email do comprador
   * @param string $email
   */
  public function setEmail($email)
  {
    $this->email = $email;
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
   * Retorna o array do telefone
   * @return array
   */
  public function getPhoneArray()
  {
    return $this->phone;
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
   * Retorna o documento do comprador
   * @var string
   */
  public function getDocument()
  {
    return $this->document['value'];
  }

  /**
   * Retorna o array documento
   * @var array
   */
  public function getDocumentArray()
  {
    return $this->document;
  }

  /**
   * Armazena o documento
   * @param string $document
   */
  public function setDocument($document)
  {
    $value = pagseguro_clear_number($document);
    $this->document['type'] = strlen($value) > 11 ? 'CNPJ' : 'CPF';
    $this->document['value'] = $value;
  }

  /**
   * Retorna o objeto como array
   * @return array
   */
  public function toArray()
  {
    return [
      'hash'     => $this->hash,
      'name'     => $this->name,
      'email'    => $this->email,
      'areaCode' => $this->phone['areaCode'],
      'number'   => $this->phone['number'],
      'type'     => $this->document['type'],
      'value'    => $this->document['value'],
    ];
  }

  /**
   * Regras de validação do comprador
   * @array
   */
  public function rules()
  {
    return [
      'hash'     => 'required',
      'name'     => 'required|max:50',
      'email'    => 'required|email|max:60',
      'areaCode' => 'required|digits:2',
      'number'   => 'required|digits_between:8,9',
      'type'     => 'required',
      'value'    => ['required', new DocumentRule($this->document['value'])],
    ];
  }
}
