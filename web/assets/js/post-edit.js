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

/**
 *  Заполняет значение 'body' в форме.
 */
function edit(textarea) {
    $('#bodyInput').val(textarea.innerHTML)
}

function formatting(type, showUI = false, value = null) {
    document.execCommand(type, showUI, value);
    $('#inputBody').focus();

    return false;
}

/**
 * Вставляет текст в поле ввода.
 */
function paste(event) {
    let clipboardData, pastedData;
    event.stopPropagation();
    event.preventDefault();
    clipboardData = event.clipboardData || window.clipboardData;
    pastedData = clipboardData.getData('text');
    formatting('insertText', false, pastedData);
}

/**
 * Ловит сочетания клавиш.
 */
$(window).keydown(function (event) {
    if (event.target.id === 'inputBody') {
        if (event.ctrlKey) {
            switch (event.which) {
                case 66:
                    event.preventDefault();
                    formatting('bold');
                    break;
                case 73:
                    event.preventDefault();
                    formatting('italic');
                    break;
                case 85:
                    event.preventDefault();
                    formatting('underline');
                    break;
                case 83:
                    event.preventDefault();
                    formatting('strikethrough');
                    break;
                case 65:
                    event.preventDefault();
                    formatting('selectAll');
                    break;
                case 86:
                    addEventListener('paste', paste);
                    break;
            }
        }

        if (event.keyCode === 9) {
            event.preventDefault();
            formatting('insertText', false, '\t');
        }
    }
})

function quote() {
    formatting('insertHTML', false, '<p class="post-body-quote"></p>');
}
