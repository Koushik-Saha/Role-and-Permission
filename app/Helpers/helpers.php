<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use App\Models\Activity;
use App\Models\Payment;
use App\User;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class Helper
{

    public static function getMonthlyAttendancesOfUser(string $month, User $user) {
        return $user->attendances()
            ->whereYear('updated_at', '=', \Str::before($month, '-'))
            ->whereMonth('updated_at', '=', \Str::after($month, '-'))
            ->get();
    }

    public static function getMonthlyPaymentsToStaff(string $month, User $user) {
        return $user->staffPayments()
            ->whereYear('updated_at', '=', \Str::before($month, '-'))
            ->whereMonth('updated_at', '=', \Str::after($month, '-'))
            ->get();
    }


    public static function sendJsonResponse(string $status = 'success', string $msg = 'OK!') {
        return response()->json([
            'status' => strtolower($status),
            'msg'    => $msg
        ]);
    }


    public static function createNewPayment(array $data, string $note = 'New Payment Made!') {
        $payment = new Payment();

        $payment->payment_type = isset($data['type']) ? $data['type'] : null;
        $payment->payment_to_user = isset($data['to_user']) ? $data['to_user'] : null;
        $payment->payment_from_user = isset($data['from_user']) ? $data['from_user'] : null;
        $payment->payment_to_bank_account = isset($data['to_bank_account']) ? $data['to_bank_account'] : null;
        $payment->payment_from_bank_account = isset($data['from_bank_account']) ? $data['from_bank_account'] : null;
        $payment->payment_for_project = $data['project'];
        $payment->payment_purpose = $data['purpose'];
        $payment->payment_amount = $data['amount'];
        $payment->payment_by = isset($data['by']) ? $data['by'] : 'cash';
        $payment->payment_date = isset($data['date']) ? $data['date'] : Carbon::now()->toDateString();
        $payment->payment_image = isset($data['image']) ? $data['image'] : null;
        $payment->payment_note = isset($data['note']) ? $data['note'] : null;

        if($payment->save()) {
            Helper::addActivity('payment', $payment->payment_id, $note);
            return true;
        }
        return false;
    }

    public static function mobileNumber(string $mobile = null) {
        return ($mobile) ? '+880 ' . substr($mobile, 0, 4) . ' ' . substr($mobile, -6) : null;
    }

    public static function addActivity(string $for, int $forId, string $note) {
        $activityFor = 'activity_';
        $activityFor .= ($for === 'user') ? 'for_user_id' : $for . '_id';

        return Activity::insert([
            'activity_of_user_id'   => Auth::id(),
            'activity_note'         => $note,
            $activityFor            => $forId,
            'created_at'            => \Carbon\Carbon::now(config('app.timezone'))
        ]);
    }

    public static function redirectBackWithException(Exception $e) {
        $err_msg = Lang::get($e->getMessage());
        $notification = [
            'message'       => $err_msg,
            'alert-type'    => 'error'
        ];

        return redirect()->back()->with($notification);
    }

    public static function redirectBackWithNotification(string $type = 'error', string $msg = 'Something Wrong!') {
        $notification = [
            'message'       => $msg,
            'alert-type'    => $type
        ];

        return redirect()->back()->with($notification);
    }

    public static function redirectUrlWithNotification(string $url, string $type = 'success', string $msg = 'Looking Good!') {
        $notification = [
            'message'       => $msg,
            'alert-type'    => $type
        ];

        return redirect($url)->with($notification);
    }

    public static function redirectBackWithValidationError(Validator $validator) {
        $notification = [
            'message' => 'Please Fill Up fields properly!',
            'alert-type' =>'error'
        ];
        return redirect()->back()->withInput()->withErrors($validator)->with($notification);
    }

    public static function applClasses()
    {
        // Demo
        $fullURL = request()->fullurl();
        if(App()->environment() === 'production'){
          for ($i=1; $i < 7; $i++) {
            $contains = Str::contains($fullURL, 'demo-'.$i);
            if($contains === true){
              $data = config('custom.'.'demo-'.$i);
            }
          }
        }
        else{
          $data = config('custom.custom');
        }

        // $data = config('custom.custom');
        // echo App()->environment();
        $layoutClasses = [
            'theme' => $data['theme'],
            'sidebarCollapsed' => $data['sidebarCollapsed'],
            'navbarColor' => $data['navbarColor'],
            'menuType' => $data['menuType'],
            'navbarType' => $data['navbarType'],
            'footerType' => $data['footerType'],
            'sidebarClass' => 'menu-expanded',
            'bodyClass' => $data['bodyClass'],
            'pageHeader' => $data['pageHeader'],
            'blankPage' => $data['blankPage'],
            'blankPageClass' => '',
            'contentLayout' => $data['contentLayout'],
            'sidebarPositionClass' => '',
            'contentsidebarClass' => '',
            'mainLayoutType' => $data['mainLayoutType'],
            'direction' => $data['direction'],
         ];



        //Theme
        if($layoutClasses['theme'] == 'dark')
            $layoutClasses['theme'] = "dark-layout";
        elseif($layoutClasses['theme'] == 'semi-dark')
            $layoutClasses['theme'] = "semi-dark-layout";
        else
            $layoutClasses['theme'] = "light";

        //menu Type
        switch($layoutClasses['menuType']){
          case "static":
              $layoutClasses['menuType'] = "menu-static";
              break;
          default:
              $layoutClasses['menuType'] = "menu-fixed";
      }


        //navbar
        switch($layoutClasses['navbarType']){
          case "static":
              $layoutClasses['navbarType'] = "navbar-static";
              $layoutClasses['navbarClass'] = "navbar-static-top";
              break;
          case "sticky":
              $layoutClasses['navbarType'] = "navbar-sticky";
              $layoutClasses['navbarClass'] = "fixed-top";
              break;
          case "hidden":
              $layoutClasses['navbarType'] = "navbar-hidden";
              break;
          default:
              $layoutClasses['navbarType'] = "navbar-floating";
              $layoutClasses['navbarClass'] = "floating-nav";
      }

        // sidebar Collapsed
        if($layoutClasses['sidebarCollapsed'] == 'true')
            $layoutClasses['sidebarClass'] = "menu-collapsed";

        // sidebar Collapsed
        if($layoutClasses['blankPage'] == 'true')
            $layoutClasses['blankPageClass'] = "blank-page";

        //footer
        switch($layoutClasses['footerType']){
            case "sticky":
                $layoutClasses['footerType'] = "fixed-footer";
                break;
            case "hidden":
                $layoutClasses['footerType'] = "footer-hidden";
                break;
            default:
                $layoutClasses['footerType'] = "footer-static";
        }

        //Cotntent Sidebar
        switch($layoutClasses['contentLayout']){
            case "content-left-sidebar":
                $layoutClasses['sidebarPositionClass'] = "sidebar-left";
                $layoutClasses['contentsidebarClass'] = "content-right";
                break;
            case "content-right-sidebar":
                $layoutClasses['sidebarPositionClass'] = "sidebar-right";
                $layoutClasses['contentsidebarClass'] = "content-left";
                break;
            case "content-detached-left-sidebar":
                $layoutClasses['sidebarPositionClass'] = "sidebar-detached sidebar-left";
                $layoutClasses['contentsidebarClass'] = "content-detached content-right";
                break;
            case "content-detached-right-sidebar":
                $layoutClasses['sidebarPositionClass'] = "sidebar-detached sidebar-right";
                $layoutClasses['contentsidebarClass'] = "content-detached content-left";
                break;
            default:
                $layoutClasses['sidebarPositionClass'] = "";
                $layoutClasses['contentsidebarClass'] = "";
        }

        return $layoutClasses;
    }

    public static function updatePageConfig($pageConfigs){
        $demo = 'custom';
        $fullURL = request()->fullurl();
        if(App()->environment() === 'production'){
            for ($i=1; $i < 7; $i++) {
                $contains = Str::contains($fullURL, 'demo-'.$i);
                if($contains === true){
                $demo = 'demo-'.$i;
                }
            }
        }
        if(isset($pageConfigs)){
            if(count($pageConfigs) > 0){
                foreach ($pageConfigs as $config => $val){
                    Config::set('custom.'.$demo.'.'.$config, $val);
                }
            }
        }
    }
}
