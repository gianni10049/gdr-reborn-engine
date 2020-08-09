<?php

namespace Models;

use Controllers\SessionController,
    Database\DB,
    Libraries\Security;

/**
 * @class Account
 * @package Models
 * @
 */
class Account
{
    /**
     * Init vars PUBLIC STATIC
     * @var Account $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PRIVATE
     * @var array $datas
     * @var DB $db
     * @var Security $sec
     * @var SessionController $session
     */
    private
        $datas,
        $db,
        $sec,
        $session;

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Account
     */
    public static function getInstance(): Account
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
     * @note Account constructor.
     * @return void
     */
    private function __construct()
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->session = SessionController::getInstance();
        $this->db = DB::getInstance();

        #Extract data of the account
        $this->RetrieveData();
    }

    /**
     * @fn __get
     * @note extract account data from $datas var
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        #Filter entered value
        $name = $this->sec->Filter($name, 'String');

        #If data is not extracted
        if (empty($this->datas)) {

            #Extract data
            $this->RetrieveData();
        }

        #If session param exist
        if (isset($this->datas[$name])) {

            #Return session param
            return $this->datas[$name];
        } #Else not exist
        else {

            #Return false
            return false;
        }
    }

    /**
     * @fn RetrieveData
     * @note Extract data of the account and save in object $datas
     * @return void
     */
    public function RetrieveData()
    {
        #If session exist
        if ($this->session->SessionExist()) {

            #Get account id
            $account = $this->session->id;

            #Get pdo object
            $db = $this->db;

            #Select data of the account
            $data = $db->Select("*","account","id='{$account}' LIMIT 1")->Fetch();

            #Save account data
            $this->datas = $data;

            #Save id account
            $this->datas['id'] = $account;
        }
    }

    /**
     * @fn CountById
     * @note Count account row whit passed where
     * @param int $account
     * @return int
     */
    public function ExistenceControl( int $account):int
    {
        #If account exist return true, else return false
        return ( $this->db->Count("account", "id='{$account}' AND active=1") === 1 );
    }

    /**
     * @fn UpdateFingerprint
     * @note Update fingerprint in account row
     * @param int $id
     * @return void
     */
    public function UpdateFingerprint(int $id){

        #Get and filter passed id
        $id= $this->sec->Filter($id,'Int');

        #Generate new fingerprint
        $fingerprint= $this->sec->GenerateFingerprint();

        #Get Fingerprints
        $ip = $this->sec->Filter($fingerprint['ip'],'String');
        $lang = $this->sec->Filter($fingerprint['lang'],'String');

        #Update fingerprint in db
        $this->db->Update('account',"fingerprint_ip='{$ip}',fingerprint_lang='{$lang}'","id='{$id}'");
    }

    /**
     * @fn UpdateLastActive
     * @note Update last_active account in db
     * @param int $id
     * @return void
     */
    public function UpdateLastActive(int $id){

        #Get and filter passed id
        $id= $this->sec->Filter($id,'Int');

        #Update last active
        $this->db->Update('account','last_active=NOW()',"id='{$id}'");
    }

    /**
     * @fn EmailExist
     * @note Control if the email exist
     * @param string $email
     * @return bool
     */
    public function EmailExist(string $email):bool
    {
        #Crypt email for extract data
        $email= $this->sec->Filter($email,'String');

        #Extract all emails
        $accounts= $this->db->Select('id,email','account','1')->FetchArray();

        #Foreach account
        foreach ($accounts as $account){

            #Extract email saved in db, if not is one, extract email, else extract single mail in db
            $dbEmail = $this->sec->Filter($account['email'],'String');

            #If decrypted is equal to given email
            if($this->sec->VerifyHash($email,$dbEmail)){

                #Return false
                return true;
            }

        }

        #If email not exist return true
        return false;
    }

    /**
     * @fn UsernameExist
     * @note Control if username exist
     * @param string $username
     * @return bool
     */
    public function UsernameExist(string $username):bool
    {
        #If not exist return true, else false
        return ($this->db->Count("account", "username='{$username}'") === 0) ? false : true;
    }

    /**
     * @fn NewAccount
     * @note Subscribe a new account previously controlled
     * @param string $user
     * @param string $email
     * @param string $pass
     * @return void
     */
    public function NewAccount(string $user,string $email,string $pass)
    {
        #Convert Username
        $user= $this->sec->Filter($user,'Convert');

        #Hash email and password
        $email= $this->sec->Hash($email);
        $pass= $this->sec->Hash($pass);

        #Create account in db
        $this->db->Insert('account','username,email,password',"'{$user}','{$email}','{$pass}'");
    }

    /**
     * @fn DataMatch
     * @note Control if pass and email is correct for the passed user
     * @param string $user
     * @param string $pass
     * @param string $email
     * @return bool
     */
    public function DataMatch(string $user,string $pass,string $email):bool
    {
        #Filter inserted data
        $user= $this->sec->Filter($user,'Convert');
        $pass = $this->sec->Filter($pass);
        $email = $this->sec->Filter($email);

        #Extract user data
        $data= $this->db->Select('id,email,password','account',"username='{$user}' LIMIT 1")->Fetch();

        #If password and email exist and user exist
        if(!is_null($data['password']) && !is_null($data['email'])) {

            #Filter extracted data
            $dbPass = $this->sec->Filter($data['password']);
            $dbEmail = $this->sec->Filter($data['email']);

            #If password and email are verified, return true, else return false
            return ( $this->sec->VerifyHash($pass, $dbPass) && $this->sec->VerifyHash($email, $dbEmail) );

        } #Else account don't exist
        else{

            #Return false
            return false;
        }
    }

    /**
     * @fn SetPassword
     * @param string $user
     * @param string $pass
     * @return void
     */
    public function SetPassword(string $user, string $pass)
    {
        #Filter username
        $user= $this->sec->Filter($user,'Convert');

        #Hash password
        $pass= $this->sec->Hash($pass);

        #Update password of the account
        $this->db->Update('account',"password='{$pass}'","username='{$user}'");
    }

    /**
     * @fn SetEmail
     * @param string $user
     * @param string $email
     * @return void
     */
    public function SetEmail(string $user, string $email)
    {
        #Filter username
        $user= $this->sec->Filter($user,'Convert');

        #Hash password
        $email= $this->sec->Hash($email);

        #Update password of the account
        $this->db->Update('account',"email='{$email}'","username='{$user}'");
    }

    /**
     * @fn getAllEmails
     * @note get all accounts email
     * @return array
     */
    public function AccountEmails(): array
    {
        #Init empty emails container
        $emails = [];

        #Extract all encrypted emails stored on server
        $data = $this->db->Select("email","account","1")->FetchArray();

        #Foreach encrypted email
        foreach ($data as $email){

            #Decrypt email and add to the emails array
            $emails[] = $this->sec->Decrypt($email);
        }

        #Return container full of decrypted emails
        return $emails;
    }

    /**
     * @fn readByName
     * @note Extract data of the user by his username
     * @param string $username
     * @return mixed
     */
    public function readByName(string $username = null)
    {
        #Return account data
        return $this->db->Select(
            "*","account","username = '{$username}' AND active = 1 LIMIT 1"
        )->Fetch();
    }

    /**
     * @fn readById
     * @note Extract data of the user by his id
     * @param int $id
     * @return mixed
     */
    public function readById(int $id = null)
    {
        #Return account data
        return $this->db->Select(
            "*","account","id = '{$id}' AND active = 1 LIMIT 1"
        )->Fetch();
    }


    /**
     * @fn readById
     * @note Extract data of the user by his email
     * @param string $email
     * @return mixed
     */
    public function readByEmail(string $email = null)
    {

        $accounts= $this->db->Select('id,username,email','account','1')->FetchArray();

        $data = [];

        #Foreach account
        foreach ($accounts as $account) {

            #Extract email saved in db, if not is one, extract email, else extract single mail in db
            $dbEmail = $this->sec->Filter($account['email'], 'String');

            #If decrypted is equal to given email
            if ($this->sec->VerifyHash($email, $dbEmail)) {

                $data = $account;
            }
        }

        #Return account data
        return $data;
    }
}