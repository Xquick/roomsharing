<h2><?php echo lang("suggested_header")?></h2>

<div><?php echo lang("suggested_subheader")?></div>
<div id="followed">

    <?php

    foreach ($suggestedAds as $ad):
        $data['post'] = $ad;
        $data['isFollowed'] = 0;
        foreach ($followedAds as $followedAd):
            if ($ad['ad_id_pk'] == $followedAd['ad_id_pk']) {
                $data['isFollowed'] = 1;
                break;
            }
        endforeach;
        $this->load->view('content/post', $data);
    endforeach;
    ?>
</div>
