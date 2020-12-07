<?php
declare(strict_types=1);

namespace App\Application\Actions\Url;

use App\Application\Actions\Action;
use App\Domain\Url\UrlRepository;
use Psr\Log\LoggerInterface;

abstract class UrlAction extends Action
{
    /**
     * @var UrlRepository
     */
    protected $urlRepository;

    /**
     * @param LoggerInterface $logger
     * @param UrlsRepository  $urlRepository
     */
    public function __construct(LoggerInterface $logger, UrlsRepository $urlRepository)
    {
        parent::__construct($logger);
        $this->urlRepository = $urlRepository;
    }

}
