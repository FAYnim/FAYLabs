<?php

const PROJECTS_API_URL = 'http://localhost/faydev/faylabs-dashboard/api/public/load-projects.php?offset=0';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function fetchPublishedProjects(string $url = PROJECTS_API_URL): array
{
    $json = fetchJson($url);
    if ($json === null) {
        return [];
    }

    $payload = json_decode($json, true);
    if (!is_array($payload) || ($payload['success'] ?? false) !== true) {
        return [];
    }

    $projects = $payload['data']['projects'] ?? [];
    if (!is_array($projects)) {
        return [];
    }

    return array_values(array_filter(array_map('normalizeProject', $projects)));
}

function fetchJson(string $url): ?string
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $body   = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (is_string($body) && $status >= 200 && $status < 300) {
            return $body;
        }

        return null;
    }

    $context = stream_context_create([
        'http' => [
            'timeout'       => 5,
            'ignore_errors' => true,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    return is_string($body) ? $body : null;
}

function normalizeProject(array $project): ?array
{
    $title = trim((string) ($project['title'] ?? ''));
    $slug  = trim((string) ($project['slug'] ?? ''));

    if ($title === '' || $slug === '') {
        return null;
    }

    return [
        'title'        => $title,
        'slug'         => $slug,
        'description'  => trim((string) ($project['description'] ?? '')),
        'cover_image'  => trim((string) ($project['cover_image'] ?? '')),
        'label'        => trim((string) ($project['label'] ?? '')),
        'project_year' => trim((string) ($project['project_year'] ?? '')),
    ];
}

function projectDetailUrl(array $project): string
{
    return 'project.php?slug=' . rawurlencode($project['slug']);
}
