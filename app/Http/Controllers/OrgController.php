<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Notifications\SignupActivate;
use Illuminate\Support\Str;
// use Laravolt\Avatar\Avatar;
use Laravolt\Avatar\Facade as AvatarCustom;
use Storage;
use App\Role;
use App\MongoUser;

class OrgController extends Controller {

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => Str::random(60)
        ]);
        $user->save();
        /* Attach Roles admin */
        $admin_role = Role::where('name', 'admin')->first();
        $user->roles()->attach($admin_role);

        // create avatar and update user
        // $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        //$avatar = AvatarCustom::create($user->name)->getImageObject()->encode('png');
        //Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);
        //$user->notify(new SignupActivate($user)); // call activation notification 
        return response()->json([
                    'message' => 'Successfully created user!'
                        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        //validate that account is active and has not been deleted.
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials))
            return response()->json([
                        'message' => 'Unauthorized'
                            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                    )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return response()->json([
                    'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request) {
        return response()->json($request->user());
    }

    /*
     * Verify signup user via email 
     * @param  [string] activation_token
     */

    public function Activatesignup($token) {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                        'message' => 'This activation token is invalid.'
                            ], 404);
        } $user->active = true;
        $user->activation_token = '';
        $user->save();
        return $user;
    }

    /*
     * Check ACL Roles
     * 
     */

    public function checkrole(Request $request) {
        dd($request->all());
    }

    /**
     * 
     * 
     */
    public function usermailcheck($email, Request $request) {
        $input = $request->all();
        $user_detail = MongoUser::where('email', $email)->first();
        dd($user_detail);
      
        if ($user_detail) {
            $user_payment = commonhelper::CheckMemberPaymentStatus($user_detail->email);
//        dd($user_payment);
            if ($user_payment['status']) {
                $user_detail['mem_date'] = $user_detail->membership_valid->toDatetime()->format('Y-m-d');
                return $user_detail;
            } else {
                $user_detail['paymentdone'] = 0;
                return $user_detail;
            }
        } else {
            return 0;
        }
    }

}
