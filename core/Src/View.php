<?php
namespace Src;

use Exception;

class View
{
    private string $view = '';
    private array $data = [];
    private string $root = '';
    private string $layout = '/layouts/main.php';

    public function __construct(string $view = '', array $data = [])
    {
        $this->root = $this->getRoot();
        $this->view = $view;
        $this->data = $data;
    }

    // Полный путь до директории с представлениями
    private function getRoot(): string
    {
        global $app;
        $root = $app->settings->getRootPath();
        $path = $app->settings->getViewsPath();
        return $_SERVER['DOCUMENT_ROOT'] . $root . $path;
    }

    // Путь до основного файла с шаблоном сайта (с поддержкой разных layout)
    private function getPathToMain(string $layout = 'main'): string
    {
        return $this->root . '/layouts/' . $layout . '.php';
    }

    // Путь до текущего шаблона
    private function getPathToView(string $view = ''): string
    {
        $view = str_replace('.', '/', $view);
        return $this->getRoot() . "/$view.php";
    }

    // Рендер с выбором layout
    public function render(string $view = '', array $data = [], string $layout = 'main'): string
    {
        $path = $this->getPathToView($view);
        $layoutPath = $this->getPathToMain($layout);
        
        if (file_exists($layoutPath) && file_exists($path)) {
            extract($data, EXTR_PREFIX_SAME, '');
            ob_start();
            require $path;
            $content = ob_get_clean();
            return require($layoutPath);
        }
        throw new Exception('Error render');
    }

    // Магический метод для вызова render() при использовании объекта как строки
    public function __toString(): string
    {
        return $this->render($this->view, $this->data);
    }
}