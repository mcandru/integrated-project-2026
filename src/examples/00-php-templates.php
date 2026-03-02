<?php
require_once "../lib/config.php";

try {
    $featured_stories = Story::findAll(["limit" => 5, "order_by" => "updated_at", "order" => "DESC"]);
    $spotlight = $featured_stories[0];
    $sidebar_stories = array_slice($featured_stories, 1);
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
                    <?php if ($story->img_url): ?>
                        <img src="<?= "../" . htmlspecialchars($story->img_url) ?>" alt="">
                    <?php endif; ?>
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
                    <?php if ($spotlight->img_url): ?>
                        <img src="<?= "../" . htmlspecialchars($spotlight->img_url) ?>" alt="">
                    <?php endif; ?>
                    <h2><?= htmlspecialchars($spotlight->headline) ?></h2>
                    <p><?= htmlspecialchars($spotlight->subheadline) ?></p>
                </div>
                <div class="story-list">
                    <?php foreach ($sidebar_stories as $story) { ?>
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

            <!-- Example 4: Alternating spotlight per category -->
            <div class="width-12"><h2>Example 4: Alternating spotlight per category</h2></div>

            <?php foreach ($categories as $cat_index => $category): ?>
                <?php
                  $cat_stories = Story::findByCategory(
                    $category->id,
                    ["limit" => 4, "order_by" => "created_at", "order" => "DESC"],
                  );
                ?>
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
                            <img src="<?= "../" . htmlspecialchars($spotlight->img_url) ?>" alt="">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($spotlight->headline) ?></h3>
                        <p><?= htmlspecialchars($spotlight->subheadline) ?></p>
                    </div>
                    <div class="story-list">
                        <?php for ($i = 1; $i < count($cat_stories); $i++): ?>
                            <div>
                                <h4><?= htmlspecialchars($cat_stories[$i]->headline) ?></h4>
                                <p><?= htmlspecialchars($cat_stories[$i]->subheadline) ?></p>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>
