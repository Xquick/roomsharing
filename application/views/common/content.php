<body>
<noscript>
    <div class="info-line"><?php echo lang("noscript"); ?></div>
    <div class="no-script-grayout"></div>
</noscript>
<div class="info-line please-login"><?php echo lang("pleaseLogin"); ?>
    <div class="button-close"></div>
</div>
<div id="main">
    <audio id="sound-new-message" type="audio/wav" src="/sounds/new_message.wav?<?php echo time(); ?>"></audio>
    <div id="inactive-area" class="scrollable"></div>
    <header id="head">
        <div id="menu">
            <?php $this->load->view('common/menu_side'); ?>
            <?php $this->load->view('common/menu_user'); ?>
        </div>
    </header>
    <div id="wrap" class="mCustomScrollbar" data-mcs-theme="dark">
        <div class="loader"></div>
        <?php
        foreach ($content as $c):
            $this->load->view('content/' . $c);
        endforeach;
        ?>
    </div>
    <!--    <div id="chatdoc-wrapper">-->
    <!--        <div id="chatdoc">-->
    <!---->
    <!--        </div>-->
    <!--    </div>-->
</div>
<div id="menu-right">
    <?php
    $this->load->view('content/map');
    //        if ($this->session->userdata('logged_in')) {
    //            $this->load->view('content/activity');
    //    }
    ?>

</div>

</body>
<footer>
    <div id="footer"><a href=""></a></div>
</footer>
</html>