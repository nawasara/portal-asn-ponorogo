<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Kode OTP Reset MFA</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial,Helvetica,sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px; background-color:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">

                    {{-- Header / brand --}}
                    <tr>
                        <td style="padding:20px 28px; border-bottom:1px solid #f0f0f0;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:middle; padding-right:12px;">
                                        <div style="width:40px; height:40px; background-color:#bd4137; border-radius:8px; color:#ffffff; font-weight:bold; font-size:18px; line-height:40px; text-align:center;">SSO</div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <div style="font-size:16px; font-weight:bold; color:#1f2937;">Single Sign-On ASN</div>
                                        <div style="font-size:13px; color:#6b7280;">Kabupaten Ponorogo</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px; font-size:15px; line-height:1.6; color:#374151;">
                            <p style="margin:0 0 16px;">Anda meminta <b>Reset MFA</b> pada akun SSO ASN Ponorogo. Gunakan kode OTP berikut untuk melanjutkan:</p>

                            <div style="margin:24px 0; text-align:center;">
                                <span style="display:inline-block; font-size:34px; font-weight:bold; letter-spacing:10px; color:#bd4137; background-color:#fdf1f1; padding:14px 24px; border-radius:10px;">{{ $otp }}</span>
                            </div>

                            <p style="margin:0 0 8px;">Kode ini berlaku selama <b>{{ $ttlMinutes }} menit</b>.</p>
                            <p style="margin:0; font-size:13px; color:#9ca3af;">Jika Anda tidak meminta reset MFA, abaikan saja email ini dan tidak ada yang berubah.</p>

                            @if (!empty($ref))
                                <p style="margin:16px 0 0; font-size:11px; color:#cbd5e1;">Ref: {{ $ref }}</p>
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="height:4px; background-color:#bd4137; line-height:4px; font-size:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:18px 28px; background-color:#fafafa; font-size:12px; line-height:1.5; color:#9ca3af; text-align:center;">
                            Email ini dikirim otomatis oleh sistem SSO ASN Kabupaten Ponorogo.<br />
                            Mohon tidak membalas email ini.<br /><br />
                            &copy; {{ date('Y') }} Pemerintah Kabupaten Ponorogo &mdash; Dinas Komunikasi, Informatika dan Statistik
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
