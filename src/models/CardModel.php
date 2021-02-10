<?php


namespace Models;

use Controllers\CardController;
use Controllers\SessionController;
use Database\DB;
use Libraries\Security;

/**
 * @class Character
 * @package Models
 * @note Model for character
 */
class Card
{
    /**
     * Init vars PUBLIC STATIC
     * @var CardController $_instance
     */
    public static
        $_instance;

    /**
     * Init vars PRIVATE
     * @var DB $db
     * @var Security $sec
     * @var SessionController $session
     */
    private
        $db,
        $sec,
        $session,
        $character;

    /**
     * @fn __constructor.
     * @note Character constructor.
     * @return void
     */
    private function __construct()
    {
        #Init needed classes
        $this->sec = Security::getInstance();
        $this->session = SessionController::getInstance();
        $this->db = DB::getInstance();
        $this->character = Character::getInstance();
    }

    /**
     * @fn getInstance
     * @note Self Instance
     * @return Card
     */
    public static function getInstance(): Card
    {
        #If self-instance not defined
        if (!(self::$_instance instanceof self)) {
            #define it
            self::$_instance = new self();
        }
        #return defined instance
        return self::$_instance;
    }

    /**** CARD-PARTS ****/

    /**
     * @fn PartTotalLifePoint
     * @note Get total life point of body part
     * @param int $part
     * @return int|null
     */
    public function PartTotalLifePoint(int $part): ?int
    {

        #Parse passed data
        $part = $this->sec->Filter($part, 'Int');

        #Get part max life point
        $result = $this->db->Select('total_hp', 'list_parts', "id='{$part}' LIMIT 1")->Fetch();

        #If result exist return total hp of that part, else die whit error
        return ($result)
            ? $this->sec->Filter($result['total_hp'], 'Int')
            : die('Una delle parti risulta inesistente');
    }

    /**
     * @fn CharacterPartDamage
     * @note Get damage of character part
     * @param int $character
     * @param int $part
     * @return int
     */
    public function CharacterPartDamage(int $character, int $part): int
    {

        #Parse passed data
        $character = $this->sec->Filter($character, 'Int');
        $part = $this->sec->Filter($part, 'Int');

        #If character exist
        if ($this->character->CharacterExistence($character)) {

            # Get damage for selected part
            $damages = $this->db->Sum(
                'characters_parts_damage',
                'damage',
                "character_id = '{$character}' AND part='{$part}' AND ending >= CURDATE()"
            );

            #Return damage value
            return $this->sec->Filter($damages, 'Int');

        } #Else return false
        else {
            return false;
        }
    }

    /**
     * @fn StatusList
     * @note Get damage of character part
     * @return array
     */
    public function StatusList(): array
    {
        #Get status list
        return $this->db->Select('*', 'list_parts_status', '1 ORDER BY max_hp')->FetchArray();
    }

    /**
     * @fn CharacterPartDamageList
     * @note Get list of damages of character single part
     * @param int $character
     * @param int $part
     * @return array|null
     */
    public function CharacterPartDamageList(int $character, int $part): ?array
    {

        #Parse passed data
        $character = $this->sec->Filter($character, 'Int');
        $part = $this->sec->Filter($part, 'Int');

        #If character exist
        if ($this->character->CharacterExistence($character)) {

            #Return list of damages of that part
            return $this->db->Select(
                '*',
                'characters_parts_damage',
                "character_id='{$character}' AND part='{$part}' AND ending >= CURDATE() ORDER BY creation")->FetchArray();
        }
    }

}