<h2>Followed</h2>

<div>Your favourite apartments</div>
<div id="followed">

    <?php
    $count = 0;
    $data['isFollowed'] = 1;
    foreach ($followedAds as $ad):
        $data['post'] = $ad;
        $this->load->view('content/post', $data);
    endforeach; ?>
</div>
