<?php declare(strict_types=1);


namespace Core\Service;

class Config
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get(?string $path = null, $default = null)
    {
        if (null === $path) {
            return $this->config;
        }

        $config = $this->config;
        foreach (explode("/", $path) as $part) {
            if (array_key_exists($part, $config)) {
                $config = $config[$part];
            } else {
                return $default;
            }
        }

        return $config;
    }

    public function __invoke(?string $path = null, $default = null)
    {
        return $this->get($path, $default);
    }
}