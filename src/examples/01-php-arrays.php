<?php
require_once "../lib/config.php";

$stories = Story::findAll(["limit" => 9, "order_by" => "updated_at", "order" => "DESC"]);
$categories = Category::findAll();
?>
<html>
    <head>
        <title>Example 01 – PHP Arrays for Templating</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/grid.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <h1>PHP Arrays for Templating</h1>

                <p>This example covers the PHP array functions you will use most when building templates. Each section fetches real data from the database and demonstrates one technique. These are the same tools used in <a href="00-php-templates.php">Example 00</a> &mdash; here we isolate each one so you can see exactly what it does.</p>
            </div>

            <!-- 1. Array indexing -->
            <div class="width-12"><h2>1. Array indexing</h2></div>

            <div class="width-12">
                <p>When you have an array of results, you can grab specific items by their numeric index. <code>$stories[0]</code> is the first element and <code>$stories[count($stories) - 1]</code> is the last.</p>
            </div>

            <?php $first = $stories[0]; ?>
            <?php $last = $stories[count($stories) - 1]; ?>

            <div class="width-6 featured">
                <h3>Spotlight (first story)</h3>
                <?php if ($first->img_url): ?>
                    <img src="<?= htmlspecialchars($first->img_url) ?>" alt="">
                <?php endif; ?>
                <h4><?= htmlspecialchars($first->headline) ?></h4>
                <p><?= htmlspecialchars($first->subheadline) ?></p>
            </div>

            <div class="width-6">
                <h3>Oldest story (last in array)</h3>
                <?php if ($last->img_url): ?>
                    <img src="<?= htmlspecialchars($last->img_url) ?>" alt="">
                <?php endif; ?>
                <h4><?= htmlspecialchars($last->headline) ?></h4>
                <p><?= htmlspecialchars($last->subheadline) ?></p>
            </div>

            <!-- 2. foreach with index -->
            <div class="width-12"><h2>2. foreach with index</h2></div>

            <div class="width-12">
                <p>A plain <code>foreach ($items as $item)</code> gives you each value. Adding a key &mdash; <code>foreach ($items as $index =&gt; $item)</code> &mdash; also gives you the numeric position. Use it to number a list or style the first/last item differently.</p>
            </div>

            <div class="width-6">
                <h3>Numbered headline list</h3>
                <ol>
                    <?php foreach (array_slice($stories, 0, 5) as $index => $story): ?>
                        <li><?= ($index + 1) ?>. <?= htmlspecialchars($story->headline) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <div class="width-6">
                <h3>First/last class styling</h3>
                <ul>
                    <?php $subset = array_slice($stories, 0, 5); ?>
                    <?php $last_index = count($subset) - 1; ?>
                    <?php foreach ($subset as $index => $story): ?>
                        <?php
                        $class = "";
                        if ($index === 0) $class = "featured";
                        if ($index === $last_index) $class = "featured";
                        ?>
                        <li class="<?= $class ?>"><?= htmlspecialchars($story->headline) ?><?php if ($class): ?> <em>(<?= $index === 0 ? "first" : "last" ?>)</em><?php endif; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- 3. array_slice -->
            <div class="width-12"><h2>3. array_slice</h2></div>

            <div class="width-12">
                <p><code>array_slice($array, $offset, $length)</code> extracts a portion of an array without modifying the original. This is how you split query results into a "featured" section and a "rest" section &mdash; the same pattern used in Example 00's alternating spotlight.</p>
            </div>

            <?php $featured = array_slice($stories, 0, 2); ?>
            <?php $rest = array_slice($stories, 2); ?>

            <div class="width-12"><h4>Featured row (first 2)</h4></div>

            <?php foreach ($featured as $story): ?>
                <div class="width-6 featured">
                    <?php if ($story->img_url): ?>
                        <img src="<?= htmlspecialchars($story->img_url) ?>" alt="">
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($story->headline) ?></h4>
                    <p><?= htmlspecialchars($story->subheadline) ?></p>
                </div>
            <?php endforeach; ?>

            <div class="width-12"><h4>Regular grid (the rest)</h4></div>

            <?php foreach ($rest as $story): ?>
                <div class="width-4">
                    <?php if ($story->img_url): ?>
                        <img src="<?= htmlspecialchars($story->img_url) ?>" alt="">
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($story->headline) ?></h4>
                </div>
            <?php endforeach; ?>

            <!-- 4. count -->
            <div class="width-12"><h2>4. count</h2></div>

            <div class="width-12">
                <p><code>count($array)</code> returns the number of elements. Use it to show a total, or to check whether a query returned any results before rendering a section.</p>
            </div>

            <div class="width-12">
                <p><strong>Showing <?= count($stories) ?> stories</strong></p>
            </div>

            <?php foreach ($categories as $category): ?>
                <?php $cat_stories = Story::findByCategory($category->id, ["limit" => 3]); ?>
                <div class="width-4">
                    <h4><?= htmlspecialchars($category->name) ?></h4>
                    <?php if (count($cat_stories) === 0): ?>
                        <p><em>No stories found in this category.</em></p>
                    <?php else: ?>
                        <p><?= count($cat_stories) ?> <?= count($cat_stories) === 1 ? "story" : "stories" ?></p>
                        <ul>
                            <?php foreach ($cat_stories as $story): ?>
                                <li><?= htmlspecialchars($story->headline) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- 5. Modulo for patterns -->
            <div class="width-12"><h2>5. Modulo for patterns</h2></div>

            <div class="width-12">
                <p><code>$index % 2</code> returns 0 for even positions and 1 for odd &mdash; perfect for zebra-striping a list. <code>$index % 3</code> cycles through three states, useful for assigning column classes or rotating accent colours.</p>
            </div>

            <div class="width-6">
                <h3>Zebra striping (% 2)</h3>
                <table style="width:100%; border-collapse:collapse;">
                    <?php foreach (array_slice($stories, 0, 6) as $index => $story): ?>
                        <tr style="background: <?= $index % 2 === 0 ? '#e8f4e8' : '#ffffff' ?>; padding: 4px;">
                            <td style="padding: 6px;"><?= $index + 1 ?></td>
                            <td style="padding: 6px;"><?= htmlspecialchars($story->headline) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </body>
</html>
