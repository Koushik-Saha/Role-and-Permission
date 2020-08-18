<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard - Analytics
    public function dashboardAnalytics(){
        $pageConfigs = [
            'pageHeader' => false,
            'sidebarCollapsed' => true
        ];

      return view('back-end.dashboard.dashboard')->with([
        'pageConfigs' => $pageConfigs,
      ]);

    }

}

