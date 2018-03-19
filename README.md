# moodle-local_adminsettingsconfig

This is local plugin that adds a new setting type for your plugins. Currently, this plugin just adds a JSON setting type. The plugin ensures that the text inserted into the textarea is valid JSON, and throws an error if it isn't.

If you're interested in adding some more setting types just do a pull request on the git repo.

This plugin has no UI of its own, it's primarily aimed at developers who will add this plugin as a dependency in their own plugins.

## Installing
1.  Drop code into /local/adminsettingsconfig
2.  Go to Site administration -> Notifications to install
3.  Add
```
$plugin->dependencies = ['local_adminsettingsconfig' => 2018031900]
```
to *your* plugin's version.php file.

4.  Include a setting in *your* plugin's settings.php file.
```
$setting = new \local_adminsettingsconfig\admin_setting_configjson($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);
```

5. No need to use require_once or include_once.
