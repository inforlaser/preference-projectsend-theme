<?php
/*
Template name: Preference - Public Files
URI: https://www.projectsend.org/templates/preference
Author: ProjectSend
Author URI: https://www.projectsend.org/
Author e-mail: contact@projectsend.org
Description: Custom theme for Preference Porto.
*/
$ld = 'preference_template'; // specify the language domain for this template

// Use the global pagination setting from admin area
define('TEMPLATE_RESULTS_PER_PAGE', get_option('pagination_results_per_page'));

define('TEMPLATE_THUMBNAILS_WIDTH', '120');
define('TEMPLATE_THUMBNAILS_HEIGHT', '120');

$filter_by_category = (isset($_GET['category']) && $_GET['category'] !== '') ? $_GET['category'] : null;

$current_url = get_form_action_with_existing_parameters('public.php');

$count = $files['pagination']['total'];

if ($count == 0) {
    if (isset($_GET['search'])) {
        $no_results_message = __('Your search keywords returned no results.', 'preference_template');
    } else {
        $no_results_message = __('There are no files available.', 'preference_template');
    }
}

$groups = get_groups([
    'public' => true,
]);

// Get template and branding information
$this_template = get_option('selected_clients_template');
$this_template_url = BASE_URI . 'templates/' . $this_template . '/';
$logo_file_info = generate_logo_url();

$window_title = __('Available files', 'preference_template');
$site_title = get_option('this_install_title');

?>
<!DOCTYPE html>
<html lang="<?php echo LOADED_LANG; ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo html_output($window_title . ' &raquo; ' . $site_title); ?></title>
    <?php meta_favicon(); ?>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Tailwind Elements JS -->
    <script type="module" src="<?php echo $this_template_url; ?>js/tailwindplus-elements.js"></script>

    <!-- Flags CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css" />

    <!-- Custom theme CSS -->
    <link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template_url; ?>preference.min.css" />
    <script>
        window.base_url = '<?php echo BASE_URI; ?>';
        window.downloadOverlayTexts = {
            preparing: '<?php echo addslashes(__('Preparing download…', 'preference_template')); ?>',
            selectedTemplate: '<?php echo addslashes(__('Selected %s file%s totaling %s.', 'preference_template')); ?>',
            timeTemplate: '<?php echo addslashes(__('This may take up to %s minute%s.', 'preference_template')); ?>'
        };
    </script>

    <?php render_custom_assets('head'); ?>
    <script src="<?php echo $this_template_url; ?>js/template.js"></script>
</head>

