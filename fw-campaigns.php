<?php
/**
* Plugin Name: F+W Community Email Capture Campaign
* Plugin URI: http://interweave.com/
* Description: Email capture boxes for utm_campaigns
* Version: 1.0
* Author: Matt Pileggi
* Author URI: http://github.com
* License: Apache 2.0
**/

  if (is_admin()) {
    add_action('admin_menu', 'fw_campaign_admin_menu');
    add_action( 'admin_init', 'fw_campaign_register_settings' );
    add_action( 'admin_post_fw_capture_create_code', 'fw_capture_create_code' );
    add_action( 'admin_post_fw_capture_update_code', 'fw_capture_update_code' );
    add_action( 'admin_post_fw_capture_delete_code', 'fw_capture_delete_code' );
  }

  function fw_campaign_register_settings() {
    register_setting( 'fw-capture-options', 'fw_capture_submission_url' );
    register_setting( 'fw-capture-options', 'fw_capture_disclaimer' );
    register_setting( 'fw-capture-options', 'fw_capture_button_label' );
    register_setting( 'fw-capture-options', 'fw_capture_delay' );
    register_setting( 'fw-capture-options', 'fw_capture_thanks_page' );
    register_setting( 'fw-capture-options', 'fw_capture_thanks_email' );
  }

  function fw_parse_utm_data_post() {
    $data = array();
    $data['code'] = $_POST['utm_code'];
    $data['title'] = $_POST['title'];
    $data['img'] = $_POST['image_attachment_url'];
    $data['width'] = $_POST['image_attachment_w'];
    $data['desc'] = $_POST['desc'];
    $data['offer_url'] = $_POST['offer_url'];
    $data['thanks_page'] = $_POST['thanks_page'];
    $data['thanks_email'] = $_POST['thanks_email'];
    return $data;
  }

  function fw_capture_update_code() {
    $data = fw_parse_utm_data_post();
    $code = $data['code'];
    $opt_name = 'fw_capture_utm_code_' . $code;
    // make sure already exists since we aren't updating the main array
    if (get_option($opt_name)) {
      update_option($opt_name, json_encode($data));
    }
    wp_redirect(admin_url('admin.php?page=fw-capture-email-codes'));
  }

  function fw_capture_create_code() {
    $data = fw_parse_utm_data_post();
    $code = $data['code'];
    $opt_name = 'fw_capture_utm_code_' . $code;
    // make sure it doesn't already exist, so we don't duplicate keys in the array
    if (!get_option($opt_name)) {
      $arr = get_option('fw_capture_utm_codes', []);
      $arr[] = $code;
      update_option('fw_capture_utm_codes', $arr);
      add_option($opt_name, json_encode($data));
    }
    wp_redirect(admin_url('admin.php?page=fw-capture-email-codes'));
  }

  function fw_capture_delete_code() {
    $code = $_POST['utm_code'];
    $arr = get_option('fw_capture_utm_codes', []);
    $pos = array_search($code, $arr); 
    if ($pos !== false) {
      unset($arr[$pos]);
      update_option('fw_capture_utm_codes', $arr);
      delete_option('fw_capture_utm_code_' . $code);      
    }
    wp_redirect(admin_url('admin.php?page=fw-capture-email-codes'));
  }


  function fw_campaign_admin_menu() {
    add_menu_page( 'F+W Email Capture Options', 'F+W Email Capture', 'edit_posts', 'fw-capture-email-options', 'fw_campaign_manage_options');
    add_submenu_page( 'fw-capture-email-options', 'F+W Email Capture Settings', 'Plugin Settings', 'edit_posts', 'fw-capture-email-options', 'fw_campaign_manage_options');
    add_submenu_page( 'fw-capture-email-options', 'F+W Email Captre Campaign Codes', 'Campaign Codes', 'edit_posts', 'fw-capture-email-codes', 'fw_capture_manage_codes' );
  }

  function fw_capture_manage_codes() {
    if ( !current_user_can( 'edit_posts' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    wp_enqueue_media();
    $utm_codes = get_option('fw_capture_utm_codes', []);
    $utm_code_data = array();
    foreach($utm_codes as $code) {
      $json = json_decode( get_option('fw_capture_utm_code_' . $code), '{}');
      $utm_code_data[$code] = $json;
    }
    require(plugin_dir_path( dirname(__FILE__) ) . 'fw-capture/admin/fw-capture-manage-codes-page.php');
  }

  function fw_campaign_manage_options() {
    if ( !current_user_can( 'edit_posts' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    require(plugin_dir_path( dirname(__FILE__) ) . 'fw-capture/admin/fw-capture-admin-page.php');
  }

  function fw_campaign_footer() {
    // utm capture code
    $submissionUrl = get_option('fw_capture_submission_url', '/');
    $disclaimer = get_option('fw_capture_disclaimer');
    $btnLabel = get_option('fw_capture_button_label', 'Sign Up');
    $interDelay = get_option('fw_capture_delay');
    $interTitle = get_option('fw_capture_inter_title', 'Join Our Mailing List!');
    $interDesc = addslashes(get_option('fw_capture_inter_desc', 'Enter your email address below to receive special offers and exciting updates from Interweave.com.'));
    $plugin_footer = <<< EOT
    <style>body.noscroll{overflow:hidden}#fw-overlay-modal{z-index:10000;position:fixed;width:100%;height:100%;top:0;left:0;opacity:0;overflow:auto;transition:opacity .5s linear;background-color:rgba(0,0,0,.85);text-align:center}#fw-overlay-modal form{display:inline-block;position:relative;top:10%;min-width:25%;min-height:50%;overflow:visible;background:#FFF;margin-bottom:10%}#fw-overlay-modal p{ margin: 20px 8px;line-height: 1.55em;}#fw-overlay-modal h4{ margin: 25px 0 15px }#fw-overlay-modal p.small{line-height:1.25em;font-size:0.85em}#fw-overlay-modal .form-group{margin:15px}#fw-overlay-modal label{color:#40A8C5}#fw-overlay-modal.fadeout{opacity:0}#fw-overlay-modal.fadein{opacity:1}#fw-overlay-modal .fw-overlay-close{display:inline-block;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;width:.4em;height:.4em;position:absolute;top:-25px;right:-25px;border:none;-webkit-border-radius:1em;border-radius:1em;font:400 8em/normal Arial,Helvetica,sans-serif;color:rgba(0,0,0,1);-o-text-overflow:clip;text-overflow:clip;background:#40A8C5;cursor:pointer}#fw-overlay-modal .fw-overlay-close:after,#fw-overlay-modal .fw-overlay-close:before{display:inline-block;width:.25em;height:.075em;position:absolute;content:"";top:.165em;left:.075em;border:none;font:400 100%/normal Arial,Helvetica,sans-serif;color:rgba(0,0,0,1);-o-text-overflow:clip;text-overflow:clip;background:#fff;text-shadow:none}#fw-overlay-modal .fw-overlay-close:before{-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;-webkit-transform:rotateZ(45deg);transform:rotateZ(45deg)}#fw-overlay-modal .fw-overlay-close:after{-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;-webkit-transform:rotateZ(-45deg);transform:rotateZ(-45deg)}#fw-overlay-modal.form-error input.required{border-color:red;background-color:#FEE}#fw-overlay-modal .error-message{display:none;color:red;font-size:.875em}#fw-overlay-modal.form-error .error-message{display:block}#fw-overlay-modal #fw-capture-form-thanks{display: none}#fw-overlay-modal.submitted #fw-capture-form-thanks{display:block}#fw-overlay-modal.submitted .overlay-capture-form{display:none}</style>
    <div style="opacity: 0; width: 0; height: 0; position: fixed; bottom: 0; left: 0;overflow: hidden: z-index: 1"><iframe name="fw-capture-submission-iframe" id="fw-capture-submission-iframe"></iframe></div>
    <script>
    // fw utm campaign script
    (function() {

      function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }

      // regex for validating email format
      var email_pattern = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/g;

      var fw_capture = {
        btnLabel: '{$btnLabel}',
        disclaimer: '${disclaimer}',
        submissionUrl: '{$submissionUrl}'
      };

      fw_capture.interstitial = {
        label: '{$interTitle}',
        delay: '{$interDelay}'
      }

      fw_capture.openModal = function(offerData, width) {
        // prevent regular scrolling
        jQuery('body').addClass('noscroll');

        fw_capture.overlay = document.createElement('DIV');
        fw_capture.overlay.id = 'fw-overlay-modal';

        var form = '<form id="optin-form" style="width: ' + offerData.width + 'px;" method="POST" action="' + fw_capture.submissionUrl + '" onSubmit="return fw_capture.submitForm()" target="fw-capture-submission-iframe"><a class="fw-overlay-close"></a><div id="fw-overlay-modal-content-wrapper"><h4>' + offerData.title + '</h4>';
        if (offerData.img) {
          form += '<img src="' + offerData.img +'">';
        }
        if (offerData.desc) {
          form += '<p>' + offerData.desc + '</p>'
        }
        if (offerData.code) {
          form += '<input type="hidden" name="utm_campaign" value="' + offerData.code + '">';
        }
        if (offerData.thanks_email) {
         form += '<input type="hidden" name="email_text" value="' + offerData.thanks_email + '">'; 
        }
        if (offerData.offer_url) {
          form += '<input type="hidden" name="offer_url" value="' + offerData.offer_url + '">';
        }

        form += '</div><div class="overlay-capture-form"><div class="form-group"><label for="fw-campaign-email-input">Enter Your Email Address</label> <div class="error-message">Please enter a valid email</div><input id="fw-campaign-email-input" type="email" class="form-control required" placeholder="your@email.com" name="email"></div><div class="form-group"><input type="submit" class="btn btn-primary fw-overlay-submit" value="' + ( fw_capture.btnLabel || "Sign Up" )+ '"><p class="small"><em>' + fw_capture.disclaimer + '</em></p></div></div><div id="fw-capture-form-thanks" class="form-group"><h4>Thank you!</h4><p>' + offerData.thanks_page + '</p><a class="btn btn-default fw-overlay-cancel">Done</a></div></form>';
        
        fw_capture.overlay.innerHTML = form;

        document.body.append(fw_capture.overlay);

        fw_capture.activeOverlay = jQuery(document.getElementById('fw-overlay-modal')).on('click', fw_capture.handleClick);
        window.setTimeout(function() { fw_capture.activeOverlay.addClass('fadein'); }, 100);

        setCookie('fw_capture_seen', new Date(), 30);
      }

      fw_capture.closeModal = function() {
        // enable scrolling again
        jQuery('body').removeClass('noscroll');
        fw_capture.activeOverlay.removeClass('fadein').addClass('fadeout');
        window.setTimeout(function() {
          document.body.removeChild(fw_capture.activeOverlay.get(0));
          delete fw_capture.activeOverlay;
        }, 500);
      }

      fw_capture.handleClick = function(evt) {
        var target = jQuery(evt.target);
        console.log('handleClick', target);
        if (target.is('.fw-overlay-cancel, .fw-overlay-close')) {
          fw_capture.closeModal();
        } 
      }

      fw_capture.submitForm = function() {
        var email = document.getElementById('fw-campaign-email-input').value;
        fw_capture.activeOverlay.removeClass('form-error');
        if (!email_pattern.test(email)) {
          fw_capture.activeOverlay.addClass('form-error');
          return false;
        } 
        window.setTimeout(function() {
          fw_capture.activeOverlay.addClass('submitted');
        },100);
        return true;
      }

      window.fw_capture = fw_capture;
    })(window)
    </script>
EOT;
    echo $plugin_footer; 
    // utm campaign specific
    $code = $_GET['fw_campaign'];
    if (isset($code)) {
      $codeData = get_option('fw_capture_utm_code_' . $code);
      if ($codeData) {
        $html = <<<EOT
        <script>
        (function() {
          fw_capture.openModal(JSON.parse('{$codeData}'));
        })()
        </script>
EOT;

        echo $html;
      }
    } else {
      // setup interstitial code if a delay is set and the cookie is not present
      if ($interDelay && !isset($_COOKIE['fw_capture_seen'])) {
        $interDelayMS = intval($interDelay) * 1000;
        $interThanksPage = addslashes(get_option('fw_capture_thanks_page'));
        $interThanksEmail = addslashes(get_option('fw_capture_thanks_email'));
        echo "<script>window.setTimeout(function() { fw_capture.openModal({ code: 'iw_interstitial', width: 375, title: '{$interTitle}', desc: '{$interDesc}', thanks_email: '{$interThanksEmail}', thanks_page: '{$interThanksPage}' }); }, {$interDelayMS});</script>";
      }
    }
  }
  add_action( 'wp_footer', 'fw_campaign_footer', 500);
?>