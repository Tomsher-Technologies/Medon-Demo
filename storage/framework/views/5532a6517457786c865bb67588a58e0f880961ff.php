<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Review Reminder</title>

        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        
       
        <style type="text/css">
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: auto; background-color: #fff; padding: 30px; border-radius: 8px; }
        h1 { font-size: 20px; }
        a { color: #007bff; text-decoration: none; }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
        }
        .product { margin-bottom: 15px; }
            div,
            p,
            a,
            li,
            td {
                -webkit-text-size-adjust: none;
            }

            body {
                width: 100%;
                height: 100%;
                background-color: #cecece;
                margin: 0;
                padding: 0;
                -webkit-font-smoothing: antialiased;
            }

            html {
                width: 100%;
            }

            img {
                border: none;
            }

            table td[class=show] {
                display: none !important;
            }

            @media  only screen and (max-width: 640px) {
                body {
                    width: auto !important;
                }

                table[class=full] {
                    width: 100% !important;
                }

                table[class=devicewidth] {
                    width: 100% !important;
                    padding-left: 20px !important;
                    padding-right: 20px !important;
                }

                table[class=inner] {
                    width: 100% !important;
                    text-align: center !important;
                    clear: both;
                }

                table[class=inner-centerd] {
                    width: 78% !important;
                    text-align: center !important;
                    clear: both;
                    float: none !important;
                    margin: 0 auto !important;
                }

                table td[class=hide],
                .hide {
                    display: none !important;
                }

                table td[class=show],
                .show {
                    display: block !important;
                }

                img[class=responsiveimg] {
                    width: 100% !important;
                    height: atuo !important;
                    display: block !important;
                }

                table[class=btnalign] {
                    float: left !important;
                }

                table[class=btnaligncenter] {
                    float: none !important;
                    margin: 0 auto !important;
                }

                table td[class=textalignleft] {
                    text-align: left !important;
                    padding: 0 !important;
                }

                table td[class=textaligcenter] {
                    text-align: center !important;
                }

                table td[class=heightsmalldevices] {
                    height: 45px !important;
                }

                table td[class=heightSDBottom] {
                    height: 28px !important;
                }

                table[class=adjustblock] {
                    width: 87% !important;
                }

                table[class=resizeblock] {
                    width: 92% !important;
                }

                table td[class=smallfont] {
                    font-size: 8px !important;
                }
            }

            @media  only screen and (max-width: 520px) {
                table td[class=heading] {
                    font-size: 24px !important;
                }

                table td[class=heading01] {
                    font-size: 18px !important;
                }

                table td[class=heading02] {
                    font-size: 27px !important;
                }

                table td[class=text01] {
                    font-size: 22px !important;
                }

                table[class="full mhide"],
                table tr[class=mhide] {
                    display: none !important;
                }
            }

            @media  only screen and (max-width: 480px) {
                table {
                    border-collapse: collapse;
                }

                table[id=colaps-inhiret01],
                table[id=colaps-inhiret02],
                table[id=colaps-inhiret03],
                table[id=colaps-inhiret04],
                table[id=colaps-inhiret05],
                table[id=colaps-inhiret06],
                table[id=colaps-inhiret07],
                table[id=colaps-inhiret08],
                table[id=colaps-inhiret09] {
                    border-collapse: inherit !important;
                }
            }

            @media  only screen and (max-width: 320px) {}
        </style>
    </head>

    <body style="background-color: #b7e3f978;">
        <!-- ----------------- Header Start Here ------------------------- -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
            <tr>
                <td class="heightsmalldevices" height="60">&nbsp;</td>
            </tr>
            <tr>
                <td align="center">
                    <table width="800" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
                        <tr>
                            <td>
                                <table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0"
                                    align="center" class="full"
                                    style="background-color: #ffffff; border-radius: 5px 5px 0 0">
                                    <tr>
                                        <td>
                                            <table align="center" border="0" cellspacing="0" cellpadding="0"
                                                class="inner" style="text-align: center">
                                                <tr>
                                                    <td width="28" height="52">&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td align="center" valign="middle">
                                                        <a href="<?php echo e(env('WEB_URL')); ?>">
                                                            <img src="<?php echo e(asset('admin_assets/assets/img/logo.png')); ?>"
                                                                height="100" alt="Medon">
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr></tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- ----------------- Header End Here ------------------------- -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
            <tr>
                <td align="center">
                    <table width="800" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
                        <tr>
                            <td>
                                <table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0"
                                    align="center" class="full"
                                    style="text-align: center; border-bottom: 1px solid #e5e5e5;padding:0 20px">
                                    
                                    

                                    <tr>
                                        <td height="21">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font: 18px Arial, Helvetica, sans-serif;color: #64a644;">
                                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="21">&nbsp;</td>
                                    </tr>
                                    <tr style="text-align: left;">
                                        <td style="font: 16px Arial, Helvetica, sans-serif;color: #404040;padding: 0px 30px 30px;">
                                            <h3>Hello <?php echo e($user->name); ?>,</h3>
                                        
                                            <p>We hope you're enjoying your recent order! We'd be grateful if you could leave a review for the product<?php echo e(count($links) > 1 ? 's' : ''); ?> you purchased:</p>

                                            <ul style="padding: 1%;">
                                                <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="product">
                                                        <a href="<?php echo e($link['url']); ?>" style=""><?php echo e($link['name']); ?></a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                    
                                            <p>Your feedback helps us and other customers a lot. Thank you for choosing us! We truly appreciate your support and feedback.</p>
                                    
                                            <p>Best regards,<br>
                                            <?php echo e(config('app.name')); ?> Team</p>
                                        </td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
            <tr>
                <td align="center">
                    <table width="800" border="0" cellspacing="0" cellpadding="0" align="center"
                        class="devicewidth">
                        <tr>
                            <td>
                                <table width="100%" bgcolor="#64a644" border="0" cellspacing="0" cellpadding="0"
                                    align="center" class="full" style="border-radius:0 0 5px 5px;">
                                    <tr>
                                        <td height="18">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table border="0" cellspacing="0" cellpadding="0" align="center"
                                                style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align:center;"
                                                class="inner">
                                                <tr>
                                                    <td width="21">&nbsp;</td>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0"
                                                            cellpadding="0" align="center">
                                                            <tr>
                                                                <td align="center"
                                                                    style="font:11px Helvetica,  Arial, sans-serif; color:#ffffff;">
                                                                    &copy; <?= date('Y') ?>, <?php echo e(env('APP_NAME')); ?> All Rights Reserved
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td height="18">&nbsp;</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width="21">&nbsp;</td>
                                                </tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="mhide">
                            <td height="100">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </body>

</html>
<?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/emails/review_reminder.blade.php ENDPATH**/ ?>