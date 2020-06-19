<?php

namespace Libraries;

use Database\DB;
use Libraries\Security;
use Libraries\Enviroment;

/**
 * @class Preprocessor
 * @package Libraries
 * @note Class for manage css preprocessor (csscrush)
 */
class Preprocessor
{


    /**
     * Init vars PUBLIC STATIC
     * @var Preprocessor $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PRIVATE
     * @var Security $sec
     * @var Enviroment $dot
     */
    private
        $sec,
        $env,
        $layout,
        $db;

    /**
     * @fn __construct
     * @note Request constructor.
     * @return Preprocessor
     */
    private function __construct()
    {
        #Init needed classes
        $this->db = DB::getInstance();
        $this->sec = Security::getInstance();
        $this->env = Enviroment::getInstance();
        $this->layout = $this->env->LAYOUT_NAME;

        #Set initial config
        $this->SetInit();

        return $this;
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Preprocessor
     */
    public static function getInstance(): Preprocessor
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) {
            #define it
            self::$_instance = new self();
        }
        #return defined instance
        return self::$_instance;
    }

    /**
     * @fn SetInit
     * @note Set initial config for csscrush compile
     */
    private function SetInit()
    {
        # Set option var name
        $object_name = 'options';

        # Set option var values
        $settings = array(
            'minify' => true,
            'output_dir' => "/public/Layouts/{$this->layout}/css",
            'versioning' => true,
            'vars' => $this->CreateVars()
        );

        # Set csscrush settings
        csscrush_set($object_name, $settings);
    }

    /**
     * @fn CreateVars
     * @note Create vars whit value need for layout
     * @return array
     */
    private function CreateVars():array
    {
        # Take layout options from db
        $options= $this->db->Select('*','layout_options','1')->FetchArray();

        # Start empty array
        $array= [];

        # Foreach options as option
        foreach ($options as $option){

            # Set array value for the array key
            $array[$option['name']] = $option['value'];
        }

        # Return created array
        return $array;
    }

    /**
     * @fn Compile
     * @note Compile css and return link of the compiled file
     * @return string
     */
    public function Compile(): string
    {
        # Return link of the compiled file
        return csscrush_file("/assets/css/compil.scss");
    }

}