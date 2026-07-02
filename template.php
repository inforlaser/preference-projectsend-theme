<?php
/*
Template name: Preference
URI: 
Author: Nuno Fernandes - Highly based on Pinboxes template by ProjectSend
Author URI: https://inforlaser.pt
Author e-mail: nunofernandes@inforlaser.pt
Description: Custom theme for Preference Porto.
*/
$ld = 'preference_template'; // specify the language domain for this template

// Handle per_page parameter for pagination and filtering
$default_per_page = get_option('pagination_results_per_page');
$allowed_per_page_values = [5, 10, 15, 20, 25, 50, 100];
if (isset($_GET['per_page']) && in_array((int) $_GET['per_page'], $allowed_per_page_values, true)) {
    define('TEMPLATE_RESULTS_PER_PAGE', (int) $_GET['per_page']);
} else {
    define('TEMPLATE_RESULTS_PER_PAGE', $default_per_page);
}

define('TEMPLATE_THUMBNAILS_WIDTH', '120');
define('TEMPLATE_THUMBNAILS_HEIGHT', '120');

$filter_by_category = (isset($_GET['category']) && $_GET['category'] !== '') ? $_GET['category'] : null;

$current_url = get_form_action_with_existing_parameters('index.php');

// When searching, don't limit to current folder - search globally
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $_GET['global_search'] = true;
}

include_once ROOT_DIR . '/templates/common.php'; // include the required functions for this template

// Generate CSRF token
$csrf_token = getCsrfToken();
$site_title = get_option('this_install_title');
$count = count($my_files);

?>
<!DOCTYPE html>
<html lang="<?php echo LOADED_LANG; ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo html_output($site_title); ?></title>
    <?php meta_favicon(); ?>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Tailwind Elements JS -->
    <script type="module" src="<?php echo $this_template_url; ?>js/tailwindplus-elements.js"></script>

    <!-- Custom theme CSS -->
    <link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template_url; ?>preference.min.css" />
    <script>
        window.base_url = '<?php echo BASE_URI; ?>';
    </script>

    <?php render_custom_assets('head'); ?>
    <script src="<?php echo $this_template_url; ?>js/template.js"></script>
</head>

