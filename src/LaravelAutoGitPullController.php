<?php
namespace Ahmeti\LaravelAutoGitPull;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;

class LaravelAutoGitPullController extends Controller {

    public function pull(Request $request)
    {
        if(
            ! is_null(env('AUTO_PULL_DIR')) &&
            ! is_null(env('AUTO_PULL_SERVER_IP')) &&
            ! is_null(env('AUTO_PULL_SSH_USER')) &&
            ! is_null(env('AUTO_PULL_SECRET')) &&
            ! is_null($request->secret) &&
            env('AUTO_PULL_SECRET') == $request->secret
        ){

            if( ! is_nul(env('AUTO_PULL_SSH_PRIVATE_KEY')) ){
                return $this->connetSSHWithKey();

            }elseif( ! is_nul(env('AUTO_PULL_SSH_USER_PASS')) ){
                return $this->connetSSHWithPassword();

            }else{
                return response()->json(['status' => false]);
            }
        }
    }

    private function connetSSHWithKey()
    {
        $key = new RSA();
        $key->loadKey(file_get_contents(base_path(env('AUTO_PULL_SSH_PRIVATE_KEY'))));

        $ssh = new SSH2(env('AUTO_PULL_SERVER_IP'));
        if (!$ssh->login(env('AUTO_PULL_SSH_USER'), $key)) {
            return response()->json(['status' => false]);
        }

        return $this->processPull($ssh);
    }

    private function connetSSHWithPassword()
    {
        $ssh = new SSH2(env('AUTO_PULL_SERVER_IP'));
        if (!$ssh->login(env('AUTO_PULL_SSH_USER'), env('AUTO_PULL_SSH_USER_PASS'))) {
            return response()->json(['status' => false]);
        }

        return $this->processPull($ssh);
    }

    private function processPull($ssh)
    {
        $ssh->exec('cd '.env('AUTO_PULL_DIR'));
        $ssh->exec('git stash save --keep-index');
        $ssh->exec('git pull');
        $ssh->exec('exit');

        return response()->json(['status' => true]);
    }
}