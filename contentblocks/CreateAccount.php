<?php namespace LearnKit\LMS\ContentBlocks;

use Auth;
use Ramsey\Uuid\Uuid;
use RainLab\User\Models\User;
use Kloos\Saas\Classes\Tenant;
use LearnKit\LMS\Classes\Base\ContentBlockBase;

class CreateAccount extends ContentBlockBase
{
    public static $code = 'learnkit.lms::create_account';

    public static $label = 'Create account';

    public static $description = 'Create a new account or view existing.';

    public function saveResults()
    {
        if (Auth::getUser()) {
            return;
        }

        //
        $payload = $this->getFormResults();
        $user = $this->createAccount($payload);

        // For tenant
        $active = Tenant::instance()->active();
        if ($active) {
            $user->tenants()->add($active);
        }

        $user->is_guest = false;
        $user->is_activated = true;
        $user->save();

        Auth::login($user);

        // Remove password before storing the payload
        if (isset($payload['password'])) {
            unset($payload['password']);
            unset($payload['password_confirmation']);
        }

        //
        $this->newResult(null, null, $payload);
    }

    protected function getFormResults()
    {
        $credentials = [];

        if ($this->config['ask_name']) {
            $credentials['name'] = input('name');
        }

        $credentials['email'] = input('email');

        if (!$this->config['is_guest']) {
            $password = input('password');
        } else {
            $credentials['is_guest'] = true;
            $password = Uuid::uuid4();
        }

        $credentials['password'] = $password;
        $credentials['password_confirmation'] = $password;

        return $credentials;
    }

    protected function createAccount($credentials)
    {
        // Check if account already exists
        $user = User::findByEmail($credentials['email']);
        if ($user) {
            return $user;
        }

        return Auth::registerGuest($credentials);
    }
}