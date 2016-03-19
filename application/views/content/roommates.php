<?php
$data['header'] = lang("roommates_header");
$data['subheader'] = lang("roommates_subheader");
$this->load->view("components/section_header", $data);
?>
<div id="roommates">
    <?php
    $this->load->view("components/card_new", $data);
    ?>

    <?php
    if (!empty($roommates)) {
        foreach ($roommates as $roommate):
            if ($roommate->answer == 1) {
                $data['user'] = $roommate;
                $this->load->view('content/userCard', $data);
            }
        endforeach;

        foreach ($roommates as $roommate):
            if ($roommate->answer == 0) {
                $data['user'] = $roommate;
                $this->load->view('content/userCard', $data);
            }
        endforeach;
    }
    ?>
</div>