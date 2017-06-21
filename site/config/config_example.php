<?php

/* --------------------------------------
  Plugins-widget functionality
-------------------------------------- */

/* Set the plugins-directory, WITH a trailing-slash */

c::set('plugins-widget', 'site/widgets/plugins/');

if (file_exists(c::get('plugins-widget') . 'widgets.php' )) {
  include_once(c::get('plugins-widget') . 'widgets.php');
}

?>