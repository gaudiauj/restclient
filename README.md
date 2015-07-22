# restclient
REST Full Client for Codeigniter 3

## Requirements

- PHP 5.4.x (Composer requirement)
- CodeIgniter 3.0.x

## Installation
### Step 1 Installation by Composer
#### Run composer
```shell
composer require maltyxx/restclient
```

### Step 2 Examples
Cotroller file is located in `/application/controllers/Client.php`.
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller
{
    public function index()
    {
        $this->load
            ->add_package_path(APPPATH.'third_party/restclient')
            ->library('restclient')
            ->remove_package_path(APPPATH.'third_party/restclient');

        $this->load->helper('url');

        $values = $this->restclient->post(site_url('server'), ['id' => 2]);
    }
}
```
