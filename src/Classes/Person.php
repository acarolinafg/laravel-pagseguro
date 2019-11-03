<?php

namespace Acarolinafg\PagSeguro\Classes;

/**
 * Armazena os dados de uma pessoa
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class Person
{
  /**
   * Token gerado pelo javscript (no caso do comprador)
   * @var string
   */
  private $hash;

  /**
   * Nome da pessoa
   * @var string
   */
  private $name;

  /**
   * Endereço de email
   * @var string
   */
  private $email;
}