<body class="bg-cream-100 min-h-screen flex flex-col text-slate-700">

    <?php render_custom_assets('body_top'); ?>

    <!-- Top Navigation Bar -->
    <nav
        class="sticky top-0 z-50 border-b border-cream-200/70 bg-white/90 backdrop-blur-sm shadow-sm shadow-cream-200/40">
        <div class="mx-auto max-w-360 px-4 py-3.5">
            <div class="flex items-center justify-between gap-3">
                <!-- Logo -->
                <a href="<?php echo BASE_URI; ?>" class="shrink-0 rounded-full p-1 transition hover:bg-cream-50">
                    <?php if ($logo_file_info && $logo_file_info['exists']) { ?>
                        <img src="<?php echo $logo_file_info['url']; ?>" alt="<?php echo html_output($site_title); ?>"
                            class="h-8 w-auto max-w-32 object-contain sm:h-24 sm:max-w-[16rem]" />
                    <?php } else { ?>
                        <span
                            class="text-xl font-bold text-brand sm:text-2xl"><?php echo html_output($site_title); ?></span>
                    <?php } ?>
                </a>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-2">
                    <!-- Language Dropdown -->
                    <el-dropdown class="relative">
                        <button
                            class="flex items-center justify-center rounded-full border border-cream-200 bg-white p-2.5 text-slate-600 shadow-sm transition hover:border-brand/30 hover:bg-cream-50 hover:text-brand sm:gap-2 sm:px-3.5 sm:py-2 sm:text-sm sm:font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5 text-slate-500 sm:h-6 sm:w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m10.5 21 5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 0 1 6-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 0 1-3.827-5.802" />
                            </svg>
                            <span
                                class="hidden text-gray-700 font-medium sm:inline"><?php _e('Language', 'preference_template'); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="hidden h-6 w-6 text-gray-400 sm:block">
                                <path fill-rule="evenodd"
                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-5.23-5.23a.75.75 0 1 1 1.06-1.06L12 14.19l4.7-4.7a.75.75 0 1 1 1.06 1.06l-5.23 5.23Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Language dropdown menu -->
                        <el-menu anchor="bottom end" popover
                            class="right-0 top-full mt-2 w-56 overflow-hidden rounded-2xl border border-cream-200/80 bg-white shadow-[0_18px_50px_-12px_rgba(15,23,42,0.2)] ring-1 ring-brand/10">
                            <?php
                            // Scan for language files
                            $available_langs = get_available_languages();
                            $return_to = make_return_to_url($_SERVER['REQUEST_URI'], true);

                            // Flag icons and localized names mapping
                            $lang_flags = array(
                                'en' => array(
                                    'flag' => '<span class="fi fi-gb"></span>',
                                    'name' => 'English'
                                ),
                                'pt' => array(
                                    'flag' => '<span class="fi fi-pt"></span>',
                                    'name' => 'Português'
                                )
                            );

                            foreach ($available_langs as $filename => $lang_name) {
                                // Extract language code (e.g., 'en' from 'en.php' or 'en_US')
                                $lang_code = substr($filename, 0, 2);
                                $flag = '';
                                $display_name = $lang_name;

                                if (isset($lang_flags[$lang_code])) {
                                    $flag = $lang_flags[$lang_code]['flag'];
                                    $display_name = $lang_flags[$lang_code]['name'];
                                }
                                ?>
                                <a href="<?php echo BASE_URI . 'process.php?do=change_language&language=' . $filename . '&return_to=' . $return_to; ?>"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-cream-50 hover:text-brand transition-colors">
                                    <?php echo $flag; ?>
                                    <?php echo $display_name; ?>
                                </a>
                                <?php
                            }
                            ?>
                        </el-menu>
                    </el-dropdown>

                    <!-- Login Button -->
                    <a href="<?php echo BASE_URI; ?>index.php"
                        class="flex items-center justify-center rounded-full border border-cream-200 bg-white p-2.5 text-slate-600 shadow-sm transition hover:border-brand/30 hover:bg-cream-50 hover:text-brand sm:gap-2 sm:px-3.5 sm:py-2 sm:text-sm sm:font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 text-slate-500 sm:h-6 sm:w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        <span
                            class="hidden text-gray-700 font-medium sm:inline"><?php _e('Login', 'preference_template'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="mx-auto flex-1 w-full max-w-360 px-4 py-3 sm:px-6 sm:py-4">
        <!-- Page Intro -->
        <div class="mb-6 rounded-3xl border border-cream-200 bg-white p-5 shadow-sm shadow-cream-200/40 sm:p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-slate-800">
                        <?php echo html_output($window_title); ?>
                    </h1>
                    <p class="text-sm text-slate-500">
                        <?php echo (int) $count; ?>
                        <?php _e('files published for direct access.', 'preference_template'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <?php if ($count > 0 || isset($_GET['search']) || isset($_GET['category'])): ?>
            <div class="mb-6 rounded-3xl border border-cream-200 bg-white p-5 shadow-sm shadow-cream-200/40 sm:p-6">
                <form action="<?php echo BASE_URI; ?>public.php" method="get" class="flex flex-col lg:flex-row gap-4">

                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                            <input type="text" name="search"
                                value="<?php echo (isset($_GET['search']) && !empty($_GET['search'])) ? html_output($_GET['search']) : ''; ?>"
                                placeholder="<?php _e('Search files...', 'preference_template'); ?>"
                                class="block w-full pl-10 pr-3 py-2 border border-cream-300 rounded-lg bg-white text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <?php if (!empty($get_categories['categories'])): ?>
                        <div class="lg:w-64">
                            <select name="category"
                                class="block w-full px-3 py-2 border border-cream-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                                <option value=""><?php _e('All Categories', 'preference_template'); ?></option>
                                <?php foreach ($get_categories['categories'] as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo ($filter_by_category == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_output($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <!-- Search Button -->
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-brand hover:bg-brand-dark text-white rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand/30 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <?php _e('Apply', 'preference_template'); ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Files List -->
        <?php if (!$count) { ?>
            <!-- No Files Message -->
            <div class="no-files-state flex flex-col items-center justify-center py-20 rounded-3xl">
                <div class="w-20 h-20 bg-cream-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <?php $search_query = isset($_GET['search']) ? trim((string) $_GET['search']) : ''; ?>
                <?php if ($search_query !== '') { ?>
                    <h3 class="text-lg font-medium text-gray-400 mb-1">
                        <?php echo _e('No files found', 'preference_template'); ?></h3>
                    <p class="text-sm text-gray-300"><?php echo $no_results_message; ?></p>
                    <a href="<?php echo modify_url_with_parameters($current_url, [], ['search', 'page']); ?>"
                        class="mt-4 px-6 py-2.5 bg-brand hover:bg-brand-dark text-white text-sm font-medium rounded-full transition-colors">
                        <?php echo _e('Clear search', 'preference_template'); ?>
                    </a>
                <?php } else { ?>
                    <h3 class="text-lg font-medium text-gray-400 mb-1">
                        <?php echo _e('No files found', 'preference_template'); ?></h3>
                    <p class="text-sm text-gray-300"><?php echo $no_results_message; ?></p>
                <?php }
                ; ?>
            </div>
        <?php } else { ?>
            <div class="space-y-3">
                <?php
                foreach ($files['files_ids'] as $file_id) {
                    $file = new \ProjectSend\Classes\Files($file_id);

                    // safe fallbacks
                    $extension = !empty($file->extension) ? strtolower($file->extension) : '';
                    $file_size = !empty($file->size_formatted) ? $file->size_formatted : '';
                    $upload_date = !empty($file->uploaded_date) ? format_date($file->uploaded_date) : '';
                    $download_url = !(empty($file->public_url)) ? $file->public_url . '&download' : BASE_URI . "download.php?id=" . $file->id - "&token=" . $file->public_token;
                    $expired_class = $file->expired ? 'opacity-50' : '';
                    ?>
                    <div class="file-card group flex flex-col overflow-hidden rounded-2xl border border-cream-200/80 bg-white shadow-sm shadow-cream-200/30 transition-all duration-200 hover:-translate-y-0.5 hover:border-brand/30 hover:shadow-md lg:flex-row <?php echo $expired_class; ?>"
                        data-file-id="<?php echo $file->id; ?>">
                        <div class="file-thumb flex w-full shrink-0 items-center justify-center bg-cream-50/70 p-3 lg:w-32">
                            <?php if ($file->isImage() && !empty($file->full_path) && file_exists($file->full_path)) {
                                $thumbnail = make_thumbnail($file->full_path, 'thumbnail', TEMPLATE_THUMBNAILS_WIDTH, TEMPLATE_THUMBNAILS_HEIGHT);
                                ?>
                                <img src="<?php echo $thumbnail['thumbnail']['url']; ?>"
                                    alt="<?php echo html_output($file->title); ?>"
                                    class="max-h-24 max-w-24 rounded-lg object-contain" />
                            <?php } else { ?>
                                <div
                                    class="flex h-24 w-24 items-center justify-center rounded-xl bg-white text-sm font-bold uppercase text-slate-600 shadow-sm ring-1 ring-cream-200">
                                    <?php echo htmlspecialchars(substr($extension, 0, 4)); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div
                            class="flex flex-1 flex-col justify-between gap-4 px-4 py-4 sm:px-6 sm:py-5 lg:flex-wrap lg:items-start lg:gap-6">
                            <div class="min-w-0 flex-1 self-stretch">
                                <div class="flex items-start gap-3">
                                    <div class="min-w-0 flex-1">
                                        <h3 class="mb-1 wrap-break-word text-base font-semibold leading-6 text-slate-800">
                                            <?php echo html_output($file->title); ?>
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                            <?php if ($file->expired) { ?>
                                                <span
                                                    class="rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-600"><?php echo _e('Expired', 'preference_template'); ?></span>
                                            <?php } ?>
                                            <span><?php echo $file_size; ?></span>
                                            <span class="text-slate-300">•</span>
                                            <span><?php echo $upload_date; ?></span>
                                        </div>
                                        <?php if (!empty($file->description)) { ?>
                                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                                <?php echo html_output($file->description); ?>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0 lg:ml-auto">
                                <?php if ($file->expired == false) { ?>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="<?php echo BASE_URI; ?>download.php?id=<?php echo $file->id; ?>&token=<?php echo $file->public_token; ?>"
                                            target="_blank"
                                            class="inline-flex items-center justify-center gap-2 rounded-full border border-cream-200 bg-white px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-slate-600 shadow-sm transition-colors hover:border-brand/30 hover:bg-cream-50 hover:text-brand">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            <?php _e('View Details', 'preference_template'); ?>
                                        </a>
                                        <a href="<?php echo $download_url; ?>" target="_blank"
                                            class="download-action inline-flex items-center justify-center gap-2 rounded-full bg-brand px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-white shadow-sm shadow-brand/20 transition-colors hover:bg-brand-dark">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="h-4 w-4">
                                                <path fill-rule="evenodd"
                                                    d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v3.75a3 3 0 0 0 3 3h13.5a3 3 0 0 0 3-3V16.5a.75.75 0 0 1 1.5 0v3.75a4.5 4.5 0 0 1-4.5 4.5H6.75a4.5 4.5 0 0 1-4.5-4.5V16.5a.75.75 0 0 1 .75-.75Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <?php _e('Download', 'preference_template'); ?>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div
                                        class="file_actions_notice rounded-md bg-red-50 px-4 py-2 text-sm font-medium text-red-600">
                                        <?php _e('File expired', 'preference_template'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($files['pagination']) && $files['pagination']['total'] > 0 && TEMPLATE_RESULTS_PER_PAGE > 0 && $files['pagination']['total'] > TEMPLATE_RESULTS_PER_PAGE) { ?>
                <div class="mt-8 flex items-center justify-center">
                    <nav class="flex items-center gap-2" aria-label="<?php echo _e('Pagination', 'preference_template'); ?>">
                        <?php
                        $pagination_page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? $_GET["page"] : 1;
                        $pages = ceil($files['pagination']['total'] / TEMPLATE_RESULTS_PER_PAGE);
                        $query_string = '';

                        if (isset($_GET['search']))
                            $query_string .= '&search=' . urlencode($_GET['search']);
                        if (isset($_GET['category']) && $_GET['category'] !== '')
                            $query_string .= '&category=' . $_GET['category'];

                        // Previous button
                        if ($pagination_page > 1) {
                            $prev = $pagination_page - 1;
                            echo '<a href="?page=' . $prev . $query_string . '" class="pagination-link w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-500 hover:text-brand hover:bg-cream-50 transition-colors shadow-sm">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l5.23-5.23a.75.75 0 0 1 1.06 1.06L9.31 12l4.7 4.7a.75.75 0 1 1-1.06 1.06l-5.23-5.23Z" clip-rule="evenodd"/></svg>';
                            echo '</a>';
                        }

                        // Page numbers
                        $start_page = max(1, $pagination_page - 2);
                        $end_page = min($pages, $pagination_page + 2);

                        if ($start_page > 1) {
                            echo '<a href="?page=1' . $query_string . '" class="pagination-link w-10 h-10 flex items-center justify-center text-sm rounded-xl bg-white text-gray-500 hover:text-brand hover:bg-cream-50 transition-colors shadow-sm">1</a>';
                            if ($start_page > 2)
                                echo '<span class="text-gray-300 px-1">...</span>';
                        }

                        for ($i = $start_page; $i <= $end_page; $i++) {
                            $active = ($i == $pagination_page) ? 'bg-brand text-white shadow-sm shadow-brand/20' : 'bg-white text-gray-500 hover:text-brand hover:bg-cream-50';
                            if ($i == $pagination_page) {
                                echo '<span class="pagination-current w-10 h-10 flex items-center justify-center text-sm rounded-xl ' . $active . '">' . $i . '</span>';
                            } else {
                                echo '<a href="?page=' . $i . $query_string . '" class="pagination-link w-10 h-10 flex items-center justify-center text-sm rounded-xl ' . $active . ' transition-colors shadow-sm">' . $i . '</a>';
                            }
                        }

                        if ($end_page < $pages) {
                            if ($end_page < $pages - 1)
                                echo '<span class="text-gray-300 px-1">...</span>';
                            echo '<a href="?page=' . $pages . $query_string . '" class="pagination-link w-10 h-10 flex items-center justify-center text-sm rounded-xl bg-white text-gray-500 hover:text-brand hover:bg-cream-50 transition-colors shadow-sm">' . $pages . '</a>';
                        }

                        // Next button
                        if ($pagination_page < $pages) {
                            $next = $pagination_page + 1;
                            echo '<a href="?page=' . $next . $query_string . '" class="pagination-link w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-500 hover:text-brand hover:bg-cream-50 transition-colors shadow-sm">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-5.23 5.23a.75.75 0 0 1-1.06-1.06L14.69 12 9.99 7.3a.75.75 0 0 1 1.06-1.06l5.23 5.23Z" clip-rule="evenodd"/></svg>';
                            echo '</a>';
                        }
                        ?>
                    </nav>
                </div>
            <?php } ?>
        <?php } ?>

        <?php render_custom_assets('body_bottom'); ?>
    </main>

    <!-- Footer -->
    <footer class="mt-16 border-t border-cream-200/80 bg-linear-to-br from-white to-cream-50">
        <div class="mx-auto max-w-360 px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center px-5 py-4">
                <div class="text-center text-sm text-slate-600">
                    <?php render_footer_text(); ?>
                </div>
            </div>
        </div>
    </footer>

    <script>
        window.folderAccordionTexts = {
            collapse: '<?php echo _e('Collapse', 'preference_template'); ?>',
            expand: '<?php echo _e('Expand', 'preference_template'); ?>'
        };
    </script>
</body>

</html>