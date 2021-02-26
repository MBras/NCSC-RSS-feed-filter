<?php
    $domOBJ = new DOMDocument();
    $domOBJ->load("https://advisories.ncsc.nl/rss/advisories");//XML page URL
   
    $content = $domOBJ->getElementsByTagName("item");
     
    header( "Content-type: text/xml");

    echo "<?xml version='1.0' encoding='UTF-8'?>
    <rss version='2.0'>
    <channel>
    <title>NCSC extract for OST</title>
    <link>/</link>
    <description>filtering chance (M,H) and damage (H) and version (1.00)</description>
    <language>en-us</language>";

    foreach( $content as $data )
    {
        $title = $data->getElementsByTagName("title")->item(0)->nodeValue;
        $link = $data->getElementsByTagName("link")->item(0)->nodeValue;
        $pubdate = $data->getElementsByTagName("pubDate")->item(0)->nodeValue;

        preg_match('/(\S*) \[(.*?)\] \[(\S)\/(\S)\] .* verholpen in (.*)/', $title, $matches);

        if ($matches[2] == "1.00" && $matches[4] == "H" && ($matches[3] == "M" || $matches[3] == "H")) {
            $title = $matches[5] . " [" . $matches[3] . "/" . $matches[4] . "] " . $matches[1] . " [" . $matches[2] . "]";
            echo "<item>
                <title>$title</title>
                <link>$link</link>
                <pubdate>$pubdate</pubdate>
                </item>";
        }
     }
     echo "</channel></rss>";
?>
