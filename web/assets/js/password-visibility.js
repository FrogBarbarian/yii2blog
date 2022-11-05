/**
 * Все <input> поля с типом данных 'password'.
 */
let passwordFields = document.querySelectorAll('[type=password]');
/**
 * Кнопка для смены видимости вводимого пароля.
 */
let togglePasswordButton = document.getElementById('togglePasswordButton');
togglePasswordButton.addEventListener('click', showingPassword);

function showingPassword() {
    let typeIsPassword = passwordFields[0].getAttribute('type') === 'password';
    let imageElement = togglePasswordButton.getElementsByTagName('img')[0];
    let imageName = typeIsPassword ? 'password-show.svg' : 'password-hide.svg';
    imageElement.setAttribute('src', `/assets/images/${imageName}`)

    for (const field of passwordFields) {
        field.setAttribute('type', typeIsPassword ? 'text' : 'password');
    }
}
