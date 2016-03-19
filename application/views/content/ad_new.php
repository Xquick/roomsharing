<div id="object-ad-new" data-id="<?php echo $objectId; ?>" class="shadow-right scrollable right-window">
    <?php $this->load->view("components/button_close.php"); ?>
    <h3>Propagujte svůj byt</h3>

    <div class="top-line"></div>

    <?php
    $stepNum = 1;
    $contentArr = array(
        array('content' => 'content/campaign_form/campaign_form_select',
            'header' => 'Vyberte místnosti, na kterých bude spuštěna kampaň'),
        array('content' => 'content/campaign_form/campaign_form_rooms', 'header' => 'Popis jednotlivých místností'),
        array('content' => 'content/campaign_form/campaign_form_title', 'header' => 'Název a popis nabídky'),
        array('content' => 'content/campaign_form/campaign_form_submit', 'header' => 'Potvrzeni')
    );
    foreach ($contentArr as $c):?>
        <div data-step-id="<?php echo $stepNum; ?>"
             class="new-step-<?php echo $stepNum; ?> new-step <?php if ($stepNum != 1) echo 'folded'; ?> overflow">
            <div class="fullWidth pointer"><h4>KROK <?php echo $stepNum . ' - ' . $c['header']; ?></h4></div>
            <?php $this->load->view($c['content']);
            $stepNum++; ?>
        </div>
        <div class="top-line"></div>
    <?php endforeach; ?>
</div>