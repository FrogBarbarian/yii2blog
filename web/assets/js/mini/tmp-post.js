/**
 * ID поста.
 */
const postId = params.get('id');

/**
 * Удаляет пост по ID.
 */
document.getElementById('deleteTempPostButton').onclick = () => {
    $.ajax({
        url: '/post-ajax/temp-delete',
        cache: false,
        data: {postId: postId},
        success: function () {
            location.href = '/profile';
        }
    });
}