<?php
/**
 * Created for mojespolubydleni.cz
 * User: Adam Mrazek, mrazek.adam@gmail.com
 * Date: 14.3.14
 * Time: 11:05
 */

require_once(APPPATH . 'controllers/generic_c.php');

class Site_c extends Generic_c
{
    public $logged_out_allowed = Array(
        "discover",
        "ad_detail",
        "filter"
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('ad_model');
        $this->load->model('object_model');
    }

    public function index()
    {
        $this->loadView('discover');
    }

    public function loadView($view)
    {
        session_start();
        if (isset($_SESSION['logged_in']))
            $this->data['logged'] = $_SESSION['logged_in'];
        else
            $this->data['logged'] = FALSE;

        if ($this->data['logged']) {
            $userId = $this->user_model->getUserId();
//            echo "User Id je: " . $userId;
            $this->data['conversations'] = $this->chat_model->getUserConversations(); //api
            $this->data['messageCount'] = $this->chat_model->getMessageCount();
            $this->data['suggestedAds'] = $this->user_model->getSuggestedAds();
            $this->data['followedAds'] = $this->user_model->getFollowedAds();
            $this->data['current_user_info'] = $this->user_model->getUserInfo($userId);
            $this->data['roomTypes'] = $this->ad_model->getRoomTypes();
            $this->data['equipment'] = $this->object_model->getEquipmentTypes();
            $this->data['formattedFilter'] = $this->user_model->getFormattedFilter();
            $this->data['filter'] = $this->user_model->getFilter();
            $this->data['objects'] = $this->user_model->getObjects();
            $this->data['filterResult'] = $this->ad_model->filter();
            $this->data['userObjects'] = $this->user_model->getUserObjects();
            $this->data['roommates'] = $this->user_model->getMyRoommates();
            $this->data['currentUserId'] = $userId;

            $this->data['content'] = array($view);
        } else {
//          checks if site is available for NOT logged in user
            if (in_array($view, $this->logged_out_allowed)) {
                $this->data['content'] = array($view);
                $this->data['equipment'] = $this->object_model->getEquipmentTypes();
//                $this->data['discover'] = $this->user_model->getFilteredAds();
                $this->data['filterResult'] = $this->ad_model->filter();
                $this->data['formattedFilter'] = $this->user_model->getFormattedFilter();
                $this->data['followedAds'] = Array();
            } else {
                $this->data['content'] = array('login');
            }
        }
        parent::initViews();
    }

    public function f()
    {
        print_r($this->user_model->getFollowedAds());
    }

    public function isVisibleForLoggedOut($view)
    {
        if (in_array($view, $this->logged_out_allowed))
            return true;
        return false;
    }

    public function userProfile($userId = FALSE)
    {

        $segment = $this->uri->segment(2);
//        if ($userId == $this->user_model->getUserId())
//            redirect('/user');

        if (!is_numeric($userId)) {
            switch ($segment) {
                case 'messages':
                    $this->loadView("messages");
                    break;
                case 'info':
                    $this->data['userContent'] = 'info';
                    break;
                case 'groups':
                    $this->data['userContent'] = 'groups';
                    break;
                default:
                    redirect('/user/info');
                    break;
            }
        }
        if (!$userId)
            $userId = $this->user_model->getUserId();
        //api
        $this->data['isCurrentUser'] = $this->user_model->checkCurrentUser($userId); //api
        $this->data['user_info'] = $this->user_model->getUserInfo($userId); //api
        $this->data['user_settings'] = $this->user_model->getUserSettings($userId); //api
        $this->data['followedNum'] = $this->user_model->getUserFollowedNum($userId); //api
        $this->data['followersNum'] = $this->user_model->getUserFollowersNum($userId); //api
        $this->loadView('user');
    }

    public function objectCreate()
    {
        $this->loadView('objects_new');
    }

    public function objectEdit($adId)
    {
        $adDetail = $this->ad_model->adDetail($adId);
        $this->data['adDetail'] = $adDetail[0];
        $this->data['ad_equipment'] = $this->ad_model->getEquipment($adId);
        $this->data['ad_gallery'] = $this->ad_model->getGallery($adId);
        $this->loadView('objects_new');
    }

