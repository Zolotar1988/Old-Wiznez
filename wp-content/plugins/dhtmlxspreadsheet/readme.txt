=== Spreadsheet ===
Contributors: dhtmlx
Donate link: http://dhtmlx.com/docs/products/licenses.shtml
Tags: spreadsheet, table, data table, datagrid, data grid, excel, database, grid
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 2.0


This plugin allows you to quickly create an Excel-like, editable spreadsheet with basic cell formatting and math functions support.


== Description ==

With dhtmlxSpreadsheet, you can quickly add a nice-looking, editable data table on your WordPress page. The plugin takes the content from the database table and displays it in an Excel-like spreadsheet with basic cell formatting, math functions, and keyboard navigation support. It offers an effective solution if you need to organize and share some data online in a table view.


The spreadsheet can be displayed in edit or read-only mode. All changes made in a table on a web page (add/edit/delete records) are automatically saved to the database. dhtmlxSpreadsheet provides built-in export to PDF and Excel, as well as clipboard support, allowing you to copy and paste data from Excel documents right into your browser-based table.

Requirements

- PHP 5.0 or greater
- MySQL 4.0 or greater
- Doesn't work for WPMU

== Installation ==

1. Upload the content of spreadsheet.zip to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place anywhere in post or on page [[spreadsheet]] marker

for more details check
http://docs.dhtmlx.com/doku.php?id=dhtmlxspreadsheet:pluginguide_wordpress

== Frequently Asked Questions ==

= Spreadsheet throws "Incorrect XML" error  =

Most probably you are using php 4.x , which is not supported.

= I have a question - ... = 

If something is still not clear - you can ask your question at [dhtmlx support forum](http://forum.dhtmlx.com/viewforum.php?f=9)

== Screenshots ==

1.  dhtmlxSpreadsheet - basic text and cell formatting
2.  dhtmlxSpreadsheet - math operations
3.  dhtmlxSpreadsheet - export to PDF

== Changelog ==

= 1.0 =
Initial release.

= 2.0 =
Cell validation
Lock/unlock
Using database prefix
Improved ctrl+c/ctrl+v support, support for ctrl+x
Hotkeys support
Undo operation
Copy selection
Absolute links in formulas (Excel-like functionality)
Improved copying (relative formulas and styles)