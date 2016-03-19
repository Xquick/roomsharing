<?php

class Map_c extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Function getLatLng
     *
     * Funkce vrací JSON google formátovanou adresu s extrahovanými parametry lng a lat na základě GET parametru
     *<ul>
     * <li>GET <b> address </b>- adresa, pro kterou funkce vrátí formátovaný tvar s lat a lng</li>
     *</ul>
     *
     * @see https://developers.google.com/maps/documentation/geocoding/
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getLatLng()
    {
        if (isset($_GET["address"])) {
            $address = $this->input->get('address');
            echo $this->map_model->getMap($address);
        } else {
            echo "No address provided";
        }

    }

    /**
     * Function getObjectsInBounds
     *
     * vrací JSON objekty (dům, byt), které se nacházejí v mezích, určených GET parametry
     * <ul>
     * <li>GET <b> northeastLat </b>- severo východní zeměpisná šířka </li>
     * <li>GET <b> northeastLng </b>- severo východní zeměpisná délka </li>
     * <li>GET <b> southwestLat </b>- jiho-západní zeměpisná šířka </li>
     * <li>GET <b> southwestLng </b>- jiho-západní zeměpisná délka </li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getObjectsInBounds()
    {
        $northeastLat = $this->input->get('northeastLat');
        $northeastLng = $this->input->get('northeastLng');
        $southwestLat = $this->input->get('southwestLat');
        $southwestLng = $this->input->get('southwestLng');

        $objects = json_encode($this->map_model->getObjectsInBounds($northeastLat, $northeastLng, $southwestLat, $southwestLng));
        echo $objects;
    }

    public function filterObjectsInBounds()
    {
        $northeastLat = $this->input->get('northeastLat');
        $northeastLng = $this->input->get('northeastLng');
        $southwestLat = $this->input->get('southwestLat');
        $southwestLng = $this->input->get('southwestLng');

        $objects = json_encode($this->map_model->getObjectsInBounds($northeastLat, $northeastLng, $southwestLat, $southwestLng));
        echo $objects;
    }

    /**
     * Function getObjectsInBounds
     *
     * Funkce vrací JSON objekt na místě daném GET parametry
     * <ul>
     * <li>GET <b> lat </b>- severo východní zeměpisná šířka</li>
     * <li>GET <b> lng </b>- jiho-západní zeměpisná délka</li>
     *</ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getObjectOnLocation()
    {
        $lat = $this->input->get('lat');
        $lng = $this->input->get('lng');
        $objects = $this->map_model->getObjectOnLocation($lat, $lng);
        echo json_encode($objects);
    }

    /**
     * Function getAddress
     *
     * Funkce vrací JSON googlemap formátovanou adresu na základě GET parametru
     * <ul>
     *  <li>GET <b> address </b>- adresa, kterou chceme převést na googlemap formát</li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getAddress()
    {
        $address = $this->input->get('address');
        echo json_encode($this->map_model->getAddress($address));
    }

    public function getObjectLocation()
    {
        $objectId = $this->input->get('object_id');
        echo json_encode($this->map_model->getObjectLocation($objectId), JSON_UNESCAPED_UNICODE);
    }

}
