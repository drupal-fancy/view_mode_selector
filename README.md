# Introduction

Current Maintainers:

* [yannickoo](https://www.drupal.org/u/yannickoo)
* [axe312](https://www.drupal.org/u/axe312)

View Mode Selector creates a new field type called *View Mode Selector* which
will override the view mode of an entity.

# Installation

1. Install the module the [drupal way](http://drupal.org/documentation/install/modules-themes/modules-8).

2. Go to *Manage fields* and just select *View mode selector* as field type.

3. Select view modes that should be provided.

4. As form widget you can choose between a *Select list*, *Radio buttons*
   or *Icons*. *Icons* allows you to upload an icon for a view mode which will
   be used instead of the view mode label in the widget form.

5. Create or edit an entity with the field attached and select a view mode.

6. Every time an entity is rendered with the "view_mode_selector" view mode,
   View Mode Selector will change the view mode to the user selected one.

# Troubleshooting

When the view mode of an entity did not change as expected a solution could be
to check which module are implementing the [`hook_entity_view_mode_alter`](https://api.drupal.org/api/drupal/core!modules!system!entity.api.php/function/hook_entity_view_mode_alter/8), maybe another one overrides the view mode after View Mode Selector did it.
