<?php

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('sip calculator page can be visited', function () {
    $response = $this->get(route('sip-calculator'));

    $response->assertOk()
        ->assertSee('SIP Calculator');
});
