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

        // parse title to get all relevant info for the filter and to create a shorter title
        preg_match('/(\S*) \[(.*?)\] \[(\S)\/(\S)\] (.*)/', $title, $matches);

        $chance = $matches[3];
        $impact = $matches[4];
        $ver    = $matches[2];

        // perform filter
        if ($ver == "1.00" && $impact == "H" && ($chance == "M" || $chance == "H")) {
            echo "<item>
                <title>$matches[5] [$chance/$impact] $matches[1] [$ver]</title>
                <link>$link</link>
                <pubdate>$pubdate</pubdate>
                </item>";
        }
     }
     echo "</channel></rss>";
?>
