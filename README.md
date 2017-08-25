# Project
Magento 2 migrations module based on [phinx](https://github.com/cakephp/phinx).

## Purpose of the repository
Creating tool for migrations in magento, with `rollback` option.

## Usage

### Download
You can add these module into your project by adding lines below into your composer.json `require` section:
```
"adeptofvoltron/magento-migration" : "*",
"symfony/console": "~2.8|~3.0" as v2.6
```
Setting symfony/console alias is required for magento compatibility.

After that use your `composer update` command.

### Using
That will enable `pcf:migration` command from your magento cli.

You have to create at least one directory in your custom modules called `Migrations` as migration files directory.

`pcf:migration` works same way as phinx binary file. [Phinx](https://github.com/cakephp/phinx) documentation can be found here: (Phinx documentation)http://docs.phinx.org]

## Contributing

If you wish to contribute, please read first our [Contribution guide](CONTRIBUTING.md).
