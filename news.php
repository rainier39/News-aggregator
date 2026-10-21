<?php
/*
 * Copyright © 2025 rainier39 <rainier39@proton.me>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

// Custom news aggregator.

$config = array(
 "timezone" => "America/Los_Angeles"
);

$feeds = array(
 "https://krebsonsecurity.com/feed/",
 "https://www.malwarebytes.com/blog/feed/index.xml",
 "https://www.theregister.com/feed"
);

date_default_timezone_set($config["timezone"]);

$tiles = array();

echo("<!DOCTYPE html>
<html lang='en-US'>
 <head>
  <meta charset='UTF-8'>
  <title>News aggregator</title>
  <link rel='stylesheet' href='./news.css'>
  <meta name='viewport' content='width=device-width,initial-scale=1.0'>
 </head>
 <body>
  <h1>Latest news</h1>
  <div class='tiles'>");

foreach ($feeds as $feed) {
    $raw = file_get_contents($feed);
    $xml = simplexml_load_string($raw);
    $author = $xml->channel->title ?? $xml->title;
    $items = $xml->channel->item ?? $xml->entry;
    foreach ($items as $item) {
        $tile = array();
        $tile["author"] = $author;
        $tile["link"] = $item->link;
        $tile["title"] = $item->title;
        $tile["pubDate"] = strtotime($item->pubDate ?? $item->updated);
        $tile["description"] = $item->description ?? $item->summary ?? $item->content;
        $tiles[] = $tile;
    }
}

// Sort the articles by their timestamp (newest to oldest).
usort($tiles, fn($a, $b) => (-1*($a["pubDate"] <=> $b["pubDate"])));

foreach ($tiles as $tile) {
    echo("<div class='tile'>");
    echo("<a href='" . htmlspecialchars($tile["link"]) . "' target='_blank' rel='nofollow'>");
    echo(htmlspecialchars($tile["title"]));
    echo("</a>");
    echo("<br>");
    echo("<b>");
    echo(htmlspecialchars($tile["author"]));
    echo("</b>");
    echo("<br>");
    echo("<i>");
    echo(htmlspecialchars(date("F j, Y g:i a", $tile["pubDate"])));
    echo("</i>");
    echo("<br>");
    echo("<div class='content'>");
    // Cap the preview to 2048.
    echo(htmlspecialchars(substr($tile["description"], 0, 2048)));
    echo("</div>");
    echo("</div>");
}

echo("  </div>
 <footer>
  News aggregator by <a href='https://github.com/rainier39'>rainier39</a>.
 </footer>
 </body>
</html>")

?>
