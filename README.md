# PHPStan <!-- omit in toc -->

- [Overview](#overview)
- [Rules](#rules)
- [Install](#install)
- [Ignoring errors](#ignoring-errors)
  - [Ignoring in code using PHPDocs](#ignoring-in-code-using-phpdocs)
  - [Ignoring in configuration file](#ignoring-in-configuration-file)
  - [Reporting unused ignores](#reporting-unused-ignores)
  - [Exclude entire file/paths](#exclude-entire-filepaths)
- [VScode](#vscode)
- [Extending](#extending)

## Overview

PHPStan (PHP STatic ANalyzer) is a static analyzer designed to discover bugs without the need to run tests.
PHP compiles at runtime so compiler errors and bugs appear when called directly, or indirectly via tests.

Home: <https://phpstan.org/>
VScode: [SanderRonde.phpstan-vscode](https://marketplace.visualstudio.com/items?itemName=SanderRonde.phpstan-vscode)

## Rules

PHPStan uses a system of 10 "levels" (0-9). `0` is the default level.
Each level includes all previous level checks.

Level overview:

<!-- markdownlint-disable -->
<!-- textlint-disable -->
0. Basic checks, unknown classes, unknown functions, unknown methods called on `$this`, wrong number of arguments passed to those methods and functions, always undefined variables
1. Possibly undefined variables, unknown magic methods and properties on classes with `__call` and `__get`
2. Unknown methods checked on all expressions (not just `$this`), validating PHPDocs
3. Return types, types assigned to properties
4. Basic dead code checking - always false `instanceof` and other type checks, dead else branches, unreachable code after return; etc.
5. Checking types of arguments passed to methods and functions
6. Report missing `typehints`
7. Report partially wrong union types - if you call a method that only exists on some types in a union type, level 7 starts to 8.report that; other possibly incorrect situations
8. Report calling methods and accessing properties on nullable types
9. Be strict about the mixed type - the only allowed operation you can do with it is to pass it to another mixed
<!-- textlint-enable -->
<!-- markdownlint-enable -->

## Install

1. Add via composer

    ```shell
    composer require --dev phpstan/phpstan
    ```

2. Run via CLI. For Example: `phpstan analyse [path]`

    ```shell
    phpstan analyse modules/custom
    ```

3. (optional ) Add configuration file in the project root: [`phpstan.neon`](./phpstan.neon)

    ```yml
    # phpstan.neon
    parameters:
      level: 4
      paths:
        - app/
      excludes_analyse:
        - *Test.php
    ```

## Ignoring errors

@see <https://phpstan.org/user-guide/ignoring-errors#ignoring-in-code-using-phpdocs>

### Ignoring in code using PHPDocs

- Use PHP comments styles (`//`, `/* */`, `/** */`)

    ```php
    echo $foo; /** @phpstan-ignore-line */

    /** @phpstan-ignore-next-line */
    echo $foo;
    ```

### Ignoring in configuration file

- Update the `phpstan.neon` file to target a specific error message.

    ```yml
    parameters:
      ignoreErrors:
        - '#Call to an undefined method [a-zA-Z0-9\\_]+::doFoo\(\)#'
        - '#Call to an undefined method [a-zA-Z0-9\\_]+::doBar\(\)#'
    ```

- Can also include `count` and/or `path` to target specific files.

    ```yml
    parameters:
      ignoreErrors:
        message: '#Call to an undefined method [a-zA-Z0-9\\_]+::doFoo\(\)#'
        path: other/dir/DifferentFile.php
        count: 2 # optional
    ```

PHPStan provides an online generator to create valid entries:

- <https://phpstan.org/user-guide/ignoring-errors#generate-an-ignoreerrors-entry>

### Reporting unused ignores

- Configure PHPStan to report ignored errors that do not occur.

    ```yml
    parameters:
     reportUnmatchedIgnoredErrors: false
    ```

### Exclude entire file/paths

- Configure PHPStan to ignore specific files or directories.

  ```yml
    parameters:
      excludePaths:
        - tests/*/data/*
    ```

### Generating a baseline

A baseline marks current errors as "acceptable".
Use the baseline feature when:

- You want to upgrade to a higher version of PHPStan.
- Correct errors at your own pace by remove them.

```shell
$ phpstan --generate-baseline
[OK] Baseline generated with 2 errors.
```

It generates a `phpstan-baseline.neon` file that contains all the current errors.
Use `includes` in `phpstan.neon` file to allow

```yml
# phpstan.neon
includes:
- phpstan-baseline.neon

parameters:
# your usual configuration options
```

## VScode

Homepage: [SanderRonde.phpstan-vscode](https://marketplace.visualstudio.com/items?itemName=SanderRonde.phpstan-vscode)

```json
{
  "phpstan.enabled": true,
  "phpstan.enableStatusBar": false,
  "phpstan.suppressTimeoutMessage": true,
}
```

## Extending

PHPStan targets generic projects out of the box.
PHPStan community contains official and unofficial extensions that target popular frameworks including:

- [Symfony Framework](https://github.com/phpstan/phpstan-symfony) (official)
- [PHPUnit](https://github.com/phpstan/phpstan-phpunit) (official)
- [Mockery](https://github.com/phpstan/phpstan-mockery) (official)
- [Larastan](https://github.com/larastan/larastan) (unofficial)
- [Drupal](https://github.com/mglaman/phpstan-drupal) (unofficial)
- [CakePHP](https://github.com/CakeDC/cakephp-phpstan) (unofficial)
- [Prophecy](https://github.com/Jan0707/phpstan-prophecy) (unofficial)

Use third-party extensions to extend PHPStan's capabilities.

- [phpstan-todo-by](https://github.com/staabm/phpstan-todo-by)
