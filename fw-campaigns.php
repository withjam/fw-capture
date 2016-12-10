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
  }

  function fw_capture_update_code() {

  }

  function fw_capture_create_code() {

  }

  function fw_capture_delete_code() {
    
  }


  function fw_campaign_admin_menu() {
    add_menu_page( 'F+W Email Capture Options', 'F+W Email Capture', 'manage_options', 'fw-capture-email-options', 'fw_campaign_manage_options');
    add_submenu_page( 'fw-capture-email-options', 'F+W Email Capture Settings', 'Plugin Settings', 'manage_options', 'fw-capture-email-options', 'fw_campaign_manage_options');
    add_submenu_page( 'fw-capture-email-options', 'F+W Email Captre Campaign Codes', 'Campaign Codes', 'manage_options', 'fw-capture-email-codes', 'fw_capture_manage_codes' );
  }

  function fw_capture_manage_codes() {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    require(plugin_dir_path( dirname(__FILE__) ) . 'fw-campaigns/admin/fw-capture-manage-codes-page.php');
  }

  function fw_campaign_manage_options() {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    require(plugin_dir_path( dirname(__FILE__) ) . 'fw-campaigns/admin/fw-campaigns-admin-page.php');
  }

  function fw_campaign_footer() {
    $url = plugins_url();
    $html = <<<EOT
    <style>
    body.noscroll { overflow: hidden; }
    #fw-overlay-modal { z-index: 10000; position: fixed; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; overflow: auto; transition: opacity 0.5s linear; background-color: rgba(0,0,0,0.85); text-align: center;}
    #fw-overlay-modal form { display: inline-block; position: relative; top: 10%; min-width: 25%; min-height: 80%; overflow: visible; background: #FFF; margin-bottom: 10%; }
    #fw-overlay-modal .form-group { margin: 15px;}
    #fw-overlay-modal label { color: #40A8C5; }
    #fw-overlay-modal.fadeout { opacity: 0 }
    #fw-overlay-modal.fadein { opacity: 1 }
    #fw-overlay-modal .fw-overlay-close {
      display: inline-block;
      -webkit-box-sizing: content-box;
      -moz-box-sizing: content-box;
      box-sizing: content-box;
      width: 0.4em;
      height: 0.4em;
      position: absolute;
      top: -25px;
      right: -25px;
      border: none;
      -webkit-border-radius: 1em;
      border-radius: 1em;
      font: normal 8em/normal Arial, Helvetica, sans-serif;
      color: rgba(0,0,0,1);
      -o-text-overflow: clip;
      text-overflow: clip;
      background: #40A8C5;
      cursor: pointer;
    }

    #fw-overlay-modal .fw-overlay-close:before {
      display: inline-block;
      -webkit-box-sizing: content-box;
      -moz-box-sizing: content-box;
      box-sizing: content-box;
      width: 0.25em;
      height: 0.075em;
      position: absolute;
      content: "";
      top: 0.165em;
      left: 0.075em;
      border: none;
      font: normal 100%/normal Arial, Helvetica, sans-serif;
      color: rgba(0,0,0,1);
      -o-text-overflow: clip;
      text-overflow: clip;
      background: #ffffff;
      text-shadow: none;
      -webkit-transform: rotateZ(45deg)   ;
      transform: rotateZ(45deg)   ;
    }

    #fw-overlay-modal .fw-overlay-close:after {
      display: inline-block;
      -webkit-box-sizing: content-box;
      -moz-box-sizing: content-box;
      box-sizing: content-box;
      width: 0.25em;
      height: 0.075em;
      position: absolute;
      content: "";
      top: 0.165em;
      left: 0.075em;
      border: none;
      font: normal 100%/normal Arial, Helvetica, sans-serif;
      color: rgba(0,0,0,1);
      -o-text-overflow: clip;
      text-overflow: clip;
      background: #ffffff;
      text-shadow: none;
      -webkit-transform: rotateZ(-45deg)   ;
      transform: rotateZ(-45deg)   ;
    }

    #fw-overlay-modal.form-error input { border-color: red; background-color: #FEE; }
    #fw-overlay-modal .error-message { display: none; color: red; font-size: 0.875em; }
    #fw-overlay-modal.form-error .error-message { display: block; }
    </style>
    <script>
    // campaign code
    (function(window, document) {
        var email_pattern = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/g;
      function getQS(q) {
        return function(r) {
          if ("" == r) return {};
          for (var e = {}, n = 0; n < r.length; ++n) {
            var t = r[n].split("=");
            if (2 == t.length) {
              var a = t[0],
                i = decodeURIComponent(t[1].replace(/\+/g, " "));
              e[a] && !Array.isArray(e[a]) ? (e[a] = [e[a]], e[a].push(i)) : e[a] = i
            }
          }
          return e
        }(q.split("&"));
      };

      var params = getQS(document.location.search.slice(1));

      var utm_code = params.utm_campaign;

      if (utm_code) {
        jQuery('body').addClass('noscroll');
        var ts = new Date().getTime();
        var overlay = document.createElement('DIV');
        overlay.id = 'fw-overlay-modal';
        var form = '<form id="optin-form"><a class="fw-overlay-close"></a><div id="fw-overlay-modal-content-wrapper"></div><div class="overlay-capture-form"><div class="form-group"><label for="fw-campaign-email-input">Enter Your Email Address</label> <div class="error-message">Please enter a valid email</div> <input id="fw-campaign-email-input" type="email" class="form-control" placeholder="your@email.com"></div><div class="form-group"><a class="btn btn-primary fw-overlay-submit">Sign Up</a></div></div></form>';
        overlay.innerHTML = form;
        document.body.append(overlay);
        overlay = document.getElementById('fw-overlay-modal');
        try {
          overlay.addEventListener('click', handleClick, false);
        } catch(ex) {
            overlay.attachEvent('onclick', handleClick);
        }
        
        window.setTimeout(function() {
          console.log('fadein');
          overlay.className = 'fadein';
          jQuery('#fw-overlay-modal-content-wrapper').load('{$url}/fw-campaigns/html/' + utm_code + '.html?ts=' + ts)
        }, 500);
        
        function handleClick(event) {
           var e = event || window.event;
                 var target = e.target;
                 switch(target.className) {
            case 'fw-overlay-close':
            case 'btn btn-default fw-overlay-cancel':
             console.log('fadeout');
              overlay.className = 'fadeout';
              window.setTimeout(function() {
                document.body.removeChild(overlay);
                delete overlay;
              }, 500);
              jQuery('body').removeClass('noscroll');
              break;
            case 'btn btn-primary fw-overlay-submit':
              var email = document.getElementById('fw-campaign-email-input').value;
              overlay.className = 'fadein';
              if (!email_pattern.test(email)) {
                overlay.className = 'fadein form-error';
              } else {
                jQuery(overlay).find('.overlay-capture-form').html('<div class="form-group"><h4>Thank you!</h4><a class="btn btn-default fw-overlay-cancel">Done</a></div>');
              }

              break;
           }
        }
      }

    })(window, document)
    </script>
EOT;

    echo $html;
  }
  add_action( 'wp_footer', 'fw_campaign_footer', 500);
?>