<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    public function show($id)
    {
        //$user = User::find($id);
        //$timeline = $user->timeline()->get();
        //return response()->json($timeline);
        $timeline = [
            "id" => 1,
            "name"=> "JLPT",
            "tasks"=> [
                [
                    "id"=> "JLPTT1",
                    "time" => "Thang 1",
                    "main" => "500 tu vung & 200 mau ngu phap ",
                    "goals" => [
                        [
                        "id" => "JLPTT1G1",
                        "goal" => "500 tu vung",
                        "hasComplete" => true
                        ],
                        [
                        "id" => "JLPTT1G2",
                        "goal" => "200 mau ngu phap",
                        "hasComplete" => false
                        ],
                        [
                        "id" => "JLPTT1G3",
                        "goal" => "100 mau ngu phap",
                        "hasComplete" => false
                        ]
                    ]
                ],
                [
                    "id" => "JLPTT2",
                    "time" => "Thang 2",
                    "main" => "600 tu vung & 100 mau ngu phap",
                    "goals" => [
                        [
                        "id" => "JLPTT2G1",
                        "goal" => "600 tu vung",
                        "hasComplete" => false
                        ],
                        [
                        "id" => "JLPTT2G2",
                        "goal" => "100 mau ngu phap",
                        "hasComplete" => false
                        ]
                    ]
                ],
                [
                    "id" => "JLPTT3",
                    "time" => "Thang 3",
                    "main" => "200 tu vung & 100 mau ngu phap & Luyen de",
                    "goals" => [
                        [
                            "id" => "JLPTT3G1",
                            "goal" => "200 tu vung",
                            "hasComplete" => false
                        ],
                        [
                            "id" => "JLPTT3G2",
                            "goal" => "100 mau ngu phap",
                            "hasComplete" => false
                        ],
                        [
                            "id" => "JLPTT3G3",
                            "goal" => "Luyen de",
                            "hasComplete" => false
                        ]
                    ]
                ]
            ]
        ];
        return response()->json([
            "status" => 200,
            "timeline" => $timeline
        ]);
    }
}
