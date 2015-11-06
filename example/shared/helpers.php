<?php

class Output
{
    public static function init()
    {
        ob_start();
    }
    
    public static function render()
    {
        $contents = ob_get_contents();
        ob_end_clean();
        $template = new View('template', ['content' => $contents]);
        echo $template->render();
    }
}

class View
{
    protected $path;
    public $data;
    
    public function __construct($fileName, $data = [])
    {
        $this->path = dirname(dirname(__FILE__)).'/views/'.$fileName.'.php';
        $this->data = $data;
    }
    
    public function render()
    {
        ob_start();
        extract($this->data);
        include($this->path);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    
    public function __toString()
    {
        return $this->render();
    }
}