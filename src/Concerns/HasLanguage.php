<?php

namespace STS\FilamentUppy\Concerns;

use Closure;

trait HasLanguage
{
    protected Closure|string $language = 'en';

    /**
     * Set the language for component text.
     * 
     * @param string|Closure $language
     * @return $this
     */
    public function language(string|Closure $language): static
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get the language for component text.
     * 
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->evaluate($this->language);
    }
}
