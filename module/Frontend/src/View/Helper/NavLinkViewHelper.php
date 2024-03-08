<?php declare(strict_types=1);


namespace Frontend\View\Helper;

use Zend\Router\RouteMatch;
use Zend\View\Helper\AbstractHelper;

class NavLinkViewHelper extends AbstractHelper
{
    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    public function __construct(?RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
    }

    public function __invoke(string $label, string $routeName, string $classes = '', array $routeParams = [], bool $strict = false): string
    {
        return sprintf(
            '<a class="nav-link%s%s"%s href="%s">%s</a>',
            $classes,
            $this->isActive($routeName, $routeParams, $strict) ? ' active' : '',
            $this->isActive($routeName, $routeParams, $strict) ? ' aria-current="page"' : '',
            $this->getView()->url($routeName, $routeParams),
            $label
        );
    }

    private function isActive(string $route, array $params = [], bool $strict = false): bool
    {
        if (null === $this->routeMatch) {
            return false;
        }

        if ($strict) {
            if ($route === $this->routeMatch->getMatchedRouteName()) {
                if (empty($params)) {
                    return true;
                }

                foreach ($params as $paramKey => $paramValue) {
                    if (array_key_exists($paramKey, $this->routeMatch->getParams())) {
                        if ($this->routeMatch->getParams()[$paramKey] == $paramValue) {
                            return true;
                        }
                    }
                }
            }
        } else {
            if (strpos($this->routeMatch->getMatchedRouteName(), $route) !== false) {
                return true;
            }
        }

        return false;
    }
}