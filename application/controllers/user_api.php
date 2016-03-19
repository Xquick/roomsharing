<?php

class User_api extends CI_Controller
{
    var $user = null, $logoutUrl = null, $loginUrl = null;

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Function checkCurrentUser
     *
     *  zkontroluje, zda přihlášený uživatel je ten, kterého předáváme v parametru.
     *
     * @param (int) (userId) ID kontrolovaného uživatele
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function checkCurrentUser($userId)
    {
        echo json_encode($this->user_model->checkCurrentUser($userId), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getUserInfo
     *
     *  vrací JSON informace o uživateli (student, parametry jeho filtru, věk, osobní informace).
     *<ul>
     * <li><b> student </b>- </li>
     * <li><b> user_id_pk </b>- </li>
     * <li><b> "firstname </b>- </li>
     * <li><b> lastname </b>- </li>
     * </ul>
     *
     * @param (int) (userId) ID uživatele
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getUserInfo($userId) //!!!!!!!!!!!!DOIMPLEMENTOVAT !!!!!!!!!!!
    {
        echo json_encode($this->user_model->getUserInfo($userId), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getUserSettings
     *
     *  vrací JSON data nastavení filtru
     *<ul>
     *<li><b> setting_id_pk </b>- ID nastavení</li>
     *<li><b> user_id_fk </b>- ID uživatele</li>
     *<li><b> name </b>- jméno filtrované položky</li>
     *<li><b> type </b>- typ filtrované položky
     * <ul>
     * <li><b>0</b>- int</li>
     * <li><b>1</b>- char</li>
     * </ul>
     * </li>
     *<li><b> value_int </b>- integer hodnota dané filtrované položky </li>
     *<li><b> value_char </b>- character hodnota dané filtrované položky </li>
     * </ul>
     * @param (int) (userId) ID uživatele
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     *
     */
    public function getUserSettings($userId)
    {
        echo json_encode($this->user_model->getUserSettings($userId), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getUserFollowedNum
     *
     *  vrací počet kampaní, které uživatel momentálně sleduje (má ve sledovaných).
     *
     * @param (int) (userId) ID uživatele
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getUserFollowedNum($userId)
    {
        echo json_encode($this->user_model->getUserFollowedNum($userId), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getUserFollowersNum
     *
     *  vrací počet uživatelů, kteří sledují kampaně daného uživatele, uvedeného jako parametr této funkce
     *
     * @param (int) (userId)
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     *
     */
    public function getUserFollowersNum($userId)
    {
        echo json_encode($this->user_model->getUserFollowersNum($userId), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getUserId
     *
     *  vrací ID momentálně přihlášeného uživatele.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     *
     */
    public function getUserId()
    {
        echo json_encode($this->user_model->getUserId(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getActivity
     *
     *  vrací JSON data 10 posledních uživatelských aktivit
     * <ul>
     * <li><b> activity_id_pk </b>- ID aktivity v databázi
     * <li><b> ad_id_fk </b>- ID kampaně ke které se aktivita vztahuje
     * <li><b> user_id_fk </b>- ID uživatele, který aktivitu vytvořil
     * <li><b> activity_type </b>- typ aktivity (1-7)(1-komentář, 2-ohodnocení, 3-začal sledovat, 4-přestal sledovat, 5-vytvořil, 6-aktivoval, 7-deaktivoval, 8-editoval)
     * <li><b> activity_value_char </b>- extra popis k aktivitě
     * <li><b> activity_value_int </b>- extra hodnota k aktivitě
     * <li><b> time_inserted </b>- SATUM vytvoření aktivity
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getActivity()
    {
        echo json_encode($this->user_model->getActivity(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getSimilarUsers
     *
     *  vrací JSON podobné uživatele, na základě uživatelova aktuálního filtru, který má nastavený.
     *
     * <ul>
     * <li><b> user_id_pk </b> - ID uživatele</li>
     * <li><b> firstname </b> - křestní jméno uživatele</li>
     * <li><b> lastname </b> - příjmení uživatele</li>
     * <li><b> email </b> - email uživatele</li>
     * <li><b> student </b> - informace o tom, zda je uživatel student</li>
     * <li><b> setting_id_pk </b> - ID nastavení uživatelova filtru</li>
     * <li><b> name </b> - název sublokality, na základě které mají uživatelé shodnu</li>
     * <li><b> value_char </b> - hodnota sublokality, na základě které mají uživatelé shodu (Praha 6, apod.)</li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getSimilarUsers()
    {
        echo json_encode($this->user_model->getSimilarUsers(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function verifyLastUserConversation
     *
     *  vrací potvrzení, zda uživatel, získávající patří do konverzace
     * možné použití v případě, kdy se uživatel pokouší získat konverzaci a my chceme ověřit, zda na to má nárok
     *
     * @param (int) (conversationId) ID konverzace, ze které chceme dostat zprávy
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function verifyLastUserConversation($conversationId)
    {
        echo json_encode($this->user_model->verifyLastUserConversation($conversationId), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getFollowedAds
     *
     *  vrací JSON data všech kampaní, které uživatel má ve sledovaných
     *
     * <ul>
     * <li><b> ad_id_pk </b> - ID kampaně</li>
     * <li><b> user_id_fk </b> - ID uživatele, který kampaň vytvořil</li>
     * <li><b> object_id_fk </b> - ID objektu, na který je kampaň vytvořena </li>
     * <li><b> ad_title </b> - textovýtitulek kampaně </li>
     * <li><b> ad_body </b> - textový popis kampaně </li>
     * <li><b> ad_price </b> - cena kampaně </li>
     * <li><b> active </b> - aktivní či neaktivní kampaň
     * <ul>
     * <li><b> 0 </b> - neaktivní </li>
     * <li><b> 1 </b> - aktivní </li>
     * </ul>
     * </li>
     * <li><b> rel_type_id_fk </b> - </li>
     * <li><b> location_id_fk </b> - ID lokace objektu, na který je kampaň vytvořena </li>
     * <li><b> floor </b> - patro, ve které se nachází objekt, na který je kampaň umístěna </li>
     * <li><b> floor_area </b> - rozloha objektu </li>
     * <li><b> reconstruction_date</b> - datum rekonstrukce objektu </li>
     * <li><b> heat_type_id_fk </b> - ID typu topení
     * <ul>
     * <li><b> 0 </b> - plynové </li>
     * <li><b> 1 </b> - elektrické </li>
     * </ul>
     * </li>
     * <li><b> construction_type_id_fk </b> - ID typu konstrukce objektu
     * <ul>
     * <li><b> 0 </b> - panelová </li>
     * <li><b> 1 </b> - cihlová </li>
     * </ul>
     * </li>
     * <li><b> floor_count </b> - počet pater objektu </li>
     * <li><b> pet </b> - možnost domácího zvířete v objektu
     * <ul>
     * <li><b> 0 </b> - nesmí být </li>
     * <li><b> 1 </b> - může být </li>
     * </ul>
     * </li>
     * <li><b> smoker </b> - možnost kouření v objektu
     * <ul>
     * <li><b> 0 </b> - nesmí se kouřit </li>
     * <li><b> 1 </b> - může se kouřit </li>
     * </ul>
     * </li>
     * <li><b> date_inserted </b> - datum vložení kampaně</li>
     * <li><b> address </b> - neformátovaná adresa objektu </li>
     * <li><b> country </b> - adresa - země </li>
     * <li><b> locality </b> - adresa lokalita (Praha, Brno) </li>
     * <li><b> neighborhood </b> - adresa - městská čtvrť (Dejvice, Vinohrady) </li>
     * <li><b> postal_code </b> - adresa - PSČ </li>
     * <li><b> postal_town </b> - adresa - městská část (Praha 6, Praha 2) </li>
     * <li><b> route </b> - adresa - ulice </li>
     * <li><b> street_number </b> - adresa - číslo domu </li>
     * <li><b> sublocality </b> - adresa - podoblast (Praha 6, Praha 2) </li>
     * <li><b> administrative_area_level_1 </b> - adresa - kraj </li>
     * <li><b> administrative_area_level_2 </b> - adresa - okres </li>
     * <li><b> premise </b> - adresa - orientační číslo </li>
     * <li><b> establishment </b> - adresa - upřesnění typu budovy </li>
     * <li><b> point_of_interest </b> - adresa - památka </li>
     * <li><b> lat </b> - adresa - zeměpisná šířka </li>
     * <li><b> lng </b> - adresa - zeměpisná délka </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getFollowedAds()
    {
        echo json_encode($this->user_model->getFollowedAds(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getTmpRooms
     *
     *  vrací JSON data místností, které uživatel naposledy nastavil v tvorbě nového objektu a nedokončil tvorbu.
     *<ul>
     * <li><b> tmp_object_rooms_id_pk </b> - ID temp. místnosti uživatele </li>
     * <li><b> user_id_fk </b> - ID uživatele </li>
     * <li><b> room_id_fk </b> - ID typu místnosti </li>
     * <li><b> room_count </b> - počet místností tohoto typu </li>
     * <li><b> room_name </b> - název typu místnosti </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getTmpRooms()
    {
        echo json_encode($this->user_model->getTmpRooms(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getTmpRooms
     *
     *  vrací JSON data vybavení, které uživatel naposledy nastavil v tvorbě nového objektu a nedokončil tvorbu.
     *<ul>
     * <li><b> tmp_object_equipment_id_pk </b> - ID temp. vybavení objektu </li>
     * <li><b> user_id_fk </b> - ID uživatele </li>
     * <li><b> equipment_id_fk </b> - ID typu vybavení objektu </li>
     * <li><b> equipment_name </b> - název typu vybavení </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getTmpEquipment()
    {
        echo json_encode($this->user_model->getTmpEquipment(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getRoomTypes
     *
     *  vrací JSON typy místností
     *
     * <ul>
     * <li><b> room_id_pk </b> - ID typu místnosti</li>
     * <li><b> room_name </b> - název místnosti </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     *
     */
    public function getRoomTypes()
    {
        echo json_encode($this->ad_model->getRoomTypes(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getHeatType
     * <ul>
     * <li><b> room_id_pk </b> - ID typu topení</li>
     * <li><b> room_name </b> - název topení </li>
     * </ul>
     *  vrací JSON typy topení.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getHeatType()
    {
        echo json_encode($this->object_model->getHeatType(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getConstructionType
     * <ul>
     * <li><b> room_id_pk </b> - ID typu konstrukce</li>
     * <li><b> room_name </b> - název konstrukce </li>
     * </ul>
     *  vrací JSON typy konstrukcí objektů.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getConstructionType()
    {
        echo json_encode($this->object_model->getConstructionType(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getEquipment
     * <ul>
     * <li><b> room_id_pk </b> - ID typu vybavení</li>
     * <li><b> room_name </b> - název vybavení </li>
     * </ul>
     *  vrací JSON typy vybavení místností
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getEquipment()
    {
        echo json_encode($this->object_model->getEquipment(), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getFormattedFilter
     *
     *  vrací JSON filtr uživatele pouze s hodnotami
     * <ul>
     * <li><b> address </b>- adresa filtru </li>
     * <li><b> price_from </b>- cena od </li>
     * <li><b> price_to </b>- cena do </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getFormattedFilter()
    {
        echo json_encode($this->user_model->getFormattedFilter(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getFilter
     *
     *  vrací JSON kompletní filtr uživatele
     * <ul>
     * <li><b> address </b>- adresa filtru </li>
     * <li><b>  country </b>- země </li>
     * <li><b> locality </b>- město (Praha, Hradec Králové, apod) </li>
     * <li><b> sublocality </b>- užší lokalita, městská čtvrť (Praha 6, apod) </li>
     * <li><b> postal_code </b>- PSČ </li>
     * <li><b> route </b>- ulice </li>
     * <li><b> price_from </b>- cena od </li>
     * <li><b> price_to </b>- cena do </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getFilter()
    {
        echo json_encode($this->user_model->getFilter(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getObjects
     *
     *  vrací JSON všechny objekty v databázy (byty, domy)
     *
     * <ul>
     * <li><b> object_id_pk </b>- ID objektu </li>
     * <li><b>  location_id_fk </b>- ID lokality </li>
     * <li><b> floor </b>- patro ve kterém se objekt případně nachází </li>
     * <li><b> floor_area </b>- rozloha objektu v metrech čtverečných </li>
     * <li><b> reconstruction_date </b>- datum rekonstrukce </li>
     * <li><b> heat_type_id_fk </b> - ID typu topení
     * <ul>
     * <li><b> 0 </b> - plynové </li>
     * <li><b> 1 </b> - elektrické </li>
     * </ul>
     * </li>
     * <li><b> construction_type_id_fk </b> - ID typu konstrukce objektu
     * <ul>
     * <li><b> 0 </b> - panelová </li>
     * <li><b> 1 </b> - cihlová </li>
     * </ul>
     * </li>
     * <li><b> floor_count </b> - počet pater objektu </li>
     * <li><b> pet </b> - možnost domácího zvířete v objektu
     * <ul>
     * <li><b> 0 </b> - nesmí být </li>
     * <li><b> 1 </b> - může být </li>
     * </ul>
     * </li>
     * <li><b> smoker </b> - možnost kouření v objektu
     * <ul>
     * <li><b> 0 </b> - nesmí se kouřit </li>
     * <li><b> 1 </b> - může se kouřit </li>
     * </ul>
     * </li>
     * <li><b> date_inserted </b>- datum vložení objektu do databáze </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getObjects()
    {
        echo json_encode($this->user_model->getObjects(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getFilteredAds
     *
     *  vrací JSON všechny kampaně, které odpovídají uživatelově filtru
     * <ul>
     * <li><b> ad_id_pk </b> - ID kampaně</li>
     * <li><b> user_id_fk </b> - ID uživatele, který kampaň vytvořil</li>
     * <li><b> object_id_fk </b> - ID objektu, na který je kampaň vytvořena </li>
     * <li><b> ad_title </b> - textovýtitulek kampaně </li>
     * <li><b> ad_body </b> - textový popis kampaně </li>
     * <li><b> ad_price </b> - cena kampaně </li>
     * <li><b> active </b> - aktivní či neaktivní kampaň
     * <ul>
     * <li><b> 0 </b> - neaktivní </li>
     * <li><b> 1 </b> - aktivní </li>
     * </ul>
     * </li>
     * <li><b> rel_type_id_fk </b> - </li>
     * <li><b> location_id_fk </b> - ID lokace objektu, na který je kampaň vytvořena </li>
     * <li><b> floor </b> - patro, ve které se nachází objekt, na který je kampaň umístěna </li>
     * <li><b> floor_area </b> - rozloha objektu </li>
     * <li><b> reconstruction_date</b> - datum rekonstrukce objektu </li>
     * <li><b> heat_type_id_fk </b> - ID typu topení
     * <ul>
     * <li><b> 0 </b> - plynové </li>
     * <li><b> 1 </b> - elektrické </li>
     * </ul>
     * </li>
     * <li><b> construction_type_id_fk </b> - ID typu konstrukce objektu
     * <ul>
     * <li><b> 0 </b> - panelová </li>
     * <li><b> 1 </b> - cihlová </li>
     * </ul>
     * </li>
     * <li><b> floor_count </b> - počet pater objektu </li>
     * <li><b> pet </b> - možnost domácího zvířete v objektu
     * <ul>
     * <li><b> 0 </b> - nesmí být </li>
     * <li><b> 1 </b> - může být </li>
     * </ul>
     * </li>
     * <li><b> smoker </b> - možnost kouření v objektu
     * <ul>
     * <li><b> 0 </b> - nesmí se kouřit </li>
     * <li><b> 1 </b> - může se kouřit </li>
     * </ul>
     * </li>
     * <li><b> date_inserted </b> - datum vložení kampaně</li>
     * <li><b> address </b> - neformátovaná adresa objektu </li>
     * <li><b> country </b> - adresa - země </li>
     * <li><b> locality </b> - adresa lokalita (Praha, Brno) </li>
     * <li><b> neighborhood </b> - adresa - městská čtvrť (Dejvice, Vinohrady) </li>
     * <li><b> postal_code </b> - adresa - PSČ </li>
     * <li><b> postal_town </b> - adresa - městská část (Praha 6, Praha 2) </li>
     * <li><b> route </b> - adresa - ulice </li>
     * <li><b> street_number </b> - adresa - číslo domu </li>
     * <li><b> sublocality </b> - adresa - podoblast (Praha 6, Praha 2) </li>
     * <li><b> administrative_area_level_1 </b> - adresa - kraj </li>
     * <li><b> administrative_area_level_2 </b> - adresa - okres </li>
     * <li><b> premise </b> - adresa - orientační číslo </li>
     * <li><b> establishment </b> - adresa - upřesnění typu budovy </li>
     * <li><b> point_of_interest </b> - adresa - památka </li>
     * <li><b> lat </b> - adresa - zeměpisná šířka </li>
     * <li><b> lng </b> - adresa - zeměpisná délka </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getFilteredAds()
    {
        echo json_encode($this->user_model->getFilteredAds(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getUserObjects
     *
     *  vrací JSON všechny objekty, které patří uživateli.
     * Ke každému objektu vrací seznam kampaní, které na daném objektu jsou.
     * Ke každé kampani vrací počet uživatelů, kteří ji sledují
     *
     * <li><b> object_id_pk </b> - ID objektu</li>
     * <li><b> address </b> - neformátovaná adresa objektu </li>
     * <li><b> country </b> - adresa - země </li>
     * <li><b> locality </b> - adresa lokalita (Praha, Brno) </li>
     * <li><b> neighborhood </b> - adresa - městská čtvrť (Dejvice, Vinohrady) </li>
     * <li><b> postal_code </b> - adresa - PSČ </li>
     * <li><b> postal_town </b> - adresa - městská část (Praha 6, Praha 2) </li>
     * <li><b> route </b> - adresa - ulice </li>
     * <li><b> street_number </b> - adresa - číslo domu </li>
     * <li><b> sublocality </b> - adresa - podoblast (Praha 6, Praha 2) </li>
     * <li><b> administrative_area_level_1 </b> - adresa - kraj </li>
     * <li><b> administrative_area_level_2 </b> - adresa - okres </li>
     * <li><b> premise </b> - adresa - orientační číslo </li>
     * <li><b> establishment </b> - adresa - upřesnění typu budovy </li>
     * <li><b> point_of_interest </b> - adresa - památka </li>
     * <li><b> lat </b> - adresa - zeměpisná šířka </li>
     * <li><b> lng </b> - adresa - zeměpisná délka </li>
     * <li><b> ad </b> - seznam kampaní nad daným objektem</li>
     * <ul>
     *      <li><b> ad_id_pk </b> - id kampaně</li>
     *      <li><b> ad_price </b> - cena kampaně</li>
     *      <li><b> active </b> - aktivní či neaktivní kampaň
     *      <ul>
     *           <li><b> 0 </b> - neaktivní </li>
     *           <li><b> 1 </b> - aktivní </li>
     *      </ul>
     *      <li><b> ad_title </b> - textový titulek kampaně</li>
     *      <li><b> ad_followers </b> - počet lidí, sledujících danou kampaň</li>
     * </ul>
     *
     * </li>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getUserObjects()
    {
        echo json_encode($this->user_model->getUserObjects(), JSON_UNESCAPED_UNICODE);
    }

    public function startSession()
    {
        $email = $this->input->get('email');
        var_dump($this->user_model->startSession($email));
    }

    /**
     * Function setFilter
     *
     * Nastaví nový filtr uživatele na základě přijatých POST parametrů
     * <ul>
     * <li>POST<b> filter_area </b>- adresa </li>
     * <li>POST<b> filter_price_from </b>- cena od </li>
     * <li>POST<b> filter_price_to </b>- cena do </li>
     *</ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function setFilter()
    {
        $area = $this->input->post('filter_area', true);
        $price_from = $this->input->post('filter_price_from', true);
        $price_to = $this->input->post('filter_price_to', true);
        $this->user_model->setFilter($area, $price_from, $price_to);
    }

    /**
     * Function follow
     *
     * Aktuálnímu uživateli umístí danou kampaň do jeho sledovaných kampaní.
     *
     * @param (int) (adId) ID kampaně určené ke sledování
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function follow($adId)
    {
        $this->user_model->follow($adId);
    }

    /**
     * Function unfollow
     *
     * Aktuálnímu uživateli odebere danou kampaň z jeho sledovaných kampaní.
     *
     * @param (int) (adId) ID kampaně určené k odstranění ze sledovaných kampaní
     */
    public function unfollow($adId)
    {
        $this->user_model->unfollow($adId);
    }


    /**
     * Function editObject
     *
     * Editace existujícího objektu (dům, byt) podle zadaných GET parametrů
     *<ul>
     * <li>GET<b> object_equipment </b>- JSON vybavení objektu </li>
     * <li>GET<b> object_floor </b>- podlaží objektu (dům, byt) </li>
     * <li>GET<b> object_rooms </b>- JSON počet a typů místností </li>
     *
     * <li>GET<b> object_reconstruction_date </b>- DATUM rekonstrukce bytu </li>
     * <li>GET<b> object_floor_count </b>- počet pater objektu (dům, byt) </li>
     * <li>GET<b> object_pet </b>- je povoleno domácí zvíře </li>
     * <li>GET<b> object_smoker </b>- je v objektu povoleno kouřit </li>
     *</ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function editObject($objectId)
    {
//        $address = json_decode($this->input->get('object_address'));
        $equipment = json_decode($this->input->get('object_equipment', true));
        $floor = $this->input->get('object_floor', true);
        $rooms = json_decode($this->input->get('object_rooms', true));

        $reconstruction_date = $this->input->get('object_reconstruction_date', true);
        $floor_area = $this->input->get('object_floor_area', true);
        $heat_type = $this->input->get('object_heat_type', true);
        $construction_type = $this->input->get('object_construction_type', true);
        $floor_count = $this->input->get('object_floor_count', true);
        $pet = $this->input->get('object_pet', true);
        $smoker = $this->input->get('object_smoker', true);
        $this->object_model->editObject($objectId, $floor, $floor_area, $reconstruction_date, $heat_type, $construction_type, $floor_count, $pet, $smoker, $rooms, $equipment);
    }


    /**
     * Function saveTmpRooms
     *
     * Uloží dočasné počty a typy místností na základě POST parametru
     * <ul>
     * <li> POST JSON <b>tmpRooms </b>- typy místností a jejich počty </li>
     *</ul>
     * Slouží při práci s formulářem k ukládání uživatelových dočasných dat (místností), před úplným uložením vytvářeného nebo editovaného objektu.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function saveTmpRooms()
    {
        $roomArr = json_decode($this->input->post('tmpRooms', true));
        $this->user_model->saveTmpRooms($roomArr);
    }

    /**
     * Function saveTmpEquipment
     *
     * Uloží vybavení objektu v průběhu vytváření nového objektu na základě POST parametru
     * <ul>
     * <li> POST JSON <b>tmpEquipment </b>- typy místností a jejich počty </li>
     *</ul>
     * Slouží při práci s formulářem k ukládání uživatelových dočasných dat (vybavení), před úplným uložením vytvářeného nebo editovaného objektu.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function saveTmpEquipment()
    {
        $equipmentArr = json_decode($this->input->post('tmpEquipment', true));
        $this->user_model->saveTmpEquipment($equipmentArr);
    }

    /**
     * Function removeTmpRoom
     *
     * Odstraní dočasný počet a typ dané místnosti na základě GET parametru
     * <ul>
     * <li>GET <b> roomId </b>- ID typu místnosti, který se má odstranit z uživatelova dočasného výběru </li>
     *</ul>
     * Slouží při práci s formulářem k editaci uživatelových dočasných dat, před úplným uložením vytvářeného nebo editovaného objektu.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */

    public function removeTmpRoom()
    {
        $roomId = $this->input->get('roomId', true);
        $this->user_model->removeTmpRoom($roomId);
    }


    /**
     * Function clearTmpRoom
     *
     * Odstraní dočasný počet a typ všech místností momentálně přihlášeného uživatele.
     *
     * Slouží při práci s formulářem k editaci uživatelových dočasných dat, před úplným uložením vytvářeného nebo editovaného objektu.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function clearTmpRooms()
    {
        $this->user_model->clearTmpRooms();
    }

    /**
     * Function clearTmpRoom
     *
     * Odstraní dočasné vybavení tvořeného objektu momentálně přihlášeného uživatele.
     *
     * Slouží při práci s formulářem k editaci uživatelových dočasných dat, před úplným uložením vytvářeného nebo editovaného objektu.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function clearTmpEquipment()
    {
        $this->user_model->clearTmpEquipment();
    }


    /**
     * Function removeTmpEquipment
     *
     * Odstraní dočasný počet a typ dané místnosti na základě GET parametru
     * <ul>
     * <li>GET <b> roomId </b>- ID typu místnosti, který se má odstranit z uživatelova dočasného výběru </li>
     *</ul>
     * Slouží při práci s formulářem k editaci uživatelových dočasných dat, před úplným uložením vytvářeného nebo editovaného objektu.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function removeTmpEquipment()
    {
        $equipmentId = $this->input->get('equipmentId', true);
        $this->user_model->removeTmpEquipment($equipmentId);
    }

    /**
     * Function pairUserObject
     *
     * Spáruje uživatele s již existujícím bytem na základě POST parametrů.
     * <ul>
     * <li> POST <b> objectId </b>- ID objektu určeného ke spárování </li>
     * <li> POST <b>lat </b>- latitude dnaného objektu </li>
     * <li> POST <b>lng </b>- longitude dnaného objektu </li>
     * <li> POST <b>inputFloor </b>- patro daného objektu </li>
     *</ul>
     * parametry slouží k ověření, že uživatel skutečně objekt zná a že se nejedná o falešné propojení
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function pairUserObject()
    {
        $objectId = $this->input->post('objectId', true);
        $lat = $this->input->post('lat', true);
        $lng = $this->input->post('lng', true);
        $inputFloor = $this->input->post('inputFloor', true);
        $this->user_model->pairUserObject($objectId, $inputFloor, $lat, $lng);
    }


    /**
     * Function setMessageStatus
     *
     * Nastaví status konverzace na základě GET parametrů
     * <ul>
     * <li>GET <b> conversation_id </b>- ID konverzace, které se má status nastavit </li>
     * <li>GET <b> status </b> - status zprávy (0,1) </li>
     * <li>0 - přečtená </li>
     * <li>1 - nepřečtená </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function setMessageStatus()
    {
        $status = $this->input->get('status', true);
        $conversationId = $this->input->get('conversation_id', true);
        echo $this->user_model->setMessageStatus($conversationId, $status);
    }

    /**
     * Function unlinkObject
     *
     * Aktuálnímu uzivateli smaže propojení s daným objektem (byt, dům).
     *
     * @param (int) (objectId) ID objektu
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function unlinkObject($objectId)
    {
        $this->object_model->unlinkObject($objectId);
    }

    /**
     * Function saveGallery
     *
     * Uloží záznam o nové galerii do databáze na základě GET parametru a vrrátí ID galerie.
     *
     * <ul>
     * <li>GET <b> object_id </b>- ID objektu </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function saveGallery()
    {
        $objectId = $this->input->get('object_id', true);
        echo $this->object_model->saveGallery($objectId);
    }


    /**
     * Function saveRoomPhoto
     *
     * Uloží záznam o nové galerii do databáze na základě GET parametru a vrrátí ID galerie.
     *
     * <ul>
     * <li>GET <b> photo_number </b>- číslo fotky </li>
     * <li>GET <b> object_room_id </b>- ID místnosti připojené k objektu </li>
     * <li>GET <b> gallery_id </b>- ID galerie připojené k objektu </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function saveRoomPhoto()
    {
        $photoNumber = $this->input->get('photo_number', true);
        $objectRoomId = $this->input->get('object_room_id', true);
        $galleryId = $this->input->get('gallery_id', true);
        $this->object_model->saveRoomPhoto($photoNumber, $objectRoomId, $galleryId);
    }

    /**
     * Function setFrontPhoto
     *
     * Nastaví úvodní fotografii galerie na základě GET parametrů.
     *
     * <ul>
     * <li>GET <b> gallery_id </b>- ID galerie připojené k objektu </li>
     * <li>GET <b> photo_id </b>- ID fotografie, kterou chceme mít jako úvodní </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function setFrontPhoto()
    {
        $galleryId = $this->input->get('gallery_id', true);
        $photoId = $this->input->get('photo_id', true);
        $this->object_model->setFrontPhoto($galleryId, $photoId);
    }

    /**
     * Function getObjectGallery
     *
     * Vrátí JSON data o galerii k objektu na základě vsupního parametru
     *
     * <Formát výstupu>
     * <ul>
     * <li><b> gallery_id_pk </b>- ID galerie </li>
     * <li><b> front_photo_id_fk </b>- číslo fotografie, která je zvolená za úvodní ke galerii </li>
     * <li><b> object_id_fk </b>- ID objektu, ke kterému galerie patří </li>
     * <li><b> time_inserted </b>- DATETIME vytvoření galerie </li>
     * </ul>
     *
     * @param (int) (galleryId) ID galerie
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getObjectGallery($galleryId)
    {
        echo json_encode($this->object_model->getObjectGallery($galleryId), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function getRoomPhotos
     *
     * Vrátí JSON seznam fotografií patřících v objektu k dané místnosti uvedené v GET parametru.
     *
     * <ul>
     * <li>GET <b> object_room_id </b>- ID galerie připojené k objektu </li>
     * </ul>
     *
     * <Formát výstupu>
     * <ul>
     * <li><b> photo_id_pk </b>- ID fotografie </li>
     * <li><b> gallery_id_fk </b>- ID galerie </li>
     * <li><b> path </b>- číslo fotografie v adresáři </li>
     * <li><b> object_room_id_fk </b>- ID místnosti </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getRoomPhotos()
    {
        $objectRoomId = $this->input->get('object_room_id', true);
        echo json_encode($this->object_model->getRoomPhotos($objectRoomId), JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function recentUsers
     *
     * Vrátí JSON seznam uživatelů, se kterými si přihlášený uživatel naposledy psal.
     *
     * <Formát výstupu>
     * <ul>
     * <li><b> user_id_fk </b>- ID uživatele, se kterým si přihlášený psal </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function recentUsers()
    {
        echo json_encode($this->user_model->recentUsers(), JSON_UNESCAPED_UNICODE);
    }


    public function likeRoommate()
    {
        if (isset($_POST["roommate"]) && !empty($_POST["roommate"]))
            $roommate = $this->input->post("roommate");

        if (!empty($roommate))
            $this->user_model->likeDislikeRoommates($roommate, 0);
    }

    public function dislikeRoommate()
    {
        if (isset($_POST["roommate"]) && !empty($_POST["roommate"]))
            $roommate = $this->input->post("roommate");

        if (!empty($roommate))
            $this->user_model->likeDislikeRoommates($roommate, -1);
    }

    public function acceptRoommate()
    {
        if (isset($_POST["roommate"]) && !empty($_POST["roommate"]))
            $roommate = $this->input->post("roommate");

        if (!empty($roommate))
            $this->user_model->acceptRejectRoommates($roommate, 1);
    }

    public function rejectRoommate()
    {
        if (isset($_POST["roommate"]) && !empty($_POST["roommate"]))
            $roommate = $this->input->post("roommate");

        if (!empty($roommate))
            $this->user_model->acceptRejectRoommates($roommate, -1);
    }


    public function getSuggestedRoommates()
    {
        echo json_encode($this->user_model->getSuggestedRoommates(), JSON_UNESCAPED_UNICODE);
    }

    public function getSuggestedAds()
    {
        echo json_encode($this->user_model->getSuggestedAds(), JSON_UNESCAPED_UNICODE);
    }

    public function  whoSuggestedAd($adId)
    {
        if (isset($adId)) {
            echo json_encode($this->user_model->whoSuggestedAd($adId), JSON_UNESCAPED_UNICODE);
        } else {
            echo "No parametr ad_id";
        }
    }

    public function removeRoommate()
    {
        if (isset($_POST["roommate"]) && !empty($_POST["roommate"]))
            $roommate = $this->input->post("roommate");

        if (!empty($roommate))
            $this->user_model->removeRoommate($roommate);
    }
}