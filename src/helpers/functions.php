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

if (!function_exists('pagseguro_format_money')) {
  /**
   * Validação do campo monetário com virgula ou com ponto e duas casas decimais
   * @param mixed $value
   * @return null|float
   */
  function pagseguro_format_money($value)
  {
    if ($value) {
      if (is_float((float) $value)) {
        $value =  (float) $value;
      } else {
        $value = str_replace('.', '', $value);
        $value = (float) str_replace(',', '.', $value);
      }
      return number_format($value, 2, '.', '');
    }
    return null;
  }
}

if (!function_exists('pagseguro_clear_value')) {
  /**
   * Limpa um valor removendo espaços duplos
   * @param mixed $value
   *  @return null|mixed
   */
  function pagseguro_clear_value($value, $regex = '/\s+/', $replace = ' ')
  {
    return $value == null ? null : utf8_decode(trim(preg_replace($regex, $replace, $value)));
  }
}

if (!function_exists('pagseguro_clear_number')) {
  /**
   * Limpa um valor deixando apenas números
   * @param mixed $value
   * @return null|mixed
   */
  function pagseguro_clear_number($value)
  {
    return pagseguro_clear_value($value, '/\D/', '');
  }
}

if (!function_exists('pagseguro_dateBR')) {

  /**
   * Coloca uma data no padrão DIA/MÊS/ANO
   * @param mixed $value
   * @return null|mixed
   */
  function pagseguro_dateBR($value)
  {
    $value = pagseguro_clear_value($value);
    $array = explode('-', $value);
    if (sizeof($array) == 3) {
      return $array[2] . "/" . $array[1] . "/" . $array[0];
    }
    return $value;
  }
}
