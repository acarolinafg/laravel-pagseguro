<?php

namespace Acarolinafg\PagSeguro\Classes;

use Acarolinafg\PagSeguro\Rules\DocumentRule;

/**
 * Classe Sender (Comprador)
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class Sender extends CommonData
{

  /**
   * Identificador do comprador gerado pelo Java Script do PagSeguro
   * @var string
   */
  protected $hash;

  /**
   * Nome completo do comprador
   * @var string
   */
  protected $name;

  /**
   * Especifica o e-mail do comprador que está realizando o pagamento
   * @var string
   */
  protected $email;

  /**
   * Dados do telefone do comprador
   * @var array
   */
  protected $phone = ['areaCode' => '', 'number' => ''];

  /**
   * Dados de documentos do comprador
   * @var array
   */
  protected $document = ['type' => '', 'value' => ''];

  public function __construct(array $data)
  {
    $this->alias = 'sender';
    $this->attributes = ['hash', 'name', 'email', 'phone', 'document'];
    parent::__construct($data);
  }

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
   * Armazena o documento
   * @param string $document
   */
  public function setDocument($document)
  {
    $value = pagseguro_clear_number($document);
    $this->document['type'] = strlen($value) > 11 ? 'CNPJ' : 'CPF';
    $this->document['value'] = $value;
  }

  public function rules(): array
  {
    return [
      'hash'      => 'required',
      'name'      => 'required|max:50',
      'email'     => 'required|email|max:60',
      'phone'     => 'required|digits_between:10,11',
      'document'  => ['required', new DocumentRule($this->getDocument())],
    ];
  }

  public function toArray(bool $useAlias = false): array
  {
    $array = parent::toArray($useAlias);

    if ($useAlias) {
      unset($array['senderDocument']);
      $type = $this->document['type'];
      $array["{$this->alias}{$type}"] = $this->getDocument();

      unset($array['senderPhone']);
      $array['senderAreaCode'] = $this->phone['areaCode'];
      $array['senderPhone'] = $this->phone['number'];
    } else {
      $array['document'] = $this->getDocument();
      $array['phone'] = $this->getPhone();
     }
    return $array;
  }
}
