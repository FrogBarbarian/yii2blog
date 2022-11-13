<div class="nav-item post-search mx-1">
    <form action="/" class="ms-0">
        <label>
            <input onblur="removeSuggest()" onfocus="suggestSearch(this)" autocomplete="off"
                   oninput="suggestSearch(this)" type="text" placeholder="Поиск" name="search">
        </label>
        <button type="submit">
            <img src="<?= IMAGES ?>icons/search.svg" alt="search" width="16" height="16">
        </button>
    </form>
    <ul id="suggestedForSearch" class="list-group" style=""></ul>
</div>
