<?php?>

<div class="wrap" id="fw-campaign-manage-options">
  <h1>F+W Email Capture - Settings</h1>

  <form method="post" action="admin-post.php"> 
    <input type="hidden" name="action" value="fw_capture_update_settings">
    <table class="form-table">
    <tr>
      <th scope="row"><label for="fw_capture_submit_url">Default Capture URL</label></th>
      <td><input id="fw_capture_submission_url" type="text" name="fw_capture_submission_url" value="<?php echo esc_attr( get_option('fw_capture_submission_url') ); ?>" class="large-text"></td>
    </tr>
    <tr>
      <th scope="row"><label for="fw_capture_disclaimer">Disclaimer Text</label></th>
      <td><input id="fw_capture_disclaimer" type="text" name="fw_capture_disclaimer" value="<?php echo esc_attr( get_option('fw_capture_disclaimer') ); ?>" class="large-text"></td>
    </tr>
    <tr>
      <th scope="row"><label for="fw_capture_button_label">Button Label</label></th>
      <td><input id="fw_capture_button_label" type="text" name="fw_capture_button_label" value="<?php echo esc_attr( get_option('fw_capture_button_label') ); ?>" class="regular-text"></td>
    </tr>
    </table>

    <h3>Interstitial Settings</h3>

    <table class="form-table">
    <tr>
      <th scope="row"><label for="fw_capture_delay">Interstitial Delay <small>(seconds)</small></label></th>
      <td><input id="fw_capture_delay" type="text" name="fw_capture_delay" value="<?php echo esc_attr( get_option('fw_capture_delay') ); ?>" class="small-text"></td>
    </tr>
    <tr>
      <th scope="row"><label for="fw_capture_thanks_page">Interstitial Thanks Text</label></th>
      <td><input id="fw_capture_thank_page" type="text" name="fw_capture_thanks_page" value="<?php echo esc_attr( get_option('fw_capture_thanks_page') ); ?>" class="large-text"></td>
    </tr>
    <tr>
      <th scope="row"><label for="fw_capture_thanks_email">Interstitial Email Thanks Text</label></th>
      <td><input id="fw_capture_thanks_email" type="text" name="fw_capture_thanks_email" value="<?php echo esc_attr( get_option('fw_capture_thanks_email') ); ?>" class="large-text"></td>
    </tr>
    </table>
  <?php submit_button(); ?>
  </form>

</div>