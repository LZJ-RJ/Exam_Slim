<?php

namespace App\Domain\Url\Service;

use App\Domain\Url\Repository\UrlCreatorRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class UrlCreator
{
    /**
     * @var UrlCreatorRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param UrlCreatorRepository $repository The repository
     */
    public function __construct(UrlCreatorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new url.
     *
     * @param array $data The form data
     *
     * @return int The new url ID
     */
    public function createUrl(array $data): int
    {
        // Input validation
        $this->validateNewUrl($data);

        // Insert url
        $urlId = $this->repository->insertUrl($data);

        // Logging here: Url created successfully
        $this->logger->info(sprintf('Url created successfully: %s', $urlId));

        return $urlId;
    }

    /**
     * Input validation.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function validateNewUrl(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (empty($data['urlname'])) {
            $errors['urlname'] = 'Input required';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Input required';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Invalid email address';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}