<?php

namespace Models;

use Database\DB;
use Libraries\Security;

/**
 * @class Config
 * @package Models
 * @note Config Model for get data from DB
 */
class Config
{

    /**
     * Init Vars PUBLIC STATIC
     * @var Config $_instance Self-Instance
     */
    public static
        $_instance;

    /**
     * Init vars PRIVATE
     * @var DB $db
     */
    private
        $db,
        $sec,
        $globals;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Config
     */
    public static function getInstance(): Config
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
     * @fn __construct
     * @note Config constructor.
     * @return void
     */
    private function __construct()
    {
        #Init DB and Security instance
        $this->db = DB::getInstance();
        $this->sec = Security::getInstance();

        #If globals not is already extracted
        if (empty($this->globals)) {

            #Extract global configs
            $this->ExtractGlobalsConfig();
        }
    }

    /**
     * @fn ExtractGlobalConfig
     * @note Extract global config vars from db
     * @return void
     */
    private function ExtractGlobalsConfig()
    {

        #Extract globals config vars
        $data = $this->db->Select('*', 'config', 'global=1')->FetchArray();

        #Foreach value
        foreach ($data as $config) {

            #Filter type and name
            $type = $this->sec->Filter($config['type'], 'String');
            $name = $this->sec->Filter($config['name'], 'String');


            #Switch type
            switch ($type) {

                #General usage, filter var in base of type
                default:
                    $default = $this->sec->Filter($config['default'], $type);
                    $custom = $this->sec->Filter($config['value'], $type);
                    break;

                #If is indicated like array type
                case 'Array':

                    #Explode the default and custom value
                    $expDefault = explode(',', $config['default']);
                    $expCustom = explode(',', $config['value']);

                    #Set default array
                    $default = $this->sec->Filter($expDefault, $type);

                    #If custom value set
                    if (empty($expCustom)) {

                        #Set custom array
                        $custom = $this->sec->Filter($expCustom, $type);
                    } #Else the custom value is not set
                    else {

                        #Set custom value to null
                        $custom = NULL;
                    }

                    break;
            }

            #If custom is set, return custom, else, return default
            $value = (!empty($custom) && (!is_null($custom))) ? $custom : $default;

            #Set global value
            $this->globals[$name] = $value;
        }
    }

    /**
     * @fn __get
     * @note Magic Method __get for extract private global config vars
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        #Filter passed name of get function
        $name = $this->sec->Filter($name, 'String');

        #If config var exist in globals config
        if (!is_null($this->globals[$name])) {

            #Return value of the config var
            return $this->globals[$name];
        } #Else the var not exist
        else {

            #Return false
            return false;
        }

    }

}