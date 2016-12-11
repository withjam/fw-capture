<?php?>

<div class="wrap" id="fw-campaign-manage-options">
  <h1>F+W Email Capture - Campaign Codes</h1>

  <table class="wp-list-table widefat fixed striped fw-campaigns">
  <thead>
    <tr>
      <th scope="col" class="manage-column">Campaign Code</th>
      <th scope="col" class="manage-column">Title</th>
      <th scope="col" class="manage-column">Download Link</th>
      <th class="column-cb check-column" style="width: 70px;"></th>
    </tr>
  </thead>
  <?php for($i = 0; $i < count($utm_codes); $i++) { 
      $utm_data = json_decode($utm_codes[$i]); ?>
    <tr>
      <td>bd-jra-fp-primebeading</td>
      <td>Enjoy 3 free beaded bracelet patterns!</td>
      <td></td>
      <td>&nbsp;<a class="editCode" data-row-index="<?php echo $i; ?>">edit</a>&nbsp;</td>
    </tr>
  <?php } ?>
    <tr>
      <td colspan="4"><a class="addNewCode">+ Add New Code</a></td>
    </tr>
  </table>

</div>

<div id="fw_code_form" style="position: fixed; top: 0; left: 0; height: 100%; width: 100%; background-color: rgba(0,0,0,0.8);">
  <form action="http://www.example.com/wp-admin/admin-post.php" method="post" style="width: 50%; position: fixed; top: 15%; left: 25%; background-color: #FFF; padding: 15px;">
    <input type="hidden" name="action" value="add_foobar">
    <fieldset>
      <h2>Edit UTM Code</h2>
      <table class="form-table" style="margin-left: 15px">
      <tr>
        <th><label for="fw-capture-utm-code">UTM Code</label></th>
        <td><input type="text" name="utm_code" class="large-text"></td>
      </tr>
      <tr>
        <th><label for="fw-capture-title">Title</label></th>
        <td><input type="text" name="title" class="large-text"></td>
      </tr>
      <tr>
        <th><label for="fw-capture-img">Image</label></th>
        <td>
          <div class='image-preview-wrapper'>
            <img id='image-preview' src='' width='100' height='100' style='max-height: 100px; width: 100px;'>
          </div>
          <a id="upload_image_button">Select Image</a>
          <input type='hidden' name='image_attachment_id' id='image_attachment_id' value=''>
        </td>
      </tr>
      <tr>
        <th><label for="fw-campaign-desc">Description</label></th>
        <td><textarea rows="5" cols="25" name="desc" class="large-text"></textarea></td>
      </tr>
      <tr>
        <th><label for="fw-capture-download">Offer Download URL</label></th>
        <td><input type="text" name="url" class="large-text"></td>
      </tr>
      </table>
      <p class="submit" style="margin-left: 15px">
        <input type="submit" name="submit" class="button button-primary" value="Save Changes">
        <a class="cancel" style="display: inline-block; margin-left: 50px;">Cancel</a>
      </p>
    </fieldset>
  </form>
</div>

<script type='text/javascript'>

    jQuery( document ).ready( function( $ ) {

      // Uploading files
      var file_frame;
      var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
      var set_to_post_id = 1; // Set this

      jQuery('#upload_image_button').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
          // Set the post ID to what we want
          file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
          // Open frame
          file_frame.open();
          return;
        } else {
          // Set the wp.media post id so the uploader grabs the ID we want when initialised
          wp.media.model.settings.post.id = set_to_post_id;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          title: 'Media Library',
          button: {
            text: 'Use this image',
          },
          multiple: false // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
          // We set multiple to false so only get one image from the uploader
          attachment = file_frame.state().get('selection').first().toJSON();

          // Do something with attachment.id and/or attachment.url here
          $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
          $( '#image_attachment_id' ).val( attachment.id );

          // Restore the main post ID
          wp.media.model.settings.post.id = wp_media_post_id;
        });

          // Finally, open the modal
          file_frame.open();
      });

      // Restore the main ID when the add media button is pressed
      jQuery( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
      });
    });

  </script>