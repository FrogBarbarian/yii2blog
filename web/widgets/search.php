<div class="nav-item post-search mx-1">
    <form action="/" class="ms-0">
        <input onblur="removeSuggest()" onfocus="suggestSearch(this)" autocomplete="off" oninput="suggestSearch(this)" type="text" placeholder="Поиск" name="search">
        <button type="submit">
            <img src="../../assets/images/search.svg" alt="Logo" width="16" height="16">
        </button>
    </form>
    <ul id="suggestedSearchField" class="list-group" style="position: absolute; top: 80%; width: 150px"></ul>
</div>