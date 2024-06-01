# Laravel Blog
This package allows you to store some global settings into your app database and load them once you need them in entire app globally.

## Installation

Install my-project with npm

```bash
  composer require moh6mmad/laravel-settings
```
    
## Documentation
You may call set and get functions in several ways. Settings are stored in database as following: 
`settings_group` and `name` and `value`
You need to call both `settings_group` and `name` to access to the `value`

## Usage