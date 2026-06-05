# NoTocNum

**NoTocNum** is a MediaWiki extension that allows editors to hide automatic table of contents numbering on selected pages using the `__NOTOCNUMBERS__` behavior switch.

It can also hide table of contents numbering on special pages when enabled through configuration.

## Features

- Adds the `__NOTOCNUMBERS__` behavior switch.
- Hides table of contents numbering only on pages that use `__NOTOCNUMBERS__`.
- Removes `__NOTOCNUMBERS__` from the visible page output.
- Supports optional application on special pages.
- Does not require editing `MediaWiki:Common.css`.
- Does not add database tables.
- Supports classic MediaWiki table of contents numbering.
- Includes additional support for Vector and Vector 2022 table of contents number classes.

## Requirements

- MediaWiki 1.35 or later
- PHP version supported by your MediaWiki installation

## Installation

Download or clone this repository into the `extensions/` directory of your MediaWiki installation:

```bash
cd extensions
git clone https://github.com/YOUR-USERNAME/NoTocNum.git
```

Add the following line to your `LocalSettings.php` file:

```php
wfLoadExtension( 'NoTocNum' );
```

Then go to `Special:Version` on your wiki to verify that the extension has been installed successfully.

## Configuration

### `$wgNoTocNumEnableSpecialPages`

By default, NoTocNum does not affect special pages:

```php
$wgNoTocNumEnableSpecialPages = false;
```

To enable NoTocNum on special pages, add the following configuration to your `LocalSettings.php` file:

```php
$wgNoTocNumEnableSpecialPages = true;
```

Recommended setup:

```php
wfLoadExtension( 'NoTocNum' );

$wgNoTocNumEnableSpecialPages = true;
```

When this option is enabled, table of contents numbering will also be hidden on special pages.

## Usage

To hide table of contents numbering on a normal wiki page, add the following behavior switch anywhere in the page wikitext:

```text
__NOTOCNUMBERS__
```

Example:

```text
__NOTOCNUMBERS__

== History ==

=== Early years ===

=== Later years ===

== See also ==

=== Related topic ===
```

The table of contents will still be displayed, but the automatic numbering will be hidden.

For example, instead of:

```text
1 History
  1.1 Early years
  1.2 Later years
2 See also
  2.1 Related topic
```

the table of contents will appear as:

```text
History
  Early years
  Later years
See also
  Related topic
```

## Behavior switch

NoTocNum registers the following double-underscore behavior switch:

```text
__NOTOCNUMBERS__
```

This marker indicates that table of contents numbering should be hidden on the current page.

The marker is removed from the rendered page output.

## Special pages

Most special pages do not have normal editable wikitext. Because of that, `__NOTOCNUMBERS__` usually cannot be added directly to a special page.

Special pages are controlled through the configuration variable:

```php
$wgNoTocNumEnableSpecialPages = true;
```

If this option is set to `false`, special pages are not affected.

## How it works

For normal wiki pages, NoTocNum checks the source wikitext of the current page. If the page contains `__NOTOCNUMBERS__`, the extension adds inline CSS to hide table of contents number elements.

For special pages, NoTocNum uses the `$wgNoTocNumEnableSpecialPages` configuration variable.

The extension targets common table of contents numbering classes such as:

```css
.tocnumber
.vector-toc-numb
```

This allows the extension to work with the classic MediaWiki table of contents and with skins that use Vector-related table of contents number classes.


## Compatibility notes

NoTocNum is intended for MediaWiki 1.35 or later.

The extension does not create database tables and does not require running update scripts.

## License

This extension is licensed under the MIT License.

See the `LICENSE` file for details.
