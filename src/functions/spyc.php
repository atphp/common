<?php
/**
 * @file
 * YAML wrapper functions
 *
 * @todo Support yaml_parse_url()
 * @todo Support yaml_emit_file()
 */
 
 if (!function_exists('yaml_parse_file')) {
  /**
   * Read YAML file.
   *
   * @param  string $path Path to yaml file.
   * @return mixed
   */
  function yaml_parse_file($path) {
    return Spyc::YAMLLoad($path);
  }
}

if (!function_exists('yaml_parse')) {
  /**
   * Parse a YAML stream
   *
   * @todo Support all options in http://www.php.net/manual/en/function.yaml-parse.php
   */
  function yaml_parse($string) {
    return Spyc::YAMLLoadString($string);
  }
}

if (!function_exists('yaml_emit')) {
  /**
   * Returns the YAML representation of a value.
   *
   * @return string Returns a YAML encoded string on success.
   */
  function yaml_emit($data) {
    return Spyc::YAMLDump($data, false, false, true);
  }
}
