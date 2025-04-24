<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SiteSettingController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $settings = SiteSetting::all();
            
            if ($settings->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No settings found'
                ]);
            }

            $formattedSettings = $settings->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->value];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSettings
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching settings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            Log::info('Received settings update request:', $request->all());

            $validator = Validator::make($request->all(), [
                'site_name' => 'sometimes|required|string|max:255',
                'site_logo' => 'sometimes|required|string|max:2048',
                'site_description' => 'sometimes|required|string',
                'contact_email' => 'sometimes|required|email',
                'contact_phone' => 'sometimes|required|string',
                'social_links' => 'sometimes|required|array',
                'social_links.facebook' => 'sometimes|required|string|url',
                'social_links.twitter' => 'sometimes|required|string|url',
                'social_links.instagram' => 'sometimes|required|string|url'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            foreach ($request->all() as $key => $value) {
                try {
                    SiteSetting::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => is_array($value) ? json_encode($value) : $value,
                            'type' => is_array($value) ? 'array' : 'string'
                        ]
                    );
                } catch (\Exception $e) {
                    Log::error("Error updating setting {$key}: " . $e->getMessage());
                    throw $e;
                }
            }

            $updatedSettings = SiteSetting::all()->mapWithKeys(function ($setting) {
                $value = $setting->type === 'array' ? json_decode($setting->value, true) : $setting->value;
                return [$setting->key => $value];
            });

            return response()->json([
                'success' => true,
                'data' => $updatedSettings,
                'message' => 'Settings updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating settings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating settings: ' . $e->getMessage()
            ], 500);
        }
    }
} 