---
marp: true
theme: default
paginate: true
---

# PHP for Templating

Loops, layouts, arrays, dates, and strings: the tools to turn database rows into finished pages.

---

## What is Templating?

PHP sits between your **data** and your **HTML**.

```
Database  →  PHP logic  →  HTML output  →  Browser
```

Your job: fetch data, loop over it, format it, and output clean HTML.

The template should focus primarily on **displaying** prepared data, not on figuring out _what_ to display. Data should be fetched at the top of the template.

---

## Best Practices

- **Let data drive the layout:** loop over query results instead of hardcoding HTML for specific items
- **Use the loop index for conditional styling**
- **Nest loops for grouped layouts or hierarchical data**
- **Keep logic above the HTML:** fetch, sort, and group your data, where possible, _before_ the template begins

---

## Don't Repeat Yourself (DRY)

If you're copying and pasting a block of HTML, that block should be inside a **loop**.

```php
<!-- ❌ Copy-pasting a card for each story -->
<div class="width-4"><h4>Story 1 headline</h4></div>
<div class="width-4"><h4>Story 2 headline</h4></div>
<div class="width-4"><h4>Story 3 headline</h4></div>

<!-- ✅ One card, driven by data -->
<?php foreach ($stories as $story): ?>
    <div class="width-4">
        <h4><?= htmlspecialchars($story->headline) ?></h4>
    </div>
<?php endforeach; ?>
```

---

## Loops & Layouts

`foreach` is the core of every template.

```php
<?php foreach ($stories as $index => $story): ?>
    <div class="<?= $index === 0 ? 'width-8 featured' : 'width-4' ?>">
        <h4><?= htmlspecialchars($story->headline) ?></h4>
    </div>
<?php endforeach; ?>
```

The **index** lets you conditionally style specific items in an array from within the loop.

---

## Arrays: Your Toolkit

| Function                  | What it does    | Template use                            |
| ------------------------- | --------------- | --------------------------------------- |
| `$arr[0]`                 | First element   | Spotlight / hero story                  |
| `count($arr)`             | Number of items | "Showing 5 stories", empty-state checks |
| `array_slice($arr, 0, 3)` | First 3 items   | Featured row vs. rest of grid           |
| `$index % 2`              | Even/odd check  | Zebra striping, alternating layouts     |

```php
$featured = array_slice($stories, 0, 2);
$rest = array_slice($stories, 2);
```

Split one query into two layout sections.

---

## Date Formatting

Database dates look like `"2025-03-15 14:30:00"` but users expect **"March 15, 2025"**.

Two functions: `strtotime()` converts the string to a timestamp, `date()` formats it.

```php
$d = $story->created_at; // "2025-03-15 14:30:00"

date("F j, Y", strtotime($d));          // March 15, 2025
date("M j", strtotime($d));             // Mar 15
date("M j, Y \a\\t g:i A", strtotime($d)); // Mar 15, 2025 at 2:30 PM
```

| `F` full month | `M` short month | `j` day | `Y` year | `g:i A` time |
| -------------- | --------------- | ------- | -------- | ------------ |

Full list of format characters: [php.net/datetime.format](https://www.php.net/manual/en/datetime.format.php)

---

## String Functions

**Truncating** long text for card previews:

```php
$limit = 100;
$text = $story->article;

if (strlen($text) > $limit) {
    $preview = substr($text, 0, $limit) . "...";
} else {
    $preview = $text;
}
```

| Function               | What it does                   |
| ---------------------- | ------------------------------ |
| `strlen($str)`         | Returns the length of a string |
| `substr($str, 0, 100)` | Extracts first 100 characters  |

Only add `"..."` when there's actually more text to hide.

---

## Escaping Output

Always wrap user-sourced data in `htmlspecialchars()` before echoing it:

```php
<!-- ❌ XSS vulnerability -->
<h4><?= $story->headline ?></h4>

<!-- ✅ Safe output -->
<h4><?= htmlspecialchars($story->headline) ?></h4>
```

If someone stores `<script>alert('hacked')</script>` as a headline, escaping turns it into harmless text instead of running it.
