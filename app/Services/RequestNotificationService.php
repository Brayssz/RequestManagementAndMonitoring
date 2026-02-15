<?php

namespace App\Services;

use App\Mail\RequestCreatedNotification;
use App\Mail\RequestDeletedNotification;
use App\Mail\RequestReturnedNotification;
use App\Mail\RequestTransmittedNotification;
use App\Models\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestNotificationService
{
    /**
     * Send notification when a request is created
     */
    public static function sendCreatedNotification(Request $request): bool
    {
        try {
            $requestingOffice = $request->requestingOffice;
            
            if (!$requestingOffice || !$requestingOffice->requestor_obj) {
                Log::warning('Cannot send created notification: No requestor found for request ' . $request->request_id);
                return false;
            }

            $requestor = $requestingOffice->requestor_obj;
            
            if (empty($requestor->email)) {
                Log::warning('Cannot send created notification: Requestor has no email for request ' . $request->request_id);
                return false;
            }

            $requestData = [
                'dts_tracker_number' => $request->dts_tracker_number,
                'dts_date' => $request->dts_date,
                'nature_of_request' => $request->nature_of_request,
                'amount' => $request->amount,
                'sgod_date_received' => $request->sgod_date_received,
                'allotment_year' => $request->allotment_year,
            ];

            Mail::to($requestor->email)->send(
                new RequestCreatedNotification($requestData, $requestor->name, $requestingOffice->name)
            );

            Log::info('Created notification sent to ' . $requestor->email . ' for request ' . $request->request_id);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send created notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification when a request is deleted
     */
    public static function sendDeletedNotification(Request $request): bool
    {
        try {
            $requestingOffice = $request->requestingOffice;
            
            if (!$requestingOffice || !$requestingOffice->requestor_obj) {
                Log::warning('Cannot send deleted notification: No requestor found for request ' . $request->request_id);
                return false;
            }

            $requestor = $requestingOffice->requestor_obj;
            
            if (empty($requestor->email)) {
                Log::warning('Cannot send deleted notification: Requestor has no email for request ' . $request->request_id);
                return false;
            }

            $requestData = [
                'dts_tracker_number' => $request->dts_tracker_number,
                'dts_date' => $request->dts_date,
                'nature_of_request' => $request->nature_of_request,
                'amount' => $request->amount,
            ];

            Mail::to($requestor->email)->send(
                new RequestDeletedNotification($requestData, $requestor->name, $requestingOffice->name)
            );

            Log::info('Deleted notification sent to ' . $requestor->email . ' for request ' . $request->request_id);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send deleted notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification when a request is transmitted
     */
    public static function sendTransmittedNotification(Request $request): bool
    {
        try {
            $requestingOffice = $request->requestingOffice;
            
            if (!$requestingOffice || !$requestingOffice->requestor_obj) {
                Log::warning('Cannot send transmitted notification: No requestor found for request ' . $request->request_id);
                return false;
            }

            $requestor = $requestingOffice->requestor_obj;
            
            if (empty($requestor->email)) {
                Log::warning('Cannot send transmitted notification: Requestor has no email for request ' . $request->request_id);
                return false;
            }

            $transmittedOffice = $request->transmittedOffice;
            $transmittedOfficeName = $transmittedOffice ? $transmittedOffice->name : 'Unknown Office';

            $requestData = [
                'dts_tracker_number' => $request->dts_tracker_number,
                'dts_date' => $request->dts_date,
                'nature_of_request' => $request->nature_of_request,
                'amount' => $request->amount,
                'signed_chief_date' => $request->signed_chief_date,
                'date_transmitted' => $request->date_transmitted,
                'remarks' => $request->remarks,
            ];

            Mail::to($requestor->email)->send(
                new RequestTransmittedNotification($requestData, $requestor->name, $requestingOffice->name, $transmittedOfficeName)
            );

            Log::info('Transmitted notification sent to ' . $requestor->email . ' for request ' . $request->request_id);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send transmitted notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification when a request is returned
     */
    public static function sendReturnedNotification(Request $request): bool
    {
        try {
            $requestingOffice = $request->requestingOffice;
            
            if (!$requestingOffice || !$requestingOffice->requestor_obj) {
                Log::warning('Cannot send returned notification: No requestor found for request ' . $request->request_id);
                return false;
            }

            $requestor = $requestingOffice->requestor_obj;
            
            if (empty($requestor->email)) {
                Log::warning('Cannot send returned notification: Requestor has no email for request ' . $request->request_id);
                return false;
            }

            $returnedToOffice = $request->transmittedOffice;
            $returnedToOfficeName = $returnedToOffice ? $returnedToOffice->name : 'Originating Office';

            $requestData = [
                'dts_tracker_number' => $request->dts_tracker_number,
                'dts_date' => $request->dts_date,
                'nature_of_request' => $request->nature_of_request,
                'amount' => $request->amount,
                'date_transmitted' => $request->date_transmitted,
                'remarks' => $request->remarks,
            ];

            Mail::to($requestor->email)->send(
                new RequestReturnedNotification($requestData, $requestor->name, $requestingOffice->name, $returnedToOfficeName)
            );

            Log::info('Returned notification sent to ' . $requestor->email . ' for request ' . $request->request_id);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send returned notification: ' . $e->getMessage());
            return false;
        }
    }
}

