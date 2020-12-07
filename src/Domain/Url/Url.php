<?php
declare(strict_types=1);

namespace App\Domain\Url;

use JsonSerializable;

class Url implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $urlname;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @param int|null  $id
     * @param string    $urlname
     * @param string    $firstName
     * @param string    $lastName
     */
    public function __construct(?int $id, string $urlname, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->urlname = strtolower($urlname);
        $this->firstName = ucfirst($firstName);
        $this->lastName = ucfirst($lastName);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrlname(): string
    {
        return $this->urlname;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'urlname' => $this->urlname,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
