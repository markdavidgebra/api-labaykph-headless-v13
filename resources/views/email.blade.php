<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? config('app.name') }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f3efe6; font-family: Georgia, 'Times New Roman', Times, serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3efe6;">
        <tr>
            <td style="padding: 36px 16px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 580px; margin: 0 auto;">
                    <!-- Preheader spacer -->
                    <tr>
                        <td style="padding: 0 0 18px 0; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #8a7d66; letter-spacing: 0.08em; text-transform: uppercase;">
                            {{ config('app.name') }} · Muslim-friendly travel
                        </td>
                    </tr>

                    <!-- Card -->
                    <tr>
                        <td style="background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e8dfcc; box-shadow: 0 10px 30px rgba(40, 28, 8, 0.08);">
                            <!-- Header -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="background-color: #9e7102; background-image: linear-gradient(135deg, #9e7102 0%, #c49a2a 55%, #efcf64 100%); padding: 32px 28px 28px 28px; text-align: center;">
                                        <p style="margin: 0 0 8px 0; font-family: Arial, Helvetica, sans-serif; font-size: 11px; letter-spacing: 0.22em; text-transform: uppercase; color: rgba(255,255,255,0.88);">
                                            Welcome aboard
                                        </p>
                                        <h1 style="margin: 0; font-family: Georgia, 'Times New Roman', Times, serif; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: 0.02em; line-height: 1.2;">
                                            {{ config('app.name') }}
                                        </h1>
                                        <p style="margin: 10px 0 0 0; font-family: Arial, Helvetica, sans-serif; color: rgba(255,255,255,0.92); font-size: 14px; font-weight: 400;">
                                            Travel with confidence
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 4px; background-color: #efcf64; font-size: 0; line-height: 0;">&nbsp;</td>
                                </tr>
                            </table>

                            <!-- Body -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 36px 32px 28px 32px; font-family: Arial, Helvetica, sans-serif; color: #2f2a22; font-size: 15px; line-height: 1.7;">
                                        {!! $body !!}
                                    </td>
                                </tr>
                            </table>

                            <!-- Footer -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="background-color: #faf7f0; padding: 22px 28px 26px 28px; border-top: 1px solid #eee4d2; text-align: center;">
                                        <p style="margin: 0 0 6px 0; font-family: Arial, Helvetica, sans-serif; color: #9e7102; font-size: 12px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;">
                                            {{ config('app.name') }}
                                        </p>
                                        <p style="margin: 0; font-family: Arial, Helvetica, sans-serif; color: #7a7164; font-size: 12px; line-height: 1.55;">
                                            This email was sent by {{ config('app.name') }}. If you did not request this, you can safely ignore it.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 18px 8px 0 8px; text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #9a8f7c; line-height: 1.5;">
                            Please do not reply to this email. For help, visit our website and use Contact.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
