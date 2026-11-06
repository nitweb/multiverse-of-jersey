<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200)->assertSee('Phone or email');
});

test('users can authenticate with their mobile number', function () {
    $user = User::factory()->create(['phone' => '01711223344']);

    $response = $this->post('/login', [
        'login' => '+8801711223344',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('account.index', absolute: false));
});

test('users can authenticate with their email address', function () {
    $user = User::factory()->create(['email' => 'rahim@example.com']);

    $this->post('/login', ['login' => ' Rahim@Example.com ', 'password' => 'password'])
        ->assertRedirect(route('account.index', absolute: false));

    $this->assertAuthenticatedAs($user);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'login' => $user->phone,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('login');

    $this->post('/login', [
        'login' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('login');

    $this->assertGuest();
});

test('the login must be a mobile number or an email address', function () {
    $this->post('/login', ['login' => 'not-a-login', 'password' => 'password'])->assertSessionHasErrors('login');
    $this->post('/login', ['password' => 'password'])->assertSessionHasErrors('login');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('a signed-in customer is sent from the login page to their account', function () {
    $this->actingAs(User::factory()->create())->get('/login')->assertRedirect(route('account.index'));
});
