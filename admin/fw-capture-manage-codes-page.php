<?php?>
<style>
a { cursor: pointer; }
.delete, .delete:hover { color: #C03; }</style>
<div id="fw-campaign-manage-options">
  <div class="wrap">
    <h1>F+W Email Capture - Campaign Codes</h1>

    <table class="wp-list-table widefat fixed striped fw-campaigns">
    <thead>
      <tr>
        <th scope="col" class="manage-column">Campaign Code</th>
        <th scope="col" class="manage-column">Title</th>
        <th scope="col" class="manage-column">Description</th>
        <th scope="col" class="manage-column">Download Link</th>
        <th class="column-cb check-column" style="width: 95px;"></th>
      </tr>
    </thead>
    <script>var utm_codes = {};</script>
    <?php foreach($utm_code_data as $utm_data) { ?>
      <script>utm_codes['<?php echo $utm_data["code"]?>'] = JSON.parse('<?php echo json_encode($utm_data)?>');</script> 
      <tr>
        <td><?php echo $utm_data['code']?></td>
        <td><?php echo $utm_data['title']?></td>
        <td><div title="<?php echo $utm_data['desc']?>" style="text-overflow: ellipsis; width:98%; overflow: hidden; white-space: nowrap;"><?php echo $utm_data['desc']?></td>
        <td><?php echo $utm_data['offer_url']?></td>
        <td><a class="editCode" data-utm-code="<?php echo $utm_data["code"] ?>">edit</a>&nbsp;&nbsp;&nbsp;<a class="deleteCode delete"  data-utm-code="<?php echo $utm_data["code"] ?>">delete</a></td>
      </tr>
    <?php } ?>
      <tr>
        <td colspan="4"><a class="addNewCode">+ Add New Code</a></td>
      </tr>
    </table>

  </div>

  <div id="fw_code_form" style="display: none; position: fixed; top: 0; left: 0; height: 100%; width: 100%; background-color: rgba(0,0,0,0.8);">
    <form action="admin-post.php" onSubmit="return utm_code_submit()" method="post" style="width: 50%; position: fixed; top: 10%; left: 25%; height: 80%; background-color: #FFF; padding: 5px 15px; overflow: auto">
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
            <input type='hidden' name='image_attachment_url' id='image_attachment_id' value=''>
          </td>
        </tr>
        <tr>
          <th><label for="fw-campaign-desc">Description</label></th>
          <td><textarea rows="5" cols="25" name="desc" class="large-text"></textarea></td>
        </tr>
        <tr>
          <th><label for="fw-capture-download">Offer Download URL</label></th>
          <td><input type="text" name="offer_url" class="large-text"></td>
        </tr>
        </table>
        <p class="submit" style="margin-left: 15px">
          <input type="submit" name="submit" class="button button-primary" value="Save Changes">
          <a class="cancelEdit" style="display: inline-block; margin-left: 50px;">Cancel</a>
        </p>
      </fieldset>
    </form>
  </div>
</div>

<form id="fwDeleteForm" method="POST" action="admin-post.php"><input type="hidden" name="action" value="fw_capture_delete_code"><input type="hidden" name="utm_code" value=""></form>

<script type='text/javascript'>

    function utm_code_submit() {
      var frm = jQuery('#fw_code_form form');
      console.log('submit form');
      var post_url = frm.attr('action');
      var action = frm.find('input[name="action"]').val();
      var data = {
        action: action,
        code: frm.find('input[name="utm_code"]').val(),
        title: frm.find('input[name="title"]').val(),
        img_id: frm.find('input[name="image_attachment_id"]').val(),
        desc: frm.find('textarea[name="desc"]').val(),
        offer_url: frm.find('input[name="url"]').val(),
      }

      console.log('data', data);
      if (!data.code) {
        alert('UTM Code is required.');
        return false;
      } 

      return true;

    }

    jQuery( document ).ready( function( $ ) {

      var edit_form = jQuery('#fw_code_form'), edit_form_action = edit_form.find('input[name="action"]'), bod = jQuery('body');

      function showEditForm(type) {
        bod.addClass('modal-open');
        edit_form.show();
      }
      function hideEditForm() {
        bod.removeClass('modal-open');
        edit_form.hide();
      }

      jQuery('#fw-campaign-manage-options').on('click', 'a', function(evt) {
        var t = jQuery(this);
        var code = t.data('utm-code');
        if (t.is('.addNewCode')) {
          edit_form_action.val('fw_capture_create_code');
          showEditForm('Add');
        } else if (t.is('.cancelEdit')) {
          hideEditForm();
        } else if (t.is('.deleteCode') && confirm('Are you sure you want to delete "' + code + '"?')) {
          if (!code) {
            throw 'No code for deletion!';
          }
          var del_form = jQuery('#fwDeleteForm');
          del_form.find('input[name="utm_code"]').val(code);
          del_form.submit();
        }
      })

      // Uploading files
      var wpmedia;

      jQuery('#upload_image_button').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( !wpmedia ) {
          // Create the media frame.
          wpmedia = wp.media({
            title: 'Media Library',
            button: {
              text: 'Use this image',
            },
            multiple: false // Set to true to allow multiple files to be selected
          });

          // When an image is selected, run a callback.
          wpmedia.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = wpmedia.state().get('selection').first().toJSON();
            // Do something with attachment.id and/or attachment.url here
            $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
            $( '#image_attachment_url' ).val( attachment.url );
          });
        }



          // Finally, open the modal
          wpmedia.open();
      });

      // Restore the main ID when the add media button is pressed
      jQuery( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
      });
    });

  </script>