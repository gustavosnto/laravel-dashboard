<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Business;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $projectCount = Project::count();
        $businessCount = Business::count();

        return Inertia::render('Dashboard', [
            'projectCount' => $projectCount,
            'businessCount' => $businessCount,
        ]);
    }
}