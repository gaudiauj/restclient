<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Librairie REST Full Client 
 * @author Yoann VANITOU
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @link https://github.com/maltyxx/restclient
 */
$config['restclient'] = array(
    'auth' => FALSE,
    'auth_type' => 'basic',
    'auth_username' => '',
    'auth_password' => '',
    'header' => FALSE,
    'cookie' => FALSE,
    'timeout' => 30,
    'result_assoc' => TRUE,
    'cache' => FALSE,
    'tts' => 3600
);

/* End of file restclient.php */
/* Location: ./config/restclient.php */
