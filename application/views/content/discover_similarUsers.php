<?php
if (!empty($similarUsers)) {
    foreach ($similarUsers as $user): ?>
        <div class="">
            <?php $data['user'] = $user;
            $this->load->view('content/userCard', $data); ?>
        </div>
    <?php endforeach;
} else {
    ?>
    <div class="no-results">
        Na základě Vašeho filtru jsme nenašli
        <div class="big-text">žádné podobné uživatele.</div>
    </div>
<?php
}