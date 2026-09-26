<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationCodeMail;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthPageController extends Controller
{
    private const CODE_TTL_MINUTES = 10;

    private const MAX_CODE_ATTEMPTS = 5;

    private const RESEND_COOLDOWN_SECONDS = 30;

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'g-recaptcha-response' => [new Recaptcha('login')],
        ]);

        $credentials = collect($validated)->only(['email', 'password'])->all();

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'بيانات الدخول غير صحيحة']);
        }

        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'تم تسجيل الدخول بنجاح');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
            'g-recaptcha-response' => [new Recaptcha('register')],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Skip the email verification step in local dev — there's no point
        // waiting on a real inbox just to test registration on your own machine.
        if (app()->environment('local')) {
            $user->forceFill(['email_verified_at' => now()])->save();

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('home')->with('status', 'تم إنشاء حسابك بنجاح');
        }

        $this->issueVerificationCode($request, $user);

        return redirect()->route('register.verify');
    }

    public function showVerifyEmail(Request $request): RedirectResponse|View
    {
        $pending = $request->session()->get('email_verification');

        if (! $pending || now()->timestamp > $pending['expires_at']) {
            $request->session()->forget('email_verification');

            return redirect()->route('register')->withErrors([
                'email' => 'انتهت صلاحية رمز التفعيل، يرجى إنشاء الحساب مرة أخرى.',
            ]);
        }

        return view('auth.verify-email', [
            'maskedEmail' => $this->maskEmail($pending['email']),
        ]);
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $pending = $request->session()->get('email_verification');

        if (! $pending || now()->timestamp > $pending['expires_at']) {
            $request->session()->forget('email_verification');

            return redirect()->route('register')->withErrors([
                'email' => 'انتهت صلاحية رمز التفعيل، يرجى إنشاء الحساب مرة أخرى.',
            ]);
        }

        if (! Hash::check((string) $request->string('code'), $pending['code'])) {
            $attempts = ($pending['attempts'] ?? 0) + 1;

            if ($attempts >= self::MAX_CODE_ATTEMPTS) {
                $request->session()->forget('email_verification');

                return redirect()->route('register')->withErrors([
                    'email' => 'تم تجاوز عدد المحاولات المسموح به، يرجى إنشاء الحساب مرة أخرى.',
                ]);
            }

            $pending['attempts'] = $attempts;
            $request->session()->put('email_verification', $pending);

            return back()->withErrors([
                'code' => 'الرمز الذي أدخلته غير صحيح.',
            ]);
        }

        $user = User::findOrFail($pending['user_id']);
        $user->forceFill(['email_verified_at' => now()])->save();

        $request->session()->forget('email_verification');
        $request->session()->regenerate();

        Auth::login($user);

        return redirect()->route('home')->with('status', 'تم تفعيل حسابك بنجاح');
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('email_verification');

        if (! $pending) {
            return redirect()->route('register');
        }

        if (now()->timestamp < ($pending['last_sent_at'] + self::RESEND_COOLDOWN_SECONDS)) {
            return back()->withErrors([
                'code' => 'يرجى الانتظار قليلًا قبل طلب رمز جديد.',
            ]);
        }

        $user = User::findOrFail($pending['user_id']);

        $this->issueVerificationCode($request, $user);

        return redirect()->route('register.verify')->with('status', 'تم إرسال رمز جديد إلى بريدك الإلكتروني.');
    }

    private function issueVerificationCode(Request $request, User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $request->session()->put('email_verification', [
            'user_id' => $user->id,
            'email' => $user->email,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES)->timestamp,
            'last_sent_at' => now()->timestamp,
            'attempts' => 0,
        ]);

        Mail::to($user->email)->send(new EmailVerificationCodeMail($user, $code, self::CODE_TTL_MINUTES));
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function submitForgotPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'g-recaptcha-response' => [new Recaptcha('forgot-password')],
        ]);

        // Never reveal whether the email is registered — always issue a
        // pending code (with a null user when no match is found) and land
        // on the same code-entry page either way, so the response gives an
        // attacker no way to distinguish "wrong code" from "no such account".
        $user = User::where('email', $validated['email'])->first();

        $this->issueResetCode($request, $user, $validated['email']);

        return redirect()->route('reset-password');
    }

    public function showResetPassword(Request $request): RedirectResponse|View
    {
        $pending = $request->session()->get('password_reset');

        if (! $pending || now()->timestamp > $pending['expires_at']) {
            $request->session()->forget('password_reset');

            return redirect()->route('forgot-password')->withErrors([
                'email' => 'انتهت صلاحية الرمز، يرجى طلب رمز جديد.',
            ]);
        }

        return view('auth.reset-password', [
            'maskedEmail' => $this->maskEmail($pending['email']),
        ]);
    }

    public function submitResetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $pending = $request->session()->get('password_reset');

        if (! $pending || now()->timestamp > $pending['expires_at']) {
            $request->session()->forget('password_reset');

            return redirect()->route('forgot-password')->withErrors([
                'email' => 'انتهت صلاحية الرمز، يرجى طلب رمز جديد.',
            ]);
        }

        // A null user_id (no matching account when the code was issued) is
        // treated exactly like a wrong code — same error, same attempt
        // counting — so there's no observable difference from a real typo.
        if (! $pending['user_id'] || ! Hash::check($validated['code'], $pending['code'])) {
            $attempts = ($pending['attempts'] ?? 0) + 1;

            if ($attempts >= self::MAX_CODE_ATTEMPTS) {
                $request->session()->forget('password_reset');

                return redirect()->route('forgot-password')->withErrors([
                    'email' => 'تم تجاوز عدد المحاولات المسموح به، يرجى طلب رمز جديد.',
                ]);
            }

            $pending['attempts'] = $attempts;
            $request->session()->put('password_reset', $pending);

            return back()->withErrors([
                'code' => 'الرمز الذي أدخلته غير صحيح.',
            ]);
        }

        $user = User::findOrFail($pending['user_id']);
        $user->forceFill(['password' => Hash::make($validated['password'])])->save();

        $request->session()->forget('password_reset');
        $request->session()->regenerate();

        Auth::login($user);

        return redirect()->route('home')->with('status', 'تم تحديث كلمة المرور بنجاح');
    }

    public function resendResetCode(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('password_reset');

        if (! $pending) {
            return redirect()->route('forgot-password');
        }

        if (now()->timestamp < ($pending['last_sent_at'] + self::RESEND_COOLDOWN_SECONDS)) {
            return back()->withErrors([
                'code' => 'يرجى الانتظار قليلًا قبل طلب رمز جديد.',
            ]);
        }

        $user = $pending['user_id'] ? User::find($pending['user_id']) : null;

        $this->issueResetCode($request, $user, $pending['email']);

        return redirect()->route('reset-password')->with('status', 'تم إرسال رمز جديد إلى بريدك الإلكتروني.');
    }

    private function issueResetCode(Request $request, ?User $user, string $email): void
    {
        $code = (string) random_int(100000, 999999);

        $request->session()->put('password_reset', [
            'user_id' => $user?->id,
            'email' => $email,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES)->timestamp,
            'last_sent_at' => now()->timestamp,
            'attempts' => 0,
        ]);

        if ($user) {
            Mail::to($user->email)->send(new PasswordResetCodeMail($user, $code, self::CODE_TTL_MINUTES));
        }
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email);

        $visible = mb_substr($name, 0, 1);

        return $visible.str_repeat('•', max(mb_strlen($name) - 1, 3)).'@'.$domain;
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
