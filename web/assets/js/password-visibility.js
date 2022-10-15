const togglePassword = document.querySelector('#togglePassword');
const passwordVisibility = document.querySelector('#passwordInput');
const confirmPassword = document.querySelector('#confirmPasswordInput');

togglePassword.addEventListener('click', function () {
    const passwordShowed = passwordVisibility.getAttribute('type') === 'text'
    const passwordType = passwordShowed ? 'password' : 'text';
    const imgName = passwordShowed ? 'hide' : 'show';
    const eyeImg = togglePassword.children;
    passwordVisibility.setAttribute('type', passwordType);
    eyeImg[0].setAttribute(
        'src',
        '/assets/images/password-' +
        imgName +
        '.svg'
    );

    if (confirmPassword !== null) {
        confirmPassword.setAttribute('type', passwordType);
    }
});