# Project
Magento migrations module based on [phinx](https://github.com/cakephp/phinx).

## Purpose of the repository
Create tool for migrations in magento, with `rollback` option.

## Usage

### Download
You can add these module in to your project by adding lines below into your composer.json `require` section:
```
"adeptofvoltron/magento-migration" : "*",
"symfony/console": "~2.8|~3.0" as v2.6
```
Line setting symfony/console is required for magento compatibility.

After that use your `composer update` command

That will enable `pcf:migration` comand from your magento cli.

You have to create at least one directory in your custom modules called `Migrations` as migration files directory.

`pcf:migration` works same way as phinx binary file. phinx documentation can be found here: (Phinx docmentation)[http://docs.phinx.org]

## Contributing

If you wish to contribute, please read first our [Contribution guide](CONTRIBUTING.md).