    public function adCreate($objectId)
    {
        $this->data['objectId'] = $objectId;
        $this->data['objectRooms'] = $this->object_model->getObjectRooms($objectId);
        $this->loadView('ad_new');
    }

    public function adDetail($adId)
    {
        $this->data['adId'] = $adId;
        $adDetail = $this->ad_model->adDetail($adId);
        $this->data['gallery'] = $this->ad_model->getGallery($adId);
        $this->data['isFollowed'] = $this->user_model->doIFollowAd($adId);
        $this->data['ad_equipment'] = $this->ad_model->getEquipment($adId);
        $this->data['adDetail'] = $adDetail[0];
        $this->data['formatTime'] = formatTime($this->data['adDetail']->date_inserted);
        $this->loadView('ad_detail');
    }

    public function roommateProfile($roommateId)
    {
        if (isset($roommateId) && !empty($roommateId)) {
            $this->data["roommate"] = $this->user_model->roommateProfile($roommateId);
        }
        $this->data["isRoommate"] = $this->user_model->isMyRoommate($roommateId);
//        echo $this->db->last_query();
        $this->data["adsIncommon"] = $this->user_model->adsIncommon($roommateId);
        $this->loadView("roommate_profile", $this->data);
    }

    public function filter()
    {
        $this->data["filterResult"] = $this->ad_model->filter();
        echo $this->db->last_query();
        $this->loadView("discover");
    }

    public function adRoommates($roommateId = null)
    {
        $currentUserId = $this->user_model->getUserId();
        if ($roommateId != null) {
            $this->data["followers"] = $this->user_model->getRoommate($roommateId);
        } else {
            $this->data["followers"] = $this->user_model->getAllRoommates();
        }
//        print_r($this->data["followers"]);
//        echo $this->db->last_query();
        $this->data["currentUserId"] = $currentUserId;
        $this->loadView("ad_roommates");
    }

    public function userChat($conversationId)
    {
        $currentUserId = $this->user_model->getUserId();
        if ($currentUserId != $conversationId) {
            $conversation = $this->user_model->getConversationInfo($conversationId);
            $data['currentUserId'] = $currentUserId;
            $data['conversation'] = $conversation;
            $data['messageCount'] = $this->chat_model->getMessageCount();
            $data['messages'] = array_reverse($this->chat_model->getMessages($conversationId, 8));
            $this->load->view('content/chat/chat_current', $data);
        }
    }

