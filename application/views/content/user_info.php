<?php
?>
<div class="user-info-detail">
    <div class="user-info-student">
        Student:
        <?php if ($user_info[0]->student) {
            echo 'ANO';
        } else {
            echo 'NE';
        } ?>
    </div>
    <?php foreach ($user_settings as $setting): ?>
        <div class="user-info-setting-item">
            <?php switch ($setting->name) {
                case 'area':
                    echo 'Vyhledávaná lokace: ';
                    echo $setting->value_char;
                    break;
                case 'price_from':
                    echo 'Cena od: ';
                    echo $setting->value_int;
                    break;
                case 'price_to':
                    echo 'Cena do: ';
                    echo $setting->value_int;
                    break;
            } ?>

        </div>
    <?php endforeach; ?>
</div>