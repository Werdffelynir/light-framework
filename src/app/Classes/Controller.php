<?php


namespace App\Classes;


abstract class Controller
{
    /** @type Response */
    public $response;

    /** @type Request */
    public $request;

    /** @type Template */
    public $template;

    public function init() {}

    /**
     * @param $view
     * @param array $data
     */
    public function view ($view, $data = [])
    {
        $position = 'main';
        $this->template
            ->setPosition($position, $view, $data)
            ->outTemplate();
    }

    /**
     * @param array $values
     */
    public function json (array $values)
    {
        echo $this->response->json($values);
    }

    /**
     * @param $name
     * @param null $value
     * @return mixed|null
     */
    public function variable($name, $value = null)
    {
        try {
            if (!$this->template)
                throw new TemplateException('Controller has not yet loaded the template instance.');
        } catch (TemplateException $error) {
            $error->render();
        }

        return $this->template->variable($name, $value);
    }

}
