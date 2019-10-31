<?php
if (!function_exists('pagseguro_str_parameters')) {
  /**
   * Transforma o array de parâmentro em uma string para envio da requisição
   * @param array $parameters
   * @return string
   */
  function pagseguro_str_parameters(array $parameters)
  {
    $data = '';
    foreach ($parameters as $key => $value)
      $data .= "{$key}={$value}&";

    return rtrim($data, '&');
  }
}
