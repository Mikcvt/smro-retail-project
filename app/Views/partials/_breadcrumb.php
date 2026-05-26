<?php
/**
 * Partial: Breadcrumb Navigation
 * Generates a breadcrumb trail based on the current URL path.
 *
 * @var \CodeIgniter\View\View $this
 */

// Get current URI path
$uri = service('request')->getUri();
$segments = array_filter(explode('/', trim($uri->getPath(), '/')));

// Build breadcrumb items
$items = [];
$items[] = [
    'label' => 'Home',
    'url'   => base_url('/dashboard'),
    'active' => empty($segments),
];

$currentPath = '';
foreach ($segments as $segment) {
    $currentPath .= '/' . $segment;
    
    // Format label (capitalize and replace hyphens with spaces)
    $label = ucwords(str_replace(['-', '_'], ' ', $segment));
    
    $items[] = [
        'label'  => $label,
        'url'    => base_url($currentPath),
        'active' => true,
    ];
}
?>

<?php if (count($items) > 1) : ?>
<nav aria-label="breadcrumb" class="smro-breadcrumb-nav">
    <ol class="breadcrumb mb-0">
        <?php foreach ($items as $index => $item) : ?>
            <?php if ($item['active']) : ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= esc($item['label']) ?>
                </li>
            <?php else : ?>
                <li class="breadcrumb-item">
                    <a href="<?= esc($item['url']) ?>" class="text-decoration-none">
                        <?= esc($item['label']) ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>