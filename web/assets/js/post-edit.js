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
 * Выделенный текст.
 */
let selection = null;
/**
 * Адрес выделения.
 */
let range = null;

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

/**
 * Вставляет форматированный текст.
 */
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

/**
 * Создает блок цитат.
 */
function quote() {
    formatting('insertHTML', false, '<p class="post-body-quote"></p>');
}

/**
 *  Жирный текст.
 */
function bold() {
    formatting('bold');
}

/**
 * Текст курсив.
 */
function italic() {
    formatting('italic');
}

/**
 * Подчеркнутый текст.
 */
function underline() {
    formatting('underline');
}

/**
 * Зачеркнутый текст.
 */
function strikethrough() {
    formatting('strikethrough');
}

/**
 * Текст в верхнем индексе.
 */
function superscript() {
    formatting('superscript');
}

/**
 * Текст в нижнем индексе.
 */
function subscript() {
    formatting('subscript');
}

/**
 * Маркированный список.
 */
function ul() {
    formatting('insertUnorderedList');
}

/**
 * Нумерованный список.
 */
function ol() {
    formatting('insertOrderedList');
}

/**
 * Горизонтальная линия.
 */
function hr() {
    formatting('insertHorizontalRule');
}

/**
 * Заголовок.
 */
function h5() {
    formatting('formatBlock', false, 'h5');
}

/**
 * Очищает форматирование.
 */
function clearFormat() {
    formatting('removeFormat');
}

/**
 * Создает ссылку.
 */
function createUrl() {
    let text = document.getElementById('textUrlInput').value.trim();

    if (text === '') {
        alert('Текст ссылки не может быть пустым')

        return false;
    }

    let url = document.getElementById('urlInput').value.trim();

    if (url === '') {
        alert('Ссылка не может быть пустой')

        return false;
    }

    let html = '<a class="post-body-link" href="' +
        url +
        '">' +
        text +
        '</a>';
    selection.addRange(range)
    formatting('insertHtml', false, html);
    selection = null;
    range = null;
    closeModalDiv();
}

/**
 * Удаляет ссылку.
 */
function removeLink() {
    let text = window.getSelection().toString();
    formatting('unlink', false, text);
    clearFormat();
}

/**
 * Создает окно для вставки ссылки.
 */
function linkModal() {
    selection = window.getSelection();
    range = selection.getRangeAt(0)

    $('#modalDiv').html(
        '<div id="modalWindow" class="modal-window-back" tabindex="-1">' +
        '<div class="modal-window">' +
        '<div class="modal-window-header">' +
        'Добавить ссылку' +
        '<button type="button" class="btn-close" onclick="closeModalDiv()">' +
        '</button>' +
        '</div>' +
        '<input id="textUrlInput" class="form-control mb-1" placeholder="Текст ссылки" value="' +
        selection.toString() +
        '">' +
        '<input id="urlInput" class="form-control" placeholder="Вставьте ссылку">' +
        '<div class="modal-window-footer">' +
        '<button type="button" class="toolbar-button me-1" onclick="closeModalDiv()" style="width: auto; font-weight: lighter">' +
        'Отмена' +
        '</button>' +
        '<button type="button" onclick="createUrl()" class="toolbar-button" style="width: auto; font-weight: lighter">' +
        'Добавить ссылку' +
        '</button>' +
        '</div>' +
        '</div>' +
        '</div>'
    );
}

/**
 * Создает окно для загрузки изображения.
 */
function imageModal() {
    $.ajax({
        url: '/post-u-i/image-modal',
        cache: false,
        type: 'post',
        data: {_csrf: $('meta[name=csrf-token]').attr("content")},
        success: function (response) {
            $('#modalDiv').html(response);
        }
    });
}

//TODO: DO IT
function uploadImage() {
    let form = $('#uploadFileForm').serialize();
    let data = {
        _csrf: $('meta[name=csrf-token]').attr("content"),
        ajax: {
            formData: form,
        }
    }
    $.ajax({
        url: '/post-u-i/upload-image',
        cache: false,
        type: 'post',
        data: data,
        success: function (response) {
            console.log(response);
        }
    });
}
