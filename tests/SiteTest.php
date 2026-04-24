<?php

use Model\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SiteTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $_SERVER['DOCUMENT_ROOT'] = '/var/www/html';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . '/../vendor/autoload.php';

        if (!isset($GLOBALS['app'])) {
            $GLOBALS['app'] = require __DIR__ . '/../core/bootstrap.php';
        }

        if (!function_exists('app')) {
            function app()
            {
                return $GLOBALS['app'];
            }
        }
    }

    protected function setUp(): void
    {
        $_GET = [];
        $_POST = [];
        $_REQUEST = [];
        $_FILES = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    #[DataProvider('signupProvider')]
    public function testSignup(string $httpMethod, array $userData, string $expectedText): void
    {
        if ($userData['login'] === 'login_is_busy' || $userData['email'] === 'email_is_busy') {
            $existingUser = User::query()->first();

            if (!$existingUser) {
                $existingUser = User::create([
                    'name' => 'TestUser',
                    'login' => 'existing_login',
                    'email' => 'existing@test.com',
                    'password' => md5('123456'),
                    'role' => 'decanat'
                ]);
            }

            if ($userData['login'] === 'login_is_busy') {
                $userData['login'] = $existingUser->login;
            }

            if ($userData['email'] === 'email_is_busy') {
                $userData['email'] = $existingUser->email;
            }
        }

        $request = $this->createMock(\Src\Request::class);
        $request->method = $httpMethod;

        $request->expects($this->any())
            ->method('all')
            ->willReturn($userData);

        $request->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($field) use ($userData) {
                return $userData[$field] ?? null;
            });

        ob_start();
        (new \Controller\Site())->signup($request);
        $output = ob_get_clean();

        $this->assertIsString($output);
        $this->assertStringContainsString($expectedText, $output);
    }

    public static function signupProvider(): array
    {
        return [
            [
                'GET',
                [
                    'name' => '',
                    'login' => '',
                    'email' => '',
                    'password' => ''
                ],
                '<form'
            ],
            [
                'POST',
                [
                    'name' => '',
                    'login' => '',
                    'email' => '',
                    'password' => ''
                ],
                'Поле name обязательно'
            ],
            [
                'POST',
                [
                    'name' => 'Оля',
                    'login' => 'login_is_busy',
                    'email' => 'newmail@test.com',
                    'password' => '123456'
                ],
                'Поле login должно быть уникально'
            ],
            [
                'POST',
                [
                    'name' => 'Оля',
                    'login' => 'new_login_123',
                    'email' => 'email_is_busy',
                    'password' => '123456'
                ],
                'Поле email должно быть уникально'
            ],
            [
                'POST',
                [
                    'name' => 'О',
                    'login' => 'ab',
                    'email' => 'badmail',
                    'password' => '123'
                ],
                'Поле name слишком короткое'
            ],
        ];
    }

    #[DataProvider('loginProvider')]
public function testLogin(string $httpMethod, array $userData, string $expectedText): void
{
    $request = $this->createMock(\Src\Request::class);
    $request->method = $httpMethod;

    $request->expects($this->any())
        ->method('all')
        ->willReturn($userData);

    ob_start();
    (new \Controller\Site())->login($request);
    $output = ob_get_clean();

    $this->assertIsString($output);
    $this->assertStringContainsString($expectedText, $output);
}

public static function loginProvider(): array
{
    return [
        [
            'GET',
            [],
            '<form'
        ],
        [
            'POST',
            [
                'login' => 'wrong_login',
                'password' => 'wrong_password'
            ],
            'Неправильные логин или пароль'
        ],
    ];
}
}