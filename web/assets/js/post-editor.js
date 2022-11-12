$(document).ready(function () {
    const tagsArea = $('#tagsArea');
    const tags = $('#tagsInput').val();
    const tagsArray = tags.split('#');
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
            } else {
                buttonsPanel.className = 'absolute-buttons-panel';
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
 * Событие потери фокуса.
 */
const blurEvent = new Event ('blur');
/**
 * Div контейнер для ввода содержания поста.
 */
const inputBodyContainer = document.getElementById('inputBody');
/**
 * Поле ввода для поиска тегов.
 */
const inputTagContainer = document.getElementById('tagField');
inputBodyContainer.onblur = () => {
    document.getElementById('bodyInput').dispatchEvent(blurEvent)
}
inputTagContainer.onblur = () => {
    document.getElementById('tagsInput').dispatchEvent(blurEvent)
}

/**
 * Удаляет выбранный тег.
 */
function removeTag(tag) {
    document.getElementById('tagsInput').dispatchEvent(blurEvent)
    const tags = $('#tagsInput');
    tag.remove();
    tag = '#' + tag.innerHTML;
    tags.val(tags.val().replace(tag, ''));
}

/**
 * Добавляет тег.
 */
function addTag(tag = '') {
    document.getElementById('tagsInput').dispatchEvent(blurEvent)
    $('#suggestedTags').html('');
    if (inputTagContainer.value === '' && tag === '') {
        return false;
    }

    if (tag === '') {
        tag = inputTagContainer.value;
    }

    const tagsArea = $('#tagsArea');
    const tags = $('#tagsInput');
    tags.val(`${tags.val()}#${tag}`);
    tagsArea.html(
        tagsArea.html() +
        '<span onclick="removeTag(this)" class="tag-card">' +
        tag +
        '</span>'
    );
    inputTagContainer.value = '';
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
        url: '/post-editor-ajax/search-tags',
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
                    '<li class="mt-1 list-group-item message-suggested-user" onclick="addTag(\'' +
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
    inputBodyContainer.focus();
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
    document.execCommand('insertText', false, pastedData);
}

/**
 * Ловит сочетания клавиш.
 */
$(window).keydown(function (event) {
    if (event.target.id === inputBodyContainer.id) {
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
    const text = document.getElementById('textUrlInput').value.trim();

    if (text === '') {
        alert('Текст ссылки не может быть пустым')

        return false;
    }

    const url = document.getElementById('urlInput').value.trim();

    if (url === '') {
        alert('Ссылка не может быть пустой')

        return false;
    }

    const html = '<a class="post-body-link" href="' +
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
    const text = window.getSelection().toString();
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
        '<div class="modal-window" style="max-width: 350px">' +
        '<div class="modal-window-header">' +
        'Добавить ссылку' +
        '<button type="button" class="btn-close" onclick="closeModalDiv()">' +
        '</button>' +
        '</div>' +
        '<input id="textUrlInput" class="txt-input-basic" placeholder="Текст ссылки" value="' +
        selection.toString() +
        '">' +
        '<input id="urlInput" class="txt-input-basic" placeholder="Вставьте ссылку">' +
        '<div class="modal-window-footer">' +
        '<button type="button" class="btn-basic" onclick="closeModalDiv()">' +
        'Отмена' +
        '</button>' +
        '<button type="button" onclick="createUrl()" class="btn-basic">' +
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

    if (element.id === inputBodyContainer.id) {
        return true;
    }

    return checkParent(element.parentElement);
}

/**
 * Устанавливает диапазон выделения.
 */
function setRange() {
    selection = window.getSelection();
    const anchor = selection.anchorNode;

    if (checkParent(anchor) !== true) {
        range = new Range();
        range.setStart(inputBodyContainer, 0);
        range.setEnd(inputBodyContainer, 0);

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
        url: '/post-editor-ajax/create-image-upload-modal-window',
        cache: false,
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
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response !== false) {
                closeModalDiv();
                let html = '<div class="post-image">' +
                    '<img src="/uploads/' +
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
            }

            shakeModal();
        }
    });
}

/**
 * Отправляет пост на проверку.
 */
function submitPost() {
    const form = $('#postEditorForm');
    const formData = new FormData(form[0]);
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        cache: false,
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response === false) {
                return false;
            }

            let id = response;
            let url = id === true
                ? '/'
                : '/post?id=' + id;

            return location.href = url;
        }
    });
}
