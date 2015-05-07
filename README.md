# restclient

# Config (/application/config/restclient.php)

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

# Exemple (/application/controllers/exemple.php)
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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

