<?php

namespace Libraries;

use Core\Config;

/**
 * @class Security
 * @package Libraries
 * @note Class for manage security of the website
 */
class Security
{

    /**
     * Init vars PUBLIC STATIC
     * @var Security
     */
    public static
        $_instance;

    /**
     * Init vars PUBLIC
     * @var Config
     */
    public
        $config;

    /**
     * @fn __constructor
     * @note Security constructor.
     */
    public function __construct()
    {
        $this->config = Config::getInstance();
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Security
     */
    public static function getInstance(): Security
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
     * @fn Hash
     * @note Hashing of the data passed
     * @param string $string
     * @return bool|string
     */
    public function Hash(string $string = null)
    {
        return (isset($string)) ? password_hash($string, PASSWORD_BCRYPT) : false;
    }

    /**
     * @fn Verify
     * @note Verify hashed data
     * @param string $string
     * @param string $hashed
     * @return bool
     */
    public function Verify(string $string, string $hashed): bool
    {
        return password_verify($string, $hashed);
    }

    /**
     * @fn Filter
     * @note Filter the data for indicated type. Default if wrong, not existent or not defined type: string.
     * @example $sec->Filter(1,'Int');
     * @param mixed $data
     * @param string $type
     * @return mixed
     */
    public function Filter($data, string $type = 'String')
    {
        #Switch passed type
        switch ($type) {

            #default type string ('test','test1')
            #Type string ('test','test1')
            default:

            case 'String':
                $data = trim(filter_var($data, FILTER_SANITIZE_STRING));
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

            #Validate Input post
            case 'Post':
                $data = filter_input(INPUT_POST, $data, FILTER_SANITIZE_SPECIAL_CHARS);
                break;
        }

        #Return filtered data
        return $data;
    }

    /**
     * @fn HtmlFilter
     * @note HTML filter
     * @param string $string
     * @return string
     */
    public function HtmlFilter(string $string):string
    {
        #Array of not allowed html codes
        $notAllowed = array(
            "#(<script.*?>.*?(<\/script>)?)#is" => "Script non consentiti",
            "#(<iframe.*?\/?>.*?(<\/iframe>)?)#is" => "Frame non consentiti",
            "#(<object.*?>.*?(<\/object>)?)#is" => "Contenuti multimediali non consentiti",
            "#(<embed.*?\/?>.*?(<\/embed>)?)#is" => "Contenuti multimediali non consentiti",
            "#( on[a-zA-Z]+=\s*[\"]\s*.*?\s*[\"])#is" => '',
            "#( on[a-zA-Z]+=\s*[']\s*.*?\s*['])#is" => '',
            "#(javascript:[^\s\"']+)#is" => ""
        );

        #Return filtered html
        return $this->Filter(preg_replace(array_keys($notAllowed), array_values($notAllowed), $string), 'Convert');
    }

    /**
     * @fn NoChace
     * @note Reload Files Cache only when modified.
     * @param string $file
     * @return string
     */
    public function NoChace(string $file):string
    {

        #Filter passed vars
        $file = $this->Filter($file, 'String');

        #Get last update time
        $mtime = filemtime(ROOT . $file);

        #Compose url whit last update time
        $text = $file . '?time=' . $mtime;

        #Return filtered and converted url
        return $this->Filter($text, 'String');
    }

    /**
     * @fn Version
     * @note Reload Files Cache when change version
     * @param string $file
     * @param string $version
     * @return string
     */
    public function Version(string $file, string $version):string
    {
        #Filter passed vars
        $file = $this->Filter($file, 'String');
        $version = $this->Filter($version, 'String');

        #Compose url whit the version
        $text = $file . '?v=' . $version;

        #Return filtered and converted url
        return $this->Filter($text, 'String');
    }

    /**
     * @fn GenerateFingerprint
     * @note Generate fingerprint for session control
     * @return string
     */
    public function GenerateFingerprint():string
    {

        #Get Request instance
        $request = Request::getInstance();

        #Create fingerprint
        $fingerprint = hash_hmac('sha256', $request->getUserAgent(), hash('sha256', $request->getIPAddress(), true));

        #Return fingerprint
        return $this->Filter($fingerprint, 'String');
    }

    /**
     * @fn getEmail
     * @example $sec->getEmail($_POST['email']);
     * @param string|null $email input
     * @param int|null $min min chars
     * @param int|null $max max chars
     * @return bool
     */
    public function getEmail(string $email = null): bool
    {

        $max= $this->config->EmailMax;
        $min= $this->config->EmailMin;

        #If is an email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ return false; }

        #Get list of banned domains
        $bannedEmails = json_decode(file_get_contents(LIBRARIES . "/domains/domains.json"));

        #If the domain is not banned
        if (in_array(strtolower(explode('@', $email)[1]), $bannedEmails)){ return false; }

        #If the mail have the right length
        if (
            (isset($min)) && (strlen($email) < $min) ||
            (isset($max)) && (strlen($email) > $max)
        ){ return false; }

        #Is a valid email
        return true;
    }


    #TODO Controllare i vari filtri regex
    /**
     * @fn setPassword
     * @param string|null $password input
     * @return bool
     */
    public function setPassword(string $password = null): bool
    {

        $min= $this->config->PassMin;
        $max= $this->config->PassMax;

        #If password not have right length
        if (
            (strlen($password) < $min) ||
            (strlen($password) > $max)
        ){
            return false;
        }

        // digit
        if (!preg_match("/\d/", $password)) {
            return false;
        }

        #If not have uppercase values
        if (!preg_match("/[A-Z]/", $password)) {
            return false;
        }

        #If not have lowercase values
        if (!preg_match("/[a-z]/", $password)) {
            return false;
        }

        #If have special chars
        if (!preg_match("/\W/", $password)) {
            return false;
        }

        #If have space
        if (preg_match("/\s/", $password)) {
            return false;
        }

        return true;
    }

    /**
     * @fn matches
     * @example $sec->matches($_POST['password'], $_POST['confirm_password']);
     * @param string|null $string input
     * @param string|null $confstr input
     * @return bool
     */
    public function matches(string $string = null, string $confstr = null): bool
    {
        #Leave spaces from passed data
        $string = preg_replace('/\s+/', '', $string);

        #If string is not null
        if ($string == null){ return false; }

        #If is the same
        return ($string === $confstr) ? true : false;
    }
}