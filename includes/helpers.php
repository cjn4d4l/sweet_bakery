<?php
declare(strict_types=1);

/**
 * Escape dynamic values before placing them in HTML.
 * This keeps product names and descriptions from being treated as markup.
 */
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
