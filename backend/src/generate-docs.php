<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use OpenApi\Generator;

// Definer søkestien for annotasjoner
$directoriesToScan = [__DIR__ . '/app'];
$openapi = Generator::scan($directoriesToScan);

$outputDir = __DIR__ . '/swagger';
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Hent miljøvariabelen eller bruk en standardverdi
$frontendUrl = defined('BACKEND_URL') ? BACKEND_URL : 'default.example.com';

// Konverter spesifikasjonen til JSON
$json = $openapi->toJson();

// Erstatt placeholderen "FRONTEND_URL" med verdien fra miljøvariabelen
$json = str_replace('BACKEND_URL', $frontendUrl, $json);

// Lagre til JSON-fil
file_put_contents($outputDir . '/swagger.json', $json);

echo "Swagger specification generated successfully!\n";
