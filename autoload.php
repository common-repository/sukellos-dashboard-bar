<?php

\spl_autoload_register(function ($class) {
  static $map = array (
  'Sukellos\\WP_Sukellos_Dashboard_Bar_Loader' => 'sukellos-dashboard-bar.php',
  'Sukellos\\WP_Sukellos_Dashboard_Bar' => 'class-wp-sukellos-dashboard-bar.php',
  'Sukellos\\Admin\\WP_Sukellos_Dashboard_Bar_Admin' => 'admin/class-wp-sukellos-dashboard-bar-admin.php',
  'Sukellos\\Dashboard_Bar_Manager' => 'includes/managers/class-dashboard-bar-manager.php',
);

  if (isset($map[$class])) {
    require_once __DIR__ . '/' . $map[$class];
  }
}, true, false);