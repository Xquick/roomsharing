<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = "site_c";
$route['404_override'] = '';

//view routing/////
$route['site_c/(:any)'] = "site_c/$1";
$route['site_c/(:any)/(:any)'] = "site_c/$1/$2";
///////////////////

//funkce USER_API controlleru
$route['user_api/(:any)'] = 'user_api/$1';
$route['user_api/(:any)/(:any)'] = "user_api/$1/$2";


//NICE URL
//default uživateslký profil
$route['user'] = 'site_c/userProfile';

//uživatel sekce ZPRÁVY
$route['user/messages'] = 'site_c/userProfile';
//uživatel sekce INFO
$route['user/info'] = 'site_c/userProfile';
//uživatel sekce SKUPINY
$route['user/groups'] = 'site_c/userProfile';

//uživateslké profily podle ID
$route['user/(:num)'] = 'site_c/userProfile/$1';
//získání konverzace podle ID
$route['user/conversation/(:num)'] = 'site_c/getMessages/$1';
//nová kampaň
$route['objects/(:num)/campaign/new'] = "site_c/adCreate/$1";
//nový objekt
$route['objects/new'] = "site_c/loadView/objects_new";
//editovat objekt
$route['objects/edit/(:num)'] = "site_c/objectEdit/$1";
//detail kampaně
$route['campaign/(:num)'] = "site_c/adDetail/$1";
$route['(:any)/campaign/(:num)'] = "site_c/adDetail/$2";
//filter view
$route['discover/filter'] = "site_c/filter";

$route['adroommates/(:num)'] = 'site_c/adRoommates/$1';

//funkce USER controlleru
$route['user_c/(:any)'] = "user_c/$1";
$route['user_c/(:any)/(:any)'] = "user_c/$1/$2";

//funkce LOGIN controlleru
$route['login_c/(:any)'] = "login_c/$1";
$route['login_c/(:any)/(:any)'] = "login_c/$1/$2";

//funkce CHAT controlleru
$route['chat_c/(:any)'] = 'chat_c/$1';
$route['chat_c/(:any)/(:any)'] = 'chat_c/$1/$2';

//funkce MAP controlleru
$route['map_c/(:any)'] = 'map_c/$1';

//funkce FORM controlleru
$route['form_c/(:any)'] = 'form_c/$1';

//funkce KAMPAŇ controlleru
$route['ad_c/(:any)'] = 'ad_c/$1';
$route['ad_c/(:any)/(:any)'] = "ad_c/$1/$2";

//nahrávání všech VIEW
$route['(:any)'] = "site_c/loadView/$1";





/* End of file routes.php */
/* Location: ./application/config/routes.php */