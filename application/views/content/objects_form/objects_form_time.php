<div class="object-section" data-id="4">
    <div class="object-section-info">
        <div class="object-section-item">
            <h3>Kalendář</h3>

            <p>Kdy je nabídka aktuální.</p>
        </div>
    </div>
    <div class="object-section-content">
        <!--    /////////////////////////////////////////////////-->
        <div class="object-subsection">
            <label>Dostupné od</label>

            <div class="form-item datepicker" data-wizard="filter-available-from" data-wizard-position="right">
                <input type="text" name="object_available_from" id="object-available-from" placeholder="Zvolte datum"
                       value="<?php if (isset($adDetail->available_from)) {
                           echo date("m/d/Y", strtotime($adDetail->available_from));
                       } ?>"/>
            </div>
        </div>

        <!--    /////////////////////////////////////////////////-->
        <div class="object-subsection">
            <label>Dostupné do</label>

            <div class="form-item" data-wizard="filter-available-to" data-wizard-position="right">
                <input type="text" name="object_available_to" id="object-available-to" placeholder="Zvolte datum"
                       value="<?php if (isset($adDetail->available_to)) {
                           echo date("m/d/Y", strtotime($adDetail->available_to));
                       } ?>"/>
            </div>
        </div>
    </div>
</div>