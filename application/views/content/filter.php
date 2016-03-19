<div id="user-filter">
<?php echo form_open('ad_c/filter', array('id' => 'filter'));
?>
<div class="filter-quick">
    <div class="filter-more-button">
        <button class="button-wide-light" name="filter-more">
            <?php echo lang('filter_more'); ?>
        </button>
    </div>

    <?php
    $arr = array(
        'name' => 'filter_area',
        'class' => 'address-input'
    );
    ?>
    <div class="filter-location" data-wizard="filter-address" data-wizard-position="left">
        <?php
        echo form_input($arr);
        ?>
    </div>

    <div class="filter-price-slide">
        <span><span class="price-slider-from"></span><?php echo lang("currency"); ?></span>
        <input type="text" class="price-slider" data-price-from="<?php
        if (array_key_exists('price_from', $formattedFilter)) {
            echo $formattedFilter['price_from'];
        }
        ?>" data-price-to="<?php
        if (array_key_exists('price_from', $formattedFilter)) {
            echo $formattedFilter['price_to'];
        }
        ?>">
            <span><span class="price-slider-to"></span><?php echo lang("currency"); ?><span>
    </div>
    <div class="filter-bail">
        <div class="filter-item">
            <label>
                <label class="myCheckbox left">
                    <input type="checkbox" name="filter_bail_boolean"
                           value="2"><?php echo lang("filter_bail_boolean"); ?>
                    <span class="left"></span>
                </label>
            </label>
        </div>
    </div>
</div>
<div class="filter-more">
    <div class="filter-sections">
        <ul>
            <li class="active" data-filter-source="0"><?php echo lang('filter_object') ?></li>
            <li class="" data-filter-source="1"><?php echo lang('filter_people') ?></li>
            <li class="" data-filter-source="2"><?php echo lang('filter_term') ?></li>
            <li class="" data-filter-source="3"><?php echo lang('filter_equipment') ?></li>
            <li class=" " data-filter-source="4"><?php echo lang('filter_dimensions') ?></li>
        </ul>
    </div>


    <div class="filter-more-content-wrapper">
        <div class="filter-more-content">

            <!--            AD SECTION-->
            <section class="filter-more-content-item active" data-filter-target="0">
                <div>
                    <h4>Typ pokoje</h4>

                    <div class="filter-item">
                        <label>
                            <label class="myCheckbox left">
                                <input type="checkbox" name="filter_object_type"
                                       value="1"> <?php echo lang("filter_object_type_1"); ?>
                                <span class="left"></span>
                            </label>
                        </label>
                    </div>
                    <div class="filter-item">
                        <label>
                            <label class="myCheckbox left">
                                <input type="checkbox" name="filter_object_type"
                                       value="2"><?php echo lang("filter_object_type_2"); ?>
                                <span class="left"></span>
                            </label>
                        </label>
                    </div>
                    <div class="filter-item">
                        <label>
                            <label class="myCheckbox left">
                                <input type="checkbox" name="filter_object_type"
                                       value="3"><?php echo lang("filter_object_type_3"); ?>
                                <span class="left"></span>
                            </label>
                        </label>
                    </div>
                </div>
                <br>
                <br>
                <br>

                <div>
                    <h4>Typ nemovitosti</h4>

                    <div class="filter-item">
                        <label>
                            <label class="myCheckbox left">
                                <input type="checkbox" name="filter_ad_type"
                                       value="1"><?php echo lang("filter_ad_type_1"); ?>
                                <span class="left"></span>
                            </label>
                        </label>
                    </div>
                    <div class="filter-item">
                        <label>
                            <label class="myCheckbox left">
                                <input type="checkbox" name="filter_ad_type"
                                       value="2"><?php echo lang("filter_ad_type_2"); ?>
                                <span class="left"></span>
                            </label>
                        </label>
                    </div>
                </div>
            </section>

            <!--        PEOPLE SECTION-->
            <section class="filter-more-content-item" data-filter-target="1">
                <h4>Pohlaví</h4>

                <div class="filter-item">
                    <label>
                        <label class="myCheckbox left">
                            <input type="checkbox" name="filter_sex" value="1">
                            <span class="left"></span>
                            <?php echo lang("filter_sex_type_1"); ?>
                        </label>
                    </label>
                </div>
                <div class="filter-item">
                    <label class="myCheckbox left">
                        <label>
                            <input type="checkbox" name="filter_sex" value="2">
                            <span class="left"></span>
                            <?php echo lang("filter_sex_type_2"); ?></label>
                </div>
                <div class="filter-item">
                    <label>
                        <label class="myCheckbox left">
                            <input type="checkbox" name="filter_sex" value="3">
                            <span class="left"></span>
                            <?php echo lang("filter_sex_type_3"); ?>
                        </label>
                    </label>
                </div>
            </section>

            <!--        TIME SECTION-->
            <section class="filter-more-content-item" data-filter-target="2">
                <h4><?php echo lang("filter_availability_header"); ?></h4>

                <div class="form-item datepicker" data-wizard="filter-available-from" data-wizard-position="right">
                    <label><?php echo lang("filter_available_from"); ?>
                        <br><input
                            name="filter_available_from" id="filter-available-from" type="text"
                            placeholder="<?php echo lang("insert_date_from"); ?>"/>
                    </label>
                </div>

                <div class="form-item" data-wizard="filter-available-to" data-wizard-position="right">
                    <label><?php echo lang("filter_available_to"); ?>
                        <br><input name="filter_available_to" id="filter-available-to" type="text"
                                   placeholder="<?php echo lang("insert_date_to"); ?>"/>
                    </label>
                </div>
            </section>

            <!--            EQUIPMENT SECTION-->
            <section class="filter-more-content-item" data-filter-target="3">

                <h4><?php echo lang("filter_equipment_header"); ?></h4>
                <?php
                foreach ($equipment as $e) {
                    echo '<div class="filter-item">
                                <label>
                                    <label class="myCheckbox left">
                                        <input name="filter_equipment" type="checkbox" value="' . $e->equipment_type_id_pk . '">' . $e->equipment_name . '
                                        <span class="left"></span>
                                    </label>
                              </div>';
                }
                ?>
            </section>

            <!--            SQUARE AREA SECTION-->
            <section class="filter-more-content-item" data-filter-target="4">
                <h4><?php echo lang("filter_square_area_header"); ?></h4>

                <div>
                    <label class="relative"><?php echo lang("filter_square_area"); ?>
                        <input type="text" name="filter_square_area"
                               placeholder="<?php echo lang("insert_location"); ?>">
                        <span class="input-extra"><?php echo lang("square_area"); ?></span>
                    </label>
                </div>
            </section>
        </div>
    </div>
</div>
<?php
echo form_close();
?>

<div class="tag-base-wrapper">
    <div class="tag-base"></div>
</div>
</div>