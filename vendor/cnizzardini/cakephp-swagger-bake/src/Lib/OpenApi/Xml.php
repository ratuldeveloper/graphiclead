<?php
declare(strict_types=1);

namespace SwaggerBake\Lib\OpenApi;

use JsonSerializable;

/**
 * Class Xml
 *
 * @package SwaggerBake\Lib\OpenApi
 */
class Xml implements JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $namespace;

    /**
     * @var string|null
     */
    private $prefix;

    /**
     * @var bool|null
     */
    private $attribute;

    /**
     * @var bool|null
     */
    private $wrapped;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $vars = get_object_vars($this);

        // remove properties if they are set to their defaults (to avoid json clutter)
        foreach (['attribute','wrapped'] as $v) {
            if (array_key_exists($v, $vars) && $vars[$v] === false) {
                unset($vars[$v]);
            }
        }

        // remove empty properties to avoid swagger.json clutter
        foreach (['namespace','prefix','attribute','wrapped'] as $v) {
            if (array_key_exists($v, $vars) && (is_null($vars[$v]) || empty($vars[$v]))) {
                unset($vars[$v]);
            }
        }

        return $vars;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string|null $namespace XML namespace
     * @return $this
     */
    public function setNamespace(?string $namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @param string|null $prefix Prefix
     * @return $this
     */
    public function setPrefix(?string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAttribute(): ?bool
    {
        return $this->attribute;
    }

    /**
     * @param bool|null $attribute Attribute
     * @return $this
     */
    public function setAttribute(?bool $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getWrapped(): ?bool
    {
        return $this->wrapped;
    }

    /**
     * @param bool|null $wrapped Wrapped
     * @return $this
     */
    public function setWrapped(?bool $wrapped)
    {
        $this->wrapped = $wrapped;

        return $this;
    }
}
