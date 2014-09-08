<?php

/**
 * @file
 * Administrative interface for View Mode Selector.
 */

/**
 * Configuration form.
 */
function view_mode_selector_config($form, &$form_state) {
  $form = array(
    '#tree' => TRUE,
    '#attached' => array(
      'css' => array(
        drupal_get_path('module', 'view_mode_selector') . '/css/view-mode-selector.css',
      ),
    ),
    '#submit' => array('view_mode_selector_config_submit'),
  );

  foreach (entity_get_info() as $entity_type => $entity_info) {
    $name = 'view_mode_selector_' . $entity_type;

    foreach ($entity_info['bundles'] as $bundle_name => $bundle) {
      $variable_name = $name . '[' . $bundle_name . ']';

      foreach ($entity_info['view modes'] as $view_mode_name => $view_mode) {
        if ($view_mode['custom settings']) {
          $variable = variable_get('view_mode_selector_' . $entity_type, array());

          if (!isset($form[$name])) {
            $form[$name] = array(
              '#type' => 'fieldset',
              '#title' => $entity_info['label'],
              '#collapsible' => TRUE,
            );
          }

          if (!isset($form[$name][$bundle_name]['_enable'])) {
            $form[$name][$bundle_name]['_enable'] = array(
              '#type' => 'checkbox',
              '#title' => t('Enable view mode selector for %bundle', array('%bundle' => $bundle['label'])),
              '#default_value' => $variable[$bundle_name]['_enable'],
            );
          }

          if (!isset($form[$name][$bundle_name]['_style'])) {
            $form[$name][$bundle_name]['_style'] = array(
              '#type' => 'select',
              '#title' => t('Selector style'),
              '#description' => t('Choose the list style. When "Select list" is selected the icon will be displayed next to the view mode name.'),
              '#options' => array(
                'radios' => t('Radio buttons'),
                'select' => t('Select list'),
              ),
              '#default_value' => $variable[$bundle_name]['_style'],
              '#states' => array(
                'visible' => array(
                  'input[name="' . $variable_name . '[_enable]"]' => array('checked' => TRUE),
                ),
              ),
            );
          }

          if (!isset($form[$name][$bundle_name]['view_modes'])) {
            $form[$name][$bundle_name]['view_modes'] = array(
              '#type' => 'fieldset',
              '#title' => t('View modes'),
              '#collapsible' => TRUE,
              '#states' => array(
                'visible' => array(
                  'input[name="' . $variable_name . '[_enable]"]' => array('checked' => TRUE),
                ),
              ),
            );
          }

          $form[$name][$bundle_name]['view_modes'][$view_mode_name] = array(
            '#type' => 'managed_file',
            '#title' => t('@view-mode', array('@view-mode' => $view_mode['label'])),
            '#description' => t('An icon which can be used for a view mode preview.'),
            '#upload_location' => 'public://view-mode-selector/' . $entity_type,
            '#default_value' => $variable[$bundle_name][$view_mode_name],
          );
        }
      }
    }
  }

  return system_settings_form($form);
}

/**
 * Submit function for configuration form.
 */
function view_mode_selector_config_submit(&$form, &$form_state) {
  foreach (element_children($form_state['values']) as $key) {
    $entity_type = $form_state['values'][$key];

    // Skip unimportant things.
    if (strpos($key, 'view_mode_selector_') !== 0) {
      continue;
    }

    foreach ($entity_type as $bundle_name => $settings) {
      foreach ($settings as $name => $value) {
        // Skip enable indicator.
        if ($name == '_enable' || $name == '_style') {
          continue;
        }

        // Save icon permanently.
        if ($value) {
          $file = file_load($value);
          $file->status = FILE_STATUS_PERMANENT;
          file_save($file);
        }
      }
    }
  }
}
