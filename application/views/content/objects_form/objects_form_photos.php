<div class="object-section" data-id="6">
    <div class="object-subsection">
        <div class="object-subsection-content">
            <?php echo form_open_multipart('user_api/uploadImage/', array('id' => 'fileupload')); ?>
            <div class="fileUpload">
                <div class="button-wide-light">+ Vyberte fotky</div>
                <input name="files[]" id="fileupload-input" type="file" data-url="server/php/" multiple
                       class="upload pointer"/>
            </div>
            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>
            <br>

            <div class="upload-image-preview">
                <?php if (isset($ad_gallery) && sizeof($ad_gallery) > 0) {
                    foreach ($ad_gallery as $photo) {
                        echo '<div class="upload-image-preview-item">' .
                            '<img src="/galleries/' . $adDetail->object_id_fk . "/" . $photo . '">' .
                            '<div class="object-showcase-item-selection"></div>' .
                            '</div>';
                    }
                } else {
                    echo '<div class="no-results">Zde se zobrazí Vaše fotky</div>';
                }?>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>