<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MongoUser extends Authenticatable
{
    use Notifiable;
    protected $collection = 'users';
    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'dob',
        'country',
        'country_code',
        'home_city',
        'current_city',
        'contact',
        'package',
        'status',
        'OTP',
        'expiry',
        'phone_status',
        'having_ref_code',
        'familyType',
        'anniversary',
        'child',
        'spouse',
        'waiter',
        'family_card',
        'interest',
        'myChoice',
        'own_referral_code',
        'spouseChoice',
        'childChoice',
        'Restaurants',
        'role',
        'Membership',
        'image',
        'login_source',
        'profileStep1',
        'profileStep2',
        'profileStep3',
        'profileStep4',
        'my_prefer_drink',
        'spouse_prefer_drink',
        'No_of_child',
        'total_subscription',
        'Restra_id',
        'Hotel_id',
        'payment_status',
        'membership_valid',
        'pay_route',
        'otherotp',
        'othertoken',
        'my_saving',
        'vegetarian',
        'points',
        'last_login_at',
        'the last_login_ip',
        'login_count',
        'varifyToken',
        'password',
        'globalid',
		'added_by',
        'otpValid',
        'varifyOtp',
        'total_spends',
        'authToken',
        'payment_mode',
        'transactionid',
        'chequeno',
        'chequepaymentmode',
        'renew_status',
        'resetPassword_OTP',
        'request_call_back',
    ];
    protected $attributes = ['status'=>0,'phone_status'=>0,'added_by'=>'0','familyType'=>1,'countryCode'=>'91'];



}
