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

define('TEMPLATE_RESULTS_PER_PAGE', -1);

$filter_by_category = isset($_GET['category']) ? $_GET['category'] : null;

include_once ROOT_DIR.'/templates/common.php'; // include the required functions for every template

$window_title = __('Available files','preference_template');
$site_title = get_option('this_install_title');

$count = count($my_files);

define('TEMPLATE_THUMBNAILS_WIDTH', '120');
define('TEMPLATE_THUMBNAILS_HEIGHT', '120');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo html_output( $client_info['name'].' | '.$window_title . ' &raquo; ' . $site_title ); ?></title>
		<?php meta_favicon(); ?>
		<link href='https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Sora:wght@500;600;700&display=swap' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo $this_template_url; ?>/font-awesome-4.6.3/css/font-awesome.min.css">
		<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template_url; ?>main.min.css" />
        
        <script>
            window.base_url = '<?php echo BASE_URI; ?>';
        </script>
        <script src="<?php echo $this_template_url; ?>/js/jquery.1.11.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
		<script src="<?php echo $this_template_url; ?>/js/jquery.masonry.min.js"></script>
        <script src="<?php echo $this_template_url; ?>/js/imagesloaded.pkgd.min.js"></script>
        <script src="<?php echo $this_template_url; ?>/js/template.js"></script>

        <?php render_custom_assets('head'); ?>
	</head>
	
	<body class="preference_theme authenticated_page">
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
				<div class="topbar_controls">
					<button type="button" class="topbar_toggle" id="topbar_toggle" aria-expanded="false" aria-controls="topbar_menu">
						<i class="fa fa-bars" aria-hidden="true"></i>
						<span><?php _e('Menu', 'preference_template'); ?></span>
					</button>
					<div class="topbar_menu">
						<ul id="topbar_menu">
						<li id="search_box">
							<form action="" name="files_search" method="get">
								<input type="text" name="search" id="search_text" value="<?php echo (isset($_GET['search']) && !empty($_GET['search'])) ? html_output($_GET['search']) : ''; ?>" placeholder="<?php _e('Search...','preference_template'); ?>">
								<button type="submit" id="search_go"><i class="fa fa-search" aria-hidden="true"></i></button>
							</form>
						</li>
						<?php
							if ( !empty( $get_categories['categories'] ) ) {
								$url_client_id	= ( !empty($_GET['client'] ) && !current_role_in(['Client'])) ? $_GET['client'] : null;
								$link_template	= CLIENT_VIEW_FILE_LIST_URL;
						?>
							<li class="categories_trigger menu_dropdown_trigger">
								<a href="#" target="_self" aria-expanded="false"><i class="fa fa-filter" aria-hidden="true"></i> <?php _e('Categories', 'preference_template'); ?></a>
								<ul class="categories menu_dropdown_panel">
									<li class="filter_all_files"><a href="<?php echo CLIENT_VIEW_FILE_LIST_URL; if ( !empty( $url_client_id ) ) { echo '?client=' . $url_client_id; }; ?>"><?php  _e('All files', 'preference_template'); ?></a></li>
									<?php
										foreach ( $get_categories['categories'] as $category ) {
											$link_data	= array(
                                            'client' => $url_client_id,
                                            'category' => $category['id'],
                                        );
											$link_query	= http_build_query($link_data);
									?>
											<li><a href="<?php echo $link_template . '?' . $link_query; ?>"><?php echo $category['name']; ?></a></li>
									<?php
										}
									?>							
								</ul>
							</li>
						<?php
							}
						?>
						<?php if (current_user_can_upload()) { ?>
						<li class="mobile_only_action">
                    <a href="<?php echo BASE_URI; ?>upload.php" target="_self">
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i> <?php _e('Upload', 'preference_template'); ?>
                    </a>
						</li>
						<li class="mobile_only_action">
                    <a href="<?php echo BASE_URI; ?>manage-files.php" target="_self">
                        <i class="fa fa-file" aria-hidden="true"></i> <?php _e('Manage', 'preference_template'); ?>
                    </a>
						</li>
						<?php } ?>
						<li class="mobile_only_action">
                    <a href="<?php echo BASE_URI; ?>process.php?do=logout" target="_self">
                        <i class="fa fa-sign-out" aria-hidden="true"></i> <?php _e('Logout', 'preference_template'); ?>
                    </a>
						</li>
						<li class="mobile_only_user">
                    <span><?php echo html_output($client_info['name']); ?></span>
						</li>
						<li class="user_menu_trigger menu_dropdown_trigger">
							<button type="button" class="user_menu_button" aria-expanded="false">
								<span class="user_menu_name"><?php echo html_output($client_info['name']); ?></span>
								<i class="fa fa-chevron-down" aria-hidden="true"></i>
							</button>
							<ul class="user_dropdown menu_dropdown_panel">
								<?php if (current_user_can_upload()) { ?>
								<li>
                    <a href="<?php echo BASE_URI; ?>upload.php" target="_self">
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i> <?php _e('Upload', 'preference_template'); ?>
                    </a>
								</li>
								<li>
                    <a href="<?php echo BASE_URI; ?>manage-files.php" target="_self">
                        <i class="fa fa-file" aria-hidden="true"></i> <?php _e('Manage', 'preference_template'); ?>
                    </a>
								</li>
								<?php } ?>
								<li>
                    <a href="<?php echo BASE_URI; ?>process.php?do=logout" target="_self">
                        <i class="fa fa-sign-out" aria-hidden="true"></i> <?php _e('Logout', 'preference_template'); ?>
                    </a>
								</li>
							</ul>
						</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div id="content">
			<div class="content_cover"></div>
			<div class="wrapper">
				<div class="page_intro">
					<div class="page_intro_heading">
						<p class="page_intro_eyebrow"><?php echo html_output($window_title); ?></p>
					</div>
					<p class="page_intro_meta"><?php echo (int) $count; ?> files available in your workspace.</p>
				</div>
		
            <?php
                $current_url = get_form_action_with_existing_parameters('index.php');
                include_once LAYOUT_DIR . DS . 'breadcrumbs.php';
                include_once LAYOUT_DIR . DS . 'folders-nav.php';

            if (!$count) {
		?>
				<div class="no_files">
					<?php
						_e('There are no files.','preference_template');
					?>
				</div>
		<?php
			}
			else {
		?>
				<div class="files_toolbar">
					<div class="select_all_toggle">
						<input type="checkbox" id="select_all_files">
						<label for="select_all_files"><?php _e('Select all', 'preference_template'); ?></label>
					</div>
					<p class="files_toolbar_meta"><?php _e('Choose files to include in the zip download.', 'preference_template'); ?></p>
				</div>
				<div class="photo_list">
                <?php
					foreach ($available_files as $file_id) {
                        $file = new \ProjectSend\Classes\Files($file_id);
                        ?>
						<div class="photo selectable <?php if ($file->expired == true) { echo 'expired'; } ?>">
							<div class="photo_int">
                                <?php
                                    if ($file->isImage()) {
								?>
										<div class="img_prev">
											<?php
												if ($file->expired == false) {
											?>
														<div class="img_prev_media">
		                                                        <?php $thumbnail = make_thumbnail($file->full_path, null, TEMPLATE_THUMBNAILS_WIDTH, TEMPLATE_THUMBNAILS_HEIGHT); ?>
														<img src="<?php echo $thumbnail['thumbnail']['url']; ?>" alt="<?php echo html_output($file->title); ?>" />
														</div>
											<?php
												}
											?>
										</div>
								<?php
									} else {
										if ($file->expired == false) {
								?>
											<div class="ext_prev">
											<div class="ext_prev_media">
													<h6><?php echo $file->extension; ?></h6>
											</div>
											</div>
								<?php
										}
									}
								?>
							</div>
							<div class="img_data">
								<h2><?php echo html_output($file->title); ?></h2>
								<div class="photo_info">
									<?php if (!empty($file->description)) { ?>
										<div class="file_description_excerpt"><?php echo format_description($file->description); ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="file_meta_column">
								<div class="file_summary">
									<span class="file_summary_chip"><?php echo $file->size_formatted; ?></span>
									<span class="file_summary_chip"><?php echo !empty($file->uploaded_date) ? format_date($file->uploaded_date) : __('Unknown', 'preference_template'); ?></span>

                                    <?php
                                        if ($file->isImage()) {
                                            $dimensions = $file->getDimensions();
                                            if (!empty($dimensions['width'])) {
                                    ?>
										<span class="file_summary_chip file_summary_chip_subtle"><?php echo $dimensions['width']; ?> x <?php echo $dimensions['height']; ?> px</span>
                                    <?php
                                            }
                                        }

                                        if ($file->expires == '1') {
                                            $exp_date = date(get_option('timeformat'), strtotime($file->expiry_date));
                                    ?>
										<span class="file_summary_chip file_summary_chip_subtle"><?php _e('Expires', 'preference_template'); ?>: <?php echo $exp_date; ?></span>
                                    <?php } ?>
								</div>
							</div>
							<div class="file_actions file_actions_selectable">
									<?php
										if ($file->expired == false) {
                                    ?>
											<div class="checkbox">
												<input type="checkbox" class="checkbox_file" name="file_id" value="<?php echo $file->id; ?>" id="checkbox_file_<?php echo $file->id; ?>">
												<label for="checkbox_file_<?php echo $file->id; ?>"></label>
											</div>
											<a href="<?php echo $file->download_link; ?>" target="_blank" class="button button_gray">
												<?php _e('Download','preference_template'); ?>
											</a>
									<?php
										}
										else {
									?>
											<div class="file_actions_notice"><?php _e('File expired','preference_template'); ?></div>
									<?php
										}
									?>
								</div>
						</div>
					<?php
						}
					?>
				</div>
        <?php
			}
        ?>
		
			</div>
	
			<?php render_footer_text(); ?>
	
        </div>

			<a href="<?php echo BASE_URI; ?>process.php" id="zip_download" target="_self" class="floating_zip_action disabled" aria-hidden="true">
				<span class="floating_zip_action_count">0</span>
				<span class="floating_zip_action_label"><?php _e('Download 0 Selected Files as Zip', 'preference_template'); ?></span>
			</a>

        <?php render_custom_assets('body_bottom'); ?>
	
	</body>
</html>