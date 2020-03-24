<?php

namespace App\Classes;

/**
 * Class Router
 *
 * <pre>
 * $router = new Router();
 *
 * $router->get('/', function () {
 *     echo 'Hello world';
 * });
 * $router->get('/user/(<name>:a?)', function ($name) {
 *     echo "Hello $name";
 * });
 * $router->get('/user/(<id>:n!)', function ($id) { });
 *
 * $router->get('/', [new \App\Controllers\MainController(), 'index']);
 *
 * $router->run();
 *
 * if ($router->error()) {
 *      echo $router->error();
 * };
 *
 * </pre>
 *
 *
 * <pre>
 * Examples: $condition
 * user/(<name>:a?)
 * user/(<name>:a!)
 * user/(<id>:n!)
 * user/(<name>:a!)/(<id>:n!)'
 * page/(:p!)/(:p!)/(:p?)
 * page/(:*!) all valid symbols and separator / to
 * page/(:*!)/(:*!)/(:*!) WRONG !!!
 * </pre>
 *
 *
 * @package App\Classes
 */
class Router
{
    private $port;
    private $protocol;
    private $domain;
    private $basePath;
    private $baseScriptName;
    private $requestUri;
    private $requestMethod;
    private $currentRequest;
    private $currentGetParams;

    private $routerResult = null;
    private $routerError = null;

    private $forceRun = false;
    private $regReplaces = [
        ':n!' => '\d+',
        ':s!' => '[a-zA-Z]+',
        ':a!' => '\w+',
        ':p!' => '[\w\?\&\=\-\%\.\+]+',
        ':*!' => '[\w\?\&\=\-\%\.\+\/]+',
        ':n?' => '\d{0,}',
        ':s?' => '[a-zA-Z]{0,}',
        ':a?' => '\w{0,}',
        ':p?' => '[\w\?\&\=\-\%\.\+\{\}]{0,}',
        ':*?' => '[\w\?\&\=\-\%\.\+\{\}\/]{0,}',
        '/' => '\/',
        '<' => '?<',
        ').'=> ')\.',
    ];

    /**
     * Can accept params
     * <pre>
     * [
     *      // Example: 'mysite.com'
     *      'domain'=> '',
     *      // Example: "/" "/some/"
     *      'base_path'=> '',
     *      'request_uri'=>'string',
     *      'request_method'=>'string',
     *      'base_script_name'=>'string'
     * ];
     * </pre>
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->domain = isset($params['domain'])
            ? $params['domain']
            : $_SERVER['HTTP_HOST'];

        $this->basePath = isset($params['path'])
            ? trim($params['path'],'/') . '/'
            : '/';

        $this->port = $_SERVER['SERVER_PORT'];

        $this->protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on"
            ? 'https'
            : 'http';

        $this->requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->baseScriptName = pathinfo($_SERVER['SCRIPT_FILENAME'])['basename'];
        $this->requestUri = urldecode($_SERVER['REQUEST_URI']);

        $this->determineRequestParams();
    }

    /**
     * Supported request methods
     *
     * @param string|array  $condition      uri path rules
     * @param callable      $callback       callback function
     * @param array         $callbackParams callback function parameters
     */
    public function post($condition,$callback,array $callbackParams=[]){
        $this->map('POST',$condition,$callback,$callbackParams);
    }
    public function get($condition, $callback, array $callbackParams = []){
        $this->map('GET', $condition, $callback, $callbackParams);
    }
    public function put($condition,$callback,array $callbackParams=[]){
        $this->map('PUT',$condition,$callback,$callbackParams);
    }
    public function delete($condition,$callback,array $callbackParams=[]){
        $this->map('DELETE',$condition,$callback,$callbackParams);
    }
    public function options($condition,$callback,array $callbackParams=[]){
        $this->map('OPTIONS',$condition,$callback,$callbackParams);
    }
    public function xhr($condition,$callback,array $callbackParams=[]){
        $this->map('XHR',$condition,$callback,$callbackParams);
    }

    /**
     * @param string        $method
     * @param string|array  $condition
     * @param callable      $callback
     * @param array         $addedCallbackParams
     */
    public function map($method, $condition, $callback, array $addedCallbackParams = [])
    {
        if(strpos($method, '|')) {
            $methods = explode('|', $method);
            foreach ($methods as $mth) {
                $this->map(strtoupper(trim($mth)), $condition, $callback, $addedCallbackParams);
            }
        } else {
            if(is_array($condition)){
                foreach ($condition as $one) {
                    $this->runProcessing(strtoupper($method), $one, $callback, $addedCallbackParams);
                }
            }else{
                $this->runProcessing(strtoupper($method), $condition, $callback, $addedCallbackParams);
            }
        }
    }
    /**
     * @param string        $method
     * @param string|array  $condition
     * @param callable      $callback
     * @param array         $addedCallbackParams
     */
    private function runProcessing($method, $condition, $callback, $addedCallbackParams)
    {
        if(!empty($this->routerResult))
            return;

        if($this->requestMethod == $method || ($method == 'XHR' && $this->isXMLHTTPRequest()) ) {
            $callableParams = $this->conditionMatch($condition);

            if($callableParams) {
                $callbackParams = array_merge($addedCallbackParams, $callableParams['numberParams']);

                $this->routerResult = [
                    'method'    => $method,
                    'callback'  => $callback,
                    'params'    => $callbackParams,
                    'paramsGet' => $this->currentGetParams,
                ];

                if($this->forceRun) {
                    if(is_callable($callback) || is_array($callback) || is_string($callback)) {
                        call_user_func_array($callback, (array) $callbackParams);
                    } else {
                        $this->routerError = [
                            'target' => __FILE__ ."::".__LINE__,
                            'message' => 'runProcessing() in force run not find callable router ' . print_r($callback, true),
                        ];
                    }
                }
            }
        }
    }

