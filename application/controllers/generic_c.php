<?php

/**
 * Created for mojespolubydleni.cz
 * User: Adam Mrázek (Xquick), mrazek.adam@gmail.com
 * Date: 14.3.14
 * Time: 11:05
 */
class Generic_c extends CI_Controller
{

    protected $data;

    public function __construct()
    {
        parent::__construct();
        require_once('php_sdk/facebook.php');
        $this->data['meta'] = array('title' => SITE_NAME, 'keywords' => SITE_KEYWORDS, 'description' => SITE_DESC, 'image' => base_url('images/favicon.png'), 'url' => base_url($this->uri->uri_string()));
        $this->setLanguage();

    }

    protected function initViews()
    {
        $this->load->view('common/htmlheader', $this->data);
        $this->load->view('common/content', $this->data);
    }

    private function setLanguage()
    {
        $_langs = array('cz' => 'czech');
        $this->lang->load('general', $_langs['cz']);
    }

    protected function resizer($file, $path, $thumbpath, $w = 190, $h = 200)
    {
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $path . '/' . $file;
        $config['new_image'] = $thumbpath . '/' . $file;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $w;
        $config['height'] = $h;
        $config['thumb_marker'] = '';

        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
        echo $this->image_lib->display_errors();
    }

    protected function resizeOriginal($file, $path, $w, $h)
    {
        $this->resizer($file, $path, $path, $w, $h);
    }

    protected function scanDir($path)
    {
        $scan = scandir($path);
        return $scan;
    }

}