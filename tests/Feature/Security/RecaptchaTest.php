<?php

use App\Rules\RecaptchaRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

test('the recaptcha rule passes when no secret key is configured', function () {
    config(['services.recaptcha.secret_key' => null]);

    $validator = Validator::make(
        ['g-recaptcha-response' => null],
        ['g-recaptcha-response' => [new RecaptchaRule]],
    );

    expect($validator->passes())->toBeTrue();
});

test('the recaptcha rule fails when configured and no token is provided', function () {
    config(['services.recaptcha.secret_key' => 'test-secret']);

    $validator = Validator::make(
        ['g-recaptcha-response' => null],
        ['g-recaptcha-response' => [new RecaptchaRule]],
    );

    expect($validator->fails())->toBeTrue();
});

test('the recaptcha rule passes when google confirms a valid high-score token', function () {
    config(['services.recaptcha.secret_key' => 'test-secret']);
    Http::fake(['google.com/*' => Http::response(['success' => true, 'score' => 0.9])]);

    $validator = Validator::make(
        ['g-recaptcha-response' => 'valid-token'],
        ['g-recaptcha-response' => [new RecaptchaRule]],
    );

    expect($validator->passes())->toBeTrue();
});

test('the recaptcha rule fails when google reports a low score', function () {
    config(['services.recaptcha.secret_key' => 'test-secret']);
    Http::fake(['google.com/*' => Http::response(['success' => true, 'score' => 0.1])]);

    $validator = Validator::make(
        ['g-recaptcha-response' => 'suspicious-token'],
        ['g-recaptcha-response' => [new RecaptchaRule]],
    );

    expect($validator->fails())->toBeTrue();
});
