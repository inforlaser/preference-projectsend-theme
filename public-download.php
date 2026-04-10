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

define('TEMPLATE_THUMBNAILS_WIDTH', '300');
define('TEMPLATE_THUMBNAILS_HEIGHT', '300');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo html_output( $window_title . ' &raquo; ' . $site_title ); ?></title>
		<?php meta_favicon(); ?>
		<link href='https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Sora:wght@500;600;700&display=swap' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo $this_template_url; ?>/font-awesome-4.6.3/css/font-awesome.min.css">
		<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template_url; ?>main.min.css" />

        <script>
            window.base_url = '<?php echo BASE_URI; ?>';
        </script>
        <script src="<?php echo $this_template_url; ?>/js/jquery.1.11.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>

        <script>
            function openPreviewImage(url, title) {
                var modal = document.getElementById('previewModal');
                var modalTitle = document.getElementById('previewModalTitle');
                var modalBody = document.getElementById('previewModalBody');

                if (modal && modalTitle && modalBody) {
                    modalTitle.textContent = title;
                    modalBody.innerHTML = '<img src="' + url + '" alt="' + title.replace(/"/g, '&quot;') + '" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">';
                    modal.style.display = 'block';
                }
            }

            function closePreview() {
                var modal = document.getElementById('previewModal');
                var body = document.getElementById('previewModalBody');

                if (modal) {
                    modal.style.display = 'none';
                }

                if (body) {
                    body.innerHTML = '';
                }
            }

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePreview();
                }
            });

            // Close modal when clicking outside
            document.addEventListener('click', function(e) {
                var modal = document.getElementById('previewModal');
                if (e.target === modal) {
                    closePreview();
                }
            });
        </script>

        <?php render_custom_assets('head'); ?>
	</head>

    <body class="preference_theme download_page">
        <?php render_custom_assets('body_top'); ?>

		<div id="header">
            <div class="header_inner">
                <div id="branding" class="branding_lockup">
                    <?php if ($logo_file_info && $logo_file_info['exists']) { ?>
                        <img class="brand_logo" src="<?php echo $logo_file_info['url']; ?>" alt="<?php echo html_output($site_title); ?>">
                    <?php } ?>
                    <div class="brand_text">
                        <span class="brand_title"><?php echo html_output($site_title); ?></span>
                    </div>
                </div>
            </div>
		</div>

		<div id="menu">
            <div class="menu_inner">
                <p class="welcome">
                    <?php _e('File Download','preference_template'); ?>
                    <span><?php echo html_output($site_title); ?></span>
                </p>
                <ul>
				<li>
                    <a href="<?php echo BASE_URI; ?>index.php" target="_self">
                        <i class="fa fa-sign-in" aria-hidden="true"></i> <?php _e('Login', 'preference_template'); ?>
                    </a>
				</li>
                </ul>
            </div>
		</div>

		<div id="content">
			<div class="wrapper">
                <div class="page_intro">
                    <div class="page_intro_heading">
                        <p class="page_intro_eyebrow"><?php echo html_output($window_title); ?></p>
                    </div>
                    <p class="page_intro_meta">Secure access to the requested file with the same shared visual system.</p>
                </div>

                <?php if ($can_view && !empty($file)) { ?>
                    <a href="javascript:history.back()" class="back-link">
                        <i class="fa fa-arrow-left"></i> <?php _e('Back', 'preference_template'); ?>
                    </a>

                    <div class="download-container">
                        <div class="download-header">
                            <div class="file-icon">
                                <?php
                                    if ($file->isImage()) {
                                        echo '<i class="fa fa-picture-o"></i>';
                                    } elseif (in_array($file->extension, ['pdf'])) {
                                        echo '<i class="fa fa-file-pdf-o"></i>';
                                    } elseif (in_array($file->extension, ['doc', 'docx'])) {
                                        echo '<i class="fa fa-file-word-o"></i>';
                                    } elseif (in_array($file->extension, ['xls', 'xlsx'])) {
                                        echo '<i class="fa fa-file-excel-o"></i>';
                                    } elseif (in_array($file->extension, ['zip', 'rar', '7z'])) {
                                        echo '<i class="fa fa-file-archive-o"></i>';
                                    } elseif (in_array($file->extension, ['mp4', 'avi', 'mov'])) {
                                        echo '<i class="fa fa-file-video-o"></i>';
                                    } elseif (in_array($file->extension, ['mp3', 'wav', 'ogg'])) {
                                        echo '<i class="fa fa-file-audio-o"></i>';
                                    } else {
                                        echo '<i class="fa fa-file-o"></i>';
                                    }
                                ?>
                            </div>
                            <h1><?php echo html_output($file->title); ?></h1>
                        </div>

                        <div class="file-info">
                            <?php
                                $preview_image_url = '';
                                if ($file->isImage() && $can_download) {
                                    $preview_thumbnail = make_thumbnail($file->full_path, null, 1400, 1400);
                                    if (!empty($preview_thumbnail) && isset($preview_thumbnail['thumbnail']) && !empty($preview_thumbnail['thumbnail']['exists'])) {
                                        $preview_image_url = $preview_thumbnail['thumbnail']['url'];
                                    }
                            ?>
                                <div class="file-preview">
                                    <?php
                                        $thumbnail = make_thumbnail($file->full_path, null, TEMPLATE_THUMBNAILS_WIDTH, TEMPLATE_THUMBNAILS_HEIGHT);
                                        if (!empty($thumbnail) && isset($thumbnail['thumbnail']) && !empty($thumbnail['thumbnail']['exists'])) {
                                    ?>
                                        <img src="<?php echo $thumbnail['thumbnail']['url']; ?>" alt="<?php echo html_output($file->title); ?>" />
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="file-details">
                                <div class="file-detail">
                                    <strong><?php _e('File name', 'preference_template'); ?></strong>
                                    <span><?php echo html_output($file->filename_original); ?></span>
                                </div>
                                <div class="file-detail">
                                    <strong><?php _e('File size', 'preference_template'); ?></strong>
                                    <span><?php echo $file->size_formatted; ?></span>
                                </div>
                                <div class="file-detail">
                                    <strong><?php _e('File type', 'preference_template'); ?></strong>
                                    <span><?php echo strtoupper($file->extension); ?></span>
                                </div>
                                <div class="file-detail">
                                    <strong><?php _e('Upload date', 'preference_template'); ?></strong>
                                    <span><?php echo !empty($file->uploaded_date) ? format_date($file->uploaded_date) : __('Unknown', 'preference_template'); ?></span>
                                </div>
                            </div>

                            <?php if (!empty($file->description)) { ?>
                                <div class="file-description">
                                    <strong><?php _e('Description', 'preference_template'); ?>:</strong><br>
                                    <?php echo format_description($file->description); ?>
                                </div>
                            <?php } ?>

                            <?php if ($file->isImage()) {
                                $dimensions = $file->getDimensions();
                                if (!empty($dimensions['width'])) {
                            ?>
                                    <div class="file-description">
                                        <strong><?php _e('Dimensions', 'preference_template'); ?>:</strong><br>
                                        <?php echo $dimensions['width']; ?> x <?php echo $dimensions['height']; ?> px
                                    </div>
                            <?php
                                }
                            } ?>

                            <?php if ($file->expires == '1') { ?>
                                <div class="file-description file-description_alert">
                                    <strong><?php _e('Expiration date', 'preference_template'); ?>:</strong><br>
                                    <span>
                                        <?php echo date(get_option('timeformat'), strtotime($file->expiry_date)); ?>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="download-actions">
                            <?php if ($can_download) { ?>
                                <a href="<?php echo $file->download_link; ?>" class="download-button">
                                    <i class="fa fa-download"></i> <?php _e('Download', 'preference_template'); ?>
                                </a>

                                <?php if (!empty($preview_image_url)) { ?>
                                    <a href="#" onclick="openPreviewImage('<?php echo $preview_image_url; ?>', '<?php echo addslashes($file->title); ?>'); return false;" class="preview-button">
                                        <i class="fa fa-eye"></i> <?php _e('Preview', 'preference_template'); ?>
                                    </a>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="access-denied">
                                    <i class="fa fa-lock"></i>
                                    <?php _e('This file is not available for download.', 'preference_template'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                <?php } else { ?>
                    <div class="download-container">
                        <div class="access-denied">
                            <i class="fa fa-exclamation-triangle"></i>
                            <?php _e('Access denied or file not found.', 'preference_template'); ?>
                        </div>
                    </div>
                <?php } ?>

			</div>

			<?php render_footer_text(); ?>
        </div>

        <!-- Preview Modal -->
        <div id="previewModal" class="preview-modal">
            <div class="preview-modal-content">
                <div class="preview-modal-header">
                    <h3 id="previewModalTitle"><?php _e('File Preview', 'preference_template'); ?></h3>
                    <button class="preview-modal-close" onclick="closePreview()">&times;</button>
                </div>
                <div class="preview-modal-body" id="previewModalBody">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
        </div>

        <?php render_custom_assets('body_bottom'); ?>

	</body>
</html>