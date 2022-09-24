<?php

namespace app\interfaces;

interface UserData
{
    public function getUser (): array|bool;
}