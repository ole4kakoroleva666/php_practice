<?php

use Model\Department;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DepartmentTest extends TestCase
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

    $user = \Model\User::where('login', 'test_decanat')->first();

    if (!$user) {
        $user = \Model\User::create([
            'name' => 'Test Decanat',
            'login' => 'test_decanat',
            'email' => 'test_decanat@example.com',
            'password' => md5('123456'),
            'role' => 'decanat'
        ]);
    }

    $_SESSION['id'] = $user->id;
}
    #[DataProvider('departmentsProvider')]
    public function testDepartments(string $httpMethod, array $data, string $expectedText): void
    {
        if (($data['name'] ?? '') === 'existing_department') {
            $existing = Department::query()->first();

            if (!$existing) {
                $existing = Department::create([
                    'name' => 'Существующая кафедра'
                ]);
            }

            $data['name'] = $existing->name;
        }

        $request = $this->createMock(\Src\Request::class);
        $request->method = $httpMethod;

        $request->expects($this->any())
            ->method('all')
            ->willReturn($data);

        $request->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($field) use ($data) {
                return $data[$field] ?? null;
            });

        ob_start();
        (new \Controller\Site())->departments($request);
        $output = ob_get_clean();

        $this->assertIsString($output);
        $this->assertStringContainsString($expectedText, $output);
    }

    public static function departmentsProvider(): array
{
    return [
        [
            'GET',
            [],
            'Название кафедры'
        ],
        [
            'POST',
            ['name' => ''],
            'Название кафедры обязательно'
        ],
        [
            'POST',
            ['name' => 'А'],
            'Название кафедры слишком короткое'
        ],
        [
            'POST',
            ['name' => 'existing_department'],
            'Такая кафедра уже существует'
        ],
    ];
}
}

