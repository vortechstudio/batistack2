<?php

namespace App\Http\Controllers;

use App\Models\Core\Company;
use App\Services\Bridge;

class BankController extends Controller
{
    public function connectAccount()
    {
        $company = Company::first();
        $bridge = new Bridge();
        if (!$company->bridge_client_id) {
            $user_account = $bridge->post('aggregation/users', [
                "external_user_id" => "USER".rand(1,5000),
            ]);
            $company->update(['bridge_client_id' => $user_account['uuid']]);
        }

        $authToken = $bridge->post('aggregation/authorization/token', [
            'user_uuid' => $company->bridge_client_id,
        ]);
        cache()->put('bridge_access_token', $authToken['access_token']);

        try {
            $session = $bridge->post('aggregation/connect-sessions', [
                'user_email' => $company->email,
                'country_code' => "FR",
                'callback_url' => config('app.url') . '/settings/bank/create',
            ], $authToken['access_token']);
            if (array_key_exists('errors', $session)) {
                toastr()->error($session['errors'][0]['message'], [], $session['errors'][0]['code']);
            }
            return redirect($session['url']);
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage(), ['session' => $session]);
            toastr()->addError($e->getMessage(), [], 'Erreur');
            return redirect()->back();
        }

    }
}
