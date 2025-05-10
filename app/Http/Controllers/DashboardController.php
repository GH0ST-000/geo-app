<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\UserStandard;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Extract statistics data
        $statisticsData = [
            'user' => User::count(),
            'product' => Product::count(),
            'application' => UserStandard::count()
        ];

        // Extract to a constant or config value
        $standardLabels = [
            'honey_standard' => 'თაფლის მოდული',
            'dairy_standard' => 'რძის მოდული',
            'crop_standard' => 'მემცენარეობის მოდული',
        ];

        // Use eager loading to reduce queries and transform data in one go
        $recentApplications = UserStandard::with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($application) use ($standardLabels) {
                return [
                    'id' => $application->id,
                    'fullName' => $application->user->first_name . ' ' . $application->user->last_name,
                    'standard' => $standardLabels[$application->slug] ?? $application->slug,
                    'created_at' => $application->created_at->diffForHumans()
                ];
            });

        return view('pages.dashboard', array_merge($statisticsData, [
            'applications' => $recentApplications
        ]));
    }
}
