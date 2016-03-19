<?php if (empty($filterResult)) {
    ?>
    <div class="no-results">
        <?php echo lang("no_results"); ?>
    </div>
<?php
}?>
<?php
foreach ($filterResult as $item):
    $data['post'] = $item;
    $data['isFollowed'] = 0;
    foreach ($followedAds as $followedAd):
        if ($item['ad_id_pk'] == $followedAd['ad_id_pk']) {
            $data['isFollowed'] = 1;
            break;
        }
    endforeach;
    $this->load->view('content/post', $data);
endforeach;
if (sizeof($filterResult) >= 10) {
    ?>
    <div class="more-results">
        <?php echo lang("more_results"); ?>
    </div>
<? } ?>
