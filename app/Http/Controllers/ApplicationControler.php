<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserStandard;
use Illuminate\Http\Request;


class ApplicationControler extends Controller
{
    public function index()
    {
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



        // Get all applications, already ordered by ID descending (newest first)
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
                    'is_group' => true,
                    'group_id' => $groupId,
                    'is_verified' => $firstApp->is_verified,
                    'reject_reason' => $firstApp->reject_reason,
                    'file_count' => $group->count(),
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
                    'sort_date' => $application->created_at->timestamp, // For accurate sorting
                    'is_group' => false,
                    'is_verified' => $application->is_verified ?? false,
                    'reject_reason' => $application->reject_reason,
                    'file_count' => 1,
                    'files' => [$application->file_name]
                ];
            }
        }

        // Sort by created_at timestamp (newest first)
        usort($groupedApplications, function($a, $b) {
            return $b['sort_date'] - $a['sort_date'];
        });

        return view('pages.applications', ['applications' => $groupedApplications]);
    }

    public function show($id)
    {
       $application = UserStandard::where('id', $id)->first();

       if (!$application) {
           return redirect()->route('applications')->with('error', 'განცხადება ვერ მოიძებნა');
       }

       // Get user details
       $user = User::find($application->user_id);
       $userName = $user ? $user->first_name . ' ' . $user->last_name : 'Unknown User';

       // If this is part of a group, get all files in the group
       if ($application && $application->group_id) {
           $groupFiles = UserStandard::where('group_id', $application->group_id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

           // Add file URLs and separate images from other files
           $imageFiles = [];
           $otherFiles = [];

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

           foreach ($groupFiles as $file) {
               // Add full URL to the file
               $file->file_url = \Storage::url($file->file_path);

               // Determine if it's an image or another file type
               if ($file->file_category === 'image') {
                   $imageFiles[] = $file;
               } else {
                   $otherFiles[] = $file;
               }
           }

           return view('pages.applicationDetail', [
               'application' => $application,
               'groupFiles' => $groupFiles,
               'imageFiles' => $imageFiles,
               'otherFiles' => $otherFiles,
               'isGroup' => true,
               'userName' => $userName,
               'user' => $user,
               'standard' => $standard[$application->slug] ?? $application->slug,
           ]);
       }

       // For single file, add the URL
       if ($application) {
           $application->file_url = \Storage::url($application->file_path);
           $isImage = $application->file_category === 'image';
       }

       return view('pages.applicationDetail', [
           'application' => $application,
           'isGroup' => false,
           'isImage' => $isImage ?? false,
           'userName' => $userName,
           'user' => $user
       ]);
    }

    public function update(Request $request)
    {
        $user = User::find($request->user_id);
        $user->is_verified = 1;
        $user->save();

        UserStandard::where('group_id',$request->app_id)->update(['is_verified'=>true]);
        return redirect(url('admin/applications'))->with('message','მოქმედება წარმეტებით შესრულდა');
    }


    public function reject(Request $request)
    {

        $user = User::find($request->userID);
        $user->is_verified = 0;
        $user->save();

        UserStandard::where('group_id',$request->appID)->update(['is_verified'=>false,'reject_reason'=>$request->reason]);
        return redirect(url('admin/applications'))->with('message','მოქმედება წარმეტებით შესრულდა');

    }
}

