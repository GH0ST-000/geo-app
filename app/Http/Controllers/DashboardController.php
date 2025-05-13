<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\User;
use App\Models\UserStandard;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Extract statistics data
        $statisticsData = [
            'user' => User::count(),
            'product' => Product::count(),
            'application' =>  UserStandard::select('group_id')
                ->whereNotNull('group_id')
                ->groupBy('group_id')
                ->get()
                ->count()

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

        // Get all applications, ordered by newest first
        $applications = UserStandard::orderBy('created_at', 'desc')->get();

        // Group applications by group_id
        $groupedApplications = [];

        // First, handle applications with group_id
        $withGroupId = $applications->whereNotNull('group_id')->groupBy('group_id');

        foreach ($withGroupId as $groupId => $group) {
            // Get the first application in the group (which is the latest one since we sorted by created_at desc)
            $firstApp = $group->first();
            $user = User::where('id', $firstApp->user_id)->first();

            if ($user) {
                $groupedApplications[] = [
                    'id' => $firstApp->id,
                    'fullName' => $user->first_name . ' ' . $user->last_name,
                    'standard' => $standard[$firstApp->slug] ?? $firstApp->slug,
                    'created_at' => $firstApp->created_at->diffForHumans(),
                    'sort_date' => $firstApp->created_at->timestamp, // For accurate sorting
                    'reject_reason' => $firstApp->reject_reason,
                    'is_group' => true,
                    'group_id' => $groupId,
                    'file_count' => $group->count(),
                    'is_verified' => $firstApp->is_verified ?? false,
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
                    'reject_reason' => $application->reject_reason,
                    'sort_date' => $application->created_at->timestamp, // For accurate sorting
                    'is_group' => false,
                    'file_count' => 1,
                    'is_verified' => $application->is_verified ?? false,
                    'files' => [$application->file_name]
                ];
            }
        }

        // Sort by created_at timestamp (newest first)
        usort($groupedApplications, function($a, $b) {
            return $b['sort_date'] - $a['sort_date'];
        });

        // Limit to 5 most recent applications for the dashboard
        $recentApplications = array_slice($groupedApplications, 0, 5);

        return view('pages.dashboard', array_merge($statisticsData, [
            'applications' => $recentApplications
        ]));
    }
}
