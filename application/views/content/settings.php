<div id="header">
    <div class="header-text">
        <h2><?php echo lang("settings_header"); ?></h2>

        <div class="subheader"><?php echo lang("settings_subheader"); ?></div>
    </div>
</div>
<div id="settings">

    <form action="user_c/saveProfilePicture" method="post" enctype="multipart/form-data">
        <input type="file" name="profile-photo">
        <input class="button-wide-light" type="submit" value="<?php echo lang("changepicture"); ?>">
    </form>

    <br>

    <form action="/login_c/logout">
        <input class="button-wide-light" type="submit" value="<?php echo lang("logout"); ?>">
    </form>
</div>