<?php

namespace Acarolinafg\PagSeguro\Classes;

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
   * Data de nascimento do comprador no formato dd/mm/yyyy
   * @var string
   */
  private $dateBirth;

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
   * Retorna a data de nascimento no formato dd/mm/yyyy
   * @var string
   */
  public function getDateBirth()
  {
    return $this->dateBirth;
  }

  /**
   * Armazena a data de nascimento
   * @param string $document
   */
  public function setDateBirth($dateBirth)
  {
    $this->dateBirth = pagseguro_dateBR($dateBirth);
  }
}
