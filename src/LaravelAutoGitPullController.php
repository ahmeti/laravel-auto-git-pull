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
            ! is_null(env('AUTO_PULL_SECRET')) &&
            ! is_null(env('AUTO_PULL_DIR')) &&
            ! is_null(env('AUTO_PULL_SERVER_IP')) &&
            ! is_null(env('AUTO_PULL_SSH_USER')) &&
            ! is_null($request->secret) &&
            env('AUTO_PULL_SECRET') === $request->secret
        ){
            if( ! is_null(env('AUTO_PULL_SSH_PRIVATE_KEY')) ){
                return $this->connetSSHWithKey();

            }elseif( ! is_null(env('AUTO_PULL_SSH_USER_PASS')) ){
                return $this->connetSSHWithPassword();

            }

            return response()->json(['status' => false, 'message'=>'Connection failed with key or password.']);
        }

        return response()->json(['status' => false, 'message'=>'Please check .env variables.']);
    }

    private function connetSSHWithKey()
    {
        $privateKey=file_get_contents(base_path(env('AUTO_PULL_SSH_PRIVATE_KEY')));

        $key = new RSA();
        $key->loadKey($privateKey);

        $ssh = new SSH2(env('AUTO_PULL_SERVER_IP'));
        if (!$ssh->login(env('AUTO_PULL_SSH_USER'), $key)) {
            return response()->json(['status' => false, 'message'=>'Connection failed with rsa key.', 'errors'=>$ssh->getErrors()]);
        }

        return $this->processPull($ssh);
    }

    private function connetSSHWithPassword()
    {
        $ssh = new SSH2(env('AUTO_PULL_SERVER_IP'));
        if (!$ssh->login(env('AUTO_PULL_SSH_USER'), env('AUTO_PULL_SSH_USER_PASS'))) {
            return response()->json(['status' => false, 'message'=>'Connection failed with password.', 'errors'=>$ssh->getErrors()]);
        }

        return $this->processPull($ssh);
    }

    private function processPull($ssh)
    {
        $message=$ssh->exec('cd '.env('AUTO_PULL_DIR').' && git stash save --keep-index && git pull');
        $ssh->exec('exit');

        return response()->json(['status' => true, 'message' => $message, 'errors'=>$ssh->getErrors()]);
    }
}