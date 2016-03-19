<?php

/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 4.5.14
 * Time: 2:34
 */


//require_once(APPPATH . "libraries/Facebook/autoload.php");

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

class Login_c extends CI_Controller
{
    public function Login_c()
    {
        parent::__construct();
        $this->user = null;
        $this->session = null;
    }

    public function facebookLogin()
    {
        session_start();
        $appId = '570643943043167';
        $secret = 'bc29fdb6a5c98ba3120bc40b86e443e3';
        $domain = 'http://roomsharing.localhost/';
        $redirect = $domain . 'login_c/facebookLogin/';
        $permissions = array(
            'email'
//            'user_location',
//            'user_birthday'
        );

        FacebookSession::setDefaultApplication($appId, $secret);

        $helper = new FacebookRedirectLoginHelper($redirect);

        print_r($_SESSION);

        // Check if existing session exists
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            // Create new session from saved access_token
            $this->session = new FacebookSession($_SESSION['fb_token']);

            $this->getUserInfo();
            $this->getUserPicture();
            $this->processFacebookLogin();

            // Validate the access_token to make sure it's still valid
            try {
                if (!$this->session->validate()) {
                    $this->session = null;
                }
            } catch (Exception $e) {
                // Catch any exceptions
                $this->session = null;
            }
        } else {
            // No session exists
            try {
                $this->session = $helper->getSessionFromRedirect();
            } catch (FacebookRequestException $ex) {
                // When Facebook returns an error
            } catch (Exception $ex) {
                // When validation fails or other local issues
            }
        }

        if (isset($this->session)) {

            // Save the session
            $_SESSION['fb_token'] = $this->session->getToken();

            // Create session using saved token or the new one we generated at login
            $this->session = new FacebookSession($this->session->getToken());
            $this->getUserInfo();
            $this->getUserPicture();

            $this->processFacebookLogin();
            // Create the logout URL (logout page should destroy the session)
            $_SESSION["logout_url"] = $helper->getLogoutUrl($this->session, $domain);
        } else {
            // No session
            // Get login URL
            $loginUrl = $helper->getLoginUrl(array(
                'display' => 'popup',
                'next' => $domain,
                'redirect_uri' => $domain,
                'scope' => $permissions
            ));
            redirect($loginUrl);
//            echo " <a href = " . $helper->getLoginUrl($permissions) . " > Login</a> ";
        }
    }

    /**
     * Get User’s Profile Picture
     */
    public function getUserPicture()
    {

        // Graph API to request profile picture
        $request = (new FacebookRequest($this->session, 'GET', '/me/picture?type=large&redirect=false'))->execute();

        // Get response as an array
        $picture = $request->getGraphObject()->asArray();

        return $picture;
    }

    public function getUserInfo()
    {
        $request = (new FacebookRequest($this->session, 'GET', '/me'))->execute();

        $this->user = $request->getGraphObject()->asArray();
        return $this->user;
    }


    /**
     * Function login
     *
     * P?ihlásí uživatele, vytvo?ením nové session s jeho údaji.
     * <ul>
     * <li><b> logged_in <b>- údaj, že uživatel je p?ihlášen </li>
     * <li><b> email <b>- uživatel?v email </li>
     * <li><b> firstname <b>- k?estní jméno uživatele </li>
     * <li><b> lastname <b>- p?íjmení uživatele </li>
     *</ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function login()
    {
        if (isset($_POST["login_email"]) && $_POST["login_password"]) {

            $email = $this->input->post('login_email', true);
            $password = $this->input->post('login_password', true);
            if ($this->user_model->login($email, $password)) {
                $this->user_model->startSession($email);
                redirect('/');
            };
        }
        redirect('/login');
    }


    /**
     * Function logout
     *
     *  odhlásí uživatele zrušením aktuální session.
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */

    public function logout()
    {
        session_start();
        session_destroy();
        redirect('/');
    }

    public function process()
    {
        if (isset($_POST['data'])) {
            $data = $this->input->post("data");
            $email = $data["email"];
            $firstname = $data["first_name"];
            $lastname = $data["last_name"];
            $gender = $data["gender"];
            $fb_id = $data["id"];
        }
        $userArr = array(
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'gender' => $gender,
            'fb_id' => $fb_id,
            'logged_in' => TRUE
        );
        $sql = $this->db->select('user_id_pk')->where('fb_id', $fb_id)->get('users');
        if ($sql->num_rows() < 1) {
            $this->db->insert('users', $userArr);
        }
        $this->user_model->startSession($email);
        redirect('/');
    }

    public function friends()
    {
        $friends = $this->facebook->api('me/taggable_friends');
        var_dump($friends);
    }

    public function isLogged()
    {
        if ($this->session->user_data('logged_in'))
            return true;
        return false;
    }
}