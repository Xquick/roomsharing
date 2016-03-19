<?php

class Object_model extends CI_model
{
    function getEquipmentTypes()
    {
        $sql = $this->db->query("SELECT * FROM equipment_type ORDER BY equipment_name ASC");
        return $sql->result();
    }

    function getHeatType()
    {
        $sql = $this->db->select('heat_name')->get('heat_type');
        $result = $sql->result();
        $arr = array();
        for ($i = 0; $i < sizeof($result); $i++) {
            $arr[$i] = $result[$i]->heat_name;
        }
        return $arr;
    }

    function getConstructionType()
    {
        $sql = $this->db->select('construction_name')->get('construction_type');
        $result = $sql->result();
        $arr = array();
        for ($i = 0; $i < sizeof($result); $i++) {
            $arr[$i] = $result[$i]->construction_name;
        }
        return $arr;
    }

    public function saveObject($object)
    {
        $this->load->model("map_model");
        $this->db->insert('locations', $this->map_model->getAddress($object['location']));
        $object['location_id_fk'] = $this->db->insert_id();
        $object['userId'] = $this->user_model->getUserId();

        //insert this object into DB
        $this->db->insert('objects', Array(
            "object_type" => $object["realityType"],
            "ad_type" => $object["adType"],
            "creator_type" => $object["creatorType"],
            "walkthrough" => $object["walkthrough"],
            "square_area" => $object["squareArea"],
            "location_id_fk" => $object['location_id_fk']
        ));

        $objectId = $this->db->insert_id();

        //creates new ad for this object
        $this->db->insert('ad', Array("object_id_fk" => $objectId, "user_id_fk" => $object['userId']));
        $adId = $this->db->insert_id();

//      insert REL_USER_OBJECT
        $relArr = Array(
            'user_id_fk' => $object['userId'],
            'object_id_fk' => $objectId,
            'rel_type_id_fk' => CREATOR
        );
        $this->db->insert('rel_user_object', $relArr);

        $idArr = Array(
            "objectId" => $objectId,
            "adId" => $adId
        );

        return $idArr;
    }

    public function deleteObject($objectId)
    {
        if ($this->user_model->verifyUserObject($objectId)) {
            $this->db->where('object_id_pk', $objectId)->delete('objects');
        } else {
            return false;
        }
    }

    public function getObjectRooms($objectId)
    {
        $this->db->join('room_type', 'room_type.room_id_pk = rel_object_rooms.room_type_id_fk');
        $sql = $this->db->where(array('object_id_fk' => $objectId, 'habitable' => 1))->get('rel_object_rooms');
        return $sql->result();
    }

    public function unlinkObject($objectId)
    {
        $userId = $this->user_model->getUserId();
        if ($this->db->where(array('user_id_fk' => $userId, 'object_id_fk' => $objectId))->delete('rel_user_object')) {
            return true;
        }
        return false;
    }

    public function saveRoomPhoto($photoNumber, $objectRoomId, $galleryId)
    {
        $arr = array(
            'gallery_id_fk' => $galleryId,
            'object_room_id_fk' => $objectRoomId,
            'path' => $photoNumber
        );
        $this->db->insert('photo', $arr);
    }

    public function saveGallery($objectId)
    {
        $arr = array(
            'object_id_fk' => $objectId
        );
        $this->db->insert('gallery', $arr);
        return $this->db->insert_id();
    }

    public function setFrontPhoto($galleryId, $photoId)
    {
        $sql = $this->db->where('gallery_id_pk', $galleryId)->get('gallery');
        if ($sql->num_rows() > 0) {
            $arr = array(
                'front_photo_id_fk' => $photoId
            );
            $this->db->where('gallery_id_pk', $galleryId)->update('gallery', $arr);
            return true;
        } else {
            return false;
        }
    }

    public function getObjectGallery($galleryId)
    {
        $sql = $this->db->where('gallery_id_pk', $galleryId)->get('gallery');
        return $sql->result();
    }

    public function getRoomPhotos($object_room_id)
    {
        $sql = $this->db->where('object_room_id_fk', $object_room_id)->get('photo');
        return $sql->result();
    }
}