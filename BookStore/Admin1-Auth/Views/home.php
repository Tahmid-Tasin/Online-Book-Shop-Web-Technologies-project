<section class="hero">
    <div>
        <p class="eyebrow">Browse, search, cart, checkout</p>
        <h1>Find books by category, author, genre, or title.</h1>
    </div>
    <form class="search-row" id="search-form">
        <input id="search-q" name="q" placeholder="Search books">
        <select id="search-filter" name="filter">
            <option value="title">Book Name</option>
            <option value="author">Author</option>
            <option value="genre">Genre</option>
        </select>
        <button class="button primary" type="submit">Search</button>
    </form>
</section>

<section class="category-bar">
    <?php foreach ($categories as $category): ?>
        <a class="chip" href="<?= url('category/' . $category['id']) ?>"><?= e($category['name']) ?></a>
    <?php endforeach; ?>
</section>

<section>
    <div class="toolbar">
        <h2><?= e($heading) ?></h2>
    </div>
    <div class="book-grid" id="book-list">
        <?php foreach ($books as $book): ?>
            <?php require __DIR__ . '/books/card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
