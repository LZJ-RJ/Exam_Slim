<?php
declare(strict_types=1);

namespace App\Domain\Url;

interface UrlRepository
{
    /**
     * @return Url[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Url
     * @throws UrlNotFoundException
     */
    public function findUrlOfId(int $id): Url;
}
