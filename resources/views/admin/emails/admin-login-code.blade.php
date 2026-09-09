<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>رمز تسجيل الدخول</title>
</head>
<body style="margin:0; padding:0; background:#f4f1ea; font-family: Tahoma, Arial, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ea; padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:420px; background:#ffffff; border-radius:14px; overflow:hidden; border:1px solid #eae4d6;">
          <tr>
            <td style="background:#123a3f; padding:24px 28px;">
              <span style="color:#ffffff; font-size:18px; font-weight:700;">مكتبتي — لوحة التحكم</span>
            </td>
          </tr>
          <tr>
            <td style="padding:28px;">
              <p style="margin:0 0 8px; font-size:15px; color:#1f2d2b;">مرحبًا {{ $admin->name }}،</p>
              <p style="margin:0 0 20px; font-size:14px; color:#5c6764; line-height:1.8;">
                طلب أحد الحسابات تسجيل الدخول إلى لوحة تحكم مكتبتي. استخدم الرمز التالي لإتمام تسجيل الدخول:
              </p>
              <div style="text-align:center; margin:24px 0;">
                <span style="display:inline-block; background:#f4f1ea; border:1px dashed #c9a15a; border-radius:10px; padding:14px 28px; font-size:28px; font-weight:800; letter-spacing:6px; color:#123a3f;">
                  {{ $code }}
                </span>
              </div>
              <p style="margin:0 0 6px; font-size:13px; color:#8a9490;">
                هذا الرمز صالح لمدة {{ $expiresInMinutes }} دقائق فقط.
              </p>
              <p style="margin:0; font-size:13px; color:#8a9490;">
                إذا لم تحاول تسجيل الدخول، تجاهل هذه الرسالة أو راجع أمان حسابك.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
