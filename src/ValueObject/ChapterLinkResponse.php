<?php

namespace App\ValueObject;

class ChapterLinkResponse implements \JsonSerializable
{
    /**
     * @param string $response
     * @param array|string[] $aliases
     * @param array[] $translations
     */
    public function __construct(
        private string $response,
        private array $aliases = [],
        private array $translations = [],
    ) {

    }

    public function jsonSerialize(): mixed
    {
        return [
            'response' => $this->response,
            'aliases' => $this->aliases,
            'translations' => $this->translations,
        ];
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }

    public function setResponse(string $response): self
    {
        $old = $this->response;
        $this->response = $response;
        $this->updateTranslationKey($old, $response);

        return $this;
    }

    public function addAlias(string $alias): self
    {
        $this->aliases[] = $alias;
        $this->aliases = array_unique($this->aliases);

        return $this;
    }

    public function removeAlias(string $alias): self
    {
        if (!in_array($alias, $this->aliases, true)) {
            return $this;
        }

        $this->updateTranslationKey($alias, null);
        unset($this->aliases[array_search($alias, $this->aliases, true)]);

        return $this;
    }

    public function addTranslation(string $locale, string $key, string $value): self
    {
        $this->translations[$locale][$key] = $value;

        return $this;
    }

    public function removeTranslation(string $locale, string $key): self
    {
        unset($this->translations[$locale][$key]);

        return $this;
    }

    private function updateTranslationKey(string $oldKey, ?string $newKey): void
    {
        foreach ($this->translations as $languageData) {
            $translation = $languageData[$oldKey] ?? null;
            if (!$translation) {
                continue;
            }
            if ($newKey) {
                $languageData[$newKey] = $translation;
            }
            unset($languageData[$oldKey]);
        }
    }
}
