<?php

class Map_model extends CI_Model
{
    public function getMap($address)
    {
        $location = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&sensor=false';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $location);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function getObjectsInBounds($northeastLat, $northeastLng, $southwestLat, $southwestLng)
    {
        $this->db->join('objects', 'objects.object_id_pk = ad.object_id_fk');
        $this->db->join('locations', 'locations.location_id_pk = objects.location_id_fk');
        $objects = $this->db->select('lat,lng')->where('locations.lat <', $northeastLat)->
            where('locations.lng <', $northeastLng)->
            where('locations.lat >', $southwestLat)->
            where('locations.lng >', $southwestLng)->
            get('ad')->result();
        return $objects;
    }

    public function getAddress($address)
    {
        $location = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false&language=cz';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $location);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch));
        if (!empty($result->results)) {
            $rawAddress = $result->results[0]->address_components;
            curl_close($ch);

            $address = Array();
            for ($i = 0; $i < sizeOf($rawAddress); $i++) {
                $address[$rawAddress[$i]->types[0]] = $rawAddress[$i]->long_name;
            }
            $address['lat'] = $result->results[0]->geometry->location->lat;
            $address['lng'] = $result->results[0]->geometry->location->lng;

            return $address;
        }
        return false;
    }

    public function getObjectOnLocation($lat, $lng)
    {
        $offset = 0.003;
        $this->db->join('objects', 'objects.location_id_fk = locations.location_id_pk');
        $sql = $this->db->where(array(
            'lat >' => $lat - $offset,
            'lng >' => $lng - $offset,
            'lat <' => $lat + $offset,
            'lng <' => $lng + $offset
        ))->get('locations');
        return $sql->result();
    }

    public function isAddressSpecific($address)
    {
//        $this->
    }

    public function getObjectLocation($objectId)
    {
        $this->db->join('locations', 'locations.location_id_pk = objects.location_id_fk');
        $sql = $this->db->select('lat,lng')->where('object_id_pk', $objectId)->get('objects');
        return $sql->result();
    }

}