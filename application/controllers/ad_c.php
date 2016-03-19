<?php

class Ad_c extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Function saveObject
     *
     * Vytvoření nového objektu (dům, byt) ze zadaných GET parametrů
     *<ul>
     * <li>GET<b> object_address </b>- JSON adresy objektu (google formát adresy) </li>
     * <li>GET<b> object_equipment </b>- JSON vybavení objektu </li>
     * <li>GET<b> object_floor </b>- podlaží objektu (dům, byt) </li>
     * <li>GET<b> object_rooms </b>- JSON počet a typů místností </li>
     *
     * <li>GET<b> object_reconstruction_date </b>- DATUM rekonstrukce bytu </li>
     * <li>GET<b> object_floor_count </b>- počet pater objektu (dům, byt) </li>
     * <li>GET<b> object_pet </b>- je povoleno domácí zvíře </li>
     * <li>GET<b> object_smoker </b>- je v objektu povoleno kouřit </li>
     *</ul>
     * při vytvoření objektu bere metoda v úvahu $_FILES globální proměnnou. Slouží ke vložení fotek k objektu.
     *
     * Metoda vrátí JSON pole vytvořených místností.
     *
     * <h4>Formát JSON výstupu</h4>
     *<ul>
     * <li><b>room_type</b> - ID typu místnosti</li>
     * <li><b>room_number</b> - počet vložených místností tohoto typu (číslováno od 0)</li>
     * <li><b>room_object_id</b> - ID vložené místnosti</li>
     * </ul>
     * <ul>
     * <li><b>object_id</b> - ID vloženého objektu</li>
     * </ul>
     *
     * <h4>Příklad výstupu</h4>
     * {"0":{"room_type":1,"room_number":1,"room_object_id":410},"1":{"room_type":3,"room_number":0,"room_object_id":411},"object_id":412},"object_id":140}
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     *
     */
    public function saveObject()
    {
        $objectPostData = Array(
            'location' => $this->input->get('location', true),
            'realityType' => $this->input->get('realityType', true),
            'adType' => $this->input->get('adTypee', true),
            'creatorType' => $this->input->get('creatorType', true),
            'walkthrough' => $this->input->get('walkthrough', true),
            'squareArea' => $this->input->get('squareArea', true)
        );

        //contains new ObjectID and new AdId
        $idArr = $this->object_model->saveObject($objectPostData);

        mkdir(APPPATH . '../galleries/' . $idArr["objectId"], 0777, TRUE);
        mkdir(APPPATH . '../galleries/' . $idArr["objectId"] . '/_thumbs/', 0777, TRUE);

        echo json_encode($idArr, JSON_UNESCAPED_UNICODE);
    }


    /**
     * Function updateAd
     *
     * Funkce edituje existující kampaň na základě POST parametrů
     *<ul>
     * <li>GET <b> ad_adId </b>- ID kampaně, kterou chceme editovat</li>
     * <li>GET <b> ad_availableFrom </b>- datum, od kdy je kampaň platná</li>
     * <li>GET <b> ad_title </b>- titulek kampaně</li>
     * <li>GET <b> ad_body </b>- popisek kampaně</li>
     * <li>GET <b> ad_price </b>- cena</li>
     *<ul>
     * @author Adam Mrázek
     */
    public function updateAd($adId)
    {
        $adArr = Array();
        if (isset($_POST['peopleCount'])) {
            $adArr["max_people_count"] = $this->input->post("peopleCount");
        }
        if (isset($_POST['roomCount'])) {
            $adArr["room_count"] = $this->input->post("roomCount");
        }
        if (isset($_POST['sex'])) {
            $adArr["sex"] = $this->input->post("sex");
        }
        if (isset($_POST['ageFrom'])) {
            $adArr["age_from"] = $this->input->post("ageFrom");
        }
        if (isset($_POST['ageTo'])) {
            $adArr["age_to"] = $this->input->post("ageTo");
        }
        if (isset($_POST['price'])) {
            $adArr["ad_price"] = $this->input->post("price");
        }
        if (isset($_POST['bailBoolean'])) {
            $adArr["bail_boolean"] = $this->input->post("bailBoolean");
        }
        if (isset($_POST['bail'])) {
            $adArr["bail"] = $this->input->post("bail");
        }
        if (isset($_POST['available_from'])) {
            $adArr["available_from"] = date("Y-m-d", strtotime($this->input->post("available_from")));
        }
        if (isset($_POST['available_to'])) {
            $adArr["available_to"] = date("Y-m-d", strtotime($this->input->post("available_to")));
        }

        $equipmentArr = null;
        if (isset($_POST['equipment'])) {
            $equipmentArr = json_decode($this->input->post("equipment"));
        }
//        echo "<pre>";
//        print_r($adArr);
//        print_r($equipmentArr);
        $this->ad_model->updateAd($adArr, $equipmentArr, $adId);
//        var_dump($adArr);

    }


    /**
     * Function getAdDetail
     *
     * Funkce vrací JSON detail kampaně.
     * <ul>
     * <li><b> ad_id_pk </b> - ID kampaně</li>
     * <li><b> user_id_fk </b> - ID uživatele, který kampaň založil</li>
     * <li><b> object_id_fk </b> - ID objektu, kterému kampaň patří</li>
     * <li><b> ad_title </b> - titulek kampaně</li>
     * <li><b> ad_body </b> - popis kampaně</li>
     * <li><b> available_from </b> - DATUM od kdy je daný objekt k mání</li>
     * <li><b> ad_price </b> - měsíční cena za bydlení</li>
     * <li><b> active </b> - kampaň je aktivní(1) nebo neaktivní(0)</li>
     * <li><b> location_id_fk </b> - ID lokace v databázi</li>
     *</ul>
     * Dále vrací veškeré informace, které patří k objektu, na který je kampaň spuštěna
     * @see
     * @param (int) (adId) ID kampaně
     *
     * @author Adam Mrázek
     */
    public function getAdDetail($adId)
    {
        $adDetail = $this->ad_model->adDetail($adId);
        echo json_encode($adDetail, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getObjectRooms
     *
     * Funkce vrací JSON místnosti, které patří objektu.
     *
     * @param (int) (objectId) ID objektu
     * @author Adam Mrázek
     *
     */
    public function getObjectRooms($objectId)
    {
        $rooms = $this->object_model->getObjectRooms($objectId);
        echo json_encode($rooms, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function toggleActive
     *
     * Funkce přepíná kampaň mezi stavy aktivní(1) a neaktivní (0). Pokud je kampaň aktivní, přepne ji do neaktivního stavu a naopak.
     *
     * @param (int) (adId) ID kampaně
     * @author Adam Mrázek
     */
    public function toggleActive($adId)
    {
        echo $this->ad_model->toggleActive($adId);

    }


    /**
     * Function getAdUrl
     *
     * Funkce vrací URL kampaně.
     *
     * @param (int) (adId) ID kampaně
     * @author Adam Mrázek
     */
    public function getAdUrl($adId)
    {
        //TODO
    }

    /**
     * Function getFollowersList
     *
     * Funkce vrací JSON seznam uživatelů sledujících danou kampaň.
     * <ul>
     * <li><b> user_id_pk </b> - ID uživatele</li>
     * <li><b> firstname </b> - Křestní jméno uživatele</li>
     * <li><b> lastname </b> - Příjmení uživatele</li>
     * </ul>
     *
     * @param (int) (adId) ID kampaně
     *
     * @author Adam Mrázek
     */
    public function getFollowersList($adId)
    {
        $result = $this->ad_model->getFollowersList($adId);
//        echo $this->db->last_query();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getOwner($adId)
    {
        $data["user"] = $this->ad_model->getOwner($adId)[0];
        $this->load->view("components/contact.php", $data);
    }

    public function getGallery($adId)
    {
        echo json_encode($this->ad_model->getGallery($adId), JSON_UNESCAPED_UNICODE);
    }
}