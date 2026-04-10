$(document).ready(function() {
    var $container = $('.photo_list');
    var zipDownloadInProgress = false;
    $container.imagesLoaded(function() {
        $container.addClass('is-ready');
    });

    function updateZipButtonState() {
        var amountChecked = $('.checkbox_file:checked').length;
        var $zipButton = $('#zip_download');

        if (zipDownloadInProgress) {
            return;
        }

        $zipButton.find('.floating_zip_action_count').text(amountChecked);
        $zipButton.find('.floating_zip_action_label').text(
            'Download ' + amountChecked + ' Selected Files as Zip'
        );

        if (amountChecked > 0) {
            $zipButton.removeClass('disabled').addClass('is-visible').attr('aria-hidden', 'false');
        } else {
            $zipButton.addClass('disabled').removeClass('is-visible').attr('aria-hidden', 'true');
        }
    }

    function setZipButtonLoading(isLoading) {
        var $zipButton = $('#zip_download');
        zipDownloadInProgress = isLoading;

        if (isLoading) {
            $zipButton
                .addClass('is-loading is-visible disabled')
                .attr('aria-hidden', 'false')
                .attr('aria-busy', 'true');
            $zipButton.find('.floating_zip_action_label').text('Preparing Selected Files as Zip');
        } else {
            $zipButton.removeClass('is-loading').attr('aria-busy', 'false');
            updateZipButtonState();
        }
    }

    function updateSelectAllState() {
        var $selectAll = $('#select_all_files');
        var totalFiles = $('.checkbox_file').length;
        var checkedFiles = $('.checkbox_file:checked').length;

        if ($selectAll.length === 0) {
            return;
        }

        $selectAll.prop('checked', totalFiles > 0 && checkedFiles === totalFiles);
        $selectAll.prop('indeterminate', checkedFiles > 0 && checkedFiles < totalFiles);
    }

    function updateZipButtonScrollState() {
        $('#zip_download').toggleClass('is-scrolled', $(window).scrollTop() > 24);
    }

    function updateCardSelectionState($checkbox) {
        $checkbox.closest('.photo').toggleClass('selected', $checkbox.prop('checked'));
    }

    $('.button').click(function() {
        $(this).blur();
    });

    function closeMenuOverlays() {
        $('.menu_dropdown_panel').removeClass('visible').stop().slideUp(150);
        $('.menu_dropdown_trigger').removeClass('is-open').find('button, a').attr('aria-expanded', 'false');
        $('.topbar_menu').removeClass('is-open');
        $('#topbar_toggle').attr('aria-expanded', 'false');
        $('.content_cover').stop().fadeOut(200);
    }

    function syncMenuOverlayState() {
        var mobileMenuOpen = $('.topbar_menu').hasClass('is-open') && $(window).width() <= 1024;

        if (mobileMenuOpen) {
            $('.content_cover').stop().fadeIn(200);
        } else {
            $('.content_cover').stop().fadeOut(200);
        }
    }

    $('.menu_dropdown_trigger > a, .menu_dropdown_trigger > button').on('click', function(e) {
        e.preventDefault();

        var $trigger = $(this).closest('.menu_dropdown_trigger');
        var $panel = $trigger.find('.menu_dropdown_panel').first();
        var isOpen = $panel.hasClass('visible');

        $('.menu_dropdown_panel').not($panel).removeClass('visible').stop().slideUp(150);
        $('.menu_dropdown_trigger').not($trigger).removeClass('is-open').find('button, a').attr('aria-expanded', 'false');

        if (isOpen) {
            $panel.removeClass('visible').stop().slideUp(150);
            $trigger.removeClass('is-open');
            $(this).attr('aria-expanded', 'false');
        } else {
            $panel.addClass('visible').stop().slideDown(150);
            $trigger.addClass('is-open');
            $(this).attr('aria-expanded', 'true');
        }

        syncMenuOverlayState();
    });

    $('#topbar_toggle').on('click', function() {
        var $menu = $('.topbar_menu');
        var isOpen = $menu.hasClass('is-open');

        $menu.toggleClass('is-open', !isOpen);
        $(this).attr('aria-expanded', isOpen ? 'false' : 'true');
        syncMenuOverlayState();
    });

    $('.content_cover').click(function() {
        closeMenuOverlays();
    });

    $(document).on('click', function(e) {
        if ($(e.target).closest('.menu_dropdown_trigger, #topbar_toggle, .topbar_menu').length === 0) {
            closeMenuOverlays();
        }
    });

    $(window).on('resize', function() {
        if ($(window).width() > 1024) {
            $('.topbar_menu').removeClass('is-open');
            $('#topbar_toggle').attr('aria-expanded', 'false');
            syncMenuOverlayState();
        }
    });

    $('a.disabled').on('click', function(e) {
        e.preventDefault();
    });

    $container.on('click', '.photo.selectable', function(e) {
        if ($(e.target).closest('a, button, input, label, .checkbox').length) {
            return;
        }

        var $checkbox = $(this).find('.checkbox_file').first();
        if ($checkbox.length === 0 || $checkbox.is(':disabled')) {
            return;
        }

        $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
    });

    $('.checkbox_file').on('change', function() {
        updateCardSelectionState($(this));
        updateZipButtonState();
        updateSelectAllState();
    });

    $('#select_all_files').on('change', function() {
        var shouldCheck = $(this).prop('checked');

        $('.checkbox_file').each(function() {
            $(this).prop('checked', shouldCheck).trigger('change');
        });
    });

    $('.checkbox_file').each(function() {
        updateCardSelectionState($(this));
    });

    updateZipButtonState();
    updateSelectAllState();
    updateZipButtonScrollState();

    $(window).on('scroll', updateZipButtonScrollState);

    $('#zip_download').on('click', function(e) {
        e.preventDefault();

        if ($(this).hasClass('disabled') || zipDownloadInProgress) {
            return;
        }

        var ids = [];
        $('.checkbox_file:checked').each(function () {
            ids.push($(this).val());
        });

        setZipButtonLoading(true);
        Cookies.set('download_started', 0, { expires: 100 });
        setTimeout(check_download_cookie, 1000);

        var url = base_url+'process.php?do=download_zip&files='+ids.toString();
        $('body').append("<iframe id='modal_zip'></iframe>");
        $('#modal_zip').attr('src', url);
    });

    var downloadTimeout;
    var check_download_cookie = function() {
        if (Cookies.get("download_started") == 1) {
            Cookies.set("download_started", "false", { expires: 100 });
            setZipButtonLoading(false);
            $('#modal_zip').remove();
        } else {
            downloadTimeout = setTimeout(check_download_cookie, 1000);
        }
    };
});
