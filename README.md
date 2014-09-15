CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Installation
 * Troubleshooting


INTRODUCTION
------------

Current Maintainers:

* [yannickoo](http://drupal.org/user/531118)

View Mode Selector creates a new field type called *View mode selector* which will override the view mode of an entity.

INSTALLATION
------------

1. Install the module the [drupal way](http://drupal.org/documentation/install/modules-themes/modules-7).

2. Go to *Manage fields* and just select *View mode selector* as field type.

3. Now you can choose between a *Select list* and a *Radio buttons*. BTW the *Radio buttons* widget also allows you to upload an icon for a view mode which will be used instead of the view mode label in the widget form.

4. Select view modes that should be provided.

5. Create or edit an entity with the field attached and select a view mode.

TROUBLESHOOTING
---------------

When the view mode of an entity did not change as expected a solution could be to check which module are implementing the [`hook_entity_view_mode_alter`](https://api.drupal.org/api/drupal/modules!system!system.api.php/function/hook_entity_view_mode_alter/7), maybe another one overrides the view mode after View mode selector did it.
