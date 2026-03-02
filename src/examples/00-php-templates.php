<?php
require_once "../lib/config.php";

try {
    $featured_stories = Story::findAll(["limit" => 5, "order_by" => "updated_at", "order" => "DESC"]);
    $categories = Category::findAll();
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}
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

            <!-- Example 1: Featured first story -->
            <div class="width-12"><h2>Example 1: Featured first story</h2></div>

            <?php foreach ($featured_stories as $index => $story) { ?>
                <div class="<?= $index === 0 ? 'width-8 featured' : 'width-4'; ?>">
                    <?php if ($story->img_url) { ?>
                        <img src="<?= "../" . htmlspecialchars($story->img_url) ?>" alt="">
                    <?php } ?>
                    <h3><?= htmlspecialchars($story->headline) ?></h3>
                    <p><?= htmlspecialchars($story->subheadline) ?></p>
                </div>
            <?php } ?>

            <!-- Example 2: Spotlight + featured grid -->
            <!-- Rule of thumb: use one loop when item structure is the same, split
             when layout containers differ significantly (like here) -->
            <div class="width-12"><h2>Example 2: Spotlight + featured grid</h2></div>

            <div class="width-12 spotlight-row">
                <div class="spotlight featured">
                    <?php if ($featured_stories[0]->img_url) { ?>
                        <img src="<?= "../" . htmlspecialchars($featured_stories[0]->img_url) ?>" alt="">
                    <?php } ?>
                    <h2><?= htmlspecialchars($featured_stories[0]->headline) ?></h2>
                    <p><?= htmlspecialchars($featured_stories[0]->subheadline) ?></p>
                </div>
                <div class="story-list">
                    <?php foreach (array_slice($featured_stories, 1) as $story) { ?>
                        <div>
                            <h4><?= htmlspecialchars($story->headline) ?></h4>
                            <p><?= htmlspecialchars($story->subheadline) ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Example 3: Category sections with nested loops -->
            <div class="width-12"><h2>Example 3: Stories by category</h2></div>

            <?php foreach ($categories as $category) { ?>
                <div class="width-12">
                    <h3><?= htmlspecialchars($category->name) ?></h3>
                </div>
                <?php $category_stories = Story::findByCategory($category->id, ["limit" => 3, "order_by" => "created_at", "order" => "DESC"]); ?>
                <?php foreach ($category_stories as $story) { ?>
                    <div class="width-4">
                        <?php if ($story->img_url) { ?>
                            <img src="<?= "../" . htmlspecialchars($story->img_url) ?>" alt="">
                        <?php } ?>
                        <h4><?= htmlspecialchars($story->headline) ?></h4>
                        <p><?= htmlspecialchars($story->subheadline) ?></p>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </body>
</html>
