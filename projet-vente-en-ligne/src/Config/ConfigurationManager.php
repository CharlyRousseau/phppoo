<?php
namespace App\Config;

class ConfigurationManager
{
    private static ?ConfigurationManager $instance = null;
    private array $config = [];

    private function __construct()
    {
        $this->config = [
            "tva" => 20,
            "devise" => "EUR",
            "frais_livraison" => 5.99,
            "email_contact" => "contact@exemple.com",
        ];
    }

    public static function getInstance(): ConfigurationManager
    {
        if (self::$instance === null) {
            self::$instance = new ConfigurationManager();
        }
        return self::$instance;
    }

    public function chargerConfiguration(array $config): void
    {
        $this->config = array_merge($this->config, $config);
    }

    public function get(string $key)
    {
        if (!isset($this->config[$key])) {
            throw new \InvalidArgumentException(
                "Clé de configuration '$key' non trouvée."
            );
        }
        return $this->config[$key];
    }

    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    public function validerConfiguration(): bool
    {
        return isset(
            $this->config["tva"],
            $this->config["devise"],
            $this->config["frais_livraison"]
        );
    }
}
