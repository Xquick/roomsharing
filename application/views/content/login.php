<script type="application/javascript" src="/js/facebook.js"></script>

<div id="login">
    <div class="login-header">
        <h3><?php echo lang("login_header") ?></h3>
        <h4><?php echo lang("login_subheader"); ?></h4>
    </div>
    <div class="section-login">
        <div class="login-standart">
            <?php
            echo form_open('login_c/login');
            ?>
            <div class="login_email">
                <input type="email" name="login_email" placeholder="<?php echo lang("email"); ?>" id="login_email"/>
            </div>
            <div class="login_password">
                <input type="password" name="login_password" placeholder="<?php echo lang("password"); ?>"
                       id="login_password"/>
            </div>
            <?php
            echo form_submit('login', 'Login', "class=button-wide-light");
            echo form_close();
            ?>
        </div>
        <div class="login-facebook">
            <button class="button-facebook" onclick="facebookLogin()">
                <?php echo lang('login_facebook') ?>
            </button>
            <strong class="line-thru">or</strong>

            <div class="button-gplus">
                <?php echo lang("login_gplus"); ?>
            </div>
        </div>

    </div>
    <div class="section-registration">
        <span><?php echo lang('login_reg_text'); ?> <a href="#"> <?php echo lang('registration'); ?></a></span>
    </div>
</div>
