<?php

namespace Midnight\Settings\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Setting
 * @package Settings\Entity
 *
 * @ORM\Entity(repositoryClass="Midnight\Settings\Repository\SettingRepository")
 * @ORM\Table(
 *      name="settings",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="unique", columns={"namespace", "key"})}
 * )
 */
class Setting
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * @var string
     * @ORM\Column
     */
    private $namespace;
    /**
     * @var string
     * @ORM\Column(name="`key`")
     */
    private $key;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $value;

    function __construct($namespace, $key, $value = null)
    {
        $this->key = $key;
        $this->namespace = $namespace;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}