<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>رمز إعادة تعيين كلمة المرور</title>
</head>
<body style="margin:0; padding:0; background:#f4f1ea; font-family: Tahoma, Arial, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ea; padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:420px; background:#ffffff; border-radius:14px; overflow:hidden; border:1px solid #eae4d6;">
          <tr>
            <td style="background:#0d2b30; padding:24px 28px;">
              <span style="color:#ffffff; font-size:18px; font-weight:700;">نوتابوكس</span>
            </td>
          </tr>
          <tr>
            <td style="padding:28px;">
              <p style="margin:0 0 8px; font-size:15px; color:#1f2d2b;">مرحبًا {{ $user->name }}،</p>
              <p style="margin:0 0 20px; font-size:14px; color:#5c6764; line-height:1.8;">
                وصلنا طلب لإعادة تعيين كلمة المرور الخاصة بحسابك على منصة نوتابوكس. استخدم الرمز التالي لإتمام العملية:
              </p>
              <div style="text-align:center; margin:24px 0;">
                <span style="display:inline-block; background:#faf8f3; border:1px dashed #d3a256; border-radius:10px; padding:14px 28px; font-size:28px; font-weight:800; letter-spacing:6px; color:#0d2b30;">
                  {{ $code }}
                </span>
              </div>
              <p style="margin:0 0 6px; font-size:13px; color:#8a9490;">
                هذا الرمز صالح لمدة {{ $expiresInMinutes }} دقائق فقط.
              </p>
              <p style="margin:0; font-size:13px; color:#8a9490;">
                إذا لم تطلب إعادة تعيين كلمة المرور، تجاهل هذه الرسالة ولن يتغير شيء في حسابك.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
