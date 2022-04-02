<?php
declare(strict_types=1);

namespace SwaggerBake\Lib\OpenApi;

use InvalidArgumentException;
use JsonSerializable;
use LogicException;

/**
 * Class Parameter
 *
 * @package SwaggerBake\Lib\OpenApi
 * @see https://swagger.io/docs/specification/describing-parameters/
 */
class Parameter implements JsonSerializable
{
    public const IN = ['query','cookie','header','path','body'];

    /**
     * For referencing an OpenAPI YAML object. If set only ref will be returned
     *
     * @var string|null
     */
    private $ref;

    /**
     * @var string
     **/
    private $name = '';

    /**
     * @var string
     **/
    private $in = '';

    /**
     * @var string
     **/
    private $description = '';

    /**
     * @var bool
     **/
    private $required = false;

    /**
     * @var \SwaggerBake\Lib\OpenApi\Schema
     **/
    private $schema;

    /**
     * @var bool
     **/
    private $deprecated = false;

    /**
     * @var bool
     **/
    private $allowEmptyValue = false;

    /**
     * @var bool
     **/
    private $explode = false;

    /**
     * @var string
     **/
    private $style = '';

    /**
     * @var bool
     **/
    private $allowReserved = false;

    /**
     * @var mixed
     **/
    private $example = '';

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (empty($this->in)) {
            throw new LogicException('Parameter::in is required for ' . $this->name);
        }

        return get_object_vars($this);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $vars = $this->toArray();

        if (!empty($this->ref)) {
            return ['$ref' => $this->ref];
        }

        if ($vars['in'] !== 'query') {
            unset($vars['allowReserved']);
        }

        // remove empty values from JSON
        foreach (['style','description','schema','example','ref'] as $property) {
            if (empty($vars[$property])) {
                unset($vars[$property]);
            }
        }

        // reduce JSON clutter if these values are equal to their defaults
        $defaults = ['deprecated' => false, 'allowEmptyValue' => false, 'explode' => false, 'allowReserved' => false];
        foreach ($defaults as $name => $value) {
            if ($this->{$name} === $value) {
                unset($vars[$name]);
            }
        }

        return $vars;
    }

    /**
     * @return string|null
     */
    public function getRef(): ?string
    {
        return $this->ref;
    }

    /**
     * @param string $ref a ref string such (e.g. #/x-my-project/components/parameters/my-header)
     * @return $this
     */
    public function setRef(string $ref)
    {
        $this->ref = $ref;

        return $this;
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
     * @return string
     */
    public function getIn(): string
    {
        return $this->in;
    }

    /**
     * @param string $in In
     * @return $this
     */
    public function setIn(string $in)
    {
        $in = strtolower($in);
        if (!in_array($in, self::IN)) {
            throw new InvalidArgumentException("Invalid type for in. Given $in");
        }
        $this->in = $in;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description Description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required Is required
     * @return $this
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    /**
     * @param bool $deprecated Deprecated
     * @return $this
     */
    public function setDeprecated(bool $deprecated)
    {
        $this->deprecated = $deprecated;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowEmptyValue(): bool
    {
        return $this->allowEmptyValue;
    }

    /**
     * @param bool $allowEmptyValue Allow empty
     * @return $this
     */
    public function setAllowEmptyValue(bool $allowEmptyValue)
    {
        $this->allowEmptyValue = $allowEmptyValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param \SwaggerBake\Lib\OpenApi\Schema $schema Schema
     * @return $this
     */
    public function setSchema(Schema $schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExplode(): bool
    {
        return $this->explode;
    }

    /**
     * @param bool $explode Explode
     * @return $this
     */
    public function setExplode(bool $explode)
    {
        $this->explode = $explode;

        return $this;
    }

    /**
     * @return string
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * @param string $style Style
     * @return $this
     */
    public function setStyle(string $style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowReserved(): bool
    {
        return $this->allowReserved;
    }

    /**
     * @param bool $allowReserved Allow reserved
     * @return $this
     */
    public function setAllowReserved(bool $allowReserved)
    {
        $this->allowReserved = $allowReserved;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * @param mixed $example Example
     * @return $this
     */
    public function setExample($example)
    {
        $this->example = $example;

        return $this;
    }
}
