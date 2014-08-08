<form id="product_listing" class="product_create_listing inline_form">

    <header>
        <div class="go_back"></div>
        <div class="title"><input type="text"></div>
        <div class="price"></div>
    </header>

    <section>
        <div class="left images_container">
            <div class="main_image">
                <div class="image_item"></div>
            </div>

            <div class="secondary_images">
                <div class="image_item"></div>
                <div class="image_item"></div>
                <div class="image_item"></div>
                <div class="image_item"></div>
            </div>

            <div class="imageUpload_area">
                <?php $this->load->view('modules/imageupload/views/imageupload_view', array('imageUploadConfig' => $imageUploadConfig)); ?>
            </div>
        </div>

        <div class="right info_container">
            <div class="description"><textarea></textarea></div>
            <div class="purchase_area">
                <a href="#" class="btn">Create</a>
            </div>
        </div>
    </section>

</form>