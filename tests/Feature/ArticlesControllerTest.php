<?php

test('GET /api/articles is available', function () {
    $response = $this->get('/api/articles');

    $response->assertStatus(200)
        ->assertJson(['data' => []]);
});

test('GET /api/authors is available', function () {
    $response = $this->get('/api/authors');

    $response->assertStatus(200)
        ->assertJson(['data' => []]);
});

test('GET /api/sources is available', function () {
    $response = $this->get('/api/sources');

    $response->assertStatus(200)
        ->assertJson(['data' => []]);
});
