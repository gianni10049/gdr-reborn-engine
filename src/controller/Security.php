<?php

namespace BaseSecurity;

use Connection\DB;
use Core\Config;

class Security
{

    /**
     * @var DB (CLASS)
     * @var Config (CLASS)
     */
    protected $db;
    protected $config;

    /**
     * Security constructor.
     */
    public function __construct()
    {
        $this->db = new DB();
        $this->config = new Config();
    }

    /**
     * Hashing of the data passed
     * @param string $string
     * @return bool|string
     */
    public function Hash($string)
    {
        if (is_string($string)) {
            return password_hash($string, PASSWORD_BCRYPT);
        }
        else{
            return false;
        }
    }

    /**
     * Verify hashed data
     * @param string $string
     * @param string $hashed
     * @return bool
     */
    public function Verify($string, $hashed)
    {
        if (is_string($string)) {
            return password_verify($string, $hashed);
        }
        else{
            return false;
        }
    }

    /**
     * Filter the data for indicated type. Default if wrong, not existent or not defined type: string.
     * @example $sec->Filter('test','String');
     * @example $sec->Filter(1,'Int');
     * @param mixed $data
     * @param string $type
     * @return mixed
     */
    public function Filter($data, $type = 'String')
    {
        switch ($type) {

            #Type string ('test','test1')
            default:
            case 'String':
                $data = filter_var($data, FILTER_SANITIZE_STRING);
                break;

            #Type Int (1,2,3)
            case 'Int':
                $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                break;

            #Type Float (0.1,0.2,0.3)
            case 'Float':
                $data = filter_var($data, FILTER_VALIDATE_FLOAT);
                break;

            #Type Email (test@test.test)
            case 'Email':
                $data = filter_var($data, FILTER_SANITIZE_EMAIL);
                break;

            #Type Bool (false,true)
            case 'Bool':
                $data = filter_var($data, FILTER_VALIDATE_BOOLEAN);
                break;

            #Type Array, values convert in string ['1'=>'test','2'=>'test]
            case 'Array':
                $data = filter_var_array($data, FILTER_SANITIZE_STRING);
                break;

            #Convert single and double quotes in HTML entities
            case 'Quote':
                $data = htmlentities($data, ENT_QUOTES);
                break;

            #Convert single and double quote from HTML entities
            case 'Convert':
                $data = html_entity_decode($data, ENT_QUOTES);
                break;

            #Type url (https://test.com)
            case 'Url':
                $data = filter_var($data, FILTER_SANITIZE_URL);
                break;

            #Make slash on single and double quotes
            case 'Slash':
                $data = addslashes($data);
                break;

            #Convert possible empty query values on NULL db type
            case 'QueryNull':
                $data = (!empty($data)) ? "'{$this->Filter($data,'Slash')}'" : 'NULL';
                break;
        }

        #Return filtered data
        return $data;

    }

    /**
     * HTML filter
     * @param string $string
     * @return string
     */
    public function HtmlFilter($string)
    {
        $notAllowed = array(
            "#(<script.*?>.*?(<\/script>)?)#is" => "Script non consentiti",
            "#(<iframe.*?\/?>.*?(<\/iframe>)?)#is" => "Frame non consentiti",
            "#(<object.*?>.*?(<\/object>)?)#is" => "Contenuti multimediali non consentiti",
            "#(<embed.*?\/?>.*?(<\/embed>)?)#is" => "Contenuti multimediali non consentiti",
            "#( on[a-zA-Z]+=\s*[\"]\s*.*?\s*[\"])#is" => '',
            "#( on[a-zA-Z]+=\s*[']\s*.*?\s*['])#is" => '',
            "#(javascript:[^\s\"']+)#is" => ""
        );

        return $this->Filter(preg_replace(array_keys($notAllowed), array_values($notAllowed), $string), 'Convert');

    }

    /**
     * Reload Files Cache only when modified.
     * @param string $file
     * @return mixed|string
     */
    public function NoChace($file)
    {
        $mtime = filemtime(ROOT . '/' . $file);
        $text = $file . '?time=' . $mtime;

        return $this->Filter($text, 'String');
    }

    /**
     * Reload Files Cache when change version
     * @param string $file
     * @param string $version
     * @return string
     */
    public function Version($file, $version)
    {
        $text = $file . '?v=' . $version;
        return $this->Filter($text, 'String');
    }


}