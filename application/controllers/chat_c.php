<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 6.5.14
 * Time: 1:29
 */
class Chat_c extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Function createConversation
     *
     * funkce vytvoří prázdnou konverzaci mezi aktuálně přihlášeným uživatelem a cílovým uživatelem uvedeným v parametru
     *
     * @param int $targetUser
     */
    public function createConversation($targetUser = null)
    {
        if ($targetUser != null) {
            echo $this->chat_model->createConversation($targetUser);
        }
    }

    public function getUserChat($userId)
    {
        $data["user"] = $this->user_model->getUserInfo($userId)[0];
        $this->load->view("components/contact.php", $data);
    }

    /**
     * Function sendMessage
     *
     * aktuální uživatel odešle zprávu cílovému uživateli na základě GET parametrů.
     * <ul>
     * <li> GET <b> target_user </b>- ID cílového uživatele </li>
     * <li> GET <b> message </b>- zpráva, kterou aktuální uživatel chce odeslat </li>
     *</ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function sendMessage()
    {
        if (isset($_GET['targetUserId']))
            $targetUserId = $this->input->get('targetUserId', true);

        if (isset($_GET['message']))
            $message = $this->input->get('message', true);

        if (isset($targetUserId) && isset($message)) {
            $this->chat_model->sendMessage($targetUserId, $message);
        }
    }

    /**
     * Function lastMessagesForMe
     *
     * funkce vrací nastevený počet JSON posledních konverzací s jejich poslední zprávou
     *
     */
    public function lastMessagesForMe()
    {
        echo json_encode($this->chat_model->lastMessagesForMe(5), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getUserConversations
     *
     *  vrací JSON všech uživatelů, se kterými vedl aktuálně přihlášený uživatel konverzace.
     *
     * <ul>
     * <li><b> user_id_fk </b> - ID uživatele, se kterým je konverzace vedena</li>
     * <li><b> conversation_id_fk    </b> - ID konverzace </li>
     * <li><b> firstname </b> - křestní jméno uživatele, se kterým je konverzace vedena</li>
     * <li><b> lastname </b> - příjmení uživatele, se kterým je konverzace vedena</li>
     * <li><b> email </b> - email uživatele, se kterým je konverzace vedena</li>
     * <li><b> student </b> - informace o tom, zda je uživatel student</li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getUserConversations()
    {
        echo json_encode($this->chat_model->getUserConversations(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function zeroOutMessageCount
     *
     *  nastaví status konverzace
     * přijímá GET parametr "value", který může nabývat dvou hodnot (0, 1)
     *<ul>
     * <li><b> 0 </b> - přečtená</li>
     * <li><b> 1 </b>- nepřečtená</li>
     *</ul>
     * @param (int) (conversationId) ID konverzace, které nastavujeme status
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function zeroOutMessageCount($conversationId)
    {
        echo json_encode($this->chat_model->zeroOutMessageCount($conversationId), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getMessageCount
     *
     *  vrací JSON počet nových zpráv, které uživatel obdržel.
     * Na indexu 0 je celkový počet nových zpráv
     * každý další index je ID uživatele, od kterého přišla zpráva
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getMessageCount()
    {
        echo json_encode($this->chat_model->getMessageCount(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getMessages
     * <ul>
     * <li><b> conversation_reply_id_pk </b> - ID odpovědi konverzace</li>
     * <li><b> reply </b> - text odpovědi konverzace</li>
     * <li><b> user_id_fk </b> - ID uživatele,který zprávu posílal </li>
     * <li><b> ip </b> - IP adresa uživatele, který zprávu odesílal </li>
     * <li><b> conversation_id_fk </b> - ID konverzace, do které zprávy patří (dotazovaná konverzace) </li>
     * <li><b> time </b> - čas odelání zprávy </li>
     * <li><b> status </b> - status zprávy
     * <ul>
     * <li><b> 0 </b> -přečtená </li>
     * <li><b> 1 </b> -nepřečtená </li>
     * </ul>
     * </li>
     * </ul>
     *
     *  vrací JSON výpis zpráv dané konverzace
     *
     * @param (int) (conversationId) ID konverzace, ze které chceme dostat zprávy
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getMessages($conversationId)
    {
        echo json_encode($this->user_model->getMessages($conversationId), JSON_UNESCAPED_UNICODE);
    }

    public function getConversationInfo($conversationId)
    {
        echo json_encode($this->chat_model->getConversationInfo($conversationId));
    }

}