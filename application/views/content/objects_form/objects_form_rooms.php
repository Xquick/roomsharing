<div class="object-section" data-id="<?php echo $id; ?>">
    <div id="object-new-rooms">
        <div class="object-new-room-inner">
            <?php
            foreach ($roomTypes as $room): ?>
                <div class="object-new-rooms-item" data-id="<?php echo $room->room_id_pk; ?>">
                    <div class="object-new-rooms-type">
                        <label class="myCheckbox left">
                            <input type="checkbox" name="room-<?php echo $room->room_id_pk; ?>"
                            <?php
                            foreach ($tmpRooms as $tmpRoom) {
                                if ($room->room_id_pk == $tmpRoom->room_id_fk) {
                                    echo '/checked/';
                                } else {

                                }
                            }
                            ?>"/>
                            <span></span>
                        </label>
                        <label class="left room-item-name"
                               for="room-<?php echo $room->room_id_pk; ?>"><?php echo $room->room_name; ?></label>
                    </div>
                    <?php
                    ?>
                    <div class="object-new-rooms-count <?php
                    $val = 1;
                    for ($i = 0; $i < sizeof($tmpRooms); $i++) {
                        if ($room->room_id_pk == $tmpRooms[$i]->room_id_fk) {
                            echo 'visible';
                            $val = $tmpRooms[$i]->room_count;
                        }
                    } ?>">
                        <div class="count-minus">-</div>
                        <?php
                        echo form_input(array('name' => 'room-type' . $room->room_id_pk . '-count', 'value' => $val));?>
                        <div class="count-plus">+</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <br>
</div>