<body class="bg-cream-100 min-h-screen flex flex-col text-slate-700">

    <?php render_custom_assets('body_top'); ?>

    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-50 border-b border-cream-200/70 bg-white/90 backdrop-blur-sm shadow-sm shadow-cream-200/40">
        <div class="mx-auto max-w-360 px-4 py-3.5">
            <div class="flex items-center justify-between gap-3">
                <!-- Logo -->
                <a href="<?php echo $base_uri; ?>" class="shrink-0 rounded-full p-1 transition hover:bg-cream-50">
                    <?php if ($logo_file_info && $logo_file_info['exists']) { ?>
                        <img src="<?php echo $logo_file_info['url']; ?>" alt="<?php echo 'adsda'; ?>"
                        class="h-8 w-auto max-w-32 object-contain sm:h-24 sm:max-w-[16rem]" />
                    <?php } else { ?>
                        <span class="text-xl font-bold text-brand sm:text-2xl"><?php echo 'asdas'; ?></span>
                    <?php } ?>
                </a>

                <!-- User Menu -->
                <div class="flex items-center gap-2">
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
                            foreach ($available_langs as $filename => $lang_name) {
                                ?>
                                <a href="<?php echo BASE_URI . 'process.php?do=change_language&language=' . $filename . '&return_to=' . $return_to; ?>"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-cream-50 hover:text-brand transition-colors">
                                    <?php echo $lang_name; ?>
                                </a>
                                <?php
                            }
                            ?>
                        </el-menu>
                    </el-dropdown>
                    <!-- User Dropdown -->
                    <el-dropdown class="relative">
                        <button
                            class="flex items-center justify-center rounded-full border border-cream-200 bg-white p-2.5 text-slate-600 shadow-sm transition hover:border-brand/30 hover:bg-cream-50 hover:text-brand sm:gap-2 sm:px-3.5 sm:py-2 sm:text-sm sm:font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-5 w-5 text-slate-500 sm:h-6 sm:w-6">
                                <path fill-rule="evenodd"
                                    d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="hidden text-gray-700 font-medium sm:inline"><?php echo html_output(CURRENT_USER_NAME); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="hidden h-6 w-6 text-gray-400 sm:block">
                                <path fill-rule="evenodd"
                                    d="M12.53 16.28a.75.75 0 0 1-1.06 0l-5.23-5.23a.75.75 0 1 1 1.06-1.06L12 14.19l4.7-4.7a.75.75 0 1 1 1.06 1.06l-5.23 5.23Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <el-menu anchor="bottom end" popover
                            class="right-0 top-full mt-2 w-56 overflow-hidden rounded-2xl border border-cream-200/80 bg-white shadow-[0_18px_50px_-12px_rgba(15,23,42,0.2)] ring-1 ring-brand/10">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b border-cream-100">
                                    <p class="text-xs text-gray-400">
                                        <?php echo _e('Signed in as', 'preference_template'); ?>
                                    </p>
                                    <p class="text-sm font-medium text-gray-700 truncate">
                                        <?php echo html_output(CURRENT_USER_NAME); ?>
                                    </p>
                                </div>
                                <a href="<?php echo client_get_profile_link(); ?>"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-cream-50 hover:text-brand transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                                    </svg>
                                    <?php echo _e('My Account', 'preference_template'); ?>
                                </a>
                                <a href="<?php echo BASE_URI; ?>process.php?do=logout"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>

                                    <?php echo _e('Logout', 'preference_template'); ?>
                                </a>
                            </div>
                        </el-menu>
                    </el-dropdown>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb Navigation -->
    <?php if (!empty($_GET['folder_id'])): ?>
        <div class="mx-auto w-full max-w-360 px-4 py-4 sm:px-6">
            <div class="flex flex-wrap items-center gap-2 rounded-2xl border border-cream-200/80 bg-white/80 px-4 py-3 text-sm shadow-sm shadow-cream-200/40">
                <?php
                // Build full breadcrumb path
                $breadcrumbs = [];
                $current_folder_id = !empty($_GET['folder_id']) ? (int) $_GET['folder_id'] : null;

                // If we're in a folder, build the path
                if ($current_folder_id) {
                    $temp_folder_id = $current_folder_id;
                    while ($temp_folder_id) {
                        $temp_folder = new \ProjectSend\Classes\Folder($temp_folder_id);
                        $temp_data = $temp_folder->getData();
                        if ($temp_data) {
                            array_unshift($breadcrumbs, [
                                'id' => $temp_data['id'],
                                'name' => $temp_data['name'],
                                'parent' => $temp_data['parent']
                            ]);
                            $temp_folder_id = $temp_data['parent'];
                        } else {
                            break;
                        }
                    }
                }

                // Root link
                $root_link = modify_url_with_parameters($current_url, [], ['folder_id']);
                ?>
                <a href="<?php echo $root_link; ?>" class="group flex items-center gap-2 hover:bg-cream-200 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <span class="text-gray-700 font-medium text-sm break-words group-hover:text-brand transition-colors">
                        <?php _e('Root Folder', 'preference_template') ?>
                    </span>
                </a>

                <?php if (!empty($breadcrumbs)): ?>
                    <?php foreach ($breadcrumbs as $index => $breadcrumb):
                        $is_last = ($index === count($breadcrumbs) - 1);
                        $breadcrumb_link = modify_url_with_parameters($current_url, ['folder_id' => $breadcrumb['id']], ['folder_id']); ?>
                        <span class="text-slate-400">></span>
                        <?php if ($is_last): ?>
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                                </svg>
                                <span class="text-gray-500 font-medium text-sm break-words">
                                    <?php echo html_output($breadcrumb['name']); ?>
                                </span>
                            </span>
                        <?php else: ?>
                            <a href="<?php echo $breadcrumb_link; ?>"
                                class="flex items-center gap-2 hover:bg-cream-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                </svg>
                                <span class="text-gray-700 font-medium text-sm break-words">
                                    <?php echo html_output($breadcrumb['name']); ?>
                                </span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <main class="mx-auto flex-1 w-full max-w-360 px-4 py-3 sm:px-6 sm:py-4">
        <?php if (!empty($folders)): ?>
            <details class="mb-6" open>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-3xl border border-cream-200 bg-white p-5 shadow-sm shadow-cream-200/40">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">
                        <?php _e('Folder Navigation', 'preference_template'); ?>
                    </h2>
                    <span class="rounded-full bg-cream-100 px-3 py-1 text-xs font-medium text-slate-500">
                        <?php echo count($folders); ?> <?php _e('folders', 'preference_template'); ?>
                    </span>
                </summary>
                <div class="mt-3 rounded-3xl border border-cream-200 bg-white p-5 shadow-sm shadow-cream-200/40">
                    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        <?php
                        sort($folders);
                        foreach ($folders as $folder_data):
                            $folder = new \ProjectSend\Classes\Folder($folder_data['id']);
                            $folder_data = $folder->getData();
                            $link = modify_url_with_parameters($current_url, ['folder_id' => $folder_data['id']], ['folder_id']); ?>
                            <a href="<?php echo $link; ?>"
                                class="group flex w-full min-w-0 items-start gap-3 rounded-2xl border border-cream-200 bg-cream-50/70 p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-brand/30 hover:bg-white hover:shadow-md">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand shadow-sm ring-1 ring-cream-200 group-hover:bg-brand group-hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6"
                                        stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="line-clamp-2 break-words text-sm font-semibold leading-snug text-slate-700 group-hover:text-brand sm:line-clamp-1">
                                        <?php echo html_output($folder->name); ?>
                                    </div>
                                    <div class="mt-1 text-xs text-slate-400">
                                        <?php _e('Open folder', 'preference_template'); ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </details>
        <?php endif; ?>

        <!-- Search and Filters -->
        <?php if ($count > 0 || isset($_GET['search']) || isset($_GET['category'])): ?>
            <div class="mb-6 rounded-3xl border border-cream-200 bg-white p-5 shadow-sm shadow-cream-200/40 sm:p-6">
                <form action="<?php echo BASE_URI; ?>my_files/index.php" method="get"
                    class="flex flex-col lg:flex-row gap-4">
                    <?php
                    // Preserve folder_id if present
                    if (isset($_GET['folder_id'])): ?>
                        <input type="hidden" name="folder_id" value="<?php echo htmlspecialchars($_GET['folder_id']); ?>">
                    <?php endif; ?>

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
                                value="<?php echo isset($_GET['search']) ? html_output($_GET['search']) : ''; ?>"
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

                    <!-- Results Per Page -->
                    <div class="lg:w-32">
                        <select name="per_page" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 border border-cream-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                            <?php
                            $current_per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : TEMPLATE_RESULTS_PER_PAGE;
                            foreach ([5, 10, 15, 20, 25, 50, 100] as $value): ?>
                                <option value="<?php echo $value; ?>" <?php echo ($current_per_page == $value) ? 'selected' : ''; ?>>
                                    <?php echo $value . ' ' . __('per page', 'preference_template'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-brand hover:bg-brand-dark text-white rounded-lg font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand/30 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <?php _e('Apply', 'preference_template'); ?>
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Selection Bar -->
        <div class="selection-bar mb-6 flex flex-col gap-3 rounded-2xl border border-cream-200 bg-white px-4 py-3 shadow-sm shadow-cream-200/40 sm:flex-row sm:items-center sm:justify-between sm:px-5"
            id="selection-bar">
            <div class="flex items-center gap-3">
                <label for="select-all" class="flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-600">
                    <input type="checkbox" id="select-all" class="custom-checkbox accent-brand" aria-label="<?php echo _e('Select all files', 'preference_template'); ?>" />
                    <span><?php echo _e('Select all', 'preference_template'); ?></span>
                </label>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span id="selection-summary" class="text-xs text-slate-400 sm:mr-1" data-total="<?php echo count($available_files ?? []); ?>">0 of <?php echo count($available_files ?? []); ?> items selected for download.</span>
                <a href="#" id="zip_download" class="zip-button inline-flex items-center gap-2 rounded-lg border border-cream-200 bg-white px-4 py-2 text-slate-700 transition disabled:pointer-events-none disabled:opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v3.75a3 3 0 0 0 3 3h13.5a3 3 0 0 0 3-3V16.5a.75.75 0 0 1 1.5 0v3.75a4.5 4.5 0 0 1-4.5 4.5H6.75a4.5 4.5 0 0 1-4.5-4.5V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                    </svg>
                    <?php echo _e('Download zipped', 'preference_template'); ?>
                </a>
            </div>
        </div>

        <!-- Files List -->
        <?php
            // Ensure we have an available files list and pagination count
            if (!isset($available_files)) {
                $available_files = isset($my_files) ? $my_files : [];
            }
            if (!isset($count_for_pagination)) {
                $count_for_pagination = isset($count) ? $count : count($available_files);
            }

            if (isset($count) && $count > 0) { ?>
            <div class="space-y-3">
                <?php foreach ($available_files as $file_id) {
                    $file = new ProjectSend\Classes\Files($file_id);

                    // safe fallbacks
                    $extension = !empty($file->extension) ? strtolower($file->extension) : '';
                    $file_size = !empty($file->size_formatted) ? $file->size_formatted : '';
                    $upload_date = !empty($file->uploaded_date) ? date(get_option('timeformat'), strtotime($file->uploaded_date)) : '';
                    $download_url = !empty($file->download_link) ? $file->download_link : BASE_URI . 'process.php?do=download&file_id=' . $file->id;
                    $expired_class = $file->expired ? 'opacity-50' : '';
                ?>
                    <div class="file-card group flex flex-col overflow-hidden rounded-2xl border border-cream-200/80 bg-white shadow-sm shadow-cream-200/30 transition-all duration-200 hover:-translate-y-0.5 hover:border-brand/30 hover:shadow-md lg:flex-row <?php echo $expired_class; ?>"
                         data-file-id="<?php echo $file->id; ?>"
                         role="button"
                         tabindex="0"
                         aria-label="<?php echo html_output($file->title ? $file->title : $file->filename_original); ?>">
                        <div class="file-thumb flex w-full shrink-0 items-center justify-center bg-cream-50/70 p-3 lg:w-32">
                            <?php if ($file->isImage() && !empty($file->full_path) && file_exists($file->full_path)) {
                                $thumbnail = make_thumbnail($file->full_path, 'proportional', TEMPLATE_THUMBNAILS_WIDTH, TEMPLATE_THUMBNAILS_HEIGHT);
                            ?>
                                <img src="<?php echo $thumbnail['thumbnail']['url']; ?>" alt="<?php echo html_output($file->title); ?>" class="h-24 w-24 rounded-lg object-cover" />
                            <?php } else { ?>
                                <div class="flex h-24 w-24 items-center justify-center rounded-xl bg-white text-sm font-bold uppercase text-slate-600 shadow-sm ring-1 ring-cream-200">
                                    <?php echo htmlspecialchars(substr($extension, 0, 4)); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="flex flex-1 flex-col justify-between gap-4 px-4 py-4 sm:px-6 sm:py-5 lg:flex-wrap lg:items-start lg:gap-6">
                            <div class="min-w-0 flex-1 self-stretch">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 flex shrink-0 items-center justify-center self-start">
                                        <input type="checkbox"
                                               class="file-checkbox custom-checkbox"
                                               value="<?php echo $file->id; ?>"
                                               data-size="<?php echo (int) $file->size; ?>"
                                               aria-label="<?php echo _e('Select file', 'preference_template'); ?>" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="mb-1 break-words text-base font-semibold leading-6 text-slate-800">
                                            <?php echo html_output($file->title ? $file->title : $file->filename_original); ?>
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                            <?php if ($file->expired) { ?>
                                                <span class="rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-600"><?php echo _e('Expired', 'preference_template'); ?></span>
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
                                <a href="<?php echo $download_url; ?>" target="_blank"
                                   class="download-action inline-flex flex-wrap items-center justify-center gap-2 rounded-full bg-brand px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-white shadow-sm shadow-brand/20 transition-colors hover:bg-brand-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                        <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v3.75a3 3 0 0 0 3 3h13.5a3 3 0 0 0 3-3V16.5a.75.75 0 0 1 1.5 0v3.75a4.5 4.5 0 0 1-4.5 4.5H6.75a4.5 4.5 0 0 1-4.5-4.5V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                    </svg>
                                    <?php echo _e('Download', 'preference_template'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Pagination -->
            <?php if (TEMPLATE_RESULTS_PER_PAGE > 0 && $count_for_pagination > TEMPLATE_RESULTS_PER_PAGE) { ?>
                <div class="mt-8 flex items-center justify-center">
                    <nav class="flex items-center gap-2" aria-label="<?php echo __('Pagination', 'preference_template'); ?>">
                        <?php
                        $pagination_page = (isset($_GET["page"]) && is_numeric($_GET["page"])) ? $_GET["page"] : 1;
                        $pages = ceil($count_for_pagination / TEMPLATE_RESULTS_PER_PAGE);
                        $query_string = '';
                        
                        if (isset($_GET['search'])) $query_string .= '&search=' . urlencode($_GET['search']);
                        if (isset($_GET['category']) && $_GET['category'] !== '') $query_string .= '&category=' . $_GET['category'];
                        if (isset($_GET['per_page'])) $query_string .= '&per_page=' . $_GET['per_page'];
                        if (isset($_GET['folder_id'])) $query_string .= '&folder_id=' . $_GET['folder_id'];
                        
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
                            if ($start_page > 2) echo '<span class="text-gray-300 px-1">...</span>';
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
                            if ($end_page < $pages - 1) echo '<span class="text-gray-300 px-1">...</span>';
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

        <?php } else { ?>
            <!-- No Files Message -->
            <div class="no-files-state flex flex-col items-center justify-center py-20 rounded-3xl">
                <div class="w-20 h-20 bg-cream-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 text-gray-300">
                        <path fill-rule="evenodd" d="M19.5 22.5a7.5 7.5 0 0 0 7.5-7.5v-9a7.5 7.5 0 0 0-7.5-7.5h-9A7.5 7.5 0 0 0 3 6v9a7.5 7.5 0 0 0 7.5 7.5h9Z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-400 mb-1"><?php echo _e('No files found', 'preference_template'); ?></h3>
                <p class="text-sm text-gray-300"><?php echo _e('There are no files matching your criteria.', 'preference_template'); ?></p>
                <?php $search_query = isset($_GET['search']) ? trim((string) $_GET['search']) : ''; ?>
                <?php if ($search_query !== '') { ?>
                    <a href="<?php echo modify_url_with_parameters($current_url, [], ['search', 'page']); ?>"
                       class="mt-4 px-6 py-2.5 bg-brand hover:bg-brand-dark text-white text-sm font-medium rounded-full transition-colors">
                        <?php echo _e('Clear search', 'preference_template'); ?>
                    </a>
                <?php } ?>
            </div>
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
        window.csrf_token = '<?php echo $csrf_token; ?>';
    </script>
</body>

</html>