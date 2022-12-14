$(document).ready(function () {
    getObjects(construct);
});

/**
 *@see table
 */
table = 'tags';

/**
 * @see model
 */
model = 'Tag';

/**
 * Удаляет тег.
 */
function deleteTag(id) {
    $.ajax({
        url: '/admin-ajax/delete-tag',
        cache: false,
        data: {id: id},
        success: function () {
            $('#tag_' + id).html('Тег удален')
        }
    });
}

/**
 * Отрисовывает теги.
 */
function construct(tags) {
    let tagsDiv = $('#objects');
    tagsDiv.html('');

    tags.forEach((tag) => {
        tagsDiv.html(tagsDiv.html() +
            '<a class="tag-card suggested-tag" href="/tag/' +
            tag['tag'] +
            '">' +
            tag['tag'] + '[' + tag['amount_of_uses'] + ']' +
            '</a>'
        );
    });
}
