/**
 * Password plugin script
 *
 * @licstart  The following is the entire license notice for the
 * JavaScript code in this file.
 *
 * Copyright (c) The Roundcube Dev Team
 *
 * The JavaScript code in this page is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * @licend  The above is the entire license notice
 * for the JavaScript code in this file.
 */

window.rcmail && rcmail.addEventListener('init', function(evt) {
    if (rcmail.env.password_disabled) {
        $('#password-form input').prop('disabled', true);
        // reload page after ca. 3 minutes
        rcmail.reload(3 * 60 * 1000 - 2000);
        return;
    }

    // register command handler
    rcmail.register_command('plugin.password-save', function() {
        var input_curpasswd = rcube_find_object('_curpasswd'),
            input_newpasswd = rcube_find_object('_newpasswd'),
            input_confpasswd = rcube_find_object('_confpasswd');

      if (input_curpasswd && input_curpasswd.value == '') {
          rcmail.alert_dialog(rcmail.get_label('nocurpassword', 'password'), function() {
              input_curpasswd.focus();
              return true;
            });
      }
      else if (input_newpasswd && input_newpasswd.value == '') {
          rcmail.alert_dialog(rcmail.get_label('nopassword', 'password'), function() {
              input_newpasswd.focus();
              return true;
            });
      }
      else if (input_confpasswd && input_confpasswd.value == '') {
          rcmail.alert_dialog(rcmail.get_label('nopassword', 'password'), function() {
              input_confpasswd.focus();
              return true;
            });
      }
      else if (input_newpasswd && input_confpasswd && input_newpasswd.value != input_confpasswd.value) {
          rcmail.alert_dialog(rcmail.get_label('passwordinconsistency', 'password'), function() {
              input_newpasswd.focus();
              return true;
            });
      } else if(!valid_password(input_newpasswd.value)){
          var info_str = "Please check password rules again.";
          rcmail.alert_dialog(info_str, function() {
            input_newpasswd.focus();
            return true;
          });
      }
      else {
          rcmail.gui_objects.passform.submit();
      }
    }, true);

    $('input:not(:hidden)').first().focus();

    $(".toggle-password").on('click', function(event){
        event.preventDefault();
        $(this).toggleClass("i-visible i-invisible");
        var input = $("#" + $(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
        input.focus();
    });
});

function valid_password(pass){

    if(pass.length < 8) return false;
    var upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var lower = "abcdefghijklmnopqrstuvwxyz";
    var spec = "~!@#$%^&";
    var alpha = "1234567890";
    var upper_found = false;
    var lower_found = false;
    var spec_found = false;
    var alpha_found = false;
    for (var i = 0; i < pass.length; i++){
        if(!upper_found && upper.includes(pass[i])){
                upper_found = true;
        }
        if(!lower_found && lower.includes(pass[i])){
            lower_found = true;
        }
        if(!spec_found && spec.includes(pass[i])){
            spec_found = true;
        }
        if(!alpha_found && alpha.includes(pass[i])){
            alpha_found = true;
        }

        if(upper_found && lower_found && spec_found && alpha_found) break;
    }

    if(upper_found && lower_found && spec_found && alpha_found) return true;
    return false;
}
