<?php
/*======================================================================*\
|| #################################################################### ||
|| #                This file is part of Cybercure                    # ||
|| #                          for  #RISK[Solutions]Maurice            # ||
|| # ---------------------------------------------------------------- # ||
|| #         Copyright © 2017 cybercure.ngrok.io. All Rights Reserved.# ||
|| #                                                                  # ||
|| # ----------     Cybercure IS AN OPENSOURCE SOFTWARE    ---------- # ||
|| # -------------------- https://cybercure.ngrok.io -------- ------- # ||
|| #################################################################### ||
\*======================================================================*/
?>

<script>
    (function( $ ) {
        "use strict";

        $(function() {

            var wWidth = $(window).width();
            var dWidth = wWidth * 0.3;
            var wHeight = $(window).height();
            var dHeight = wHeight * 0.2;

            $('[data-type="modal"]').click(function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var post = $(this).attr('data-post');
                if (url.indexOf('#') == 0) {
                    $(url).modal('open');
                } else {
                    var dialog_log_prepare = $('<div id="dialog" title="Detailed Log - Loading."><p style="text-align: center"> <strong>Loading... Please wait.</strong></p><img class="center" src="<?php echo cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/img/ajax-loader.gif' ?>"></div>').dialog({ modal: true, dialogClass: 'fixed-dialog', minWidth: dWidth, minHeight: dHeight });
                    $.post(ajaxurl, post, function(data) {
                        dialog_log_prepare.dialog('close');
                        var dialog_log = $('<div id="dialog" title="Detailed Log">' + data + '</div>').dialog({ modal: true, dialogClass: 'fixed-dialog', minWidth: dWidth, minHeight: dHeight });
                    }).success(function() {
                        }
                    );
                }
            });

        });

    }(jQuery));
</script>