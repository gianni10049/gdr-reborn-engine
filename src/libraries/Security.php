<?php

namespace Libraries;

class Security
{

    /**
     * Hashing of the data passed
     * @param string $string
     * @return bool|string
     */
    public function Hash(string $string = null)
    {
        if(isset($string)) 
        {
            return password_hash($string, PASSWORD_BCRYPT);
        }
        else 
        {
            return false;
        }
    }

    /**
     * Verify hashed data
     * @param string $string
     * @param string $hashed
     * @return bool
     */
    public function Verify($hashed, string $string = null): bool
    {
        if(isset($string))
        {
            return password_verify($string, $hashed);
        }
        else 
        {
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

    
    /**
     * @fn getEmail 
     * 
     * Ex.: $sec->getEmail($_POST['email']);
     * 
     * @param  string|null $email input
     * @param  int|null    $min   min chars
     * @param  int|null    $max   max chars
     * @return bool
     */
    public function getEmail(string $email = null, int $min = null, int $max = null): bool
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;

        // domains banned (da scegliere la dir)
        $bannedEmails = json_decode(file_get_contents(__DIR__ . "/domains/domains.json"));

        if(in_array(strtolower(explode('@', $email)[1]), $bannedEmails)) return false;

        if((isset($min)) && (strlen($email) < $min)) return false;

        if((isset($max)) && (strlen($email) > $max)) return false;

        return true;
    }

    /**
     * @fn setPassword 
     * 
     * @param  string|null $password input
     * @param  int|integer $min      min len
     * @param  int|integer $max      max len
     * @return bool
     */
    public function setPassword(string $password = null, int $min = 8, int $max = 16): bool
    {
        // len
        if(strlen($password) < $min || strlen($max) > 16) 
        {
            return false;
        }

        // digit
        if (!preg_match("/\d/", $password)) 
        {
            return false;
        }

        // upper
        if (!preg_match("/[A-Z]/", $password)) 
        {
            return false;
        }

        // lower
        if (!preg_match("/[a-z]/", $password)) 
        {
            return false;
        }

        // special chars
        if (!preg_match("/\W/", $password)) 
        {
            return false;
        }

        // no ws
        if (preg_match("/\s/", $password)) 
        {
            return false;
        }

        return true;
    }

    /**
     * @fn matches 
     * Ex.: $sec->matches($_POST['password'], $_POST['confirm_password']);
     * 
     * @param  string|null $string  input
     * @param  string|null $confstr input
     * @return bool
     */
    public function matches(string $string = null, string $confstr = null): bool
    {
        $string = preg_replace('/\s+/', '', $string);

        if($string == null) return false;

        if($string === $confstr) return true;

        return false;
    }
}