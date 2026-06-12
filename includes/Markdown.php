<?php

function markdownToHtml(string $markdown): string
{
    $markdown = str_replace(["\r\n", "\r"], "\n", trim($markdown));
    if ($markdown === '') {
        return '';
    }

    $blocks = preg_split("/\n{2,}/", $markdown) ?: [];
    $html = [];

    foreach ($blocks as $block) {
        $block = trim($block);
        if ($block === '') {
            continue;
        }

        if (preg_match('/^```([a-zA-Z0-9_-]*)\n([\s\S]*?)```$/', $block, $matches)) {
            $language = trim($matches[1]);
            $class = $language !== '' ? ' class="language-' . e($language) . '"' : '';
            $html[] = '<pre><code' . $class . '>' . e($matches[2]) . '</code></pre>';
            continue;
        }

        if (preg_match('/^(#{1,4})\s+(.+)$/', $block, $matches)) {
            $level = strlen($matches[1]);
            $html[] = '<h' . $level . '>' . parseMarkdownInline($matches[2]) . '</h' . $level . '>';
            continue;
        }

        if (preg_match('/^>\s?/m', $block)) {
            $quote = preg_replace('/^>\s?/m', '', $block);
            $html[] = '<blockquote><p>' . parseMarkdownInline(trim((string) $quote)) . '</p></blockquote>';
            continue;
        }

        if (preg_match('/^[-*+]\s+/m', $block)) {
            $items = preg_split('/\n/', $block) ?: [];
            $html[] = '<ul>' . implode('', array_map(static function ($item): string {
                return '<li>' . parseMarkdownInline((string) preg_replace('/^[-*+]\s+/', '', trim($item))) . '</li>';
            }, $items)) . '</ul>';
            continue;
        }

        if (preg_match('/^\d+\.\s+/m', $block)) {
            $items = preg_split('/\n/', $block) ?: [];
            $html[] = '<ol>' . implode('', array_map(static function ($item): string {
                return '<li>' . parseMarkdownInline((string) preg_replace('/^\d+\.\s+/', '', trim($item))) . '</li>';
            }, $items)) . '</ol>';
            continue;
        }

        if (preg_match('/^---+$/', $block)) {
            $html[] = '<hr>';
            continue;
        }

        $html[] = '<p>' . parseMarkdownInline(str_replace("\n", '<br>', $block)) . '</p>';
    }

    return implode("\n", $html);
}

function parseMarkdownInline(string $text): string
{
    $text = e($text);

    $text = preg_replace('/!\[([^\]]*)\]\(([^\s)]+)\)/', '<img src="$2" alt="$1">', $text) ?? $text;
    $text = preg_replace('/\[([^\]]+)\]\(([^\s)]+)\)/', '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>', $text) ?? $text;
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text) ?? $text;
    $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text) ?? $text;
    $text = preg_replace('/__([^_]+)__/', '<strong>$1</strong>', $text) ?? $text;
    $text = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $text) ?? $text;
    $text = preg_replace('/(?<!_)_([^_]+)_(?!_)/', '<em>$1</em>', $text) ?? $text;

    return $text;
}