    /**
     * <pre>
     * Examples: $condition
     * user/(<name>:a?)
     * user/(<name>:a!)
     * user/(<id>:n!)
     * user/(<name>:a!)/(<id>:n!)'
     * page/(:p!)/(:p!)/(:p?)
     * page/(:*!) all valid symbols and separator / to
     * page/(:*!)/(:*!)/(:*!) WRONG !!!
     * </pre>
     * @param $condition
     * @return array|bool 'namedParams'=> 'numberParams'=>
     */
    private function conditionMatch($condition)
    {
        $hewLimiter = true;
        if(strpos($condition,':*') !== false)
            $hewLimiter = false;

        $parts = explode('/', trim($condition,'/'));
        $toMap = '';
        foreach ($parts as $part) {
            $position = strpos($part, ":");
            if(strpos($part, "<") !== false || $position !== false){
                $part = (substr($part, $position + 2, 1) == '?') ? "?($part)" : "($part)";

            }
            $toMap .= '/'.$part;
        }

        $toMap = strtr($toMap, $this->regReplaces);

        if(preg_match("|^{$toMap}$|i", $this->currentRequest, $result)){
            $namedParams = [];
            $numberParams = [];
            if(count($result)>1){
                array_shift($result);
                if($hewLimiter) {
                    foreach ($result as $resultKey=>$resultVal) {
                        if(is_string($resultKey))
                            $namedParams[$resultKey] = $resultVal;
                        else
                            $numberParams[] = $resultVal;
                    }
                }else{
                    $numberParams = explode('/',$result[0]);
                }
            }
            return [
                'namedParams' => $namedParams,
                'numberParams'=> $numberParams
            ];
        }

        return false;
    }

    private function determineRequestParams()
    {
        $case = $this->requestUri;
        $params = null;
        if(strtolower($this->requestMethod) === 'GET'){
            if(!empty($_GET)){
                $get = explode('?', $case);
                if(count($get) > 1) {
                    $case = $get[0];
                    parse_str($get[1], $params);
                } else
                    $case = is_array($get) ? join('/',$get) : $get ;
            }else{
                $params = null;
            }
        } else {

            parse_str(file_get_contents('php://input'), $params);
        }

        $this->currentRequest = '/' . trim($case,'/');
        $this->currentGetParams = $params;
    }

    /**
     * Return current request port
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Return current request protocol, http or https
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Return and or create base relative url
     * @param string $link
     * @return string
     */
    public function getUrl($link = '')
    {
        return $this->basePath . $link;
    }

    /**
     * Return and or create base absolute url
     * @param string $link
     * @return string
     */
    public function getFullUrl($link = '')
    {
        return $this->protocol.'://'.$this->domain . $this->basePath . $link;
    }

    /**
     * Return current domain name
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Returns an array, or null if no one rule does not fit
     *
     * @return array|null
     */
    public function getResult()
    {
        return $this->routerResult;
    }

    /**
     * Return router errors for current request
     * @return array|null
     */
    public function getError() {
        return $this->routerError;
    }

    /**
     * @return string
     */
    public function getCurrentUri()
    {
        return '/' . trim($this->requestUri, '/') ;
    }

    public function getParams($name = null)
    {
        $params = array_merge(
            $this->routerResult['paramsGet'],
            $this->routerResult['params']
        );

        if ($name !== null) {
            return isset($params[$name]) ? $params[$name] : null;
        } else {
            return $params;
        }
    }

    /**
     * Executed $callback with $callbackParams, when no one rule does not fit
     *
     * @param callable $callback
     * @param array $callbackParams
     */
    public function notFount(callable $callback, array $callbackParams = [])
    {
        if(!$this->getResult() && !$this->getError()){
            if(is_callable($callback)) {
                call_user_func_array($callback, (array) $callbackParams);

            } else {
                $this->routerError = [
                    'target' => __FILE__ ."::".__LINE__,
                    'message' => 'Error is no a callable $callback',
                ];

            }
        }
    }

    /**
     * Executed immediately when finding matching, and skip the other rules
     * @param bool|true $force.
     */
    public function forceRun($force=true) {
        $this->forceRun = (bool) $force;
    }

    /**
     * Start implementation of the rules of the first found, after checking all the rules
     */
    public function run()
    {
        $result = $this->routerResult;

        if ($result && is_callable($result['callback'])) {
            $callback = $result['callback'];
            $params = (array) $result['params'];

            call_user_func_array( $callback, $params);
        }
    }

    public function isXMLHTTPRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || isset($_GET['ajax']);
    }

}
