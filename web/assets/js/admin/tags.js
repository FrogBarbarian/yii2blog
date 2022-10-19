$(document).ready(function () {
    getObjects(construct);
});

table = 'tags';
model = 'Tag';

/**
 * Удаляет тег.
 */
function deleteTag(id) {
    $.ajax({
        url: '/admin-u-i/delete-tag',
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
    const tagsDiv = $('#objects');
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
