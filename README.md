# restclient

# /application/config/restclient.php
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


# /application/controllers/exemple.php
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
