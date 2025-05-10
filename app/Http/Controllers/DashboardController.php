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

        $standard = [
            'honey_standard' => 'თაფლის მოდული',
            'dairy_standard' => 'რძის მოდული',
            'crop_standard' =>'მემცენარეობის მოდული',
            'other_standard' => 'სხვა მოდული',
            'images' => 'სურათები',
            'documents' => 'დოკუმენტები',
            'videos' => 'ვიდეოები',
            'audio' => 'აუდიო',
            'spreadsheets' => 'ელცხრილები',
            'binary' => 'ბინარული ფაილები'
        ];

        // Get all applications
        $applications = UserStandard::all();

        // Group applications by group_id
        $groupedApplications = [];

        // First, handle applications with group_id
        $withGroupId = $applications->whereNotNull('group_id')->groupBy('group_id');

        foreach ($withGroupId as $groupId => $group) {
            // Get the first application in the group
            $firstApp = $group->first();
            $user = User::where('id', $firstApp->user_id)->first();

            if ($user) {
                $groupedApplications[] = [
                    'id' => $firstApp->id,
                    'fullName' => $user->first_name . ' ' . $user->last_name,
                    'standard' => $standard[$firstApp->slug] ?? $firstApp->slug,
                    'created_at' => $firstApp->created_at->diffForHumans(),
                    'is_group' => true,
                    'group_id' => $groupId,
                    'file_count' => $group->count(),
                    'is_verified' => $firstApp->is_verified,
                    'files' => $group->pluck('file_name')->toArray()
                ];
            }
        }

        // Then, handle applications without group_id
        $withoutGroupId = $applications->whereNull('group_id');

        foreach ($withoutGroupId as $application) {
            $user = User::where('id', $application->user_id)->first();

            if ($user) {
                $groupedApplications[] = [
                    'id' => $application->id,
                    'fullName' => $user->first_name . ' ' . $user->last_name,
                    'standard' => $standard[$application->slug] ?? $application->slug,
                    'created_at' => $application->created_at->diffForHumans(),
                    'is_group' => false,
                    'file_count' => 1,
                    'files' => [$application->file_name]
                ];
            }
        }

        // Sort by created_at (newest first)
        usort($groupedApplications, function($a, $b) {
            return strtotime(str_replace(' ago', '', $b['created_at'])) <=> strtotime(str_replace(' ago', '', $a['created_at']));
        });

        return view('pages.dashboard', array_merge($statisticsData, [
            'applications' => $groupedApplications
        ]));
    }
}
