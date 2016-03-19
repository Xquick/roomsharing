<div id="header">
    <div class="header-text">
        <h2><?php echo lang("objects_header"); ?></h2>

        <div class="subheader"><?php echo lang("objects_subheader"); ?></div>
    </div>
    <?php
    $attributes = array(
        'class' => 'header-button button-wide',
        'name' => 'object-new',
        'content' => 'Nová nabídka'
    );
    echo form_button($attributes);?>
</div>

<div id="objects">
    <?php $arr = array();
    if (!empty($userObjects)) {
        foreach ($userObjects as $object):?>
            <?php
            foreach ($object->ad as $ad): ?>
                <div class="post"
                     data-lat="<?php echo $object->lat; ?>"
                     data-lng="<?php echo $object->lng; ?>"
                     data-id="<?php echo $object->object_id_pk; ?>"
                     data-adid="<?php echo $ad->ad_id_pk; ?>">
                    <div class="loader"></div>
                    <div class="post-icons">
                        <div class="card-error not-complete">
                            <div class="card-info-text left"><?php echo lang("not_complete"); ?></div>
                            <div class="icon-small icon-dislike right"></div>
                        </div>
                        <div class="post-icons-inner">
                            <span class="right followers_num"
                                  title="počet lidí, kteří mají tuto nabídku v oblíbených"> <?php echo $ad->ad_followers; ?></span>

                            <span class="item-active icon-medium">
                               <a href="ad_c/toggleActive/<?php echo $ad->ad_id_pk; ?>" class="ajax">
                                   <span class="<?php
                                   if ($ad->active == 1) {
                                       echo 'icon-active';
                                   } else {
                                       echo 'icon-inactive';
                                   }
                                   ?>"></span>
                               </a>
                            </span>
                            <span class="icon-edit"></span>
                        </div>
                    </div>
                    <div class="post-photo top-line">
                        <div class="post-photo-inner">
                            <img src="galleries/<?php echo $object->object_id_pk; ?>/1.jpg" alt=""/>
                        </div>
                        <div class="post-description">
                            <!--    <div class="post-date">--><?php //echo $post->date_inserted; ?><!--</div>-->
                            <div class="info-description">
                                <div class="info-description-location">
                                   <span class="locality"> <?php
                                       echo $object->sublocality_level_1;
                                       ?>  </span>
                                    <span class="route">
                                        <?php
                                        if (!empty($object->route))
                                            echo ' - ' . $object->route;
                                        ?>
                                    </span>
                                </div>
                                <div class="info-description-price">
                                    <?php echo $ad->ad_price . ',-'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endforeach;
    } else {
        ?>
        <div class="no-results">
            Nemáte žádné vlastní byty,
            <div class="big-text">musíte je vytvořit.</div>
        </div>
    <?php } ?>
    <!--    --><?php //$this->load->view('content/objects_new'); ?>
    <!--    --><?php //$this->load->view('content/ad_new'); ?>
</div>
