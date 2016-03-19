<div class="object-section" data-id="3">
    <div class="object-section-info">
        <div class="object-section-item">
            <h3>Cenové podmínky</h3>

            <p>Cenové podmínky nabídky za osobu na měsíc.</p>
        </div>
    </div>
    <div class="object-section-content">

        <!--////////////////////////////////////////////////////-->
        <div class="object-subsection">
            <label>Cena za nabídku</label>

            <div class="form-item">
                <div class="relative">
                    <input type="text" name="object_price" placeholder="Zadejte cenu"
                           value="<?php if (isset($adDetail->ad_price)) echo $adDetail->ad_price; ?>"/>
                    <span class="input-extra"><?php echo lang("currency"); ?></span>
                </div>
            </div>
        </div>
        <br>

        <!--////////////////////////////////////////////////////-->

        <div class="object-subsection">
            <label>Je požadována kauce</label>
            <label
                class="myCheckbox left">
                <input type="checkbox"
                       name="object_bail_bool" <?php if (isset($adDetail->bail_boolean)) if ($adDetail->bail_boolean == 1) echo "checked"; ?>/>
                <span></span>
            </label>
        </div>
        <br>

        <!--////////////////////////////////////////////////////-->
        <div
            class="object-subsection  <?php if (isset($adDetail->bail_boolean)) if ($adDetail->bail_boolean == 0) echo "hide"; ?>"
            data-subsection="bail">
            <label>Výše kauce</label>

            <div class="form-item">
                <div class="relative">
                    <input type="text" name="object_bail"
                           value=" <?php if (isset($adDetail->bail)) echo $adDetail->bail; ?>"/>
                    <span class="input-extra"><?php echo lang("currency"); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

