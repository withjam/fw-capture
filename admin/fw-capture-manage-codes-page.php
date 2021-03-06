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
        <th scope="col" class="manage-column">Interest</th>
        <th scope="col" class="manage-column">Description</th>
        <th scope="col" class="manage-column">Image</th>
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
        <td><?php echo $utm_data['interest']?></td>
        <td><div title="<?php echo $utm_data['desc']?>" style="text-overflow: ellipsis; width:98%; overflow: visible;"><?php echo $utm_data['desc']?></td>
        <td><img src="<?php echo $utm_data['img'] ?>" style="width:80px"></td>
        <td><?php echo $utm_data['offer_url']?></td>
        <td><a class="editCode" data-utm-code="<?php echo $utm_data["code"] ?>">edit</a>&nbsp;&nbsp;&nbsp;<a class="deleteCode delete"  data-utm-code="<?php echo $utm_data["code"] ?>">delete</a></td>
      </tr>
    <?php } ?>
      <tr>
        <td colspan="6"><a class="addNewCode">+ Add New Code</a></td>
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
          <th><label for="fw-capture-interest">Interest</label></th>
          <td><select type="text" name="interest">
            <option>Beading</option>
            <option>Crochet</option>
            <option>Jewelry</option>
            <option>Knitting</option>
            <option>Needlework</option>
            <option>Spinning</option>
            <option>Weaving</option>
            <option>Partners</option>
          </select></td>
        </tr>
        <tr>
          <th><label for="fw-capture-title">Title</label></th>
          <td><input type="text" name="title" class="large-text"></td>
        </tr>
        <tr>
          <th><label for="fw-capture-img">Image</label></th>
          <td>
            <div class='image-preview-wrapper'>
              <img id='image-preview' src='' width='80'>
            </div>
            <a id="upload_image_button">Select Image</a>
            <input type='hidden' name='image_attachment_url' id='image_attachment_url' value=''>
            <input type='hidden' name='image_attachment_w' id='image_attachment_w' value=''>
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
        <tr>
          <th><label for="fw-capture-thanks-page">Thanks Page Text</label></th>
          <td><textarea rows="5" cols="25" name="thanks_page" class="large-text"></textarea></td>
        </tr>
        <tr>
          <th><label for="fw-capture-thanks-email">Email Thanks Text</label></th>
          <td><textarea rows="5" cols="25" name="thanks_email" class="large-text"></textarea></td>
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
      var post_url = frm.attr('action');
      var action = frm.find('input[name="action"]').val();
      frm.find('input[name="utm_code"]').removeAttr('disabled');
      var data = {
        action: action,
        code: frm.find('input[name="utm_code"]').val(),
        interest: frm.find('select[name="interest"').val(),
        title: frm.find('input[name="title"]').val(),
        img_url: frm.find('input[name="image_attachment_url"]').val(),
        img_w: frm.find('input[name="image_attachment_w"]').val(),
        desc: frm.find('textarea[name="desc"]').val(),
        offer_url: frm.find('input[name="url"]').val(),
        thanks_page: frm.find('textarea[name="thanks_page"]').val(),
        thanks_email: frm.find('textarea[name="thanks_email"]').val()
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
          edit_form.find('input[name="action"],input[name="utm_code"]').removeAttr('disabled');
          edit_form_action.val('fw_capture_create_code');
          edit_form.find('select[name="interest"]').val('');
          edit_form.find('textarea,input[type="text"]').val('');
          edit_form.find('img#image-preview').attr('src','about:blank');
          edit_form.find('input#image_attachment_url').val('');
          edit_form.find('input#image_attachment_w').val('');
          showEditForm('Add');
        } else if (t.is('.cancelEdit')) {
          hideEditForm();
        } else if (t.is('.editCode')) {
          var d = utm_codes[code];
          edit_form.find('input[name="utm_code"]').attr('disabled','disabled').val(d.code);
          edit_form.find('input[name="title"]').val(d.title);
          edit_form.find('select[name="interest"]').val(d.interest);
          edit_form.find('img#image-preview').attr('src',d.img);
          edit_form.find('input#image_attachment_url').val(d.img);
          edit_form.find('input#image_attachment_w').val(d.width);
          edit_form.find('textarea[name="desc"]').val(d.desc);
          edit_form.find('textarea[name="thanks_page"]').val(d.thanks_page);
          edit_form.find('textarea[name="thanks_email"]').val(d.thanks_email);
          edit_form.find('input[name="offer_url"]').val(d.offer_url);
          edit_form_action.val('fw_capture_update_code');
          showEditForm('Edit');
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
            console.log('attachment', attachment);
            $( '#image-preview' ).attr( 'src', attachment.url );
            $( '#image_attachment_url' ).val( attachment.url );
            $( '#image_attachment_w' ).val( attachment.width );
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