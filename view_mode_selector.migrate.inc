<?php

/**
 * @file
 * Support for Migrate API module.
 */

/**
 * Field handler class to implement view mode selector field migration.
 */
class MigrateViewModeSelectorValueFieldHandler extends MigrateValueFieldHandler {
  public function __construct() {
    $this->registerTypes(array('view_mode_selector'));
  }
}
