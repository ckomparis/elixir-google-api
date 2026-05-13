<?php

namespace SensoGPT\Plugin\System\SensoGPT\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;

final class SensoGPT extends CMSPlugin
{
    protected $autoloadLanguage = true;

    private const MAX_CONTENT_LENGTH = 1200000;

    public function onAfterRender(): void
    {
        $app = $this->getApplication();

        if (!$app->isClient('site')) {
            return;
        }

        if (!(bool) $this->params->get('enabled', 1)) {
            return;
        }

        $body = (string) $app->getBody();

        if ($body === '' || strlen($body) > self::MAX_CONTENT_LENGTH) {
            return;
        }

        if (!$this->isHtmlResponse($body)) {
            return;
        }

        $startTag = trim((string) $this->params->get('start_tag', '{gpt}'));
        $endTag = trim((string) $this->params->get('end_tag', '{/gpt}'));
        $targetLanguage = $this->sanitizeLanguageCode((string) $this->params->get('target_language', 'en'));
        $translateAlt = (bool) $this->params->get('translate_alt', 1);

        if (!$this->isValidTagPair($startTag, $endTag) || $targetLanguage === '') {
            return;
        }

        $body = $this->translateTaggedSegments($body, $startTag, $endTag, $targetLanguage);

        if ($translateAlt) {
            $body = $this->translateTaggedAltAttributes($body, $startTag, $endTag, $targetLanguage);
        }

        $app->setBody($body);
    }

    private function isHtmlResponse(string $body): bool
    {
        return str_contains($body, '<html') || str_contains($body, '<body') || str_contains($body, $this->params->get('start_tag', '{gpt}'));
    }

    private function isValidTagPair(string $startTag, string $endTag): bool
    {
        return $startTag !== '' && $endTag !== '' && $startTag !== $endTag && strlen($startTag) <= 32 && strlen($endTag) <= 32;
    }

    private function sanitizeLanguageCode(string $lang): string
    {
        $sanitized = strtolower(trim($lang));

        if (!preg_match('/^[a-z]{2,3}(?:-[a-z0-9]{2,8})?$/', $sanitized)) {
            return '';
        }

        return $sanitized;
    }

    private function translateTaggedSegments(string $html, string $startTag, string $endTag, string $targetLanguage): string
    {
        $pattern = '/' . preg_quote($startTag, '/') . '(.*?)' . preg_quote($endTag, '/') . '/su';

        return (string) preg_replace_callback($pattern, static function (array $matches) use ($targetLanguage): string {
            return self::translateText($matches[1], $targetLanguage);
        }, $html);
    }

    private function translateTaggedAltAttributes(string $html, string $startTag, string $endTag, string $targetLanguage): string
    {
        $pattern = '/alt\s*=\s*(["\'])' . preg_quote($startTag, '/') . '(.*?)' . preg_quote($endTag, '/') . '\1/su';

        return (string) preg_replace_callback($pattern, static function (array $matches) use ($targetLanguage): string {
            $quote = $matches[1];
            $translated = self::translateText($matches[2], $targetLanguage);

            return 'alt=' . $quote . htmlspecialchars($translated, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . $quote;
        }, $html);
    }

    private static function translateText(string $text, string $targetLanguage): string
    {
        $normalized = trim(html_entity_decode($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

        if ($normalized === '') {
            return $text;
        }

        return '[' . $targetLanguage . '] ' . $normalized;
    }
}
