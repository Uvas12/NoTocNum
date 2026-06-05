# NoTocNum


**NoTocNum** is a MediaWiki extension that allows editors to hide automatic table of contents numbering on selected pages using the magic word `__NOTOCNUMBERS__` behavior switch.

It can also hide table of contents numbering on special pages when enabled through configuration.

## Features

- Adds the `__NOTOCNUMBERS__` behavior switch.
- Hides table of contents numbering only on pages that use `__NOTOCNUMBERS__`.
- Removes `__NOTOCNUMBERS__` from the visible page output.
- Supports optional application on special pages.
- Does not require editing `MediaWiki:Common.css`.
- Does not add database tables.
- Includes support for classic TOC numbering and Vector/Vector 2022 TOC number classes.

## Requirements

- MediaWiki 1.35 or later

## Installation

Download or clone this repository into the `extensions/` directory of your MediaWiki installation:

```bash
cd extensions
git clone https://github.com/YOUR-USERNAME/NoTocNum.git

Add the following line to your LocalSettings.php file:

`wfLoadExtension( 'NoTocNum' );` 
