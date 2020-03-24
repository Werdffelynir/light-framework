<?php

namespace App\Classes;

/**
 * Class Template.
 * new Template( [ 'path' => '', 'template' => '' ] )
 *      ->render( $view [, $data [, $callback]] )
 *      ->variable( $name [, $value] )
 *      ->value( $name [, $value] )
 *      ::value( $name [, $value] )
 *      ->setPosition($position, $view [, $data [, $callback]] )
 *      ::outPosition($position [, $returned] )
 *      ->outTemplate( [$returned] )
 */
class Template
{
    /**
     * Default configuration
     * @var array
     */
    private $config = [
        'path' => 'views',
        'template' => 'layout/template',
    ];

    /**
     * Global template values store
     * @var array
     */
    static private $templateValues = [];

    /**
     * Positions store
     * @var array
     */
    static private $positionsData = [];

    /**
     * One life variables
     * @var array
     */
    private $dataVariables = [];

    /**
     * Template view variables
     * @var array
     */
    private $dataTemplate = [];

    /**
     * Template constructor.
     * @param array|mixed $config
     * @throws TemplateException
     */
    public function __construct(array $config = [])
    {
        $this->config['path'] = isset($config['path'])
            ? $config['path']
            : '/views';

        $this->config['template'] = isset($config['template'])
            ? $config['template']
            : 'layout/template';

        if (!is_dir($this->config['path']) || !is_file($this->realFile($this->config['template']))) {
            throw new TemplateException('Template path or layout not exists: ' . print_r($config,true));
        }
    }

    /**
     * Render partial view, merge variables with (array) $data and pass $data across $callback function
     *
     * @param $view
     * @param array $data
     * @param callable|null $callback
     * @return false|string
     */
    public function render($view, array $data = [], callable $callback = null)
    {
        try {
            if($path = $this->realFile($view)) {
                if($callback) {
                    $callback_result = $callback($data);
                    if(is_array($callback_result))
                        $data = $callback_result;
                }

                $this->dataTemplate = $data;
                ob_start();
                extract((array) $data);
                require $path;
                return ob_get_clean();
            }
        } catch (TemplateException $error) {
            $error->render();
        }

        return null;
    }

    /**
     * Safe method for get view variables
     * @param $name
     * @return mixed|null
     */
    public function data($name)
    {
        return isset($this->dataTemplate[$name])
            ? $this->dataTemplate[$name]
            : null;
    }

    /**
     * Safe method for setter and getter
     * @param $name
     * @param $value
     * @return mixed|null
     */
    public function variable($name, $value = null)
    {
        return $value === null
            ? ( isset($this->dataVariables[$name]) ? $this->dataVariables[$name] : null )
            : $this->dataVariables[$name] = $value;
    }

    public function __set($name, $value)
    {
        $this->dataVariables[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->setStack[$name]))
            return $this->dataVariables[$name];
        return null;
    }

    public function __call($name, array $args)
    {
        if($name === 'value' )
            return call_user_func_array( [$this, 'value'], $args );
        return null;
    }

    /**
     * Set $view to $position (look - outPosition())
     * @param $position
     * @param $view
     * @param array $data
     * @param null $callback
     * @return $this
     */
    public function setPosition($position, $view, array $data = [], $callback = null)
    {
        self::$positionsData[$position] = $this->render($view, $data, $callback);
        return $this;
    }

    /**
     * Output view into place,
     * from position store.  (for assign look - setPosition())
     * @param $position
     * @param bool $returned
     * @return null
     */
    static public function outPosition($position, $returned = false)
    {
        $view = isset(self::$positionsData[$position]) ? self::$positionsData[$position] : null;
        if($returned)
            return $view;
        echo $view;
    }

    /**
     * Set or get global values
     * @param $name
     * @param null $value
     * @return null
     */
    static public function value($name, $value = null)
    {
        return $value === null
            ? (isset(self::$templateValues[$name]) ? self::$templateValues[$name]:null)
            : self::$templateValues[$name] = $value;
    }

    /**
     * Output Template
     * @param bool $returned
     * @return bool|string
     */
    public function outTemplate($returned = false)
    {
        $template = $this->render($this->config['template'], self::$templateValues);

        if($returned)
            return $template;
        else
            echo $template;
    }

    /**
     * Return full file pathname
     * @param $file
     * @return string
     * @throws TemplateException
     */
    private function realFile($file)
    {
        $file = rtrim(str_replace('\\', '/', $this->config['path']),'/') . '/' . trim($file, '/');

        if(substr($file, -4) !== '.php')
            $file .= '.php';

        if (!is_file($file))
            throw new TemplateException('Render file not exist! "' . $file . '"');

        return $file;
    }


}


