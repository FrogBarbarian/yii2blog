$(document).ready(function () {
    const tagsArea = $('#tagsArea');
    const tagsString = $('#postinteractionsform-tags').val();
    const tagsArray = tagsString.split('#');
    tagsArray.shift();

    tagsArray.forEach((tag) => {
        tagsArea.html(tagsArea.html() +
            '<span onclick="removeTag(this)" class="tag-card">' +
            tag +
            '</span>'
        );
    });
});

/**
 * Удаляет выбранный тег.
 */
function removeTag(tag) {
    let tags = $('#postinteractionsform-tags');
    tag.remove();
    tag = '#' + tag.innerHTML;
    tags.val(tags.val().replace(tag, ''));
}

/**
 * Добавляет тег.
 */
function addTag(tag = '') {
    $('#suggestedTags').html('');
    const tagsField = $('#tagField');

    if (tagsField.val() === '' && tag === '') {
        return false;
    }

    if (tag === '') {
        tag = tagsField.val();
    }

    const tagsArea = $('#tagsArea');
    const tags = $('#postinteractionsform-tags');
    tags.val(tags.val() + '#' + tag);
    tagsArea.html(
        tagsArea.html() +
        '<span onclick="removeTag(this)" class="tag-card">' +
        tag +
        '</span>'
    );
    tagsField.val('');
}

/**
 * Отображает список предложенных тегов.
 */
function fillTagField(field) {
    const suggestedTags = $('#suggestedTags');

    if (field.value === '') {
        suggestedTags.html('')

        return false;
    }

    $.ajax({
        url: '/post-u-i/search-tags',
        cache: false,
        data: {input: field.value},
        success: function (response) {
            if (response === false) {
                suggestedTags.html(
                    '<li class="mt-1 list-group-item small text-danger">' +
                    'Теги не найдены, добавьте свой, нажатием на <b>+</b>' +
                    '</li>');

                return false;
            }

            suggestedTags.html('');

            response.forEach((tag) => {
                suggestedTags.html(suggestedTags.html() +
                    '<li class="mt-1 list-group-item suggested-tag" onclick="addTag(\'' +
                    tag['tag'] +
                    '\')">' +
                    tag['tag'] +
                    '</li>'
                );
            });
        }
    });
}
