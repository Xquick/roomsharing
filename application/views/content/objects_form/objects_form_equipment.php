<div class="object-section" data-id="5">


    <?php $equipmentTypes = array(
        array('name' => 'Obecné vybavení', 'id' => 0),
        array('name' => 'Vybavení pokoje', 'id' => 4),
        array('name' => 'Vybavení kuchyn?', 'id' => 6),
        array('name' => 'Vybavení koupelny', 'id' => 3),
        array('name' => 'Vybavení obývacího pokoje', 'id' => 2)
    );
    ?>

    <?php foreach ($equipmentTypes as $type): ?>
        <div class="object-subsection">
            <div class="object-section-info">
                <h5><?php echo $type['name']; ?></h5>
            </div>
            <div class="object-subsection-content">
                <?php foreach ($equipment as $item): ?>
                    <?php if ($item->room_id_fk == $type['id']): ?>
                        <div class="step-column-thirds">
                            <div class="object-new-equipment-item"
                                 data-id="<?php echo $item->equipment_type_id_pk; ?>">
                                <?php echo form_label($item->equipment_name); ?>
                                <label
                                    class="myCheckbox left">
                                    <input type="checkbox"
                                           name="equipment-<?php echo $item->equipment_type_id_pk; ?>"
                                        <?php
                                        if (isset($ad_equipment))
                                            foreach ($ad_equipment as $e) {
                                                if ($e->equipment_type_id_pk == $item->equipment_type_id_pk) {
                                                    echo '/checked/';
                                                }
                                            }
                                        ?>/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>
    <?php
    endforeach;
    echo form_close(); ?>
</div>
