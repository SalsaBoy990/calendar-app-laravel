<?php

namespace Tests\Unit\App\TwoFactor;


use App\Mail\SendCodeMail;
use App\Models\User;
use App\Models\UserCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class Check2FaTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        // get user
        $this->user = User::findOrFail(1);
    }


    /**
     * Creates a 2FA code for the user (modified for the test to make it work)
     *
     * $this->user->id instead of auth()->id()
     * returns the $details array
     * code sending in mail tested separately (removed here)
     * @return array
     */
    public function generateCode()
    {
        $code = rand(100000, 999999);

        UserCode::updateOrCreate(
            ['user_id' => $this->user->id],
            ['code' => $code]
        );

        // details
        return [
            'title' => __('Login code'),
            'code' => $code
        ];
    }


    /**
     * User exists
     */
    public function test_user_exist(): void
    {
        $this->assertTrue((bool) $this->user);
    }


    /**
     * @return void
     */
    public function test_user_has_2fa_enabled_with_session_expired(): void
    {
        $this->assertTrue($this->user->enable_2fa && !Session::has('user_2fa'));
        // need to send a new code automatically when 'user_2fa' session variable expires
    }


    /**
     * @return void
     */
    public function test_generate_2fa_code(): void
    {
        // need to duplicate the method (auth()->id() will not work here)
        $details = $this->generateCode();

        $generatedCode = UserCode::where('user_id', $this->user->id)
            ->where('code', $details['code'])
            ->where('updated_at', '>=', now()->subMinutes(2))
            ->first();

        $this->assertNotNull($generatedCode, '2FA code generated for the user.');

        Session::put('user_2fa', $this->user->id);

        $this->assertEquals(
            $this->user->id,
            Session::has('user_2fa'),
            'Session has the user_2fa variable with the user id: '.$this->user->id
        );
    }


    /**
     * @return void
     */
    public function test_2fa_code_sent_to_the_user_s_email_address(): void
    {
        $details = [
            'title' => __('Login code'),
            'code' => 123456
        ];

        $mailable = new SendCodeMail($details);
        $mailable->assertSeeInHtml($details['title']);
        $mailable->assertSeeInHtml($details['code']);
        $mailable->assertHasSubject($details['title']);

        // Send the code in email
        // Laravel handles mail sending (no need to assert it here)
        $mail = Mail::to($this->user->email)->send($mailable);
        $this->assertTrue($mail instanceof \Illuminate\Mail\SentMessage);
    }


    /**
     * @return void
     */
    public function test_login_user_2fa_enabled(): void
    {
        $this->assertTrue(Route::has('2fa.index'), '2FA verification route should exists');
        $this->assertTrue(class_exists('\App\Http\Controllers\Auth\UserCodeController'), 'UserCodeController should exists');

        $response = $this->actingAs($this->user)->get('/admin/dashboard');

        $response->assertHeader('content-type', 'text/html; charset=UTF-8');
        $response->assertStatus(302);

        // Code should be re-generated and send to the user when redirected to the login page (and 2fa is on)
        $generatedCode = UserCode::where('user_id', $this->user->id)
            ->where('updated_at', '>=', now()->subMinutes(2))
            ->first();

        $this->assertNotNull($generatedCode, '2FA code generated for the user.');
    }

}
