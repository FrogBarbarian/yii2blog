/**
 * Все <input> поля с типом данных 'password'.
 */
const passwordFields = document.querySelectorAll('[type=password]');
/**
 * Кнопка для смены видимости вводимого пароля.
 */
const togglePasswordButton = document.getElementById('togglePasswordButton');
togglePasswordButton.addEventListener('click', () => {
    const typeIsPassword = passwordFields[0].getAttribute('type') === 'password';
    const imageElement = togglePasswordButton.getElementsByTagName('img')[0];
    const imageName = typeIsPassword ? 'password-show.svg' : 'password-hide.svg';
    imageElement.setAttribute('src', `/assets/images/${imageName}`)

    for (const field of passwordFields) {
        field.setAttribute('type', typeIsPassword ? 'text' : 'password');
    }
});