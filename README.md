# PHP Class Menu for the Tigra Framework

This is the Class Menu which is used by Tigra.

## Installation
Install the package using composer.
```
composer require tigra/menu
```

or by editing composer.json yourself and adding:
```
"require": {
    "tigra/menu": "*"
}
```

## External packages

This package includes the CSS and JavaScript file from Github repository [Bootstrap 4 Responsive Navbar with Multi level Dropdowns](https://github.com/bootstrapthemesco/bootstrap-4-multi-dropdown-navbar) from developer [BootstrapThemes.co](http://bootstrapthemes.co/).

If you want to use multi level Dropdowns for your Bootstrap menu, don't forget to add following lines to your twig-template:

    <script src="{{ BASE_URL }}/vendor/tigra/menu/src/js/bootstrap-4-navbar.js"></script>
    <link href="{{ BASE_URL }}/vendor/tigra/menu/src/css/bootstrap-4-navbar.css" rel="stylesheet">
