<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 自分自身ではないかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 自分自身ではないかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
}

    public function is_following($userId) {
        return $this->followings()->where('follow_id', $userId)->exists();
    }

     public function feed_microposts()
    {
        $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    
    //お気に入りに関するメソッド開始
	
	    public function favorite()
	    {
	        return $this->belongsToMany(Micropost::class, 'user_favorites', 'user_id', 'micropost_id')->withTimestamps();
	    
	    }
	    public function favored()
	    {
	        return $this->belongsToMany(Micropost::class, 'user_favorites', 'micropost_id', 'user_id')->withTimestamps();
	    }
	    
	    public function favor($mid)
	    {
	        // 既にお気に入りに登録しているかの確認
	        $exist = $this->is_favoring($mid);
	        // 自分自身投稿ではないかの確認
	        $its_mine = in_array($mid, $this->microposts->pluck('id')->toArray());
	        if ($exist || $its_mine) {
	            // 既にお気に入れていれば何もしない
	            return false;
	        } else {
	            // 未お気に入りであればお気に入り登録する
	            $this->favorite()->attach($mid);
	            return true;
	        }


	    }
	
	    public function unfavor($mid)
	    {
	        // 既にお気に入りに登録しているかの確認
	        $exist = $this->is_favoring($mid);
	
	        if ($exist) {
	            // 既にお気に入りしていればフォローを外す
	            $this->favorite()->detach($mid);
	            return true;
	        } else {
	            // 未お気に入り登録であれば何もしない
	            return false;
	        }
	}
	
	    public function is_favoring($mid) {
	        return $this->favorite()->where('micropost_id', $mid)->exists();
	    }
	    
    //お気に入りに関するメソッド終了
}
