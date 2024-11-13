<?php

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Error\LoaderError;

class Template
{
    private static $twig;

    public static function getTwig()
    {
        if (!self::$twig) {
            $loader = new FilesystemLoader(__DIR__ . '/../../templates');
            self::$twig = new Environment($loader, [
                'strict_variables' => true, // Activate strict variables
            ]);
        }

        return self::$twig;
    }

    public static function render($template, $variables = [])
    {
        try {
            return self::getTwig()->render($template, $variables);
        } catch (LoaderError $e) {
            return "<p>Template not found: $template</p>";
        }
    }
}
