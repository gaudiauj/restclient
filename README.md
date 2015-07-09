# restclient
REST Full Client for Codeigniter 3

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

## Examples
/application/controllers/Client.php:
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller
{

    public function index()
    {
        $this->load->add_package_path(APPPATH.'third_party/restclient');
        $this->load->library('restclient');
        $this->load->helper('url');

        $values = $this->restclient->post(site_url('server'), array('id' => 2));
    }

}
```
