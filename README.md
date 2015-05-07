# restclient
REST Full Client for Codeigniter

## Config
/application/config/restclient.php:
```php
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
```

## Examples
/application/controllers/exemple.php:
```php
$this->load->library('restclient');
$this->load->helper('url');

$values = $this->restclient->get(site_url('welcome/index'), array(
    'id' => 2
));

$this->restclient->debug();

var_dump($values);
```