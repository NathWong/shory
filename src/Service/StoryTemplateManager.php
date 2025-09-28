<?php

namespace App\Service;

use App\Contract\StoryTemplateInterface;
use App\Entity\Story;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class StoryTemplateManager
{
    public function __construct(
        #[AutowireIterator('app.story_template')]
        private iterable $templates,
    ) {
    }

    public function getChoices(): array
    {
        $choices = [];
        foreach ($this->templates as $template) {
            if (!($template instanceof StoryTemplateInterface)) {
                throw new \Exception(sprintf('\"%s\" must implement \"%s\"', $template::class, StoryTemplateInterface::class));
            }

            $choices[$template->getName()] = $template->getIdentifier();
        }

        return $choices;
    }

    public function getTemplateClass(Story $story): ?string
    {
        return $this
            ->getTemplate($story->getTemplate())
            ?->getCssClass();
    }

    public function getTemplate(string $identifier): ?StoryTemplateInterface
    {
        foreach ($this->templates as $template) {
            if (!($template instanceof StoryTemplateInterface)) {
                throw new \Exception(sprintf('\"%s\" must implement \"%s\"', $template::class, StoryTemplateInterface::class));
            }
            if ($template->getIdentifier() === $identifier) {
                return $template;
            }
        }

        return null;
    }
}
