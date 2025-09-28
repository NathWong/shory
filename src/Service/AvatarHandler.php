<?php

namespace App\Service;

use App\Entity\UserProfile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class AvatarHandler
{
    private const PREFIX = 'avtr_';

    public function __construct(
        #[Autowire('%kernel.project_dir%/public/uploads/avatar')]
        private string $avatarDir,
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
            // Silent exception the file is just no saved

            return;
        }

        $userProfile->setAvatar($avatarName);
    }
}
