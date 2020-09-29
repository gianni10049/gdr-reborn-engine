<?php

namespace Libraries;

use Libraries\Security;
use Libraries\Enviroment;
use Database\DB;

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
        $env,
        $db;

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
        $this->db = DB::getInstance();

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
     * @param array $variables
     * @return bool|string
     */
    public function Render( array $variables = [])
    {
        #Find template
        $template = $this->FindTemplate($variables['Page']);

        #If template exist render template else return false
        echo ($template !== false) ? $this->RenderTemplate($template, $variables) : false;
    }

    /**
     * @fn FindTemplate
     * @param string $path
     * @return string
     */
    public function FindTemplate(string $path): string
    {
        #Create path to file
        $file = "{$this->folder}{$path}.php";

        #If file exist return path, else return false
        return (file_exists($file)) ? $file : '';
    }

    /**
     * @fn GetVars
     * @return string
     * @var string $template
     * @var array $vars
     */
    public function RenderTemplate(string $template, array $vars): string
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
        include($template);

        #Return output for echo
        return ob_get_clean();
    }

    public function MenuList($type){

        $type= $this->sec->Filter($type,'String');

        return $this->db->Select('*','menu',"active='1' AND box='{$type}' AND father_id='0'")->FetchArray();
    }

    public function SubMenuList($father){

        $father= $this->sec->Filter($father,'String');

        return $this->db->Select('*','menu',"active='1' AND father_id='{$father}'")->FetchArray();
    }

    public function GetContainerForLinks($type){

        $type = $this->sec->Filter($type,'String');

        switch ($type){
            case 'central':
                $val = 'container_central';
                break;
            case 'body':
                $val = 'container_body';
                break;
            case 'card-complete':
                $val = 'complete';
                break;
            case 'card-internal':
                $val = 'internal';
                break;
        }
;
        return $this->sec->Filter($val,'String');
    }

    function SetParam($array)
    {

        #  $array = ['character' => 2, 'page' => 'prova.php', 'testo' => 'titolo a caso'];

        $input = '{"character":"character_id","page":"page_id","text":"text_id"}'; #Extracted from DB

        $data = (array)json_decode($input);

        foreach ($array as $index => $value) {
            $data[$index] = $value;
        }

        return $data;
    }

    function modifyParam($input){
        # $input = (string)'character,page,text';

        $data = explode(',', $input);

        $array_data = [];

        foreach ($data as $sub_data) {
            $val = $sub_data . '_id';
            $array_data[$sub_data] = $val;
        }

        $res = json_encode($array_data);

        return $res;
    }
}