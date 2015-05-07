# restclient

# /application/config/restclient.php
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/** Librairie REST Full Client 
 * @author Yoann VANITOU
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version 2.0.0 (20150206)
 */
$config['restclient'] = array(
    'auth' => FALSE,
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
/* Location: ./application/config/restclient.php */



# /application/controllers/exemple.php
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/** Librairie REST Full Client 
 * @author Yoann VANITOU
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version 2.0.0 (20150206)
 */
class Exemple extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->library('restclient');

        $values = $this->restclient->get(site_url('exemple'), array(
            'id' => 2
        ));

        $this->restclient->debug();

        var_dump($values);
    }

}

/* End of file exemple.php */
/* Location: ./application/controllers/exemple.php */

