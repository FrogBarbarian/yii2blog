$(document).ready(function () {
    const tagsArea = $('#tagsArea');
    const tagsString = $('#posteditorform-tags').val();
    const tagsArray = tagsString.split('#');
    tagsArray.shift();

    tagsArray.forEach((tag) => {
        tagsArea.html(tagsArea.html() +
            '<span onclick="removeTag(this)" class="tag-card">' +
            tag +
            '</span>'
        );
    });

    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 1,
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                buttonsPanel.removeAttribute('class');
                buttonsPanel.style.removeProperty('left');
                buttonsPanel.lastChild.remove();
            } else {
                buttonsPanel.className = 'absolute-buttons-panel';
                let hideToolbarButton = document.createElement('button');
                hideToolbarButton.className = 'toolbar-button';
                hideToolbarButton.type = 'button';
                hideToolbarButton.innerHTML = '&#10005;';
                hideToolbarButton.title = 'Скрыть панель';
                hideToolbarButton.onclick = () => {
                    if (buttonsPanel.style.left !== '-20px') {
                        hideToolbarButton.title = 'Показать панель';
                        buttonsPanel.style.left = '-20px';
                    } else {
                        hideToolbarButton.title = 'Скрыть панель';
                        buttonsPanel.style.left = '0px';
                    }
                }
                buttonsPanel.appendChild(hideToolbarButton);
            }

        });
    }, options);

    const target = document.querySelector('#toolbar');
    const buttonsPanel = document.querySelector('#buttons');
    observer.observe(target);
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
 *  Изображения в посте, на момент открытия редактора.
 */
let startImages = [];

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
    const tags = $('#posteditorform-tags');
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
    document.getElementById('inputBody').focus();
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
        '" target="_blank">' +
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
    setRange();
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
 * Проверяет родительский элемент на соответствие.
 */
function checkParent(element) {
    if (element.tagName === 'HTML') {
        return false;
    }

    if (element.id === 'inputBody') {
        return true;
    }

    return checkParent(element.parentElement);
}

/**
 * Устанавливает диапазон выделения.
 */
function setRange() {
    const inputBody = document.getElementById('inputBody');
    selection = window.getSelection();
    let anchor = selection.anchorNode;

    if (checkParent(anchor) !== true) {
        range = new Range();
        range.setStart(inputBody, 0);
        range.setEnd(inputBody, 0);

        return false;
    }

    range = selection.getRangeAt(0);

    return true;
}


/**
 * Создает окно для загрузки изображения.
 */
function imageModal() {
    setRange();
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

/**
 * Добавляет изображение и подпись.
 */
function uploadImage() {
    let form = $('#uploadImageForm');
    let formData = new FormData(form[0]);
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        cache: false,
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            $('#imageErrorLabel').html('');
            $('#signatureErrorLabel').html('');

            if (Array.isArray(response)) {
                closeModalDiv();
                let html = '<div class="post-image">' +
                    '<img src="uploads/' +
                    response[0] +
                    '" alt="">' +
                    '<span>' +
                    response[1] +
                    '</span>' +
                    '</div>' +
                    '<br>';
                selection.addRange(range);
                formatting('insertHtml', false, html);
                selection = null;
                range = null;
            } else {
                let errors = Object.entries(response);

                errors.forEach((object) => {
                    $('#' + object[0] + 'ErrorLabel').html(object[1]);
                });
            }
        }
    });
}

/**
 * Отправляет пост на проверку.
 */
function submitPost() {
    let img = document.getElementById('inputBody').querySelectorAll('img');
    let endImages = [];
    img.forEach(img => {
        endImages.push(img.src);
    });
    const form = $('#postEditorForm');
    const formData = new FormData(form[0]);
    formData.append('startImages', startImages.toString());
    formData.append('endImages', endImages.toString());
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        cache: false,
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (Array.isArray(response) === true) {
                let id = response[1];
                let url = id === null
                    ? '/'
                    : '/post?id=' + id;

                return location.href = url;
            }

            document.querySelectorAll('[id$=ErrorLabel]').forEach(object => {
                object.innerHTML = '';
            });

            let errors = Object.entries(response);

            errors.forEach((object) => {
                $('#' + object[0] + 'ErrorLabel').html(object[1]);
            });

            return false;
        }
    });
}
