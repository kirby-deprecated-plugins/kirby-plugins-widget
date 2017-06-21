<?php

/* Replace last occurence in directory-name */

function str_lreplace($search, $replace, $subject) {

    $pos = strrpos($subject, $search);

    if($pos !== false) {

      $subject = substr_replace($subject, $replace, $pos, strlen($search));

    }

    return $subject;
}

/* Default Kirby route */

c::set('routes', array(

  array(
    'pattern' => 'plugins-toggle',
    'method' => 'POST',
    'action' => function() {

      $plugins_dir = $_POST['plugins_dir'];
      $plugins_action = 'disable';

      if (strrpos($plugins_dir, '/plugins/_') > 0) {
        $plugins_action = 'enable';
      }

/* Check if plugin-dir exists */

      if (file_exists($plugins_dir)) {

/* Rename - enable */

        if ($plugins_action == 'enable') {

          try {

            rename($plugins_dir, str_lreplace('/_', '/', $plugins_dir));

          } catch (Exception $e) {

            echo ('can not enable / rename this plugin.');

          }

/* Rename - disable */

        } else {

          try {

            rename($plugins_dir, str_lreplace('/', '/_', $plugins_dir));

          } catch (Exception $e) {

            echo ('can not disable / rename this plugin.');

          }

        }

/* Error - directory does not exist */

      } else {

        echo('directory does not exist.');
      }

    }
  )

));

?>