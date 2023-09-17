<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Google2FA;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function socialSignup(Request $r, $provider)
    {
        $validator = Validator::make($r->all(), [
            'code' => 'nullable|string',
            'hash' => 'nullable|string',
            'otp' => 'nullable|numeric',
            'token' => 'nullable|string',
            'secret' => 'nullable|string',

        ]);
        if ($validator->fails()) {
            return [
                'message' => 'Incorrect Data Posted',
                'status' => 445,
            ];
        }

        $hash = $r->hash ?? null;
        $hashuser = Cache::get($hash);
        if ($hashuser) {
            return $this->socialSignupNext($r, $hashuser);
        }
        try {
            // Socialite will pick response data automatic
            $user = Socialite::driver($provider)->stateless()->user();
            $token = $user->token ?? null;
            $refreshToken = $user->refreshToken ?? null;
            $expiresIn = $user->expiresIn ?? null;
            $tokenSecret = $user->tokenSecret ?? null;
            $id = $user->id ?? $user->getId();
            $nickname = $user->nickname ?? $user->getNickname();
            $firstName = $user->name->firstName ?? null;
            $lastName = $user->name->lastName ?? null;
            $name = $user->name ?? $firstName . ' ' . $lastName ?? null;
            $email = $user->getEmail();
            $profileImage = $this->getHighQualityImage($user->getAvatar());

            $data =  [
                'name' => $name,
                'nickname' => $nickname,
                'profileImage' => $profileImage,
                'username' => '',
                'email' => $email,
                'provider' => $provider,
                'provider_id' => $id,
                'token' => $token,
                'tokenSecret' => $tokenSecret,
                'refreshToken' => $refreshToken,
                'expiresIn' => $expiresIn,

            ];
            // this is optional can be skip you can return your user data from here
            if ($email) {

                return $this->socialSignupNext($r, $data);
            }
        } catch (\Throwable $th) {
            logger($th);
        }

        return [
            'message' => 'Unknow Error',
            'status' => 445,
        ];
    }


    public function socialSignupNext($request, $userdata)
    {
        $email = $userdata['email'];
        $provider = $userdata['provider'];
        $provider_id = $userdata['provider_id'];
        $name = $userdata['name'];
        $profilePicture = $userdata['profileImage'];
        $usr = User::where('email', $email)->get();

        $user =  $usr->where('provider', $provider)
            ->where('provider_id', $provider_id)
            ->first();

        if ($user) {
            return $this->socialLogin($request, $user);
        }
        $user = $usr->first();
        if ($user) {
            $user->update([

                'provider' => $provider,
                'provider_id' => $provider_id,

            ]);
            return $this->socialLogin($request, $user);
        }
        $u =  User::create([
            'name' => $name,
            'email' => $email,
            'provider' => $provider,
            'provider_id' => $provider_id,
            'profile_picture' => $profilePicture,

        ]);
        // this is optional can be skip you can return your user data from here
        return $this->socialLogin($request, $u);
    }



    public function socialLogin(Request $request, User $user)
    {
        $otp = $request->otp;
        $hashid = Str::random(12);

        if ($user->google2fa_secret && !$this->verifyOtp($otp)) {
            Cache::put($hashid, $user, now()->addMinutes(15));
            return [
                'message' => 'Unauthorized',
                'status' => 444,
                'hash' => $hashid,
            ];
        }

        if ($this->verifyOtp($otp)) {
            $google2fa_secret = $user->google2fa_secret;
            $google2fa_ts = $user->google2fa_ts;
            $g = Google2FA::verifyKeyNewer($google2fa_secret, $otp, $google2fa_ts);

            if (!$g) {
                return [
                    'message' => '2FA Expired Or Incorrect Code',
                    'status' => 445,
                ];
            } else {
                $user->update([
                    'google2fa_ts' => $g,
                ]);

                // Optional: In case you are using Passport OAuth
                Auth::login($user);
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $token->save();

                return [
                    'u' => [
                        'data' => $tokenResult->accessToken,
                        'user' => $user,
                    ],
                ];
            }
        }

        // Create token if OTP verification is not required
        Auth::login($user);
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return [
            'u' => [
                'data' => $tokenResult->accessToken,
                'user' => $user,
            ],
        ];
    }

    private function getHighQualityImage($avatar)
    {
        return str_replace("s96", "s596", $avatar);
    }

    private function verifyOtp($otp)
    {
        // Implement your OTP verification logic here
        // You might want to use a package or custom logic to verify OTPs
        // Return true if OTP is valid, false otherwise
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
