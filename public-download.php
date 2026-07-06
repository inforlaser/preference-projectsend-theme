<?php
/*
Template name: Preference - Download Page
URI: https://www.projectsend.org/templates/preference
Author: ProjectSend
Author URI: https://www.projectsend.org/
Author e-mail: contact@projectsend.org
Description: Custom theme for Preference Porto.
*/
$ld = 'preference_template'; // specify the language domain for this template

// Get template and branding information
$this_template = get_option('selected_clients_template');
$this_template_url = BASE_URI.'templates/'.$this_template.'/';
$logo_file_info = generate_logo_url();
$site_title = get_option('this_install_title');

if (!$can_view) {
    header("Location: " . BASE_URI);
    exit;
}

$window_title = __('Download file','preference_template');

define('TEMPLATE_THUMBNAILS_WIDTH', '3840');
define('TEMPLATE_THUMBNAILS_HEIGHT', '2160');

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
    </script>


    <?php render_custom_assets('head'); ?>
</head>

<body class="bg-cream-100 min-h-screen flex flex-col text-slate-700">

    <?php render_custom_assets('body_top'); ?>

    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-50 border-b border-cream-200/70 bg-white/90 backdrop-blur-sm shadow-sm shadow-cream-200/40">
        <div class="mx-auto max-w-360 px-4 py-3.5">
            <div class="flex items-center justify-between gap-3">
                <!-- Logo -->
                <a href="<?php echo BASE_URI; ?>" class="shrink-0 rounded-full p-1 transition hover:bg-cream-50">
                    <?php if ($logo_file_info && $logo_file_info['exists']) { ?>
                        <img src="<?php echo $logo_file_info['url']; ?>" alt="<?php echo html_output($site_title); ?>"
                            class="h-8 w-auto max-w-32 object-contain sm:h-24 sm:max-w-[16rem]" />
                    <?php } else { ?>
                        <span class="text-xl font-bold text-brand sm:text-2xl"><?php echo html_output($site_title); ?></span>
                    <?php } ?>
                </a>

                <!-- User Menu -->
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

                    <!-- Login Link -->
                    <a href="<?php echo BASE_URI; ?>index.php"
                        class="flex items-center justify-center rounded-full border border-cream-200 bg-white p-2.5 text-slate-600 shadow-sm transition hover:border-brand/30 hover:bg-cream-50 hover:text-brand sm:gap-2 sm:px-3.5 sm:py-2 sm:text-sm sm:font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 text-slate-500 sm:h-6 sm:w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                        <span class="hidden text-gray-700 font-medium sm:inline"><?php _e('Login', 'preference_template'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="mx-auto flex-1 w-full max-w-360 px-4 py-8 sm:px-6 sm:py-12">
        <!-- Page Intro -->
        <div class="mb-8 text-center">
            <span class="inline-block rounded-full bg-cream-100 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                <?php echo html_output($window_title); ?>
            </span>
            <h1 class="mt-4 text-2xl font-bold text-slate-800 sm:text-3xl">
                <?php _e('File Details', 'preference_template'); ?>
            </h1>
        </div>

        <?php if ($can_view && !empty($file)) { ?>
            <!-- Download Card -->
            <div class="rounded-3xl border border-cream-200/80 bg-white shadow-sm shadow-cream-200/40 overflow-hidden">
                <!-- File Header with Icon -->
                <div class="flex items-center gap-4 border-b border-cream-200/80 bg-cream-50/70 px-6 py-5 sm:px-8">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white text-brand shadow-sm ring-1 ring-cream-200">
                        <?php
                        if ($file->isImage()) {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">';
                            echo '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />';
                            echo '</svg>';
                        } elseif (in_array($file->extension, ['pdf'])) {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">';
                            echo '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />';
                            echo '</svg>';
                        } elseif (in_array($file->extension, ['doc', 'docx'])) {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">';
                            echo '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />';
                            echo '</svg>';
                        } elseif (in_array($file->extension, ['xls', 'xlsx'])) {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">';
                            echo '<path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-18 0V12A2.25 2.25 0 0 1 5.25 9.75h13.5A2.25 2.25 0 0 1 21 12v4.5m-18 0V12a4.5 4.5 0 0 1 4.5-4.5h13.5A4.5 4.5 0 0 1 21 12v4.5m-9-9v9m-3-3l3 3 3-3" />';
                            echo '</svg>';
                        } elseif (in_array($file->extension, ['zip', 'rar', '7z', 'tar', 'gz'])) {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">';
                            echo '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
                            echo '</svg>';
                        } else {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">';
                            echo '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />';
                            echo '</svg>';
                        }
                        ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="truncate text-lg font-bold text-slate-800">
                            <?php echo html_output($file->title); ?>
                        </h2>
                        <div class="mt-0.5 flex items-center gap-2 text-xs text-slate-500">
                            <span class="rounded-md bg-cream-100 px-2 py-0.5 font-medium uppercase text-slate-600">
                                <?php echo strtoupper($file->extension); ?>
                            </span>
                            <span class="text-slate-300">•</span>
                            <span><?php echo $file->size_formatted; ?></span>
                        </div>
                    </div>
                </div>

                <!-- File Content -->
                <div class="px-6 py-8 sm:px-8 sm:py-10">
                    <!-- File Preview Image -->
                    <?php if ($file->isImage() && !empty($file->full_path) && file_exists($file->full_path)) {
                        $thumbnail = make_thumbnail($file->full_path, 'proportional', TEMPLATE_THUMBNAILS_WIDTH, TEMPLATE_THUMBNAILS_HEIGHT);
                    ?>
                        <div class="mb-8 min-w-0 overflow-hidden rounded-2xl border border-cream-200/80 bg-cream-50/70">
                            <div class="relative">
                                <span class="absolute right-3 top-3 z-10 rounded-full bg-black/60 px-2.5 py-1 text-xs font-semibold uppercase tracking-wider text-white backdrop-blur-sm">
                                    <?php _e('Preview', 'preference_template'); ?>
                                </span>
                                <?php if (!empty($thumbnail['thumbnail']['url'])) { ?>
                                    <img src="<?php echo $thumbnail['thumbnail']['url']; ?>"
                                        alt="<?php echo html_output($file->title); ?>"
                                        class="w-full border-b border-cream-200/80 object-contain"
                                        style="max-height: 240px; max-width: 100%; height: auto;" />
                                <?php } else { ?>
                                    <div class="flex items-center justify-center py-16 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" class="h-12 w-12">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                        </svg>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- File Description -->
                    <?php if (!empty($file->description)) { ?>
                        <div class="mb-8 rounded-2xl border border-cream-200/80 bg-cream-50/70 p-6">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <?php _e('Description', 'preference_template'); ?>
                            </h3>
                            <div class="mt-3 text-sm leading-relaxed text-slate-700">
                                <?php echo format_description($file->description); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- File Details Grid -->
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="min-w-0 rounded-2xl border border-cream-200/80 bg-cream-50/70 p-5">
                            <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <?php _e('File name', 'preference_template'); ?>
                            </dt>
                            <dd class="mt-1.5 break-words text-sm font-medium leading-snug text-slate-800" title="<?php echo html_output($file->filename_original); ?>">
                                <?php echo html_output($file->filename_original); ?>
                            </dd>
                        </div>

                        <div class="min-w-0 rounded-2xl border border-cream-200/80 bg-cream-50/70 p-5">
                            <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <?php _e('File size', 'preference_template'); ?>
                            </dt>
                            <dd class="mt-1.5 text-sm font-medium text-slate-800">
                                <?php echo $file->size_formatted; ?>
                            </dd>
                        </div>

                        <div class="min-w-0 rounded-2xl border border-cream-200/80 bg-cream-50/70 p-5">
                            <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <?php _e('File type', 'preference_template'); ?>
                            </dt>
                            <dd class="mt-1.5 text-sm font-medium text-slate-800">
                                <?php echo strtoupper($file->extension); ?>
                            </dd>
                        </div>

                        <div class="min-w-0 rounded-2xl border border-cream-200/80 bg-cream-50/70 p-5">
                            <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <?php _e('Upload date', 'preference_template'); ?>
                            </dt>
                            <dd class="mt-1.5 text-sm font-medium text-slate-800">
                                <?php echo !empty($file->uploaded_date) ? format_date($file->uploaded_date) : __('Unknown', 'preference_template'); ?>
                            </dd>
                        </div>

                        <?php if ($file->isImage()) {
                            $dimensions = $file->getDimensions();
                            if (!empty($dimensions['width'])) {
                        ?>
                                <div class="min-w-0 rounded-2xl border border-cream-200/80 bg-cream-50/70 p-5">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        <?php _e('Dimensions', 'preference_template'); ?>
                                    </dt>
                                    <dd class="mt-1.5 text-sm font-medium text-slate-800">
                                        <?php echo $dimensions['width']; ?> &times; <?php echo $dimensions['height']; ?> px
                                    </dd>
                                </div>
                        <?php
                            }
                        } ?>
                    </div>

                    <!-- Expiration Warning -->
                    <?php if ($file->expires == '1') { ?>
                        <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-6">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="mt-0.5 h-5 w-5 shrink-0 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-red-800">
                                        <?php _e('Expiration date', 'preference_template'); ?>
                                    </h3>
                                    <p class="mt-1 text-sm text-red-600">
                                        <?php echo date(get_option('timeformat'), strtotime($file->expiry_date)); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <!-- Download Actions Footer -->
                <div class="border-t border-cream-200/80 bg-cream-50/70 px-6 py-5 sm:px-8 sm:py-6">
                    <div class="flex flex-wrap items-center justify-end gap-3">
                        <?php if ($can_download) { ?>
                            <a href="<?php echo $file->public_url . '&download'; ?>"
                                class="inline-flex items-center gap-2 rounded-full bg-brand px-6 py-3 text-sm font-semibold uppercase tracking-wider text-white shadow-sm shadow-brand/20 transition-colors hover:bg-brand-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                    <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v3.75a3 3 0 0 0 3 3h13.5a3 3 0 0 0 3-3V16.5a.75.75 0 0 1 1.5 0v3.75a4.5 4.5 0 0 1-4.5 4.5H6.75a4.5 4.5 0 0 1-4.5-4.5V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <?php _e('Download', 'preference_template'); ?>
                            </a>

                        <?php } else { ?>
                            <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-6 py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-5 w-5 shrink-0 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                <span class="text-sm font-medium text-red-700">
                                    <?php _e('This file is not available for download.', 'preference_template'); ?>
                                </span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        <?php } else { ?>
            <!-- Access Denied Card -->
            <div class="rounded-3xl border border-cream-200/80 bg-white shadow-sm shadow-cream-200/40 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-16 px-6">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-red-50 text-red-400 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-10 w-10">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">
                        <?php _e('Access Denied', 'preference_template'); ?>
                    </h3>
                    <p class="text-sm text-slate-500 text-center max-w-md">
                        <?php _e('Access denied or file not found.', 'preference_template'); ?>
                    </p>
                    <a href="<?php echo BASE_URI; ?>"
                        class="mt-6 inline-flex items-center gap-2 rounded-full bg-brand px-6 py-3 text-sm font-semibold uppercase tracking-wider text-white shadow-sm shadow-brand/20 transition-colors hover:bg-brand-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>
                        <?php _e('Return to Home', 'preference_template'); ?>
                    </a>
                </div>
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
</body>

</html>