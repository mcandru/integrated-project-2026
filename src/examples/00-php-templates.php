<?php
require_once "../lib/config.php";
?>
<html>
    <head>
        <title>Example 00 – PHP Templates</title>
        <link rel="stylesheet" href="../css/reset.css">
        <link rel="stylesheet" href="../css/grid.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <h1>PHP Templates: Loops &amp; Layouts</h1>

                <p>This example fetches 5 stories from the database and renders them in a grid. The first story is styled differently as a featured card using a check on the loop index. As you work through this, ask yourself: <strong>"Is there duplicated code here that I could automate programmatically?"</strong></p>

                <h2>Best practices</h2>
                <ul>
                    <li><strong>Let data drive the layout</strong> &mdash; Instead of hardcoding HTML for each story or category, loop over query results. If you find yourself copying and pasting a block of HTML, that block could be inside a loop.</li>
                    <li><strong>Use the loop index for conditional styling</strong> &mdash; PHP's <code>foreach ($items as $index => $item)</code> gives you a numeric index you can use to change classes, widths, or visibility on specific positions (e.g. the first item, every third item).</li>
                    <li><strong>Extract repeated markup into partials</strong> &mdash; When the same card HTML appears in a spotlight section, a featured section, and a category section, move it into its own file (e.g. <code>lib/story_card.php</code>) and <code>include</code> it with variables. One change updates every section at once.</li>
                    <li><strong>Nest loops for grouped layouts</strong> &mdash; To show stories grouped by category, loop over categories first, then loop over each category's stories inside. This avoids duplicating an entire section per category.</li>
                    <li><strong>Keep logic out of the template where possible</strong> &mdash; Fetch and organise your data (queries, sorting, grouping) in PHP at the top of the file <em>before</em> the HTML begins. The template below should focus on outputting that prepared data.</li>
                </ul>

                <h2>How this example works</h2>
                <ol>
                    <li><strong>Fetch stories</strong> &mdash; <code>Story::findAll()</code> accepts an options array with <code>limit</code>, <code>order_by</code>, and <code>order</code> to control the query.</li>
                    <li><strong>Check the index</strong> &mdash; Inside the <code>foreach</code>, we use <code>$index === 0</code> to give the first story a wider column and a different class.</li>
                    <li><strong>Conditional class and width</strong> &mdash; The first story gets <code>width-8 featured</code> (spanning two-thirds of the grid), while the rest get <code>width-4</code> (one-third each).</li>
                </ol>

                <h2>Patterns to explore next</h2>
                <ul>
                    <li><strong>Spotlight + featured grid</strong> &mdash; One hero story at full width, then a row of smaller featured cards below it. Think about how the card markup is the same in both cases, just with different classes.</li>
                    <li><strong>Category sections with nested loops</strong> &mdash; Loop over <code>Category::findAll()</code>, and inside each iteration loop over that category's stories. The section HTML is written once but renders for every category.</li>
                    <li><strong>Alternating row layouts</strong> &mdash; Image on the left for even rows, image on the right for odd rows. Use <code>$index % 2</code> to flip a class &mdash; no need to write two separate HTML blocks.</li>
                    <li><strong>Data-driven navigation</strong> &mdash; Instead of hardcoding a <code>&lt;a&gt;</code> for each category in the navbar, loop over <code>Category::findAll()</code> and generate the links dynamically.</li>
                </ul>
            </div>

            <!-- Example 1: Featured first story -->
            <div class="width-12"><h2>Example 1: Featured first story</h2></div>

            <?php $stories = Story::findAll(["limit" => 5, "order_by" => "updated_at", "order" => "DESC"]); ?>

            <?php foreach ($stories as $index => $story): ?>
                <div class="<?php if ($index === 0): ?>width-8 featured<?php else: ?>width-4<?php endif; ?>">
                    <?php if ($story->img_url): ?>
                        <img src="<?= htmlspecialchars($story->img_url) ?>" alt="">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($story->headline) ?></h3>
                    <p><?= htmlspecialchars($story->subheadline) ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Example 2: Category sections with nested loops -->
            <div class="width-12"><h2>Example 2: Stories by category</h2></div>

            <?php
            $categories = Category::findAll();
            ?>

            <?php foreach ($categories as $category): ?>
                <div class="width-12">
                    <h3><?= htmlspecialchars($category->name) ?></h3>
                </div>
                <?php $category_stories = Story::findByCategory($category->id, ["limit" => 3, "order_by" => "created_at", "order" => "DESC"]); ?>
                <?php foreach ($category_stories as $story): ?>
                    <div class="width-4">
                        <?php if ($story->img_url): ?>
                            <img src="<?= htmlspecialchars($story->img_url) ?>" alt="">
                        <?php endif; ?>
                        <h4><?= htmlspecialchars($story->headline) ?></h4>
                        <p><?= htmlspecialchars($story->subheadline) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <!-- Example 3: Alternating spotlight per category -->
            <div class="width-12"><h2>Example 3: Alternating spotlight per category</h2></div>

            <?php foreach ($categories as $cat_index => $category): ?>
                <?php $cat_stories = Story::findByCategory($category->id, ["limit" => 4, "order_by" => "created_at", "order" => "DESC"]); ?>
                <?php if (count($cat_stories) === 0) continue; ?>
                <?php $spotlight = $cat_stories[0]; ?>
                <?php $remaining = array_slice($cat_stories, 1); ?>

                <div class="width-12">
                    <h3><?= htmlspecialchars($category->name) ?></h3>
                </div>

                <!-- Spotlight on one side, story list on the other — flips per category -->
                <div class="width-12 spotlight-row <?php if ($cat_index % 2 !== 0): ?>flip<?php endif; ?>">
                    <div class="spotlight featured">
                        <?php if ($spotlight->img_url): ?>
                            <img src="<?= htmlspecialchars($spotlight->img_url) ?>" alt="">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($spotlight->headline) ?></h3>
                        <p><?= htmlspecialchars($spotlight->subheadline) ?></p>
                    </div>
                    <div class="story-list">
                        <?php foreach ($remaining as $story): ?>
                            <div>
                                <h4><?= htmlspecialchars($story->headline) ?></h4>
                                <p><?= htmlspecialchars($story->subheadline) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>
