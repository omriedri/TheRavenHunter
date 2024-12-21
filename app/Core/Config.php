<?php class Config {

    public static function getHost(): string {
        return $_ENV['DB_HOST'] ?? 'localhost';
    }
}