<?php

namespace PP\User;

interface Credentials
{

    public function getEmail();

    public function getAuthString();
}
