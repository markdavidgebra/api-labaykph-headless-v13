<?php

namespace App\Support;

class MailContent
{
    public static function button(string $url, string $label): string
    {
        $safeUrl = e($url);
        $safeLabel = e($label);

        return '
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 28px auto;">
                <tr>
                    <td style="border-radius: 10px; background-color: #9e7102;">
                        <a href="'.$safeUrl.'" style="display: inline-block; padding: 14px 34px; font-family: Arial, Helvetica, sans-serif; font-size: 15px; font-weight: 700; color: #ffffff !important; text-decoration: none; border-radius: 10px; letter-spacing: 0.02em;">
                            '.$safeLabel.'
                        </a>
                    </td>
                </tr>
            </table>
        ';
    }

    public static function fallbackLink(string $url): string
    {
        $safeUrl = e($url);

        return '
            <p style="margin: 0 0 8px 0; color: #7a7164; font-size: 13px;">If the button doesn\'t work, copy and paste this link into your browser:</p>
            <p style="margin: 0; word-break: break-all;"><a href="'.$safeUrl.'" style="color: #9e7102; text-decoration: underline;">'.$safeUrl.'</a></p>
        ';
    }

    public static function verification(string $name, string $url): string
    {
        return '
            <p style="margin: 0 0 8px 0; font-size: 13px; letter-spacing: 0.12em; text-transform: uppercase; color: #9e7102; font-weight: 700;">Email verification</p>
            <p style="margin: 0 0 18px 0; font-size: 22px; line-height: 1.35; color: #2a241c; font-family: Georgia, \'Times New Roman\', Times, serif;">
                Hello '.e($name).',
            </p>
            <p style="margin: 0 0 12px 0; color: #4a4338;">
                Thank you for joining <strong>'.e(config('app.name')).'</strong>. Please confirm your email address to activate your account and start exploring our destinations and packages.
            </p>
            <p style="margin: 0 0 8px 0; color: #4a4338;">
                Click the button below to verify your email:
            </p>
            '.self::button($url, 'Verify my email').'
            '.self::fallbackLink($url).'
            <p style="margin: 28px 0 0 0; color: #4a4338;">
                We\'re glad you\'re here — travel with confidence.
            </p>
        ';
    }

    public static function passwordReset(string $name, string $url): string
    {
        $greeting = $name !== '' ? 'Hello '.e($name).',' : 'Hello,';

        return '
            <p style="margin: 0 0 8px 0; font-size: 13px; letter-spacing: 0.12em; text-transform: uppercase; color: #9e7102; font-weight: 700;">Password reset</p>
            <p style="margin: 0 0 18px 0; font-size: 22px; line-height: 1.35; color: #2a241c; font-family: Georgia, \'Times New Roman\', Times, serif;">
                '.$greeting.'
            </p>
            <p style="margin: 0 0 12px 0; color: #4a4338;">
                We received a request to reset the password for your <strong>'.e(config('app.name')).'</strong> account.
            </p>
            <p style="margin: 0 0 8px 0; color: #4a4338;">
                Click the button below to choose a new password:
            </p>
            '.self::button($url, 'Reset password').'
            '.self::fallbackLink($url).'
            <p style="margin: 28px 0 0 0; color: #7a7164; font-size: 13px;">
                If you did not request a password reset, you can ignore this email. Your password will stay the same.
            </p>
        ';
    }

    public static function paymentApproved(
        string $name,
        string $packageName,
        string $referenceNo,
        float $amount,
        string $bookingUrl
    ): string {
        $greeting = $name !== '' ? 'Hello '.e($name).',' : 'Hello,';
        $amountLabel = '₱'.number_format($amount, 0, '.', ',');

        return '
            <p style="margin: 0 0 8px 0; font-size: 13px; letter-spacing: 0.12em; text-transform: uppercase; color: #9e7102; font-weight: 700;">Payment approved</p>
            <p style="margin: 0 0 18px 0; font-size: 22px; line-height: 1.35; color: #2a241c; font-family: Georgia, \'Times New Roman\', Times, serif;">
                '.$greeting.'
            </p>
            <p style="margin: 0 0 12px 0; color: #4a4338;">
                Great news — your payment has been <strong>approved</strong> by our team.
            </p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 18px 0; background-color: #faf7f0; border: 1px solid #eee4d2; border-radius: 10px;">
                <tr>
                    <td style="padding: 16px 18px; font-family: Arial, Helvetica, sans-serif; color: #4a4338; font-size: 14px; line-height: 1.65;">
                        <div><strong>Package:</strong> '.e($packageName).'</div>
                        <div><strong>Reference:</strong> '.e($referenceNo).'</div>
                        <div><strong>Amount:</strong> '.e($amountLabel).'</div>
                        <div><strong>Status:</strong> Completed</div>
                    </td>
                </tr>
            </table>
            <p style="margin: 0 0 8px 0; color: #4a4338;">
                You can view your booking details anytime from your account:
            </p>
            '.self::button($bookingUrl, 'View my booking').'
            '.self::fallbackLink($bookingUrl).'
            <p style="margin: 28px 0 0 0; color: #4a4338;">
                Thank you for choosing <strong>'.e(config('app.name')).'</strong>. We look forward to your journey.
            </p>
        ';
    }
}
