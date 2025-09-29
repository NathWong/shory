<?php

namespace App\Service;

use App\Entity\UserProfile;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class AvatarHandler
{
    private const PREFIX = 'avtr_';

    public function __construct(
        #[Autowire('%kernel.project_dir%/public/uploads/avatar')]
        private string $avatarDir,
        private LoggerInterface $logger,
    ) {
    }

    public function handleAvatar(?UploadedFile $avatar, UserProfile $userProfile): void
    {
        if (!$avatar) {
            return;
        }
        $avatarName = sprintf(
            '%s.%s',
            uniqid(self::PREFIX),
            $avatar->guessExtension(),
        );
        try {
            $avatar->move($this->avatarDir, $avatarName);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());

            return;
        }

        $userProfile->setAvatar($avatarName);
    }
}
