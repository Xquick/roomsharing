<?php

/**
 * Created for mojespolubydleni.cz
 * User: Adam Mrázek (Xquick), mrazek.adam@gmail.com
 * Date: 14.3.14
 * Time: 11:05
 */
require_once(APPPATH . 'controllers/generic_c.php');

class User_c extends Generic_c
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('map_model');
        $this->load->model('ad_model');
    }

    public function login()
    {
        $email = $this->input->post('login_email');
        $password = $this->input->post('login_password');
        if ($this->user_model->login($email, $password)) {
            $this->user_model->startSession($email);
        };
        redirect('');
    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('');
    }

    public function setFilter()
    {
        $area = $this->input->post('filter_area');
        $price_from = $this->input->post('filter_price_from');
        $price_to = $this->input->post('filter_price_to');
        $this->user_model->setFilter($area, $price_from, $price_to);
        redirect('discover');
    }

    public function getFormattedFilter()
    {
        $this->user_model->getFormattedFilter();
    }

    public function follow($object)
    {
        if ($this->user_model->follow($object)) {
            echo "1";
        } else {
            echo "0";
        }
    }

    public function unfollow($object)
    {
        if ($this->user_model->unfollow($object)) {
            echo "1";
        } else {
            echo "0";
        }
    }

    public function upload($id, $path = "galleries/", $files = "files")
    {
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('max_input_time', 300);
        ini_set('max_execution_time', 300);

        $image_path = realpath(APPPATH . '../' . $path . "/" . $id);
        $config['upload_path'] = $image_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '10000';
        $upload_config['remove_spaces'] = true;
        $this->upload->initialize($config);
        $uploaded = Array();
        $imageArr = Array();

        if (isset($_FILES[$files])) {
            //Loop through each file
            $count = 0;

            //rename images to 1,2,3,4,5....
            for ($i = 0; $i < sizeof($_FILES['files']['name']); $i++) {
                $ext = pathinfo($_FILES['files']['name'][$i], PATHINFO_EXTENSION);
                $_FILES['files']['name'][$i] = $i + 1 . '.' . $ext;
            }
            foreach ($_FILES[$files]["name"] as $key => $name) {
                $count++;
                if ($_FILES[$files]["error"][$key] == 0 && move_uploaded_file($_FILES[$files]["tmp_name"][$key], $image_path . "/" . $name)) {
                    array_push($uploaded, "/" . $path . "/" . $id . "/" . $name);
                    array_push($imageArr, $name);
                }
            }
            foreach ($imageArr as $image) {
                $this->resizeOriginal($image, $image_path, 800, 600);
                $this->resizer($image, $image_path, $image_path . '/_thumbs/', 240, 174);
            }
        }
        echo json_encode($uploaded, JSON_UNESCAPED_UNICODE);
    }

    public function uploadImage($imageId)
    {
        echo $imageId;
        $this->upload('files', '/galleries/' . $imageId);
    }

    public function deleteImage($objectId, $imageId)
    {
        $userId = $this->user_model->getUserId();
        if (is_file(APPPATH . '../galleries/' . $objectId . '/' . $imageId . ".jpg"))
            unlink(APPPATH . '../galleries/' . $objectId . '/' . $imageId . ".jpg");
        if (is_file(APPPATH . '../galleries/' . $objectId . '/_thumbs/' . $imageId . ".jpg"))
            unlink(APPPATH . '../galleries/' . $objectId . '/_thumbs/' . $imageId . ".jpg");
    }


    public function getUserObjects()
    {
        $this->user_model->getUserObjects();
    }

    public function saveProfilePicture()
    {
//        $this->upload('');
        redirect('settings');
    }

    public function saveTmpRooms()
    {
        $roomArr = json_decode($this->input->post('tmpRooms'));
        $this->user_model->saveTmpRooms($roomArr);
    }

    public function removeTmpRoom()
    {
        $roomId = $this->input->get('roomId');
        $this->user_model->removeTmpRoom($roomId);
    }

    public function pairUserObject()
    {
        $objectId = $this->input->post('objectId');
        $lat = $this->input->post('lat');
        $lng = $this->input->post('lng');
        $inputFloor = $this->input->post('inputFloor');
        $this->user_model->pairUserObject($objectId, $inputFloor, $lat, $lng);
        redirect('objects');
    }

    public function sendMessage()
    {
        $targetUserId = $this->input->post('target_user');
        $message = $this->input->post('message');
        $this->user_model->sendMessage($targetUserId, $message);
        redirect('user/' . $targetUserId);
    }

    public function getMessageCount()
    {
        echo $this->user_model->getMessageCount();
    }

    public function setMessageStatus()
    {
        $status = $this->input->get('status');
        $conversationId = $this->input->get('conversation_id');
        echo $this->user_model->setMessageStatus($conversationId, $status);
    }

    public function getSimilarUsers()
    {
        $this->user_model->getSimilarUsers();
    }

    public function unlinkObject($objectId)
    {
        $this->object_model->unlinkObject($objectId);
    }

//
//    public function getObjectRooms()
//    {
//        $objectId = $this->input->get('objectId');
//        $rooms = $this->object_model->getObjectRooms($objectId);
//        return $rooms;
//    }

}