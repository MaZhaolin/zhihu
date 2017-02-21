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
}
