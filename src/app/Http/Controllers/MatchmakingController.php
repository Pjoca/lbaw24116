<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatchmakingController extends Controller
{
    public function index()
    {
        // Dados de exemplo (em produção, use o banco de dados)
        $profiles = [
            (object)[
                'id' => 1,
                'name' => 'Julia Toth',
                'image' => 'https://100dayscss.com/codepen/13-1.jpg',
                'header_image' => 'https://100dayscss.com/codepen/13-1-header.jpg',
            ],
            (object)[
                'id' => 2,
                'name' => 'John Doe',
                'image' => 'https://100dayscss.com/codepen/13-2.jpg',
                'header_image' => 'https://100dayscss.com/codepen/13-2-header.jpg',
            ],
        ];

        return view('pages.matchmaking', compact('profiles'));
    }
}
