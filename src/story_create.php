<?php
require_once './lib/config.php';
require_once './lib/global.php';
require_once './lib/session.php';

startSession();

try {
    $categories = Category::findAll();
    $authors = Author::findAll();
    $locations = Location::findAll();
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Story</title>
</head>
<body>
    <?php require_once "./lib/navbar.php"; ?>
    <?php require_once "./lib/flash_message.php"; ?>

    <h1>Create Story</h1>

    <form action="story_store.php" method="POST">

        <div class="input">
            <label for="headline">Headline:</label>
            <div>
                <input type="text" id="headline" name="headline" value="<?= old('headline') ?>" required>
                <p class="error"><?= error('headline') ?></p>
            </div>
        </div>

        <div class="input">
            <label for="category_id">Category:</label>
            <div>
                <select name="category_id" id="category_id">
                    <?php foreach ($categories as $cat) { ?>
                        <option value="<?= $cat->id ?>" <?= chosen('category_id', $cat->id) ? 'selected' : '' ?>><?= $cat->name ?></option>
                    <?php } ?>
                </select>
                <p class="error"><?= error('category_id') ?></p>
            </div>
        </div>

        <div class="input">
            <label for="article">Article:</label>
            <div>
                <textarea name="article" id="article" required><?= old('article') ?></textarea>
                <p class="error"><?= error('article') ?></p>
            </div>
        </div>

        <!-- TODO: Add fields for short_headline, subheadline, img_url, author, category, and location -->

        <button type="submit">Create Story</button>
    </form>
</body>
</html>

<?php
// TODO: Clear form data and errors from the session after displaying the form
