# eviivo Booking Widget - WordPress plugin

## Install
The front end files (css/js) need to be compiled if you're working of the repository files.

To do so, you will need to have `npm` and `grunt-cli` (`npm install -g grunt-cli`) installed. 

Build command
```
npm install
grunt build
```

## Development
While developing you can use the `watch` task to build the files on the fly.
```
grunt watch
```

## Structure
The plugin tries to implement the PSR-0 standard. All the classed are loaded automatically from the `src` folder, using the `eviivo\Plugin` namespace.

### File structure
- assets - frontend resources.
	- dist - computed automatically
	- source
		- images
		- js
		- less
- config - php config files
- languages - po/mo dictionary files.
- shortcodes 
	- all the files in this folder are registered automatically as short codes.
	- the files name will the shortcode tag name
	- the attributes will be extracted as variables in the current scope. To get to the all the attributes use the `$attributes` variable.
- src/eviivo/Plugin - PHP sources
	- Admin/Hooks/Filters and Admin/Hooks/Filters
		- create static methods in these classes and they will be registered as filters/actions
		- to register a filter/action that has illegal characters in the name for a method (like `acf/ini`), use the $mapping static array property to defined (method_name => hook_name)
	- Pages
		- All files (except Base.php) are registered as admin pages wp-admin. Each file should contain a class with the name of the file in the `eviivo\Plugin\Admin\Pages` namespace, extending viivo\Plugin\Admin\Pages\Base
	- Pages/Form
		- Forms used in wp-admin
	- Ajax
		- Calls - all the files are registered as ajax scripts. Calling `/wp-admin/admin-ajax.php?action=eviivo_[CLASS_NAME]` will call the process() method on the appropriate ajax script
	- Elements
		- Store view helpers, mostly for the Forms
	- Helpers
		- Generic PHP helper classes
	- Hooks - see Admin/Hooks
	- Model - Model class used to store the BookingForm data, this will be serialized and persisted as an array in the *options table
	- Widgets - all the files are registered as Widgets.
	- Main.php - main Controller for the plugin
	- Util.php - misc. usefull functionality as static methods.
- views - templates
- .gitignore
- eviivo-booking-widget.php - plugin entry point
- Gruntfile.js - define build tasks
- package.json - project dependencies
- README.md - infinite recursion

