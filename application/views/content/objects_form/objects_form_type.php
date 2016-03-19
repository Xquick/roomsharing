<div class="object-section" data-id="1">
    <form id="form_object_new" action="/ad_c/saveObject" method="get">
        <div class="object-section-info">
            <div class="object-section-item">
                <h3><?php echo lang('object_new_information') ?></h3>

                <p><?php echo lang('object_new_information_basic') ?>.</p>
            </div>
        </div>
        <div class="object-section-content">

            <!--////////////////////////////////////////////////////-->
            <div class="object-subsection">
                <label><?php echo lang('object_new_insert_address') ?></label>
                <br>

                <div class="form-item" data-wizard="object-new-address" data-wizard-position="top">
                    <input type="text" name="object_location" class="address-input"/>
                    <?php $this->load->view("components/validationSign", ["lang" => "address"]); ?>
                </div>
            </div>
            <br>
            <br>

            <!--////////////////////////////////////////////////////-->
            <div class="object-subsection">
                <div class="relative">
                    <label>Typ nemovitosti</label>
                    <br>

                    <div class="step-column-thirds">
                        <label>Byt</label>
                        <label class="myCheckbox left">
                            <input type="radio" name="object_type" value="0"/>
                            <span></span>
                        </label>
                    </div>
                    <div class="step-column-thirds">
                        <label>Dům</label>
                        <label class="myCheckbox left">
                            <input type="radio" name="object_type" value="1"/>
                            <span></span>
                        </label>
                    </div>
                    <?php $this->load->view("components/validationSign", ["lang" => "mandatory"]); ?>
                </div>
            </div>
            <br>
            <br>

            <!--////////////////////////////////////////////////////-->
            <div class="object-subsection">
                <div class="relative">
                    <label>Typ nabídky</label>
                    <br>

                    <div class="step-column-thirds">
                        <label>Celý byt/ dům</label>
                        <label class="myCheckbox left">
                            <input type="radio" name="ad_type" value="0"/>
                            <span></span>
                        </label>
                    </div>
                    <div class="step-column-thirds">
                        <label>Soukromý pokoj</label>
                        <label class="myCheckbox left">
                            <input type="radio" name="ad_type" value="1"/>
                            <span></span>
                        </label>
                    </div>
                    <div class="step-column-thirds">
                        <label>Sdílený pokoj</label>
                        <label class="myCheckbox left">
                            <input type="radio" name="ad_type" value="2"/>
                            <span></span>
                        </label>
                    </div>
                    <?php $this->load->view("components/validationSign", ["lang" => "mandatory"]); ?>
                </div>
            </div>
            <br>
            <br>

            <!--////////////////////////////////////////////////////-->
            <div class="object-subsection">
                <div class="relative">
                    <label>Rozloha</label>
                    <br>

                    <div class="form-item" data-wizard="object-new-size" data-wizard-position="top">
                        <input type="text" name="object_area"
                               placeholder="<?php echo lang("insert_location"); ?>"/>
                        <span class="input-extra"><?php echo lang("square_area"); ?></span>
                        <?php $this->load->view("components/validationSign", ["lang" => "area"]); ?>
                    </div>
                </div>
            </div>
            <div class="object-subsection">
                <div class="relative">
                    <label>Jedná se o průchozí pokoj</label>
                    <label class="myCheckbox left">
                        <input type="checkbox" name="object_walkthrough">
                        <span></span>
                    </label>
                    <?php $this->load->view("components/validationSign", ["lang" => "walkthrough"]); ?>
                </div>
            </div>
            <br>
            <br>
            <!--////////////////////////////////////////////////////-->

            <div class="object-subsection">
                <div class="relative">
                    <label>Váš vztah k nemovitosti</label>
                    <br>

                    <div class="step-column-thirds">
                        <label>Majitel</label>
                        <label class="myCheckbox left">
                            <input type="radio" name="object_rel" value="0"/>
                            <span></span>
                        </label>
                    </div>
                    <div class="step-column-thirds">
                        <label>Realitka</label>
                        <label class="myCheckbox left">
                            <input type="radio" name="object_rel" value="1"/>
                            <span></span>
                        </label>
                    </div>
                    <div class="step-column-thirds">
                        <label>Spolubydlící</label>
                        <label class="myCheckbox left">
                            <input type="radio" name="object_rel" value="2"/>
                            <span></span>
                        </label>
                    </div>
                    <?php $this->load->view("components/validationSign", ["lang" => "mandatory"]); ?>
                </div>
            </div>
            <br>
            <br>
            <button name="object_new_submit" class="button-wide-light next-step">Vytvořit nabídku</button>
        </div>
    </form>
    <div class='object-message-success'>Gratulujeme, vytvořil jste základ Vaší nabídky</div>
</div>