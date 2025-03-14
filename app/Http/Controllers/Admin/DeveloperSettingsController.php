<?php

namespace App\Http\Controllers\Admin;

use DateTimeZone;
use Inertia\Inertia;
use App\Traits\Dotenv;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeveloperSettingsController extends Controller
{
    use Dotenv;

    public function __construct()
    {
        $this->middleware('permission:developer-settings');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $segments = request()->segments();

        $buttons = [
            [
                'name' => __('Back to dashboard'),
                'url' => url('/admin/dashboard')
            ],
        ];

        if ($id == 'app-settings') {
            $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
            $languages = get_option('languages', true);
            $appName      = env('APP_NAME');
            $appDebug     = env('APP_DEBUG');
            $timeZone     = env('TIME_ZONE', 'UTC');
            $default_lang = env('DEFAULT_LANG', 'en');

            return Inertia::render('Admin/Developer/App', compact('id', 'tzlist', 'languages', 'appName', 'appDebug', 'timeZone', 'default_lang', 'segments', 'buttons'));
        } elseif ($id == 'mail-settings') {
            $mailDriver       = env('MAIL_DRIVER_TYPE') == 'MAIL_DRIVER' ? env('MAIL_DRIVER') : env('MAIL_MAILER');
            $QUEUE_MAIL       = env('QUEUE_MAIL');
            $MAIL_DRIVER_TYPE = env('MAIL_DRIVER_TYPE');
            $MAIL_HOST        = env('MAIL_HOST');
            $MAIL_PORT        = env('MAIL_PORT');
            $MAIL_USERNAME    = env('MAIL_USERNAME');
            $MAIL_PASSWORD    = env('MAIL_PASSWORD');
            $MAIL_ENCRYPTION  = env('MAIL_ENCRYPTION');
            $MAIL_FROM_ADDRESS = env('MAIL_FROM_ADDRESS');
            $MAIL_FROM_NAME   = env('MAIL_FROM_NAME');
            $MAIL_TO          = env('MAIL_TO');

            return Inertia::render('Admin/Developer/Smtp', compact(
                'id',
                'mailDriver',
                'segments',
                'buttons',
                'QUEUE_MAIL',
                'MAIL_DRIVER_TYPE',
                'MAIL_HOST',
                'MAIL_PORT',
                'MAIL_USERNAME',
                'MAIL_PASSWORD',
                'MAIL_ENCRYPTION',
                'MAIL_FROM_ADDRESS',
                'MAIL_FROM_NAME',
                'MAIL_TO',
            ));
        } elseif ($id == 'storage-settings') {
            $FILESYSTEM_DISK = env('FILESYSTEM_DISK');
            $WAS_ACCESS_KEY_ID = env('WAS_ACCESS_KEY_ID');
            $SECRET_ACCESS_KEY = env('SECRET_ACCESS_KEY');
            $WAS_DEFAULT_REGION = env('WAS_DEFAULT_REGION');
            $WAS_BUCKET = env('WAS_BUCKET');
            $WAS_ENDPOINT = env('WAS_ENDPOINT');

            return Inertia::render('Admin/Developer/Storage', compact(
                'id',
                'FILESYSTEM_DISK',
                'WAS_ACCESS_KEY_ID',
                'SECRET_ACCESS_KEY',
                'WAS_DEFAULT_REGION',
                'WAS_BUCKET',
                'WAS_ENDPOINT',
                'segments',
                'buttons'
            ));
        } elseif ($id == 'newsletter-settings') {

            $NEWSLETTER_API_KEY = env('NEWSLETTER_API_KEY');
            $NEWSLETTER_LIST_ID = env('NEWSLETTER_LIST_ID');

            return Inertia::render('Admin/Developer/Newsletter', compact(
                'id',
                'NEWSLETTER_API_KEY',
                'NEWSLETTER_LIST_ID',
                'segments',
                'buttons'
            ));
        } elseif ($id == 'features-settings') {
            $EMAIL_VERIFICATION = env('EMAIL_VERIFICATION');
            $PHONE_VERIFICATION = env('PHONE_VERIFICATION');
            $KYC_VERIFICATION = env('KYC_VERIFICATION');
            return Inertia::render('Admin/Developer/Features', compact(
                'id',
                'EMAIL_VERIFICATION',
                'PHONE_VERIFICATION',
                'KYC_VERIFICATION',
            ));
        } elseif ($id == 'social-login-settings') {

            $GOOGLE_CLIENT_ID = env('GOOGLE_CLIENT_ID');
            $GOOGLE_CLIENT_SECRET = env('GOOGLE_CLIENT_SECRET');
            $GOOGLE_REDIRECT_URL = env('GOOGLE_REDIRECT_URL');

            $FACEBOOK_CLIENT_ID = env('FACEBOOK_CLIENT_ID');
            $FACEBOOK_CLIENT_SECRET = env('FACEBOOK_CLIENT_SECRET');
            $FACEBOOK_REDIRECT_URL = env('FACEBOOK_REDIRECT_URL');

            return Inertia::render('Admin/Developer/SocialLogin', compact(
                'id',
                'GOOGLE_CLIENT_ID',
                'GOOGLE_CLIENT_SECRET',
                'GOOGLE_REDIRECT_URL',
                'FACEBOOK_CLIENT_ID',
                'FACEBOOK_CLIENT_SECRET',
                'FACEBOOK_REDIRECT_URL',
                'segments',
                'buttons'
            ));
        } elseif ($id == 'cookie-settings') {

            $COOKIE_CONSENT = env('COOKIE_CONSENT');
            $cookieData = json_decode(file_get_contents(database_path('json/cookie_data.json')), true);
            return Inertia::render('Admin/Developer/Cookie', compact(
                'id',
                'COOKIE_CONSENT',
                'cookieData',
                'segments',
                'buttons'
            ));
        }

        abort(404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if ($id == 'app-settings') {
            $this->editEnv('APP_NAME', Str::slug($request->APP_NAME));
            $this->editEnv('APP_DEBUG', filter_var($request->APP_DEBUG, FILTER_VALIDATE_BOOLEAN), true);
            $this->editEnv('TIME_ZONE', $request->TIME_ZONE);
            $this->editEnv('DEFAULT_LANG', $request->DEFAULT_LANG ?? 'en');
        } elseif ($id == 'storage-settings') {
            $this->editEnv('FILESYSTEM_DISK', $request->FILESYSTEM_DISK);
            $this->editEnv('WAS_ACCESS_KEY_ID', $request->WAS_ACCESS_KEY_ID);
            $this->editEnv('SECRET_ACCESS_KEY', $request->SECRET_ACCESS_KEY);
            $this->editEnv('WAS_DEFAULT_REGION', $request->WAS_DEFAULT_REGION);
            $this->editEnv('WAS_BUCKET', $request->WAS_BUCKET);
            $this->editEnv('WAS_ENDPOINT', $request->WAS_ENDPOINT);
        } elseif ($id == 'newsletter-settings') {

            $this->editEnv('NEWSLETTER_API_KEY', $request->NEWSLETTER_API_KEY);
            $this->editEnv('NEWSLETTER_LIST_ID', $request->NEWSLETTER_LIST_ID);
        } elseif ($id == 'mail-settings') {

            $this->editEnv('QUEUE_MAIL', filter_var($request->QUEUE_MAIL, FILTER_VALIDATE_BOOLEAN), true);
            $this->editEnv('MAIL_DRIVER_TYPE', $request->MAIL_DRIVER_TYPE);
            $this->editEnv($request->MAIL_DRIVER_TYPE, $request->MAIL_DRIVER);
            $this->editEnv('MAIL_HOST', $request->MAIL_HOST);
            $this->editEnv('MAIL_PORT', $request->MAIL_PORT);
            $this->editEnv('MAIL_USERNAME', $request->MAIL_USERNAME);
            $this->editEnv('MAIL_PASSWORD', $request->MAIL_PASSWORD);
            $this->editEnv('MAIL_ENCRYPTION', $request->MAIL_ENCRYPTION);
            $this->editEnv('MAIL_FROM_ADDRESS', $request->MAIL_FROM_ADDRESS);
            $this->editEnv('MAIL_FROM_NAME', $request->MAIL_FROM_NAME);
            $this->editEnv('MAIL_TO', $request->MAIL_TO);
        }  elseif ($id == 'features-settings') {
            $this->editEnv('EMAIL_VERIFICATION', $request->EMAIL_VERIFICATION, true);
            $this->editEnv('KYC_VERIFICATION', $request->KYC_VERIFICATION, true);
        } elseif ($id == 'cookie-settings') {
            $this->editEnv('COOKIE_CONSENT', $request->COOKIE_CONSENT, true);
            file_put_contents(database_path('json/cookie_data.json'), json_encode($request->cookieData));
        }

        return back();
    }
}
