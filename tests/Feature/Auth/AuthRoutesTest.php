<?php

it('redirects guests from /dashboard to /login', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});
