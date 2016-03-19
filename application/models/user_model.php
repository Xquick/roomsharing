<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 14.3.14
 * Time: 21:44
 */
class User_model extends CI_Model
{
    public function login($email, $password)
    {
        $sql = $this->db->where('email', $email)->where('password', $password)->get('users');
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function startSession($email)
    {
        session_start();

        $results = $this->db->where('email', $email)->get('users')->result();
        $firstname = $results[0]->firstname;
        $lastname = $results[0]->lastname;
        $gender = $results[0]->gender;
        $fbId = $results[0]->fb_id;
        $email = $results[0]->email;

        $_SESSION['email'] = $email;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['gender'] = $gender;
        $_SESSION['fb_id'] = $fbId;
        $_SESSION['logged_in'] = TRUE;

        return $results;
    }

    public function endSession()
    {
        session_start();
        session_destroy();
    }

    public function getObjects()
    {
        $objects = $this->crud->getData('objects');
        return $objects;
    }

    public function getFilteredAds()
    {
        $filterArrTemp = $this->getFilter();
        $filterArr = array();
        $extraFilterKeys = array('price_from', 'price_to', 'address');
        $extraFilter = array();

        foreach ($filterArrTemp as $key => $value) {
            if (!in_array($key, $extraFilterKeys)) {
                $filterArr[$key] = $value;
            } else {
                $extraFilter[$key] = $value;
            }
        }
        if (!array_key_exists('price_to', $extraFilter) || $extraFilter['price_to'] == 0) {
            $extraFilter['price_to'] = PHP_INT_MAX;
        }
        if (!array_key_exists('price_from', $extraFilter)) {
            $extraFilter['price_from'] = 0;
        }
        $this->db->join('rel_user_ad', 'ad.ad_id_pk = rel_user_ad.ad_id_fk', 'left');
        $this->db->join('objects', 'ad.object_id_fk = objects.object_id_pk');
        $this->db->join('rel_ad_equipment', 'rel_ad_equipment.ad_id_fk = ad.ad_id_pk');
        $this->db->join('equipment_type', 'equipment_type.equipment_type_id_pk = rel_ad_equipment.equipment_type_id_fk');
        $this->db->join('locations', 'objects.location_id_fk = locations.location_id_pk');
        $this->db->where($filterArr);
        $ads = $this->db->where('ad_price >=', $extraFilter['price_from'])->
            where('ad_price <=', $extraFilter['price_to'])->where('active', 1)->get('ad')->result();
//        var_dump($ads);
        $ads = $this->formateAds($ads);

//        echo $this->db->last_query();
        return $ads;
    }


    public function getUserObjects()
    {
        $userId = $this->getUserId();
        $this->db->join('rel_user_object', 'rel_user_object.object_id_fk = objects.object_id_pk', 'left');
        $this->db->join('ad', 'ad.object_id_fk = objects.object_id_pk', 'left');
        $this->db->join('rel_user_ad', 'rel_user_ad.ad_id_fk = ad.ad_id_pk', 'left');
        $this->db->join('users', 'rel_user_object.user_id_fk = users.user_id_pk', 'left');
        $this->db->join('locations', 'locations.location_id_pk = objects.location_id_fk', 'left');
        $results = $this->db->where('rel_user_object.user_id_fk', $userId)->get('objects')->result();
//        echo $this->db->last_query();
//        var_dump($results);
        $objects = array();
        for ($i = 0; $i < sizeof($results); $i++) {
            $objects[$i] = new stdClass();
            $objects[$i]->object_id_pk = $results[$i]->object_id_pk;
            $objects[$i]->street_number = $results[$i]->street_number;
            $objects[$i]->locality = $results[$i]->locality;
            $objects[$i]->route = $results[$i]->route;
            $objects[$i]->postal_code = $results[$i]->postal_code;
            $objects[$i]->postal_town = $results[$i]->postal_town;
            $objects[$i]->street_number = $results[$i]->street_number;
            $objects[$i]->sublocality = $results[$i]->sublocality;
            $objects[$i]->sublocality_level_1 = $results[$i]->sublocality_level_1;
            $objects[$i]->route = $results[$i]->route;
            $objects[$i]->lat = $results[$i]->lat;
            $objects[$i]->lng = $results[$i]->lng;
            $objects[$i]->ad = array();
        }
        for ($i = 0; $i < sizeof($results); $i++) {
            $tempCount = 0;
            for ($j = 0; $j < sizeof($results); $j++) {
                if ($objects[$i]->object_id_pk == $results[$j]->object_id_fk) {
                    $objects[$i]->ad[$tempCount] = new stdClass();
                    $objects[$i]->ad[$tempCount]->ad_id_pk = $results[$j]->ad_id_pk;
                    $objects[$i]->ad[$tempCount]->ad_price = $results[$j]->ad_price;
                    $objects[$i]->ad[$tempCount]->active = $results[$j]->active;
                    $objects[$i]->ad[$tempCount]->ad_title = $results[$j]->ad_title;
                    $followers = 0;
                    for ($k = 0; $k < sizeof($results); $k++) {
                        if ($objects[$i]->ad[$tempCount]->ad_id_pk == $results[$k]->ad_id_fk) {
                            $followers++;
                        }
                    }
                    $objects[$i]->ad[$tempCount]->ad_followers = $followers;
                    $tempCount++;
                }
            }
            // remove any duplicates from the result array
            for ($l = 0; $l < $tempCount; $l++) {
                for ($k = 0; $k < $tempCount; $k++) {
                    if (isset($objects[$i]->ad[$k]->ad_id_pk) && isset($objects[$i]->ad[$l]->ad_id_pk)) {
                        if ($objects[$i]->ad[$k]->ad_id_pk == $objects[$i]->ad[$l]->ad_id_pk && $k != $l) {
                            unset($objects[$i]->ad[$l]);
                        }
                    }
                }
            }
            $objects[$i]->ad = array_values($objects[$i]->ad);
        }

        $objects = array_unique($objects, SORT_REGULAR);
        $objects = array_values($objects);
//        print_r($objects);
//        var_dump($objects);
        return $objects;
    }

    public function getFollowedAds()
    {
        $userId = $this->getUserId();
        $query = "  SELECT * FROM ad
                    JOIN rel_user_ad ON ad.ad_id_pk = rel_user_ad.ad_id_fk
                    JOIN objects ON ad.object_id_fk = objects.object_id_pk
                    LEFT JOIN rel_ad_equipment ON rel_ad_equipment.ad_id_fk = ad.ad_id_pk
                    LEFT JOIN equipment_type ON equipment_type.equipment_type_id_pk = rel_ad_equipment.equipment_type_id_fk
                    JOIN locations ON objects.location_id_fk = locations.location_id_pk
                    WHERE rel_user_ad.user_id_fk =" . $userId . "
                    ORDER BY timestamp DESC";

        $ads = $this->db->query($query)->result();

        $ads = $this->formateAds($ads);
        return $ads;
    }

    public function getUserFollowersNum($userId)
    {
        $this->db->join('ad', 'ad.ad_id_pk = rel_user_ad.ad_id_fk', 'left');
        $sql = $this->db->where(array('ad.user_id_fk' => $userId))->get('rel_user_ad');
        $result = $sql->num_rows();
//        echo $this->db->last_query();
//        echo $result;
        return $result;
    }

    public function getUserFollowedNum($userId)
    {
        $sql = $this->db->where(array('user_id_fk' => $userId, 'rel_type_id_fk' => FOLLOW))->get('rel_user_ad');

        $result = $sql->num_rows();
//        echo $result;
        return $result;
    }

    public function getUserInfo($userId)
    {
        $sql = $this->db->where('user_id_pk', $userId)->get('users');
        return $sql->result();
    }

    public function getConversationInfo($conversationId)
    {
        $userId = $this->getUserId();
        $this->db->join('users', 'users.user_id_pk = rel_user_conversation.user_id_fk');
        $sql = $this->db->where(array('conversation_id_fk' => $conversationId, 'user_id_fk !=' => $userId))->get('rel_user_conversation');
        return $sql->result();
    }

    public function getUserSettings($userId)
    {
        $sql = $this->db->where('user_id_fk', $userId)->get('user_settings');
        return $sql->result();
    }

    public function getFilter()
    {
        $userId = $this->getUserId();
        $sql = $this->db->where('user_id_fk', $userId)->get('user_settings');
        $filterArrTemp = $sql->result();
        $filterArr = array();
        for ($i = 0; $i < sizeof($filterArrTemp); $i++) {
            if ($filterArrTemp[$i]->type == 0) {
                $filterArr[$filterArrTemp[$i]->name] = $filterArrTemp[$i]->value_int;
            } else {
                $filterArr[$filterArrTemp[$i]->name] = $filterArrTemp[$i]->value_char;
            }
        }
        return $filterArr;
    }

    public function getFormattedFilter()
    {
        $userId = $this->getUserId();
        $sql = $this->db->where('user_id_fk', $userId)->get('user_settings');
        $result = $sql->result();
        $arr = array();
        for ($i = 0; $i < sizeof($result); $i++) {
            switch ($result[$i]->name) {
                case 'address':
                    $arr['address'] = $result[$i]->value_char;
                    break;
                case 'price_from':
                    $arr['price_from'] = $result[$i]->value_int;
                    break;
                case 'price_to':
                    $arr['price_to'] = $result[$i]->value_int;
                    break;
            }
        }
        return $arr;
    }

    public function setFilter($address, $priceFrom, $priceTo)
    {
        $userId = $this->getUserId();
        $this->db->where('user_id_fk', $userId)->delete('user_settings');

        if (isset($address)) {
            $this->setFilterValue('address', 1, $address);
            $addressFormatted = $this->map_model->getAddress(urlencode($address));
            if (array_key_exists('country', $addressFormatted))
                $this->setFilterValue('country', 1, $addressFormatted['country']);

            if (array_key_exists('locality', $addressFormatted))
                $this->setFilterValue('locality', 1, $addressFormatted['locality']);

            if (array_key_exists('sublocality', $addressFormatted))
                $this->setFilterValue('sublocality', 1, $addressFormatted['sublocality']);

//            if (array_key_exists('neighborhood', $addressFormatted))
//                $this->setFilterValue('neighborhood', 1, $addressFormatted['neighborhood']);

            if (array_key_exists('postal_code', $addressFormatted))
                $this->setFilterValue('postal_code', 1, $addressFormatted['postal_code']);

            if (array_key_exists('route', $addressFormatted))
                $this->setFilterValue('route', 1, $addressFormatted['route']);

        }
        if (isset($priceFrom)) {
            $this->setFilterValue('price_from', 0, $priceFrom);
        }
        if (isset($priceTo)) {
            $this->setFilterValue('price_to', 0, $priceTo);
        }
    }

    public function setFilterValue($name, $type, $val)
    {
        $userId = $this->getUserId();
        $arr = array(
            'name' => $name,
            'type' => $type,
        );
        if ($type == 0) {
            $arr['value_int'] = $val;
        } else {
            $arr['value_char'] = $val;
        }
        if ($this->crud->getNumOfRecords('user_settings', array('user_id_fk' => $userId, 'name' => $name)) > 0) {
            $this->crud->updateRecord('user_settings', $arr, array('user_id_fk' => $userId, 'name' => $name));
        } else {
            $arr['user_id_fk'] = $userId;
            $this->crud->insertRecord('user_settings', $arr);
        }
    }

    public function getUserId()
    {
        if (!isset($_SESSION))
            session_start();
        if (isset($_SESSION['email']))
            $userEmail = $_SESSION['email'];
        if (!empty($userEmail)) {
            $userId = $this->crud->getSpecificData('users', array('email' => $userEmail))->user_id_pk;
            return $userId;
        } else {
            return false;
        }
    }

    public function follow($adId)
    {
        $userId = $this->getUserId();
        if ($userId != 0) {
            $arr = array(
                'user_id_fk' => $userId,
                'ad_id_fk' => $adId,
                'rel_type_id_fk' => FOLLOW
            );
            if ($this->crud->getNumOfRecords('rel_user_ad', array('user_id_fk' => $userId, 'ad_id_fk' => $adId)) == 0) {
                $this->crud->insertRecord('rel_user_ad', $arr);
//                $sql = $this->db->query("   UPDATE users
//                                            SET roommates_new = roommates_new +1
//                                            WHERE user_id_pk IN (
//                                                     SELECT user_id_fk FROM rel_user_ad
//                                                     WHERE ad_id_fk = $adId)
//                ");
//                $sql->results();
            }
            return true;
        } else {
            return false;
        }
    }

    public function unfollow($adId)
    {
        $userId = $this->getUserId();
        if ($userId != 0) {
            if ($this->crud->getNumOfRecords('rel_user_ad', array('user_id_fk' => $userId, 'ad_id_fk' => $adId)) > 0) {
                $this->crud->deleteRecord('rel_user_ad', array('user_id_fk' => $userId, 'ad_id_fk' => $adId));
            }
            return true;
        } else {
            return false;
        }
    }

    public function verifyUserObject($objectId)
    {
        $userId = $this->getUserId();
        $sql = $this->db->where(array('user_id_fk' => $userId, 'object_id_fk' => $objectId))->get('rel_user_object');
        if ($sql->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyUserAd($adId)
    {
        $userId = $this->getUserId();
        $sql = $this->db->where(array('user_id_fk' => $userId, 'ad_id_pk' => $adId))->get('ad');
        if ($sql->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function saveTmpEquipment($equipmentArr)
    {
        $this->db->where(array('user_id_fk' => $this->getUserId()))->delete('tmp_object_equipment');
        if (!empty($equipmentArr)) {
            foreach ($equipmentArr as $equipment) {
                $arr = array(
                    'user_id_fk' => $this->getUserId(),
                    'equipment_id_fk' => $equipment->equipment_id
                );
                $this->db->insert('tmp_object_equipment', $arr);
            }
        }
    }

    public function pairUserObject($objectId, $inputFloor, $lat, $lng)
    {
        $offset = 0.00001;
        $this->db->join('locations', 'locations.location_id_pk = objects.location_id_fk');
        $sql = $this->db->where(array(
            'locations.lat >' => $lat - $offset,
            'locations.lat <' => $lat + $offset,
            'locations.lng >' => $lng - $offset,
            'locations.lng <' => $lng + $offset,
            'objects.object_id_pk' => $objectId,
            'objects.floor' => $inputFloor
        ))->get('objects');
        $userId = $this->getUserId();
        if ($sql->num_rows() > 0) {
//            print_r($sql->result());
            $arr = array(
                'user_id_fk' => $userId,
                'object_id_fk' => $objectId,
                'rel_type_id_fk' => USER
            );
            $sql = $this->db->where(array('user_id_fk' => $userId, 'object_id_fk' => $objectId))->get('rel_user_object');
            if ($sql->num_rows() == 0) {
                $this->db->insert('rel_user_object', $arr);
                return 1;
            } else {
                return 0;
            }
        } else {
            return -1;
        }
    }

    public function unlinkObject($objectId)
    {
        $userId = $this->getUserId();
        $this->db->where(array('user_id_fk' => $userId, 'object_id_fk' => $objectId));
    }

    public function verifyUserConversation($conversationId)
    {
        $userId = $this->getUserId();
        $sql = $this->db->where(array('user_id_fk' => $userId, 'conversation_id_fk' => $conversationId))->get('rel_user_conversation');
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkCurrentUser($userId)
    {
        $currentUserId = $this->getUserId();
        if ($currentUserId == $userId) {
            return true;
        } else {
            return false;
        }
    }

    public function getSimilarUsers()
    {
        $userId = $this->getUserId();
        $filter = $this->getFilter();
//        var_dump($filter);
        if (array_key_exists('locality', $filter))
            $locality = $filter['locality'];
        if (array_key_exists('sublocality', $filter))
            $sublocality = $filter['sublocality'];
        if (!empty($sublocality)) {
            $this->db->join('user_settings', 'users.user_id_pk = user_settings.user_id_fk');
//        $this->db->where(array('name' => 'locality', 'value_char' => $locality));
            $sql = $this->db->where(array('name' => 'sublocality', 'value_char' => $sublocality, 'user_id_pk !=' => $userId))->get('users');
            $result = $sql->result();
            return $result;
        } else {
            return false;
        }
    }

    public function verifyLastUserConversation($conversationId)
    {
        $userId = $this->getUserId();
        $sql = $this->db->select('user_id_fk')->where(array('conversation_id_pk' => $conversationId, 'user_id_fk' => $userId))->get('conversation');
        if ($sql->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function formateAds($ads)
    {
        $finalArray = array();

        for ($i = 0; $i < sizeof($ads); $i++) {
            $finalArray[$i]['ad_id_pk'] = $ads[$i]->ad_id_pk;
            $finalArray[$i]['object_id_pk'] = $ads[$i]->object_id_pk;
            $finalArray[$i]['postal_town'] = $ads[$i]->postal_town;
            $finalArray[$i]['sublocality'] = $ads[$i]->sublocality;
            $finalArray[$i]['sublocality_level_1'] = $ads[$i]->sublocality_level_1;
            $finalArray[$i]['route'] = $ads[$i]->route;
            $finalArray[$i]['ad_price'] = $ads[$i]->ad_price;
            $finalArray[$i]['lat'] = $ads[$i]->lat;
            $finalArray[$i]['lng'] = $ads[$i]->lng;
        }
        $finalArray = array_unique($finalArray, SORT_REGULAR);
        $finalArray = array_values($finalArray);
        return $finalArray;
    }

    public function recentUsers()
    {
        $userId = $this->getUserId();
        $sql = $this->db->where('user_id_fk', $userId)->get('rel_user_conversation');
        $arrayConversations = $sql->result_array();
        if (sizeof($arrayConversations) > 0) {
            for ($i = 0; $i < sizeof($arrayConversations); $i++) {
                $arrayConversations[$i] = $arrayConversations[$i]['conversation_id_fk'];
            }
            $this->db->join('users', 'users.user_id_pk =  rel_user_conversation.user_id_fk');
            $sql = $this->db->select('user_id_fk, firstname, lastname')->where_in('conversation_id_fk', $arrayConversations)->where('user_id_fk !=', $userId)->get('rel_user_conversation');
            $recentUsers = $sql->result();
            return $recentUsers;
        } else {
            return false;
        }
    }

    public function likeDislikeRoommates($roommate, $answer)
    {
        $currentUser = $this->getUserId();
        if (!empty($currentUser)) {
            $arr = Array(
                "user1_id_fk" => $currentUser,
                "user2_id_fk" => $roommate,
                "answer" => $answer
            );
            $this->db->insert("rel_user_user", $arr);
        }
    }

    public function acceptRejectRoommates($roommate, $answer)
    {
        $currentUser = $this->getUserId();
        if (!empty($currentUser)) {
            $this->db->query("  UPDATE rel_user_user
                                SET answer = $answer
                                WHERE user1_id_fk = $roommate AND user2_id_fk = $currentUser");
        }
    }

    public function removeRoommate($roommate)
    {
        $currentUser = $this->getUserId();
        if (!empty($currentUser)) {
            $this->db->query("  UPDATE rel_user_user
                                SET removed = 1
                                WHERE user1_id_fk = $roommate AND user2_id_fk = $currentUser
                                OR user2_id_fk = $roommate AND user1_id_fk = $currentUser");
        }
    }

    public function roommateProfile($roommate)
    {
        $currentUser = $this->getUserId();
        if (!empty($currentUser)) {
            $query = "  SELECT * FROM users
                                WHERE user_id_pk = $roommate
                                AND (user_id_pk IN (
                                    SELECT user2_id_fk FROM rel_user_user
                                    WHERE user1_id_fk = $currentUser)
                                    OR
                                    user_id_pk IN (
                                    SELECT user1_id_fk FROM rel_user_user
                                    WHERE user2_id_fk = $currentUser ))";
        }

        $sql = $this->db->query($query);
        $result = $sql->result();
        foreach ($result as $roommate) {
            $c = date('Y');
            $y = date('Y', strtotime($roommate->birthday));
            $roommate->age = $c - $y;
            $roommate->languages = $this->getLanguages($roommate->user_id_pk);
            $roommate->job = $this->getJob($roommate->user_id_pk);
            $roommate->education = $this->getEducation($roommate->user_id_pk);
        }
        return $result;
    }

    public function getSuggestedAds()
    {
        $currentUserId = $this->getUserId();
        $this->db->join("ad", "ad.ad_id_pk=rel_user_user.ad_id_fk");
        $this->db->join("objects", "objects.object_id_pk=ad.object_id_fk");
        $this->db->join("locations", "objects.location_id_fk=locations.location_id_pk")
            ->where("user2_id_fk", $currentUserId)->where("user1_id_fk !=", $currentUserId);
        $this->db->distinct();
        $this->db->group_by('ad.ad_id_pk');
        $sql = $this->db->get("rel_user_user");
        $result = $sql->result();
        $suggestedAds = $this->formateAds($result);
        return $suggestedAds;
    }

    public function getSuggestedRoommates()
    {
        $currentUserId = $this->getUserId();
        $this->db->select("firstname,lastname")->join("users", "users.user_id_pk=rel_user_user.user2_id_fk")
            ->where("user1_id_fk", $currentUserId)->where("user1_id_fk !=", $currentUserId)
            ->where("answer", 1);
        $this->db->distinct();
        $this->db->group_by('user2_id_fk');
        $sql = $this->db->get("rel_user_user");

        return $sql->result();
    }

    public function getAllRoommates()
    {
        $currentUserId = $this->getUserId();
        if (!empty($currentUserId)) {
            $query = "  SELECT DISTINCT * FROM rel_user_ad
                        JOIN users ON rel_user_ad.user_id_fk = users.user_id_pk
                        LEFT JOIN rel_user_user ON rel_user_user.user2_id_fk = users.user_id_pk
                                                    OR rel_user_user.user1_id_fk = users.user_id_pk
                        WHERE ((
                        rel_user_ad.ad_id_fk IN (SELECT ad_id_fk FROM rel_user_ad  WHERE user_id_fk = $currentUserId)  AND answer IS NULL)
                        OR (answer = 0 AND user2_id_fk = $currentUserId)
                        )
                        AND rel_user_ad.user_id_fk != $currentUserId
                        GROUP BY (user_id_pk)
                        ORDER BY (user2_id_fk = $currentUserId) DESC";
            $sql = $this->db->query($query);
            $result = $sql->result();
            foreach ($result as $roommate) {
                $c = date('Y');
                $y = date('Y', strtotime($roommate->birthday));
                $roommate->age = $c - $y;
                $roommate->languages = $this->getLanguages($roommate->user_id_pk);
                $roommate->job = $this->getJob($roommate->user_id_pk);
                $roommate->education = $this->getEducation($roommate->user_id_pk);
            }
            return $result;
        }
        return false;
    }

    public function getLanguages($userId)
    {
        $sql = $this->db->query("  SELECT language_name FROM enum_languages
                            JOIN rel_user_language ON enum_languages.enum_languages_id_pk = rel_user_language.language_id_fk
                            WHERE rel_user_language.user_id_fk = $userId");

        return $sql->result();
    }


    public function getEducation($userId)
    {
        $sql = $this->db->query("  SELECT education_name FROM enum_education
                            JOIN rel_user_education ON enum_education.enum_education_id_pk = rel_user_education.education_id_fk
                            WHERE rel_user_education.user_id_fk = $userId");
        return $sql->result();
    }


    public function getJob($userId)
    {
        $sql = $this->db->query("  SELECT job_name FROM enum_job
                            JOIN rel_user_job ON enum_job.enum_job_id_pk = rel_user_job.job_id_fk
                            WHERE rel_user_job.user_id_fk = $userId");

        return $sql->result();
    }

    public function getRoommate($roommateId)
    {
        $currentUserId = $this->getUserId();

        if (!empty($currentUserId)) {
            $query = "  SELECT DISTINCT * FROM users
                        WHERE user_id_pk = " . $roommateId;
            $sql = $this->db->query($query);

            return $sql->result();
        }
        return false;
    }

    public function getMyRoommates()
    {
        $currentUserId = $this->getUserId();
        if (!empty($currentUserId)) {
            $query = "  SELECT DISTINCT * FROM rel_user_user
                        JOIN users ON rel_user_user .user2_id_fk = users.user_id_pk
                                   OR rel_user_user .user1_id_fk = users.user_id_pk
                        WHERE (user1_id_fk = $currentUserId
                        OR (user2_id_fk = $currentUserId AND answer = 1))
                        AND user_id_pk != $currentUserId
                        AND rel_user_user.removed !=1
                        GROUP BY users.user_id_pk
                        ORDER BY rel_user_user.timestamp DESC";
            $sql = $this->db->query($query);
            $results = $sql->result();
            $final = [];
            foreach ($results as $row) {
                if ($row->user2_id_fk == $currentUserId) {
                    $pom = $row->user2_id_fk;
                    $row->user2_id_fk = $row->user1_id_fk;
                    $row->user1_id_fk = $pom;
                }
                if ($row->user2_id_fk == $currentUserId)
                    array_push($final, $row);
            }
            return $results;
        }
        return false;
    }

    public function isMyRoommate($roommateId)
    {
        $currentUserId = $this->getUserId();
        $query = $this->db->query("
        SELECT * FROM rel_user_user
        WHERE ((user1_id_fk = $currentUserId AND user2_id_fk = $roommateId) OR (user2_id_fk = $currentUserId AND user1_id_fk = $roommateId))
        AND answer = 1
        ");

        $result = $query->num_rows() == 0 ? 1 : 0;
        return $query->num_rows();
    }

    public function whoSuggestedAd($adId)
    {
        $currentUserId = $this->getUserId();
        if (!empty($currentUserId)) {
            $this->db->join("users", "users . user_id_pk = rel_user_user . user1_id_fk")
                ->where("ad_id_fk", $adId)->where("user2_id_fk", $currentUserId)
                ->where("user1_id_fk != ", $currentUserId);

            $this->db->distinct();
            $this->db->group_by('user1_id_fk');
            $sql = $this->db->get("rel_user_user");

            return $sql->result();
        }
    }

    public function doIFollowAd($adId)
    {
        $currentUserId = $this->getUserId();
        if (!empty($currentUserId)) {
            $this->db->where("user_id_fk", $currentUserId);
            $this->db->where("ad_id_fk", $adId);
            $sql = $this->db->get("rel_user_ad");
            return $sql->num_rows() > 0 ? true : false;
        }
    }

    public function zeroOutRoommatesNew()
    {
        $currentUserId = $this->getUserId();
        if (!empty($currentUserId)) {
            $this->db->simple_query("
            UPDATE users
            SET roommates_new = 0
            WHERE user_id_pk = $currentUserId
            ")->results();
        }
    }

    public function zeroOutSuggestionsNew()
    {
        //TODO zeroOutSuggestionsNew();
    }

    public function adsIncommon($roommateId)
    {
        $currentUserId = $this->getUserId();
        if ($currentUserId) {
            $query = ("
                SELECT * FROM ad
                JOIN rel_user_ad ON rel_user_ad.ad_id_fk = ad.ad_id_pk
                JOIN objects ON ad.object_id_fk = objects.object_id_pk
                JOIN locations ON locations.location_id_pk = objects.location_id_fk
                WHERE ad_id_fk IN (SELECT ad_id_fk FROM rel_user_ad WHERE user_id_fk = $currentUserId)
                AND rel_user_ad.user_id_fk = $roommateId
            ");
            $ads = $this->db->query($query)->result();
            $ads = $this->formateAds($ads);
            return $ads;
        }
    }
}
