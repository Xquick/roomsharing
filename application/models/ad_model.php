<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 9.4.14
 * Time: 16:31
 */
class Ad_model extends CI_model
{
    public function updateAd($arr, $equipment, $adId = false)
    {
        if ($adId) {
            if ($this->user_model->verifyUserAd($adId)) {
                $this->db->where("ad_id_pk", $adId)->update('ad', $arr);
//                $this->setActivity($ad, ACTIVITY_EDIT);

                $equipmentArr = Array();
                $this->db->where('ad_id_fk', $adId)->delete('rel_ad_equipment');

                if (!empty($equipment)) {
                    foreach ($equipment as $item) {
                        array_push($equipmentArr, Array(
                            'equipment_type_id_fk' => $item,
                            'ad_id_fk' => $adId
                        ));
                    }
                    $this->db->insert_batch('rel_ad_equipment', $equipmentArr);
                }
            }
        }
    }

    public function adRemove($adId)
    {
        //TODO    implement
    }

    public function activatable($adId)
    {
        $sql = $this->db->query("SELECT ad_id_pk FROM ad
                    WHERE ad_id_pk = $adId
                    AND room_count > 0
                    AND ad_price > 0
                    AND max_people_count > 0");
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function toggleActive($adId)
    {
        $active = $this->db->where('ad_id_pk', $adId)->get('ad')->row('active');
        if ($active == 0) {
            $active = 1;
//            $this->user_model->setActivity($adId, ACTIVITY_ACTIVATE);
        } else {
//            $this->user_model->setActivity($adId, ACTIVITY_DEACTIVATE);
            $active = 0;
        }

        if ($active == 0 || $this->activatable($adId)) {
            $this->db->where('ad_id_pk', $adId)->update('ad', Array('active' => $active));
            return true;
        } else {
            return false;
        }
    }

    public function getFollowersNum($adId)
    {
        $sql = $this->db->select('rel_id_pk')->where('ad_id_fk', $adId)->get('rel_user_ad');
        return $sql->num_rows();
    }

    public function getRoomTypes()
    {
        $sql = $this->db->get('room_type');
        return $sql->result();
    }

    public function adDetail($adId)
    {
        $sql = $this->db->query("
        SELECT * FROM ad
        JOIN objects ON objects.object_id_pk = ad.object_id_fk
        JOIN locations ON locations.location_id_pk = objects.location_id_fk
        WHERE ad_id_pk = $adId;
        ");

        return $sql->result();
    }

    public function getEquipment($adId)
    {
        $sql = $this->db->query("
                        SELECT equipment_name FROM rel_ad_equipment
                        JOIN equipment_type ON rel_ad_equipment.equipment_type_id_fk = equipment_type.equipment_type_id_pk
                        WHERE rel_ad_equipment.ad_id_fk = $adId
                        ORDER BY equipment_name ASC;
        ");
        return $sql->result();
    }

    public function getFollowersList($adId)
    {
        $currentUserId = $this->user_model->getUserId();
        if (!empty($currentUserId)) {
            $query = "  SELECT * FROM rel_user_ad
                        JOIN users ON users.user_id_pk = rel_user_ad.user_id_fk
                        WHERE rel_user_ad.ad_id_fk = " . $adId . "
                        AND rel_user_ad.user_id_fk !=" . $currentUserId . "
                        AND rel_user_ad.user_id_fk NOT IN (SELECT user2_id_fk FROM rel_user_user WHERE user1_id_fk = " . $currentUserId . " AND ad_id_fk = " . $adId . ")";
            $sql = $this->db->query($query);
            return $sql->result();
        } else {
            return false;
        }
    }

    public function getGallery($adId)
    {
        $objectId = $this->getAdObject($adId);
        $path = 'galleries/' . $objectId . '/';
        $gallery = scandir($path);
        unset($gallery[0]);
        unset($gallery[1]);
        unset($gallery[sizeof($gallery) + 1]);
        return $gallery;
    }

    public function getAdObject($adId)
    {
        $sql = $this->db->select('object_id_fk')->where('ad_id_pk', $adId)->get('ad');
        return $sql->row('object_id_fk');
    }

    public function getOwner($adId)
    {
        $sql = $this->db->select("user_id_pk,firstname, lastname, email, phone")->join('users', 'ad.user_id_fk=users.user_id_pk')->where("ad_id_pk", $adId)->get('ad');
        return $sql->result();
    }

    public function filter()
    {
        $filterDataArr = [];
        //One big sql query composed from GET inputs///////////////////////////////////

        $query = "
                            SELECT * FROM ad
                            JOIN objects ON objects.object_id_pk = ad.object_id_fk
                            JOIN locations ON objects.location_id_fk = locations.location_id_pk
                            LEFT JOIN rel_ad_equipment ON rel_ad_equipment.ad_id_fk = ad.ad_id_pk
                            WHERE active = 1";
        //FILTER AREA
        if (isset($_GET["offset"]) && !empty($_GET["offset"])) {
            $filterDataArr["offset"] = $this->input->get("offset");
        } else {
            $filterDataArr["offset"] = 0;
        }
        if (isset($_GET["filter_area"]) && !empty($_GET["filter_area"])) {
            $filterDataArr["filter_area"] = $this->input->get("filter_area");
            $filterDataArr["filter_address"] = $this->map_model->getAddress($filterDataArr["filter_area"]);
//            return $filterDataArr["filter_address"];
            if (isset($filterDataArr["filter_address"]["sublocality_level_1"])) {
                $query .= " AND sublocality_level_1 = '" . $filterDataArr["filter_address"]["sublocality_level_1"] . "'";
            } else {
                if (isset($filterDataArr["filter_address"]["locality"])) {

                    $query .= " AND locality = '" . $filterDataArr["filter_address"]["locality"] . "'";
                }
            }
        }

        //FILTR BY SETTING BOUNDS Latitude and Longitudfacebooe
        if (isset($_GET["northeastLat"]) && !empty($_GET["northeastLat"]) &&
            isset($_GET["northeastLng"]) && !empty($_GET["northeastLng"]) &&
            isset($_GET["southwestLat"]) && !empty($_GET["southwestLat"]) &&
            isset($_GET["southwestLng"]) && !empty($_GET["southwestLng"])
        ) {
            $filterDataArr["northeastLat"] = $this->input->get('northeastLat');
            $filterDataArr["northeastLng"] = $this->input->get('northeastLng');
            $filterDataArr["southwestLat"] = $this->input->get('southwestLat');
            $filterDataArr["southwestLng"] = $this->input->get('southwestLng');

            $query .= " AND locations.lat < " . $filterDataArr["northeastLat"];
            $query .= " AND locations.lng < " . $filterDataArr["northeastLng"];
            $query .= " AND locations.lat > " . $filterDataArr["southwestLat"];
            $query .= " AND locations.lng > " . $filterDataArr["southwestLng"];
        }

        //FILTER PRICE FROM
        if (isset($_GET["filter_price_from"]) && !empty($_GET["filter_price_from"])) {
            $filterDataArr["filter_price_from"] = $this->input->get("filter_price_from");

            $query .= " AND ad_price > " . $filterDataArr["filter_price_from"];
        }
        //FILTER PRICE FROM
        if (isset($_GET["filter_price_to"]) && !empty($_GET["filter_price_to"])) {
            $filterDataArr["filter_price_to"] = $this->input->get("filter_price_to");

            $query .= " AND ad_price < " . $filterDataArr["filter_price_to"];
        }
        //FILTER OBJECT TYPE
        if (isset($_GET["filter_object_type"]) && !empty($_GET["filter_object_type"])) {
            $filterDataArr["filter_object_type"] = $this->input->get("filter_object_type");

            $query .= " AND object_type IN (" . arrayToCsv($filterDataArr["filter_object_type"]) . ")";

        }
        //FILTER BAIL BOOLEAN
        if (isset($_GET["filter_bail_boolean"])) {
            $filterDataArr["filter_bail_boolean"] = $this->input->get("filter_bail_boolean");
            if ($filterDataArr["filter_bail_boolean"] == 0)
                $query .= " AND bail_boolean = " . $filterDataArr["filter_bail_boolean"];
        }
        //FILTER SEX
        if (isset($_GET["filter_sex"]) && !empty($_GET["filter_sex"])) {
            $filterDataArr["filter_sex"] = $this->input->get("filter_sex");

            $query .= " AND sex IN (" . arrayToCsv($filterDataArr["filter_sex"]) . ")";
        }
        //FILTER TIME FROM
        if (isset($_GET["filter_time_from"]) && !empty($_GET["filter_time_from"])) {
            $filterDataArr["filter_time_from"] = $this->input->get("filter_time_from");

            $query .= " AND available_from < " . $filterDataArr["filter_time_from"];
        }
        //FILTER TIME TO
        if (isset($_GET["filter_time_to"]) && !empty($_GET["filter_time_to"])) {
            $filterDataArr["filter_time_to"] = $this->input->get("filter_time_to");

            $query .= " AND available_to > " . $filterDataArr["filter_time_to"];
        }

        //FILTER SQUARE AREA
        if (isset($_GET["filter_square_area"]) && !empty($_GET["filter_square_area"])) {
            $filterDataArr["filter_square_area"] = $this->input->get("filter_square_area");

            $query .= " AND square_area > " . $filterDataArr["filter_square_area"];
        }

        //FILTER EQUIPMENT
        if (isset($_GET["filter_equipment"]) && !empty($_GET["filter_equipment"])) {
            $filterDataArr["filter_equipment"] = $this->input->get("filter_equipment");

            $query .= " AND equipment_type_id_fk IN(" . arrayToCsv($filterDataArr["filter_equipment"]) . ")
            GROUP BY ad_id_fk
            HAVING COUNT(ad_id_fk) =" . sizeof($filterDataArr["filter_equipment"]);

        } else {
            $query .= " GROUP BY ad_id_pk ";
        }

        $query .= " ORDER BY UPPER(ad_id_pk) DESC
                    LIMIT 10
                    OFFSET " . $filterDataArr["offset"];

        $sql = $this->db->query($query);

        return $sql->result_array();
    }
}