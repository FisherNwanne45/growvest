<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Dotenv;
use Session;
use Artisan;
use Config;
use DB;
use File;
use Cache;

class InstallerController extends Controller
{
    use Dotenv;

    public function index()
    {
        return redirect('/'); // Skip installation check and redirect to home
    }

    public function store(Request $request)
    {
        $this->editEnv('APP_URL', url('/'));
        $this->editEnv('APP_NAME', $request->site_name);

        $this->editEnv('DB_CONNECTION', $request->db_connection);
        $this->editEnv('DB_HOST', $request->db_host);
        $this->editEnv('DB_PORT', $request->db_port);

        $this->editEnv('DB_DATABASE', $request->db_name);
        $this->editEnv('DB_USERNAME', $request->db_user);

        if (!empty($request->db_pass)) {
            $this->editEnv('DB_PASSWORD', $request->db_pass);
        }

        try {
            $pdo = DB::connection()->getPdo();

            return response()->json(['message' => 'Installation processed']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database connection failed'], 401);
        }
    }

    public function migrate()
    {
        ini_set('max_execution_time', 0);

        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);

            File::put('public/uploads/installed', 'installed');

            return response()->json(['message' => 'Installation complete', 'redirect' => url('/')]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database migration failed'], 401);
        }
    }

    public function show($type)
    {
        return redirect('/'); // Skip installation screens
    }

    public function verify(Request $request)
    {
        return response()->json(['message' => 'Verification bypassed', 'redirect' => url('/')]);
    }
}