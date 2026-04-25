<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use RuntimeException;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request): ?User {
            $username = $request->input(Fortify::username());
            $password = $request->input('password');

            if (! is_string($username) || ! is_string($password)) {
                return null;
            }

            $user = User::query()->where(Fortify::username(), $username)->first();

            if (! $user || ! $this->passwordMatchesSupportedHash($password, (string) $user->password)) {
                return null;
            }

            if (Hash::needsRehash((string) $user->password)) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }

            return $user;
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }

    private function passwordMatchesSupportedHash(string $plainPassword, string $hashedPassword): bool
    {
        $algorithm = password_get_info($hashedPassword)['algoName'] ?? 'unknown';

        if (in_array($algorithm, ['bcrypt', 'argon2id'], true)) {
            return password_verify($plainPassword, $hashedPassword);
        }

        try {
            return Hash::check($plainPassword, $hashedPassword);
        } catch (RuntimeException) {
            return false;
        }
    }
}
