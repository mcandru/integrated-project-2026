<?php
require_once "../lib/config.php";

$stories = Story::findAll(["limit" => 9, "order_by" => "updated_at", "order" => "DESC"]);
$authors = Author::findAll();
$categories = Category::findAll();
?>
<html>
    <head>
        <title>Example 02 – PHP Strings &amp; Dates</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/grid.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <h1>PHP Strings &amp; Dates for Templating</h1>

                <p>When you pull data from a database, it rarely looks the way you want to display it. Dates come back as <code>"2025-03-15 14:30:00"</code> instead of "March 15, 2025". Headlines might be too long for a card layout. Author names need to be combined and properly cased. This example covers the string and date functions you will use most when formatting output in your templates.</p>
            </div>

            <!-- 1. Date formatting -->
            <div class="width-12"><h2>1. Date formatting with date() and strtotime()</h2></div>

            <div class="width-12">
                <p>Database datetime values are strings like <code>"2025-03-15 14:30:00"</code>. Use <code>strtotime()</code> to convert them to a Unix timestamp, then <code>date()</code> to format them however you want. Common format characters: <code>F</code> = full month, <code>M</code> = short month, <code>j</code> = day, <code>Y</code> = 4-digit year, <code>g:i A</code> = time with AM/PM.</p>
            </div>

            <?php foreach (array_slice($stories, 0, 4) as $story): ?>
                <div class="width-6">
                    <h4><?= htmlspecialchars($story->headline) ?></h4>
                    <p>
                        Full: <strong><?= date("F j, Y", strtotime($story->created_at)) ?></strong><br>
                        Short: <strong><?= date("M j", strtotime($story->created_at)) ?></strong><br>
                        With time: <strong><?= date("M j, Y \a\\t g:i A", strtotime($story->created_at)) ?></strong>
                    </p>
                </div>
            <?php endforeach; ?>

            <!-- 3. Truncating -->
            <div class="width-12"><h2>3. Truncating with substr and strlen</h2></div>

            <div class="width-12">
                <p>Article text is often too long for a card preview. <code>substr($string, 0, 100)</code> grabs the first 100 characters. Use <code>strlen()</code> to check whether the text is actually longer before adding &ldquo;&hellip;&rdquo; so short text doesn&rsquo;t get a misleading ellipsis.</p>
            </div>

            <?php foreach (array_slice($stories, 0, 3) as $story): ?>
                <div class="width-4">
                    <h4><?= htmlspecialchars($story->headline) ?></h4>
                    <?php
                    $limit = 100;
                    $text = $story->article;
                    if (strlen($text) > $limit) {
                        $preview = substr($text, 0, $limit) . "...";
                    } else {
                        $preview = $text;
                    }
                    ?>
                    <p><?= htmlspecialchars($preview) ?></p>
                    <p><small>Length: <?= strlen($text) ?> chars &rarr; showing <?= min(strlen($text), $limit) ?></small></p>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>
