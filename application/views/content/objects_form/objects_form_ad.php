<div class="object-section" data-id="2">

    <div class="object-section-info">
        <div class="object-section-item">
            <h3>Nabízené pokoje
                a místa</h3>

            <p>Počet pokojů a míst, které nabízíte.</p>
        </div>

        <div class="object-section-item">
            <h3>Požadavky na
                nájemníky</h3>

            <p>Preferované osoby na ubytování.</p>
        </div>
    </div>

    <div class="object-section-content">
        <!--/////////////////////////////////////////////////-->
        <div class="object-subsection">
            <label>Počet nabízených míst</label>
            <br>
            <select name="object_people_count">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo '<option';
                    if (isset($adDetail->max_people_count)) {
                        if ($i == $adDetail->max_people_count) {
                            echo ' selected';
                        }
                    }
                    echo '>' . $i . '</option>';
                }
                ?>
            </select>
        </div>
        <br>
        <br>

        <!--//////////////////////////////////////////////-->
        <div class="object-subsection">
            <label>Počet nabízených pokojů</label>
            <br>
            <select name="object_room_count">
                <?php
                for ($i = 1; $i <= 10; $i++) {
                    echo '<option';
                    if (isset($adDetail->room_count)) {
                        if ($i == $adDetail->room_count) {
                            echo ' selected';
                        }
                    }
                    echo '>' . $i . '</option>';
                }
                ?>
            </select>
            <br>
            <br>
        </div>

        <!--//////////////////////////////////////////////-->
        <div class="object-subsection">
            <label>Preference pohlaví</label>
            <br>

            <div class="step-column-thirds">
                <label>Nerozhoduje</label>
                <label class="myCheckbox left">
                    <input type="radio" name="object_sex" value="1" <?php if (isset($adDetail->sex)) {
                        if ($adDetail->sex == 1) {
                            echo "checked";
                        }
                    } ?>/>
                    <span></span>
                </label>
            </div>
            <div class="step-column-thirds">
                <label>Ženy</label>
                <label class="myCheckbox left">
                    <input type="radio" name="object_sex" value="2" <?php if (isset($adDetail->sex)) {
                        if ($adDetail->sex == 2) {
                            echo "checked";
                        }
                    } ?>/>
                    <span></span>
                </label>
            </div>
            <div class="step-column-thirds">
                <label>Muži</label>
                <label class="myCheckbox left">
                    <input type="radio" name="object_sex" value="3" <?php if (isset($adDetail->sex)) {
                        if ($adDetail->sex == 3) {
                            echo "checked";
                        }
                    } ?>/>
                    <span></span>
                </label>
            </div>
        </div>
        <br>
        <br>

        <!--//////////////////////////////////////////////-->
        <div class="object-subsection">
            <label>Preference věku</label>
            <br>

            <div class="object-age">
                <span>
                    <span class="price-slider-from"></span>
                </span>
                <input type="text" name="object_age" class="object-age-slider"  <?php if (isset($adDetail->age_from)) {
                    echo 'data-ageFrom ="' . $adDetail->age_from . '"';
                }
                if (isset($adDetail->age_to)) {
                    echo ' data-ageTo ="' . $adDetail->age_to . '"';
                } ?>
                    />
                <span>
                    <span class="price-slider-to"></span>
                </span>
            </div>
        </div>
    </div>
</div>