<?php

it('boots the welcome route', function () {
    $this->get('/')->assertOk();
});
