<?php

namespace App\Core;

class Response
{
    private $contentType;
    private $data;

    public function __construct($data, $contentType = 'text/html')
    {
        $this->data = $data;
        $this->contentType = $contentType;
    }

    public function send()
    {
		// Set correct Content-Type header
        header("Content-Type: {$this->contentType}");

		// If Content-Type is JSON, use json_encode
        if ($this->contentType === 'application/json') {
            echo json_encode($this->data);
        } else {
			// If Content-Type is HTML, and data is array, convert it to HTML
            if (is_array($this->data)) {
                echo $this->arrayToHtml($this->data);
            } else {
                echo $this->data; // Send text if not array
            }
        }
    }

    private function arrayToHtml($data)
    {
        $html = '<ul>';
        foreach ($data as $item) {
            if (is_array($item)) {
                $html .= '<li>' . $this->arrayToHtml($item) . '</li>';
            } else {
                $html .= '<li>' . htmlspecialchars((string)$item) . '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public static function json($data)
    {
        return new self($data, 'application/json');
    }

    public static function html($data)
    {
        return new self($data, 'text/html');
    }

    public static function fromClientAccept($data)
    {
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? 'text/html';

        // Velg JSON hvis klienten godtar det, ellers HTML
        if (strpos($acceptHeader, 'application/json') !== false) {
            return self::json($data['json']);
        } else {
            return self::html($data['html']);
        }
    }
}
