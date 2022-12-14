<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemeRequest;
use App\Http\Requests\UpdateMemeRequest;
use App\Models\Meme;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class MemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return 50 random memes
        return Meme::inRandomOrder()->take(50)->get();
    }


    public function giveMemes(){
        // contact meme API
        $url = 'https://meme-api.com/gimme/50';
        $response = file_get_contents($url);
        $memes = json_decode($response, true);

        // loop through memes and save to database
        foreach($memes['memes'] as $meme){
            $meme = Meme::create([
                'title' => $meme['title'],
                'url' => $meme['url'],
                'postLink' => $meme['postLink'],
                'subreddit' => $meme['subreddit'],
                'nsfw' => $meme['nsfw'],
                'spoiler' => $meme['spoiler'],
                'author' => $meme['author'],
                'ups' => $meme['ups'],
            ]);
        }

        // return 50 random memes with id from db
        return Meme::inRandomOrder()->take(50)->get();
    }


    public function like(Meme $meme){
        // add meme to user's liked memes
        Auth::user()->memes()->attach($meme);

        // 1/5 chance to get a reandom user
        $random = rand(1, 5);
        if($random == 1){
            // get a random user




            $user = User::inRandomOrder()->first();
            return new JsonResponse($user, 200);
        } else {
            $match = null;
            // return 403
            return new JsonResponse($match, 210);

        }
    }
}
