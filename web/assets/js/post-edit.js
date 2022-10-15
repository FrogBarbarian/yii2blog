$(document).ready(function () {
    const tagsString = $('#postinteractionsform-tags').val();
    const tagsArray = tagsString.split('#');
    const tagsArea = $('#tagsArea');
    tagsArray.shift();

    tagsArray.forEach((tag) => {
        tagsArea.html(tagsArea.html() +
            '<span onclick="removeTag(this)" class="tag-card">' +
            tag +
            '</span>'
        );
    });
});

function removeTag(tag) {
    tag.remove();
}
