<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Naux\Mail\SendCloudTemplate;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'confirmation_token', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function owns(Model $model)
    {
        return $this->id == $model->user_id;
    }

    public function sendPasswordResetNotification($token)
    {
        $data = ['url' =>  url('password/reset', $token)];
        $template = new SendCloudTemplate('zhihu_password_reset', $data);
        Mail::raw($template, function ($message) {
            $message->from('ins@zhihu.dev', 'ins.zhihu');
            $message->to($this->email);
        });
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function questions()
    {
        $this->hasMany(Question::class);
    }

    public function followThis($question)
    {
        return $this->follows()->toggle($question);
    }

    public function follows()
    {
        return $this->belongsToMany(Question::class, 'user_question');
    }

    public function followed($question)
    {
        return !! $this->follows()->where('question_id', $question)->count();
    }

    public function followers(){
        return $this->belongsToMany(self::class, 'followers', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function followersUser(){
        return $this->belongsToMany(self::class, 'followers', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function followThisUser($user)
    {
        return $this->followers()->toggle($user);
    }

    public function votes()
    {
        return $this->belongsToMany(Answer::class, 'votes')->withTimestamps();
    }

    public function voteFor($answer)
    {
        return $this->votes()->toggle($answer);
    }

    public function hasVotedFor($answer)
    {
        return !! $this->votes()->where('answer_id', $answer)->count();
    }

}
