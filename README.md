# restclient
REST Full Client for Codeigniter 2 and Codeigniter 3

## Installation
### Step 1 Installation by Composer
```txt
# composer install maltyxx/restclient
```

### Step 2 Creates files
```txt
/application/libraries/Restclient.php
```
```php
<?php defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/Restclient/Restclient.php');
```

### Step 3 Configuration
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
$this->load->library('Restclient/restclient');
$this->load->helper('url');

$values = $this->restclient->get(site_url('welcome/index'), array(
    'id' => 2
));

$this->restclient->debug();

var_dump($values);
```