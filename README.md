Command line tool to convert PHPDoc @return hints to new PHP 7 return type syntax. (Requires PHP 5.4+)

# Installation

Install the package globally using Composer: `composer global require dchesterton/phpdoc-to-return`

# Usage

Navigate to the directory you wish to convert, then simply call `phpdoc-to-return`.

By default, the tool will overwrite the code but you can optionally specify the source and destination folders using the `--src` and `--dest` options.

    phpdoc-to-return --src=lib --dest=converted

## Hack

The tool has basic support for converting to [Hack Return Types](http://docs.hhvm.com/manual/en/hack.annotations.types.php)

    phpdoc-to-return --src=lib --hack
