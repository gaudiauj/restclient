<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Librairie REST Full Client 
 * @author Yoann VANITOU
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @link https://github.com/maltyxx/restclient
 */
class Restclient
{
    /**
     * Instance de Codeigniter
     * @var object 
     */
    private $CI;

    /**
     * Configuration
     * @var array 
     */
    private $config = array(
        'port' => NULL,
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

    /**
     * Information sur la requête
     * @var array 
     */
    private $info = array();

    /**
     * Code de retour
     * @var integer 
     */
    private $errno;

    /**
     * Erreurs
     * @var string 
     */
    private $error;

    /**
     * Valeur de l'envoi
     * @var array
     */
    private $output_value = array();

    /**
     * En-tête de l'envoi
     * @var array 
     */
    private $output_header = array();

    /**
     * Valeur du retour
     * @var string 
     */
    private $input_value;

    /**
     * En-tête du retour
     * @var string 
     */
    private $input_header;

    /**
     * Constructeur
     * @param array $config
     */
    public function __construct(array $config = array())
    {

        // Initialise la configuration, si elle existe
        $this->initialize($config);

        // Charge l'instance de CodeIgniter
        $this->CI = &get_instance();
    }

    /**
     * Configuration
     * @param array $config
     */
    public function initialize(array $config = array())
    {
        // Si il y a pas de fichier de configuration
        if (empty($config)) {
            return;
        }

        $this->config = array_merge($this->config, (isset($config['restclient'])) ? $config['restclient'] : $config);
    }

    /**
     * Requête GET
     * @param type $url
     * @param array $data
     * @param array $options
     * @return string|boolean
     */
    public function get($url, array $data = array(), array $options = array())
    {
        $url = "$url?".http_build_query($data);
        return $this->_query('get', $url, $data, $options);
    }

    /**
     * Requête POST
     * @param type $url
     * @param array $data
     * @param array $options
     * @return string|boolean
     */
    public function post($url, array $data = array(), array $options = array())
    {
        return $this->_query('post', $url, $data, $options);
    }

    /**
     * Requête PUT
     * @param type $url
     * @param array $data
     * @param array $options
     * @return string|boolean
     */
    public function put($url, array $data = array(), array $options = array())
    {
        return $this->_query('put', $url, $data, $options);
    }

    /**
     * Requête DELETE
     * @param type $url
     * @param array $data
     * @param array $options
     * @return string|boolean
     */
    public function delete($url, array $data = array(), array $options = array())
    {
        return $this->_query('delete', $url, $data, $options);
    }

    /**
     * Récupère les cookies
     * @return array
     */
    public function get_cookie()
    {
        $cookies = array();

        // Recherche dans les en-têtes les cookies
        preg_match_all('/Set-Cookie: (.*?);/is', $this->input_header, $data, PREG_PATTERN_ORDER);

        // Si il y a des cookies
        if (isset($data[1])) {
            foreach ($data[1] as $i => $cookie) {
                if (!empty($cookie)) {
                    list($key, $value) = explode('=', $cookie);
                    $cookies[$key] = $value;
                }
            }
        }

        return $cookies;
    }

    /**
     * Mode debug
     * @param  boolean $return retournera l'information plutôt que de l'afficher
     * @return string le code HTML
     */
    public function debug($return = FALSE)
    {
        $input = "=============================================<br/>".PHP_EOL;
        $input .= "=============================================<br/>".PHP_EOL;
        $input .= "<h1>Debug</h1>".PHP_EOL;
        $input .= "=============================================<br/>".PHP_EOL;
        $input .= "<h2>Envoi</h2>".PHP_EOL;
        $input .= "=============================================<br/>".PHP_EOL;
        $input .= "<h3>En-tete</h3>".PHP_EOL;
        $input .= "<pre>".PHP_EOL;
        $input .= print_r($this->output_header, TRUE);
        $input .= "</pre>".PHP_EOL;
        $input .= "<h3>Valeur</h3>".PHP_EOL;
        $input .= "<pre>".PHP_EOL;
        $input .= print_r($this->output_value, TRUE);
        $input .= "</pre>".PHP_EOL;
        $input .= "<h3>Informations</h3>".PHP_EOL;
        $input .= "</pre>".PHP_EOL;
        $input .= print_r($this->info, TRUE);
        $input .= "</pre><br/>".PHP_EOL;
        $input .= "=============================================<br/>".PHP_EOL;
        $input .= "<h2>Response</h2>".PHP_EOL;
        $input .= "=============================================<br/>".PHP_EOL;
        $input .= "<h3>En-tete</h3>".PHP_EOL;
        $input .= "<pre>".PHP_EOL;
        $input .= print_r($this->input_header, TRUE);
        $input .= "</pre>".PHP_EOL;
        $input .= "<h3>Valeur</h3>".PHP_EOL;
        $input .= "<pre>".PHP_EOL;
        $input .= print_r($this->input_value, TRUE);
        $input .= "</pre>".PHP_EOL;
        $input .= "=============================================<br/>".PHP_EOL;

        // Si il y a des erreurs
        if (!empty($this->error)) {
            $input .= "<h3>Errors</h3>".PHP_EOL;
            $input .= "<strong>Code:</strong> ".$this->errno."<br/>".PHP_EOL;
            $input .= "<strong>Message:</strong> ".$this->error."<br/>".PHP_EOL;
            $input .= "=============================================<br/>".PHP_EOL;
        }

        // Type de sortie
        if ($return) {
            return $input;
        } else {
            echo $input;
        }
    }

    /**
     * Envoi la requête
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $options
     * @return string|boolean
     */
    private function _query($method, $url, array $data, array $options = array())
    {
        // Initialise la configuration, si elle existe
        $this->initialize($options);

        // Initialisation
        $this->output_header = array();
        $this->output_value = array();
        $this->input_header = '';
        $this->input_value = '';

        // Si le cache est activé
        if ($this->config['cache']) {
            // Parse l'URL
            $url_indo = parse_url($url);

            // Définition de l'api
            $api = 'rest'.str_replace('/', '_', $url_indo['path']);

            // Définition de la clé
            $cache_key = (isset($url_indo['query'])) ? "{$api}_".md5($url_indo['query']) : "{$api}";

            // Si la méthode est de type GET
            if ($method == 'get') {
                // Si il existe une clé
                if ($json = $this->CI->cache->get($cache_key)) {
                    return $json;
                }

                // Si la méthode n'est pas de type GET
            } else {
                // Si l'arbre de clés existe
                if ($keys = $this->CI->cache->get($api)) {
                    if (is_array($keys)) {
                        // Parcours les clés pour les supprimer
                        foreach ($keys as $key) {
                            $this->CI->cache->delete($key);
                        }
                    }

                    // Supprime l'arbre de clés
                    $this->CI->cache->delete($api);
                }
            }
        }

        // Création d'une nouvelle ressource cURL
        $curl = curl_init();

        // Configuration de l'URL et d'autres options
        curl_setopt($curl, CURLOPT_URL, $url);

        // Si le port est spécifié
        if (!empty($this->config['port'])) {
            curl_setopt($curl, CURLOPT_PORT, $this->config['port']);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->config['timeout']);
        curl_setopt($curl, CURLOPT_FAILONERROR, FALSE);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, array($this, '_headers'));
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);

