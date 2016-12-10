<?php?>

<div class="wrap" id="fw-campaign-manage-options">
  <h1>F+W Email Capture - Campaign Codes</h1>

  <table class="wp-list-table widefat fixed striped fw-campaigns">

  </table>

</div>

<div id="fw_code_form">
  <form action="http://www.example.com/wp-admin/admin-post.php" method="post">
    <input type="hidden" name="action" value="add_foobar">
    <div class="form-group">
      <label for="fw-campaign-utm-code">UTM Code</label>
      <input type="text" name="utm_code">
    </div>
    <div class="form-group">
      <label for="fw-campaign-title">Title</label>
      <input type="text" name="title">
    </div>
    <div class="form-group">
      <label for="fw-campaign-desc">Description</label>
      <input type="text" name="desc">
    </div>
    <div class="form-group">
      <label for="fw-campaign-download">Offer Download URL</label>
      <input type="text" name="url">
    </div>
  </form>
</div>