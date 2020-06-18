<?php

namespace Controllers;

use Libraries\Security;
use Libraries\Enviroment;

/**
 * @class Template
 * @package Controllers
 * @note Class for create templating of pages
 */
class Template
{
    /**
     * Init vars PUBLIC
     * @var string $folder
     */
    public
        $folder;

    /**
     * Init vars PRIVATE
     * @var string $sec
     */
    private
        $sec,
        $env;

    /**
     * @fn __construct
     * @note Template constructor.
     * @param string $folder
     * @return void
     */
    public function __construct(string $folder = null)
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->env = Enviroment::getInstance();

        #If folder is specified set folder, else set default folder for views
        (!is_null($folder)) ? $this->SetFolder($folder) : $this->folder = $this->sec->Filter(VIEWS, 'String');
    }

    /**
     * @fn SetFolder
     * @note Set new folder for template object
     * @param string $folder
     * @return Template
     */
    public function SetFolder(string $folder): Template
    {
        #Set new folder
        $this->folder = $this->sec->Filter($folder, 'String');

        #Return Template object
        return $this;
    }

    /**
     * @fn Render
     * @note Render view
     * @param string $view
     * @param array $variables
     * @return bool|string
     */
    function Render(string $view, array $variables = [])
    {
        #Find template
        $template = $this->FindTemplate($view);

        #If template exist render template else return false
        echo ($template !== false) ? $this->RenderTemplate($template, $variables) : false;
    }

    /**
     * @fn FindTemplate
     * @param string $path
     * @return string
     */
    function FindTemplate(string $path): string
    {
        #Create path to file
        $file = "{$this->folder}{$path}.php";

        #If file exist return path, else return false
        return (file_exists($file)) ? $file : '';
    }

    /**
     * @fn GetVars
     * @return string
     * @var array $vars
     * @var string $template
     */
    function RenderTemplate(string $template, array $vars): string
    {
        #Start output
        ob_start();

        #Foreach var
        foreach ($vars as $key => $value) {

            #Set values like post var
            $_POST[$key] = $value;
        }

        $_POST['body'] = $template;

        #Load template
        include $template;

        #Return output for echo
        return ob_get_clean();
    }
}