        // Si il y a une authentification
        if ($this->config['auth']) {
            switch ($this->config['auth_type']) {
                // Authentification http basic
                case 'basic':
                    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($curl, CURLOPT_USERPWD, "{$this->config['auth_username']}:{$this->config['auth_password']}");
            }
        }

        // Si il y a des headers
        if (!empty($this->config['header']) && is_array($this->config['header'])) {
            // Ajoute les en-têtes
            foreach ($this->config['header'] as $key => $value) {
                $this->output_header[] = "$key: $value";
            }
        }

        // Référence du data
        $this->output_value = & $data;

        // Encodage des datas
        switch ($method) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, TRUE);

                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'put':
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                }
                break;
            case 'delete':
                if (!empty($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                }
                break;
            case 'get':
            default:
        }

        // Définition des headers d'envoi
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->output_header);

        // Si y il a un cookie
        if (!empty($this->config['cookie']) && is_array($this->config['cookie'])) {
            $cookies = array();

            foreach ($this->config['cookie'] as $key => $value) {
                $cookies[] = "$key=$value";
            }

            curl_setopt($curl, CURLOPT_COOKIE, implode(";", $cookies));
        }

        // Récupération de l'URL et affichage sur le naviguateur
        $result = curl_exec($curl);

        // Information sur la requete
        $this->info = curl_getinfo($curl);

        // Gestion des erreurs
        if ($result === FALSE) {
            $this->errno = curl_errno($curl);
            $this->error = curl_error($curl);
            return FALSE;
        }

        // Fermeture de la session cURL
        curl_close($curl);

        // Converti les données en JSON
        $json = json_decode($result, $this->config['result_assoc']);

        // Référence de la réponse
        $this->input_value = & $result;

        // Si le cahche est activé et que la méthode est de type GET 
        if ($this->config['cache'] && $method == 'get') {
            // Si la clé existe dans le noeud
            if (!$keys = $this->CI->cache->get($api) OR ! isset($keys[$cache_key])) {
                // Récupère les clés existantes
                $keys = (is_array($keys)) ? $keys : array();

                // Enregistre la clé
                $keys[$cache_key] = $cache_key;

                // Sauvegarde les clés
                $this->CI->cache->save($api, $keys, $this->config['tts']);
            }

            // Sauvegarde les données
            $this->CI->cache->save($cache_key, $json, $this->config['tts']);
        }

        // Retourne les résultats
        return $json;
    }

    /**
     * Récupère les en-têtes
     * @param resource $curl
     * @param string $data
     * @return integer
     */
    public function _headers($curl, $data)
    {
        if (!empty($data)) {
            $this->input_header .= $data;
        }
        
        return strlen($data);
    }

}

/* End of file Restclient.php */
/* Location: ./libraries/Restclient/Restclient.php */
