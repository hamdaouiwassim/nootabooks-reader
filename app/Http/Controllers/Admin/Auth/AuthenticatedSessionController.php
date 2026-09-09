<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminLoginCodeMail;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    private const CODE_TTL_MINUTES = 10;

    private const MAX_CODE_ATTEMPTS = 5;

    private const RESEND_COOLDOWN_SECONDS = 30;

    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => 'بيانات الدخول المدخلة غير صحيحة.',
            ]);
        }

        $this->issueCode($request, $admin, (bool) $request->boolean('remember'));

        return redirect()->route('admin.login.verify');
    }

    public function showVerify(Request $request): RedirectResponse|View
    {
        $pending = $request->session()->get('admin_login');

        if (! $pending || now()->timestamp > $pending['expires_at']) {
            $request->session()->forget('admin_login');

            return redirect()->route('admin.login')->withErrors([
                'email' => 'انتهت صلاحية الجلسة، يرجى تسجيل الدخول مرة أخرى.',
            ]);
        }

        return view('admin.auth.verify-code', [
            'maskedEmail' => $this->maskEmail(config('services.admin_otp.recipient')),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $pending = $request->session()->get('admin_login');

        if (! $pending || now()->timestamp > $pending['expires_at']) {
            $request->session()->forget('admin_login');

            return redirect()->route('admin.login')->withErrors([
                'email' => 'انتهت صلاحية الجلسة، يرجى تسجيل الدخول مرة أخرى.',
            ]);
        }

        if (! Hash::check((string) $request->string('code'), $pending['code'])) {
            $attempts = ($pending['attempts'] ?? 0) + 1;

            if ($attempts >= self::MAX_CODE_ATTEMPTS) {
                $request->session()->forget('admin_login');

                return redirect()->route('admin.login')->withErrors([
                    'email' => 'تم تجاوز عدد المحاولات المسموح به، يرجى تسجيل الدخول مرة أخرى.',
                ]);
            }

            $pending['attempts'] = $attempts;
            $request->session()->put('admin_login', $pending);

            return back()->withErrors([
                'code' => 'الرمز الذي أدخلته غير صحيح.',
            ]);
        }

        $adminId = $pending['admin_id'];
        $remember = $pending['remember'];

        $request->session()->forget('admin_login');
        $request->session()->regenerate();

        Auth::guard('admin')->loginUsingId($adminId, $remember);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('admin_login');

        if (! $pending) {
            return redirect()->route('admin.login');
        }

        if (now()->timestamp < ($pending['last_sent_at'] + self::RESEND_COOLDOWN_SECONDS)) {
            return back()->withErrors([
                'code' => 'يرجى الانتظار قليلًا قبل طلب رمز جديد.',
            ]);
        }

        $admin = Admin::findOrFail($pending['admin_id']);

        $this->issueCode($request, $admin, $pending['remember']);

        return redirect()->route('admin.login.verify')->with('status', 'تم إرسال رمز جديد إلى بريدك الإلكتروني.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function issueCode(Request $request, Admin $admin, bool $remember): void
    {
        $code = (string) random_int(100000, 999999);

        $request->session()->put('admin_login', [
            'admin_id' => $admin->id,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES)->timestamp,
            'last_sent_at' => now()->timestamp,
            'attempts' => 0,
            'remember' => $remember,
        ]);

        Mail::to(config('services.admin_otp.recipient'))
            ->send(new AdminLoginCodeMail($admin, $code, self::CODE_TTL_MINUTES));
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email);

        $visible = mb_substr($name, 0, 1);

        return $visible.str_repeat('•', max(mb_strlen($name) - 1, 3)).'@'.$domain;
    }
}