    public function tt()
    {
        $userId = $this->user_model->getUserId();
        $this->db->join('rel_user_ad', 'ad.ad_id_pk = rel_user_ad.ad_id_fk');
        $this->db->join('objects', 'ad.object_id_fk = objects.object_id_pk');
        $this->db->join('rel_ad_equipment', 'rel_ad_equipment.ad_id_fk = ad.ad_id_pk');
        $this->db->join('equipment_type', 'equipment_type.equipment_type_id_pk = rel_ad_equipment.equipment_type_id_fk');
        $this->db->join('locations', 'objects.location_id_fk = locations.location_id_pk');
        $ads = $this->db->select('ad_id_pk,object_id_pk,postal_town,sublocality,
        ad_price,equipment_type.equipment_type_id_pk,equipment_name')->where(array('rel_user_ad.user_id_fk' => $userId))->get('ad')->result();

        echo $this->db->last_query();
//        $ads = $this->formateAds($ads);

    }

    public function newMessage()
    {
        $currentUserId = $this->user_model->getUserId();
        $data['time'] = date('H:i:s', time());

        $data['currentUserId'] = $currentUserId;
        if (isset($_POST['sourceUserId']))
            $sourceUserId = $this->input->post('sourceUserId', true);

        if (isset($_POST['type']))
            $type = $this->input->post('type', true);

        if (isset($_POST['message']))
            $message = $this->input->post('message', true);

        if (isset($sourceUserId) && isset($message) && isset($currentUserId)) {
            $userInfo = $this->user_model->getUserInfo($sourceUserId);
            $data['fbId'] = $userInfo[0]->fb_id;

            $data['firstname'] = $userInfo[0]->firstname;
            $data['lastname'] = $userInfo[0]->lastname;
            $data['sending'] = false;
            $data['type'] = 1;
            $data['userId'] = $sourceUserId;
            $data['time'] = date('H:i:s', time());
            $data['message'] = $message;
            $data['fbId'] = $userInfo[0]->fb_id;
            $this->load->view('content/chat/chat_message', $data);
        }
    }

    public function lastMessagesForMe()
    {
        $data['messageCount'] = $this->chat_model->getMessageCount();
        $data['lastMessages'] = $this->chat_model->getUserConversations();
        $this->load->view('content/chat/chat_last_messages', $data);
    }

    public function getMessages($conversationId)
    {
        $data['conversationInfo'] = $this->chat_model->getConversationInfo($conversationId);
        $data['messages'] = $this->chat_model->getMessages($conversationId, 10);
        $this->load->view('content/chat/chat_conversation', $data);
    }

    public function testObjectAd()
    {
        $this->load->model("map_model");
        $handle = fopen("adresy2.txt", "r");

        if ($handle) {
            $count = 0;
            while (($location = fgets($handle)) !== false) {
                //contains new ObjectID and new AdId
                $locationResult = $this->map_model->getAddress($location);
                if (!empty($locationResult)) {
                    $this->db->insert('locations', $locationResult);
                    $locationId = $this->db->insert_id();
                    echo $locationId;
                    $userId = rand(20, 2000);
                    if ($locationId != 0) {
                        //insert this object into DB
                        $this->db->insert('objects', Array(
                            "object_type" => rand(0, 2),
                            "ad_type" => rand(0, 2),
                            "creator_type" => rand(0, 2),
                            "walkthrough" => rand(0, 1),
                            "square_area" => rand(25, 120),
                            "location_id_fk" => $locationId
                        ));

                        $objectId = $this->db->insert_id();
                        $room_count = rand(1, 10);
                        //creates new ad for this object
                        $this->db->insert('ad', Array(
                            "object_id_fk" => $objectId,
                            "user_id_fk" => $userId,
                            "ad_title" => "Lorem ipsum",
                            "ad_body" => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis viverra diam non justo. Integer imperdiet lectus quis justo. Integer tempor. Nulla est. Integer lacinia. Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur? Phasellus enim erat, vestibulum vel, aliquam a, posuere eu, velit. Etiam dui sem, fermentum vitae, sagittis id, malesuada in, quam. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Phasellus enim erat, vestibulum vel, aliquam a, posuere eu, velit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In rutrum.",
                            "room_count" => $room_count,
                            "sex" => rand(0, 2),
                            "bail_boolean" => $count % 5 == 0 ? 1 : 0,
                            "bail" => $count % 5 == 0 ? rand(1500, 25000) : 0,
                            "ad_price" => rand(1500, 25000),
                            "max_people_count" => $room_count,
                            "active" => 1
                        ));

                        $adId = $this->db->insert_id();

//      insert REL_USER_OBJECT
                        $relArr = Array(
                            'user_id_fk' => $userId,
                            'object_id_fk' => $objectId,
                            'rel_type_id_fk' => CREATOR
                        );
                        $this->db->insert('rel_user_object', $relArr);

                        $idArr = Array(
                            "objectId" => $objectId,
                            "adId" => $adId
                        );
                        print_r($idArr);
                        mkdir(APPPATH . '../galleries/' . $idArr["objectId"], 0777, TRUE);
                        mkdir(APPPATH . '../galleries/' . $idArr["objectId"] . '/_thumbs/', 0777, TRUE);

                        echo json_encode($idArr, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        } else {
            echo "error";
        }
        fclose($handle);
    }

    public function testUsers()
    {
        $handle = fopen("users.txt", "r");
        if ($handle) {
            echo "<pre>";
            $count = 0;
            while (($line = fgets($handle)) !== false) {
                $user = explode(";", $line);
//                $count++;
                $randomTimestamp = rand(92620556, 1062055681);
                $date = date("Y-m-d", $randomTimestamp);
                $userArr = [
                    'firstname' => $user[0],
                    'lastname' => $user[1],
                    'gender' => $user[2],
                    'email' => $user[3],
                    'phone' => $user[4],
                    'password' => $user[5],
                    'birthday' => $date
                ];
                $this->db->insert("users", $userArr);
                $id = $this->db->insert_id();
                mkdir(APPPATH . '../profiles/' . $id, 0777, TRUE);
            }
        }
    }

    function testUserPhoto()
    {
        $directory = APPPATH . '../testphoto/users/';
        echo "<pre>";
        $handler = opendir($directory);

        $count = 1759;
        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..") {
                copy($directory . "/" . $file, APPPATH . '../profiles/' . $count . "/1.jpg");
                $count++;
            }
        }
        closedir($handler);
    }


    function testAdPhoto()
    {
        $directory = APPPATH . '../testphoto/apartments/';
        echo "<pre>";
        $handler = opendir($directory);

        $count = 1;
        $directoryNumber = 540;
        $fileNumber = 1;

        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..") {
                copy($directory . "/" . $file, APPPATH . '../galleries/' . $directoryNumber . "/" . $fileNumber . ".jpg");
                copy($directory . "/" . $file, APPPATH . '../galleries/' . $directoryNumber . "/_thumbs/" . $fileNumber . ".jpg");
                $count++;
                $fileNumber++;
                if ($count % 5 == 0) {
                    $directoryNumber++;
                    $fileNumber = 1;
                }
            }
        }

        closedir($handler);
    }

    public function testRelationships()
    {
        $query = "SELECT user_id_pk FROM users";
        $sql = $this->db->query($query);
        $userIds = $sql->result();

        $query = "SELECT ad_id_pk FROM ad";
        $sql = $this->db->query($query);
        $adIds = $sql->result();

//        $insertArr = [];
        $count = 0;
        for ($i = 0; $i < sizeof($adIds); $i++) {
            $randomInt = rand(0, 60);
            for ($j = 0; $j < $randomInt; $j++) {
                $insertArr = [
                    "ad_id_fk" => $adIds[$i]->ad_id_pk,
                    "user_id_fk" => rand(50, 1000)
                ];
                $this->db->insert("rel_user_ad", $insertArr);
                $count++;
            }
        }
    }

    public function testEquipment()
    {
        for ($i = 123; $i <= 671; $i++) {
            $randomInt = rand(0, 22);

            $random_equipment = $this->UniqueRandomNumbersWithinRange(5, 29, $randomInt);

            foreach ($random_equipment as $equipment) {
                $insertArr = [
                    "equipment_type_id_fk" => $equipment,
                    "ad_id_fk" => $i
                ];
                $this->db->insert("rel_ad_equipment", $insertArr);
            }
        }
    }

    function UniqueRandomNumbersWithinRange($min, $max, $quantity)
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    public function testUserRels()
    {
        $query = "SELECT user_id_pk FROM users";
        $sql = $this->db->query($query);
        $userIds = $sql->result();
        $language = rand(1, 4);
        $rand = rand(0, 3);
        $rand2 = rand(0, 3);
        foreach ($userIds as $userId) {
            for ($i = 0; $i < $rand; $i++) {
                $insertArr = [
                    "user_id_fk" => $userId->user_id_pk,
                    "language_id_fk" => ($i + $rand2) % 3 + 1
                ];
                $this->db->insert("rel_user_language", $insertArr);
            }
        }
    }

    public function maturita()
    {
        $handle = fopen("users.txt", "r");
        $url = [];
        if ($handle) {
            echo "<pre>";
            while (($line = fgets($handle)) !== false) {
                $user = explode(";", $line);
//                $count++;
                $randomTimestamp = rand(92620556, 1062055681);
                $date = date("Y-m-d", $randomTimestamp);
                $userArr = [
                    'firstname' => $user[0],
                    'lastname' => $user[1],
                    'gender' => $user[2],
                    'email' => $user[3],
                    'phone' => $user[4],
                    'password' => $user[5],
                    'birthday' => $date
                ];

                $rand = rand(1, 15);
                $s = "http://missmaturita.cz/doVote.asp?email=" . $userArr['email'] . "&name=" . $userArr['firstname'] . "&surname=" . $userArr['lastname'] . "&rec=5423&checkCnt=0&birth=" . $userArr['birthday'] . "&sex=male&home=Prague" . $rand . "&location=Prague" . $rand;
                array_push($url, $s);
            }
            shuffle($url);
            $u = $url[rand(0, sizeof($url))];

            echo $u . "<br><br>";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $u);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            echo $result;
        }
    }
}
