# restclient
REST Full Client for Codeigniter 2 and Codeigniter 3

## Installation
### Step 1 Installation by Composer
#### Edit /composer.json
```json
{
    "require": {
        "maltyxx/restclient": "^2.1.0"
    }
}
```
#### Run composer update
```shell
composer update
```

### Step 2 Configuration (Optional)
/application/config/restclient.php:
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
```

## Examples
/application/controllers/Client.php:
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->load->library('restclient');
        $this->load->helper('url');

        $url = site_url('server');
        $data = array('id' => 2);
        $options = array();
        
        $values = $this->restclient->post($url, $data, $options);

        $this->restclient->debug();
    }

}
```
