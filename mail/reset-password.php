<?php

declare(strict_types=1);

/**
 * @var string $token
 */

?>
<p>Вы получили это письмо, потому что запросили сброс пароля</p>
<span>Для сброса перейдите по </span>
<a href="<?= HOST ?>/user/new-password?token=<?= $token ?>">ссылке</a>
<p>Если Вы не запрашивали этого, просто игнорируйте письмо</p>