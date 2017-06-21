<?php

/* Define site() - needed for reference later */

global $site;
$site = site();

return array(
  'title' => 'Plugins',
  'options' => array(
    array(
      'text' => 'Visit Site',
      'icon' => 'eye',
      'link' => $site->url(),
      'target' => '_blank'
    )
  ),
  'html' => function() {

    global $site;

/* All the styling */

    $css = '<style>
              #plugins-widget p i {
                float: right;
                margin: 3px 0 0 0;
                cursor: pointer;
              }

              #plugins-widget p.plugins-off span {
                color: #999;
                font-style: italic;
              }

              #plugins-widget p:not(:last-of-type) {
                padding: 8px 0;
                border-bottom: 1px solid #dddddd;
              }

              #plugins-widget p:last-of-type {
                padding: 8px 0 0 0;
              }
            </style>';

/* All the scripting */

    $js = '<script>

            $("#plugins-widget p i").on("click", function() {

            var toggle = $(this).hasClass("fa-toggle-on")?"off":"on";
            var toggle_el = $(this);
            var plugins_dir = toggle_el.attr("data-plugins");
              $.ajax({
                type: "post",
                url: "' . $site->url() . '/plugins-toggle",
                dataType: "text",
                data: {"plugins_dir": plugins_dir},
                success: function(response) {
                  if (response.length < 1) {
                    toggle_el.removeClass(function (index, css){ return (css.match (/(^|\s)fa-toggle-\S+/g) || []).join(" ") }).addClass("fa-toggle-" + toggle).parent().removeClass().addClass("plugins-" + toggle);
                    var toggle_data = toggle_el.attr("data-plugins");
                    if (toggle == "on") {
                      toggle_el.attr("data-plugins", toggle_data.replace(/\/_([^\/_]*)$/,"/" + "$1"));
                    } else {
                      toggle_el.attr("data-plugins", toggle_data.replace(/\/([^\/]*)$/,"/_" + "$1"));
                    }
                  } else {
                    alert("Oops... something went wrong : " + response);
                  }
                },
                error: function(error) {
                  alert("Oops... something went wrong : " + JSON.stringify(error));
                }
              });

            });

          </script>';

/* Loop through all plugins */

      $dirs = '<b>Error</b> : plugins-folder not found';
      $level = '../';
      $path = 'site/plugins/';

        if (is_dir($level . $path)) {

          $dirs = '';
          $dir = new DirectoryIterator($level . $path);

          foreach ($dir as $fileinfo) {

            if ($fileinfo->isDir() && !$fileinfo->isDot()) {

              $plugins_dir = $fileinfo->getFilename();
              $toggle = substr($plugins_dir, 0, 1) == '_'?'off':'on';
              $plugins_txt = $toggle == 'on'?$plugins_dir:substr($plugins_dir, 1);

              $dirs .= '<p class="plugins-' . $toggle . '"><span>' . ucfirst(strtolower($plugins_txt)) . '</span> <i class="fa fa-toggle-' . $toggle . '" title="toggle this plugin" data-plugins="' . $path . $plugins_dir . '"></i></p>';

            }

          }

        }

/* Nothing found, nothing to show */

      if (empty($dirs)) {
        $dirs = 'No plugins found';
      }

/* Show all plugins in widget */

    	return $css . $dirs . $js;

    }

);

?>