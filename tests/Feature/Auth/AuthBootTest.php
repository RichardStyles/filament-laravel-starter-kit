<?php

it('boots the welcome route', function (): void {
    $this->get('/')->assertOk();
});
