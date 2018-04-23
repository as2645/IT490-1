<?php
// Basic error/user/registration logging
// Hopefully, we'll be able to catch errors should something go wrong
class logger {
  $user_log_file = 'user.log';
  $error_log_file = 'error.log';
  $reg_log_file = 'reg.log';
  $error_response = "";
  $reg_response = "";
  $user_response = "";
  public function log_error()
  {
    file_put_contents($error_log_file, $error_response, FILE_APPEND);
  }
  public function log_reg()
  {
    file_put_contents($reg_log_file, $reg_response, FILE_APPEND);
  }
  public function log_user()
  {
    file_put_contents($user_log_file, $user_response, FILE_APPEND);
  }
}
 ?>
