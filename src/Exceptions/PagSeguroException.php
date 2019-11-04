<?php

namespace Acarolinafg\PagSeguro\Exceptions;

use Exception;

/**
 * Controle de exceções do pacote PagSeguro
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class PagSeguroException extends Exception
{
  /**
   * Mensagens como array
   * @var array
   */
  protected $messages = ['code' => '', 'message' => ''];

  public function __construct($message, $code = 0)
  {
    parent::__construct($message, $code);
    $this->setMessages();
  }

  /**
   * Obter o número de mensagens
   * @return int
   */
  public function any()
  {
    return count($this->messages);
  }

  /**
   * Retorna totdas as messagens
   * @return array
   */
  public function all()
  {
    return $this->messages;
  }

  /**
   * Armazenar a menssagem no vetor de mensagens
   */
  private function setMessages()
  {
    $this->messages = explode("|", $this->message);
  }
}
