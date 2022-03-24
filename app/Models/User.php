<?php

namespace App\Models;

use App\Enums\MediaCollection;
use App\Enums\MediaTypes;
use App\Notifications\User\ResetPassword;
use App\Notifications\User\VerifyEmail;
use App\Traits\UUID;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use InteractsWithMedia;
    use Notifiable;
    use UUID;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'media',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Indicates custom attributes to append to model.
     *
     * @var array
     */
    public $appends = [
        'profile_picture',
    ];

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get profile picture attribute.
     *
     * @return string
     */
    public function getProfilePictureAttribute()
    {
        return $this->getFirstMediaUrl(MediaCollection::PROFILEPICTURE);
    }

    /**
     * Registers media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::PROFILEPICTURE)
            ->useFallbackUrl(url('/img/placeholders/avatar.png'))
            ->acceptsMimeTypes(MediaTypes::IMAGES)
            ->singleFile();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $callbackUrl = request('callbackUrl', config('frontend.url'));

        $this->notify(new VerifyEmail($callbackUrl));
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        request()->validate([
            'callbackUrl' => 'required|url',
        ]);
        $callbackUrl = request('callbackUrl', config('frontend.user.url'));

        $this->notify(new ResetPassword($callbackUrl, $token));
    }

    /**
     * Get the cordinator details associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cordinator()
    {
        return $this->hasOne(Cordinator::class);
    }

    /**
     * Get the contributor details associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contributor()
    {
        return $this->hasOne(Contributor::class);
    }
}
