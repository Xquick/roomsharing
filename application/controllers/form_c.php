<?php

class Form_c extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Function verifyForm
     *
     * vrací true nebo chybovou hlášku podle zadaných GET parametrů
     *
     * <ul>
     * <li>GET<b> form_item </b> - pole formuláře
     * <h4>Možné vstupy</h4>
     * <ul>
     *      <li><b> filter_price </b>- cena (0<= VALUE < MAX_INT) </li>
     *      <li><b> object_floor_varification </b>- (-2<= VALUE < 50) </li>
     *      <li><b> object_floor </b>-  (-2<= VALUE < 50)</li>
     *      <li><b> object_floor_area </b>-  (1<= VALUE < 1000)</li>
     *      <li><b> object_reconstruction_date </b>-  (day(1-31), month(1-12), year(YYYY))</li>
     *      <li><b> object_floor_count </b>- (1<= VALUE < 50) </li>
     * </ul>
     * </li>
     * <li>GET<b> content </b> - obsah formuláře (kontrolovaná hodnota)</li>
     * </ul>
     *
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function verifyForm()
    {
        $item = $this->input->get('form_item');
        $content = $this->input->get('content');
        switch ($item) {
            case 'filter_price':
                if (is_numeric($content) && $content >= 0 && $content < PHP_INT_MAX) {
                    $message = true;
                } else {
                    $message = 'Cena musí být nezáporná číselná cifra rozumné maximální hodnoty.';
                }
                break;
            case 'object_floor_varification':
                if (!empty($content) && is_numeric($content)) {
                    if ($content >= -2 && $content < 50) {
                        $message = true;
                    }
                } else {
                    $message = 'Podlaží musí být číslo od -2 do 50.';
                }
                break;
            case 'object_floor':
                if (!empty($content) && is_numeric($content)) {
                    if ($content >= -2 && content < 50) {
                        $message = true;
                    }
                } else {
                    $message = 'Podlaží musí být číslo od -2 do 50.';
                }
                break;
                break;
            case 'object_floor_area':
                if (!empty($content) && is_numeric($content)) {
                    if ($content > 0 && $content < 1000) {
                        $message = true;
                    }
                } else {
                    $message = 'Podlaží musí být číslo od 1 do 1000.';
                }
                break;
            case 'object_reconstruction_date':
                if (!empty($content)) {
                    $date = date_parse($content);
                    if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"]))
                        $message = true;
                    else
                        $message = "Neplatné datum";
                } else {
                    $message = "Neplatné datum";
                }
                break;
            case 'object_floor_count':
                if (!empty($content) && is_numeric($content)) {
                    if ($content >= -2 && $content < 50) {
                        $message = true;
                    }
                } else {
                    $message = 'Počet podlaží musí být číslo od 1 do 50.';
                }
                break;
            default:
                $message = "Spatna data.";
        }
        echo json_encode($message, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Function getFormInfo
     *
     * vrací informační hlášku podle zadaných GET parametrů Slouží k zjištění doplňujících informací o daném formulářovém poli.
     * <ul>
     * <li>GET<b> form_item </b> - pole formuláře
     * <h4>Možné vstupy</h4>
     * <ul>
     *      <li><b> object_address </b>- adresa objektu </li>
     *      <li><b> object_floor_varification </b>- kontrola, zda uživatelobjekt, se kterým se chce spojit skutečně zdá, Potvrzení podle zadaného podlaží </li>
     *      <li><b> object_floor </b>- podlaží objektu </li>
     *      <li><b> object_floor_area </b>- rozloha objektu v metrech čtverečných</li>
     *      <li><b> object_reconstruction_date </b>- datum rekonstrukce objektu</li>
     *      <li><b> object_floor_count </b>- počet pater objektu</li>
     *      <li><b> object_select_photos </b>- vložené fotografie </li>
     * </ul>
     * </li>
     * </ul>
     * @author Bc.Adam Mrázek - mrazek.adam@gmail.com
     */
    public function getFormInfo()
    {
        $item = $this->input->get('form_item');

        switch ($item) {
            case 'object_address':
                $message = 'Adresa nebude nikde zveřejněna. Našeptávač Vám ulehčí práci. Adresa musí být přesná. ';
                break;
            case 'object_floor_verification':
                $message = 'Kolikáté patro, ověření, zda jde skutečně o byt který znáte.';
                break;
            case 'object_floor':
                $message = 'Zadejte v jakém patře se nachází Váš byt, v případě domu nechte prázdné.';
                break;
            case 'object_floor_area':
                $message = 'Zadejte rozlohu vašeho bytu či domu v metrech čtverečných.';
                break;
            case 'object_reconstruction:date':
                $message = 'Zadejte datum, kdy naposledy proběhla ve vašem bytě rekonstrukce. V případě že byt není rekonstruován, nechte pole prázdné';
                break;
            case 'object_heat_type':
                $message = 'Zvolte typ topení. Elektrické: akumulační kamna, přímotopy. Plynové: vytápění kotlem, radiátory.';
                break;
            case 'object_construction_type':
                $message = 'Zvolte typ konstrukce vyšeho bytu či domu. Panelový: klasické byty v panelových bytovkách. Cihlový: zvolte v případě domu, či cihlového bytu.';
                break;
            case 'object_floor_count':
                $message = 'Zadejte počet pater, které Váš dům má. V případě klasického bytu s jedním patrem zadejte 1.';
                break;
            case 'object_select_photos':
                $message = 'Vložte prosím minimálně 5 fotografií Vašeho bytu. V případě většího objektu Vás poprosíme o minimálně 2 fotografie každé místnosti.';
                break;
            default:
                $message = 'Špatný prvek formuláře';
        }
        echo json_encode($message);
    }

}