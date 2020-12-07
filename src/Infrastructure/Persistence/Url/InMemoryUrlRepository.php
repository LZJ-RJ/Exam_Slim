<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Url;

use App\Domain\Url\Url;
use App\Domain\Url\UrlNotFoundException;
use App\Domain\Url\UrlRepository;

class InMemoryUrlRepository implements UrlRepository
{
    /**
     * @var Url[]
     */
    private $urls;

    /**
     * InMemoryUrlRepository constructor.
     *
     * @param array|null $urls
     */
    public function __construct(array $urls = null)
    {
        $this->urls = $urls ?? [
            1 => new Url(1, 'bill.gates', 'Bill', 'Gates'),
            2 => new Url(2, 'steve.jobs', 'Steve', 'Jobs'),
            3 => new Url(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            4 => new Url(4, 'evan.spiegel', 'Evan', 'Spiegel'),
            5 => new Url(5, 'jack.dorsey', 'Jack', 'Dorsey'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->urls);
    }

    /**
     * {@inheritdoc}
     */
    public function findUrlOfId(int $id): Url
    {
        if (!isset($this->urls[$id])) {
            throw new UrlNotFoundException();
        }

        return $this->urls[$id];
    }
}
