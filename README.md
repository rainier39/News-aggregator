# News aggregator
A basic news aggregator written in PHP. Currently configured via editing the `$config` array, and the `$feeds` array. The `$feeds` array should consist of URLs leading to valid RSS/Atom feeds on blogs or news sites you would like to see articles from. There is a default list of feeds which may be modified to your heart's content.

The software is reasonably secure (no XSS), but very basic, not the most robust, and not thoroughly tested. Use at your own risk.

## Dependencies
- Some webserver (e.g. Apache2, Nginx)
- PHP 7.4+
- php-xml (may or may not be prepackaged with PHP)
